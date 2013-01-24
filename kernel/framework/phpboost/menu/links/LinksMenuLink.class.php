<?php
/*##################################################
 *                           LinksMenuLink.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 R�gis Viarre; Loic Rouchon
 *   email                : crowkait@phpboost.com; loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc A Simple menu link
 * @package {@package}
 */
class LinksMenuLink extends LinksMenuElement
{
	const LINKS_MENU_LINK__CLASS = 'LinksMenuLink';

	/**
	* @desc Constructor
	* @param string $title Menu title
	* @param string $url Destination url
	* @param string $image Menu's image url relative to the website root or absolute
	* @param int $id The Menu's id in the database
	*/
	public function __construct($title, $url, $image = '')
	{
		parent::__construct($title, $url, $image);
	}

	/**
	 * @desc Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	public function display($template = false, $mode = LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
		// Stop if the user isn't authorised
		if (!$this->_check_auth())
		{
			return '';
		}

		parent::_assign($template, $mode);
		$template->put_all(array(
  			'C_LINK' => true
		));

		return $template->render();
	}

	/**
	 * @param Template $template the template to use to display the link
	 * @return string the string to write in the cache file
	 */
	public function cache_export($template = false)
	{
		parent::_assign($template);
		$template->put_all(array(
            'C_LINK' => true
		));
		return parent::cache_export_begin() . $template->render() . parent::cache_export_end();
	}
}
?>