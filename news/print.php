<?php
/*##################################################
 *                               print.php
 *                            -------------------
 *   begin                : January 24, 2007
 *   copyright            : (C) 2009 Roguelon Geoffrey
 *   email                : liaght@gmail.com
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

require_once('../kernel/begin.php'); 

require_once('news_begin.php');

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

//Id de la news � afficher en version imprimable
$id = retrieve(GET, 'id', 0, TINTEGER);

if ($id > 0) //Si on connait son titre
{
	// R�cup�ration de la news
	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.visible, n.user_id, n.img, n.alt, m.login, m.level
	FROM " . DB_TABLE_NEWS . " n LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	WHERE n.id = '" . $id . "'", __LINE__, __FILE__);
	$news = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	
	$template = new FileTemplate('framework/content/print.tpl');

	$template->put_all(array(
		'PAGE_TITLE' => $news['title'] . ' - ' . GeneralConfig::load()->get_site_name(),
		'TITLE' => $news['title'],
		'L_XML_LANGUAGE' => $LANG['xml_lang'],
		'CONTENT' => FormatingHelper::second_parse($news['contents']) . '<br />' . FormatingHelper::second_parse($news['extend_contents'])
	));
	
	$template->display();
}

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>