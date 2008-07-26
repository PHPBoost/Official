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
define('NO_FATAL_ERROR_CACHE', true);

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
    function load_file($file, $reload_cache = false)
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
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
			}
			else
			{
				//R�g�n�ration du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if( !@include(PATH_TO_ROOT . '/cache/' . $file . '.php') )
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache <strong>' . $file . '</strong>, ni de le r�g�n�rer!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
			}
		}
    }
    
    //Fonction d'enregistrement du fichier.
    function generate_file($file)
    {
		$this->_write_cache($file, $this->{'_get_' . $file}());
    }
    
	//Fonction d'enregistrement du fichier d'un module.
    function generate_module_file($module_name, $no_alert_on_error = false)
    {
		global $Errorh;
		
		include_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');
		$modulesLoader = new Modules();
		$module = $modulesLoader->get_module($module_name);
		if( $module->has_functionnality('get_cache') ) //Le module impl�mente bien la fonction.
			$this->_write_cache($module_name, $module->functionnality('get_cache'));
		elseif( !$no_alert_on_error )
			$Errorh->Error_handler('Cache -> Le module <strong>' . $module_name . '</strong> n\'a pas de fonction de cache!', E_USER_ERROR, __LINE__, __FILE__);
    }
	
	//G�n�ration de tous les fichiers
    function generate_all_files()
    {
        foreach( $this->files as $cache_file )
            $this->generate_file($cache_file);
		
		//G�n�ration de tout les fichiers de cache des modules.
		$this->generate_all_modules();
    }
	
	//Parcours les dossiers, � la recherche de fichiers de configuration en vue de reg�n�rer le cache des modules.
	function generate_all_modules()
	{
		global $MODULES;
		
		require_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');
		$modulesLoader = new Modules();
		$modules = $modulesLoader->get_available_modules('get_cache');
		foreach($modules as $module)
		{
			if( $MODULES[strtolower($module->id)]['activ'] == '1' ) //Module activ�
				$this->_write_cache($module, $module->functionnality('get_cache'));
		}
	}
	
	//Suppression d'un fichier cache
	function delete_file($file)
	{
		if( @file_exists(PATH_TO_ROOT . '/cache/' . $file . '.php') )
			return @unlink(PATH_TO_ROOT . '/cache/' . $file . '.php');
		else
			return false;
	}
	
	
	## Private Methods ##
	function _write_cache($module_name, &$cache_string)
	{
		$file_path = PATH_TO_ROOT . '/cache/' . $module_name . '.php';
		delete_file($file_path); //Supprime le fichier
		if( $handle = @fopen($file_path, 'wb') ) //On cr�e le fichier avec droit d'�criture et lecture.
		{
			@flock($handle, LOCK_EX);
			@fwrite($handle, "<?php\n" . $cache_string . "\n?>");
			@flock($handle, LOCK_UN);
			@fclose($handle);			
			@chmod($file_path, 0666);
		}

		//Il est l'heure de v�rifier si la g�n�ration a fonctionn�e.
		if( !file_exists($file_path) && filesize($file_path) == '0' )
			$Errorh->Error_handler('Cache -> La g�n�ration du fichier de cache <strong>' . $file . '</strong> a �chou�!', E_USER_ERROR, __LINE__, __FILE__);
	}
	
    ########## Fonctions de g�n�ration des fichiers un � un ##########
	//Gestions des modules installal�s, configuration des autorisations.
	function _get_modules()
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
	function _get_modules_mini()
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
	function _get_config()
	{
		global $Sql;
		
		$config = 'global $CONFIG;' . "\n" . '$CONFIG = array();' . "\n";
	
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		
		foreach($CONFIG as $key => $value)
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";

		return $config;
	}
	
	//G�n�ration du fichier htaccess
	function _get_htaccess()
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
		}
		else
			$htaccess_rules = 'ErrorDocument 404 ' . HOST . DIR . '/member/404.php';	
		
		if( !empty($CONFIG['htaccess_manual_content']) )
			$htaccess_rules .= "\n\n#Manual content\n" . $CONFIG['htaccess_manual_content'];
		
		//Ecriture du fichier .htaccess
		$file_path = PATH_TO_ROOT . '/.htaccess';
		@delete_file($file_path); //Supprime le fichier.
		$handle = @fopen($file_path, 'w+'); //On cr�e le fichier avec droit d'�criture et lecture.
		@fwrite($handle, $htaccess_rules);
		@fclose($handle);
	}
	
	//Cache des css associ�s aux mini-modules.
	function _get_css()
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
	function _get_themes()
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
	function _get_day()
	{
		return 'global $_record_day;' . "\n" . '$_record_day = ' . gmdate_format('j', time(), TIMEZONE_SITE) . ';';
	}
	
	//Groupes
	function _get_groups()
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
	
	//Configuration des membres
	function _get_member()
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
	function _get_ranks()
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
	
	//Fichiers.
	function _get_files()
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
	function _get_com()
	{
		global $Sql;
		
		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_COM = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'com'", __LINE__, __FILE__));
		$CONFIG_COM = is_array($CONFIG_COM) ? $CONFIG_COM : array();
		foreach($CONFIG_COM as $key => $value)
			$com_config .= '$CONFIG_COM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
		
		return $com_config;
	}
	
	//Smileys
	function _get_smileys()
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
	function _get_stats()
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
    var $files = array('config', 'modules', 'modules_mini', 'htaccess', 'themes', 'css', 'day', 'groups', 'member', 'files', 'com', 'ranks', 'smileys', 'stats');
}

?>