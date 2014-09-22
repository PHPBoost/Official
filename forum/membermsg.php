<?php
/*##################################################
 *                                membermsg.php
 *                            -------------------
 *   begin                : April 19, 2007
 *   copyright            : (C) 2007 Viarre R�gis
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
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php');
define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php'); 

$view_msg = retrieve(GET, 'id', 0);
if (!empty($view_msg)) //Affichage de tous les messages du membre
{
	$_NBR_ELEMENTS_PER_PAGE = 10;
	
	$tpl = new FileTemplate('forum/forum_membermsg.tpl');
	
	$auth_cats = '';
	foreach ($CAT_FORUM as $idcat => $key)
	{
		if (!AppContext::get_current_user()->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM))
			$auth_cats .= $idcat . ',';
	}
	$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
	
	$nbr_msg = $Sql->query("SELECT COUNT(*)
	FROM " . PREFIX . "forum_msg msg
	LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
	JOIN " . PREFIX . "forum_cats c ON t.idcat = c.id AND c.aprob = 1" . $auth_cats . "
	WHERE msg.user_id = '" . $view_msg . "'");
	
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_msg, $_NBR_ELEMENTS_PER_PAGE, Pagination::LIGHT_PAGINATION);
	$pagination->set_url(new Url('/forum/membermsg.php?id=' . $view_msg . '&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	$tpl->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['show_member_msg'],
		'PAGINATION' => $pagination->display(),
		'L_BACK' => $LANG['back'],
		'L_VIEW_MSG_USER' => $LANG['show_member_msg'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'U_FORUM_VIEW_MSG' => url('.php?id=' . $view_msg)
	));
	
	$result = $Sql->query_while("SELECT msg.id, msg.user_id, msg.idtopic, msg.timestamp, msg.timestamp_edit, m.groups, t.title, t.status, t.idcat, c.name, m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, m.posted_msg, m.warning_percentage, m.delay_banned, s.user_id AS connect, msg.contents
	FROM " . PREFIX . "forum_msg msg
	LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
	JOIN " . PREFIX . "forum_cats c ON t.idcat = c.id AND c.aprob = 1
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = '" . $view_msg . "'
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
	WHERE msg.user_id = '" . $view_msg . "'" . $auth_cats . "
	ORDER BY msg.id DESC
	" . $Sql->limit($pagination->get_display_from(), $_NBR_ELEMENTS_PER_PAGE));
	while ($row = $Sql->fetch_assoc($result))
	{
		//On encode l'url pour un �ventuel rewriting, c'est une op�ration assez gourmande
		$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['name']) : '';
		//On encode l'url pour un �ventuel rewriting, c'est une op�ration assez gourmande
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['title']) : '';
		
		//Ajout du marqueur d'�dition si activ�.
		$edit_mark = ($row['timestamp_edit'] > 0 && $CONFIG_FORUM['edit_mark'] == '0') ? '<br /><br /><br /><span style="padding: 10px;font-size:10px;font-style:italic;">' . $LANG['edit_by'] . ' <a class="edit_pseudo" href="'. UserUrlBuilder::profile($row['user_id_edit'])->rel() .'">' . $row['login_edit'] . '</a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp_edit']) . '</span><br />' : '';
		
		$group_color = User::get_group_color($row['groups'], $row['level']);
		
		$tpl->assign_block_vars('list', array(
			'C_GROUP_COLOR' => !empty($group_color),
			'C_GUEST' => empty($row['display_name']),
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
			'ID' => $row['id'],
			'USER_ONLINE' => '<i class="fa ' . (!empty($row['connect']) ? 'fa-online' : 'fa-offline') . '"></i>',
			'USER_PSEUDO' => !empty($row['display_name']) ? wordwrap(TextHelper::html_entity_decode($row['display_name']), 13, '<br />', 1) : $LANG['guest'],
			'LEVEL_CLASS' => UserService::get_level_class($row['level']),
			'GROUP_COLOR' => $group_color,
			'U_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			'U_VARS_ANCRE' => url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php'),
			'U_FORUM_CAT' => '<a class="forum-mbrmsg-links" href="../forum/forum' . url('.php?id=' . $row['idcat'], '-' . $row['idcat'] . $rewrited_cat_title . '.php') . '">' . $row['name'] . '</a>',
			'U_TITLE_T' => '<a class="forum-mbrmsg-links" href="../forum/topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php') . '">' . ucfirst($row['title']) . '</a>'
		));
	}
	$result->dispose();
	
	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" ."/forum/membermsg.php%'");

	$vars_tpl = array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	);
		
	$tpl->put_all($vars_tpl);
	$tpl_top->put_all($vars_tpl);
	$tpl_bottom->put_all($vars_tpl);
		
	$tpl->put('forum_top', $tpl_top);
	$tpl->put('forum_bottom', $tpl_bottom);
		
	$tpl->display();
}
else
	AppContext::get_response()->redirect('/forum/index.php');

require_once('../kernel/footer.php');

?>
