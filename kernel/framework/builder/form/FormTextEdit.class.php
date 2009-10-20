<?php
/*##################################################
 *                             field_input_text.class.php
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

import('builder/form/FormField');

/**
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class manage single-line text fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The maximum size for the field</li>
 * 	<li>maxlength : The maximum length for the field</li>
 * 	<li>required_alert : Text displayed if field is empty (javscript only)</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormTextEdit extends FormField
{
	private $size = '';
	private $maxlength = '';
	
	public function __construct($field_id, $field_value, $field_options = array())
	{
		parent::__construct($field_id, $field_value, $field_options);
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'size' :
					$this->size = $value;
				break;
				case 'maxlength' :
					$this->maxlength = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$Template = new Template('framework/builder/forms/field.tpl');
			
		$field = '<input type="text" ';
		$field .= !empty($this->size) ? 'size="' . $this->size . '" ' : '';
		$field .= !empty($this->maxlength) ? 'maxlength="' . $this->maxlength . '" ' : '';
		$field .= !empty($this->name) ? 'name="' . $this->name . '" ' : '';
		$field .= !empty($this->id) ? 'id="' . $this->id . '" ' : '';
		$field .= 'value="' . $this->value . '" ';
		$field .= !empty($this->css_class) ? 'class="' . $this->css_class . '" ' : '';
		$field .= !empty($this->on_blur) ? 'onblur="' . $this->on_blur . '" ' : '';
		$field .= '/>';
		
		$Template->assign_vars(array(
			'ID' => $this->id,
			'FIELD' => $field,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'L_REQUIRE' => $this->required ? '* ' : ''
		));	
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>