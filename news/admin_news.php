<?php
/*##################################################
 *                               admin_news.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre R�gis, Roguelon Geoffrey
 *   email                : crowkait@phpboost.com, liaght@gmail.com
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

require_once('../admin/admin_begin.php');
require_once('news_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$tpl = new FileTemplate('news/admin_news.tpl');


$now = new Date(DATE_NOW, TIMEZONE_AUTO);
//On cr�e une pagination si le nombre de news est trop important.

$Pagination = new DeprecatedPagination();

$result = $Sql->query_while("SELECT n.id, n.idcat, n.title, n.user_id, n.timestamp, n.start, n.end, n.visible, m.login, m.level
	FROM " . DB_TABLE_NEWS . " n
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	ORDER BY n.timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);

$level = array(0 => '', 1 => ' modo', 2 => ' admin');

while ($row = $Sql->fetch_assoc($result))
{
	if ($row['visible'] && $row['start'] > $now->get_timestamp())
	{
		$aprob = $LANG['waiting'];
	}
	elseif ($row['visible'] && $row['start'] < $now->get_timestamp() && ($row['end'] > $now->get_timestamp() || empty($row['end'])))
	{
		$aprob = $LANG['yes'];
	}
	else
	{
		$aprob = $LANG['no'];
	}

	$visible = '';
	$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
	// On reccourci le lien si il est trop long pour �viter de d�former l'administration.
	$title = TextHelper::html_entity_decode($row['title']);
	$title = strlen($title) > 45 ? substr($title, 0, 45) . '...' : $title;

	if ($row['end'] > 0 && $row['start'] > 0)
	{
		$start = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['start']);
		$end = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['end']);
		$visible .= sprintf($NEWS_LANG['until_2'], $start->format(DATE_FORMAT, TIMEZONE_AUTO), $end->format(DATE_FORMAT, TIMEZONE_AUTO));
	}
	elseif ($row['end'] > 0)
	{
		$end = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['end']);
		$visible .= sprintf($NEWS_LANG['until_1'], $end->format(DATE_FORMAT, TIMEZONE_AUTO));
	}
	elseif ($row['start'] > 0)
	{
		$start = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['start']);
		$visible .= $start->format(DATE_FORMAT, TIMEZONE_AUTO);
	}

	$tpl->assign_block_vars('news', array(
		'IDNEWS' => $row['id'],
		'TITLE' => $title,
		'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
		'LOGIN' => !empty($row['login']) ? $row['login'] : $LANG['guest'],
		'U_USER' => !empty($row['login']) ? UserUrlBuilder::profile($row['user_id'])->absolute() : 0,
		'LEVEL' => !empty($row['login']) ? $level[$row['level']] : 0,
		'CATEGORY' => !empty($NEWS_CAT[$row['idcat']]['name']) ? $NEWS_CAT[$row['idcat']]['name'] : '',
		'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
		'DATE' => $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
		'APROBATION' => $aprob,
		'VISIBLE' => !empty($visible) ? $visible : ''
	));
}
$Sql->query_close($result);

// Chargement du menu de l'administration.
require_once('admin_news_menu.php');

$nbr_news = $Sql->count_table(PREFIX . 'news', __LINE__, __FILE__);

$tpl->put_all(array(
	'ADMIN_MENU' => $admin_menu,
	'NO_NEWS' => $nbr_news == 0 ? $NEWS_LANG['no_news_available'] : 0,
	'PAGINATION' => $Pagination->display('admin_news.php?p=%d', $nbr_news, 'p', 25, 3),
	'L_CONFIRM_DEL_NEWS' => $NEWS_LANG['confirm_del_news'],
	'L_TITLE' => $LANG['title'],
	'L_CATEGORY' => $LANG['category'],
	'L_PSEUDO' => $LANG['member'],
	'L_DATE' => $LANG['date'],
	'L_APROB' => $LANG['aprob'],
	'L_UPDATE' => $LANG['update'],
	'L_DELETE' => $LANG['delete']
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>