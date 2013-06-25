<?php
/*##################################################
 *                          packages_manager.class.php
 *                            -------------------
 *   begin                : October 12, 2008
 *   copyright            :(C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class enables you to manages the PHPBoost packages which are nothing else than the modules.
 *
 */
class ModulesManager
{
	const GENERATE_CACHE_AFTER_THE_OPERATION = true;
	const DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION = false;
	const MODULE_UNINSTALLED = 0;
	const MODULE_INSTALLED = 1;
	const UNEXISTING_MODULE = 2;
	const MODULE_ALREADY_INSTALLED = 3;
	const CONFIG_CONFLICT = 4;
	const NOT_INSTALLED_MODULE = 5;
	const MODULE_FILES_COULD_NOT_BE_DROPPED = 6;
	const PHP_VERSION_CONFLICT = 7;
	const MODULE_NOT_UPGRADABLE = 8;
	const UPGRADE_FAILED = 9;
	const MODULE_UPDATED = 10;
	
	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 */
	public static function get_installed_modules_map()
	{
		return ModulesConfig::load()->get_modules();
	}
	
	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (and activated )
	 */
	public static function get_activated_modules_map()
	{
		$activated_modules = array();
		foreach (ModulesConfig::load()->get_modules() as $module) {
			if ($module->is_activated()) {
				$activated_modules[$module->get_id()] = $module;
			}
		}
		return $activated_modules;
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the uninstalled modules (activated or not)
	 */
	public static function get_uninstalled_modules_map()
	{
        throw new NotYetImplementedException();
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 * sorted by name
	 */
	public static function get_installed_modules_map_sorted_by_localized_name()
	{
		$modules = self::get_installed_modules_map();
		try {
			usort($modules, array(__CLASS__, 'callback_sort_modules_by_name'));
		} catch (IOException $ex) {
		}
		return $modules;
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (and activated)
	 * sorted by name
	 */
	public static function get_activated_modules_map_sorted_by_localized_name()
	{
		$modules = self::get_activated_modules_map();
		try {
			usort($modules, array(__CLASS__, 'callback_sort_modules_by_name'));
		} catch (IOException $ex) {
		}
		return $modules;
	}
	
	public static function callback_sort_modules_by_name(Module $module1, Module $module2)
	{
		if ($module1->get_configuration()->get_name() > $module2->get_configuration()->get_name())
		{
			return 1;
		}
		return -1;
	}

	/**
	 * @return string[] the names list of the installed modules (activated or not)
	 */
	public static function get_installed_modules_ids_list()
	{
		return array_keys(self::get_installed_modules_map());
	}

	/**
	 * @return string[] the names list of the installed modules (and activated)
	 */
	public static function get_activated_modules_ids_list()
	{
		return array_keys(self::get_activated_modules_map());
	}

	/**
	 * @desc Returns the requested module
	 * @param $module_id the id of the module
	 * @return Module the requested module
	 */
	public static function get_module($module_id)
	{
		return ModulesConfig::load()->get_module($module_id);
	}

	/**
	 * @desc tells whether the current user is authorized to access the requested module
	 * @param $module_id the id of the module
	 * @return bool true if the current user is authorized to access the requested module
	 */
	public static function check_module_auth($module_id)
	{
		return ModulesConfig::load()->get_module($module_id)->check_auth();
	}

	/**
	 * @desc tells whether the requested module is installed (activated or not)
	 * @return bool true if the requested module is installed
	 */
	public static function is_module_installed($module_id)
	{
		return in_array($module_id, self::get_installed_modules_ids_list());
	}
	
	/**
	 * @desc tells whether the requested module is activated
	 * @return bool true if the requested module is activated
	 */
	public static function is_module_activated($module_id)
	{
		return in_array($module_id, self::get_activated_modules_ids_list());
	}

	/**
	 * @static
	 * @desc Installs a module.
	 * @param string $module_identifier Module identifier (name of its folder)
	 * @param bool $enable_module true if you want the module to be enabled, otherwise false.
	 * @return int One of the following error codes:
	 * <ul>
	 * 	<li>MODULE_INSTALLED: the installation succeded</li>
	 * 	<li>MODULE_ALREADY_INSTALLED: the module is already installed</li>
	 * 	<li>UNEXISTING_MODULE: the module you want to install doesn't exist</li>
	 * 	<li>PHP_VERSION_CONFLICT: the server PHP version is two old to be able to run the module code (config set in the config.ini module file)</li>
	 * 	<li>CONFIG_CONFLICT: the configuration field is already used</i>
	 * </ul>
	 */
	public static function install_module($module_identifier, $enable_module = true, $generate_cache = true)
	{
		self::update_class_list();
		
		if (empty($module_identifier) || !is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			return self::UNEXISTING_MODULE;
		}

		if (self::is_module_installed($module_identifier))
		{
			return self::MODULE_ALREADY_INSTALLED;
		}

		$authorizations = array('r-1' => 1, 'r0' => 1, 'r1' => 1);
		$module = new Module($module_identifier, $enable_module, $authorizations);
		$configuration = $module->get_configuration();

		$phpversion = ServerConfiguration::get_phpversion();
		if (version_compare($phpversion, $configuration->get_php_version(), 'lt'))
		{
			return self::PHP_VERSION_CONFLICT;
		}

		self::execute_module_installation($module_identifier);

		// @deprecated
		//Insertion de la configuration du module.
		$config = get_ini_config(PATH_TO_ROOT . '/' . $module_identifier . '/lang/', get_ulang()); //R�cup�ration des infos de config.

		if (!empty($config))
		{
			$querier = PersistenceContext::get_querier();
			$check_config = $querier->count(DB_TABLE_CONFIGS, 'WHERE name=:module_id', array('module_id' => $module_identifier));
			if (empty($check_config))
			{
				$querier->insert(DB_TABLE_CONFIGS, array('name' => $module_identifier, 'value' => $config));
			}
			else
			{
				return self::CONFIG_CONFLICT;
			}
		}

		ModulesConfig::load()->add_module($module);
		ModulesConfig::save();
		
		// TODO Force initialization ExtensionProviderService for PHPBoost installation
		AppContext::init_extension_provider_service();
		
		MenuService::add_mini_module($module_identifier);

		if ($generate_cache)
		{
			MenuService::generate_cache();
			
			if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && self::module_contains_rewrited_rules($module_identifier))
			{
				HtaccessFileCache::regenerate();
			}
		}

		return self::MODULE_INSTALLED;
	}

	/**
	 * @static
	 * @desc Uninstalls a module.
	 * @param int $module_id Module id (in the DB_TABLE_MODULES table)
	 * @param bool $drop_files true if you want the module files to be dropped, otherwise false.
	 * @return int One of the following error codes:
	 * <ul>
	 * 	<li>MODULE_FILES_COULD_NOT_BE_DROPPED: the module files couldn't be deleted (probably due to an authorization issue) but it has been uninstalled .</li>
	 * 	<li>MODULE_UNINSTALLED: the module was successfully uninstalled.</li>
	 * 	<li>NOT_INSTALLED_MODULE: the module to uninstall doesn't exist!</li>
	 * </ul>
	 */
	public static function uninstall_module($module_id, $drop_files)
	{
		global $Cache;

		if (!empty($module_id))
		{
			$error = self::execute_module_uninstallation($module_id);
			if ($error === null)
			{
				// @deprecated
				//R�cup�ration des infos de config.
				$info_module = load_ini_file(PATH_TO_ROOT . '/' . $module_id . '/lang/', get_ulang());
	
				//Suppression du fichier cache
				$Cache->delete_file($module_id);

				ContributionService::delete_contribution_module($module_id);
	
				NotationService::delete_notes_module($module_id);
	
				CommentsService::delete_comments_module($module_id);
	
				PersistenceContext::get_querier()->inject("DELETE FROM ".DB_TABLE_CONFIGS." 
						WHERE name = :name", array('name' => $module_id));
	
				$dir_db_module = get_ulang();
				$dir = PATH_TO_ROOT . '/' . $module_id . '/db';
	
				if (!file_exists($dir . '/' . $dir_db_module) && file_exists($dir))
				{
					//Si le dossier de base de donn�es de la LANG n'existe pas on prend le suivant exisant.
					$folder_path = new Folder($dir);
					foreach ($folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $dir)
					{
						$dir_db_module = $dir->get_name();
						break;
					}
				}
	
				//R�g�n�ration des feeds.
				Feed::clear_cache($module_id);
	
				try {
					if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && self::module_contains_rewrited_rules($module_id))
					{
						HtaccessFileCache::regenerate();
					}
				} catch (IOException $ex) {
				}
	
				MenuService::delete_mini_module($module_id);
				MenuService::delete_module_feeds_menus($module_id);
				MenuService::generate_cache();
	
				ModulesConfig::load()->remove_module_by_id($module_id);
				ModulesConfig::save();
	
				//Module home page ?
				$general_config = GeneralConfig::load();
				$module_home_page_selected = $general_config->get_module_home_page();
				if ($module_home_page_selected == $module_id)
				{
					$general_config->set_module_home_page('');
					$general_config->set_other_home_page('index.php');
				}
				
				//Suppression des fichiers du module
				if ($drop_files)
				{
					$folder = new Folder(PATH_TO_ROOT . '/' . $module_id);
					try
					{
						$folder->delete();
						self::update_class_list();
						AppContext::init_extension_provider_service();
					}
					catch (IOException $ex)
					{
						return self::MODULE_FILES_COULD_NOT_BE_DROPPED;
					}
				}
				
				return self::MODULE_UNINSTALLED;
			}
			return $error;
		}
		else
		{
			return self::NOT_INSTALLED_MODULE;
		}
	}

	public static function upgrade_module($module_identifier)
	{
		global $Cache;
		
		if (!empty($module_identifier) && is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			if (self::is_module_installed($module_identifier))
			{
				if (self::module_is_upgradable($module_identifier))
				{
					$module = self::get_module($module_identifier);
					
					$version_upgrading = self::execute_module_upgrade($module_identifier, $module->get_installed_version());
					
					if ($version_upgrading !== null)
					{
						$module->set_installed_version($version_upgrading);
						ModulesConfig::load()->update($module);
						ModulesConfig::save();
						
						AppContext::get_cache_service()->clear_cache();
						
						try {
							if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && self::module_contains_rewrited_rules($module_identifier))
							{
								HtaccessFileCache::regenerate();
							}
						} catch (IOException $ex) {
						}
					}
					else
					{
						return self::UPGRADE_FAILED;
					}
				}
				else
				{
					return self::MODULE_NOT_UPGRADABLE;
				}
			}
			else
			{
				return self::NOT_INSTALLED_MODULE;
			}
		}
		else
		{
			return self::UNEXISTING_MODULE;
		}
		
		return self::MODULE_UPDATED;
	}
	
	public static function module_is_upgradable($module_identifier)
	{
		if (!empty($module_identifier) && is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			if (self::is_module_installed($module_identifier))
			{
				$module = self::get_module($module_identifier);
				$configuration = $module->get_configuration();
				
				$new_version = $configuration->get_version();
				$installed_version = $module->get_installed_version();
				
				if (version_compare($installed_version, $new_version) == -1)
				{
					return true;
				}
				return false;
			}
		}
	}
	
	public static function update_module_authorizations($module_id, $activated, array $authorizations)
	{
		$error = '';
		if (!$activated)
		{
			//Module home page
			$general_config = GeneralConfig::load();
			$module_home_page_selected = $general_config->get_module_home_page();
			if ($module_home_page_selected == $module_id)
			{
				$general_config->set_module_home_page('');
				$general_config->set_other_home_page('index.php');
			}
			
			$editors = AppContext::get_content_formatting_service()->get_available_editors();
			if (in_array($module_id, $editors))
			{
				if (count($editors) > 1)
				{
					$default_editor = ContentFormattingConfig::load()->get_default_editor();
					if ($default_editor !== $module_id)
					{
						PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('user_editor' => $default_editor), 
							'WHERE user_editor=:old_user_editor', array('old_user_editor' => 'bbcode'
						));
					}
					else
						$error = LangLoader::get_message('is_default_editor', 'editor-common');
				}
				else
					$error = LangLoader::get_message('last_editor_installed', 'editor-common');
			}
		}

		if (empty($error))
		{
			$module = self::get_module($module_id);
			$module->set_activated($activated);
			$module->set_authorizations($authorizations);
			ModulesConfig::load()->update($module);
			ModulesConfig::save();
			
			MenuService::generate_cache();
			Feed::clear_cache($module_id);
			
			if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && self::module_contains_rewrited_rules($module_id))
			{
				HtaccessFileCache::regenerate();
			}
		}	
		
		return $error;
	}

	private static function execute_module_installation($module_id)
	{
		$module_setup = self::get_module_setup($module_id);
		$environment_check = $module_setup->check_environment();
		if (!$environment_check->has_errors())
		{
			$module_setup->install();
		}
		else
		{
			// TODO process module installation errors
		}
	}

	private static function execute_module_uninstallation($module_id)
	{
		$module_setup = self::get_module_setup($module_id);
		return $module_setup->uninstall();
	}
	
	private static function execute_module_upgrade($module_id, $installed_version)
	{
		$module_setup = self::get_module_setup($module_id);
		return $module_setup->upgrade($installed_version);
	}

	/**
	 * @desc
	 * @param string $module_id
	 * @return ModuleSetup
	 */
	private static function get_module_setup($module_id)
	{
		$module_setup_classname = ucfirst($module_id) . 'Setup';
		if (self::module_setup_exists($module_setup_classname))
		{
			return new $module_setup_classname();
		}
		return new DefaultModuleSetup();
	}

	private static function module_setup_exists($module_setup_classname)
	{
		return ClassLoader::is_class_registered_and_valid($module_setup_classname);
	}

	private static function update_class_list()
	{
		ClassLoader::generate_classlist();
	}
	
	private static function module_contains_rewrited_rules($module_identifier)
	{
		$rewrite_rules = self::get_module($module_identifier)->get_configuration()->get_url_rewrite_rules();
		if (!empty($rewrite_rules))
		{
			return true;
		}
		elseif (AppContext::get_extension_provider_service()->provider_exists($module_identifier, UrlMappingsExtensionPoint::EXTENSION_POINT))
		{
			return true;
		}
		return false;
	}
}
?>