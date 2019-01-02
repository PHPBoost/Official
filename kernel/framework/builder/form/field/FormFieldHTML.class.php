<?php
/**
 * This class manage free contents fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>template : A template object to personnalize the field</li>
 * 	<li>content : The field html content if you don't use a personnal template</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 04 10
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldHTML extends AbstractFormField
{
	public function __construct($id, $value)
	{
		parent::__construct($id, '', $value, array(), array());
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$template->put_all(array(
			'HTML' => $this->get_value()
		));

		return $template;
	}

	protected function get_default_template()
	{
		return new StringTemplate('{HTML}');
	}
}
?>
