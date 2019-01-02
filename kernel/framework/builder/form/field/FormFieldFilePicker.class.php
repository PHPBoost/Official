<?php
/**
 * This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 *  <li>size : The size for the field</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2018 11 17
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldFilePicker extends AbstractFormField
{
	private $max_size = 0;
	private $authorized_extensions = '';

	public function __construct($id, $label, array $field_options = array(), array $constraints = array())
	{
		if (isset($field_options['authorized_extensions']))
			$constraints[] = new FormFieldConstraintFileExtension($field_options['authorized_extensions']);
		parent::__construct($id, $label, null, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-file');
	}

	/**
	 * @return Template The html code for the file input.
	 */
	function display()
	{
		$template = $this->get_template_to_use();

		$file_field_tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldFilePicker.tpl');
		$file_field_tpl->put_all(array(
			'MAX_FILE_SIZE' => $this->get_max_file_size(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $file_field_tpl->render()
		));

		return $template;
	}

	private function get_max_file_size()
	{
		if ($this->max_size > 0)
		{
			return $this->max_size;
		}
		else
		{
			return 10000000000;
		}
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'max_size':
					$this->max_size = $value;
					unset($field_options['max_size']);
					// TODO add max size constraint
					break;
				case 'authorized_extensions':
					$this->authorized_extensions = $value;
					unset($field_options['authorized_extensions']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		try
		{
			$this->retrieve_value();
			return true;
		}
		catch(Exception $ex)
		{
			if ($this->is_required())
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$file = $request->get_file($this->get_html_id());
		$this->set_value($file);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
