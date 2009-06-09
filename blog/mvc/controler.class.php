<?php
/*##################################################
 *                           controler.class.php
 *                            -------------------
 *   begin                : June 09 2009
 *   copyright            : (C) 2009 Lo�c Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

// TODO Move this file into the /kernel/framework/mvc

define('ICONTROLER__INTERFACE', 'IControler');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This interface declares the minimalist controler pattern
 * with no actions.
 *
 */
interface IControler
{
	/**
	 * @desc This method will always be called just before the controler action
	 */
	public function init();
	/**
	 * @desc This method will always be called just after the controler action
	 */
	public function destroy();
}

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This class defines the minimalist controler pattern
 * with no actions. This, in order to avoid to defines empty
 * init() and destroy() method for controlers that doesn't need
 * this functionality
 */
abstract class AbstractControler implements IControler
{
	public function init() {}
	public function destroy() {}
}
?>