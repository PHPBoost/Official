<?php
/*##################################################
 *                     MediaSitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : May 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class MediaSitemapExtensionPoint implements SitemapExtensionPoint
{
	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	private function get_module_map($auth_mode)
	{
		global $MEDIA_CATS, $MEDIA_LANG, $LANG, $User, $MEDIA_CONFIG, $Cache, $Bread_crumb;

		require_once PATH_TO_ROOT . '/media/media_begin.php';

		$media_link = new SitemapLink($MEDIA_LANG['media'], new Url('/media/media.php'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);

		$module_map = new ModuleMap($media_link, 'media');

		$id_cat = 0;
		$keys = array_keys($MEDIA_CATS);
		$num_cats = count($MEDIA_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $MEDIA_CATS[$id];

			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $properties['auth'], MEDIA_AUTH_READ) : Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $MEDIA_CONFIG['auth'], MEDIA_AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], MEDIA_AUTH_READ) : $User->check_auth($MEDIA_CONFIG['auth'], MEDIA_AUTH_READ);
			}

			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}
	
	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $MEDIA_CATS, $LANG, $User, $MEDIA_CONFIG;

		$this_category = new SitemapLink($MEDIA_CATS[$id_cat]['name'], new Url('/media/media' . url('.php?cat='.$id_cat, '-0-' . $id_cat . '+' . Url::encode_rewrite($MEDIA_CATS[$id_cat]['name']) . '.php')));

		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($MEDIA_CATS);
		$num_cats = count($MEDIA_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $MEDIA_CATS[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0	)
			$category = $this_category;
		
		return $category;
	}
}
?>