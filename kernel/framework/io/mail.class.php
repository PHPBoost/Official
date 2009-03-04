<?php
/*##################################################
 *                              mail.class.php
 *                            -------------------
 *   begin                : March 11, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to send mails without having to deal with the mail headers and parameters.
 */

class Mail
{
    /**
     * @desc Builds a Mail object.
     */
    function Mail()
    {
    }

    /**
     * @desc Sends the mail.
     * @param string $mail_to The mail recipients' address.
     * @param string $mail_objet The mail object.
     * @param string $mail_content content of the mail
     * @param string $mail_from The mail sender's address.
     * @param string $mail_header The header you want to specify (it you don't specify it, it will be generated automatically).
     * @param string $mail_sender The mail sender's name. If you don't use this parameter, the name of the site administrator will be taken.
     * @return bool True if the mail could be sent, false otherwise.
     */
    function send_from_properties($mail_to, $mail_objet, $mail_content, $mail_from, $mail_header = '', $mail_sender = 'admin')
    {
        //Initialization of the mail properties
        if (!$this->set_recipients($mail_to) || !$this->set_sender($mail_from, $mail_sender))
        {
            return false;
        }
         
        $this->set_object($mail_objet);
        $this->set_content($mail_content);

        $this->set_headers($mail_header);

        //Let's send the mail
        return $this->send();
    }

    function send()
    {
        if (empty($this->headers))
        {
            $this->_generate_headers();
        }
         
        $recipients = trim(implode('; ', $this->recipients), '; ');
         
        return @mail($this->recipients, $this->objet, $this->content, $this->headers);
    }

    /**
     * @static
     * @desc Checks that an email address has a correct form.
     * @return bool True if it's valid, false otherwise.
     */
    function check_validity($mail_address)
    {
        if (!preg_match('`^[a-z0-9._-]+@([a-z0-9._-]{2,}\.)+[a-z]{2,4}$`i', $mail_address))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * @desc Sets the mail sender.
     * @param string $sender The mail sender address.
     * @param string $sender_name 'admin' if the mail is sent by the administrator, 'user' otherwise.
     * @return bool True, if the mail sender address is correct, false otherwise.
     */
    function set_sender($sender, $sender_name = 'admin')
    {
        global $LANG;

        $this->sender_name = ($sender_name == 'admin') ? $LANG['admin'] : $LANG['user'];

        if (Mail::check_validity($sender))
        {
            $this->sender_mail = $sender;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Sets the recipient(s) of the mail.
     * @param string $recipients Recipients of the mail. It they are more than one, use the comma to separate their addresses.
     */
    function set_recipients($recipients)
    {
        $this->recipients = '';

        $recipients_list = explode(';', $recipients);
        $recipients_list = array_map('trim', $recipients_list);

        //We check that each recipient address is correct
        foreach ($recipients_list as $recipient)
        {
            if (Mail::check_validity($recipient))
            {
                $this->recipients[] = $recipient;
            }
        }
         
        //We return the setting status.
        if (!empty($this->recipients))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Sets the mail object
     * @param string $object Mail object
     */
    function set_object($object)
    {
        $this->objet = $object;
    }

    /**
     * @desc The mail content.
     * @param string $content The mail content
     */
    function set_content($content)
    {
        $this->content = $content;
    }

    /**
     * @desc Sets the headers. Forces them, they won't be generated automatically.
     * @param string $headers The mail headers.
     */
    function set_headers($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @desc Returns the mail address of the sender.
     * @return string the sender's mail address
     */
    function get_sender_mail()
    {
        return $this->sender_mail;
    }

    /**
     * @desc Returns the mail sender's name.
     * @return string The mail sender's name.
     */
    function get_sender_name()
    {
        return $this->sender_name;
    }

    /**
     * @desc Returns the mail recipients' addresses. They are separated by a comma.
     * @return string The mail recipients.
     */
    function get_recipients()
    {
        return $this->recipients;
    }

    /**
     * @desc Returns the mail object.
     * @return string The mail object.
     */
    function get_object()
    {
        return $this->objet;
    }

    /**
     * @desc Returns the mail content.
     * @return string The mail content.
     */
    function get_content()
    {
        return $this->content;
    }

    /**
     * @desc Returns the mail headers.
     * @return string The mail headers.
     */
    function get_headers()
    {
        return $this->headers;
    }

    ## Protected Methods ##
    /**
    * @access protected
    * @desc Generates the mail headers.
    */
    function _generate_headers()
    {
        global $LANG;

        //Sender
        $this->headers .= 'From: "' . $this->sender_name . ' ' . HOST . '" <' . $this->sender_mail . ">\n";

        //Recipients
        foreach ($this->recipients as $recipient)
        {
            $this->headers .= 'cc: ' . $recipient . "\n";
        }
    }

    ## Private Attributes ##
    /**
    * @var sting object of the mail
    */
    var $objet;

    /**
     * @var string content of the mail
     */
    var $content;

    /**
     * @var string Address of the mail sender.
     */
    var $sender_mail;

    /**
     * @var string The mail sender name.
     */
    var $sender_name;

    /**
     * @var The mail headers.
     */
    var $headers;

    /**
     * @var string[] Recipients of the mail. If they are more than one, a comma separates their addresses.
     */
    var $recipients = array();
}

?>