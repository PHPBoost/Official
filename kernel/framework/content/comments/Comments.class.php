<?php
/*##################################################
 *                              Comments.class.php
 *                            -------------------
 *   begin                : March 31, 2010
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
class Comments
{
	private $module_name;
	private $module_id;
	private $is_locked = false;
	private $authorizations;

	public function set_module_name($module)
	{
		$this->module_name = $module;
	}
	
	public function get_module_name()
	{
		return $this->module_name;
	}
	
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function set_is_locked($is_locked)
	{
		$this->is_locked = $is_locked;
	}
	
	public function get_is_locked()
	{
		return $this->is_locked;
	}
	
	public function set_authorizations(CommentsAuthorizations $authorizations)
	{
		$this->authorizations = $authorizations;
	}
	
	public function get_authorizations()
	{
		return !empty($this->authorizations) ? $this->authorizations : $this->default_authorizations;
	}
}
?>