<?php
/*##################################################
 *                           content.php
 *                          -------------------
 *   begin                : February 17, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$id = retrieve(REQUEST, 'id', 0);
$id_post = retrieve(POST, 'id', 0);

$action = retrieve(REQUEST, 'action', '');
$action_post = retrieve(POST, 'action', '');

if ($action_post == 'save')
{
	// Save a Menu (New / Edit)
	$menu = null;

	$menu_name = retrieve(POST, 'name', '', TSTRING_UNCHANGE);
	$menu_url = retrieve(POST, 'feed_url', '', TSTRING_UNCHANGE);
	$matches = array();
	preg_match('`/rss/(.+)/([0-9]+)/(.+)/`', $menu_url, $matches);

	if (!empty($id_post))
	{   // Edit the Menu
		$menu = MenuService::load($id_post);
		$menu->set_title($menu_name);
		$menu->set_module_id($matches[1]);
		$menu->set_cat($matches[2]);
		$menu->set_name($matches[3]);
	}
	else
	{   // Add the new Menu
		$menu = new FeedMenu($menu_name, $matches[1], $matches[2], $matches[3]);
	}

	if (!($menu instanceof FeedMenu))
	AppContext::get_response()->redirect('menus.php');

	$menu->enabled(retrieve(POST, 'activ', Menu::MENU_NOT_ENABLED));
	if ($menu->is_enabled())
	{
		$menu->set_block(retrieve(POST, 'location', Menu::BLOCK_POSITION__NOT_ENABLED));
	}
	$menu->set_auth(Authorizations::build_auth_array_from_form(Menu::MENU_AUTH_BIT));
	
    //Filters
    MenuAdminService::set_retrieved_filters($menu);
	
	MenuService::save($menu);
	MenuService::generate_cache();

	AppContext::get_response()->redirect('menus.php#m' . $id_post);
}

// Display the Menu administration
$edit = !empty($id);

include('lateral_menu.php');
lateral_menu();

$tpl = new FileTemplate('admin/menus/feed.tpl');

$tpl->put_all(array(
	'JL_REQUIRE_TITLE' => TextHelper::to_js_string($LANG['require_title']),
	'JL_REQUIRE_FEED' => TextHelper::to_js_string($LANG['choose_feed_in_list']),
	'L_FEED' => $LANG['feed'],
	'L_AVAILABLES_FEEDS' => $LANG['availables_feeds'],
	'L_NAME' => $LANG['name'],
	'L_STATUS' => $LANG['status'],
	'L_AUTHS' => $LANG['auths'],
	'L_ENABLED' => $LANG['enabled'],
	'L_DISABLED' => $LANG['disabled'],
	'L_ACTIVATION' => $LANG['activation'],
	'L_GUEST' => $LANG['guest'],
	'L_USER' => $LANG['member'],
	'L_MODO' => $LANG['modo'],
	'L_ADMIN' => $LANG['admin'],
	'L_LOCATION' => $LANG['location'],
	'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['add'],
	'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
	'L_RESET' => $LANG['reset'],
    'ACTION' => 'save',
));

//Localisation possibles.
$block = retrieve(GET, 's', Menu::BLOCK_POSITION__HEADER, TINTEGER);
$array_location = array(
	Menu::BLOCK_POSITION__HEADER => $LANG['menu_header'],
	Menu::BLOCK_POSITION__SUB_HEADER => $LANG['menu_subheader'],
	Menu::BLOCK_POSITION__LEFT => $LANG['menu_left'],
	Menu::BLOCK_POSITION__TOP_CENTRAL => $LANG['menu_top_central'],
	Menu::BLOCK_POSITION__BOTTOM_CENTRAL => $LANG['menu_bottom_central'],
	Menu::BLOCK_POSITION__RIGHT => $LANG['menu_right'],
	Menu::BLOCK_POSITION__TOP_FOOTER => $LANG['menu_top_footer'],
	Menu::BLOCK_POSITION__FOOTER => $LANG['menu_footer']
);

$feed_url = '';
if ($edit)
{
	$menu = MenuService::load($id);

	if (!($menu instanceof FeedMenu))
	AppContext::get_response()->redirect('menus.php');

	$block = $menu->get_block();
	$feed_url = $menu->get_url(true);

	$tpl->put_all(array(
		'IDMENU' => $id,
		'NAME' => $menu->get_title(),
		'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $menu->get_auth()),
        'C_ENABLED' => $menu->is_enabled(),
   	    'C_EDIT' => true,
	));
}
else
{
	$tpl->put_all(array(
   	    'C_NEW' => true,
        'C_ENABLED' => true,
        'AUTH_MENUS' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, array(), array(-1 => true, 0 => true, 1 => true, 2 => true))
	));
	
   // Create a new generic menu
    $menu = new FeedMenu('', '', '');
}

function get_feeds($feed_cat, $module_id, $feed_type, $feed_url_edit = '', $level = 0)
{
	return get_feeds_children($feed_cat->get_children(), $module_id, $feed_type, $feed_url_edit, $level +1);
}

function get_feeds_children(Array $children, $module_id, $feed_type, $feed_url_edit = '', $level)
{
	if (!empty($children))
	{
		foreach ($children as $id => $feed_cat)
		{
			$url = $feed_cat->get_url($feed_type);
			
			$urls[] = array(
				'name' => $feed_cat->get_category_name(),
				'url' => $url,
				'level' => $level,
				'feed_name' => $feed_type,
				'selected' => $feed_url_edit == $url
			);
		}
		
		return array_merge($urls, get_feeds_children($feed_cat->get_children(), $module_id, $feed_type, $feed_url_edit, $level +1));
	}
	return array();
}

$feeds_modules = AppContext::get_extension_provider_service()->get_providers(FeedProvider::EXTENSION_POINT);
ksort($feeds_modules);

foreach ($feeds_modules as $module)
{
	$list = $module->get_extension_point(FeedProvider::EXTENSION_POINT);
	$list = $list->get_feeds_list();
	
	foreach ($list->get_feeds_list() as $feed_type => $object)
	{
		$urls = get_feeds($object, $module->get_id(), $feed_type, $feed_url);
		
		$root[0] = array(
			'name' => $object->get_category_name(),
			'url' => $object->get_url($feed_type),
			'level' => 0,
			'feed_name' => null,
			'selected' => $feed_url == $object->get_url($feed_type)
		);
	}
	
	$urls = array_merge($root, $urls);
	$tpl->assign_block_vars('modules', array('NAME' => ucfirst($module->get_id())));
	
	foreach ($urls as $url)
	{
		$tpl->assign_block_vars('modules.feeds_urls', array(
			'URL' => $url['url'],
			'NAME' => $url['name'],
			'SPACE' => str_repeat('--', $url['level']),
			'FEED_NAME' => $url['feed_name'] != 'master' ? $url['feed_name'] : null,
			'SELECTED' => $url['selected'] ? ' selected="selected"' : ''
		));
	}

}

$locations = '';
foreach ($array_location as $key => $name)
{
	$locations .= '<option value="' . $key . '" ' . (($block == $key) ? 'selected="selected"' : '') . '>' . $name . '</option>';
}

//Filtres
MenuAdminService::add_filter_fieldset($menu, $tpl);    

$tpl->put_all(array('LOCATIONS' => $locations));
$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>