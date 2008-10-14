<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : September 27, 2008
 *   copyright            : (C) 2008    SAUTEL Benoit
 *   email                : ben.popeye@phpboost.com
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

//A personnaliser
define('UPDATE_VERSION', '2.1');
define('DEFAULT_LANGUAGE', 'french');
define('STEPS_NUMBER', 7);

ob_start();

define('PATH_TO_ROOT', '..');

header('Content-type: text/html; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');

//Inclusion des fichiers
require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php'); //Fonctions de base.
require_once(PATH_TO_ROOT . '/kernel/constant.php'); //Constante utiles.
require_once(PATH_TO_ROOT . '/kernel/framework/io/template.class.php');

@error_reporting(ERROR_REPORTING);

define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
define('FILE', $server_path);
define('DIR', str_replace('/install/install.php', '', $server_path));

$step = retrieve(GET, 'step', 1, TUNSIGNED_INT);
$step = $step > STEPS_NUMBER ? 1 : $step;

$lang = retrieve(GET, 'lang', DEFAULT_LANGUAGE);

//Inclusion du fichier langue
if( !@include_once('lang/' . $lang . '/install_' . $lang . '.php') )
{
	include_once('lang/' . DEFAULT_LANGUAGE . '/install_' . DEFAULT_LANGUAGE . '.php');
	$lang = DEFAULT_LANGUAGE;
}


//Chargement de la configuration de la distribution
if( is_file('distribution/distribution_' . $lang . '.php') )
{
	include('distribution/distribution_' . $lang . '.php');
}
else
{
	require_once(PATH_TO_ROOT . '/kernel/framework/io/folder.class.php');
	$distribution_folder = new Folder('distribution');
	$distribution_files = $distribution_folder->get_files('`distribution_[a-z_-]+\.php`i');
	if( count($distribution_files) > 0)
		include('distribution/distribution_' . $distribution_files[0]->get_name() . '.php');
	else
	{
		//Distribution par d�faut
		//Nom de la distribution
		define('DISTRIBUTION_NAME', 'Default distribution');
		
		//Description de la distribution
		define('DISTRIBUTION_DESCRIPTION', 'This distribution is the default distribution. You will manage to install PHPBoost with the default configuration but it will install only the kernel without any module.');
		
		//Th�me de la distribution
		define('DISTRIBUTION_THEME', 'main');
		
		//Page de d�marrage de la distribution (commencer � la racine du site avec /)
		define('DISTRIBUTION_START_PAGE', '/member/member.php');
		
		//Espace membre activ� ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
		define('DISTRIBUTION_ENABLE_MEMBER', false);
		
		//Liste des modules
		$DISTRIBUTION_MODULES = array();
	}
}

//On v�rifie que le dossier cache/tpl existe et est inscriptible, sans quoi on ne peut pas mettre en cache les fichiers et donc afficher l'installateur
if( !is_dir('../cache') || !is_dir('../cache/tpl') || !is_writable('../') )
	die($LANG['cache_tpl_must_exist_and_be_writable']);
	
if( retrieve(GET, 'restart', false) )
	redirect(HOST . add_lang(FILE, true));

//Template d'installation
$template = new Template('install/install.tpl', DO_NOT_AUTO_LOAD_FREQUENT_VARS);

//Fonction pour g�rer la langue
function add_lang($url, $header_location = false)
{
	global $lang;
	if( $lang != DEFAULT_LANGUAGE )
	{
		if( strpos($url, '?') !== false )
		{
			$ampersand = $header_location ? '&' : '&amp;';
			return $url . $ampersand . 'lang=' . $lang;
		}
		else
			return $url . '?' . 'lang=' . $lang;		
	}
	else
		return $url;
}

//Changement de langue
$new_language = retrieve(POST, 'new_language', '');
if( !empty($new_language) && is_file('lang/' . $new_language . '/install_' . $new_language . '.php') && $new_language != $lang)
{
	$lang = $new_language;
	redirect(HOST . FILE . add_lang('?step=' . $step, true));
}

//Pr�ambule
if( $step == 1 )
{
	$template->assign_vars(array(
		'C_INTRO' => true,
		'L_INTRO_TITLE' => $LANG['intro_title'],
		'L_INTRO_EXPLAIN' => $LANG['intro_explain'],
		'DISTRIBUTION' => sprintf($LANG['intro_distribution'], DISTRIBUTION_NAME),
		'L_DISTRIBUTION_EXPLAIN' => $LANG['intro_distribution_intro'],
		'DISTRIBUTION_DESCRIPTION' => DISTRIBUTION_DESCRIPTION,
		'L_NEXT_STEP' => add_lang('install.php?step=2'),
		'L_START_INSTALL' => $LANG['start_install']
	));
}
//Licence
elseif( $step == 2 )
{
	$submit = !empty($_POST['submit']) ? true : false;
	$license_agreement = !empty($_POST['license_agreement']) ? true : false;
	//On v�rifie l'�tape et si elle est valid�e on passe � la suivante
	if( $submit && $license_agreement )
		redirect(HOST . FILE . add_lang('?step=3', true));
		
	$template->assign_vars(array(
		'C_LICENSE' => true,
		'TARGET' => add_lang('install.php?step=2'),
		'U_PREVIOUS_PAGE' => add_lang('install.php?step=1'),
		'L_REQUIRE_LICENSE_AGREEMENT' => ($submit && !$license_agreement) ? '<div class="warning">' . $LANG['require_license_agreement'] . '</div>' : $LANG['require_license_agreement'],
		'L_ALERT_PLEASE_AGREE_LICENSE' => $LANG['alert_agree_license'],
		'L_QUERY_TERMS' => $LANG['license_terms'],
		'L_REQUIRE_LICENSE' => $LANG['license_agreement'],
		'L_PLEASE_AGREE' => $LANG['please_agree_license'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_LICENSE_TERMS' => file_get_contents_emulate('license.txt')
	));
}
//Configuration du serveur
elseif( $step == 3 )
{
	//Url rewriting
	if( function_exists('apache_get_modules') )
	{	
		$get_rewrite = apache_get_modules();
		$check_rewrite = (!empty($get_rewrite[5])) ? 1 : 0;
	}
	else
		$check_rewrite = -1;
	
	$template->assign_vars(array(
		'C_SERVER_CONFIG' => true,
		'C_PHP_VERSION_OK' => phpversion() >= '4.1.0',
		'C_GD_LIBRAIRY_ENABLED' => @extension_loaded('gd'),
		'C_URL_REWRITING_KNOWN' => $check_rewrite != -1,
		'C_URL_REWRITING_ENABLED' => $check_rewrite == 1
	));
	
	//Mise � jour du cache de Apache � propos du syst�me de fichiers
	@clearstatcache();
	
	$chmod_dir = array('../cache', '../cache/backup', '../cache/syndication', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../kernel/auth', '../lang', '../menus', '../templates', '../upload');
	
	//V�rifications et le cas �ch�ant tentative de changement des autorisations en �criture.
	foreach($chmod_dir as $dir)
	{
		$is_writable = $is_dir = true;
		//If the file exists and is a directory
		if( file_exists($dir) && is_dir($dir) )
		{
			//Si il n'est pas inscriptible, on demande � Apache de le rendre inscriptible en esp�rant qu'il soit configurer pour accepter de telles requ�tes
			if( !is_writable($dir) )
				$is_writable = (@chmod($dir, 0777)) ? true : false;			
		}
		else
			$is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;
			
		$template->assign_block_vars('chmod', array(
			'TITLE'	=> str_replace('..' , '', $dir),
			'C_EXISTING_DIR' => $is_dir,
			'C_WRITIBLE_DIR' => $is_writable			
		));
	}
	
	$template->assign_vars(array(
		'L_CONFIG_SERVER_TITLE' => $LANG['config_server_title'],
		'L_CONFIG_SERVER_EXPLAIN' => $LANG['config_server_explain'],
		'L_PHP_VERSION' => $LANG['php_version'],
		'L_CHECK_PHP_VERSION' => $LANG['check_php_version'],
		'L_CHECK_PHP_VERSION_EXPLAIN' => $LANG['check_php_version_explain'],
		'L_EXTENSIONS' => $LANG['extensions'],
		'L_CHECK_EXTENSIONS' => $LANG['check_extensions'],
		'L_GD_LIBRARY' => $LANG['gd_library'],
		'L_GD_LIBRARY_EXPLAIN' => $LANG['gd_library_explain'],
		'L_URL_REWRITING' => $LANG['url_rewriting'],
		'L_URL_REWRITING_EXPLAIN' => $LANG['url_rewriting_explain'],
		'L_AUTH_DIR' => $LANG['auth_dir'],
		'L_CHECK_AUTH_DIR' => $LANG['check_auth_dir'],
		'L_EXISTING' => $LANG['existing'],
		'L_NOT_EXISTING' => $LANG['unexisting'],
		'L_WRITABLE' => $LANG['writable'],
		'L_NOT_WRITABLE' => $LANG['unwritable'],
		'L_REFRESH' => $LANG['refresh_chmod'],
		'L_RESULT' => $LANG['result'],
		'L_QUERY_LOADING' => $LANG['query_loading'],
		'L_QUERY_SENT' => $LANG['query_sent'],
		'L_QUERY_PROCESSING' => $LANG['query_processing'],
		'L_QUERY_SUCCESS' => $LANG['query_success'],
		'L_QUERY_FAILURE' => $LANG['query_failure'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'U_PREVIOUS_STEP' => add_lang('install.php?step=2'),
		'U_CURRENT_STEP' => add_lang('install.php?step=3'),
		'U_NEXT_STEP' => add_lang('install.php?step=4')
	));	
}
//Mise en place de la base de donn�es
elseif( $step == 4 )
{
	require_once('functions.php');
	
	if( retrieve(POST, 'submit', false) )
	{
		//R�cup�ration de la configuration de connexion
		$host = retrieve(POST, 'host', 'localhost');
		$login = retrieve(POST, 'login', '');
		$password = retrieve(POST, 'password', '');
		$database = retrieve(POST, 'database', '');
		$tables_prefix = str_replace('.', '_', retrieve(POST, 'tableprefix', 'phpboost_'));
		
		include_once('functions.php');
		if( !empty($host) && !empty($login) && !empty($database) )
			$result = check_database_config($host, $login, $password, $database, $tables_prefix);
		else
			$result = DB_UNKNOW_ERROR;
		
		switch($result)
		{
			case DB_CONFIG_SUCCESS:
			case DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED:
			case DB_CONFIG_ERROR_TABLES_ALREADY_EXIST:
				require_once('../kernel/framework/core/errors.class.php');
				$Errorh = new Errors;
				$Sql = new Sql();
	            //Connexion
				$Sql->connect($host, $login, $password, $database, ERRORS_MANAGEMENT_BY_RETURN);
							
				//Cr�ation du fichier de configuration
				require_once('../kernel/framework/io/file.class.php');
				
				$file_path = '../kernel/auth/config.php';
				
				$db_config_content = '<?php
if( !defined(\'DBSECURE\') )
{
    $sql_host = "' . $host . '"; //Adresse serveur MySQL - MySQL server address
    $sql_login = "' . $login . '"; //Login
    $sql_pass = "' . $password . '"; //Mot de passe - Password
    $sql_base = "' . $database . '"; //Nom de la base de donn�es - Database name
    $table_prefix = "' . $tables_prefix . '"; //Pr�fixe des tables - Tables prefix
    define(\'DBSECURE\', true);
    define(\'PHPBOOST_INSTALLED\', true);
}   
else
{
    exit;
}
?>';
				
				//Ouverture du fichier kernel/auth/config.php
				$db_config_file = new File($file_path);
				//Ecriture de son contenu (les variables de configuration)
				$db_config_file->write($db_config_content);
				//Fermeture du fichier dont on n'a plus besoin
				$db_config_file->close();
	
				//On cr�e la structure de la base de donn�es et on y ins�re la configuration de base
				$Sql->parse('db/mysql.sql', $tables_prefix);
				//Insertion des donn�es variables selon la langue
				$Sql->parse('lang/' . $lang . '/mysql_install_' . $lang . '.sql', $tables_prefix);
				
				//Fermeture de la connexion BDD	
				$Sql->close();
				
				redirect(HOST . FILE . add_lang('?step=5', true));
				break;
			case DB_CONFIG_ERROR_CONNECTION_TO_DBMS:
				$error = '<div class="error">' . $LANG['db_error_connexion'] . '</div>';
				break;
			case DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED:
				$error = '<div class="error">' . $LANG['db_error_selection_not_creable'] . '</div>';
			case DB_UNKNOW_ERROR:
			default:
				$error = '<div class="error">' . $LANG['db_unknown_error'] . '</div>'; 
		}
	}
    
	$template->assign_vars(array(
		'C_DATABASE_CONFIG' => true,
		'C_DISPLAY_RESULT' => !empty($error),
		'ERROR' => !empty($error) ? $error : '',
		'PROGRESS' => !empty($error) ? '100' : '0',
		'PROGRESS_STATUS' => !empty($error) ? $LANG['query_success'] : '',
		'PROGRESS_BAR' => !empty($error) ? str_repeat('<img src="templates/images/progress.png" alt="">', 56) : '',
		'HOST_VALUE' => !empty($error) ? $host  : 'localhost',
		'LOGIN_VALUE' => !empty($error) ? $login  : '',
		'PASSWORD_VALUE' => !empty($error) ? $password  : '',
		'DB_NAME_VALUE' => !empty($error) ? $database  : '',
		'PREFIX_VALUE' => !empty($error) ? $tables_prefix  : 'phpboost_',
		'U_PREVIOUS_STEP' => add_lang('install.php?step=3'),
		'U_CURRENT_STEP' => add_lang('install.php?step=4'),
		'DB_CONFIG_SUCCESS' => DB_CONFIG_SUCCESS,
		'DB_CONFIG_ERROR_CONNECTION_TO_DBMS' => DB_CONFIG_ERROR_CONNECTION_TO_DBMS,
		'DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED' => DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED,
		'DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED' => DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED,
		'DB_CONFIG_ERROR_TABLES_ALREADY_EXIST' => DB_CONFIG_ERROR_TABLES_ALREADY_EXIST,
		'DB_UNKNOW_ERROR' => DB_UNKNOW_ERROR,
		'L_DB_CONFIG_SUCESS' => addslashes($LANG['db_success']),
		'L_DB_CONFIG_ERROR_CONNECTION_TO_DBMS' => addslashes($LANG['db_error_connexion']),
		'L_DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED' => addslashes($LANG['db_error_selection_but_created']),
		'L_DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED' => addslashes($LANG['db_error_selection_not_creable']),
		'L_DB_CONFIG_ERROR_TABLES_ALREADY_EXIST' => $LANG['db_error_tables_already_exist'],
		'L_UNKNOWN_ERROR' => $LANG['db_unknown_error'],
		'L_DB_EXPLAIN' => $LANG['db_explain'],
		'L_DB_TITLE' => $LANG['db_title'],
		'L_SGBD_PARAMETERS' => $LANG['dbms_paramters'],
		'L_DB_PARAMETERS' => $LANG['db_properties'],
		'L_HOST' => $LANG['db_host_name'],
		'L_HOST_EXPLAIN' => $LANG['db_host_name_explain'],
		'L_LOGIN' => $LANG['db_login'],
		'L_LOGIN_EXPLAIN' => $LANG['db_login_explain'],
		'L_PASSWORD' => $LANG['db_password'],
		'L_PASSWORD_EXPLAIN' => $LANG['db_password_explain'],
		'L_DB_NAME' => $LANG['db_name'],
		'L_DB_NAME_EXPLAIN' => $LANG['db_name_explain'],
		'L_DB_PREFIX' => $LANG['db_prefix'],
		'L_DB_PREFIX_EXPLAIN' => $LANG['db_prefix_explain'],
		'L_TEST_DB_CONFIG' => $LANG['test_db_config'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_QUERY_LOADING' => $LANG['query_loading'],
		'L_QUERY_SENT' => $LANG['query_sent'],
		'L_QUERY_PROCESSING' => $LANG['query_processing'],
		'L_QUERY_SUCCESS' => $LANG['query_success'],
		'L_QUERY_FAILURE' => $LANG['query_failure'],
		'L_RESULT' => $LANG['db_result'],
		'L_REQUIRE_HOSTNAME' => $LANG['require_hostname'],
		'L_REQUIRE_LOGIN' => $LANG['require_login'],
		'L_REQUIRE_DATABASE_NAME' => $LANG['require_db_name']
	));
}
// Configuration du site
elseif( $step == 5 )
{
	//Variables serveur.
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$server_path = trim(str_replace('/install', '', dirname($server_path)));
	$server_path = ($server_path == '/') ? '' : $server_path;
	$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
	
	//Enregistrement de la r�ponse
	if( retrieve(POST, 'submit', false) )
	{
		$server_url = strprotect(retrieve(POST, 'site_url', $server_name, TSTRING_UNCHANGE), HTML_PROTECT, ADDSLASHES_OFF);
		$server_path = trim(strprotect(retrieve(POST, 'site_path', $server_path, TSTRING_UNCHANGE), HTML_PROTECT, ADDSLASHES_OFF), '/');
		$site_name = stripslashes(retrieve(POST, 'site_name', ''));
		$site_desc = stripslashes(retrieve(POST, 'site_desc', ''));
		$site_keyword = stripslashes(retrieve(POST, 'site_keyword', ''));
		$site_timezone = retrieve(POST, 'site_timezone', (int)date('I'));
        
		$CONFIG = array();
		$CONFIG['server_name'] = $server_url;
		//Si le chemin de PHPBoost n'est pas vide, on y ajoute un / devant
		if( $server_path != '' )
			  $CONFIG['server_path'] = '/' . $server_path;
		else
			$CONFIG['server_path'] = $server_path;		
		$CONFIG['site_name'] = $site_name;
		$CONFIG['site_desc'] = $site_desc;
		$CONFIG['site_keyword'] = $site_keyword;
		$CONFIG['start'] = time();
		$CONFIG['version'] = UPDATE_VERSION;
		$CONFIG['lang'] = $lang;
		$CONFIG['theme'] = DISTRIBUTION_THEME;
		$CONFIG['editor'] = 'bbcode';
		$CONFIG['timezone'] = $site_timezone;
		$CONFIG['start_page'] = DISTRIBUTION_START_PAGE;
		$CONFIG['maintain'] = 0;
		$CONFIG['maintain_delay'] = 1;
		$CONFIG['maintain_display_admin'] = 1;
		$CONFIG['maintain_text'] = $LANG['site_config_maintain_text'];
		$CONFIG['htaccess_manual_content'] = '';
		$CONFIG['rewrite'] = 0;
		$CONFIG['com_popup'] = 0;
		$CONFIG['compteur'] = 0;
		$CONFIG['bench'] = 1;
		$CONFIG['theme_author'] = 0;
		$CONFIG['ob_gzhandler'] = 0;
		$CONFIG['site_cookie'] = 'session';
		$CONFIG['site_session'] = 3600;
		$CONFIG['site_session_invit'] = 300;
		$CONFIG['mail'] = '';
		$CONFIG['activ_mail'] = 0;
		$CONFIG['sign'] = $LANG['site_config_mail_signature'];
		$CONFIG['anti_flood'] = 0;
		$CONFIG['delay_flood'] = 7;
		$CONFIG['unlock_admin'] = '';
		$CONFIG['pm_max'] = 50;
		$CONFIG['search_cache_time'] = 30;
		$CONFIG['search_max_use'] = 100;
		$CONFIG['html_auth'] = array ('r2' => 1);
		$CONFIG['forbidden_tags'] = array ();
		
        //Connexion � la base de donn�es
        require_once('functions.php');
		load_db_connection();
		
		//On ins�re dans la base de donn�es
        $Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
        
		//On installe la langue
		$Sql->query_inject("INSERT INTO ".PREFIX."lang (lang, activ, secure) VALUES ('" . strprotect($CONFIG['lang']) . "', 1, -1)", __LINE__, __FILE__);
		
		//On installe le th�me
		$Sql->query_inject("INSERT INTO ".PREFIX."themes (theme, activ, secure) VALUES ('" . strprotect($CONFIG['theme']) . "', 1, -1)", __LINE__, __FILE__);
		
		//On g�n�re le cache
		include('../kernel/framework/core/cache.class.php');
		$Cache = new Cache;
		
		//Installation des modules de la distribution
		require_once('../kernel/framework/modules/packages_manager.class.php');
		foreach($DISTRIBUTION_MODULES as $module_name)
		{
			$Cache->load('modules');
			PackagesManager::install_module($module_name, DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION);
		}
		
		$Cache->Generate_all_files();
		
		$Sql->close();
		
		redirect(HOST . FILE . add_lang('?step=6', true));
	}
		
	//Interface configuration du site
	$template->assign_vars(array(
		'C_SITE_CONFIG' => true,
		'SITE_URL' => $server_name,
		'SITE_PATH' => $server_path
	));

	//Balayage des fuseaux horaires
	$site_timezone = (int)date('I');
	for($i = -12; $i <= 14; $i++)
	{
		$timezone_name = '';
		if( $i === 0 )
			$timezone_name = 'GMT';
		elseif( $i > 0 )
			$timezone_name = 'GMT + ' . $i;
		else
			$timezone_name = 'GMT - ' . (-$i);
		
		$template->assign_block_vars('timezone', array(
			'NAME' => $timezone_name,
			'VALUE' => $i,
			'SELECTED' => $i === $site_timezone ? 'selected="selected"' : ''
		));
	}
		
	$template->assign_vars(array(
		'IMG_THEME' => DISTRIBUTION_THEME,
		'U_PREVIOUS_STEP' => add_lang('install.php?step=4'),
		'U_CURRENT_STEP' => add_lang('install.php?step=5'),
		'L_SITE_CONFIG' => $LANG['site_config_title'],
		'L_SITE_CONFIG_EXPLAIN' => $LANG['site_config_explain'],
		'L_YOUR_SITE' => $LANG['your_site'],
		'L_SITE_URL' => $LANG['site_url'],
		'L_SITE_URL_EXPLAIN' => $LANG['site_url_explain'],
		'L_SITE_PATH' => $LANG['site_path'],
		'L_SITE_PATH_EXPLAIN' => $LANG['site_path_explain'],
		'L_SITE_NAME' => $LANG['site_name'],
		'L_SITE_TIMEZONE' => $LANG['site_timezone'],
		'L_SITE_TIMEZONE_EXPLAIN' => $LANG['site_timezone_explain'],
		'L_SITE_DESCRIPTION' => $LANG['site_description'],
		'L_SITE_DESCRIPTION_EXPLAIN' => $LANG['site_description_explain'],
		'L_SITE_KEYWORDS' => $LANG['site_keywords'],
		'L_SITE_KEYWORDS_EXPLAIN' => $LANG['site_keywords_explain'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_REQUIRE_SITE_URL' => $LANG['require_site_url'],
		'L_REQUIRE_SITE_NAME' => $LANG['require_site_name'],
		'L_CONFIRM_SITE_URL' => $LANG['confirm_site_url'],
		'L_CONFIRM_SITE_PATH' => $LANG['confirm_site_path']
	));
}
elseif( $step == 6 )
{
	$template->assign_block_vars('admin', array());
	//Validation de l'�tape
	if( retrieve(POST, 'submit', false) )
	{
		$login = retrieve(POST, 'login', '', TSTRING_UNCHANGE);
		$password = retrieve(POST, 'password', '', TSTRING_UNCHANGE);
		$password_repeat = retrieve(POST, 'password_repeat', '', TSTRING_UNCHANGE);
		$user_mail = retrieve(POST, 'mail', '', TSTRING_UNCHANGE);
		$create_session = retrieve(POST, 'create_session', false);
		$auto_connection = retrieve(POST, 'auto_connection', false);
		
		function check_admin_account($login, $password, $password_repeat, $user_mail)
		{
			global $LANG;
			if( empty($login) )
				return $LANG['admin_require_login'];
			elseif( strlen($login) < 3 )
				return $LANG['admin_login_too_short'];
			elseif( empty($password) )
				return $LANG['admin_require_password'];
			elseif( empty($password_repeat) )
				return $LANG['admin_require_password_repeat'];
			elseif( strlen($password) < 6 )
				return $LANG['admin_password_too_short'];
			elseif( empty($user_mail) )
				return $LANG['admin_require_mail'];
			elseif( $password != $password_repeat )
				return $LANG['admin_passwords_error'];
			elseif( !preg_match('`^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$`i', $user_mail)  )
				return $LANG['admin_email_error'];
			else
				return '';
		}
		$error = check_admin_account($login, $password, $password_repeat, $user_mail);

		//Si il n'y a pas d'erreur on enregistre dans la table
		if( empty($error) )
		{
			require_once('functions.php');
			load_db_connection();
			
			//On cr�e le code de d�verrouillage
			include_once('../kernel/framework/core/cache.class.php');
			$Cache = new Cache;
			$Cache->load('config');
			
			//On enregistre le membre (l'entr�e �tait au pr�alable cr��e)
			$Sql->query_inject("UPDATE ".PREFIX."member SET login = '" . strprotect($login) . "', password = '" . strhash($password) . "', level = '2', user_lang = '" . $CONFIG['lang'] . "', user_theme = '" . $CONFIG['theme'] . "', user_mail = '" . $user_mail . "', user_show_mail = '1', timestamp = '" . time() . "', user_aprob = '1' WHERE user_id = '1'",__LINE__, __FILE__);
			
			//G�n�ration de la cl� d'activation, en cas de verrouillage de l'administration
			$unlock_admin = substr(strhash(uniqid(mt_rand(), true)), 0, 12);
			$CONFIG['unlock_admin'] = strhash($unlock_admin);
			$CONFIG['mail'] = $user_mail;
			
			$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
			
			$Cache->Generate_file('config');
			
			//Configuration des membres
			$Cache->load('member');
			
			$CONFIG_MEMBER['activ_mbr'] = (int)DISTRIBUTION_ENABLE_MEMBER;
			$CONFIG_MEMBER['msg_mbr'] = $LANG['site_config_msg_mbr'];
			$CONFIG_MEMBER['msg_register'] = $LANG['site_config_msg_register'];
			
			$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_MEMBER)) . "' WHERE name = 'member'", __LINE__, __FILE__);
			
			$Cache->generate_file('member');
			
			//On envoie un mail � l'administrateur
			$LANG['admin'] = '';
			include_once('../kernel/framework/io/mail.class.php');
			$Mail = new Mail();
			$Mail->send($user_mail, $LANG['admin_mail_object'], sprintf($LANG['admin_mail_unlock_code'], stripslashes($login), stripslashes($login), $password, $unlock_admin, HOST . DIR), $CONFIG['mail']);
			
			//On connecte directement l'administrateur si il l'a demand�
			if( $create_session )
			{
				include('../kernel/framework/members/sessions.class.php');
				$Session = new Sessions;
				
				//Remise � z�ro du compteur d'essais.
				$Sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "' WHERE user_id = '1'", __LINE__, __FILE__);
				//Lancement de la session (avec ou sans autoconnexion selon la demande de l'utilisateur)
				$Session->start(1, $password, 2, '/install/install.php', '', $LANG['page_title'], $auto_connection); 
			}
			
			//On redirige vers l'�tape suivante
			redirect(HOST . FILE . add_lang('?step=7', true));
		}
		else
			$template->assign_block_vars('error', array(
				'ERROR' => '<div class="warning">' . $error . '</div>'
			));
	}
	
	$template->assign_vars(array(
		'C_ADMIN_ACCOUNT' => true,
		'U_PREVIOUS_STEP' => add_lang('install.php?step=5'),
		'U_CURRENT_STEP' => add_lang('install.php?step=6'),
		'L_ADMIN_ACCOUNT_CREATION' => $LANG['admin_account_creation'],
		'L_EXPLAIN_ADMIN_ACCOUNT_CREATION' => $LANG['admin_account_creation_explain'],
		'L_ADMIN_ACCOUNT' => $LANG['admin_account'],
		'L_PSEUDO' => $LANG['admin_pseudo'],
		'L_PSEUDO_EXPLAIN' => $LANG['admin_pseudo_explain'],
		'L_PASSWORD' => $LANG['admin_password'],
		'L_PASSWORD_EXPLAIN' => $LANG['admin_password_explain'],
		'L_PASSWORD_REPEAT' => $LANG['admin_password_repeat'],
		'L_MAIL' => $LANG['admin_mail'],
		'L_MAIL_EXPLAIN' => $LANG['admin_mail_explain'],
		'L_PREVIOUS_STEP' => $LANG['previous_step'],
		'L_NEXT_STEP' => $LANG['next_step'],
		'L_ERROR' => $LANG['admin_error'],
		'L_REQUIRE_LOGIN' => $LANG['admin_require_login'],
		'L_LOGIN_TOO_SHORT' => $LANG['admin_login_too_short'],
		'L_PASSWORD_TOO_SHORT' => $LANG['admin_password_too_short'],
		'L_REQUIRE_PASSWORD' => $LANG['admin_require_password'],
		'L_REQUIRE_PASSWORD_REPEAT' => $LANG['admin_require_password_repeat'],
		'L_REQUIRE_MAIL' => $LANG['admin_require_mail'],
		'L_PASSWORDS_ERROR' => $LANG['admin_passwords_error'],
		'L_CREATE_SESSION' => $LANG['admin_create_session'],
		'L_AUTO_CONNECTION' => $LANG['admin_auto_connection'],
		'L_EMAIL_ERROR' => $LANG['admin_email_error'],
		'LOGIN_VALUE' => !empty($error) ? $login : '',
		'PASSWORD_VALUE' => !empty($error) ? $password : '',
		'MAIL_VALUE' => !empty($error) ? $user_mail : '',
		'CHECKED_AUTO_CONNECTION' => !empty($error) ? ($auto_connection ? 'checked="checked"' : '') : 'checked="checked"',
		'CHECKED_CREATE_SESSION' => !empty($error) ? ($create_session ? 'checked="checked"' : '') : 'checked="checked"'
	));		
}
elseif( $step == 7 )
{
	require_once('functions.php');
	load_db_connection();
	
	require_once('../kernel/framework/core/cache.class.php');
	$Cache = new Cache;
	$Cache->load('config');
	
	$template->assign_vars(array(
		'C_END' => true,
		'CONTENTS' => sprintf($LANG['end_installation']),	
		'L_ADMIN_INDEX' => $LANG['admin_index'],
		'L_SITE_INDEX' => $LANG['site_index'],
		'U_ADMIN_INDEX' => '../admin/admin_index.php',
		'U_INDEX' => '..' . $CONFIG['start_page']
	));
}

$steps = array(
	array($LANG['introduction'], 'intro.png', 0),
	array($LANG['license'], 'license.png', 10),
	array($LANG['config_server'], 'config.png', 30),
	array($LANG['database_config'], 'database.png', 70),
	array($LANG['advanced_config'], 'advanced_config.png', 80),
	array($LANG['administrator_account_creation'], 'admin.png', 90),
	array($LANG['end'], 'end.png', 100)
);

$step_name = $steps[$step - 1][0];


require_once('../kernel/framework/io/folder.class.php');

$lang_dir = new Folder('../lang');

foreach($lang_dir->get_folders('`[a-z_-]`i') as $folder)
{
	$info_lang = load_ini_file('../lang/', $folder->get_name());
	if( !empty($info_lang['name']) )
	{	
		$template->assign_block_vars('lang', array(
			'LANG' => $folder->get_name(),
			'LANG_NAME' => $info_lang['name'],
			'SELECTED' => $folder->get_name() == $lang ? 'selected="selected"' : ''
		));
		
		if(	$folder->get_name() == $lang )
		{
			$template->assign_vars(array(
				'LANG_IDENTIFIER' => $info_lang['identifier']
			));
		}
	}
}

$template->assign_vars(array(
	'PATH_TO_ROOT' => PATH_TO_ROOT,
	'LANG' => $lang,
	'NUM_STEP' => $step,
	'PROGRESS_LEVEL' => $steps[$step - 1][2],
	'L_TITLE' => $LANG['page_title'] . ' - ' . $step_name,
	'L_STEP' => $step_name,
	'L_STEPS_LIST' => $LANG['steps_list'],
	'L_LICENSE' => $LANG['license'],
	'L_INSTALL_PROGRESS' => $LANG['install_progress'],
	'L_GENERATED_BY' => sprintf($LANG['generated_by'], '<a href="http://www.phpboost.com" style="color:#799cbb;">PHPBoost ' . UPDATE_VERSION . '</a>'),
	'L_APPENDICES' => $LANG['appendices'],
	'L_DOCUMENTATION' => $LANG['documentation'],
	'U_DOCUMENTATION' => $LANG['documentation_link'],
	'L_RESTART_INSTALL' => $LANG['restart_installation'],
	'L_CONFIRM_RESTART' => $LANG['confirm_restart_installation'],
	'L_LANG' => $LANG['change_lang'],
	'L_CHANGE' => $LANG['change'],
	'L_YES' => $LANG['yes'],
	'L_NO' => $LANG['no'],
	'L_UNKNOWN' => $LANG['unknown'], 
	'U_RESTART' => add_lang('install.php')
));

//Images de la barre de progression
for($i = 1; $i <= floor($steps[$step - 1][2] * 24 / 100); $i++)
	$template->assign_block_vars('progress_bar', array());

//Etapes de l'installation
for($i = 1; $i <= STEPS_NUMBER; $i++ )
{
	if( $i < $step )
		$row_class = 'row_success';
	elseif( $i == $step )
		$row_class = 'row_current';
	else
		$row_class = 'row_next';
	
	$template->assign_block_vars('link_menu', array(
		'CLASS' => $row_class,
		'STEP_IMG' => $steps[$i - 1][1],
		'STEP_NAME' => $steps[$i - 1][0]
	));
}

$template->parse();

ob_end_flush();

?>
