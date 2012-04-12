<?php
/*##################################################
 *                               MemberExtendedFieldsDAO.class.php
 *                            -------------------
 *   begin                : September 2, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

 /**
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @desc The class is responsible for access to the database fields extended.
 * @package {@package}
 */
class MemberExtendedFieldsDAO
{
	private $db_connection;
	private $request_insert;
	private $request_update;
	private $request_field;
	private $fields;
	
	public function __construct()
	{
		$this->db_connection = PersistenceContext::get_sql();
		$this->request_field = '';
		$this->request_insert = '';
		$this->request_update = '';
		$this->fields = array();
	}
	
	public function set_request(MemberExtendedField $member_extended_field)
	{
		$this->set_request_update($member_extended_field);
		$this->set_request_insert($member_extended_field);
		$this->fields[$member_extended_field->get_field_name()] = $member_extended_field->get_value();
	}
	
	public function get_request($user_id)
	{
		$check_member = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_member)
		{
			$this->get_request_update($user_id);
		}
		else
		{
			$this->get_request_insert($user_id);
		}
	}
	
	public function set_request_insert(MemberExtendedField $member_extended_field)
	{
		$this->set_request_field($member_extended_field);
		$this->request_insert .= '\'' . htmlspecialchars(trim($member_extended_field->get_value(), '|')) . '\', ';
	}
	
	private function get_request_insert($user_id)
	{
		if (!empty($this->request_field) && !empty($this->request_insert))
		{
			$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " (user_id, " . trim($this->request_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($this->request_insert, ', ') . ")", __LINE__, __FILE__);
		}
	}
	
	public function set_request_field(MemberExtendedField $member_extended_field)
	{
		$this->request_field .= htmlspecialchars($member_extended_field->get_field_name()) . ', ';
	}
	
	public function set_request_update(MemberExtendedField $member_extended_field)
	{
		$this->request_update .= $member_extended_field->get_field_name() . ' = \'' . htmlspecialchars(trim($member_extended_field->get_value(), '|')) . '\', ';
	}
	
	private function get_request_update($user_id)
	{
		if (!empty($this->request_update))
		{
			$this->db_connection->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " SET " . trim($this->request_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		}
	}
	
	/**
	 * @desc Return value field for field_name
	 */
	public function get_value($field_name)
	{
		if (in_array($field_name, $this->fields))
		{
			return $this->fields[$field_name];
		}
	}
	
	/**
	 * @desc Return true if exist displayed extended fields.
	 */
	public static function extended_fields_displayed()
	{
		return (bool)PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " WHERE display = 1", __LINE__, __FILE__);
	}
	
	/**
	 * @desc Return Array containing list fields and the value.
	 */
	public static function select_data_field_by_user_id($user_id)
	{
		try {
			return PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER_EXTENDED_FIELDS, array('*'), "WHERE user_id = '" . $user_id . "'");
		} catch (RowNotFoundException $e) {
			return array();
		}
	}
}
?>