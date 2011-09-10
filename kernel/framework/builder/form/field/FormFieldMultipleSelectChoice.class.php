<?php
/*##################################################
 *                             FormFieldMultipleSelectChoice.class.php
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
 * @package {@package}
 */
class FormFieldMultipleSelectChoice extends AbstractFormFieldChoice
{
    /**
     * @desc Constructs a FormFieldMultipleSelectChoice.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param FormFieldEnumOption[] $options Enumeration of the possible values
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, array $selected_options, array $available_options, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $selected_options, $available_options, $field_options, $constraints);
		$this->set_selected_options($selected_options);
    }

	private function set_selected_options(array $selected_options)
    {
    	$value = array();
    	foreach ($selected_options as $option)
    	{
    		if (is_string($option))
    		{
    			$value[] = $this->get_option($option);
    		}
    		else if ($option instanceof FormFieldSelectChoiceOption)
    		{
    			$value[] = $option;
    		}
    		else
    		{
    			throw new FormBuilderException('option ' . $option . ' isn\'t recognized');
    		}
    	}
    	$this->set_value($value);
    }
	
	public function retrieve_value()
    {
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->set_value($request->get_array($this->get_html_id()));
		}
		else
		{
			$this->set_value(array());
		}
    }
	
    /**
     * @return string The html code for the select.
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);

        $template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_code()->render(),
        ));

        return $template;
    }

    private function get_html_code()
    {
        $tpl = new FileTemplate('framework/builder/form/FormFieldMultipleSelectChoice.tpl');

		$lang = LangLoader::get('main');
        $tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'CSS_CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'L_SELECT_ALL' => $lang['select_all'],
			'L_UNSELECT_ALL' => $lang['select_none'],
			'L_SELECT_EXPLAIN' => $lang['explain_select_multiple']
        ));
		
        foreach ($this->get_options() as $option)
        {
			$select = $this->is_selected($option);
			if ($select)
			{
				$option->set_active();
			}
			
            $tpl->assign_block_vars('options', array(), array(
				'OPTION' => $option->display()
            ));
        }

        return $tpl;
    }
	
	private function is_selected($option)
    {
    	$value = $this->get_value();
    	if (is_array($value))
    	{
    		return in_array($option, $value); 
    	}
    	return false;
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}
?>