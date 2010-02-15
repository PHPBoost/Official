<?php
/*##################################################
 *                             HTMLFormFieldset.class.php
 *                            -------------------
 *   begin                : May 01, 2009
 *   copyright            : (C) 2009 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
 * @package builder
 * @subpackage form/fieldset
 * @desc
 * @author R�gis Viarre <crowkait@phpboost.com>
 */
class HTMLFormFieldset
{
	private $title = '';
	private $form_name = '';
	private $fields = array();
	private $validation_error_messages = array();

	/**
	 * @desc constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($name)
	{
		$this->title = $name;
	}

	public function set_form_name($form_name)
	{
		$this->form_name = $form_name;
	}

	/**
	 * @desc Store fields in the fieldset.
	 * @param FormField $form_field
	 */
	public function add_field($form_field)
	{
		if (isset($this->fields[$form_field->get_id()]))
		{
			throw new FormBuilderException('Field with identifier "<strong>' . $form_field->get_id() . '</strong>" already exists,
			please chose a different one!');
		}
		else
		{
			$this->fields[$form_field->get_id()] = $form_field;
		}
	}

	public function validate()
	{
		$validation_result = true;
		foreach ($this->fields as $field)
		{
			if (!$field->validate())
			{
				$validation_result = false;
				$this->validation_error_messages[] = $field->get_validation_error_message();
			}
		}
		return $validation_result;
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function display()
	{
		global $LANG;

		$template = new FileTemplate('framework/builder/form/FormFieldset.tpl');

		$template->assign_vars(array(
			'L_FORMTITLE' => $this->title
		));

		//On affiche les champs
		foreach($this->fields as $field)
		{
			$template->assign_block_vars('fields', array(
				'FIELD' => $field->display()->to_string(),
			));
		}
		return $template->to_string();
	}

	public function get_onsubmit_validations()
	{
		$validations = array();
		foreach ($this->fields as $field)
		{
			$validations[] = $field->get_js_validations();
		}
		return $validations;
	}

	public function get_validation_error_messages()
	{
		return $this->validation_error_messages;
	}

	/**
	 * @param string $title The fieldset title
	 */
	public function set_title($title) { $this->title = $title; }

	/**
	 * @param boolean $display_required
	 */
	public function set_display_required($display_required) { $this->display_required = $display_required; }

	/**
	 * @return string The fieldset title
	 */
	public function get_title() { return $this->title; }

	/**
	 * @return bool
	 */
	public function has_field($field_id) { return isset($this->fields[$field_id]); }

	/**
	 * @return FormField
	 */
	public function get_field($field_id) { return $this->fields[$field_id]; }
}

?>