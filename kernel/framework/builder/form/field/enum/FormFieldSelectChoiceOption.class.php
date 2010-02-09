<?php
/*##################################################
 *                       FormFieldSelectOption.class.php
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
class FormFieldSelectChoiceOption extends AbstractFormFieldEnumOption
{
	/**
	 * @param $label string The label
	 * @param $raw_value string The raw value
	 */
	public function __construct($label, $raw_value)
	{
		parent::__construct($label, $raw_value);
	}
		
	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$tpl_src = '<option value="{VALUE}" # IF C_SELECTED # selected="selected" # ENDIF # >{LABEL}</option>';
		
		$tpl = new StringTemplate($tpl_src);
		$tpl->assign_vars(array(
			'VALUE' => $this->get_raw_value(),
			'C_SELECTED' => $this->is_active(),
			'LABEL' => $this->get_label()
		));
		
		return $tpl;
	}
}

?>