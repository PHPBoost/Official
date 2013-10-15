<?php
/*##################################################
 *                               articles.php
 *                            -------------------
 *   begin                : July 17, 2005
 *   copyright            : (C) 2005 Viarre R�gis & (C) 2009 Maurel Nicolas
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

require_once('../kernel/begin.php'); 
require_once('articles_begin.php');
require_once('../kernel/header.php');

$articles_categories = new ArticlesCats();
$page = retrieve(GET, 'p', 1, TUNSIGNED_INT);
$cat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);	
$now = new Date(DATE_NOW, TIMEZONE_AUTO);

if (!empty($idart) && isset($cat))
{		
	$result = $Sql->query_while("SELECT a.contents, a.title, a.id, a.idcat, a.timestamp, a.sources, a.start, a.visible, a.user_id, a.icon, m.login, m.level, m.user_groups, notes.average_notes, notes.number_notes, note.note
		FROM " . DB_TABLE_ARTICLES . " a 
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
		LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = a.id AND notes.module_name = 'articles'
		LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = a.id AND note.module_name = 'articles' AND note.user_id = " . AppContext::get_current_user()->get_id() . "
		WHERE a.id = '" . $idart . "'" . (!$User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_MODERATE) ? " AND (visible = 1 OR start <= '" . $now->get_timestamp() . "' AND start > 0 AND (end >= '" . $now->get_timestamp() . "' OR end = 0))" : ""), __LINE__, __FILE__);
	$articles = $Sql->fetch_assoc($result);
	$Sql->query_close($result);

	if (!isset($ARTICLES_CAT[$cat]) || !$ARTICLES_CAT[$cat]['visible'])
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (empty($articles['id']))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_articles']);
        DispatchManager::redirect($controller);
	}
	
	$articles['auth'] = $ARTICLES_CAT[$articles['idcat']]['auth'];
	
	//checking authorization
	if ((!$User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_READ) || !$User->check_auth($articles['auth'], AUTH_ARTICLES_READ)) || ($articles['visible'] == 0 && $articles['start'] == 0 && $articles['end'] == 0 && $articles['user_id'] != $User->get_attribute('user_id')))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$tpl = new FileTemplate('articles/articles.tpl');
	
	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " " . DB_TABLE_ARTICLES . " SET views = views + 1 WHERE id = " . $idart, __LINE__, __FILE__); 
	
	//On cr�e une pagination si il y plus d'une page.
	 
	$Pagination = new DeprecatedPagination();

	//Si l'article ne commence pas par une page on l'ajoute.
	if (substr(trim($articles['contents']), 0, 6) != '[page]')
	{
		$articles['contents'] = ' [page]&nbsp;[/page]' . $articles['contents'];
	}
	else
	{
		$articles['contents'] = ' ' . $articles['contents'];
	}
		
	//Pagination des articles.
	$array_contents = preg_split('`\[page\].+\[/page\](.*)`Us', $articles['contents'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	//Récupération de la liste des pages.
	preg_match_all('`\[page\]([^[]+)\[/page\]`U', $articles['contents'], $array_page);
	$page_list = '<option value="1">' . $ARTICLES_LANG['select_page'] . '</option>';
	$page_list .= '<option value="1"></option>';
	$i = 1;

	$nbr_page = 0;
	foreach ($array_page[1] as $page_name)
	{
		$selected = ($i == $page) ? 'selected="selected"' : '';
		$page_list .= '<option value="' . $i++ . '"' . $selected . '>' . $page_name . '</option>';
		$nbr_page++;
	}
	
	$array_sources = unserialize($articles['sources']);
	$i = 0;
	foreach ($array_sources as $sources)
	{	
		$tpl->assign_block_vars('sources', array(
			'I' => $i,
			'SOURCE' => stripslashes($sources['sources']),
			'URL' => stripslashes($sources['url']),
			'INDENT'=> $i < (count($array_sources)-1) ? '-' : '',
		));
		$i++;
	}	
	
	$notation = new Notation();
	$notation->set_module_name('articles');
	$notation->set_id_in_module($articles['id']);
	$notation->set_notation_scale($CONFIG_ARTICLES['note_max']);
	$notation->set_number_notes($articles['number_notes']);
	$notation->set_average_notes($articles['average_notes']);
	$notation->set_user_already_noted(!empty($articles['note']));
	
	$group_color = User::get_group_color($articles['user_groups'], $articles['level']);
	
	$tpl->put_all(array(
		'C_IS_MODERATE' => ($User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_MODERATE)),
		'C_DISPLAY_ARTICLE' => true,
		'C_SOURCES' => $i > 0,
		'C_GROUP_COLOR' => !empty($group_color),
		'IDART' => $articles['id'],
		'IDCAT' => $cat,
		'NAME' => $articles['title'],
		'GROUP_COLOR' => $group_color,
		'PSEUDO' => $articles['login'],	
		'LEVEL_CLASS' => UserService::get_level_class($articles['level']),
		'CONTENTS' => isset($array_contents[$page]) ? FormatingHelper::second_parse($array_contents[$page]) : '',
		'CAT' => $ARTICLES_CAT[$cat]['name'],
		'DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'PAGES_LIST' => $page_list,
		'PAGINATION_ARTICLES' => $Pagination->display('articles' . url('.php?cat=' . $cat . '&amp;id='. $idart . '&amp;p=%d', '-' . $cat . '-'. $idart . '-%d+' . Url::encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS),
		'PAGE_NAME' => (isset($array_page[1][($page-1)]) && $array_page[1][($page-1)] != '&nbsp;') ? $array_page[1][($page-1)] : '',
		'PAGE_PREVIOUS_ARTICLES' => ($page > 1 && $page <= $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $cat . '&amp;id=' . $idart . '&amp;p=' . ($page - 1), 'articles-' . $cat . '-' . $idart . '-' . ($page - 1) . '+' . Url::encode_rewrite($articles['title']) . '.php') . '">&laquo; ' . $LANG['previous_page'] . '</a><br />' . $array_page[1][($page-2)] : '',
		'PAGE_NEXT_ARTICLES' => ($page > 0 && $page < $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $cat . '&amp;id=' . $idart . '&amp;p=' . ($page + 1), 'articles-' . $cat . '-' . $idart . '-' . ($page + 1) . '+' . Url::encode_rewrite($articles['title']) . '.php') . '">' . $LANG['next_page'] . ' &raquo;</a><br />' . $array_page[1][$page] : '',
		'COM' => '<a href="'. PATH_TO_ROOT .'/articles/articles' . url('.php?cat=' . $cat . '&amp;id=' . $idart . '&amp;com=0', '-' . $cat . '-' . $idart . '+' . Url::encode_rewrite($articles['title']) . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('articles', $idart) . '</a>',
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'L_DELETE' => $LANG['delete'],
		'L_EDIT' => $LANG['edit'],
		'L_SUBMIT' => $LANG['submit'],
		'L_WRITTEN' => $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_DATE' => $LANG['date'],
		'L_COM' => $LANG['com'],
		'L_PRINTABLE_VERSION' => $LANG['printable_version'],
		'L_SOURCE' => $ARTICLES_LANG['source'],
		'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
		'L_SUMMARY' => $ARTICLES_LANG['summary'],
		'L_ERASE' => $LANG['erase'],
		'L_INFO' => $LANG['info'],
		'U_PROFILE' => UserUrlBuilder::profile($articles['user_id'])->absolute(),
		'U_ARTICLES_LINK' => url('articles.php?cat=' . $cat . '&amp;id=' . $idart, 'articles-' . $cat . '-' . $idart .  Url::encode_rewrite($articles['title']) . '.php' . "'"),
		'U_ONCHANGE_ARTICLE' => "'" . url('articles.php?cat=' . $cat . '&amp;id=' . $idart . '&amp;p=\' + this.options[this.selectedIndex].value', 'articles-' . $idartcat . '-' . $idart . '-\'+ this.options[this.selectedIndex].value + \'+' . Url::encode_rewrite($articles['title']) . '.php' . "'"),
		'U_PRINT_ARTICLE' => url('print.php?id=' . $idart),
		'U_ARTICLES_EDIT' => url('management.php?edit=' . $idart),
		'U_ARTICLES_DEL' => url('management.php?del=' . $idart . '&amp;token=' . $Session->get_token()),
	));

	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$comments_topic = new ArticlesCommentsTopic();
		$comments_topic->set_id_in_module($idart);
		$comments_topic->set_url(new Url('/articles/articles.php?cat=' . $cat . '&id=' . $idart . '&com=0'));
		$tpl->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}	
	$tpl->display();
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'articles';
	$module = $modulesLoader->get_provider($module_name);
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
	elseif (!$no_alert_on_error) 
	{
		//TODO Gestion de la langue
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            'Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}
			
require_once('../kernel/footer.php'); 

?>