<?php
/*##################################################
 *                       AdminExtendedFieldMemberAddController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
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

class AdminExtendedFieldMemberAddController extends AdminController
{
	private $tpl;
	
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$this->tpl = new StringTemplate('<script type="text/javascript">
				<!--
					Event.observe(window, \'load\', function() {
						'. $this->get_events_select_type() .'
					});
				-->		
				</script>
				# INCLUDE MSG #
				# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);

		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$error = ExtendedFieldsService::get_error();
			if (!empty($error))
			{
				$this->tpl->put('MSG', MessageHelper::display($error, E_USER_NOTICE, 6));
			}
			else
			{
				$this->tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'errors-common'), MessageHelper::SUCCESS, 6));
			}
		}

		$this->tpl->put('FORM', $this->form->display());

		return new AdminExtendedFieldsDisplayResponse($this->tpl, $this->lang['extended-field-add']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-extended-fields-common');
	}

	private function build_form()
	{
		$form = new HTMLForm('extended-fields-add');
		
		$fieldset = new FormFieldsetHTML('add_fields', $this->lang['extended-field-add']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['field.name'], '', array(
			'class' => 'text', 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('description', $this->lang['field.description'], '',
		array('rows' => 4, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_type', $this->lang['field.type'], '1',
			$this->get_array_select_type(),
			array('events' => array('change' => $this->get_events_select_type()))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('regex_type', $this->lang['field.regex'], '0',
			array(
				new FormFieldSelectChoiceOption('--', '0'),
				new FormFieldSelectChoiceOption($this->lang['regex.figures'], '1'),
				new FormFieldSelectChoiceOption($this->lang['regex.letters'], '2'),
				new FormFieldSelectChoiceOption($this->lang['regex.figures-letters'], '3'),
				new FormFieldSelectChoiceOption($this->lang['regex.mail'], '4'),
				new FormFieldSelectChoiceOption($this->lang['regex.website'], '5'),
				new FormFieldSelectChoiceOption($this->lang['regex.personnal-regex'], '6'),
			),
			array('description' => $this->lang['field.regex-explain'], 'events' => array('change' => '
				if (HTMLForms.getField("regex_type").getValue() == 6) { 
					HTMLForms.getField("regex").enable(); 
				} else { 
					HTMLForms.getField("regex").disable(); 
				}'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('regex', $this->lang['regex.personnal-regex'], '', array(
			'class' => 'text', 'maxlength' => 25)
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('field_required', $this->lang['field.required'], '1',
			array(
				new FormFieldRadioChoiceOption($this->lang['field.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['field.no'], '0')
			), array('description' => $this->lang['field.required_explain'])
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('possible_values', $this->lang['field.possible-values'], '', array(
			'class' => 'text', 'width' => 60, 'rows' => 4,'description' => $this->lang['field.possible-values-explain'])
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('default_values', $this->lang['field.default-values'], '', array(
			'class' => 'text', 'width' => 60, 'rows' => 4,'description' => $this->lang['field.default-values-explain'])
		));

		$fieldset->add_field(new FormFieldRadioChoice('display', LangLoader::get_message('display', 'main'), '1',
			array(
				new FormFieldRadioChoiceOption($this->lang['field.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['field.no'], '0')
			)
		));

		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['field.read_authorizations'], ExtendedField::READ_PROFILE_AUTHORIZATION), new ActionAuthorization($this->lang['field.actions_authorizations'], ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION)));
		$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 3, 'r-1' => 3));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$extended_field = new ExtendedField();
		$extended_field->set_name(TextHelper::htmlspecialchars($this->form->get_value('name')));
		$extended_field->set_field_name(ExtendedField::rewrite_field_name($this->form->get_value('name')));
		$extended_field->set_position(PersistenceContext::get_sql()->query("SELECT MAX(position) + 1 FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . "", __LINE__, __FILE__));
		$extended_field->set_description(TextHelper::htmlspecialchars($this->form->get_value('description')));
		$field_type = $this->form->get_value('field_type')->get_raw_value();
		$extended_field->set_field_type($field_type);
		$extended_field->set_possible_values(TextHelper::htmlspecialchars($this->form->get_value('possible_values', '')));
		$extended_field->set_default_values(TextHelper::htmlspecialchars($this->form->get_value('default_values', '')));
		$extended_field->set_is_required((bool)$this->form->get_value('field_required')->get_raw_value());
		$extended_field->set_display((bool)$this->form->get_value('display')->get_raw_value());
		$regex = 0;
		
		if (!$this->form->field_is_disabled('regex_type'))
		{
			$regex = is_numeric($this->form->get_value('regex_type', '')->get_raw_value()) ? $this->form->get_value('regex_type', '')->get_raw_value() : $this->form->get_value('regex', '');
		}
		
		$extended_field->set_regex($regex);
		$extended_field->set_authorization($this->form->get_value('authorizations')->build_auth_array());
		$extended_field->set_is_not_installer(true);

		ExtendedFieldsService::add($extended_field);
	}

	private function get_array_select_type()
	{
		$select = array();
		foreach ($this->get_extended_fields_class_name() as $module => $files)
		{
			if ($module == 'kernel')
			{
				$kernel_select = array();
				foreach ($files as $field_type)
				{
					$kernel_select[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
				}
				$select[] = new FormFieldSelectChoiceGroupOption($this->lang['default-field'], $kernel_select);
			}
			else
			{
				$module_select = array();
				foreach ($files as $field_type)
				{
					$module_select[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
				}

				$module_name = ModulesManager::get_module($module)->get_configuration()->get_name();
				$select[] = new FormFieldSelectChoiceGroupOption($module_name, $module_select);
			}
		}
		return $select;
	}
	
	private function get_events_select_type()
	{
		$event = '';
		$disable_fields = $this->get_disable_fields();
		foreach ($disable_fields as $name_field_disable => $field_type)
		{
			if (!empty($field_type))
			{
				$one_field = array_shift($field_type);
				$event .= 'if (HTMLForms.getField("field_type").getValue() == "'. $one_field .'"';
				foreach ($field_type as $name)
				{
					$event .= ' || HTMLForms.getField("field_type").getValue() == "'. $name .'"';
				}
				$event .= ') { 
					HTMLForms.getField("' .$name_field_disable. '").disable();';
					if ($name_field_disable == 'regex')
					{
						$event .= 'HTMLForms.getField("regex").disable();
						HTMLForms.getField("regex_type").disable();';
					}
					$event .= '} else {	HTMLForms.getField("' .$name_field_disable. '").enable();';
					if ($name_field_disable == 'regex')
					{
						$event .= 'HTMLForms.getField("regex").disable();
						HTMLForms.getField("regex_type").enable();';
					}
					$event .= '}';
			}
		}
		return $event;
	}

	private function get_disable_fields()
	{
		$disable_field = array(
			'name' => array(), 
			'description' => array(), 
			'possible_values' => array(), 
			'default_values' => array(), 
			'regex' => array(), 
			'authorizations' => array()
		);
		
		foreach ($this->get_extended_fields_class_name() as $module => $files)
		{
			foreach ($files as $field_type)
			{
				$disable_fields_extended_field = $field_type->get_disable_fields_configuration();
				
				foreach ($disable_fields_extended_field as $name_disable_field)
				{
					if (array_key_exists($name_disable_field, $disable_field))
					{
						$disable_field[$name_disable_field][] = get_class($field_type);
					}
				}
			}
		}
		return $disable_field;
	}
	
	private function get_extended_fields_class_name()
	{
		$providers = AppContext::get_extension_provider_service()->get_providers(ExtendedFieldExtensionPoint::EXTENSION_POINT);
		
		$extended_fields_class_name = array();
		foreach ($providers as $name_provider => $properties)
		{
			$extended_fiel_extension_point = $properties->get_extension_point(ExtendedFieldExtensionPoint::EXTENSION_POINT);
			$extended_fields = $extended_fiel_extension_point->get_extended_fields();
			
			$extended_fields_class_name[$name_provider] = $extended_fields;
		}
		return $extended_fields_class_name;
	}
}
?>