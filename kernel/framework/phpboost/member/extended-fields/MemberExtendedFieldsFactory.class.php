<?php
/*##################################################
 *                               MemberExtendedFieldsFactory.class.php
 *                            -------------------
 *   begin                : December 10, 2010
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
 * @desc This class is a Factory and return instance class
 * @package {@package}
 */
class MemberExtendedFieldsFactory
{	
	/**
	 * @desc This function displayed field for create form
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function display_field_create(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
		
		$instance_class = new $name_class();
		return $instance_class->display_field_create($member_extended_field);
		
	}

	/**
	 * @desc This function displayed field for update form
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function display_field_update(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
	
		$instance_class = new $name_class();
		return $instance_class->display_field_update($member_extended_field);
		
	}
	
	/**
	 * @desc This function displayed field for profile
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
	
		$instance_class = new $name_class();
		return $instance_class->display_field_profile($member_extended_field);
	}
	
	/**
	 * @desc This function returned value form fields
	 * @param object $form HTMLForm
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->return_value($form, $member_extended_field);
	}
	
	/**
	 * @desc This function execute the database request
	 * @param object $member_extended_field MemberExtendedField
	 * @param object $member_extended_fields_dao MemberExtendedFieldsDAO
	 */
	public static function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao, HTMLForm $form)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->register($member_extended_field, $member_extended_fields_dao, $form);
	}
	
	/**
	 * @desc This function return Array disable fields in configuration
	 * @param string $field_type field type.
	 */
	public static function get_disable_fields_configuration($field_type)
	{
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_field_type($field_type);
		
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->get_disable_fields_configuration();
	}
	
	/**
	 * @desc This function determines the class depending on the type of field
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function name_class($member_extended_field)
	{
		$field_type = $member_extended_field->get_field_type();
		if (!empty($field_type))
		{
			return (string)$field_type;
		}
		return '';
	}
}
?>