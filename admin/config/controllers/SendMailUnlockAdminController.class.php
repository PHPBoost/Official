<?php
/*##################################################
 *                       SendMailUnlockAdminController.class.php
 *                            -------------------
 *   begin                : August 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class SendMailUnlockAdminController extends AdminController 
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$unlock_admin_clean = KeyGenerator::generate_key(18);
		
		if ($this->send_mail($unlock_admin_clean))
		{
			$this->save_unlock_code($unlock_admin_clean);
			
			$controller = new UserErrorController($this->lang['advanced-config.unlock-administration'], $this->lang['advanced-config.code_sent_success'], 1);
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}
		else 
		{
			$controller = new UserErrorController($this->lang['advanced-config.unlock-administration'], $this->lang['advanced-config.code_sent_fail'], 4);
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}
	}
	
	private function init()
	{
		$this->load_lang();
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}
	
	private function send_mail($unlock_admin_clean)
	{        
		$subject = $this->lang['advanced-config.unlock-code.title'] . ' - ' . GeneralConfig::load()->get_site_name();
		$content = StringVars::replace_vars($this->lang['advanced-config.unlock-code.content'], 
			array('unlock_code' => $unlock_admin_clean, 'host_dir' => (HOST . DIR), 'signature' => MailServiceConfig::load()->get_mail_signature())
		);
		
		$mail = new Mail();
        $admin_mails = MailServiceConfig::load()->get_administrators_mails();
        foreach ($admin_mails as $mail_address)
        {
        	$mail->add_recipient($mail_address);
        }
		
        $mail->set_sender(MailServiceConfig::load()->get_default_mail_sender());
        $mail->set_subject($subject);
        $mail->set_content($content);
        $mail->set_is_html(true);
        return AppContext::get_mail_service()->try_to_send($mail);
	}
	
	private function save_unlock_code($unlock_admin_clean) 
	{
        $general_config = GeneralConfig::load();
        $general_config->set_admin_unlocking_key($unlock_admin_clean);
        GeneralConfig::save();
	}
}
?>