<?php
/*##################################################
 *                               AbstractShare.class.php
 *                            -------------------
 *   begin                : July 06, 2011
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
abstract class AbstractShare implements ShareInterface
{
	protected $template = null;
	
	protected function set_template(View $template)
	{
		$this->template = $template;
	}
	
	protected function get_template()
	{
		return $this->template;
	}
	
	protected function assign_vars(){}
	
	public function display()
	{
		if ($this->template !== null)
		{
			$this->assign_vars();
			return $this->get_template()->display();
		}
	}
	
	public function render()
	{
		if ($this->template !== null)
		{
			$this->assign_vars();
			return $this->get_template()->render();
		}
	}
}
?>