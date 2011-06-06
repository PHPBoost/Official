<?php
/*##################################################
 *                           syndication.php
 *                         -------------------
 *   begin                : January 19, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

define('PATH_TO_ROOT', '.');
header("Content-Type: application/xml; charset=iso-8859-1");

define('NO_SESSION_LOCATION', true);
require_once PATH_TO_ROOT . '/kernel/begin.php';
require_once PATH_TO_ROOT . '/kernel/header_no_display.php';

$module_id = retrieve(GET, 'm', '');
if (!empty($module_id))
{
	$feed_name = retrieve(GET, 'name', Feed::DEFAULT_FEED_NAME);
	$category_id = retrieve(GET, 'cat', 0);

	$feed = null;

	switch (retrieve(GET, 'feed', 'rss'))
	{
		case 'atom':    // ATOM
			$feed= new ATOM($module_id, $feed_name, $category_id);
			break;
		default:        // RSS
			$feed= new RSS($module_id, $feed_name, $category_id);
			break;
	}

	if ($feed != null && $feed->is_in_cache())
	{   // If the file exist, we print it
		echo $feed->read();
	}
	else
	{
		$eps = AppContext::get_extension_provider_service();
		if ($eps->provider_exists($module_id, FeedProvider::EXTENSION_POINT))
		{
			$provider = $eps->get_provider($module_id);
			$feeds = $provider->feeds();
			$feed->load_data($feeds->get_feed_data_struct($category_id, $feed_name));
			$feed->cache();
			echo $feed->export();
		}
		else
		{
			AppContext::get_response()->redirect('member/error.php?e=e_uninstalled_module');
		}
	}
}

require_once PATH_TO_ROOT . '/kernel/footer_no_display.php';

?>