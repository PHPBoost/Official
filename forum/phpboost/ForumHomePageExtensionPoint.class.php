<?php
/*##################################################
 *                     ForumHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 07, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class ForumHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('forum');
		
		return $LANG['title_forum'];
	}
	
	private function get_view()
	{
		global $Cache, $LANG, $CONFIG_FORUM, $User, $auth_write, $Session, $CAT_FORUM, $AUTH_READ_FORUM, $nbr_msg_not_read, $Template, $Sql, $sid;
		
		require_once(PATH_TO_ROOT . '/forum/forum_begin.php');
		require_once(PATH_TO_ROOT . '/forum/forum_tools.php');
		
		$id_get = retrieve(GET, 'id', 0);

		$tpl = new FileTemplate('forum/forum_index.tpl');
		$tpl_top = new FileTemplate('forum/forum_top.tpl');
		$tpl_bottom = new FileTemplate('forum/forum_bottom.tpl');
		
		if ($CONFIG_FORUM['display_connexion'])
		{
			$display_connexion = array(	
				'C_FORUM_CONNEXION' => true,
				'L_CONNECT' => $LANG['connect'],
				'L_DISCONNECT' => $LANG['disconnect'],
				'L_AUTOCONNECT' => $LANG['autoconnect'],
				'L_REGISTER' => $LANG['register']
			);
			
			$tpl_top->put_all($display_connexion);
			$tpl_bottom->put_all($display_connexion);
		}
		
		$vars_tpl = array(	
			'C_DISPLAY_UNREAD_DETAILS' => ($User->get_attribute('user_id') !== -1) ? true : false,
			'C_MODERATION_PANEL' => $User->check_level(1) ? true : false,
			'U_TOPIC_TRACK' => '<a class="small_link" href="'. PATH_TO_ROOT .'/forum/track.php' . $sid . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
			'U_LAST_MSG_READ' => '<a class="small_link" href="'. PATH_TO_ROOT .'/forum/lastread.php' . $sid . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
			'U_MSG_NOT_READ' => '<a class="small_link" href="'. PATH_TO_ROOT .'/forum/unread.php' . $sid  . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . ($User->get_attribute('user_id') !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
			'U_MSG_SET_VIEW' => '<a class="small_link" href="'. PATH_TO_ROOT .'/forum/action' . url('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
			'L_MODERATION_PANEL' => $LANG['moderation_panel'],
			'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
			'L_AUTH_ERROR' => LangLoader::get_message('e_auth', 'errors'),
			'L_SEARCH' => $LANG['search'],
			'L_ADVANCED_SEARCH' => $LANG['advanced_search']
		);
		
		//Affichage des sous-cat�gories de la cat�gorie.
		$display_sub_cat = ' AND c.level BETWEEN 0 AND 1';
		$display_cat = !empty($id_get);
		if ($display_cat)
		{
			$intervall = $this->sql_querier->query_array(PREFIX . "forum_cats", "id_left", "id_right", "level", "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			$display_sub_cat = ' AND c.id_left > \'' . $intervall['id_left'] . '\'
		   AND c.id_right < \'' . $intervall['id_right'] . '\'
		   AND c.level = \'' . $intervall['level'] . '\' + 1';
		}

		//V�rification des autorisations.
		$unauth_cats = '';
		if (is_array($AUTH_READ_FORUM))
		{
			foreach ($AUTH_READ_FORUM as $idcat => $auth)
			{
				if ($auth === false)
					$unauth_cats .= $idcat . ',';
			}
			$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
		}

		//Calcul du temps de p�remption, ou de derni�re vue des messages par � rapport � la configuration.
		$max_time_msg = forum_limit_time_msg();

		$is_guest = ($User->get_attribute('user_id') !== -1) ? false : true;
		$total_topic = 0;
		$total_msg = 0;
		$i = 0;

		//On liste les cat�gories et sous-cat�gories.
		$result = $this->sql_querier->query_while("SELECT c.id AS cid, c.level, c.name, c.subname, c.url, c.nbr_msg, c.nbr_topic, c.status, c.last_topic_id, t.id AS tid,
		t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.login, m.level as user_level, m.user_groups, v.last_view_id
		FROM " . PREFIX . "forum_cats c
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = c.last_topic_id
		LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = '" . $User->get_attribute('user_id') . "' AND v.idtopic = t.id
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
		WHERE c.aprob = 1 " . $display_sub_cat . " " . $unauth_cats . "
		ORDER BY c.id_left", __LINE__, __FILE__);
		$display_sub_cats = false;
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			
			$tpl->assign_block_vars('forums_list', array());
			if ($CAT_FORUM[$row['cid']]['level'] == 0 && $i > 0 && $display_sub_cats) //Fermeture de la cat�gorie racine.
			{
				$tpl->assign_block_vars('forums_list.endcats', array(
				));
			}
			$i++;

			if ($row['level'] === '0') //Si c'est une cat�gorie
			{
				$tpl->assign_block_vars('forums_list.cats', array(
					'IDCAT' => $row['cid'],
					'NAME' => $row['name'],
					'U_FORUM_VARS' => url(PATH_TO_ROOT . '/forum/index.php?id=' . $row['cid'], 'cat-' . $row['cid'] . '+' . Url::encode_rewrite($row['name']) . '.php')
				));
				$display_sub_cats = (bool)$row['status'];
			}
			else //On liste les sous-cat�gories
			{
				if ($display_sub_cats || !empty($id_get))
				{
					if ($display_cat) //Affichage des forums d'une cat�gorie, ajout de la cat�gorie.
					{
						$tpl->assign_block_vars('forums_list.cats', array(
							'IDCAT' => $id_get,
							'NAME' => $CAT_FORUM[$id_get]['name'],
							'U_FORUM_VARS' => url(PATH_TO_ROOT . '/forum/index.php?id=' . $id_get, 'cat-' . $id_get . '+' . Url::encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php')
						));
						$display_cat = false;
					}
	
					$subforums = '';
					$tpl->put_all(array(
						'C_FORUM_ROOT_CAT' => false,
						'C_FORUM_CHILD_CAT' => true,
						'C_END_S_CATS' => false
					));
					if ($CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1)
					{
						foreach ($CAT_FORUM as $idcat => $key) //Listage des sous forums.
						{
							if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'])
							{
								if ($CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1)) //Sous forum distant d'un niveau au plus.
								{
									if ($AUTH_READ_FORUM[$row['cid']]) //Autorisation en lecture.
									{
										$link = !empty($CAT_FORUM[$idcat]['url']) ? '<a href="' . $CAT_FORUM[$idcat]['url'] . '" class="small_link">' : '<a href="forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small_link">';
										$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';
									}
								}
							}
						}
						$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
					}
	
					if (!empty($row['last_topic_id']))
					{
						//Si le dernier message lu est pr�sent on redirige vers lui, sinon on redirige vers le dernier post�.
						if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id r�alis� dans topic.php
						{
							$last_msg_id = $row['last_view_id'];
							$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
							$last_page_rewrite = '-0-' . $row['last_view_id'];
						}
						else
						{
							$last_msg_id = $row['last_msg_id'];
							$last_page = ceil($row['t_nbr_msg'] / $CONFIG_FORUM['pagination_msg']);
							$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
							$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
						}
	
						$last_topic_title = (($CONFIG_FORUM['activ_display_msg'] && $row['display_msg']) ? $CONFIG_FORUM['display_msg'] : '') . ' ' . ucfirst($row['title']);
						$last_topic_title = (strlen(TextHelper::html_entity_decode($last_topic_title)) > 20) ? TextHelper::substr_html($last_topic_title, 0, 20) . '...' : $last_topic_title;
						$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
						$group_color = User::get_group_color($row['user_groups'], $row['user_level']);
						
						$last = '<a href="'. PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . Url::encode_rewrite($row['title'])  . '.php') . '" class="small_link">' . $last_topic_title . '</a><br />
						<a href="'. PATH_TO_ROOT . '/forum/topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/ancre.png" alt="" /></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />' . $LANG['by'] . ' ' . ($row['last_user_id'] != '-1' ? '<a href="'. UserUrlBuilder::profile($row['last_user_id'])->absolute() . '" class="small_link '.UserService::get_level_class($row['user_level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>');
					}
					else
					{
						$row['last_timestamp'] = '';
						$last = '<br />' . $LANG['no_message'] . '<br /><br />';
					}
	
					//V�rifications des topics Lu/non Lus.
					$img_announce = 'announce';
					if (!$is_guest)
					{
						if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
							$img_announce =  'new_' . $img_announce; //Image affich� aux visiteurs.
					}
					$img_announce .= ($row['status'] == '0') ? '_lock' : '';
	
					$total_topic += $row['nbr_topic'];
					$total_msg += $row['nbr_msg'];
					
	
					$tpl->assign_block_vars('forums_list.subcats', array(
						'IMG_ANNOUNCE' => $img_announce,
						'NAME' => $row['name'],
						'DESC' => FormatingHelper::second_parse($row['subname']),
						'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
						'NBR_TOPIC' => $row['nbr_topic'],
						'NBR_MSG' => $row['nbr_msg'],
						'U_FORUM_URL' => $row['url'],
						'U_FORUM_VARS' => url(PATH_TO_ROOT .'/forum/forum.php?id=' . $row['cid'], 'forum-' . $row['cid'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
						'U_LAST_TOPIC' => $last
					));
				}
			}
		}
		$this->sql_querier->query_close($result);
		if ($i > 0) //Fermeture de la cat�gorie racine.
		{
			$tpl->assign_block_vars('forums_list', array(
			));
			$tpl->assign_block_vars('forums_list.endcats', array(
			));
		}
		
		$site_path = GeneralConfig::get_default_site_path();
		if (GeneralConfig::load()->get_module_home_page() == 'forum')
		{
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.session_script = '". $site_path ."/forum/' OR s.session_script = '". $site_path ."/forum/index.php' OR s.session_script = '". $site_path ."/index.php' OR s.session_script = '". $site_path ."/'");
		}
		else
		{
			$where = "AND s.session_script LIKE '". $site_path ."/forum/%'";
			if (!empty($id_get))
			{
				$where = "AND s.session_script LIKE '". $site_path . url('/forum/index.php?id=' . $id_get, '/forum/cat-' . $id_get . '+' . Url::encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php') ."'";
			}
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online($where);
		}
		
		$vars_tpl = array_merge($vars_tpl, array(
			'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
			'NBR_MSG' => $total_msg,
			'NBR_TOPIC' => $total_topic,
			'TOTAL_ONLINE' => $total_online,
			'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
			'ADMIN' => $total_admin,
			'MODO' => $total_modo,
			'MEMBER' => $total_member,
			'GUEST' => $total_visit,
			'SID' => SID,
			'SELECT_CAT' => !empty($id_get) ? forum_list_cat($id_get, 0) : '', //Retourne la liste des cat�gories, avec les v�rifications d'acc�s qui s'imposent.
			'C_TOTAL_POST' => true,
			'U_ONCHANGE' => url(PATH_TO_ROOT ."/forum/index.php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
			'U_ONCHANGE_CAT' => url(PATH_TO_ROOT ."/forum/index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
			'L_SEARCH' => $LANG['search'],
			'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
			'L_FORUM_INDEX' => $LANG['forum_index'],
			'L_FORUM' => $LANG['forum'],
			'L_TOPIC' => ($total_topic > 1) ? $LANG['topic_s'] : $LANG['topic'],
			'L_MESSAGE' => ($total_msg > 1) ? $LANG['message_s'] : $LANG['message'],
			'L_LAST_MESSAGE' => $LANG['last_message'],
			'L_STATS' => $LANG['stats'],
			'L_DISPLAY_UNREAD_MSG' => $LANG['show_not_reads'],
			'L_MARK_AS_READ' => $LANG['mark_as_read'],
			'L_TOTAL_POST' => $LANG['nbr_message'],
			'L_DISTRIBUTED' => strtolower($LANG['distributed']),
			'L_AND' => $LANG['and'],
			'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
			'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
			'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
			'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
			'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
			'L_AND' => $LANG['and'],
			'L_ONLINE' => strtolower($LANG['online'])
		));
		
		$tpl->put_all($vars_tpl);
		$tpl_top->put_all($vars_tpl);
		$tpl_bottom->put_all($vars_tpl);
		
		$tpl->add_subtemplate('forum_top', $tpl_top);
		$tpl->add_subtemplate('forum_bottom', $tpl_bottom);

		return $tpl;
	}
}
?>