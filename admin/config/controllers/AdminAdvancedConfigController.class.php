<?php
/*##################################################
 *                       AdminAdvancedConfigController.class.php
 *                            -------------------
 *   begin                : July 1, 2011
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

class AdminAdvancedConfigController extends AdminController
{
	private $lang;
	private $general_config;
	private $server_environment_config;
	private $sessions_config;
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE FORM #');
		
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			AppContext::get_response()->redirect(AdminConfigUrlBuilder::advanced_config());
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($tpl, $this->lang['advanced-config']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}

	private function load_config()
	{
		$this->general_config = GeneralConfig::load();
		$this->server_environment_config = ServerEnvironmentConfig::load();
		$this->sessions_config = SessionsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('advanced-config');
		
		$fieldset = new FormFieldsetHTML('advanced-config', $this->lang['advanced-config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('site_url', $this->lang['advanced-config.site_url'], $this->general_config->get_site_url(), array(
			'class' => 'text', 'description' => $this->lang['advanced-config.site_url-explain'], 'size' => 25, 'required' => true),
			array(new FormFieldConstraintUrl())
		));
		
		$fieldset->add_field(new FormFieldTextEditor('site_path', $this->lang['advanced-config.site_path'], $this->general_config->get_site_path(),
			array('class' => 'text', 'description' => $this->lang['advanced-config.site_path-explain'])
		));
		
		$fieldset->add_field(new FormFieldTimezone('site_timezone', $this->lang['advanced-config.site_timezone'], $this->general_config->get_site_timezone(),
		array('description' => $this->lang['advanced-config.site_timezone-explain'])));
		
		$url_rewriting_fieldset = new FormFieldsetHTML('url_rewriting', $this->lang['advanced-config.url-rewriting']);
		$form->add_fieldset($url_rewriting_fieldset);
		
		$url_rewriting_fieldset->set_description($this->lang['advanced-config.url-rewriting.explain']);
		
		$server_configuration = new ServerConfiguration();
		try 
		{
			if ($server_configuration->has_url_rewriting())
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(), array('description' => $this->lang['advanced-config.config.available'])));
			}
			else
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], FormFieldCheckbox::UNCHECKED, array('disabled' => true, 'description' => $this->lang['advanced-config.config.not-available'])));
			}
		} 
		catch (UnsupportedOperationException $ex) 
		{
			$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(), array('description' => $this->lang['advanced-config.config.unknown'])));
		}
		
		$htaccess_manual_content_fieldset = new FormFieldsetHTML('htaccess_manual_content', $this->lang['advanced-config.htaccess-manual-content']);
		$form->add_fieldset($htaccess_manual_content_fieldset);
		
		$htaccess_manual_content_fieldset->add_field(new FormFieldMultiLineTextEditor('htaccess_manual_content', $this->lang['advanced-config.htaccess-manual-content'], $this->server_environment_config->get_htaccess_manual_content(),
			array('rows' => 7, 'description' => $this->lang['advanced-config.htaccess-manual-content.explain'])
		));
		
		$sessions_config_fieldset = new FormFieldsetHTML('sessions_config', $this->lang['advanced-config.sessions-config']);
		$form->add_fieldset($sessions_config_fieldset);
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('cookie_name', $this->lang['advanced-config.cookie-name'], $this->sessions_config->get_cookie_name(), array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true),
			array(new FormFieldConstraintRegex('`^[A-Za-z0-9]+$`i', '', $this->lang['advanced-config.cookie-name.style-wrong']))
		));
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('session_duration', $this->lang['advanced-config.cookie-duration'], $this->sessions_config->get_session_duration(), array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['advanced-config.cookie-duration.explain'], 'size' => 8, 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i', '', $this->lang['advanced-config.integer-required']))
		));
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('active_session_duration', $this->lang['advanced-config.active-session-duration'], $this->sessions_config->get_active_session_duration(), array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['advanced-config.active-session-duration.explain'], 'size' => 8, 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i', '', $this->lang['advanced-config.integer-required']))
		));
		
		$miscellaneous_fieldset = new FormFieldsetHTML('miscellaneous', $this->lang['advanced-config.miscellaneous']);
		$form->add_fieldset($miscellaneous_fieldset);
		
		if (function_exists('ob_gzhandler') && @extension_loaded('zlib'))
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], $this->server_environment_config->is_output_gziping_enabled(), 
			array('description' => $this->lang['advanced-config.config.available'])));
		}
		else
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], FormFieldCheckbox::UNCHECKED, 
			array('description' => $this->lang['advanced-config.config.not-available'], 'disabled' => true)));
		}
		
		$miscellaneous_fieldset->add_field(new FormFieldFree('unlock_administration', $this->lang['advanced-config.unlock-administration'], '<a href="'. AdminConfigUrlBuilder::unlock_administration()->absolute() .'" onclick="return confirm(\'' . $this->lang['advanced-config.confirm_mail_sending'] .'\');">'. $this->lang['advanced-config.unlock-administration.request'] .'</a>', 
		array('description' => $this->lang['advanced-config.unlock-administration.explain'])));
		
		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('debug_mode_enabled', $this->lang['advanced-config.debug-mode'], Debug::is_debug_mode_enabled(), 
		array('description' => $this->lang['advanced-config.debug-mode.explain'], 'events' => array('change' => '
				if (HTMLForms.getField("debug_mode_enabled").getValue()) { 
					HTMLForms.getField("debug_mode_type").enable(); 
				} else { 
					HTMLForms.getField("debug_mode_type").disable(); 
				}'))));
		
		$debug_mode_type = Debug::is_strict_mode_enabled() ? '1' : '0';
		$miscellaneous_fieldset->add_field(new FormFieldSimpleSelectChoice('debug_mode_type', $this->lang['advanced-config.debug-mode.type'], $debug_mode_type,
			array(
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.normal'], '0'),
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.strict'], '1')
			), 
			array('hidden' => !Debug::is_debug_mode_enabled())
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		$this->general_config->set_site_url($this->form->get_value('site_url'));
		$this->general_config->set_site_path($this->form->get_value('site_path'));
		$this->general_config->set_site_timezone($this->form->get_value('site_timezone')->get_raw_value());
		GeneralConfig::save();
		
		$this->sessions_config->set_cookie_name($this->form->get_value('cookie_name'));
		$this->sessions_config->set_session_duration($this->form->get_value('session_duration'));
		$this->sessions_config->set_active_session_duration($this->form->get_value('active_session_duration'));
		SessionsConfig::save();
		
		if (!$this->form->field_is_disabled('url_rewriting_enabled'))
		{
			$this->server_environment_config->set_url_rewriting_enabled($this->form->get_value('url_rewriting_enabled'));
		}
		
		$this->server_environment_config->set_htaccess_manual_content($this->form->get_value('htaccess_manual_content'));
		
		if (!$this->form->field_is_disabled('output_gziping_enabled'))
		{
			$this->server_environment_config->set_output_gziping_enabled($this->form->get_value('output_gziping_enabled'));
		}
		
		ServerEnvironmentConfig::save();
		$this->clear_cache();
		
		if ($this->form->get_value('debug_mode_enabled') && $this->form->get_value('debug_mode_type')->get_raw_value() == '1')
		{
			Debug::enabled_debug_mode(array(Debug::STRICT_MODE => true));
		}
		elseif ($this->form->get_value('debug_mode_enabled') && $this->form->get_value('debug_mode_type')->get_raw_value() == '0')
		{
			Debug::enabled_debug_mode(array());
		}
		else
		{
			Debug::disable_debug_mode();
		}
		
		HtaccessFileCache::regenerate();
	}
	
	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>