<?php
/*##################################################
 *                              gallery_auth.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre r�gis
 *   email                : crowkait@phpboost.com
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

if( defined('PHP_BOOST') !== true)	
	exit;
	
if( !$groups->check_auth($SECURE_MODULE['gallery'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

load_module_lang('gallery', $CONFIG['lang']); //Chargement de la langue du module.
$cache->load_file('gallery');

define('READ_CAT_GALLERY', 0x01);
define('WRITE_CAT_GALLERY', 0x02);
define('EDIT_CAT_GALLERY', 0x04);

$g_idcat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;
if( !empty($g_idcat) )
{
	//Cr�ation de l'arborescence des cat�gories.
	speed_bar_generate($SPEED_BAR, $LANG['title_gallery'], transid('gallery.php'));
	foreach($CAT_GALLERY as $id => $array_info_cat)
	{
		if( $CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'] )
			speed_bar_generate($SPEED_BAR, $array_info_cat['name'], 'gallery' . transid('.php?cat=' . $id, '-' . $id . '.php'));
	}
}
else
	speed_bar_generate($SPEED_BAR, $LANG['title_gallery'], '');
	
$title_gallery = !empty($CAT_GALLERY[$g_idcat]['name']) ? addslashes($CAT_GALLERY[$g_idcat]['name']) : '';
define('TITLE', (!empty($title_gallery) ? $LANG['title_gallery'] . ' - ' . $title_gallery : $LANG['title_gallery']));
define('ALTERNATIVE_CSS', 'gallery'); //Css alternatif

?>