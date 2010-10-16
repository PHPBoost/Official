<?php
/*##################################################
 *                       FormFieldRadioChoice.class.php
 *                            -------------------
 *   begin                : April 28, 2009
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
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class manage radio input fields.
 * @package {@package}
 */
class FormFieldRadioChoice extends AbstractFormFieldChoice
{
	/**
	 * @desc Constructs a FormFieldRadioChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value (either a FormFieldRadioChoiceOption object or a string corresponding to the FormFieldRadioChoiceOption raw value)
	 * @param FormFieldRadioChoiceOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, $value, $options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $options, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		foreach ($this->get_options() as $option)
		{
			$template->assign_block_vars('fieldelements', array(
				'ELEMENT' => $option->display()->render(),
			));
		}

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}

	protected function get_js_specialization_code()
	{
		return 'field.disabled = ' . ($this->is_disabled() ? 'true' : 'false') . ';' .
			'field.disable = function() { this.disabled = true; }; field.enable = function() { this.disabled = false; };' .
			'field.isDisabled = function() { return this.disabled; };';
	}
}
?>