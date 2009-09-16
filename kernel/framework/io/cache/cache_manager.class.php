<?php
/*##################################################
 *                         cache_manager.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
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

import('io/filesystem/file');

/**
 * This class manages data loading. It makes a two-level lazy loading:
 * <ul>
 * 	<li>A static cache if a data already loaded is asked. It's read directly in the memory.
 * This cache is very powerful but is invalidated by the PHP interpreter at the end of 
 * the page execution.</li>
 * 	<li>A filesystem cache to avoid querying the database every time to obtain the same value.
 * This cache is less powerful than the previous but has an infinite life span. Indeed, it's 
 * valid until the value changes and the manager is asked to store it</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class CacheManager
{
    /**
     * @var The top-level cache which associates a name to the corresponding data. 
     */
	private static $cached_data = array();
	
	/**
	 * Load the data whose key is $name.
	 * @param $name Name of the entry in the DB_TABLE_CONFIGS table. For modules
	 * 	it must be the module name.
	 * @return CacheData The loaded data
	 */
	public static function load(string $name)
	{
		if (self::_is_memory_cached($name))
		{
			return self::_get_memory_cached_data($name);
		}
		else if(self::_is_file_cached($name))
		{
            $data = self::_get_file_cached_data($name);
            self::_memory_cache_data($name, $data);
            return $data;		    
		}
		else
		{
			$data = self::_load_in_db($name);
			self::_file_cache_data($name, $data);
			self::_memory_cache_data($name, $data);
			return $data;
		}
	}
	
	/**
	 * Saves in the data base (DB_TABLE_CONFIGS table) the data and has it become persistent.
	 * @param string $name Name of the entry in which to save it.
	 * @param CacheData $data Data to save
	 */
	public static function save(string $name, CacheData $data)
	{
		self::save_in_db($name, $data);
		self::_file_cache_data($name, $data);
		self::_memory_cache_data($name, $value);
	}
	
	private static function _load_in_db(string $name)
	{
		global $Sql;
		$result = $Sql->query_array(DB_TABLE_CONFIGS, "value", "WHERE name = '" . 
			$name . "'", __LINE__, __FILE__);
		$required_value = unserialize($result['value']);
		return $required_value;
	}
	
	private static function _save_in_db(string $name, CacheData $data)
	{
		global $Sql;
		$serialized_data = serialize($data);
		$result = $Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '"
			 . addslashes($serialized_data) . "' WHERE name = '" . 
			 $name . "'", __LINE__, __FILE__);
		
		// If no entry exists in the data base, we create it
		if ($Sql->affected_rows($result) == 0)
		{
			$Sql->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) " .
				"VALUES('" . addslashes($name) . "', '" . addslashes($serialized_data) . "')",
				__LINE__, __FILE__);
		}
	}
	
	//Top-level (memory) cache management
	private static function _is_memory_cached(string $name)
	{
		return !empty(self::$cached_data);
	}
	
	private static function _get_memory_cached_data(string $name)
	{
		return self::$cached_data[$name];
	}
	
	private static function _memory_cache_data(string $name, CacheData  $value)
	{
		self::$cached_data[$name] = $value;
	}
	
	//Filesystem cache
	private static function _get_file(string $name)
	{
	    return new File(PATH_TO_ROOT . '/cache/' . $name . '.data');
	}
	
    private static function _is_file_cached(string $name)
	{
	    $file = self::_get_file($name);
	    return $file->exists();
	}
	
	private static function _get_file_cached_data(string $name)
	{
	    $file = self::_get_file($name);
		$content = $file->get_contents();
	}
	
	private static function _file_cache_data(string $name, CacheData $value)
	{
		$file = self::_get_file($name);
		$data_to_write = serialiaze($value);
		$file->write($data_to_write, ERASE);
	}
}

?>