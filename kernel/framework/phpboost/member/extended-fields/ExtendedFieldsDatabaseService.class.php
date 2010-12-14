<?php
/*##################################################
 *      ExtendedFieldsDatabaseService.class.php
 *                            -------------------
 *   begin                : August 14, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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
 * @author K�vin MASSY <soldier.weasel@gmail.com>
 * @desc This class is responsible of all database accesses implied by the extended fields management. 
 * Indeed, for instance when a field is created, the data base structure must be updated throw an ALTER request.
 * @package {@package}
 */
class ExtendedFieldsDatabaseService
{
	public static function add_extended_field(ExtendedField $extended_field)
	{
		self::add_extended_field_to_member($extended_field);

		PersistenceContext::get_querier()->inject(
			"INSERT INTO " . DB_TABLE_MEMBER_EXTEND_CAT . " (name, position, field_name, description, field_type, possible_values, default_values, required, display, regex, auth)
			VALUES (:name, :position, :field_name, :description, :field_type, :possible_values, :default_values, :required, :display, :regex, :auth)", array(
                'name' => $extended_field->get_name(),
                'position' => $extended_field->get_position(),
				'field_name' => $extended_field->get_field_name(),
				'description' => $extended_field->get_description(),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => $extended_field->get_possible_values(),
				'default_values' => $extended_field->get_default_values(),
				'required' => $extended_field->get_required(),
				'display' => $extended_field->get_display(),
				'regex' => $extended_field->get_regex(),
				'auth' => serialize($extended_field->get_authorization()),
		));
	}
	
	public static function update_extended_field(ExtendedField $extended_field)
	{
		self::change_extended_field_to_member($extended_field);

		PersistenceContext::get_querier()->inject(
			"UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET 
			name = :name, field_name = :field_name, description = :description, field_type = :field_type, possible_values = :possible_values, default_values = :default_values, required = :required, display = :display, regex = :regex, auth = :auth
			WHERE id = :id"
			, array(
                'name' => $extended_field->get_name(),
				'field_name' => $extended_field->get_field_name(),
				'description' => $extended_field->get_description(),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => $extended_field->get_possible_values(),
				'default_values' => $extended_field->get_default_values(),
				'required' => $extended_field->get_required(),
				'display' => $extended_field->get_display(),
				'regex' => $extended_field->get_regex(),
				'auth' => serialize($extended_field->get_authorization()),
				'id' => $extended_field->get_id(),
		));
	}
	
	public static function delete_extended_field(ExtendedField $extended_field)
	{
		self::drop_extended_field_to_member($extended_field);	

		PersistenceContext::get_querier()->inject(
			"DELETE FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = :id"
			, array(
				'id' => $extended_field->get_id(),
		));
	}
	
	public static function check_field_exist_by_field_name(ExtendedField $extended_field)
	{
		return PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $extended_field->get_field_name() . "'", __LINE__, __FILE__) > 0 ? true : false;
	}
	
	public static function check_field_exist_by_id(ExtendedField $extended_field)
	{
		return PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $extended_field->get_id() . "'", __LINE__, __FILE__) > 0 ? true : false;
	}
	
	public static function add_extended_field_to_member(ExtendedField $extended_field)
	{
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " ADD " . $extended_field->get_field_name() . " " . self::type_columm_field($extended_field), __LINE__, __FILE__);
	}
	
	public static function change_extended_field_to_member(ExtendedField $extended_field)
	{
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " CHANGE " . self::select_field_name($extended_field) . " " . $extended_field->get_field_name() . " " . self::type_columm_field($extended_field), __LINE__, __FILE__);
	}

	public static function drop_extended_field_to_member(ExtendedField $extended_field)
	{
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " DROP " . self::select_field_name($extended_field), __LINE__, __FILE__);	
	}
	
	public static function select_field_name(ExtendedField $extended_field)
	{
		return PersistenceContext::get_sql()->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $extended_field->get_id() . "'", __LINE__, __FILE__);
	}
	
	public static function type_columm_field(ExtendedField $extended_field)
	{
		if (is_numeric($extended_field->get_field_type()))
		{
			$array_field_type = array(
				1 => 'VARCHAR(255) NOT NULL DEFAULT \'\'', 
				2 => 'TEXT NOT NULL', 
				3 => 'TEXT NOT NULL', 
				4 => 'TEXT NOT NULL', 
				5 => 'TEXT NOT NULL', 
				6 => 'TEXT NOT NULL'
			);
			
			return $array_field_type[$extended_field->get_field_type()];
		}
	}
	
}
?>