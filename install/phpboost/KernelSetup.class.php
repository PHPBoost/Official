<?php
/*##################################################
 *                             KernelSetup.class.php
 *                            -------------------
 *   begin                : May 29, 2010
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

class KernelSetup
{
	/**
	 * @var DBMSUtils
	 */
	private static $db_utils;
	/**
	 * @var DBQuerier
	 */
	private static $db_querier;
	private static $comments_table;
	private static $comments_topic_table;
	private static $note_table;
	private static $average_notes_table;
	private static $visit_counter_table;
	private static $configs_table;
	private static $events_table;
	private static $errors_404_table;
	private static $group_table;
	private static $member_table;
	private static $member_extended_fields_table;
	private static $member_extended_fields_list;
	private static $menus_table;
	private static $pm_msg_table;
	private static $pm_topic_table;
	private static $ranks_table;
	private static $search_index_table;
	private static $search_results_table;
	private static $sessions_table;
	private static $smileys_table;
	private static $stats_table;
	private static $stats_referer_table;
	private static $upload_table;
	private static $upload_cat_table;
	private static $verif_code_table;

	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$db_querier = PersistenceContext::get_querier();

		self::$comments_table = PREFIX . 'comments';
		self::$comments_topic_table = PREFIX . 'comments_topic';
		self::$note_table = PREFIX . 'note';
		self::$average_notes_table = PREFIX . 'average_notes';
		self::$visit_counter_table = PREFIX . 'visit_counter';
		self::$configs_table = PREFIX . 'configs';
		self::$events_table = PREFIX . 'events';
		self::$errors_404_table = PREFIX . 'errors_404';
		self::$group_table = PREFIX . 'group';
		self::$member_table = PREFIX . 'member';
		self::$member_extended_fields_table = PREFIX . 'member_extended_fields';
		self::$member_extended_fields_list = PREFIX . 'member_extended_fields_list';
		self::$menus_table = PREFIX . 'menus';
		self::$pm_msg_table = PREFIX . 'pm_msg';
		self::$pm_topic_table = PREFIX . 'pm_topic';
		self::$ranks_table = PREFIX . 'ranks';
		self::$search_index_table = PREFIX . 'search_index';
		self::$search_results_table = PREFIX . 'search_results';
		self::$sessions_table = PREFIX . 'sessions';
		self::$smileys_table = PREFIX . 'smileys';
		self::$stats_table = PREFIX . 'stats';
		self::$stats_referer_table = PREFIX . 'stats_referer';
		self::$upload_table = PREFIX . 'upload';
		self::$upload_cat_table = PREFIX . 'upload_cat';
		self::$verif_code_table = PREFIX . 'verif_code';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	private function drop_tables()
	{
		self::$db_utils->drop(array(
			self::$comments_table,
			self::$comments_topic_table,
			self::$note_table,
			self::$average_notes_table,
			self::$visit_counter_table,
			self::$configs_table,
			self::$events_table,
			self::$errors_404_table,
			self::$group_table,
			self::$member_table,
			self::$member_extended_fields_table,
			self::$member_extended_fields_list,
			self::$menus_table,
			self::$pm_msg_table,
			self::$pm_topic_table,
			self::$ranks_table,
			self::$search_index_table,
			self::$search_results_table,
			self::$sessions_table,
			self::$smileys_table,
			self::$stats_table,
			self::$stats_referer_table,
			self::$upload_table,
			self::$upload_cat_table,
			self::$verif_code_table
		));
	}

	private function create_tables()
	{
		$this->create_note_table();
		$this->create_comments_table();
		$this->create_comments_topic_table();
		$this->create_average_notes_table();
		$this->create_visit_counter_table();
		$this->create_configs_table();
		$this->create_events_table();
		$this->create_errors_404_table();
		$this->create_group_table();
		$this->create_member_table();
		$this->create_member_extended_fields_table();
		$this->create_member_extended_fields_list_table();
		$this->create_menus_table();
		$this->create_pm_msg_table();
		$this->create_pm_topic_table();
		$this->create_ranks_table();
		$this->create_search_index_table();
		$this->create_search_results_table();
		$this->create_sessions_table();
		$this->create_smileys_table();
		$this->create_stats_table();
		$this->create_stats_referer_table();
		$this->create_upload_table();
		$this->create_upload_cat_table();
		$this->create_verif_code_table();
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
		self::$db_utils->create_table(self::$comments_table, $fields, $options);
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
		self::$db_utils->create_table(self::$comments_topic_table, $fields, $options);
	}

	private function create_note_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'note' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$note_table, $fields, $options);
	}
	
	private function create_average_notes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'average_notes' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'number_notes' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$average_notes_table, $fields, $options);
	}

	private function create_visit_counter_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'ip' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'time' => array('type' => 'date', 'notnull' => 1, 'default' => "'0000-00-00'"),
			'total' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'ip' => array('type' => 'key', 'fields' => 'ip')
		));
		self::$db_utils->create_table(self::$visit_counter_table, $fields, $options);
	}

	private function create_configs_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'value' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'name' => array('type' => 'unique', 'fields' => 'name')
		));
		self::$db_utils->create_table(self::$configs_table, $fields, $options);
	}

	private function create_events_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'entitled' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'fixing_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'module' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'current_status' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'fixing_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'poster_id' => array('type' => 'integer', 'length' => 11),
			'fixer_id' => array('type' => 'integer', 'length' => 11),
			'id_in_module' => array('type' => 'integer', 'length' => 11),
			'identifier' => array('type' => 'string', 'length' => 64),
			'contribution_type' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => "1"),
			'type' => array('type' => 'string', 'length' => 64),
			'priority' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 3)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'type_index' => array('type' => 'key', 'fields' => 'type'),
				'identifier_index' => array('type' => 'key', 'fields' => 'identifier'),
				'module_index' => array('type' => 'key', 'fields' => 'module'),
				'id_in_module_index' => array('type' => 'key', 'fields' => 'id_in_module')
		));
		self::$db_utils->create_table(self::$events_table, $fields, $options);
	}

	private function create_errors_404_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 10, 'autoincrement' => true, 'notnull' => 1),
			'requested_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'from_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'times' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'unique' => array('type' => 'unique', 'fields' => array('requested_url', 'from_url'))
		));
		self::$db_utils->create_table(self::$errors_404_table, $fields, $options);
	}

	private function create_group_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'img' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'color' => array('type' => 'string', 'length' => 6, 'notnull' => 1, 'default' => "''"),
			'auth' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'members' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$group_table, $fields, $options);
	}

	private function create_member_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'password' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'level' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_groups' => array('type' => 'text', 'length' => 65000),
			'user_lang' => array('type' => 'string', 'length' => 25, 'default' => "''"),
			'user_theme' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_mail' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_show_mail' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 1),
			'user_editor' => array('type' => 'string', 'length' => 15, 'default' => "''"),
			'user_timezone' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_msg' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'user_pm' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'user_warning' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'user_readonly' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_connect' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'test_connect' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'approbation_pass' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => 0),
			'change_password_pass' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
			'user_ban' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_aprob' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('user_id'),
			'indexes' => array(
				'login' => array('type' => 'unique', 'fields' => 'login'),
				'user_id' => array('type' => 'key', 'fields' => array('login','password','level','user_id'))
		));
		self::$db_utils->create_table(self::$member_table, $fields, $options);
	}

	private function create_member_extended_fields_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
		);

		$options = array(
			'primary' => array('user_id'),
		);
		self::$db_utils->create_table(self::$member_extended_fields_table, $fields, $options);
	}

	private function create_member_extended_fields_list_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'position' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'field_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'field_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'possible_values' => array('type' => 'text', 'length' => 65000),
			'default_values' => array('type' => 'text', 'length' => 65000),
			'required' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'display' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'regex' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'freeze' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'indexes' => array(
				'id' => array('type' => 'unique', 'fields' => 'id')
		));
		self::$db_utils->create_table(self::$member_extended_fields_list, $fields, $options);
	}

	private function create_menus_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 128, 'notnull' => 1),
			'object' => array('type' => 'text', 'length' => 16777215),
			'class' => array('type' => 'string', 'length' => 67, 'notnull' => 1),
			'enabled' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'block' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'position' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'block' => array('type' => 'key', 'fields' => 'block'),
				'class' => array('type' => 'key', 'fields' => 'class'),
				'enabled' => array('type' => 'key', 'fields' => 'enabled')
		));
		self::$db_utils->create_table(self::$menus_table, $fields, $options);
	}

	private function create_pm_msg_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idconvers' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'view_status' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idconvers' => array('type' => 'key', 'fields' => array('idconvers', 'user_id', 'timestamp'))
		));
		self::$db_utils->create_table(self::$pm_msg_table, $fields, $options);
	}

	private function create_pm_topic_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id_dest' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_convers_status' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_view_pm' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_msg' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_msg_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_user' => array('type' => 'key', 'fields' => array('user_id', 'user_id_dest', 'user_convers_status', 'last_timestamp'))
		));
		self::$db_utils->create_table(self::$pm_topic_table, $fields, $options);
	}

	private function create_ranks_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'msg' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'icon' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'special' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$ranks_table, $fields, $options);
	}

	private function create_search_index_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_user' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'module' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => 0),
			'search' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'options' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'last_search_use' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'times_used' => array('type' => 'integer', 'length' => 3, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id_search'),
			'indexes' => array(
				'id_user' => array('type' => 'unique', 'fields' => array('id_user', 'module', 'search', 'options')),
				'last_search_use' => array('type' => 'key', 'fields' => 'last_search_use')
			)
		);
		self::$db_utils->create_table(self::$search_index_table, $fields, $options);
	}

	private function create_search_results_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_content' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'relevance' => array('type' => 'decimal', 'scale' => 3, 'notnull' => 1, 'default' => 0.00),
			'link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")

		);

		$options = array(
			'primary' => array('id_search', 'id_content'),
			'indexes' => array(
				'relevance' => array('type' => 'key', 'fields' => 'relevance')
			)
		);
		self::$db_utils->create_table(self::$search_results_table, $fields, $options);
	}

	private function create_sessions_table()
	{
		$fields = array(
			'session_id' => array('type' => 'string', 'length' =>64, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'level' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'session_ip' => array('type' => 'string', 'length' =>64, 'default' => "''"),
			'session_time' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'session_script' => array('type' => 'string', 'length' => 100, 'notnull' => 1,'default' => 0),
			'session_script_get' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => 0),
			'session_script_title' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'session_flag' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_theme' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'user_lang' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'modules_parameters' => array('type' => 'text', 'length' => 65000),
			'token' => array('type' => 'string', 'length' => 64, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('session_id'),
			'indexes' => array(
				'user_id' => array('type' => 'key', 'fields' => array('user_id', 'session_time'))
			)
		);
		self::$db_utils->create_table(self::$sessions_table, $fields, $options);
	}

	private function create_smileys_table()
	{
		$fields = array(
			'idsmiley' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'code_smiley' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'url_smiley' => array('type' => 'string', 'length' => 50, 'default' => "''")
		);
		$options = array(
			'primary' => array('idsmiley')
		);
		self::$db_utils->create_table(self::$smileys_table, $fields, $options);
	}

	private function create_stats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stats_year' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'stats_month' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'stats_day' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'nbr' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pages' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pages_detail' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'stats_day' => array('type' => 'unique', 'fields' => array('stats_day', 'stats_month', 'stats_year'))
			)
		);
		self::$db_utils->create_table(self::$stats_table, $fields, $options);
	}

	private function create_stats_referer_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'relative_url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'total_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'today_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'yesterday_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_day' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_update' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'url' => array('type' => 'key', 'fields' => array('url', 'relative_url'))
			)
		);
		self::$db_utils->create_table(self::$stats_referer_table, $fields, $options);
	}

	private function create_upload_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'size' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1),
			'type' => array('type' => 'string', 'length' => 10, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$upload_table, $fields, $options);
	}

	private function create_upload_cat_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$upload_cat_table, $fields, $options);
	}

	private function create_verif_code_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 15, 'notnull' => 1, 'default' => 0),
			'code' => array('type' => 'string', 'length' => 20, 'notnull' => 1, 'default' => 0),
			'difficulty' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$verif_code_table, $fields, $options);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'install');
		$this->insert_visit_counter_data();
		$this->insert_ranks_data();
		$this->insert_member_data();
		$this->insert_smileys_data();
	}
	
	private function insert_visit_counter_data()
	{
		self::$db_querier->insert(self::$visit_counter_table, array(
			'id' => 1,
			'ip' => '',
			'time' => time(),
			'total' => 0
		));
	}

	private function insert_smileys_data()
	{
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 1,
			'code_smiley' => ':|',
			'url_smiley' => 'waw.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 2,
			'code_smiley' => ':siffle',
			'url_smiley' => 'siffle.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 3,
			'code_smiley' => ':)',
			'url_smiley' => 'sourire.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 4,
			'code_smiley' => ':lol',
			'url_smiley' => 'rire.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 5,
			'code_smiley' => ':p',
			'url_smiley' => 'tirelangue.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 6,
			'code_smiley' => ':(',
			'url_smiley' => 'malheureux.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 7,
			'code_smiley' => ';)',
			'url_smiley' => 'clindoeil.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 8,
			'code_smiley' => ':heink',
			'url_smiley' => 'heink.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 9,
			'code_smiley' => ':D',
			'url_smiley' => 'heureux.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 10,
			'code_smiley' => ':d',
			'url_smiley' => 'content.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 11,
			'code_smiley' => ':s',
			'url_smiley' => 'incertain.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 12,
			'code_smiley' => ':gne',
			'url_smiley' => 'pinch.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 13,
			'code_smiley' => ':top',
			'url_smiley' => 'top.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 14,
			'code_smiley' => ':clap',
			'url_smiley' => 'clap.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 15,
			'code_smiley' => ':hehe',
			'url_smiley' => 'hehe.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 16,
			'code_smiley' => ':@',
			'url_smiley' => 'angry.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 17,
			'code_smiley' => ':\'(',
			'url_smiley' => 'snif.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 18,
			'code_smiley' => ':nex',
			'url_smiley' => 'nex.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 19,
			'code_smiley' => '8-)',
			'url_smiley' => 'star.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 20,
			'code_smiley' => '|-)',
			'url_smiley' => 'nuit.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 21,
			'code_smiley' => ':berk',
			'url_smiley' => 'berk.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 22,
			'code_smiley' => ':gre',
			'url_smiley' => 'colere.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 23,
			'code_smiley' => ':love',
			'url_smiley' => 'love.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 24,
			'code_smiley' => ':hum',
			'url_smiley' => 'doute.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 25,
			'code_smiley' => ':mat',
			'url_smiley' => 'mat.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 26,
			'code_smiley' => ':miam',
			'url_smiley' => 'miam.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 27,
			'code_smiley' => ':+1',
			'url_smiley' => 'plus1.gif'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 28,
			'code_smiley' => ':lu',
			'url_smiley' => 'lu.gif'
		));

	}

	private function insert_ranks_data()
	{
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 1,
			'name' => $this->messages['install.rank.admin'],
			'msg' => -2,
			'icon' => 'rank_admin.png',
			'special' => 1
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 2,
			'name' => $this->messages['install.rank.modo'],
			'msg' => -1,
			'icon' => 'rank_modo.png',
			'special' => 1
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 3,
			'name' => $this->messages['install.rank.inactiv'],
			'msg' => 0,
			'icon' => 'rank_0.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 4,
			'name' => $this->messages['install.rank.fronde'],
			'msg' => 1,
			'icon' => 'rank_0.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 5,
			'name' => $this->messages['install.rank.minigun'],
			'msg' => 25,
			'icon' => 'rank_1.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 6,
			'name' => $this->messages['install.rank.fuzil'],
			'msg' => 50,
			'icon' => 'rank_2.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 7,
			'name' => $this->messages['install.rank.bazooka'],
			'msg' => 100,
			'icon' => 'rank_3.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 8,
			'name' => $this->messages['install.rank.roquette'],
			'msg' => 250,
			'icon' => 'rank_4.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 9,
			'name' => $this->messages['install.rank.mortier'],
			'msg' => 500,
			'icon' => 'rank_5.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 10,
			'name' => $this->messages['install.rank.missile'],
			'msg' => 1000,
			'icon' => 'rank_6.png',
			'special' => 0
		));
		self::$db_querier->insert(self::$ranks_table, array(
			'id' => 11,
			'name' => $this->messages['install.rank.fusee'],
			'msg' => 1500,
			'icon' => 'rank_special.png',
			'special' => 0
		));

	}

	private function insert_member_data()
	{
		self::$db_querier->insert(self::$member_table, array(
			'login' => 'login',
			'level' => 2,
			'user_aprob' => 1,
			'user_groups' => ''
		));
	}
}
?>