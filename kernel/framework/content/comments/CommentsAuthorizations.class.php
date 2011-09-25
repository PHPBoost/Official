<?php
/*##################################################
 *                              CommentsAuthorizations.class.php
 *                            -------------------
 *   begin                : April 1, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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
class CommentsAuthorizations
{
	private $array_authorization = array();
	private $read_bit = 0;
	private $post_bit = 0;
	private $moderation_bit = 0;
	private $note_bit = 0;
	
	private $authorized_access_module = true;
	
	private $manual_authorized_read = null;
	private $manual_authorized_post = null;
	private $manual_authorized_moderation = null;
	private $manual_authorized_note = null;
	
	const READ_AUTHORIZATIONS = 1;
	const POST_AUTHORIZATIONS = 2;
	const MODERATION_AUTHORIZATIONS = 4;
	const NOTE_AUTHORIZATIONS = 8;
	
	/*
	 * Setters
	*/
	public function set_array_authorization(Array $array_authorization)
	{
		$this->array_authorization = $array_authorization;
	}
	
	public function set_read_bit($read_bit)
	{
		$this->read_bit = $read_bit;
	}
	
	public function set_post_bit($post_bit)
	{
		$this->post_bit = $post_bit;
	}
	
	public function set_moderation_bit($moderation_bit)
	{
		$this->moderation_bit = $moderation_bit;
	}
	
	public function set_note_bit($note_bit)
	{
		$this->note_bit = $note_bit;
	}
	
	public function is_authorized_access_module()
	{
		return $this->authorized_access_module;
	}
	
	public function is_authorized_read()
	{
		return $this->check_authorizations($this->read_bit, self::READ_AUTHORIZATIONS);
	}
	
	public function is_authorized_post()
	{
		return $this->check_authorizations($this->post_bit, self::POST_AUTHORIZATIONS);
	}
	
	public function is_authorized_moderation()
	{
		return $this->check_authorizations($this->moderation_bit, self::MODERATION_AUTHORIZATIONS);
	}
	
	public function is_authorized_note()
	{
		return $this->check_authorizations($this->note_bit, self::NOTE_AUTHORIZATIONS);
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_access_module($authorized)
	{
		$this->authorized_access_module = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_manual_authorized_read($authorized)
	{
		$this->manual_authorized_read = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_manual_authorized_post($authorized)
	{
		$this->manual_authorized_post = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_manual_authorized_moderation($authorized)
	{
		$this->manual_authorized_moderation = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_manual_authorized_note($authorized)
	{
		$this->manual_authorized_note = $authorized;
	}
	
	private function check_authorizations($bit, $global_bit)
	{
		$manual_authorizations = $this->manual_authorizations($global_bit);
		if ($manual_authorizations !== null)
		{
			return $manual_authorizations;
		}
		else if (!empty($this->array_authorization) && $bit !== 0)
		{
			return AppContext::get_user()->check_auth($this->array_authorization, $bit);
		}
		else
		{
			return AppContext::get_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
		}
	}
	
	private function manual_authorizations($type)
	{
		switch ($type) {
			case self::READ_AUTHORIZATIONS:
				return ($this->manual_authorized_read !== null ? $this->manual_authorized_read : null);
			break;
			case self::POST_AUTHORIZATIONS:
				return ($this->manual_authorized_post !== null ? $this->manual_authorized_post : null);
			break;
			case self::MODERATION_AUTHORIZATIONS:
				return ($this->manual_authorized_post !== null ? $this->manual_authorized_post : null);
			break;
			case self::NOTE_AUTHORIZATIONS:
				return ($this->manual_authorized_note !== null ? $this->manual_authorized_note : null);
			break;
			default:
			break;
		}
	}
}
?>