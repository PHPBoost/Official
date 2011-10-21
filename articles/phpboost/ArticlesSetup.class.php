<?php
/*##################################################
 *                             ArticlesSetup.class.php
 *                            -------------------
 *   begin                : April 25, 2011
 *   copyright            : (C) 2011 K�vin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ArticlesSetup extends DefaultModuleSetup
{
	public static $articles_table;
	public static $articles_categories_table;

	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_categories_table = PREFIX . 'articles_categories';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_categories_table));
	}

	private function create_tables()
	{
		$this->create_articles_table();
		$this->create_articles_categories_table();
	}

	private function create_articles_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'content' => array('type' => 'text', 'length' => 65000),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_name_visitor' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'published' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'authorizations' => array('type' => 'text', 'length' => 65000),
			'timestamp_created' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'timestamp_last_modified' => array('type' => 'integer', 'length' => 11, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_categories_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'picture_path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'notation_disabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'comments_disabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'authorizations' => array('type' => 'text', 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_categories_table, $fields, $options);
	}

	private function insert_data()
	{
	}
}

?>