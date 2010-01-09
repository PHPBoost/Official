<?php
/*##################################################
 *                             FormFieldSelect.class.php
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
 * @desc This class manage select fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>multiple : Type of select field, mutiple allow you to check several options.</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormFieldSelect implements FormField
{
	private $options = array();
	private $multiple = false;

	public function __construct($field_id, array $field_options = array(), array $options = array(), array $constraints = array())
	{
		parent::__construct($field_id, '', $field_options, $constraints);
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'multiple' :
					$this->multiple = $value;
					break;
				default :
					throw new FormBuilderException(sprintf('Unsupported option %s with field option ' . __CLASS__, $attribute));
			}
		}
		$this->options = $options;
	}

	/**
	 * @desc Add an option for the radio field.
	 * @param FormFieldSelectOption option The new option.
	 */
	public function add_option($option)
	{
		$this->options[] = $option;
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$template = new Template('framework/builder/forms/field_select.tpl');
		$template->assign_vars(array(
			'ID' => $this->id,
			'C_SELECT_MULTIPLE' => $this->multiple,
			'L_FIELD_NAME' => $this->name,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'C_REQUIRED' => $this->is_required()
		));

		foreach ($this->options as $option)
		{
			$template->assign_block_vars('field_options', array(
				'OPTION' => $option->display(),
			));
		}

		return $template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>