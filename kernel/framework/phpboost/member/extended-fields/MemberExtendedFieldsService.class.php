<?php
/*##################################################
 *                               MemberExtendedFieldsService.class.php
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
 * @desc This class is responsible for updated, displayed and registed of member extended fields.
 * @package {@package}
 */
class MemberExtendedFieldsService
{
	/**
	 * @desc This function displayed fields form
	 * @param object $member_extended_field MemberExtendedField containing user_id and Template. If user is not registered, use object MemberExtendedField, and define user_id of null.
	 */
	public static function display_form_fields(MemberExtendedField $member_extended_field)
	{
		$extended_fields_displayed = MemberExtendedFieldsDAO::extended_fields_displayed();
        if ($extended_fields_displayed)
		{
			$template = $member_extended_field->get_template();
			$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
			$member_extended_field->set_fieldset($fieldset);
			$template->add_fieldset($fieldset);

			$user_id = $member_extended_field->get_user_id();
			if($user_id == null)
			{
				self::display_create_form($member_extended_field);
			}
			else
			{
				self::display_update_form($member_extended_field);
			}
		}
	}

	/**
	 * @desc This function displayed fields profile
	 * @param object $member_extended_field MemberExtendedField containing user_id, Template and field_type.
	 */
	public static function display_profile_fields(MemberExtendedField $member_extended_field)
	{
		$extended_fields_displayed = MemberExtendedFieldsDAO::extended_fields_displayed();
        if ($extended_fields_displayed)
		{
			$user_id = $member_extended_field->get_user_id();
			if($user_id !== null)
			{
				$template = $member_extended_field->get_template();
				$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
				$member_extended_field->set_fieldset($fieldset);

				$nbr_field = 0;
				$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.field_name, exc.default_values, exc.auth, exc.field_type, ex.*
				FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " exc
				LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = '" . $user_id . "'
				WHERE exc.display = 1
				ORDER BY exc.position", __LINE__, __FILE__);
				while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
				{
					$member_extended_field->set_name($extended_field['name']);
					$member_extended_field->set_field_name($extended_field['field_name']);
					$member_extended_field->set_field_type($extended_field['field_type']);
					$value = !empty($extended_field[$extended_field['field_name']]) ? $extended_field[$extended_field['field_name']] : $extended_field['default_values'];
					$member_extended_field->set_value($value);
					
					$authorizations = unserialize($extended_field['auth']);
					if (AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_PROFILE_AUTHORIZATION))
					{
						MemberExtendedFieldsFactory::display_field_profile($member_extended_field);
						$nbr_field++;
					}
				}
				
				if ($nbr_field > 0)
				{
					$template->add_fieldset($fieldset);
				}
				
				PersistenceContext::get_sql()->query_close($result);
			}
		}
	}

	/**
	 * @desc This function register fields
	 * @param object $member_extended_field MemberExtendedField
	 */
	public static function register_fields(HTMLForm $form, $user_id)
	{
		if(!empty($user_id))
		{
			$extended_fields_displayed = MemberExtendedFieldsDAO::extended_fields_displayed();
			if ($extended_fields_displayed)
			{
				$member_extended_fields_dao = new MemberExtendedFieldsDAO();
				$extended_fields_cache = ExtendedFieldsCache::load()->get_extended_fields();
				foreach ($extended_fields_cache as $id => $extended_field)
				{
					if ($extended_field['display'] == 1)
					{
						$member_extended_field = new MemberExtendedField();		
						$member_extended_field->set_field_type($extended_field['field_type']);
						$member_extended_field->set_field_name($extended_field['field_name']);
						$member_extended_field->set_user_id($user_id);
						
						try {
							$value = MemberExtendedFieldsFactory::return_value($form, $member_extended_field);
							$member_extended_field->set_value($value);
							
							$authorizations = $extended_field['auth'];
							if (AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
							{
								MemberExtendedFieldsFactory::register($member_extended_field, $member_extended_fields_dao, $form);
							}
						} catch (MemberExtendedFieldErrorsMessageException $e) {
							throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
						}
					}
				}
				$member_extended_fields_dao->execute_request($user_id);
			}
		}
	}
	
	/**
	 * @desc This private function display form create
	 * @param object $member_extended_field MemberExtendedField
	 */
	private static function display_create_form(MemberExtendedField $member_extended_field)
	{
		$extended_fields_cache = ExtendedFieldsCache::load()->get_extended_fields();	
		foreach ($extended_fields_cache as $id => $extended_field)
		{
			if ($extended_field['display'] == 1)
			{
				$member_extended_field->set_name($extended_field['name']);
				$member_extended_field->set_field_name($extended_field['field_name']);
				$member_extended_field->set_description($extended_field['description']);
				$member_extended_field->set_field_type($extended_field['field_type']);
				$member_extended_field->set_possible_values($extended_field['possible_values']);
				$member_extended_field->set_default_values($extended_field['default_values']);
				$member_extended_field->set_required($extended_field['required']);
				$member_extended_field->set_regex($extended_field['regex']);
				
				$authorizations = $extended_field['auth'];
				if (AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
				{
					MemberExtendedFieldsFactory::display_field_create($member_extended_field);
				}
			}
		}
	}
	
	/**
	 * @desc This private function display form update
	 * @param object $member_extended_field MemberExtendedField
	 */
	private static function display_update_form(MemberExtendedField $member_extended_field)
	{
		$user_id = $member_extended_field->get_user_id();
		$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.description, exc.field_type, exc.required, exc.field_name, exc.possible_values, exc.default_values, exc.auth, exc.regex, ex.*
		FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = '" . $user_id . "'
		WHERE exc.display = 1
		ORDER BY exc.position", __LINE__, __FILE__);
		while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
		{
			$member_extended_field->set_field_type($extended_field['field_type']);
			$value = !empty($extended_field[$extended_field['field_name']]) ? $extended_field[$extended_field['field_name']] : $extended_field['default_values'];
			
			$member_extended_field->set_name($extended_field['name']);
			$member_extended_field->set_field_name($extended_field['field_name']);
			$member_extended_field->set_description($extended_field['description']);
			$member_extended_field->set_value($value);
			$member_extended_field->set_possible_values($extended_field['possible_values']);
			$member_extended_field->set_default_values($extended_field['default_values']);
			$required = $member_extended_field->get_is_admin() ? 0 : $extended_field['required'];
			$member_extended_field->set_required($required);
			$member_extended_field->set_regex($extended_field['regex']);
			
			$authorizations = unserialize($extended_field['auth']);
			if (AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
			{
				MemberExtendedFieldsFactory::display_field_update($member_extended_field);
			}
		}
		PersistenceContext::get_sql()->query_close($result);
	}
	
	/**
	 * @desc This public function return the data sent by the user depending field_name
	 */
	public static function return_field_member($field_name, $user_id, $rewrite = false)
	{
		if ($rewrite)
		{
			$field_name = 'f_' . $field_name;
		}

		$data = MemberExtendedFieldsDAO::select_data_field_by_user_id($user_id);
		return !empty($data[$field_name]) && isset($data[$field_name]) ? $data[$field_name] : '';
	}
}
?>