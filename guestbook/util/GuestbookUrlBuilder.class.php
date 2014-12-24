<?php
/*##################################################
 *                          GuestbookUrlBuilder.class.php
 *                            -------------------
 *   begin                : November 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class GuestbookUrlBuilder
{
	private static $dispatcher = '/guestbook';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}
	
	/**
	 * @return Url
	 */
	public static function home($page = 1, $id = null)
	{
		$page = $page !== 1 ? $page : '';
		$id = $id !== null ? '#m' . $id : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $page . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit($id, $redirect = null)
	{
		$redirect = $redirect !== null ? '?redirect=' . $redirect : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $redirect);
	}
	
	/**
	 * @return Url
	 */
	public static function delete($id, $redirect = null)
	{
		$redirect = $redirect !== null ? 'redirect=' . $redirect . '&' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . $redirect . 'token=' . AppContext::get_session()->get_token());
	}
}
?>