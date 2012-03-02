<?php
/*##################################################
 *		             OnlineUser.class.php
 *                            -------------------
 *   begin                : February 01, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class OnlineUser extends User
{
	protected $location_script;
	protected $location_title;
	protected $last_update;
	
	public function set_location_script($location_script)
	{
		$this->location_script = $location_script;
	}
	
	public function get_location_script()
	{
		return $this->location_script;
	}
	
	public function set_location_title($location_title)
	{
		$this->location_title = $location_title;
	}
	
	public function get_location_title()
	{
		return $this->location_title;
	}
	
	public function set_last_update($last_update)
	{
		$this->last_update = $last_update;
	}
	
	public function get_last_update()
	{
		return $this->last_update;
	}
}
?>