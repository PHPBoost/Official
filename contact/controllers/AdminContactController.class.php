<?php
/*##################################################
 *                       AdminContactController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
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

class AdminContactController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['success_saving_config'], E_USER_SUCCESS, 2));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('contact_common', 'contact');
	}

	private function build_form()
	{
		$form = new HTMLForm('contact_admin');
		$config = ContactConfig::load();

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['contact_config']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('enable_captcha', $this->lang['enable_captcha'], $config->is_captcha_enabled(),
			array('events' => array('click' => 'if (HTMLForms.getField("enable_captcha").getValue()) { HTMLForms.getField("captcha_difficulty_level").enable(); } else { HTMLForms.getField("captcha_difficulty_level").disable(); }'))));
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_difficulty_level', $this->lang['captcha_difficulty'], $config->get_captcha_difficulty_level(), $this->generate_difficulty_level_options(),
			array('hidden' => !$config->is_captcha_enabled(), 'required' => true)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function generate_difficulty_level_options()
	{
		$options = array();
		for ($i = 0; $i <= 4; $i++)
		{
			$options[] = new FormFieldSelectChoiceOption($i, $i);
		}
		return $options;
	}
	
	private function save()
	{
		$config = ContactConfig::load();
		if ($this->form->get_value('enable_captcha'))
		{
			$config->enable_captcha();
			$config->set_captcha_difficulty_level($this->form->get_value('captcha_difficulty_level')->get_raw_value());
		}
		else
		{
			$config->disable_captcha();
		}
		ContactConfig::save();
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['title_contact']);
		$response->add_link($this->lang['contact_config'], '/contact/' . url('index.php?url=/admin', 'admin/'), '/contact/contact.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['title_contact']);
		return $response;
	}
}
?>