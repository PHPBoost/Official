<?php
/*##################################################
 *                          Environment.class.php
 *                            -------------------
 *   begin                : September 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

/**
 * @package {@package}
 * This class manages all the environment that PHPBoost need to run.
 * <p>It's able to initialize the environment that contains services (database,
 * users management...) as well as the graphical environment.</p>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class Environment
{
	private static $running_module_name = '';

	/**
	 * @var GraphicalEnvironment
	 */
	private static $graphical_environment = null;

	/**
	 * Loads all the files that the environment requires
	 */
	public static function load_imports()
	{
		require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';
		require_once PATH_TO_ROOT . '/kernel/framework/helper/deprecated_helper.inc.php';

		import('core/ClassLoader');
		ClassLoader::init_autoload();
		AppContext::init_bench();
	}

	/**
	 * Inits the environment and all its services.
	 */
	public static function init()
	{
		try
		{
			self::try_init();
		}
		catch (PHPBoostNotInstalledException $ex)
		{
            AppContext::get_response()->redirect('/install/');
		}
	}

	public static function try_init()
	{
		self::fit_to_php_configuration();
		self::init_services();
		self::load_static_constants();

		// TODO Suppress uses of $Sql in the framework
		global $Sql;
		$Sql = PersistenceContext::get_sql();
		/* END DEPRECATED */

		self::load_dynamic_constants();
		self::init_session();

		// TODO move in begin
		/* DEPRECATED VARS */
		global $Session, $User, $Template;
		$Session = AppContext::get_session();
		$User = AppContext::get_user();
		$Template = new DeprecatedTemplate();
		/* END DEPRECATED */

		self::init_output_bufferization();
		self::load_lang_files();
		self::process_changeday_tasks_if_needed();
		self::compute_running_module_name();
		self::csrf_protect_post_requests();
		self::enable_errors_and_exceptions_management();
	}

	public static function init_http_services()
	{
		AppContext::set_request(new HTTPRequest());
		$response = new HTTPResponse();
		$response->set_default_attributes();
		AppContext::set_response($response);
	}

	public static function init_services()
	{
		self::init_http_services();
		AppContext::init_extension_provider_service();
	}

	public static function enable_errors_and_exceptions_management()
	{
		set_error_handler(array(new IntegratedErrorHandler(), 'handle'));
		set_exception_handler(array(new ExceptionHandler(), 'handle'));
	}

	public static function fit_to_php_configuration()
	{
		define('ERROR_REPORTING',   E_ALL | E_NOTICE | E_STRICT);
		@ini_set('display_errors', 'on');
		@ini_set('display_startup_errors', 'on');
		@error_reporting(ERROR_REPORTING);
		set_error_handler(array(new ErrorHandler(), 'handle'));
		set_exception_handler(array(new RawExceptionHandler(), 'handle'));
		Date::set_default_timezone();

		@ini_set('open_basedir', NULL);

		//Disabling magic quotes if possible
		if (ServerConfiguration::get_phpversion() < '5.3')
		{
			@set_magic_quotes_runtime(0);
		}

		//If the register globals option is enabled, we clear the automatically assigned variables
		if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
		{
			require_once PATH_TO_ROOT . '/kernel/framework/util/unusual_functions.inc.php';
			cancel_register_globals_effect();
		}

		if (get_magic_quotes_gpc())
		{
			//If magic_quotes_sybase is enabled
			if (ini_get('magic_quotes_sybase') &&
			(strtolower(ini_get('magic_quotes_sybase')) != "off"))
			{
				//We consider the magic quotes as disabled
				define('MAGIC_QUOTES', false);

				//We treat the content: it must be as if the magic_quotes option is disabled
				foreach ($_REQUEST as $var_name => $value)
				{
					$_REQUEST[$var_name] = str_replace('\'\'', '\'', $value);
				}
			}
			//Magic quotes GPC
			else
			{
				define('MAGIC_QUOTES', true);
			}
		}
		else
		{
			define('MAGIC_QUOTES', false);
		}
	}

	public static function load_static_constants()
	{
		### Common constants ###
		define('GUEST_LEVEL', 		-1);
		define('MEMBER_LEVEL', 		0);
		define('MODO_LEVEL', 		1);
		define('MODERATOR_LEVEL', 	1);
		define('ADMIN_LEVEL', 		2);

		//Path from the server root
		define('SCRIPT', 			$_SERVER['PHP_SELF']);
		define('REWRITED_SCRIPT', 	$_SERVER['REQUEST_URI']);

		//Get parameters
		define('QUERY_STRING', 		addslashes($_SERVER['QUERY_STRING']));
		define('PHPBOOST', 			true);
		define('E_UNKNOWN', 		0);
		define('E_TOKEN', 			-3);
		define('E_USER_REDIRECT', 	-1); //Deprecated
		define('E_USER_SUCCESS', 	-2);
		define('HTML_UNPROTECT', 	false);

		### Authorizations ###
		define('AUTH_MENUS', 		0x01);
		define('AUTH_FILES', 		0x01);
		define('ACCESS_MODULE', 	0x01);
		define('AUTH_THEME', 		0x01);
		define('AUTH_FLOOD', 		'auth_flood');
		define('PM_GROUP_LIMIT', 	'pm_group_limit');
		define('DATA_GROUP_LIMIT', 	'data_group_limit');
		define('AUTH_READ_MEMBERS', 1);

		### Variable types ###
		define('GET', 		1);
		define('POST', 		2);
		define('REQUEST', 	3);
		define('COOKIE', 	4);
		define('FILES', 	5);

		define('TBOOL', 			'boolean');
		define('TINTEGER', 			'integer');
		define('TDOUBLE', 			'double');
		define('TFLOAT', 			'double');
		define('TSTRING', 			'string');
		define('TSTRING_PARSE', 	'string_parse');
		define('TSTRING_UNCHANGE', 	'string_unsecure');
		define('TSTRING_HTML', 		'string_html');
		define('TSTRING_AS_RECEIVED', 'string_unchanged');
		define('TARRAY', 			'array');
		define('TUNSIGNED_INT', 	'uint');
		define('TUNSIGNED_DOUBLE', 	'udouble');
		define('TUNSIGNED_FLOAT', 	'udouble');
		define('TNONE', 			'none');

		define('USE_DEFAULT_IF_EMPTY', 1);

		### User IP address ###
		define('USER_IP', AppContext::get_request()->get_ip_address());

		### Regex options ###
		define('REGEX_MULTIPLICITY_NOT_USED', 0x01);
		define('REGEX_MULTIPLICITY_OPTIONNAL', 0x02);
		define('REGEX_MULTIPLICITY_REQUIRED', 0x03);
		define('REGEX_MULTIPLICITY_AT_LEAST_ONE', 0x04);
		define('REGEX_MULTIPLICITY_ALL', 0x05);

		DBFactory::load_prefix();
	}

	public static function load_dynamic_constants()
	{
		$general_config = GeneralConfig::load();
		$site_path = $general_config->get_site_path();
		define('DIR', $site_path);
		define('HOST', $general_config->get_site_url());
		define('TPL_PATH_TO_ROOT', DIR);
	}

	public static function init_session()
	{
		$session_data = Session::start();
		AppContext::set_session($session_data);
		AppContext::init_user();

		$user_theme = AppContext::get_user()->get_theme();
		//Is that theme authorized for this member? If not, we assign it the default theme
		$user_theme_properties = ThemeManager::get_theme($user_theme);
		if (UserAccountsConfig::load()->is_users_theme_forced() || $user_theme_properties == null
		|| !AppContext::get_user()->check_auth($user_theme_properties->get_authorizations(), AUTH_THEME))
		{
			$user_theme = UserAccountsConfig::load()->get_default_theme();
		}
		//If the user's theme doesn't exist, we assign it a default one which exists
		$user_theme = find_require_dir(PATH_TO_ROOT . '/templates/', $user_theme);
		AppContext::get_user()->set_user_theme($user_theme);

		$user_lang = AppContext::get_user()->get_locale();
		//Is that member authorized to use this lang? If not, we assign it the default lang
		$langs_cache = LangsCache::load();
		$lang_properties = $langs_cache->get_lang_properties($user_lang);
		if ($lang_properties == null || !AppContext::get_user()->check_level($lang_properties['auth']))
		{
			$user_lang = UserAccountsConfig::load()->get_default_lang();
		}
		$user_lang = find_require_dir(PATH_TO_ROOT . '/lang/', $user_lang);
		AppContext::get_user()->set_user_lang($user_lang);
	}

	public static function init_output_bufferization()
	{
		if (ServerEnvironmentConfig::load()->is_output_gziping_enabled())
		{
			ob_start('ob_gzhandler');
		}
		else
		{
			ob_start();
		}
	}

	public static function load_lang_files()
	{
		LangLoader::set_locale(get_ulang());

		global $LANG;
		$LANG = array();
		require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php');
		require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php');
	}

	public static function process_changeday_tasks_if_needed()
	{
		//If the day changed compared to the last request, we execute the daily tasks

		$last_use_config = LastUseDateConfig::load();
		$last_use_date = $last_use_config->get_last_use_date();
		$current_date = new Date();
		$current_date->set_hours(0);
		$current_date->set_minutes(0);
		$current_date->set_seconds(0);
		if ($last_use_date->is_anterior_to($current_date))
		{

			$lock_file = new File(PATH_TO_ROOT . '/cache/changeday_lock');
			if (!$lock_file->exists())
			{
				$lock_file->write('');
				$lock_file->flush();
			}
			$lock_file->lock(false);
			$yesterday_timestamp = self::get_yesterday_timestamp();

			$num_entry_today = PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_STATS
			. " WHERE stats_year = '" . gmdate_format('Y', $yesterday_timestamp,
			TIMEZONE_SYSTEM) . "' AND stats_month = '" . gmdate_format('m',
			$yesterday_timestamp, TIMEZONE_SYSTEM) . "' AND stats_day = '" . gmdate_format(
				  'd', $yesterday_timestamp, TIMEZONE_SYSTEM) . "'", __LINE__, __FILE__);

			if ((int) $num_entry_today == 0)
			{
				$last_use_config->set_last_use_date(new Date());
				LastUseDateConfig::save();

				self::perform_changeday();
			}
			$lock_file->close();
		}
	}

	private static function perform_changeday()
	{
		$today = new Date();
		$yesterday = new Date(); // FIXME set yesterday date
		$jobs = AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT);
		foreach ($jobs as $job)
		{
			$job->on_changeday($yesterday, $today);
		}
	}

	public static function compute_running_module_name()
	{
		$path = str_replace(DIR, '', SCRIPT);
		$path = trim($path, '/');
		if (strpos($path, '/'))
		{
			$module_name = explode('/', $path);
			self::$running_module_name = $module_name[0];
		}
		else
		{
			self::$running_module_name = '';
		}
	}

	/**
	 * @desc Retrieves the identifier (name of the folder) of the module which is currently executed.
	 * @return string The module identifier.
	 */
	public static function get_running_module_name()
	{
		return self::$running_module_name;
	}

	public static function csrf_protect_post_requests()
	{
		// Verify that the user really wanted to do this POST (only for the registered ones)
		if (AppContext::get_user()->check_level(MEMBER_LEVEL))
		{
			AppContext::get_session()->csrf_post_protect();
		}
	}

	/**
	 * @desc Retrieves the site start page.
	 * @return The absolute start page URL.
	 */
	public static function get_home_page()
	{
		$home_page = GeneralConfig::load()->get_home_page();
		return (substr($home_page, 0, 1) == '/') ? url(HOST . DIR . $home_page) : $home_page;
	}

	/**
	 * @desc Returns the full phpboost version with its build number
	 * @return string the full phpboost version with its build number
	 */
	public static function get_phpboost_version()
	{
		$major_version = GeneralConfig::load()->get_phpboost_major_version();
		$minor_version = self::get_phpboost_minor_version();
		return $major_version . '.' . $minor_version;
	}

	private static function get_phpboost_minor_version()
	{
		$file = new File(PATH_TO_ROOT . '/kernel/.build');
		$build =  $file->read();
		$file->close();
		return trim($build);
	}

	/**
	 * Displays the top of the page.
	 */
	public static function display_header()
	{
		self::get_graphical_environment()->display_header();
	}

	/**
	 * Displays the bottom of the page.
	 */
	public static function display_footer()
	{
		self::get_graphical_environment()->display_footer();
	}

	public static function set_graphical_environment(GraphicalEnvironment $env)
	{
		self::$graphical_environment = $env;
	}

	public static function destroy()
	{
		PersistenceContext::close_db_connection();

		ob_end_flush();
	}

	private static function get_yesterday_timestamp()
	{
		return time() - 86400;
	}

	private static function get_one_week_ago_timestamp()
	{
		return time() - 3600 * 24 * 7;
	}

	/**
	 * @return GraphicalEnvironment
	 */
	private static function get_graphical_environment()
	{
		if (self::$graphical_environment === null)
		{
			//Default graphical environment

			self::$graphical_environment = new SiteDisplayGraphicalEnvironment();
		}
		return self::$graphical_environment;
	}

	/**
	 * This method is not called automatically but can be called if you know that an action can
	 * take a long time. By default, max execution time is 30 seconds.
	 * Note that according to PHP configuration, this function can fail.
	 */
	public static function try_to_increase_max_execution_time()
	{
		@set_time_limit(600);
	}
}

?>