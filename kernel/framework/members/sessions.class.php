<?php
/*##################################################
 *                                sessions.class.php
 *                            -------------------
 *   begin                : July 04, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *   Sessions v4.0.0 
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Constantes de base.
define('AUTOCONNECT', true);
define('NO_AUTOCONNECT', false);

class Sessions
{
	## Public Attribute ##
	var $data = array(); //Tableau contenant les informations de session.
	var $session_mod = 0; //Variable contenant le mode de session � utiliser pour r�cup�rer les infos.
	
	
	## Public Methods ##
	//Lancement de la session apr�s r�cup�ration des informations par le formulaire de connexion.
	function Session_begin($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false)
	{
		global $CONFIG, $Sql;
		
		$error = '';
		$cookie_on = false;
		$session_script = addslashes($session_script);
		$session_script_title = addslashes($session_script_title);
		
		########Insertion dans le compteur si l'ip est inconnue.########
		$check_ip = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."compteur WHERE ip = '" . USER_IP . "'", __LINE__, __FILE__);
		$_include_once = empty($check_ip) && ($this->check_robot(USER_IP) === false);
		if( $_include_once )
		{
			//R�cup�ration forc�e de la valeur du total de visites, car probl�me de CAST avec postgresql.
			$Sql->Query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."compteur SET ip = ip + 1, time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', total = total + 1 WHERE id = 1", __LINE__, __FILE__);
			$Sql->Query_inject("INSERT ".LOW_PRIORITY." INTO ".PREFIX."compteur (ip, time, total) VALUES('" . USER_IP . "', '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', 0)", __LINE__, __FILE__);
			
			//Mise � jour du last_connect, pour un membre qui vient d'arriver sur le site.
			if( $user_id !== '-1' ) 
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		}
		
		//On lance les stats.
		include_once(PATH_TO_ROOT . '/kernel/save_stats.php');
			
		########G�n�ration d'un ID de session unique########
		$session_uniq_id = md5(uniqid(mt_rand(), true)); //On g�n�re un num�ro de session al�atoire.
			
		########Session existe t-elle?#########		
		$this->session_garbage_collector();	//On nettoie avant les sessions p�rim�es.

		if( $user_id !== '-1' )
		{
			//Suppression de la session visiteur g�n�r�e avant l'enregistrement!
			$Sql->Query_inject("DELETE FROM ".PREFIX."sessions WHERE session_ip = '" . USER_IP . "' AND user_id = -1", __LINE__, __FILE__);
			
			//En cas de double connexion, on supprime le cookie et la session associ�e de la base de donn�es!
			if( isset($_COOKIE[$CONFIG['site_cookie'] . '_data']) ) 
				setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/');
			$Sql->Query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//R�cup�ration password BDD
			$password_m = $Sql->Query("SELECT password FROM ".PREFIX."member WHERE user_id = '" . $user_id . "' AND user_warning < 100 AND '" . time() . "' - user_ban >= 0", __LINE__, __FILE__);
			
			if( !empty($password) && $password === $password_m ) //Succ�s!
			{
				$Sql->Query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', '" . $user_id . "', '" . $level . "', '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '')", __LINE__, __FILE__);				
				$cookie_on = true; //G�n�ration du cookie!			
			}
			else //Session visiteur, echec!
			{
				$Sql->Query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0')", __LINE__, __FILE__);		
				$delay_ban = $Sql->Query("SELECT user_ban FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				if( (time - $delay_ban) >= 0 )
					$error = 'echec';
				else
					$error = $delay_ban;		
			}	
		}
		else //Session visiteur valide.
		{
			$Sql->Query_inject("INSERT INTO ".PREFIX."sessions VALUES('" . $session_uniq_id . "', -1, -1, '" . USER_IP . "', '" . time() . "', '" . $session_script . "', '" . $session_script_get . "', '" . $session_script_title . "', '0')", __LINE__, __FILE__);
		}
		
		########G�n�ration du cookie de session########
		if( $cookie_on === true )
		{
			$data = array();
			$data['user_id'] = isset($user_id) ? numeric($user_id) : -1;
			$data['session_id'] = $session_uniq_id;
			
			setcookie($CONFIG['site_cookie'].'_data', serialize($data), time() + 31536000, '/');
			
			########G�n�ration du cookie d'autoconnection########
			if( $autoconnect === true )
			{
				$session_autoconnect['user_id'] = $user_id;				
				$session_autoconnect['pwd'] = $password;
				
				setcookie($CONFIG['site_cookie'].'_autoconnect', serialize($session_autoconnect), time() + 31536000, '/');
			}
		}
		
		return $error;
	}	
	
	//R�cup�ration des informations sur le membre.
	function Session_info()
	{
		global $Sql, $CONFIG;
		
		$this->get_session_id(); //R�cup�ration des identifiants de session.
				
		########Valeurs � retourner########
		$userdata = array();
		if( $this->data['user_id'] !== -1 && !empty($this->data['user_id']) )
		{	
			//R�cup�re �galement les champs membres suppl�mentaires
			$result = $Sql->Query_inject("SELECT m.user_id AS m_user_id, m.login, m.level, m.user_groups, m.user_lang, m.user_theme, m.user_mail, m.user_pm, m.user_editor, m.user_timezone, m.user_avatar avatar, m.user_readonly, me.*
			FROM ".PREFIX."member m
			LEFT JOIN ".PREFIX."member_extend me ON me.user_id = '" . $this->data['user_id'] . "'
			WHERE m.user_id = '" . $this->data['user_id'] . "'", __LINE__, __FILE__);	
			$userdata = $Sql->Sql_fetch_assoc($result);
			$this->data = array_merge($userdata, $this->data); //Fusion des deux tableaux.
		}	
		
		$this->data['user_id'] = isset($userdata['m_user_id']) ? (int)$userdata['m_user_id'] : -1;
		$this->data['login'] = isset($userdata['login']) ? $userdata['login'] : '';	
		$this->data['level'] = isset($userdata['level']) ? (int)$userdata['level'] : -1;		
		$this->data['user_groups'] = isset($userdata['user_groups']) ? $userdata['user_groups'] : '';
		$this->data['user_lang'] = isset($userdata['user_lang']) ? $userdata['user_lang'] : ''; //Langue membre
		$this->data['user_theme'] = isset($userdata['user_theme']) ? $userdata['user_theme'] : ''; //Th�me membre		
		$this->data['user_mail'] = isset($userdata['user_mail']) ? $userdata['user_mail'] : '';
		$this->data['user_pm'] = isset($userdata['user_pm']) ? $userdata['user_pm'] : '0';	
		$this->data['user_readonly'] = isset($userdata['user_readonly']) ? $userdata['user_readonly'] : '0';
		$this->data['user_editor'] = !empty($userdata['user_editor']) ? $userdata['user_editor'] : $CONFIG['editor'];
		$this->data['user_timezone'] = isset($userdata['user_timezone']) ? $userdata['user_timezone'] : $CONFIG['timezone'];
		$this->data['avatar'] = isset($userdata['avatar']) ? $userdata['avatar'] : '';
	}
	
	//V�rification de la session.
	function Session_check($session_script_title)
	{
		global $CONFIG, $Sql;

		$session_script = str_replace(DIR, '', SCRIPT);
		$session_script_get = QUERY_STRING;
		if( !empty($this->data['session_id']) && $this->data['user_id'] > 0 )
		{
			//On modifie le session_flag pour forcer mysql � modifier l'entr�e, pour prendre en compte la mise � jour par mysql_affected_rows().
			$resource = $Sql->Query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."sessions SET session_ip = '" . USER_IP . "', session_time = '" . time() . "', session_script = '" . addslashes($session_script) . "', session_script_get = '" . addslashes($session_script_get) . "', session_script_title = '" . addslashes($session_script_title) . "', session_flag = 1 - session_flag WHERE session_id = '" . $this->data['session_id'] . "' AND user_id = '" . $this->data['user_id'] . "'", __LINE__, __FILE__);
			
			if( $Sql->Sql_affected_rows($resource, "SELECT COUNT(*) FROM ".PREFIX."sessions WHERE session_id = '" . $this->data['session_id'] . "' AND user_id = '" . $this->data['user_id'] . "'") == 0 ) //Aucune session lanc�e.
			{
				if( $this->get_session_autoconnect($session_script, $session_script_get, $session_script_title) === false )
				{					
					if( isset($_COOKIE[$CONFIG['site_cookie'].'_data']) )
						setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //Destruction cookie.						
					
					if( QUERY_STRING != '' )
						redirect(HOST . SCRIPT . '?' . QUERY_STRING);
					else
						redirect(HOST . SCRIPT);
				}
			}
		}
		else //Visiteur
		{
			//On modifie le session_flag pour forcer mysql � modifier l'entr�e, pour prendre en compte la mise � jour par mysql_affected_rows().
			$resource = $Sql->Query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."sessions SET session_ip = '" . USER_IP . "', session_time = '" . (time() + 1) . "', session_script = '" . addslashes($session_script) . "', session_script_get = '" . addslashes($session_script_get) . "', session_script_title = '" . addslashes($session_script_title) . "', session_flag = 1 - session_flag WHERE user_id = -1 AND session_ip = '" . USER_IP . "'", __LINE__, __FILE__);
			
			if( $Sql->Sql_affected_rows($resource, "SELECT COUNT(*) FROM ".PREFIX."sessions WHERE user_id = -1 AND session_ip = '" . USER_IP . "'") == 0 ) //Aucune session lanc�e.
			{
				if( isset($_COOKIE[$CONFIG['site_cookie'].'_data']) )
					setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //Destruction cookie.
				$this->Session_begin('-1', '', '-1', $session_script, $session_script_get, $session_script_title); //Session visiteur
				
				if( QUERY_STRING != '' )
					redirect(HOST . SCRIPT . '?' . QUERY_STRING);
				else
					redirect(HOST . SCRIPT);	
			}
		}
	}
	
	//Fin de la session
	function Session_end()
	{
		global $CONFIG, $Sql;
			
		$this->get_session_id();
			
		//On supprime la session de la bdd.
		$Sql->Query_inject("DELETE FROM ".PREFIX."sessions WHERE session_id = '" . $this->data['session_id'] . "'", __LINE__, __FILE__);
		
		if( isset($_COOKIE[$CONFIG['site_cookie'].'_data']) ) //Session cookie?
			setcookie($CONFIG['site_cookie'].'_data', '', time() - 31536000, '/'); //On supprime le cookie.		
		
		if( isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']) )
			setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
		
		$this->session_garbage_collector();
	}
	
	
	## Private M�thods ##
	//R�cup�ration de session en autoconnect.
	function get_session_autoconnect($session_script, $session_script_get, $session_script_title)
	{
		global $CONFIG, $Sql;
				
		########Cookie Existe?########
		if( isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']) )
		{
			$session_autoconnect = isset($_COOKIE[$CONFIG['site_cookie'].'_autoconnect']) ? unserialize(stripslashes($_COOKIE[$CONFIG['site_cookie'].'_autoconnect'])) : array();
			$session_autoconnect['user_id'] = !empty($session_autoconnect['user_id']) ? numeric($session_autoconnect['user_id']) : ''; //Validit� user id?.				
			$session_autoconnect['pwd'] = !empty($session_autoconnect['pwd']) ? strprotect($session_autoconnect['pwd']) : ''; //Validit� password.
			$level = $Sql->Query("SELECT level FROM ".PREFIX."member WHERE user_id = '" . $session_autoconnect['user_id'] . "' AND password = '" . $session_autoconnect['pwd'] . "'", __LINE__, __FILE__);
			
			if( !empty($session_autoconnect['user_id']) && !empty($session_autoconnect['pwd']) && isset($level) )
			{
				$error_report = $this->Session_begin($session_autoconnect['user_id'], $session_autoconnect['pwd'], $level, $session_script, $session_script_get, $session_script_title); //Lancement d'une session utilisateur.
				
				//Gestion des erreurs pour �viter un brute force.
				if( $error_report === 'echec' )
				{
					$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					$test_connect = $Sql->Query("SELECT test_connect FROM ".PREFIX."member WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
					
					redirect(HOST . DIR . '/member/error.php?flood=' . (5 - ($test_connect + 1)));
				}
				elseif( is_numeric($error_report) )
				{
					setcookie($CONFIG['site_cookie'].'_autoconnect', '', time() - 31536000, '/'); //On supprime le cookie.
					
					$error_report = ceil($error_report/60);
					redirect(HOST . DIR . '/member/error.php?ban=' . $error_report);
				}
				else //Succ�s on recharge la page.
				{
					//On met � jour la date de derni�re connexion. 
					$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "' WHERE user_id = '" . $session_autoconnect['user_id'] . "'", __LINE__, __FILE__);
					
					if( QUERY_STRING != '' )
						redirect(HOST . SCRIPT . '?' . QUERY_STRING);
					else
						redirect(HOST . SCRIPT);					
				}
			}
			else
				return false;
		}	
		return false;
	}
	
	//R�cup�ration des l'identifiants de session.
	function get_session_id()
	{
		global $CONFIG, $Sql;
		
		//Suppression d'�ventuelles donn�es dans ce tableau.
		$this->data = array();
		
		$this->data['session_id'] = '';
		$this->data['user_id'] = -1;
		$this->session_mod = 0;

		########Cookie Existe?########
		if( isset($_COOKIE[$CONFIG['site_cookie'].'_data']) )
		{
			//Redirection pour supprimer les variables de session en clair dans l'url.
			if( isset($_GET['sid']) && isset($_GET['suid']) )
			{
				$query_string = preg_replace('`&?sid=(.*)&suid=(.*)`', '', QUERY_STRING);
				redirect(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : ''));				
			}
			
			$session_data = isset($_COOKIE[$CONFIG['site_cookie'].'_data']) ? unserialize(stripslashes($_COOKIE[$CONFIG['site_cookie'].'_data'])) : array();
			
			$this->data['session_id'] = isset($session_data['session_id']) ? strprotect($session_data['session_id']) : ''; //Validit� du session id.
			$this->data['user_id'] = isset($session_data['user_id']) ? numeric($session_data['user_id']) : ''; //Validit� user id?
		}	
		########SID Existe?########
		elseif( isset($_GET['sid']) && isset($_GET['suid']) )
		{
			$this->data['session_id'] = !empty($_GET['sid']) ? strprotect($_GET['sid']) : ''; //Validit� du session id.
			$this->data['user_id'] = !empty($_GET['suid']) ? numeric($_GET['suid']) : ''; //Validit� user id?
			$this->session_mod = 1;
		}
	}
	
	//Suppression des sessions expir�e par le garbage collector.
	function session_garbage_collector() 
	{
		global $CONFIG, $Sql;
			
		$Sql->Query_inject("DELETE 
		FROM ".PREFIX."sessions 
		WHERE session_time < '" . (time() - $CONFIG['site_session']) . "' 
		OR (session_time < '" . (time() - $CONFIG['site_session_invit']) . "' AND user_id = -1)", __LINE__, __FILE__);
	}
	
	//D�tecte les principaux robots par plage ip, retourne leurs noms, et enregistre le nombre et l'heure de passages dans un fichier texte.
	function check_robot($user_ip)
	{
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		if( preg_match('`(w3c|http:\/\/|bot|spider|Gigabot|gigablast.com)+`i', $_SERVER['HTTP_USER_AGENT']) )
			return 'unknow_bot';
			
		//Chaque ligne repr�sente une plage ip.
		$plage_ip = array(
			'66.249.64.0' => '66.249.95.255',
			'209.85.128.0' => '209.85.255.255', 
			'65.52.0.0' => '65.55.255.255',
			'207.68.128.0' => '207.68.207.255',
			'66.196.64.0' => '66.196.127.255',
			'68.142.192.0' => '68.142.255.255',
			'72.30.0.0' => '72.30.255.255',
			'193.252.148.0' => '193.252.148.255',
			'66.154.102.0' => '66.154.103.255',
			'209.237.237.0' => '209.237.238.255',
			'193.47.80.0' => '193.47.80.255'
		);

		//Nom des bots associ�s.
		$array_robots = array(
			'Google bot',
			'Google bot',
			'Msn bot',
			'Msn bot',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Voila',
			'Gigablast',
			'Ia archiver',
			'Exalead'
		);
		
		//Ip de l'utilisateur au format num�rique.
		$user_ip = ip2long($user_ip);	

		//On explore le tableau pour identifier les robots
		$r = 0;
		foreach($plage_ip as $start_ip => $end_ip)
		{	
			$start_ip = ip2long($start_ip);
			$end_ip = ip2long($end_ip);			
			
			//Comparaison pour chaque partie de l'ip, si l'une d'entre elle est fausse l'instruction est stop�e.
			if( $user_ip >= $start_ip && $user_ip <= $end_ip ) 
			{
				//Insertion dans le fichier texte des visites des robots.
				$file_path = PATH_TO_ROOT . '/cache/robots.txt';
				if( !file_exists($file_path) ) 
				{
					$file = @fopen($file_path, 'w+'); //Si le fichier n'existe pas on le cr�e avec droit d'�criture et lecture.
					@fwrite($file, serialize(array())); //On ins�re un tableau vide.
					@fclose($file);
				}

				if( is_file($file_path) && is_writable($file_path) ) //Fichier accessible en �criture.
				{
					$robot = $array_robots[$r]; //Nom du robot.
					$time = gmdate_format('YmdHis', time(), TIMEZONE_SYSTEM); //Date et heure du dernier passage!
					
					$line = file($file_path);
					$data = unserialize($line[0]); //Renvoi la premi�re ligne du fichier (le array pr�c�dement cr�e).
					
					if( !isset($data[$robot]) )
						$data[$robot] = $robot . '/1/' . $time; //Cr�ation du array contenant les valeurs.
						
					$array_info = explode('/', $data[$robot]); //R�cuperation des valeurs.
					if( $array_robots[$r] === $array_info[0] ) //Robo repasse.
					{
						$array_info[1]++; //Nbr de  visite.
						$array_info[2] = $time; //Date Derni�re visite
						$data[$robot] = implode('/', $array_info);
					}
					else
						$data[$robot] = $robot . '/1/' . $time; //Cr�ation du array contenant les valeurs.
					
					$file = @fopen($file_path, 'r+');
					fwrite($file, serialize($data)); //On stock le tableau dans le fichier de donn�es
					fclose($file);
				}			
				return $array_robots[$r]; //On retourne le nom du robot d'exploration.
			}
			$r++;
		}	
		return false;
	}
}

?>