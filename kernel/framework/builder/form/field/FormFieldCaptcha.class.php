<?php
/*##################################################
 *                         FormFieldCaptcha.class.php
 *                            -------------------
 *   begin                : January 11, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @desc This class manage captcha validation fields to avoid bot spam.
 * @package {@package}
 */
class FormFieldCaptcha extends AbstractFormField
{
    /**
     * @var Captcha
     */
    private $captcha = '';

    /**
     * @param Captcha $captcha The captcha to use. If not given, a default captcha will be used.
     */
    public function __construct($name = 'captcha', PHPBoostCaptcha $captcha = null)
    {
        global $LANG;
        $field_options = $this->is_enabled() ? array('required' => true) : array();
        parent::__construct($name, $LANG['verif_code'], false, $field_options);
        if ($captcha !== null)
        {
            $this->captcha = $captcha;
        }
        else
        {
            $this->captcha = new PHPBoostCaptcha();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
    	$this->captcha->set_html_id($this->get_html_id());
        if ($this->is_enabled())
        {
            $this->set_value($this->captcha->is_valid());
        }
        else
        {
            $this->set_value(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $this->captcha->save_user();
        $this->captcha->set_html_id($this->get_html_id());

        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);
         
        $template->put_all(array(
			'C_IS_ENABLED' => $this->is_enabled(),
			'CAPTCHA_INSTANCE' => $this->captcha->get_instance(),
			'CAPTCHA_WIDTH' => $this->captcha->get_width(),
			'CAPTCHA_HEIGHT' => $this->captcha->get_height(),
			'CAPTCHA_FONT' => $this->captcha->get_font(),
			'CAPTCHA_DIFFICULTY' => $this->captcha->get_difficulty(),
        ));

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $this->retrieve_value();
        $result = $this->get_value();
        if (!$result)
        {
            $this->set_validation_error_message(LangLoader::get_message('captcha_validation_error', 'builder-form-Validator'));
        }
        return $result;
    }

    private function is_enabled()
    {
        return !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormFieldCaptcha.tpl');
    }
}
?>