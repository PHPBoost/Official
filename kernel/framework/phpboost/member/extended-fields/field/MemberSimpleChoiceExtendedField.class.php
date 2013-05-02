<?php
/*##################################################
 *                               MemberSimpleChoiceExtendedField.class.php
 *                            -------------------
 *   begin                : December 08, 2010
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
 
class MemberSimpleChoiceExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex'));
		$this->set_name(LangLoader::get_message('type.simple-check','admin-extended-fields-common'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$field = array();
		$array_values = $this->possible_values($member_extended_field);
		$i = 0;
		$default = '';
		foreach ($array_values as $values)
		{
			$field[] = new FormFieldRadioChoiceOption($values, $member_extended_field->get_field_name() . '_' . $i);
			if ($values == $member_extended_field->get_default_values())
			{
				$default = $member_extended_field->get_field_name() . '_' . $i;
			}
			$i++;
		}
		
		$fieldset->add_field(new FormFieldRadioChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default, $field, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$field = array();
		$array_values = $this->possible_values($member_extended_field);
		$i = 0;
		$default = '';
		foreach ($array_values as $values)
		{
			$field[] = new FormFieldRadioChoiceOption($values, $member_extended_field->get_field_name() . '_' . $i);
			if ($values == $member_extended_field->get_value())
			{
				$default = $member_extended_field->get_field_name() . '_' . $i;
			}
			$i++;
		}
		$fieldset->add_field(new FormFieldRadioChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default, $field, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		
		if ($form->has_field($field_name))
		{
			$value = $form->get_value($field_name);
			if (!empty($value))
				return $value->get_label();
		}
		
		return '';
	}
	
	private function possible_values(MemberExtendedField $member_extended_field)
	{
		return explode('|', $member_extended_field->get_possible_values());
	}
}
?>