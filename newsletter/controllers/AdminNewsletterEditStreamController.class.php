<?php
/*##################################################
 *		                   AdminNewsletterEditStreamController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminNewsletterEditStreamController extends AdminModuleController
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

	public function execute(HTTPRequest $request)
	{
		$id = $request->get_getint('id', 0);
		$this->init();
		
		if (!$this->categorie_exist($id) || $id == 0)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), LangLoader::get_message('admin.stream-not-existed', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}

		$this->build_form($id);

		$tpl = new StringTemplate('<script type="text/javascript">
		<!--
			Event.observe(window, \'load\', function() {
				if ({ADVANCED_AUTH})
				{
					$("newsletter_admin_advanced_authorizations").fade({duration: 0.2});
				}
			});
		-->		
		</script>
		# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		$tpl->put('ADVANCED_AUTH', is_array(NewsletterStreamsCache::load()->get_authorizations_by_stream($id)) ? false : true);
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($id);
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-edit-stream'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminNewsletterDisplayResponse($tpl, $this->lang['streams.add']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}

	private function build_form($id)
	{
		$newsletter_stream_cache = NewsletterStreamsCache::load()->get_stream($id);
		
		$form = new HTMLForm('newsletter_admin');
		
		$fieldset = new FormFieldsetHTML('edit-categorie', $this->lang['streams.edit']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['streams.name'], $newsletter_stream_cache['name'], array(
			'class' => 'text', 'maxlength' => 25, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('description', $this->lang['streams.description'], $newsletter_stream_cache['description'],
		array('rows' => 4, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('picture', $this->lang['streams.picture'], $newsletter_stream_cache['picture'], array(
			'class' => 'text',
			'events' => array('change' => '$(\'preview_picture\').src = HTMLForms.getField(\'picture\').getValue();')
		)));
		$fieldset->add_field(new FormFieldFree('preview_picture', $this->lang['streams.picture-preview'], '<img id="preview_picture" src="'. $newsletter_stream_cache['picture'] .'" alt="" style="vertical-align:top" />'));
		
		$fieldset->add_field(new FormFieldCheckbox('visible', $this->lang['streams.visible'], $newsletter_stream_cache['visible']));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.newsletter-authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$active_authorizations = is_array($newsletter_stream_cache['authorizations']) ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED;
		$fieldset_authorizations->add_field(new FormFieldCheckbox('active_authorizations', $this->lang['streams.active-advanced-authorizations'], $active_authorizations, 
		array('events' => array('click' => '
		if (HTMLForms.getField("active_authorizations").getValue()) {
			$("newsletter_admin_advanced_authorizations").appear(); 
		} else { 
			$("newsletter_admin_advanced_authorizations").fade();
		}')
		)));
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['streams.auth.read'], NewsletterConfig::CAT_AUTH_READ),
			new ActionAuthorization($this->lang['streams.auth.subscribe'], NewsletterConfig::CAT_AUTH_SUBSCRIBE),
			new ActionAuthorization($this->lang['streams.auth.subscribers-read'], NewsletterConfig::CAT_AUTH_READ_SUBSCRIBERS),
			new ActionAuthorization($this->lang['streams.auth.subscribers-moderation'], NewsletterConfig::CAT_AUTH_MODERATION_SUBSCRIBERS),
			new ActionAuthorization($this->lang['streams.auth.create-newsletter'], NewsletterConfig::CAT_AUTH_CREATE_NEWSLETTER),
			new ActionAuthorization($this->lang['streams.auth.archives-read'], NewsletterConfig::CAT_AUTH_READ_ARCHIVES)
		));
		
		$default_authorizations = is_array($newsletter_stream_cache['authorizations']) ? $newsletter_stream_cache['authorizations'] : NewsletterConfig::load()->get_authorizations();
		$auth_settings->build_from_auth_array($default_authorizations);
		$auth_setter = new FormFieldAuthorizationsSetter('advanced_authorizations', $auth_settings);
		$fieldset_authorizations->add_field($auth_setter);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save($id)
	{
		$auth = $this->form->get_value('active_authorizations') ? serialize($this->form->get_value('advanced_authorizations')->build_auth_array()) : null;
		PersistenceContext::get_querier()->inject(
			"UPDATE ". NewsletterSetup::$newsletter_table_streams ." SET 
			name = :name, description = :description, picture = :picture, visible = :visible, auth = :auth
			WHERE id = '". $id ."'", array(
                'name' => TextHelper::htmlspecialchars($this->form->get_value('name')),
				'description' => TextHelper::htmlspecialchars($this->form->get_value('description')),
				'picture' => TextHelper::htmlspecialchars($this->form->get_value('picture')),
				'visible' => (int)$this->form->get_value('visible'),
				'auth' => $auth
		));
		
		NewsletterStreamsCache::invalidate();
	}

	private static function categorie_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_streams, "WHERE id = '". $id ."'");
	}
}
?>