<?php
/*##################################################
 *                       CommentsKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 06, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class CommentsKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('comments');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->create_comments_topic_table();
		$this->create_comments_table();
	}
	
	private function add_comments_rows()
	{
		$this->db_utils->add_column(PREFIX .'com', 'id_topic', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'com', 'pseudo', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));
		$this->db_utils->add_column(PREFIX .'com', 'note', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
	}
	
	private function create_comments_topic_table()
	{
		$fields = array(
			'id_topic' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true),
			'module_id' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'topic_identifier' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "'default'"),	
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'is_locked' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_comments' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'path' => array('type' => 'string', 'length' => 255, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('id_topic'),
		);
		$this->db_utils->create_table(PREFIX . 'comments_topic', $fields, $options);
	}
	
	private function create_comments_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true),
			'id_topic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'message' => array('type' => 'text', 'length' => 65000),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pseudo' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_ip' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'note' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
		);
		$this->db_utils->create_table(PREFIX . 'comments', $fields, $options);
	}
}
?>