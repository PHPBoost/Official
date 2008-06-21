<?php
/*##################################################
 *                             cache.class.php
 *                            -------------------
 *   begin                : August 28, 2006
 *   copyright          : (C) 2006 Beno�t Sautel / R�gis Viarre
 *   email                : ben.popeye@phpboost / crowkait@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('RELOAD_CACHE', true);

//Fonction d'importation/exportation de base de donn�e.
class Cache
{
    ## Public Methods ##
	//On v�rifie que le r�pertoire cache existe et est inscriptible
    function Cache()
    {
        if( !is_dir(PATH_TO_ROOT . '/cache') || !is_writable(PATH_TO_ROOT . '/cache') )
        {
            global $Errorh;
		
			//Enregistrement dans le log d'erreur.
			$Errorh->Error_handler('Cache -> Le dossier /cache doit �tre inscriptible, donc en CHMOD 777', E_USER_ERROR, __LINE__, __FILE__);
        }
    }
        
    //Fonction de chargement d'un fichier cache
    function Load_file($file, $reload_cache = false)
    {
		global $Errorh, $Sql;
		
		//On charge le fichier
		$include = !$reload_cache ? !@include_once(PATH_TO_ROOT . '/cache/' . $file . '.php') : !@include(PATH_TO_ROOT . '/cache/' . $file . '.php');
		if( $include )
		{
			if( in_array($file, $this->files) )
			{
				//R�g�n�ration du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois				
				if( !@include(PATH_TO_ROOT . '/cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
			else
			{
				//R�g�n�ration du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if( !@include(PATH_TO_ROOT . '/cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
		}
    }
    
    //G�n�ration de tous les fichiers
    function Generate_all_files()
    {
        foreach( $this->files as $cache_file )
            $this->Generate_file($cache_file);
		
		//G�n�ration de tout les fichiers de cache des modules.
		$this->generate_all_module_files();
		
		$this->Generate_htaccess();
    }
	
    //Fonction d'enregistrement du fichier.
    function Generate_file($file)
    {
		global $Errorh;
		
		$content = $this->{'generate_file_' . $file}();
		$file_path = PATH_TO_ROOT . '/cache/' . $file . '.php';
		@delete_file($file_path); //Supprime le fichier
		if( $handle = @fopen($file_path, 'wb') ) //On cr�e le fichier avec droit d'�criture et lecture.
		{
			@flock($handle, LOCK_EX); //Pose d'un verrou, pour �viter les conflits.
			@fwrite($handle, "<?php\n" . $content . "\n?>");
			@flock($handle, LOCK_UN);
			@fclose;
			
			@chmod($file_path, 0666);
		}
		//Il est l'heure de v�rifier si la g�n�ration a fonctionn�e.
		if( !file_exists($file_path) && filesize($file_path) == '0' )
			$Errorh->Error_handler('Cache -> La g�n�ration du fichier de cache <strong>' . $file . '</strong> a �chou�!', E_USER_ERROR, __LINE__, __FILE__);
    }
    
	//Fonction d'enregistrement du fichier d'un module.
    function Generate_module_file($file)
    {
		global $CONFIG, $Errorh;
		
		$root = PATH_TO_ROOT . '/';
		$dir = $file;
		
		//On v�rifie que le fichier de configuration est pr�sent.
		if( file_exists($root . $dir . '/' . $dir . '_cache.php') )
		{
			$config = load_ini_file($root . $dir . '/lang/', $CONFIG['lang']);
			//On r�cup�re l'information sur le cache, si le cache est activ�, on va chercher les fonctions de r�g�n�ration de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				include_once($root . $dir . '/' . $dir . '_cache.php');
				$content = call_user_func('generate_module_file_' . $dir);
				$file_path = PATH_TO_ROOT . '/cache/' . $file . '.php';
				@delete_file($file_path); //Supprime le fichier
				if( $handle = @fopen($file_path, 'wb') ) //On cr�e le fichier avec droit d'�criture et lecture.
				{
					@flock($handle, LOCK_EX);
					@fwrite($handle, "<?php\n" . $content . "\n?>");
					@flock($handle, LOCK_UN);
					@fclose;
					
					@chmod($file_path, 0666);
				}
		
				//Il est l'heure de v�rifier si la g�n�ration a fonctionn�e.
				if( !file_exists($file_path) && filesize($file_path) == '0' )
					$Errorh->Error_handler('Cache -> La g�n�ration du fichier de cache <strong>' . $file . '</strong> a �chou�!', E_USER_ERROR, __LINE__, __FILE__);
			}	
			else //Enregistrement dans le log d'erreur.
				$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__);
		}	
		else //Enregistrement dans le log d'erreur.
			$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__);
    }
	
	//G�n�ration du fichier htaccess
	function Generate_htaccess()
	{
		global $CONFIG, $Sql;
		
		if( $CONFIG['rewrite'] )
		{
			$htaccess_rules = 'Options +FollowSymlinks' . "\n" . 'RewriteEngine on' . "\n";
			$result = $Sql->Query_while("SELECT name
			FROM ".PREFIX."modules
			WHERE activ = 1", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				//R�cup�ration des infos de config.
				$get_info_modules = load_ini_file(PATH_TO_ROOT . '/' . $row['name'] . '/lang/', $CONFIG['lang']);
				if( !empty($get_info_modules['url_rewrite']) )
					$htaccess_rules .= str_replace('\n', "\n", str_replace('DIR', DIR, $get_info_modules['url_rewrite'])) . "\n\n";
			}
			$htaccess_rules .= 
			'# Core #' . 
			"\n" . 'RewriteRule ^(.*)member/member-([0-9]+)-?([0-9]*)\.php$ ' . DIR . '/member/member.php?id=$2&p=$3 [L,QSA]' . 
			"\n" . 'RewriteRule ^(.*)member/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})\.php$ ' . DIR . '/member/pm.php?pm=$2&id=$3&p=$4&quote=$5 [L,QSA]';	
			
			//Page d'erreur.
			$htaccess_rules .= "\n\n" . '# Error page #' . "\n" . 'ErrorDocument 404 ' . HOST . DIR . '/member/404.php';						

			//Protection de la bande passante, interdiction d'acc�s aux fichiers du r�pertoire upload depuis un autre serveur.
			global $CONFIG_FILES;
			$this->load_file('files');
			if( $CONFIG_FILES['bandwidth_protect'] )
			{
				$htaccess_rules .= "\n\n# Bandwith protection #\nRewriteCond %{HTTP_REFERER} !^$\nRewriteCond %{HTTP_REFERER} !^" . HOST . "\nReWriteRule .*upload/.*$ - [F]";
			}	

			//Ecriture du fichier .htaccess
			$file_path = PATH_TO_ROOT . '/.htaccess';
			@delete_file($file_path); //Supprime le fichier.
			$handle = @fopen($file_path, 'w+'); //On cr�e le fichier avec droit d'�criture et lecture.
			@fwrite($handle, $htaccess_rules);
			@fclose($handle);
		}
		else
		{
			$htaccess_rules = 'ErrorDocument 404 ' . HOST . DIR . '/member/404.php';	

			//Ecriture du fichier .htaccess
			$file_path = PATH_TO_ROOT . '/.htaccess';
			@delete_file($file_path); //Supprime le fichier.
			$handle = @fopen($file_path, 'w+'); //On cr�e le fichier avec droit d'�criture et lecture.
			@fwrite($handle, $htaccess_rules);
			@fclose($handle);
		}
	}
	
	//Suppression d'un fichier cache
	function Delete_file($file)
	{
		if( is_file(PATH_TO_ROOT . '/cache/' . $file . '.php') )
			return @unlink(PATH_TO_ROOT . '/cache/' . $file . '.php');
		else
			return false;
	}
	
	
	## Private Methods ##
	//Parcours les dossiers, � la recherche de fichiers de configuration en vue de reg�n�rer le cache des modules.
	function generate_all_module_files()
	{
		global $CONFIG, $Sql;
		
		$result = $Sql->Query_while("SELECT name 
		FROM ".PREFIX."modules
		WHERE activ = 1", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$config = load_ini_file(PATH_TO_ROOT . '/' . $row['name'] . '/lang/', $CONFIG['lang']);
			//On r�cup�re l'information sur le cache, si le cache est activ�, on va chercher les fonctions de r�g�n�ration de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				//g�n�ration du cache.
				@include_once(PATH_TO_ROOT . '/' . $row['name'] . '/' . $row['name'] . '_cache.php');
				$this->Generate_module_file($row['name']);
			}
		}
		$Sql->Close($result);
	}
	
    ########## Fonctions de g�n�ration des fichiers un � un ##########
	//Gestions des modules installal�s, configuration des autorisations.
	function generate_file_modules()
	{
		global $Sql;
		
		$code = 'global $MODULES;' . "\n";
		$code .= '$MODULES = array();' . "\n\n";
		$result = $Sql->Query_while("SELECT name, auth, activ
		FROM ".PREFIX."modules
		ORDER BY name", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{	
			$code .= '$MODULES[\'' . $row['name'] . '\'] = array(' . "\n"
				. "'name' => " . var_export($row['name'], true) . ',' . "\n"
				. "'activ' => " . var_export($row['activ'], true) . ',' . "\n"
				. "'auth' => " . var_export(unserialize($row['auth']), true) . ',' . "\n"
				. ");\n";
		}
		$Sql->Close($result);

		return $code;
	}
	
	//Placements et autorisations des modules minis.
	function generate_file_modules_mini()
	{
		global $Sql;
		
		$modules_mini = array();
		$result = $Sql->Query_while("SELECT name, contents, location, auth, added, use_tpl
		FROM ".PREFIX."modules_mini 
		WHERE activ = 1
		ORDER BY location, class", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$modules_mini[$row['location']][] = array('name' => $row['name'], 'contents' => $row['contents'], 'auth' => $row['auth'], 'added' => $row['added'], 'use_tpl' => $row['use_tpl']);
		}
		$Sql->Close($result);

		$code = '';
		$array_seek = array('header', 'subheader', 'left', 'right', 'topcentral', 'bottomcentral', 'topfooter', 'footer');
		foreach($array_seek as $location)
		{
			$code .= '$MODULES_MINI[\'' . $location . '\'] = \'\';' . "\n";
			if( isset($modules_mini[$location]) )
			{
				foreach($modules_mini[$location] as $location_key => $info)
				{
					if( $info['added'] == '0' )
					{	
						$code .= 'if( $Member->Check_auth(' . var_export(unserialize($info['auth']), true) . ', AUTH_MENUS) ){' . "\n"
						. "\t" . 'include_once(PATH_TO_ROOT . \'/' . $info['name'] . '/' . $info['contents'] . "');\n"
						. "\t" . '$MODULES_MINI[\'' . $location . '\'] .= $Template->Pparse(\'' . str_replace('.php', '', $info['contents']) . '\', TEMPLATE_STRING_MODE);' 
						. "\n" . '}' . "\n";
					}
					elseif( $info['added'] == '2' )
					{	
						$code .= 'if( $Member->Check_auth(' . var_export(unserialize($info['auth']), true) . ', AUTH_MENUS) ){' . "\n"
						. "\t" . 'include_once(\'PATH_TO_ROOT . \'/menus/' . $info['contents'] . "');\n"
						. "\t" . '$MODULES_MINI[\'' . $location . '\'] .= $Template->Pparse(\'' . str_replace('.php', '', $info['contents']) . '\', TEMPLATE_STRING_MODE);' 
						. "\n" . '}' . "\n";
					}
					else
					{
						$code .= 'if( $Member->Check_auth(' . var_export(unserialize($info['auth']), true) . ', AUTH_MENUS) ){' . "\n";
						
						if( $info['use_tpl'] == '0' )
							$code .= '$MODULES_MINI[\'' . $location . '\'] .= ' . var_export($info['contents'], true) . ';' . "\n";
						else
						{
							switch($location)
							{
								case 'left':
								case 'right':
									$code .= "\$Template->Set_filenames(array('modules_mini' => 'modules_mini.tpl'));\n"
									. "\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\n"
									. '$MODULES_MINI[\'' . $location . '\'] .= $Template->Pparse(\'modules_mini\', TEMPLATE_STRING_MODE);';
								break;
								case 'header':
								case 'subheader':
								case 'topcentral':
								case 'bottomcentral':
								case 'topfooter':
								case 'footer':
									$code .= "\$Template->Set_filenames(array('modules_mini_horizontal' => 'modules_mini_horizontal.tpl'));"
										. "\t\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\n"
										. '$MODULES_MINI[\'' . $location . '\'] .= $Template->Pparse(\'modules_mini_horizontal\', TEMPLATE_STRING_MODE);';
									
								break;		
							}	
						}
						
						$code .=  "\n" 
							. '}' 
							. "\n";
					}
				}
				$code .= "\n";
			}
		}
		
		return $code;
	}
	
	//Configuration du site
	function generate_file_config()
	{
		global $Sql;
		
		$config = 'global $CONFIG;' . "\n";
	
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		foreach($CONFIG as $key => $value)
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";

		return $config;
	}
	
	//Cache des css associ�s aux mini-modules.
	function generate_file_css()
	{
		global $MODULES, $CONFIG;
		
		$css = 'global $CSS;' . "\n";
		$css .= '$CSS = array();' . "\n";
		
		//Listing des modules disponibles:
		$modules_config = array();
		foreach($MODULES as $name => $array)
		{	
			$array_info = load_ini_file(PATH_TO_ROOT . '/' . $name . '/lang/', $CONFIG['lang']);
			if( is_array($array_info) && $array['activ'] == '1' ) //module activ�.
			{
				if( $array_info['css'] == 2 || $array_info['css'] == 3 ) //mini css associ�
				{
					if( file_exists(PATH_TO_ROOT . '/' . $name . '/templates/' . $name . '_mini.css') )
						$css .= '$CSS[] = \'' . PATH_TO_ROOT . '/' . $name . '/templates/' . $name . "_mini.css';\n";
					elseif( file_exists(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $name . '/' . $name . '_mini.css') )
						$css .= '$CSS[] = \'' . PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $name . '/' . $name . "_mini.css';\n";
				}
			}
		}
	
		return $css;
	}
	
	//Configuration des th�mes.
	function generate_file_themes()
	{
		global $Sql;
		
		$code = 'global $THEME_CONFIG;' . "\n";
		$result = $Sql->Query_while("SELECT theme, left_column, right_column
		FROM ".PREFIX."themes	
		WHERE activ = 1", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'left_column\'] = ' . var_export((bool)$row['left_column'], true) . ';' . "\n";
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'right_column\'] = ' . var_export((bool)$row['right_column'], true) . ';' . "\n\n";
		}			
		$Sql->Close($result);

		return $code;
	}
	
	//Day
	function generate_file_day()
	{
		return 'global $_record_day;' . "\n" . '$_record_day = ' . gmdate_format('j', time(), TIMEZONE_SITE) . ';';
	}
	
	//Groupes
	function generate_file_groups()
	{
		global $Sql;
		
		$code = 'global $_array_groups_auth;' . "\n" . '$_array_groups_auth = array(' . "\n";
		$result = $Sql->Query_while("SELECT id, name, img, auth
		FROM ".PREFIX."group	
		ORDER BY id", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$code .= $row['id'] . ' => array(\'name\' => ' . var_export($row['name'], true) . ', \'img\' => ' . var_export($row['img'], true) . ', \'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}			
		$Sql->Close($result);
		$code .= ');';
		
		return $code;
	}
	
	//Debug.
	function generate_file_debug()
	{
		global $Sql;
		
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		
		//Url rewriting.
		if( function_exists('apache_get_modules') )
		{	
			$get_rewrite = apache_get_modules();
			$check_rewrite = (!empty($get_rewrite[5])) ? '1' : '0';
		}
		else
			$check_rewrite = '?';
		
		//Variables serveur.
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if( !$server_path )
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		$server_path = trim(str_replace('/admin', '', dirname($server_path)));
		$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

		//Informations pour le d�buggage.
		$array_debug[] = 'PHPBoost output debug file';
		$array_debug[] = '-------------HOST-------------';
		$array_debug[] = 'PHP [' . phpversion() . ']';
		$array_debug[] = 'GD [' . (@extension_loaded('gd') ? 1 : 0) . ']';
		$array_debug[] = 'Mod Rewrite [' . $check_rewrite . ']';
		$array_debug[] = 'Register globals [' . ((@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') ? 1 : 0) . ']';
		$array_debug[] = 'Server name [' . $server_name . ']';
		$array_debug[] = 'Server path [' . $server_path . ']';
		$array_debug[] = '-------------CONFIG-------------';
		$array_debug[] = 'Version [' . $CONFIG['version'] . ']';
		$array_debug[] = 'Server name [' . $CONFIG['server_name'] . ']';
		$array_debug[] = 'Server path [' . $CONFIG['server_path'] . ']';
		$array_debug[] = 'Theme [' . $CONFIG['theme'] . ']';
		$array_debug[] = 'Lang [' . $CONFIG['lang'] . ']';
		$array_debug[] = 'Editor [' . $CONFIG['editor'] . ']';
		$array_debug[] = 'Start page [' . $CONFIG['start_page'] . ']';
		$array_debug[] = 'Rewrite [' . $CONFIG['rewrite'] . ']';
		$array_debug[] = 'Gz [' . $CONFIG['ob_gzhandler'] . ']';
		$array_debug[] = 'Cookie [' . $CONFIG['site_cookie'] . ']';
		$array_debug[] = 'Site session [' . $CONFIG['site_session'] . ']';
		$array_debug[] = 'Site session invit [' . $CONFIG['site_session_invit'] . ']';
		$array_debug[] = '-------------CHMOD-------------';
		$array_debug[] = 'includes/auth/ [' . (is_writable(PATH_TO_ROOT . '/kernel/auth/') ? 1 : 0) . ']';
		$array_debug[] = 'includes/ [' . (is_writable(PATH_TO_ROOT . '/kernel/') ? 1 : 0) . ']';
		$array_debug[] = 'cache/ [' . (is_writable(PATH_TO_ROOT . '/cache/') ? 1 : 0) . ']';
		$array_debug[] = 'upload/ [' . (is_writable(PATH_TO_ROOT . '/upload/') ? 1 : 0) . ']';
		$array_debug[] = 'menus/ [' . (is_writable(PATH_TO_ROOT . '/menus/') ? 1 : 0) . ']';
		$array_debug[] = '/ [' . (is_writable(PATH_TO_ROOT . '/') ? 1 : 0) . ']';
		
		$debug = '$array_debug = ' . var_export($array_debug, true) . ';' . "\n";
		$debug .= 'echo \'<pre>\'; print_r($array_debug); echo \'</pre>\';';
		
		return $debug;
	}
	
	//Configuration des membres
	function generate_file_member()
	{
		global $Sql;
		
		$config_member = 'global $CONFIG_MEMBER;' . "\n";
	
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_MEMBER = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'member'", __LINE__, __FILE__));
		foreach($CONFIG_MEMBER as $key => $value)
			$config_member .= '$CONFIG_MEMBER[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";

		return $config_member;
	}

	//Rangs
	function generate_file_ranks()
	{
		global $Sql;
		
		$stock_array_ranks = '$_array_rank = array(';	
		$result = $Sql->Query_while("SELECT name, msg, icon
		FROM ".PREFIX."ranks 
		ORDER BY msg DESC", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$stock_array_ranks .= "\n" . var_export($row['msg'], true) . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['icon'], true) . '),';
		}	
		$Sql->Close($result);
		
		$stock_array_ranks = trim($stock_array_ranks, ',');
		$stock_array_ranks .= ');';	
		return	'global $_array_rank;' . "\n" . $stock_array_ranks;	
	}
	
	//Commentaires.
	function generate_file_files()
	{
		global $Sql;
		
		$config_files = 'global $CONFIG_FILES;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_FILES = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'files'", __LINE__, __FILE__));
		$CONFIG_FILES = is_array($CONFIG_FILES) ? $CONFIG_FILES : array();
		foreach($CONFIG_FILES as $key => $value)
		{	
			if( $key == 'auth_files' )
				$config_files .= '$CONFIG_FILES[\'auth_files\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			else
				$config_files .= '$CONFIG_FILES[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";		
		}
		return $config_files;
	}
	
	//Commentaires.
	function generate_file_com()
	{
		global $Sql;
		
		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_COM = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'com'", __LINE__, __FILE__));
		$CONFIG_COM = is_array($CONFIG_COM) ? $CONFIG_COM : array();
		foreach($CONFIG_COM as $key => $value)
		{
			if( $key == 'forbidden_tags' )
				$com_config .= '$CONFIG_COM[\'forbidden_tags\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			else
				$com_config .= '$CONFIG_COM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";		
		}		
		return $com_config;
	}
	
	//Smileys
	function generate_file_smileys()
	{
		global $Sql;
		
		$i = 0;
		$stock_smiley_code = '$_array_smiley_code = array(';
		$result = $Sql->Query_while("SELECT code_smiley, url_smiley 
		FROM ".PREFIX."smileys", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$coma = ($i != 0) ? ',' : '';
			$stock_smiley_code .=  $coma . "\n" . '' . var_export($row['code_smiley'], true) . ' => ' . var_export($row['url_smiley'], true);
			$i++;
		}
		$Sql->Close($result);
		$stock_smiley_code .= "\n" . ');';
		
		return 'global $_array_smiley_code;' . "\n" . $stock_smiley_code;
	}
	
	//Statistiques
	function generate_file_stats()
	{
		global $Sql;
		
		$code = 'global $nbr_members, $last_member_login, $last_member_id;' . "\n";
		$nbr_members = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member WHERE user_aprob = 1", __LINE__, __FILE__);
		$last_member = $Sql->Query_array('member', 'user_id', 'login', "WHERE user_aprob = 1 ORDER BY timestamp DESC " . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);

		$code .= '$nbr_members = ' . var_export($nbr_members, true) . ';' . "\n";
		$code .= '$last_member_login = ' . var_export($last_member['login'], true) . ';' . "\n";
		$code .= '$last_member_id = ' . var_export($last_member['user_id'], true). ';' . "\n";
		
		$array_stats_img = array('browser.png', 'os.png', 'lang.png', 'theme.png', 'sex.png');
		foreach($array_stats_img as $key => $value)
			@unlink(PATH_TO_ROOT . '/cache/' . $value);
		
		return $code;
	}
	
	## Private Attributes ##
	//Tableau qui contient tous les fichiers support�s dans cette classe
    var $files = array('config', 'modules', 'modules_mini', 'themes', 'css', 'day', 'groups', 'debug', 'member', 'files', 'com', 'ranks', 'smileys', 'stats');
}

?>