<?php
/*##################################################
 *                              galleryExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true) exit;

class GalleryExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('gallery');
    }

	//R�cup�ration du cache.
	function get_cache()
	{
		global $CONFIG_GALLERY, $LANG;

		$gallery_config = 'global $CONFIG_GALLERY;' . "\n";

		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_GALLERY = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'gallery'", __LINE__, __FILE__));
		$CONFIG_GALLERY = is_array($CONFIG_GALLERY) ? $CONFIG_GALLERY : array();
		if (isset($CONFIG_GALLERY['auth_root']))
			$CONFIG_GALLERY['auth_root'] = unserialize($CONFIG_GALLERY['auth_root']);

		$gallery_config .= '$CONFIG_GALLERY = ' . var_export($CONFIG_GALLERY, true) . ';' . "\n";

		$cat_gallery = 'global $CAT_GALLERY;' . "\n";
		
		//Racine
		$cat_gallery .= '$CAT_GALLERY[0] = ' . var_export(array('name' => $LANG['root'], 'id_left' => 0, 'id_right' => 0, 'level' => -1, 'auth' => $CONFIG_GALLERY['auth_root']), true) . ';' . "\n\n";
		
		$result = $this->sql_querier->query_while("SELECT id, id_left, id_right, level, name, aprob, auth
		FROM " . PREFIX . "gallery_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			if (empty($row['auth']))
				$row['auth'] = serialize($CONFIG_GALLERY['auth_root']);

			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$cat_gallery .= '$CAT_GALLERY[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$this->sql_querier->query_close($result);

		$Gallery = new Gallery;

		$_array_random_pics = 'global $_array_random_pics;' . "\n" . '$_array_random_pics = array(';
		$result = $this->sql_querier->query_while("SELECT g.id, g.name, g.path, g.width, g.height, g.idcat, gc.auth
		FROM " . PREFIX . "gallery g
		LEFT JOIN " . PREFIX . "gallery_cats gc on gc.id = g.idcat
		WHERE g.aprob = 1 AND (gc.aprob = 1 OR g.idcat = 0)
		ORDER BY RAND()
		" . $this->sql_querier->limit(0, 30), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			if ($row['idcat'] == 0)
				$row['auth'] = serialize($CONFIG_GALLERY['auth_root']);

			//Calcul des dimensions avec respect des proportions.
			list($width, $height) = $Gallery->get_resize_properties($row['width'], $row['height']);

			$_array_random_pics .= 'array(' . "\n" .
			'\'id\' => ' . var_export($row['id'], true) . ',' . "\n" .
			'\'name\' => ' . var_export($row['name'], true) . ',' . "\n" .
			'\'path\' => ' . var_export($row['path'], true) . ',' . "\n" .
			'\'width\' => ' . var_export($width, true) . ',' . "\n" .
			'\'height\' => ' . var_export($height, true) . ',' . "\n" .
			'\'idcat\' => ' . var_export($row['idcat'], true) . ',' . "\n" .
			'\'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}
		$this->sql_querier->query_close($result);
		$_array_random_pics .= ');';

		return $gallery_config . "\n" . $cat_gallery . "\n" . $_array_random_pics;
	}
	
	function feeds()
	{
		return new GalleryFeedProvider();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('gallery.css');
		return $module_css_files;
	}
	
	public function menus()
	{
		return new ModuleMenus(array(
			new GalleryModuleMiniMenu()
		));
	}
	
	public function home_page()
	{
		return new GalleryHomePageExtensionPoint();
	}
	
	public function comments()
	{
		return new CommentsTopics(array(
			new GalleryCommentsTopic()
		));
	}
}
?>