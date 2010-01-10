<?php
/*##################################################
 *                      FormFieldSelectGroupOption.class.php
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
 * @desc This class manage select field options.
 * @package builder
 * @subpackage form
 */
class FormFieldSelectChoiceGroupOption extends AbstractFormFieldEnumOption
{
	private $options = array();

	/**
	 * @param string $label string The label
	 * @param FormFieldSelectChoiceOption[] $options The group's options
	 */
	public function __construct($label, array $options)
	{
		parent::__construct($label, '');
		$this->options = $options;
	}

	public function set_field(FormField $field)
	{
		parent::set_field($field);
		foreach ($this->options as $option)
		{
			$option->set_field($field);
		}
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$code = '<optgroup ';
		$code .= 'label="' . htmlspecialchars($this->get_label()) .'" ';
		$code .= '>';

		foreach ($this->options as $option)
		{
			$code .= $option->display();
		}

		$code .= '</optgroup>';
		return $code;
	}

	public function get_option($raw_option)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $raw_option)
			{
				return $option;
			}
		}
		return null;
	}
}
?>