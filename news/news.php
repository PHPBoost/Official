<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin              : June 20, 2005
 *   copyright            : (C) 2005 Viarre R�gis, Roguelon Geoffrey
 *   email              : crowkait@phpboost.com, liaght@gmail.com
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

require_once('../kernel/begin.php');
require_once('news_begin.php');

$news_cat = new NewsCats();

$idnews = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'cat', 0);
$arch = retrieve(GET, 'arch', false);
$user = retrieve(GET, 'user', false, TBOOL);
$now = new Date(DATE_NOW, TIMEZONE_AUTO);

if (!$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_READ))
{
    $error_controller = PHPBoostErrors::user_not_authorized();
    DispatchManager::redirect($error_controller);
}

if (!empty($idcat) && !$news_cat->check_category_exists($idcat))
{
    $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), $LANG['e_unexist_cat_news']);
    DispatchManager::redirect($controller);
}

if (!$User->check_auth($NEWS_CAT[$idcat]['auth'], AUTH_NEWS_READ))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
    DispatchManager::redirect($error_controller);
}

if (!empty($idnews)) // On affiche la news correspondant � l'id envoy�.
{
	// R�cup�ration de la news
	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.visible, n.user_id, n.img, n.alt, m.login, m.level, m.user_groups, n.sources
	FROM " . DB_TABLE_NEWS . " n LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	WHERE n.id = '" . $idnews . "'" . (!$User->check_auth($NEWS_CAT[$idcat]['auth'], AUTH_NEWS_MODERATE) ? " AND n.visible = 1 OR ('" . $now->get_timestamp() . "' >= n.start AND n.start > 0 AND ('" . $now->get_timestamp() . "' <= n.end OR n.end = 0))" : ""), __LINE__, __FILE__);
	$news = $Sql->fetch_assoc($result);
	$Sql->query_close($result);

	if (!empty($news['id']) && !empty($NEWS_CAT[$news['idcat']]) && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_READ)
		&& ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || ($news['visible'] && $news['start'] < $now->get_timestamp())
		|| $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id')))
	{	
		
		$Sql->query_inject("UPDATE " . DB_TABLE_NEWS . " SET compt = compt + 1 WHERE id = '" . $idnews . "'", __LINE__, __LINE__); //MAJ du compteur.

		// Bread crumb.
		$news_cat->bread_crumb($news['idcat']);
		$Bread_crumb->add($news['title'], 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . Url::encode_rewrite($news['title']) . '.php'));

		// Titre de la page.
		define('TITLE', $NEWS_LANG['news'] . ' - ' . $news['title']);
		require_once('../kernel/header.php');

		$tpl = new FileTemplate('news/news.tpl');
		
		// Gestion du timezone pour la date de la news.
		$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news['timestamp']);

		// Construction de l'arbre des cat�gories pour les news pr�c�dentes, suivantes et suggestion de news.
		$array_cat = array();
		$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);

		// News suivante.
		$next_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp > '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' ORDER BY timestamp ASC" . $Sql->limit(0, 1), __LINE__, __FILE__);

		// News pr�c�dente
		$previous_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp < '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' ORDER BY timestamp DESC" . $Sql->limit(0, 1), __LINE__, __FILE__);

		// Suggestion de news.
		$nbr_suggested = 0;
		$news_content = addslashes($news['title']) . ', ' . addslashes(strip_tags($news['contents'])) . ', ' . addslashes(strip_tags($news['extend_contents']));
		$result = $Sql->query_while("SELECT id, idcat, title, 
		(2 * FT_SEARCH_RELEVANCE(title, '" . $news_content . "') 
		+ FT_SEARCH_RELEVANCE(contents, '" . $news_content . "') 
		+ FT_SEARCH_RELEVANCE(extend_contents, '" . $news_content . "') / 4) AS relevance
		FROM " . DB_TABLE_NEWS . "
		WHERE (
		FT_SEARCH(title, '" . $news_content . "') OR
		FT_SEARCH(contents, '" . $news_content . "') OR
		FT_SEARCH(extend_contents, '" . $news_content . "') 
		) AND id <> '" . $news['id'] . "'
		ORDER BY relevance DESC LIMIT 0, 10", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$tpl->assign_block_vars('suggested', array(
				'TITLE' => $row['title'],
				'URL' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php')
			));
			$nbr_suggested++;
		}
		
		$array_sources = unserialize($news['sources']);
		$i = 0;
		foreach ($array_sources as $sources)
		{	
			$tpl->assign_block_vars('sources', array(
				'I' => $i,
				'NAME' => stripslashes($sources['name']),
				'COMMA' => $i > 0 ? ', ' : ' ',
				'URL' => stripslashes($sources['url']),
			));
			$i++;
		}	
		
		$auth_edit = $news['idcat'] > 0 ? $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id')
		: $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id');
		
		$auth_delete = $news['idcat'] > 0 ? $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE);
		
		$comments_topic = new NewsCommentsTopic();
		$comments_topic->set_id_in_module($idnews);
		$comments_topic->set_url(new Url('/news/news.php?cat='. $news['idcat'] .'&id=' . $news['id'] . '&com=0'));
		
		$group_color = User::get_group_color($news['user_groups'], $news['level']);
		
		$tpl->put_all(array(
			'C_NEXT_NEWS' => !empty($next_news['id']),
			'C_EDIT' => $auth_edit,
			'C_DELETE' => $auth_delete,
			'C_NEWS_NAVIGATION_LINKS' => !empty($previous_news['id']) || !empty($next_news['id']),
			'C_PREVIOUS_NEWS' => !empty($previous_news['id']),
			'C_NEWS_SUGGESTED' => $nbr_suggested > 0 ? 1 : 0,
			'C_IMG' => !empty($news['img']),
			'C_ICON' => $NEWS_CONFIG['activ_icon'] && !empty($row['idcat']),
			'C_SOURCES' => $i > 0 ? true : false,
			'C_GROUP_COLOR' => !empty($group_color),
			'ID' => $news['id'],
			'TITLE' => $news['title'],
			'CONTENTS' => FormatingHelper::second_parse($news['contents']),
			'EXTEND_CONTENTS' => FormatingHelper::second_parse($news['extend_contents']),
			'IMG' => FormatingHelper::second_parse_url($news['img']),
			'IMG_DESC' => $news['alt'],
			'ICON' => !empty($row['idcat']) ? FormatingHelper::second_parse_url($NEWS_CAT[$news['idcat']]['image']) : '',
			'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
			'LEVEL' =>	isset($news['level']) ? UserService::get_level_class($news['level']) : '',
			'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($news['login']) ? $news['login'] : false,
			'GROUP_COLOR' => $group_color,
			'COMMENTS' => isset($_GET['com']) && $NEWS_CONFIG['activ_com'] == 1 ? CommentsService::display($comments_topic)->render() : '',
			'NEXT_NEWS' => $next_news['title'],
			'PREVIOUS_NEWS' => $previous_news['title'],
			'FEED_MENU' => Feed::get_feed_menu(FEED_URL . '&amp;cat=' . $news['idcat']),
			'U_CAT' => !empty($row['idcat']) ? 'news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+'  . Url::encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php') : '',
			'U_LINK' => 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . Url::encode_rewrite($news['title']) . '.php'),
			'U_COM' => $NEWS_CONFIG['activ_com'] ? '<a href="'. PATH_TO_ROOT .'/news/news' . url('.php?id=' . $idnews . '&amp;com=0', '-' . $news['idcat'] . '-' . $idnews . '+' . Url::encode_rewrite($news['title']) . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('news', $idnews) . '</a>' : '',
			'U_USER_ID' => UserUrlBuilder::profile($news['user_id'])->absolute(),
			'U_SYNDICATION' => SyndicationUrlBuilder::rss('news', $news['idcat'])->rel(),
			'U_PREVIOUS_NEWS' => 'news' . url('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . Url::encode_rewrite($previous_news['title']) . '.php'),
			'U_NEXT_NEWS' => 'news' . url('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . Url::encode_rewrite($next_news['title']) . '.php'),
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_SYNDICATION' => $LANG['syndication'],
			'L_SOURCES' => $NEWS_LANG['sources'],
			'L_NEWS_SUGGESTED' => $NEWS_LANG['news_suggested']
		));

		$tpl->display();
	}
	else
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_news']);
        DispatchManager::redirect($controller);
	}
}
elseif ($user)
{
	// Bread crumb.
	$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));
	$Bread_crumb->add($NEWS_LANG['waiting_news'], url('news.php?user=1'));

	// Title of page
	define('TITLE', $NEWS_LANG['news']);
	require_once('../kernel/header.php');

	$tpl = new FileTemplate('news/news_block.tpl');
	$i = 0;

	// Build array with the children categories.
	$array_cat = array();
	$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_MODERATE);
	
	if ($User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE))
	{
		$array_cat[] = '0';
	}
	
	$where = !empty($array_cat) ? " AND n.idcat IN(" . implode(", ", $array_cat) . ")" : "";

	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.user_id, n.img, n.alt, m.login, m.level
	FROM " . DB_TABLE_NEWS . " n
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	WHERE (n.visible = 0 AND (n.start <= '" . $now->get_timestamp() . "' AND (n.end >= '" . $now->get_timestamp() . "' OR n.end = 0)) OR (n.visible = 2 AND (n.start > '" . $now->get_timestamp() . "' AND n.end >= '" . $now->get_timestamp() . "')) ". $where .")
	ORDER BY n.timestamp DESC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

		$tpl->assign_block_vars('news', array(
			'ID' => $row['id'],
			'U_SYNDICATION' => SyndicationUrlBuilder::rss('news', $row['idcat'])->rel(),
			'U_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
			'TITLE' => $row['title'],
			'C_EDIT' => true,
			'C_DELETE' => true,
			'C_DATE' => $NEWS_CONFIG['display_date'],
			'C_IMG' => !empty($row['img']),
			'IMG' => FormatingHelper::second_parse_url($row['img']),
			'IMG_DESC' => $row['alt'],
			'C_ICON' => $NEWS_CONFIG['activ_icon'] && !empty($row['idcat']),
			'U_CAT' => !empty($row['idcat']) ? 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php') : '',
			'ICON' => !empty($row['idcat']) ? FormatingHelper::second_parse_url($NEWS_CAT[$row['idcat']]['image']) : '',
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
			'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : '',
			'U_USER_ID' => UserUrlBuilder::profile($row['user_id'])->absolute(),
			'LEVEL' =>	isset($row['level']) ? UserService::get_level_class($row['level']) : '',
			'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
			'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));

		$i++;
	}

	$Sql->query_close($result);

	if ($i == 0)
	{
		$tpl->put_all(array(
			'C_NEWS_NO_AVAILABLE' => true,
			'L_LAST_NEWS' => $NEWS_LANG['waiting_news'],
			'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available'],
			'C_ADD' => $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE),
			'U_ADD' => url('management.php?new=1'),
			'L_ADD' => $NEWS_LANG['add_news']
		));
	}

	$tpl->display();
}
else
{
	define('TITLE', $NEWS_LANG['news']);
	$news_cat->bread_crumb($idcat);
	require_once('../kernel/header.php');
	
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'news';
	$module = $modulesLoader->get_provider($module_name);
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
	elseif (!$no_alert_on_error)
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            'Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}

require_once('../kernel/footer.php');

?>