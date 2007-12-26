<?php
/*##################################################
 *                               admin_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$check_advanced = !empty($_GET['adv']) ? true : false;

//Variables serveur.
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
if( !$server_path )
	$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
$server_path = trim(str_replace('/admin', '', dirname($server_path)));
$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

//Si c'est confirm� on execute
if( !empty($_POST['valid']) && empty($_POST['cache']) )
{
	//Gestion de la page de d�marrage.
	if( !empty($_POST['start_page'])  )
		$start_page = securit($_POST['start_page']);
	elseif( !empty($_POST['start_page2']) )
		$start_page = securit($_POST['start_page2']);
		
	$config = array();	 
	$config['server_name'] = $CONFIG['server_name'];
	$config['server_path'] = $CONFIG['server_path'];	
	$config['site_name'] = !empty($_POST['site_name']) ? stripslashes(securit($_POST['site_name'])) : '';	
	$config['site_desc'] = !empty($_POST['site_desc']) ? stripslashes(securit($_POST['site_desc'])) : '';    
	$config['site_keyword'] = !empty($_POST['site_keyword']) ? stripslashes(securit($_POST['site_keyword'])) : '';	
	$config['start'] = $CONFIG['start'];
	$config['version'] = $CONFIG['version'];
	$config['lang'] = !empty($_POST['lang']) ? stripslashes(securit($_POST['lang'])) : ''; 
	$config['theme'] = !empty($_POST['theme']) ? stripslashes(securit($_POST['theme'])) : 'main'; //main par defaut. 
	$config['start_page'] = !empty($start_page) ? stripslashes($start_page) : '/member/member.php';
	$config['maintain'] = $CONFIG['maintain'];
	$config['maintain_delay'] = $CONFIG['maintain_delay'];
	$config['maintain_text'] = $CONFIG['maintain_text'];
	$config['rewrite'] = $CONFIG['rewrite'];
	$config['com_popup'] = $CONFIG['com_popup'];
	$config['compteur'] = isset($_POST['compteur']) ? numeric($_POST['compteur']) : 0;
	$config['ob_gzhandler'] = $CONFIG['ob_gzhandler'];
	$config['site_cookie'] = $CONFIG['site_cookie'];
	$config['site_session'] = $CONFIG['site_session'];				
	$config['site_session_invit'] = $CONFIG['site_session_invit'];	
	$config['mail'] = !empty($_POST['mail']) ? stripslashes(securit($_POST['mail'])) : '';  
	$config['activ_mail'] = isset($_POST['activ_mail']) ? numeric($_POST['activ_mail']) : '1'; //activ� par defaut. 
	$config['sign'] = !empty($_POST['sign']) ? stripslashes(securit($_POST['sign'])) : '';   
	$config['anti_flood'] = isset($_POST['anti_flood']) ? numeric($_POST['anti_flood']) : 0;
	$config['delay_flood'] = !empty($_POST['delay_flood']) ? numeric($_POST['delay_flood']) : 0;
	$config['unlock_admin'] = $CONFIG['unlock_admin'];
	$config['pm_max'] = isset($_POST['pm_max']) ? numeric($_POST['pm_max']) : 25;

	if( !empty($config['theme']) && !empty($CONFIG['lang']) ) //Nom de serveur obligatoire
	{
		$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'config'", __LINE__, __FILE__);
		
		//Modification de la page de d�marrage.
		if( !empty($start_page) )
		{
			//Ecriture du fichier de redirection
			$file_path = '../index.php';
			delete_file($file_path); //Rippe le fichier
			
			$start_page = (substr($start_page, 0, 1) == '/') ? HOST . DIR . $start_page : $start_page;
			
			$file = @fopen($file_path, 'w+'); //On cr�e le fichier avec droit d'�criture et lecture.
			@fwrite($file, '<?php header(\'location: ' . $start_page . '\'); ?>');
			@fclose($file);
		}
		
		###### R�g�n�ration du cache $CONFIG #######
		$cache->generate_file('config');
		
		header('location:' . HOST . SCRIPT);
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_config.php?error=incomplete#errorh');
		exit;
	}
}
elseif( !empty($check_advanced) && empty($_POST['advanced']) )
{
	$template->set_filenames(array(
		'admin_config2' => '../templates/' . $CONFIG['theme'] . '/admin/admin_config2.tpl'
	));	
	
	//V�rification serveur de l'activation du mod_rewrite.
	if( function_exists('apache_get_modules') )
	{	
		$get_rewrite = apache_get_modules();
		$check_rewrite = (!empty($get_rewrite[5])) ? '<span class="success">' . $LANG['yes'] . '</span>' : '<span class="failure">' . $LANG['no'] . '</span>';
	}
	else
		$check_rewrite = '<span class="unspecified">' . $LANG['undefined'] . '</span>';
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( isset($_GET['mail']) )
		$errorh->error_handler($LANG['unlock_admin_confirm'], E_USER_NOTICE);
		
	$template->assign_vars(array(
		'SERVER_NAME' => !empty($CONFIG['server_name']) ? $CONFIG['server_name'] : $server_name,
		'SERVER_PATH' => isset($CONFIG['server_path']) ? $CONFIG['server_path'] : $server_path,
		'CHECKED' => ($CONFIG['rewrite'] == '1') ? 'checked="checked"' : '',
		'UNCHECKED' => ($CONFIG['rewrite'] == '0') ? 'checked="checked"' : '',
		'CHECK_REWRITE' => $check_rewrite,
		'GZ_DISABLED' => ((!function_exists('ob_gzhandler') || !@extension_loaded('zlib')) ? 'disabled="disabled"' : ''),
		'GZHANDLER_ENABLED' => ($CONFIG['ob_gzhandler'] == 1 && (function_exists('ob_gzhandler') && @extension_loaded('zlib'))) ? 'checked="checked"' : '',
		'GZHANDLER_DISABLED' => ($CONFIG['ob_gzhandler'] == 0) ? 'checked="checked"' : '',
		'SITE_COOKIE' => !empty($CONFIG['site_cookie']) ? $CONFIG['site_cookie'] : 'session',
		'SITE_SESSION' => !empty($CONFIG['site_session']) ? $CONFIG['site_session'] : '3600',
		'SITE_SESSION_VISIT' => !empty($CONFIG['site_session_invit']) ? $CONFIG['site_session_invit'] : '300',		
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_REQUIRE_SERV' => $LANG['require_serv'],
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_REQUIRE_COOKIE_NAME' => $LANG['require_cookie_name'],
		'L_REQUIRE_SESSION_TIME' => $LANG['require_session_time'],
		'L_REQUIRE_SESSION_INVIT' => $LANG['require_session_invit'],
		'L_REQUIRE' => $LANG['require'],
		'L_SERV_NAME' => $LANG['serv_name'],
		'L_SERV_NAME_EXPLAIN' => $LANG['serv_name_explain'],
		'L_SERV_PATH' => $LANG['serv_path'],
		'L_SERV_PATH_EXPLAIN' => $LANG['serv_path_explain'],
		'L_CONFIG' => $LANG['configuration'],
		'L_CONFIG_MAIN' => $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_REWRITE' => $LANG['rewrite'],
		'L_EXPLAIN_REWRITE' => $LANG['explain_rewrite'], 
		'L_REWRITE_SERVER' => $LANG['server_rewrite'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		'L_USER_CONNEXION' => $LANG['user_connexion'],
		'L_COOKIE_NAME' => $LANG['cookie_name'],
		'L_SESSION_TIME' => $LANG['session_time'],
		'L_SESSION_TIME_EXPLAIN' => $LANG['session_time_explain'],
		'L_SESSION_INVIT' => $LANG['session invit'],
		'L_SESSION_INVIT_EXPLAIN' => $LANG['session invit_explain'],
		'L_MISC' => $LANG['miscellaneous'],	
		'L_ACTIV_GZHANDLER' => $LANG['activ_gzhandler'],
		'L_ACTIV_GZHANDLER_EXPLAIN' => $LANG['activ_gzhandler_explain'],
		'L_CONFIRM_UNLOCK_ADMIN' => $LANG['confirm_unlock_admin'],
		'L_UNLOCK_ADMIN' => $LANG['unlock_admin'],
		'L_UNLOCK_ADMIN_EXPLAIN' => $LANG['unlock_admin_explain'],
		'L_UNLOCK_LINK' => $LANG['send_unlock_admin'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']	
	));
	
	$template->pparse('admin_config2');
}
elseif( !empty($_POST['advanced']) )
{
	$CONFIG['rewrite'] = 1;
	$CONFIG['server_name'] = !empty($_POST['server_name']) ? stripslashes(securit($_POST['server_name'])) : stripslashes(securit($server_name)); 
	$CONFIG['server_path'] = !empty($_POST['server_path']) ? stripslashes(securit($_POST['server_path'])) : '';  
	$CONFIG['ob_gzhandler'] = (!empty($_POST['ob_gzhandler'])&& function_exists('ob_gzhandler') && @extension_loaded('zlib')) ? 1 : 0;
	$CONFIG['site_cookie'] = !empty($_POST['site_cookie']) ? stripslashes(securit($_POST['site_cookie'])) : 'session'; //Session par defaut.
	$CONFIG['site_session'] = !empty($_POST['site_session']) ? numeric($_POST['site_session']) : 3600; //Valeur par defaut � 3600.					
	$CONFIG['site_session_invit'] = !empty($_POST['site_session_invit']) ? numeric($_POST['site_session_invit']) : 300; //Dur�e compteur 5min par defaut.	
	
	if( !empty($CONFIG['server_name']) && !empty($CONFIG['site_cookie']) && !empty($CONFIG['site_session']) && !empty($CONFIG['site_session_invit'])  ) //Nom de serveur obligatoire
	{
		if( !empty($_POST['rewrite_engine']) && !strpos($_SERVER['SERVER_NAME'], 'free.fr') ) //Activation.
		{
			$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			###### R�g�n�ration du cache $CONFIG #######
			$cache->generate_file('config');
			
			//R�g�n�ration du htaccess.
			$cache->generate_htaccess(); 
		}
		else
		{
			$CONFIG['rewrite'] = 0;
			$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			###### R�g�n�ration du cache $CONFIG #######
			$cache->generate_file('config');
			
			//R�g�n�ration du htaccess.
			$cache->generate_htaccess();
		}
	
		header('location:' . HOST . DIR . '/admin/admin_config.php?adv=1');
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_config.php?adv=1&error=incomplete#errorh');
		exit;
	}	
}
else //Sinon on rempli le formulaire	 
{		
	$template->set_filenames(array(
		'admin_config' => '../templates/' . $CONFIG['theme'] . '/admin/admin_config.tpl'
	));

	//On recup�re toute les informations supplementaires.
	$cache->load_file('config', RELOAD_CACHE);

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	$template->assign_vars(array(		
		'THEME' => $CONFIG['theme'],
		'SITE_NAME' => !empty($CONFIG['site_name']) ? $CONFIG['site_name'] : '',
		'SITE_DESCRIPTION' => !empty($CONFIG['site_desc']) ? $CONFIG['site_desc'] : '',
		'SITE_KEYWORD' => !empty($CONFIG['site_keyword']) ? $CONFIG['site_keyword'] : '',		
		'MAIL' => !empty($CONFIG['mail']) ? $CONFIG['mail'] : '',   
		'SIGN' => !empty($CONFIG['sign']) ? $CONFIG['sign'] : '',
		'DELAY_FLOOD' => !empty($CONFIG['delay_flood']) ? $CONFIG['delay_flood'] : '7',
		'NOTE_MAX' => isset($CONFIG['note_max']) ? $CONFIG['note_max'] : '10',
		'PM_MAX' => isset($CONFIG['pm_max']) ? $CONFIG['pm_max'] : '50',
		'COMPTEUR_ENABLED' => ($CONFIG['compteur'] == 1) ? 'checked="checked"' : '',
		'COMPTEUR_DISABLED' => ($CONFIG['compteur'] == 0) ? 'checked="checked"' : '',
		'FLOOD_ENABLED' => ($CONFIG['anti_flood'] == 1) ? 'checked="checked"' : '',
		'FLOOD_DISABLED' => ($CONFIG['anti_flood'] == 0) ? 'checked="checked"' : '',
		'MAIL_ENABLED' => ($CONFIG['activ_mail'] == 1) ? 'checked="checked"' : '','MAIL_ENABLED' => ($CONFIG['activ_mail'] == 1) ? 'checked="checked"' : '',
		'MAIL_DISABLED' => ($CONFIG['activ_mail'] == 0) ? 'checked="checked"' : '',
		'L_REQUIRE_VALID_MAIL' => $LANG['require_mail'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONFIG' => $LANG['configuration'],
		'L_CONFIG_MAIN' => $LANG['config_main'],
		'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
		'L_SITE_NAME' => $LANG['site_name'],
		'L_SITE_DESC' => $LANG['site_desc'],
		'L_SITE_KEYWORDS' => $LANG['site_keyword'],
		'L_DEFAUT_LANGUAGES' => $LANG['defaut_language'],
		'L_DEFAUT_THEME' => $LANG['defaut_theme'],
		'L_START_PAGE' => $LANG['start_page'],
		'L_OTHER' => $LANG['other_start_page'],
		'L_COMPT' => $LANG['compt'],
		'L_REWRITE' => $LANG['rewrite'],
		'L_POST_MANAGEMENT' => $LANG['post_management'],
		'L_PM_MAX' => $LANG['pm_max'],
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_ANTI_FLOOD' => $LANG['anti_flood'],
		'L_INT_FLOOD' => $LANG['int_flood'],
		'L_PM_MAX_EXPLAIN' => $LANG['pm_max_explain'],
		'L_ANTI_FLOOD_EXPLAIN' => $LANG['anti_flood_explain'],
		'L_INT_FLOOD_EXPLAIN' => $LANG['int_flood_explain'],
		'L_EMAIL_MANAGEMENT' => $LANG['email_management'],
		'L_EMAIL_ADMIN' => $LANG['email_admin'],
		'L_EMAIL_ADMIN_STATUS' => $LANG['admin_admin_status'],
		'L_EMAIL_ADMIN_SIGN' => $LANG['admin_sign'],			
		'L_EMAIL_ADMIN_EXPLAIN' => $LANG['email_admin_explain'],
		'L_EMAIL_ADMIN_STATUS_EXPLAIN' => $LANG['admin_admin_status_explain'],
		'L_EMAIL_ADMIN_SIGN_EXPLAIN' => $LANG['admin_sign_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']		
	));

		
	//Gestion langue par d�faut.
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$lang_array = array();
		$dh = @opendir( $rep);
		while( ! is_bool($lang = readdir($dh)) )
		{	
			if( !preg_match('`\.`', $lang) )
				$lang_array[] = $lang; //On cr�e un tableau, avec les different fichiers.				
		}	
		closedir($dh); //On ferme le dossier
		
		$lang_array_bdd = array();
		$result = $sql->query_while("SELECT lang 
		FROM ".PREFIX."lang", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			//On recherche les cl�es correspondante � celles trouv�e dans la bdd.
			if( array_search($row['lang'], $lang_array) !== false)
				$lang_array_bdd[] = $row['lang']; //On ins�re ces cl�es dans le tableau.
		}
		$sql->close($result);
		
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		foreach($lang_array_bdd as $lang_key => $lang_value) //On effectue la recherche dans le tableau.
		{
			$lang_info = @parse_ini_file('../lang/' . $lang_value . '/config.ini');
			if( $lang_info )
			{
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $lang_value;
				
				$array_identifier .= 'array_identifier[\'' . $lang_value . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
				$selected = '';
				if( $lang_value == $CONFIG['lang'] )
				{
					$selected = 'selected="selected"';
					$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
				}
				$template->assign_block_vars('select_lang', array(
					'LANG' => '<option value="' . $lang_value . '" ' . $selected . '>' . $lang_name . '</option>'
				));
			}
		}
		$template->assign_vars(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier
		));
	}
	
	//On recup�re les dossier des th�mes contents dans le dossier templates.
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$fichier_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($theme = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $theme) )
				$fichier_array[] = $theme; //On cr�e un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier
		
		$theme_array_bdd = array();
		$result = $sql->query_while("SELECT theme 
		FROM ".PREFIX."themes", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			//On recherche les cl�es correspondante � celles trouv�e dans la bdd.
			if( array_search($row['theme'], $fichier_array) !== false)
				$theme_array_bdd[] = $row['theme']; //On ins�re ces cl�es dans le tableau.
		}
		$sql->close($result);
		
		foreach($theme_array_bdd as $theme_array => $theme_value) //On effectue la recherche dans le tableau.
		{
			$theme_info = @parse_ini_file('../templates/' . $theme_value . '/config/' . $CONFIG['lang'] . '/config.ini');
			if( $theme_info )
			{
				$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $theme_value;
				$selected = $theme_value == $CONFIG['theme'] ? 'selected="selected"' : '';
				$template->assign_block_vars('select', array(
					'THEME' => '<option value="' . $theme_value . '" ' . $selected . '>' . $theme_name . '</option>'
				));
			}
		}
	}
	
	//Pages de d�marrage
	$root = '../';
	if( is_dir($root) ) //Si le dossier existe
	{
		$dh = @opendir($root);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $dir) )
			{
				//D�sormais on v�rifie que le fichier de configuration est pr�sent.
				if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') )
				{
					$config = @parse_ini_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini');
					if( $config['starteable_page'] != '' ) //Module possible comme page de d�marrage.
					{	
						$selected = '';
						if( '/' . $dir . '/' . $config['starteable_page'] == $CONFIG['start_page'] )
							$selected = 'selected="selected"';
						
						$template->assign_block_vars('select_page', array(
							'PAGE' => '<option value="' . '/' . $dir . '/' . $config['starteable_page'] . '" ' . $selected . '>' . $config['name'] . '</option>'
						));					
					}
				}
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	else
	{
		$template->assign_block_vars('select_page', array(
			'PAGE' => '<option value="" selected="selected">' . $LANG['no_module_starteable'] . '</option>'
		));
	}	
	
	$template->pparse('admin_config');
}

//Renvoi du code de d�blocage.
if( !empty($_GET['unlock']) )
{
	include_once('../includes/mail.class.php');
	$mail = new Mail();
	
	$unlock_admin_clean = substr(md5(uniqid(mt_rand(), true)), 0, 18); //G�n�ration de la cl�e d'activation, en cas de verrouillage de l'administration.;
	$unlock_admin = md5($unlock_admin_clean);
	
	$CONFIG['unlock_admin'] = $unlock_admin;
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### R�g�n�ration du cache $CONFIG #######
	$cache->generate_file('config');
	
	$mail->send_mail($session->data['user_mail'], $LANG['unlock_title_mail'], sprintf($LANG['unlock_mail'], $unlock_admin_clean), $CONFIG['mail']);	

	header('location:' . HOST . DIR . '/admin/admin_config.php?adv=1&mail=1');
	exit;
}

include_once('../includes/admin_footer.php');

?>