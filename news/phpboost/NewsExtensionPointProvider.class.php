<?php
/*##################################################
 *                    NewsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : February 13, 2012
 *   copyright            : (C) 2013 K�vin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('news');
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(
			new DispatcherUrlMapping('/news/index.php')
		));
	}
	
	public function search()
	{
		return new NewsSearchable();
	}
	
	public function feeds()
	{
		return new NewsFeedProvider();
	}
	
	public function sitemap()
	{
		return new NewsSitemapExtensionPoint();
	}
}
?>