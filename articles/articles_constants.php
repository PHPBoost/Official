<?php
/*##################################################
 *                              articles_begin.php
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

if (defined('PHPBOOST') !== true)
    exit;

define('AUTH_ARTICLES_READ', 1);
define('AUTH_ARTICLES_CONTRIBUTE', 2);
define('AUTH_ARTICLES_WRITE', 4);
define('AUTH_ARTICLES_MODERATE', 8);

define('DB_TABLE_ARTICLES', PREFIX . 'articles');
define('DB_TABLE_ARTICLES_CAT', PREFIX . 'articles_cats');

$Cache->load('articles');
//load_module_lang('articles'); //Chargement de la langue du module.

$idartcat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);

if (empty($idartcat))//Racine.
{
    $CAT_ARTICLES[0]['auth'] = $CONFIG_ARTICLES['global_auth'];
    $CAT_ARTICLES[0]['visible'] = 1;
    $CAT_ARTICLES[0]['name'] = $LANG['root'];
    $CAT_ARTICLES[0]['c_order'] = -1;
    $CAT_ARTICLES[0]['id_parent'] = 0;
}

?>