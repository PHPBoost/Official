<?php
/*##################################################
 *                               MemberUserThemeExtendedFieldType.class.php
 *                            -------------------
 *   begin                : December 09, 2010
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
 
class MemberUserThemeExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), UserAccountsConfig::load()->get_default_theme(),
			$this->list_theme(),
			array('events' => array('change' => 'change_img_theme(\'img_theme\', HTMLForms.getField("'. $member_extended_field->get_field_name() .'").getValue())'))
		));
		$fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['preview'], '<img id="img_theme" src="../templates/'. UserAccountsConfig::load()->get_default_theme() .'/theme/images/theme.jpg" alt="" style="vertical-align:top" />'));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), UserAccountsConfig::load()->get_default_theme(),
			$this->list_theme(),
			array('events' => array('change' => 'change_img_theme(\'img_theme\', HTMLForms.getField("'. $member_extended_field->get_field_name() .'").getValue())'))
		));
		$fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['preview'], '<img id="img_theme" src="../templates/'. UserAccountsConfig::load()->get_default_theme() .'/theme/images/theme.jpg" alt="" style="vertical-align:top" />'));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->get_raw_value();
	}
	
	private function list_theme()
	{
		$choices_list = array();
		$user_accounts_config = UserAccountsConfig::load();
		if (!$user_accounts_config->is_users_theme_forced()) // If users can choose the theme they use
		{
			foreach(ThemesCache::load()->get_installed_themes() as $theme => $theme_properties)
			{
				if (UserAccountsConfig::load()->get_default_theme() == $theme || (AppContext::get_user()->check_auth($theme_properties['auth'], AUTH_THEME) && $theme != 'default'))
				{				
					$info_theme = load_ini_file('../templates/' . $theme . '/config/', UserAccountsConfig::load()->get_default_lang());
					$choices_list[] = new FormFieldSelectChoiceOption( $info_theme['name'], $theme);
				}
			}
		}
		else
		{
			$choices_list[] = new FormFieldSelectChoiceOption('Base', 'base');
		}
		return $choices_list;
	}
}
?>