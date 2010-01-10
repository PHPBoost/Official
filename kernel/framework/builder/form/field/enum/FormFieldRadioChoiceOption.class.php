<?php
/*##################################################
 *                        FormFieldRadioOption.class.php
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
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class manage radio input field options.
 * @package builder
 * @subpackage form/field/enum
 */
class FormFieldRadioChoiceOption extends AbstractFormFieldEnumOption
{
	public function __construct($label, $raw_value)
	{
		parent::__construct($label, $raw_value);
	}

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$option = '<label><input type="radio" ';
		$option .= 'name="' . $this->get_field_id() . '" ';
		$option .= 'value="' . $this->get_raw_value() . '" ';
		$option .= $this->is_active() ? 'checked="checked" ' : '';
		$option .= '/> ' . $this->get_label() . '</label>';

		return $option;
	}
}

?>