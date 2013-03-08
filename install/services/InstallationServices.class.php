<?php
/*##################################################
 *                          InstallationServices.class.php
 *                            -------------------
 *   begin                : February 3, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class InstallationServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNABLE_TO_CREATE_DATABASE = 2;
	const UNKNOWN_ERROR = 3;

	private static $token_file_content = '1';
	private static $min_php_version = '5.1.2';
	private static $phpboost_major_version = '4.0';

	/**
	 * @var File
	 */
	private $token;

	/**
	 * @var string[string]
	 */
	private $messages;

	/**
	 * @var mixed[string] Distribution configuration
	 */
	private $distribution_config;

	public function __construct($locale = '')
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.install_token');
		if (!empty($locale))
		{
			LangLoader::set_locale($locale);
		}
		$this->messages = LangLoader::get('install', 'install');
        $this->load_distribution_configuration();
	}

	public function is_already_installed()
	{
		$tables_list = PersistenceContext::get_dbms_utils()->list_tables();
		return in_array(PREFIX . 'member', $tables_list) || in_array(PREFIX . 'configs', $tables_list);
	}

	public function check_db_connection($host, $port, $login, $password, &$database, $tables_prefix)
	{
		try
		{
			$this->try_db_connection($host, $port, $login, $password, $database, $tables_prefix);
		}
		catch (UnexistingDatabaseException $ex)
		{
			if (!$this->create_database($database))
			{
				DBFactory::reset_db_connection();
				return self::UNABLE_TO_CREATE_DATABASE;
			}
			else
			{
				return $this->check_db_connection($host, $port, $login, $password, $database, $tables_prefix);
			}
		}
		catch (DBConnectionException $ex)
		{
			DBFactory::reset_db_connection();
			return self::CONNECTION_ERROR;
		}
		catch (Exception $ex)
		{
			DBFactory::reset_db_connection();
			return self::UNKNOWN_ERROR;
		}
		return self::CONNECTION_SUCCESSFUL;
	}

	private function try_db_connection($host, $port, $login, $password, $database, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => DBFactory::MYSQL,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$db_connection = new MySQLDBConnection();
		DBFactory::init_factory($db_connection_data['dbms']);
		DBFactory::set_db_connection($db_connection);
		$db_connection->connect($db_connection_data);
	}

	private function create_database($database)
	{
		$database = PersistenceContext::get_dbms_utils()->create_database($database);
		$databases_list = PersistenceContext::get_dbms_utils()->list_databases();
		PersistenceContext::close_db_connection();
		return in_array($database, $databases_list);
	}

	public function create_phpboost_tables($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login,
		$password, $tables_prefix);
        $this->create_tables();
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_cache();
		$this->generate_installation_token();
		return true;
	}

	public function configure_website($server_url, $server_path, $site_name, $site_desc = '', $site_keyword = '', $site_timezone = '')
	{
		$this->get_installation_token();
		$modules_to_install = $this->distribution_config['modules'];
		$this->generate_website_configuration($server_url, $server_path, $site_name, $site_desc, $site_keyword, $site_timezone);
		$this->install_modules($modules_to_install);
		$this->add_menus();
		$this->add_extended_fields();
		return true;
	}

	public function create_admin($login, $password, $email, $create_session = true, $auto_connect = true)
	{
		$this->get_installation_token();
		$this->create_first_admin($login, $password, $email, $create_session, $auto_connect);
		$this->delete_installation_token();
		return true;
	}

    private function load_distribution_configuration()
    {
        $this->distribution_config = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
    }

	private function generate_website_configuration($server_url, $server_path, $site_name, $site_desc = '', $site_keyword = '', $site_timezone = '')
	{
		$locale = LangLoader::get_locale();
		$user = new AdminUser();
		$user->set_locale($locale);
		AppContext::set_current_user($user);
		$this->save_general_config($server_url, $server_path, $site_name, $site_desc, $site_keyword, $site_timezone);
		$this->init_graphical_config();
		$this->init_debug_mode();
		$this->init_user_accounts_config($locale);
		$this->install_locale($locale);
		$this->configure_theme($this->distribution_config['theme'], $locale);
	}

	private function save_general_config($server_url, $server_path, $site_name, $site_description, $site_keywords, $site_timezone)
	{
		$general_config = GeneralConfig::load();
		$general_config->set_site_url($server_url);
		$general_config->set_site_path('/' . ltrim($server_path, '/'));
		$general_config->set_site_name($site_name);
		$general_config->set_site_description($site_description);
		$general_config->set_site_keywords($site_keywords);
		$general_config->set_module_home_page($this->distribution_config['module_home_page']);
		$general_config->set_phpboost_major_version(self::$phpboost_major_version);
		$general_config->set_site_install_date(new Date());
		$general_config->set_site_timezone((int)$site_timezone);
		GeneralConfig::save();
	}

	private function init_graphical_config()
	{
		$graphical_environment_config = GraphicalEnvironmentConfig::load();
		$graphical_environment_config->set_page_bench_enabled($this->distribution_config['bench']);
		GraphicalEnvironmentConfig::save();
	}

	private function init_debug_mode()
	{
		if ($this->distribution_config['debug'])
		{
			Debug::enabled_debug_mode();
		}
		else
		{
			Debug::disable_debug_mode();
		}
	}

	private function init_user_accounts_config($locale)
	{
		$user_accounts_config = UserAccountsConfig::load();
		$user_accounts_config->set_default_lang($locale);
		$user_accounts_config->set_default_theme($this->distribution_config['theme']);
		UserAccountsConfig::save();
	}

	private function install_locale($locale)
	{
		LangManager::install($locale);
	}

	private function configure_theme($theme, $locale)
	{
		ThemeManager::install($theme);
	}

	private function install_modules(array $modules_to_install)
	{
		foreach ($modules_to_install as $module_name)
		{
			ModulesManager::install_module($module_name, true);
		}
	}

	private function add_menus()
	{
		MenuService::enable_all(true);
		$modules_menu = MenuService::website_modules(LinksMenu::VERTICAL_MENU);
		MenuService::move($modules_menu, Menu::BLOCK_POSITION__LEFT, false);
		MenuService::set_position($modules_menu, -$modules_menu->get_block_position());
		MenuService::generate_cache();
	}

	private function add_extended_fields()
	{
		$lang = LangLoader::get('admin-extended-fields-common');
		
		//Sex
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.sex']);
		$extended_field->set_field_name('user_sex');
		$extended_field->set_description($lang['field-install.sex-explain']);
		$extended_field->set_field_type('MemberUserSexExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Date Birth
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.date-birth']);
		$extended_field->set_field_name('user_born');
		$extended_field->set_description($lang['field-install.date-birth-explain']);
		$extended_field->set_field_type('MemberUserBornExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Location
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.location']);
		$extended_field->set_field_name('user_location');
		$extended_field->set_description($lang['field-install.location-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Website
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.website']);
		$extended_field->set_field_name('user_website');
		$extended_field->set_description($lang['field-install.website-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(5);
		ExtendedFieldsService::add($extended_field);
		
		//Job
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.job']);
		$extended_field->set_field_name('user_job');
		$extended_field->set_description($lang['field-install.job-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Entertainement
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.entertainement']);
		$extended_field->set_field_name('user_entertainement');
		$extended_field->set_description($lang['field-install.entertainement-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Sign
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.signing']);
		$extended_field->set_field_name('user_sign');
		$extended_field->set_description($lang['field-install.signing-explain']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Biography
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.biography']);
		$extended_field->set_field_name('user_biography');
		$extended_field->set_description($lang['field-install.biography-explain']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//MSN
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.msn']);
		$extended_field->set_field_name('user_msn');
		$extended_field->set_description($lang['field-install.msn-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);
		
		//Yahoo
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.yahoo']);
		$extended_field->set_field_name('user_yahoo');
		$extended_field->set_description($lang['field-install.yahoo-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);
		
		//Avatar
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.avatar']);
		$extended_field->set_field_name('user_avatar');
		$extended_field->set_description($lang['field-install.avatar-explain']);
		$extended_field->set_field_type('MemberUserAvatarExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
	}
	
	private function generate_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}

	private function initialize_db_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => $dbms,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . 'dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'port' => $port,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$this->connect_to_database($dbms, $db_connection_data, $database);
		return $db_connection_data;
	}

	private function connect_to_database($dbms, array $db_connection_data, $database)
	{
		DBFactory::init_factory($dbms);
		$connection = DBFactory::new_db_connection();
		DBFactory::set_db_connection($connection);
		try
		{
			$connection->connect($db_connection_data);
		}
		catch (UnexistingDatabaseException $exception)
		{
			PersistenceContext::get_dbms_utils()->create_database($database);
			PersistenceContext::close_db_connection();
			$connection = DBFactory::new_db_connection();
			$connection->connect($db_connection_data);
			DBFactory::set_db_connection($connection);
		}
	}

	private function create_tables()
	{
		$kernel = new KernelSetup();
		$kernel->install();
	}

	private function write_connection_config_file(array $db_connection_data, $tables_prefix)
	{
		$db_config_content = '<?php' . "\n" .
			'$db_connection_data = ' . var_export($db_connection_data, true) . ";\n\n" .
            'defined(\'PREFIX\') or define(\'PREFIX\' , \'' . $tables_prefix . '\');'. "\n" .
            'defined(\'PHPBOOST_INSTALLED\') or define(\'PHPBOOST_INSTALLED\', true);' . "\n" .
            'require_once PATH_TO_ROOT . \'/kernel/db/tables.php\';' . "\n" .
        '?>';

		$db_config_file = new File(PATH_TO_ROOT . '/kernel/db/config.php');
		$db_config_file->write($db_config_content);
		$db_config_file->close();
	}

	private function create_first_admin($login, $password, $email, $create_session, $auto_connect)
	{
		$admin_unlock_code = $this->generate_admin_unlock_code();
		$this->update_first_admin_account($login, $password, $email, LangLoader::get_locale(), $this->distribution_config['theme'], GeneralConfig::load()->get_site_timezone());
		$this->configure_mail_sender_system($email);
		$this->configure_accounts_policy();
		$this->send_installation_mail($login, $password, $email, $admin_unlock_code);
		if ($create_session)
		{
			$this->connect_admin($password, $auto_connect);
		}
		StatsCache::invalidate();
	}

	private function update_first_admin_account($login, $password, $email, $locale, $theme, $timezone)
	{
		$columns = array(
            'login' => $login,
            'password' => KeyGenerator::string_hash($password),
            'level' => 2,
            'user_mail' => $email,
            'user_lang' => $locale,
            'user_theme' => $theme,
            'user_show_mail' => 1,
            'timestamp' => time(),
            'user_aprob' => 1,
            'user_timezone' => $timezone
		);
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, $columns, 'WHERE user_id=1');
	}

	private function generate_admin_unlock_code()
	{
		$admin_unlock_code = KeyGenerator::generate_key(12);
		$general_config = GeneralConfig::load();
		$general_config->set_admin_unlocking_key($admin_unlock_code);
		GeneralConfig::save();
		return $admin_unlock_code;
	}

	private function configure_mail_sender_system($administrator_email)
	{
		$mail_config = MailServiceConfig::load();
		$mail_config->set_administrators_mails(array($administrator_email));
		$mail_config->set_default_mail_sender($administrator_email);
		MailServiceConfig::save();
	}

	private function configure_accounts_policy()
	{
		$user_account_config = UserAccountsConfig::load();
		$user_account_config->set_registration_enabled($this->distribution_config['allow_members_registration']);
		UserAccountsConfig::save();
	}

	private function send_installation_mail($login, $password, $email, $unlock_admin)
	{
		$general_config = GeneralConfig::load();
		$mail = new Mail();
		$mail->set_sender($email, Mail::SENDER_ADMIN);
		$mail->add_recipient($email);
		$mail->set_subject($this->messages['admin.created.email.object']);
		$mail->set_content(sprintf($this->messages['admin.created.email.unlockCode'], stripslashes($login),
		stripslashes($login), $password, $unlock_admin, $general_config->get_site_url() . $general_config->get_site_path()));
		AppContext::get_mail_service()->try_to_send($mail);
	}

	private function connect_admin($password, $auto_connect)
	{
		$Session = new Session();
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('last_connect' => time()), 'WHERE user_id=1');
		$Session->start(1, $password, 2, '/install/index.php', '', $this->messages['installation.title'], $auto_connect);
	}

	private function generate_installation_token()
	{
		$this->token->write(self::$token_file_content);
	}

	private function get_installation_token()
	{
		$is_token_valid = false;
		try
		{
			$is_token_valid = $this->token->exists() && $this->token->read() == self::$token_file_content;
		}
		catch (IOException $ioe)
		{
			$is_token_valid = false;
		}

		if (!$is_token_valid)
		{
			throw new TokenNotFoundException($this->token->get_path_from_root());
		}
	}

	private function delete_installation_token()
	{
		$this->token->delete();
	}
}
?>