<?php
/*##################################################
 *                              forum_functions.php
 *                            -------------------
 *   begin                : December 11, 2007
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

//Listes les utilisateurs en ligne.
function forum_list_user_online($condition)
{
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = PersistenceContext::get_querier()->select("SELECT s.user_id, m.level, m.display_name, m.groups
	FROM " . DB_TABLE_SESSIONS . " s 
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id 
	WHERE s.timestamp > :timestamp " . $condition . "
	ORDER BY s.timestamp DESC", array(
		'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration())
	));
	while ($row = $result->fetch())
	{
		$group_color = User::get_group_color($row['groups'], $row['level']);
		switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
		{
			case -1:
			case '':
			$total_visit++;
			break;
			case 0:
			$total_member++;
			break;
			case 1: 
			$total_modo++;
			break;
			case 2: 
			$total_admin++;
			break;
		} 
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['display_name']) && $row['level'] != -1) ?  $coma . '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'" class="' . UserService::get_level_class($row['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a>' : '';
	}
	$result->dispose();
	
	$total = $total_admin + $total_modo + $total_member + $total_visit;
	
	if (empty($total))
	{
		$current_user = AppContext::get_current_user();
		
		if ($current_user->get_level() != User::VISITOR_LEVEL)
		{
			$group_color = User::get_group_color($current_user->get_groups(), $current_user->get_level());
			switch ($current_user->get_level()) //Coloration du membre suivant son level d'autorisation. 
			{
				case 0:
				$total_member++;
				break;
				case 1: 
				$total_modo++;
				break;
				case 2: 
				$total_admin++;
				break;
			} 
			$users_list .= '<a href="'. UserUrlBuilder::profile($current_user->get_id())->rel() .'" class="' . UserService::get_level_class($current_user->get_level()) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $current_user->get_display_name() . '</a>';
		}
		else
			$total_visit++;
		
		$total++;
	}
	
	return array($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total);
}

//Liste des cat�gories du forum.
function forum_list_cat($id_select, $level)
{
	global $CAT_FORUM, $AUTH_READ_FORUM;
	
	$select = '';
	foreach ($CAT_FORUM as $idcat => $array_cat)
	{
		$selected = '';
		if ($id_select == $idcat && $array_cat['level'] == $level)
			$selected = ' selected="selected"';
		
		$margin = ($array_cat['level'] > 0) ? str_repeat('------', $array_cat['level']) : '';
		$select .= $AUTH_READ_FORUM[$idcat] && empty($CAT_FORUM[$idcat]['url']) ? '<option value="' . $idcat . '"' . $selected . '>' . $margin . ' ' . $array_cat['name'] . '</option>' : '';
	}
	
	return $select;
}

//Calcul du temps de p�remption, ou de derni�re vue des messages par � rapport � la configuration.
function forum_limit_time_msg()
{
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum');
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	
	return $max_time_msg;
}

//Marque un topic comme lu.
function mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp)
{
	//Calcul du temps de p�remption, ou de derni�re vue des messages par � rapport � la configuration.
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum', 0);
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	if (AppContext::get_current_user()->get_id() !== -1 && $last_timestamp >= $max_time_msg)
	{
		$check_view_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_view", 'last_view_id', 'WHERE user_id = :user_id AND idtopic = :idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
		if (!empty($check_view_id) && $check_view_id != $last_msg_id) 
		{
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			PersistenceContext::get_querier()->update(PREFIX . "forum_view", array('last_view_id' => $last_msg_id, 'timestamp' => time()), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
		}
		elseif (empty($check_view_id))
		{
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			PersistenceContext::get_querier()->insert(PREFIX . "forum_view", array('idtopic' => $idtopic, 'last_view_id' => $last_msg_id, 'user_id' => AppContext::get_current_user()->get_id(), 'timestamp' => time()));
		}
		else
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
	}
	else
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
}

//Gestion de l'historique des actions sur le forum.
function forum_history_collector($type, $user_id_action = '', $url_action = '')
{
	PersistenceContext::get_querier()->insert(PREFIX . "forum_history", array('action' => $type, 'user_id' => AppContext::get_current_user()->get_id(), 'user_id_action' => NumberHelper::numeric($user_id_action), 'url' => $url_action, 'timestamp' => time()));
}

//Gestion du rss du forum.
function forum_generate_feeds()
{
    Feed::clear_cache('forum');
}

//Coloration de l'item recherch� en dehors des balises html.
function token_colorate($matches)
{
    static $open_tag = 0;
    static $close_tag = 0;
    
    $open_tag += substr_count($matches[1], '<');
    $close_tag += substr_count($matches[1], '>');
    
    if ($open_tag == $close_tag)
        return $matches[1] . '<span class="forum-search-word">' . $matches[2] . '</span>' . $matches[3];
    else
        return $matches[0];
}
?>