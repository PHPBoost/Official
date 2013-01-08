<?php
/*##################################################
 *                      	 ExtendedFieldCache.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ExtendedFieldsCache implements CacheData
{
	private $extended_fields = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->extended_fields = array();
		$querier = PersistenceContext::get_querier();
		
		$result = $querier->select_rows(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, array('*'), 'ORDER BY position');
		while ($row = $result->fetch())
		{
			$auth = unserialize($row['auth']);
			
			$this->extended_fields[$row['id']] = array(
				'id' => $row['id'],
				'position' => !empty($row['position']) ? $row['position'] : '',
				'name' => !empty($row['name']) ? $row['name'] : '',
				'field_name' => !empty($row['field_name']) ? $row['field_name'] : '',
				'description' => !empty($row['description']) ? $row['description'] : '',
				'field_type' => !empty($row['field_type']) ? $row['field_type'] : '',
				'possible_values' => !empty($row['possible_values']) ? $row['possible_values'] : '',
				'default_values' => !empty($row['default_values']) ? $row['default_values'] : '',
				'required' => !empty($row['required']) ? (bool)$row['required'] : false,
				'display' => !empty($row['display']) ? (bool)$row['display'] : false,
				'freeze' => !empty($row['freeze']) ? (bool)$row['freeze'] : false,
				'regex' => !empty($row['regex']) ? $row['regex'] : '',
				'auth' => !empty($auth) ? $auth : array()
			);
		}
	}

	public function get_extended_fields()
	{
		return $this->extended_fields;
	}
	
	public function get_exist_fields()
	{
		return (count($this->extended_fields) > 0) ? true : false;
	}

	public function get_extended_field($id)
	{
		if (isset($this->extended_fields[$id]))
		{
			return $this->extended_fields[$id];
		}
		return null;
	}
	
	/**
	 * Loads and returns the extended_fields cached data.
	 * @return ExtendedFieldsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'extended-fields');
	}
	
	/**
	 * Invalidates the current extended_fields cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'extended-fields');
	}
}
?>