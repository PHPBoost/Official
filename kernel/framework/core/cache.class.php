<?php
/*##################################################
 *                              cache.class.php
 *                            -------------------
 *   begin                : August 28, 2006
 *   copyright            : (C) 2006 Beno�t Sautel / R�gis Viarre
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
        if (!is_dir(PATH_TO_ROOT . '/cache') || !is_writable(PATH_TO_ROOT . '/cache'))
        {
            global $Errorh;
		
			//Enregistrement dans le log d'erreur.
			$Errorh->handler('Cache -> Le dossier /cache doit �tre inscriptible, donc en CHMOD 777', E_USER_ERROR, __LINE__, __FILE__);
        }
    }
        
    //Fonction de chargement d'un fichier cache
    function load($file, $reload_cache = false)
    {
		global $Errorh, $Sql;
		
		//On charge le fichier
		$cache_file = PATH_TO_ROOT . '/cache/' . $file . '.php';
		if ($reload_cache)
			if (!DEBUG) {
				$include = @include($cache_file);
			} else {
				$include = include($cache_file);
			}
	    else
			if (!DEBUG) {
				$include = @include_once($cache_file);
			} else {
				$include = include_once($cache_file);
			}
		if (!$include)
		{
			if (in_array($file, $this->files))
			{
				//R�g�n�ration du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois
				if (!DEBUG) {
					$include2 = @include($cache_file);
				} else {
					$include2 = include($cache_file);
				}				
				if (!$include2)
					$Errorh->handler('Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
			}
			else
			{
				//R�g�n�ration du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if (!DEBUG) {
					$include3 = @include($cache_file);
				} else {
					$include3 = include($cache_file);
				}				
				if (!$include3)
					$Errorh->handler('Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
			}
		}
        if ($file == 'config')
        {   // Int�gre le num�ro de build
            import('io/file');
            $file = new File(PATH_TO_ROOT . '/.build');
            if (!empty($CONFIG['_version']))
                $CONFIG['version'] = $CONFIG['_version'] . $file->get_contents();
            else
            {
                $CONFIG['_version'] = $CONFIG['version'];
                $CONFIG['version'] = $CONFIG['version'] . $file->get_contents();
            }
            $file->close();
        }
    }
    
    //Fonction d'enregistrement du fichier.
    function generate_file($file)
    {
		$this->write($file, $this->{'_get_' . $file}());
    }
    
	//Fonction d'enregistrement du fichier d'un module.
    function generate_module_file($module_name, $no_alert_on_error = false)
    {
		global $Errorh;
		
		import('modules/modules_discovery_service');
		$modulesLoader = new ModulesDiscoveryService();
		$module = $modulesLoader->get_module($module_name);
		
		if ((!$module->get_errors() || $module->got_error(ACCES_DENIED)) && $module->has_functionnality('get_cache')) //Le module impl�mente bien la fonction.
			$this->write($module_name, $module->functionnality('get_cache'));
		elseif (!$no_alert_on_error)
			$Errorh->handler('Cache -&gt; Le module <strong>' . $module_name . '</strong> n\'a pas de fonction de cache!', E_USER_ERROR, __LINE__, __FILE__);
    }
	
	//G�n�ration de tous les fichiers
    function generate_all_files()
    {
        foreach ($this->files as $cache_file)
            $this->generate_file($cache_file);
		
		//G�n�ration de tout les fichiers de cache des modules.
		$this->generate_all_modules();
    }
	
	//Parcours les dossiers, � la recherche de fichiers de configuration en vue de reg�n�rer le cache des modules.
	function generate_all_modules()
	{
		global $MODULES;
		
		import('modules/modules_discovery_service');
		$modulesLoader = new ModulesDiscoveryService();
		$modules = $modulesLoader->get_available_modules('get_cache');
		foreach ($modules as $module)
		{
			if ($MODULES[strtolower($module->id)]['activ'] == '1') //Module activ�
				$this->write(strtolower($module->id), $module->functionnality('get_cache'));
		}
	}
	
	//Suppression d'un fichier cache
	function delete_file($file)
	{
		if (@file_exists(PATH_TO_ROOT . '/cache/' . $file . '.php'))
			return @unlink(PATH_TO_ROOT . '/cache/' . $file . '.php');
		else
			return false;
	}
	
	

    function write($module_name, &$cache_string)
    {
        $file_path = PATH_TO_ROOT . '/cache/' . $module_name . '.php';
        
        import('io/file');
        $cache_file = new File($file_path, WRITE);
        
        //Suppression du fichier (si il existe)
        $cache_file->delete();
        
        //Ouverture du fichier
        $cache_file->open();
        
        //Verrouillage du fichier (comme un mutex si une autre t�che travaille actuellement dessus)
        $cache_file->lock();
        
        //Ecriture de son contenu
        $cache_file->write("<?php\n" . $cache_string . "\n?>");
        
        //D�verrouillage du fichier (on rel�che le mutex)
        $cache_file->unlock();
        
        //Fermeture du fichier
        $cache_file->close();
        
        //On lui met les autorisations n�cessaires de fa�on � pouvoir par la suite le lire (4) et le supprimer (2), soit 4 + 2 = 6
        $cache_file->change_chmod(0666);
        
        //Il est l'heure de v�rifier si la g�n�ration a fonctionn�.
        if (!file_exists($file_path) && filesize($file_path) == 0)
            $Errorh->handler('Cache -> La g�n�ration du fichier de cache <strong>' . $file . '</strong> a �chou�!', E_USER_ERROR, __LINE__, __FILE__);
    }
	
	## Private Methods ##
	
    ########## Fonctions de g�n�ration des fichiers un � un ##########
	//Gestions des modules installal�s, configuration des autorisations.
	function _get_modules()
	{
		global $Sql;
		
		$code = 'global $MODULES;' . "\n";
		$code .= '$MODULES = array();' . "\n\n";
		$result = $Sql->query_while("SELECT name, auth, activ
		FROM " . PREFIX . "modules
		ORDER BY name", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$MODULES[\'' . $row['name'] . '\'] = array(' . "\n"
				. "'name' => " . var_export($row['name'], true) . ',' . "\n"
				. "'activ' => " . var_export($row['activ'], true) . ',' . "\n"
				. "'auth' => " . var_export(unserialize($row['auth']), true) . ',' . "\n"
				. ");\n";
		}
		$Sql->query_close($result);

		return $code;
	}
	
	//Placements et autorisations des modules minis.
	function _get_menus()
	{
		import('core/menu_service');
		return MenuService::generate_cache(true);
	}
	
	//Configuration du site
	function _get_config()
	{
		global $Sql;
		
		$config = 'global $CONFIG;' . "\n" . '$CONFIG = array();' . "\n";
	
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'config'", __LINE__, __FILE__));
		foreach ($CONFIG as $key => $value)
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";
		
		return $config;
	}
	
	//G�n�ration du fichier htaccess
	function _get_htaccess()
	{
		global $CONFIG, $Sql;
		
		if ($CONFIG['rewrite'])
		{
			$htaccess_rules = 'Options +FollowSymlinks' . "\n" . 'RewriteEngine on' . "\n";
			$result = $Sql->query_while("SELECT name
			FROM " . PREFIX . "modules
			WHERE activ = 1", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//R�cup�ration des infos de config.
				$get_info_modules = load_ini_file(PATH_TO_ROOT . '/' . $row['name'] . '/lang/', get_ulang());
				if (!empty($get_info_modules['url_rewrite']))
					$htaccess_rules .= str_replace('\n', "\n", str_replace('DIR', DIR, $get_info_modules['url_rewrite'])) . "\n\n";
			}
			$htaccess_rules .=
			'# Core #' .
			"\n" . 'RewriteRule ^(.*)member/member-([0-9]+)-?([0-9]*)\.php$ ' . DIR . '/member/member.php?id=$2&p=$3 [L,QSA]' .
			"\n" . 'RewriteRule ^(.*)member/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})\.php$ ' . DIR . '/member/pm.php?pm=$2&id=$3&p=$4&quote=$5 [L,QSA]';
			
			//Page d'erreur.
			$htaccess_rules .= "\n\n" . '# Error page #' . "\n" . 'ErrorDocument 404 ' . HOST . DIR . '/member/404.php';
			
			//Protection de la bande passante, interdiction d'acc�s aux fichiers du r�pertoire upload depuis un autre serveur.
			global $CONFIG_UPLOADS;
			$this->load('uploads');
			if ($CONFIG_UPLOADS['bandwidth_protect'])
				$htaccess_rules .= "\n\n# Bandwith protection #\nRewriteCond %{HTTP_REFERER} !^$\nRewriteCond %{HTTP_REFERER} !^" . HOST . "\nReWriteRule .*upload/.*$ - [F]";

			//Protection anti-bot.
			$htaccess_rules .= "\n\n" . '# Avoid Hacking Attempt #' . "\n" . 'RewriteCond %{HTTP_USER_AGENT} libwww [NC]' . "\n" . 'RewriteRule .* - [F,L]';
		}
		else
			$htaccess_rules = 'ErrorDocument 404 ' . HOST . DIR . '/member/404.php';
		
		if (!empty($CONFIG['htaccess_manual_content']))
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
		foreach ($MODULES as $name => $array)
		{
			if ($array['activ'] == '1') //module activ�.
			{
				if (file_exists(PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/' . $name . '/' . $name . '_mini.css'))
					$css .= '$CSS[] = \'/templates/' . get_utheme() . '/modules/' . $name . '/' . $name . "_mini.css';\n";
				elseif (file_exists(PATH_TO_ROOT . '/' . $name . '/templates/' . $name . '_mini.css'))
					$css .= '$CSS[] = \'/' . $name . '/templates/' . $name . "_mini.css';\n";
			}
		}
	
		return $css;
	}
	
	//Configuration des th�mes.
	function _get_themes()
	{
		global $Sql;
		
		$code = 'global $THEME_CONFIG;' . "\n";
		$result = $Sql->query_while("SELECT theme, left_column, right_column, secure
		FROM " . PREFIX . "themes
		WHERE activ = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'left_column\'] = ' . var_export((bool)$row['left_column'], true) . ';' . "\n";
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'right_column\'] = ' . var_export((bool)$row['right_column'], true) . ';' . "\n";
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n\n";
		}
		$Sql->query_close($result);
        
		return $code . '$THEME_CONFIG[\'default\'][\'left_column\'] = true;' . "\n" . '$THEME_CONFIG[\'default\'][\'right_column\'] = true;' . "\n" . '$THEME_CONFIG[\'default\'][\'secure\'] = \'-1\'';
	}
	
	//Configuration des langues.
	function _get_langs()
	{
		global $Sql;
		
		$code = 'global $LANGS_CONFIG;' . "\n";
		$result = $Sql->query_while("SELECT lang, secure
		FROM " . PREFIX . "lang
		WHERE activ = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$LANGS_CONFIG[\'' . addslashes($row['lang']) . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n\n";
		}
		$Sql->query_close($result);
        
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
		$result = $Sql->query_while("SELECT id, name, img, color, auth
		FROM " . PREFIX . "group
		ORDER BY id", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= $row['id'] . ' => array(\'name\' => ' . var_export($row['name'], true) . ', \'img\' => ' . var_export($row['img'], true) . ', \'color\' => ' . var_export($row['color'], true) . ', \'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}
		$Sql->query_close($result);
		$code .= ');';
		
		return $code;
	}
	
	//Configuration des membres
	function _get_member()
	{
		global $Sql;
		
		$config_member = 'global $CONFIG_USER, $CONTRIBUTION_PANEL_UNREAD, $ADMINISTRATOR_ALERTS;' . "\n";

		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_USER = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'member'", __LINE__, __FILE__));
		foreach ($CONFIG_USER as $key => $value)
			$config_member .= '$CONFIG_USER[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
		
		import('events/contribution_service');
		//Unread contributions for each profile
		$config_member .= '$CONTRIBUTION_PANEL_UNREAD = ' . var_export(ContributionService::compute_number_contrib_for_each_profile(), true) . ';';
		
		import('events/administrator_alert_service');
		$config_member .= "\n" . '$ADMINISTRATOR_ALERTS = ' . var_export(AdministratorAlertService::compute_number_unread_alerts(), true) . ';';
		
		return $config_member;
	}

	//Rangs
	function _get_ranks()
	{
		global $Sql;
		
		$stock_array_ranks = '$_array_rank = array(';
		$result = $Sql->query_while("SELECT name, msg, icon
		FROM " . PREFIX . "ranks
		ORDER BY msg DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$stock_array_ranks .= "\n" . var_export($row['msg'], true) . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['icon'], true) . '),';
		}
		$Sql->query_close($result);
		
		$stock_array_ranks = trim($stock_array_ranks, ',');
		$stock_array_ranks .= ');';
		return	'global $_array_rank;' . "\n" . $stock_array_ranks;
	}
	
	//Uploads des membres.
	function _get_uploads()
	{
		global $Sql;
		
		$config_uploads = 'global $CONFIG_UPLOADS;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_UPLOADS = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'uploads'", __LINE__, __FILE__));
		$CONFIG_UPLOADS = is_array($CONFIG_UPLOADS) ? $CONFIG_UPLOADS : array();
		foreach ($CONFIG_UPLOADS as $key => $value)
		{
			if ($key == 'auth_files')
				$config_uploads .= '$CONFIG_UPLOADS[\'auth_files\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			else
				$config_uploads .= '$CONFIG_UPLOADS[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
		}
		return $config_uploads;
	}
	
	//Commentaires.
	function _get_com()
	{
		global $Sql;
		
		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_COM = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'com'", __LINE__, __FILE__));
		$CONFIG_COM = is_array($CONFIG_COM) ? $CONFIG_COM : array();
		foreach ($CONFIG_COM as $key => $value)
			$com_config .= '$CONFIG_COM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
		
		return $com_config;
	}
	
	//Smileys
	function _get_smileys()
	{
		global $Sql;
		
		$i = 0;
		$stock_smiley_code = '$_array_smiley_code = array(';
		$result = $Sql->query_while("SELECT code_smiley, url_smiley
		FROM " . PREFIX . "smileys", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$coma = ($i != 0) ? ',' : '';
			$stock_smiley_code .=  $coma . "\n" . '' . var_export($row['code_smiley'], true) . ' => ' . var_export($row['url_smiley'], true);
			$i++;
		}
		$Sql->query_close($result);
		$stock_smiley_code .= "\n" . ');';
		
		return 'global $_array_smiley_code;' . "\n" . $stock_smiley_code;
	}
	
	//Statistiques
	function _get_stats()
	{
		global $Sql;
		
		$code = 'global $nbr_members, $last_member_login, $last_member_id;' . "\n";
		$nbr_members = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_aprob = 1", __LINE__, __FILE__);
		$last_member = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'login', "WHERE user_aprob = 1 ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

		$code .= '$nbr_members = ' . var_export($nbr_members, true) . ';' . "\n";
		$code .= '$last_member_login = ' . var_export($last_member['login'], true) . ';' . "\n";
		$code .= '$last_member_id = ' . var_export($last_member['user_id'], true). ';' . "\n";
		
		$array_stats_img = array('browser.png', 'os.png', 'lang.png', 'theme.png', 'sex.png');
		foreach ($array_stats_img as $key => $value)
			@unlink(PATH_TO_ROOT . '/cache/' . $value);
		
		return $code;
	}
	
	## Private Attributes ##
	//Tableau qui contient tous les fichiers support�s dans cette classe
    var $files = array('config', 'modules', 'menus', 'htaccess', 'themes', 'langs', 'css', 'day', 'groups', 'member', 'uploads', 'com', 'ranks', 'smileys', 'stats');
}

?>