<?php
/*##################################################
 *                        TextNewsletterMail.class.php
 *                            -------------------
 *   begin                : February 1, 2011
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class TextNewsletterMail extends AbstractNewsletterMail
{
	public function send_mail($subscribers, $sender, $subject, $contents)
	{
		$mail = new Mail();
		$mail->set_sender($sender);
		$mail->set_is_html(true);
		$mail->set_subject($subject);
		$contents = $this->parse_contents($contents) . $this->add_unsubscribe_link();
		
		foreach ($subscribers as $values)
		{
			$mail_subscriber = !empty($values['mail']) ? $values['mail'] : NewsletterDAO::get_mail_for_member($values['user_id']);
			$mail->clear_recipients();
			$mail->add_recipient($mail_subscriber);
			$mail->set_content($contents);
			
			//TODO gestion des erreurs
			AppContext::get_mail_service()->try_to_send($mail);
		}
	}
	
	public function parse_contents($contents)
	{
		return stripslashes(FormatingHelper::strparse($contents));
	}
}
?>