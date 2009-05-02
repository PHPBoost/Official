<?php
/*##################################################
 *                             field_input_radio.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('builder/forms/form_field');
import('builder/forms/field_input_radio_option');

/**
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class manage radio input fields.
 * @package builder
 */
class FormInputRadio extends FormField
{
	/**
	 * @desc constructor It takes a variable number of parameters. The first two are required. 
	 * @param string $fieldId Name of the field.
	 * @param array $fieldOptions Option for the field.
	 * @param FormInputRadioOption Pass variable number of FormInputRadioOption object to add in the FormInputRadio.
	 */
	function FormInputRadio()
	{
		$fieldId = func_get_arg(0);
		$field_options = func_get_arg(1);

		parent::FormField($fieldId, $field_options);
		
		$nbr_arg = func_num_args() - 1;		
		for ($i = 2; $i <= $nbr_arg; $i++)
			$this->field_options[] = func_get_arg($i);
	}
	
	/**
	 * @desc Add an option for the radio field.
	 * @param FormInputRadioOption option The new option. 
	 */
	function add_option(&$option)
	{
		$this->field_options[] = $option;
	}
	
	/**
	 * @return string The html code for the radio input.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/field_box.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $this->field_options,
			'L_FIELD_TITLE' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		foreach($this->field_options as $Option)
		{
			$Option->field_name = $this->field_name; //Set the same field name for each option.
			$Template->assign_block_vars('field_options', array(
				'OPTION' => $Option->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_options = array();
}

?>