<?php
/*##################################################
 *                               MemberExtendedField.class.php
 *                            -------------------
 *   begin                : September 2, 2010
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
 * @desc This class represente a member extended field
 * @package {@package}
 */
class MemberExtendedField
{
	private $name;
	private $field_name;
	private $description;
	private $field_type;
	private $value;
	private $default_values;
	private $possible_values;
	private $required;
	private $regex;
	
	private $user_id;
	private $is_admin;
	private $template;
	private $fieldset;
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_field_name($field_name)
	{
		$this->field_name = $field_name;
	}
	
	public function get_field_name()
	{
		return $this->field_name;
	}
	
	public function set_field_type($field_type)
	{
		$this->field_type = $field_type;
	}
	
	public function get_field_type()
	{
		return $this->field_type;
	}
	
	public function set_value($value)
	{
		$this->value = $value;
	}
	
	public function get_value()
	{
		return $this->value;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_default_values($default_values)
	{
		$this->default_values = $default_values;
	}
	
	public function get_default_values()
	{
		return $this->default_values;
	}
	
	public function set_possible_values($possible_values)
	{
		$this->possible_values = $possible_values;
	}
	
	public function get_possible_values()
	{
		return $this->possible_values;
	}
	
	public function set_required($required)
	{
		$this->required = $required;
	}
	
	public function get_required()
	{
		return $this->required;
	}
	
	public function set_regex($regex)
	{
		$this->regex = $regex;
	}
	
	public function get_regex()
	{
		return $this->regex;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_is_admin($is_admin)
	{
		$this->is_admin = $is_admin;
	}
	
	public function get_is_admin()
	{
		return $this->is_admin;
	}
	
	public function set_template($template)
	{
		$this->template = $template;
	}
	
	public function get_template()
	{
		return $this->template;
	}
	
	public function set_fieldset($fieldset)
	{
		$this->fieldset = $fieldset;
	}
	
	public function get_fieldset()
	{
		return $this->fieldset;
	}
}
?>