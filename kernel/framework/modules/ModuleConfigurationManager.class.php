<?php
/**
 *                        ModuleConfigurationManager.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Lo�c Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 *###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
 *###################################################
 */

class ModuleConfigurationManager
{
	/**
	 * @var RAMCache
	 */
	private static $cache_manager = null;

	/**
	 * @desc Returns the <code>$module_id</code> ModuleConfiguration
	 * @param string $module_id the module id
	 * @return ModuleConfiguration the module configuration
	 */
	public static function get($module_id)
	{
		$cache_manager = self::get_cache_manager();
		if (!$cache_manager->contains($module_id))
		{
			$module_configuration = self::get_module_configuration($module_id);
			$cache_manager->store($module_id, $module_configuration);
		}
		return $cache_manager->get($module_id);
	}

	/**
	 * @return RAMCache
	 */
	private static function get_cache_manager()
	{
		if (self::$cache_manager === null)
		{
			self::$cache_manager = RAMCacheFactory::get(__CLASS__);
		}
		return self::$ram_cache;
	}

	/**
	 * @return ModuleConfiguration
	 */
	private static function get_module_configuration($module_id)
	{
		$config_ini_file = PATH_TO_ROOT . '/' . $module_id . '/config.ini';
		$desc_ini_file = self::find_desc_ini_file($module_id);
		return new ModuleConfiguration($config_ini_file, $desc_ini_file);
	}

	private static function find_desc_ini_file($module_id)
	{
		$desc_ini_folder = PATH_TO_ROOT . '/' . $module_id . 'lang/';
		
		$desc_ini_file = $desc_ini_folder . get_ulang() . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}
		
		$folder = new Folder($desc_ini_folder);
		foreach ($folder->get_folders() as $lang_folder)
		{
			$desc_ini_file = $desc_ini_folder . get_ulang() . '/desc.ini';
			if (file_exists($desc_ini_file))
			{
				return $desc_ini_file;
			}
		}
		throw new Exception('Module "' . $module_id . '" description desc.ini not found in' .
			    '/' . $module_id . '/lang/');
	}
}
?>
