<?php
/*##################################################
 *                         AddNewsletterController.class.php
 *                            -------------------
 *   begin                : February 8, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AddNewsletterController extends ModuleController
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
	
	private $send_test_button;
	
	public function execute(HTTPRequestCustom $request)
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->create_newsletters())
		{
			NewsletterAuthorizationsService::get_errors()->create_newsletters();
		}
		
		$type = $request->get_value('type', '');
		
		$this->init();
		$this->build_form($type);
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->send_mail($type);
			$tpl->put('MSG', MessageHelper::display($this->lang['newsletter.success-add'], E_USER_SUCCESS, 4));
		}
		else if ($this->send_test_button->has_been_submited() && $this->form->validate())
		{
			$this->send_test($type);
			$tpl->put('MSG', MessageHelper::display($this->lang['newsletter.success-send-test'], E_USER_SUCCESS, 4));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl, $type);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}
	
	private function build_form($type)
	{
		$form = new HTMLForm('newsletter');

		$fieldset = new FormFieldsetHTML('add-newsletter', $this->lang['newsletter-add']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldMultipleSelectChoice('newsletter_choice', $this->lang['add.choice_streams'], array(), $this->get_streams(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['newsletter.title'], NewsletterConfig::load()->get_newsletter_name(), array(
			'class' => 'text', 'required' => true)
		));
		
		$fieldset->add_field($this->return_editor($type));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->send_test_button = new FormButtonSubmit($this->lang['add.send_test'], 'send_test');
		$form->add_button($this->submit_button);
		$form->add_button($this->send_test_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function send_mail($type)
	{
		$streams = array();
		foreach ($this->form->get_value('newsletter_choice') as $field => $option)
		{
			$streams[] = $option->get_raw_value();
		}
			
		NewsletterService::add_newsletter(
			$streams, 
			$this->form->get_value('title'), 
			$this->form->get_value('contents'),
			$type
		);
	}
	
	private function send_test($type)
	{
		$newsletter_config = NewsletterConfig::load();
		$subscribers[] = array('mail' => $newsletter_config->get_mail_sender());
		NewsletterMailFactory::send_mail(
			$subscribers, 
			$type, 
			$newsletter_config->get_mail_sender(), 
			$this->form->get_value('title'), 
			$this->form->get_value('contents')
		);
	}
	
	private function build_response(View $view, $type)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->absolute());
		$breadcrumb->add($this->lang['newsletter-add'], NewsletterUrlBuilder::add_newsletter()->absolute());
		$breadcrumb->add($this->lang['newsletter.types.' . $type], NewsletterUrlBuilder::add_newsletter($type)->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter-add']);
		return $response;
	}
	
	private function get_streams()
	{
		$streams = array();
		$newsletter_streams_cache = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams_cache as $id => $value)
		{
			$read_auth = NewsletterAuthorizationsService::id_stream($id)->create_newsletters();
			if ($read_auth && $value['visible'] == 1)
			{
				$streams[] = new FormFieldSelectChoiceOption($value['name'], $id);
			}
		}
		return $streams;
	}
	
	public function return_editor($type)
	{
		if ($type == 'bbcode')
		{
			return new FormFieldRichTextEditor('contents', $this->lang['newsletter.contents'], '', array(
				'rows' => 10, 'cols' => 47, 'required' => true)
			);
		}
		else
		{
			return new FormFieldMultiLineTextEditor('contents', $this->lang['newsletter.contents'], '', array(
				'rows' => 10, 'cols' => 47, 'required' => true)
			);
		}
	}
}
?>