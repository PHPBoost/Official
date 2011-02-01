<?php
/*##################################################
 *                        NewsletterMailType.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 K�vin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author K�vin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
interface NewsletterMailType
{
	/**
	 * @desc This function send mail
	 * @param instance of NewsletterMailService $newsletter_mail_config.
	 */
	public function send_mail(NewsletterMailService $newsletter_mail_config);
	
	/**
	 * @desc This function displayed mail
	 * @param instance of NewsletterMailService $newsletter_mail_config.
	 */
	public function display_mail(NewsletterMailService $newsletter_mail_config);
}

?>