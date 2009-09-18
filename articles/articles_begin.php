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

require_once('articles_constants.php');
load_module_lang('articles');	

if (isset($ARTICLES_CAT[$idartcat]) && isset($_GET['cat']))
{ 
	//Cr�ation de l'arborescence des cat�gories.
	$Bread_crumb->add($LANG['title_articles'], url('articles.php'));
	foreach ($ARTICLES_CAT as $id => $array_info_cat)
	{
		if (!empty($idartcat) && $ARTICLES_CAT[$idartcat]['id_parent'] >= $array_info_cat['id_parent']  && $array_info_cat['c_order'] <= $ARTICLES_CAT[$idartcat]['c_order'])
			$Bread_crumb->add($array_info_cat['name'], 'articles' . url('.php?cat=' . $id, '-' . $id . '.php'));
	}
	if (!empty($idart))
	{
		$articles = $Sql->query_array(PREFIX . 'articles', '*', "WHERE visible = 1 AND id = '" . $idart . "' AND idcat = " . $idartcat, __LINE__, __FILE__);
		$idartcat = $articles['idcat'];
		
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($articles['title']));
		$Bread_crumb->add($articles['title'], 'articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart, '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php'));
		
		if (!empty($get_note))
			$Bread_crumb->add($LANG['note'], '');
		elseif (!empty($_GET['i']))
			$Bread_crumb->add($LANG['com'], '');
	}
	else
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($ARTICLES_CAT[$idartcat]['name']));
}
else
{
	$Bread_crumb->add($LANG['title_articles'], '');
	if (!defined('TITLE'))
		define('TITLE', $LANG['title_articles']);
}

?>