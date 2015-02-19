<?php
/*##################################################
 *                        CategoriesCache.class.php
 *                            -------------------
 *   begin                : January 31, 2013
 *   copyright            : (C) 2013 K�vin MASSY
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

abstract class CategoriesCache implements CacheData
{
	private $categories;
	
	public function synchronize()
	{
		$db_querier = PersistenceContext::get_querier();
		
		$categories_cache = self::get_class();
		$category_class = $categories_cache->get_category_class();
		
		$root_category = $categories_cache->get_root_category();
		$this->categories[Category::ROOT_CATEGORY] = $root_category;
		$result = $db_querier->select_rows($categories_cache->get_table_name(), array('*'), 'ORDER BY id_parent, c_order');
		while ($row = $result->fetch())
		{
			$category = new $category_class();
			$category->set_properties($row);
			
			if ($categories_cache->get_elements_table_name() !== '')
			{
				$parameters = array_merge($categories_cache->get_approved_elements_parameters(), array(
					'id_category' => $row['id']
				));
				$condition = ($categories_cache->get_approved_elements_condition() !== '' ? $categories_cache->get_approved_elements_condition() . ' AND ' : 'WHERE ') . $categories_cache->get_elements_table_id_category_field_name() . ' = :id_category';
				
				$number_elements = $db_querier->count($categories_cache->get_elements_table_name(), $condition, $parameters);
				$category->set_number_elements($number_elements);
				$this->categories[$row['id_parent']]->set_number_elements($this->categories[$row['id_parent']]->get_number_elements() + $number_elements);
			}
			
			if ($category->auth_is_empty())
			{
				$category->set_authorizations($root_category->get_authorizations());
			}
			
			$this->categories[$row['id']] = $category;
		}
	}
	
	abstract public function get_table_name();
	
	abstract public function get_category_class();
	
	abstract public function get_module_identifier();
	
	abstract public function get_root_category();
	
	public function get_elements_table_name()
	{
		return '';
	}
	
	public function get_elements_table_id_category_field_name()
	{
		return 'id_category';
	}
	
	public function get_approved_elements_condition()
	{
		return '';
	}
	
	public function get_approved_elements_parameters()
	{
		return array();
	}
	
	public function get_categories()
	{
		return $this->categories;
	}
	
	public function get_childrens($id_category)
	{
		$childrens = array();
		foreach ($this->categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_category)
			{
				$childrens[$id] = $category;
			}
		}
		return $childrens;
	}
	
	public function category_exists($id)
	{
		return array_key_exists($id, $this->categories);
	}
	
	public function get_category($id)
	{
		if ($this->category_exists($id))
		{
			return $this->categories[$id];
		}
		throw new CategoryNotFoundException($id);
	}
	
	/**
	 * Loads and returns the categories cached data.
	 * @return CategoriesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(get_called_class(), self::get_class()->get_module_identifier(), 'categories');
	}
	
	/**
	 * Invalidates categories cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate(self::get_class()->get_module_identifier(), 'categories');
	}
	
	public static function get_class()
	{
		$class_name = get_called_class();
		return new $class_name();
	}
}
?>