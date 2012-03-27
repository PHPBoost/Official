<?php
/*##################################################
 *                     ModuleCssFiles.class.php
 *                            -------------------
 *   begin                : March 27, 2012
 *   copyright            : (C) 2012 K�vin MASSY
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

class ModuleCssFiles implements CssFilesExtensionPoint
{
	private $css_files_always_displayed = array();
	private $css_files_running_module_displayed = array();
	
	public function __construct($css_files_running_module_displayed = array(), $css_files_always_displayed = array())
	{
		$this->css_files_running_module_displayed = $css_files_running_module_displayed;
		$this->css_files_always_displayed = $css_files_always_displayed;
	}
	
	public function get_css_files_always_displayed()
	{
		return $this->css_files_always_displayed;
	}

	public function get_css_files_running_module_displayed()
	{
		return $this->css_files_running_module_displayed;
	}
}
?>