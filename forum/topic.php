<?php
/*##################################################
 *                                topic.php
 *                            -------------------
 *   begin                : October 26, 2005
 *   copyright            : (C) 2005 Viarre R�gis / Sautel Beno�t
 *   email                : mickaelhemri@gmail.com / ben.popeye@gmail.com
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

$id_get = retrieve(GET, 'id', 0);
$quote_get = retrieve(GET, 'quote', 0);	

//On va chercher les infos sur le topic	
$topic = !empty($id_get) ? PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', 'display_msg'), 'WHERE id=:id', array('id' => $id_get)) : '';
//Existance du topic.
if (empty($topic['id']))
{
	$controller = PHPBoostErrors::unexisting_page();
    DispatchManager::redirect($controller);
}
//Existance de la cat�gorie.
if (!isset($CAT_FORUM[$topic['idcat']]) || $CAT_FORUM[$topic['idcat']]['aprob'] == 0 || $CAT_FORUM[$topic['idcat']]['level'] == 0)
{
	$controller = PHPBoostErrors::unexisting_category();
    DispatchManager::redirect($controller);
}

//R�cup�ration de la barre d'arborescence.
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php');
foreach ($CAT_FORUM as $idcat => $array_info_cat)
{
	if ($CAT_FORUM[$topic['idcat']]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$topic['idcat']]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$topic['idcat']]['level'])
		$Bread_crumb->add($array_info_cat['name'], ($array_info_cat['level'] == 0) ? url('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php'));
}
if (!empty($CAT_FORUM[$topic['idcat']]['name'])) //Nom de la cat�gorie courante.
	$Bread_crumb->add($CAT_FORUM[$topic['idcat']]['name'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) . '.php'));
$Bread_crumb->add($topic['title'], '');

define('TITLE', $LANG['title_topic'] . ' - ' . $topic['title']);
require_once('../kernel/header.php'); 

$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : ''; //On encode l'url pour un �ventuel rewriting.
$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($topic['title']) : ''; //On encode l'url pour un �ventuel rewriting.

//Redirection changement de cat�gorie.
if (!empty($_POST['change_cat']))
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $_POST['change_cat'], '-' . $_POST['change_cat'] . $rewrited_cat_title . '.php', '&'));
	
//Autorisation en lecture.
if (!AppContext::get_current_user()->check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

if (!empty($CAT_FORUM[$topic['idcat']]['url']))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$tpl = new FileTemplate('forum/forum_topic.tpl');

$TmpTemplate = new FileTemplate('forum/forum_generic_results.tpl');
$module_data_path = $TmpTemplate->get_pictures_data_path();

//Si l'utilisateur a le droit de d�placer le topic, ou le verrouiller.	
$check_group_edit_auth = AppContext::get_current_user()->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM);
if ($check_group_edit_auth)
{
	$tpl->put_all(array(
		'C_FORUM_MODERATOR' => true,
		'C_FORUM_LOCK_TOPIC' => ($topic['status'] == '1') ? true : false,
		'U_TOPIC_LOCK' => url('.php?id=' . $id_get . '&amp;lock=true&amp;token=' . AppContext::get_session()->get_token()),
		'U_TOPIC_UNLOCK' => url('.php?id=' . $id_get . '&amp;lock=false&amp;token=' . AppContext::get_session()->get_token()),
		'U_TOPIC_MOVE' => url('.php?id=' . $id_get),
		'L_TOPIC_LOCK' => ($topic['status'] == '1') ? $LANG['forum_lock'] : $LANG['forum_unlock'],
		'L_TOPIC_MOVE' => $LANG['forum_move'],	
		'L_ALERT_DELETE_TOPIC' => $LANG['alert_delete_topic'],
		'L_ALERT_LOCK_TOPIC' => $LANG['alert_lock_topic'],
		'L_ALERT_UNLOCK_TOPIC' => $LANG['alert_unlock_topic'],
		'L_ALERT_MOVE_TOPIC' => $LANG['alert_move_topic'],
		'L_ALERT_CUT_TOPIC' => $LANG['alert_cut_topic']
	));
}
else
{
	$tpl->put_all(array(
		'C_FORUM_MODERATOR' => false
	));
}

//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou d�j� lus).
mark_topic_as_read($id_get, $topic['last_msg_id'], $topic['last_timestamp']);
	
//Gestion de la page si redirection vers le dernier message lu.
$idm = retrieve(GET, 'idm', 0);
if (!empty($idm))
{
	//Calcul de la page sur laquelle se situe le message.
	$nbr_msg_before = $Sql->query("SELECT COUNT(*) as nbr_msg_before FROM " . PREFIX . "forum_msg WHERE idtopic = " . $id_get . " AND id < '" . $idm . "'"); //Nombre de message avant le message de destination.
	
	//Dernier message de la page? Redirection vers la page suivante pour prendre en compte la reprise du message pr�c�dent.
	if (is_int(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'])) 
	{	
		//On redirige vers la page suivante, seulement si ce n'est pas la derni�re.
		if ($topic['nbr_msg'] != ($nbr_msg_before + 1))
			$nbr_msg_before++;
	}
	
	$_GET['pt'] = ceil(($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg']); //Modification de la page affich�e.
}

//On cr�e une pagination si le nombre de msg est trop important.
$page = AppContext::get_request()->get_getint('pt', 1);
$pagination = new ModulePagination($page, $topic['nbr_msg'], $CONFIG_FORUM['pagination_msg'], Pagination::LIGHT_PAGINATION);
$pagination->set_url(new Url('/forum/topic.php?id=' . $id_get . '&amp;pt=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//Affichage de l'arborescence des cat�gories.
$i = 0;
$forum_cats = '';
$Bread_crumb->remove_last();
foreach ($Bread_crumb->get_links() as $key => $array)
{
	if ($i == 2)
		$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
	elseif ($i > 2)
		$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
	$i++;
}

$vars_tpl = array(
	'C_PAGINATION' => $pagination->has_several_pages(),
	'C_FOCUS_CONTENT' => !empty($quote_get),
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
	'MODULE_DATA_PATH' => $module_data_path,
	'DESC' => !empty($topic['subtitle']) ? $topic['subtitle'] : '',
	'PAGINATION' => $pagination->display(),
	'USER_ID' => $topic['user_id'],
	'ID' => $topic['idcat'],
	'IDTOPIC' => $id_get,
	'PAGE' => $page,
	'TITLE_T' => ucfirst($topic['title']),
	'DISPLAY_MSG' => (($CONFIG_FORUM['activ_display_msg'] && $topic['display_msg']) ? $CONFIG_FORUM['display_msg'] . ' ' : '') ,
	'U_MSG_SET_VIEW' => '<a class="small" href="../forum/action' . url('.php?read=1&amp;f=' . $topic['idcat'], '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'U_CHANGE_CAT'=> 'topic' . url('.php?id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token(), '-' . $id_get . $rewrited_cat_title . '.php?token=' . AppContext::get_session()->get_token()),
	'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),		
	'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
	'U_FORUM_CAT' => !empty($forum_cats) ? $forum_cats . ' &raquo;' : '',
	'U_TITLE_T' => 'topic' . url('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php'),
	'L_REQUIRE_MESSAGE' => $LANG['require_text'],
	'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
	'L_GUEST' => $LANG['guest'],
	'L_DELETE' => $LANG['delete'],
	'L_EDIT' => $LANG['edit'],
	'L_CUT_TOPIC' => $LANG['cut_topic'],
	'L_EDIT_BY' => $LANG['edit_by'],
	'L_PUNISHMENT_MANAGEMENT' => $LANG['punishment_management'],
	'L_WARNING_MANAGEMENT' => $LANG['warning_management'],
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_QUOTE' => $LANG['quote'],
	'L_ON' => $LANG['on'],
	'L_RESPOND' => $LANG['respond'],
	'L_SUBMIT' => $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset']
);

//Cr�ation du tableau des rangs.
$array_ranks = array(-1 => $LANG['guest_s'], 0 => $LANG['member_s'], 1 => $LANG['modo_s'], 2 => $LANG['admin_s']);

list($track, $track_pm, $track_mail, $poll_done) = array(false, false, false, false);
$ranks_cache = ForumRanksCache::load()->get_ranks(); //R�cup�re les rangs en cache.
$quote_last_msg = ($page > 1) ? 1 : 0; //On enl�ve 1 au limite si on est sur une page > 1, afin de r�cup�rer le dernier msg de la page pr�c�dente.
$i = 0;	
$j = 0;	
$result = $Sql->query_while("SELECT msg.id, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, m.user_id, m.groups, p.question, p.answers, p.voter_id, p.votes, p.type, m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, ext_field.user_avatar, m.posted_msg, ext_field.user_website, ext_field.user_sign, ext_field.user_msn, ext_field.user_yahoo, m.warning_percentage, m.delay_readonly, m.delay_banned, m2.display_name as login_edit, s.user_id AS connect, tr.id AS trackid, tr.pm as trackpm, tr.track AS track, tr.mail AS trackmail, msg.contents
FROM " . PREFIX . "forum_msg msg
LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = '" . $id_get . "'
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = msg.user_id_edit
LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = '" . $id_get . "' AND tr.user_id = '" . AppContext::get_current_user()->get_id() . "'
LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id != -1
WHERE msg.idtopic = '" . $id_get . "'
ORDER BY msg.timestamp 
" . $Sql->limit(($pagination->get_display_from() - $quote_last_msg), ($CONFIG_FORUM['pagination_msg'] + $quote_last_msg)));
while ( $row = $Sql->fetch_assoc($result) )
{
	//Invit�?
	$is_guest = empty($row['user_id']);
	$first_message = ($row['id'] == $topic['first_msg_id']) ? true : false;

	//Gestion du niveau d'autorisation.
	list($edit, $del, $cut, $moderator) = array(false, false, false, false);
	if ($check_group_edit_auth || (AppContext::get_current_user()->get_id() == $row['user_id'] && !$is_guest && !$first_message))
	{
		list($edit, $del) = array(true, true);
		if ($check_group_edit_auth) //Fonctions r�serv�es � ceux poss�dants les droits de mod�rateurs seulement.
		{
			$cut = (!$first_message) ? true : false;
			$moderator = (!$is_guest) ? true : false;
		}
	}
	elseif (AppContext::get_current_user()->get_id() == $row['user_id'] && !$is_guest && $first_message) //Premier msg du topic => suppression du topic non autoris� au membre auteur du message.
		$edit = true;
	
	//Gestion des sondages => execut� une seule fois.
	if (!empty($row['question']) && $poll_done === false)
	{
		$tpl->put_all(array(				
			'C_POLL_EXIST' => true,
			'QUESTION' => $row['question'],				
			'U_POLL_RESULT' => url('.php?id=' . $id_get . '&amp;r=1&amp;pt=' . $page),
			'U_POLL_ACTION' => url('.php?id=' . $id_get . '&amp;p=' . $page . '&amp;token=' . AppContext::get_session()->get_token()),
			'L_POLL' => $LANG['poll'], 
			'L_VOTE' => $LANG['poll_vote'],
			'L_RESULT' => $LANG['poll_result']
		));
		
		$array_voter = explode('|', $row['voter_id']);			
		if (in_array(AppContext::get_current_user()->get_id(), $array_voter) || !empty($_GET['r']) || AppContext::get_current_user()->get_id() === -1) //D�j� vot�.
		{
			$array_answer = explode('|', $row['answers']);
			$array_vote = explode('|', $row['votes']);
			
			$sum_vote = array_sum($array_vote);	
			$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Emp�che la division par 0.

			foreach ($array_answer as $key => $answer)
			{
				$tpl->assign_block_vars('poll_result', array(
					'ANSWERS' => $answer, 
					'NBRVOTE' => $array_vote[$key],
					'WIDTH' => NumberHelper::round(($array_vote[$key] * 100 / $sum_vote), 1) * 4, //x 4 Pour agrandir la barre de vote.					
					'PERCENT' => NumberHelper::round(($array_vote[$key] * 100 / $sum_vote), 1)
				));
			}
		}
		else //Affichage des formulaires (radio/checkbox) pour voter.
		{
			$tpl->put_all(array(
				'C_POLL_QUESTION' => true
			));
			
			$z = 0;
			$array_answer = explode('|', $row['answers']);
			if ($row['type'] == 0)
			{
				foreach ($array_answer as $answer)
				{						
					$tpl->assign_block_vars('poll_radio', array(
						'NAME' => $z,
						'TYPE' => 'radio',
						'ANSWERS' => $answer
					));
					$z++;
				}
			}	
			elseif ($row['type'] == 1) 
			{
				foreach ($array_answer as $answer)
				{						
					$tpl->assign_block_vars('poll_checkbox', array(
						'NAME' => 'forumpoll' . $z,
						'TYPE' => 'checkbox',
						'ANSWERS' => $answer
					));
					$z++;	
				}
			}
		}
		$poll_done = true;	
	}
	
	//Rang de l'utilisateur.			
	$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
	$user_group = $user_rank;
	$user_rank_icon = '';
	if ($row['level'] === '2') //Rang sp�cial (admins).  
	{
		$user_rank = $ranks_cache[-2]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-2]['icon'];
	}
	elseif ($row['level'] === '1') //Rang sp�cial (modos).  
	{
		$user_rank = $ranks_cache[-1]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-1]['icon'];
	}
	else
	{
		foreach ($ranks_cache as $msg => $ranks_info)
		{
			if ($msg >= 0 && $msg <= $row['posted_msg'])
			{ 
				$user_rank = $ranks_info['name'];
				$user_rank_icon = $ranks_info['icon'];
			}
		}
	}

	//Image associ�e au rang.
	if (file_exists(TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/forum/images/ranks/' . $user_rank_icon))
	{
		$rank_img = TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/forum/images/ranks/' . $user_rank_icon;
	}
	else
	{
		$rank_img = TPL_PATH_TO_ROOT . '/forum/templates/images/ranks/' . $user_rank_icon;
	}
	$user_assoc_img = !empty($user_rank_icon) ? '<img src="' . $rank_img . '" alt="" />' : '';
	
	//Affichage des groupes du membre.		
	if (!empty($row['groups'])) 
	{	
		$user_groups = '';
		$array_user_groups = explode('|', $row['groups']);
		foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
		{
			if (is_numeric(array_search($idgroup, $array_user_groups)))
				$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
		}
	}
	else
		$user_groups = $LANG['group'] . ': ' . $user_group;

	$user_accounts_config = UserAccountsConfig::load();
	
	//Avatar			
	if (empty($row['user_avatar'])) 
		$user_avatar = ($user_accounts_config->is_default_avatar_enabled() == '1') ? '<img src="../templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
	else
		$user_avatar = '<img src="' . Url::to_rel($row['user_avatar']) . '" alt=""	/>';
		
	//Affichage du nombre de message.
	if ($row['posted_msg'] >= 1)
		$posted_msg = '<a href="'. UserUrlBuilder::messages($row['user_id'])->rel() . '" class="small">' . $LANG['message_s'] . '</a>: ' . $row['posted_msg'];
	else		
		$posted_msg = (!$is_guest) ? '<a href="../forum/membermsg' . url('.php?id=' . $row['user_id'], '') . '" class="small">' . $LANG['message'] . '</a>: 0' : $LANG['message'] . ': 0';		
	
	$extended_fields_cache = ExtendedFieldsCache::load();
	$user_msn_field = $extended_fields_cache->get_extended_field_by_field_name('user_msn');
	$user_yahoo_field = $extended_fields_cache->get_extended_field_by_field_name('user_yahoo');
	$user_sign_field = $extended_fields_cache->get_extended_field_by_field_name('user_sign');
	$user_website_field = $extended_fields_cache->get_extended_field_by_field_name('user_website');
	
	$tpl->assign_block_vars('msg', array(
		'ID' => $row['id'],
		'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
		'FORUM_ONLINE_STATUT_USER' => !empty($row['connect']) ? 'online' : 'offline',
		'FORUM_USER_LOGIN' => TextHelper::wordwrap_html($row['display_name'], 13),
		'FORUM_MSG_DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
		'FORUM_MSG_CONTENTS' => FormatingHelper::second_parse($row['contents']),
		'FORUM_USER_EDITOR_LOGIN' => $row['login_edit'],
		'FORUM_USER_EDITOR_DATE' => gmdate_format('date_format', $row['timestamp_edit']),
		'USER_RANK' => ($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0) ? $user_rank : $LANG['banned'],
		'USER_IMG_ASSOC' => $user_assoc_img,
		'USER_AVATAR' => $user_avatar,
		'USER_GROUP' => $user_groups,
		'USER_DATE' => (!$is_guest) ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
		'USER_MSG' => (!$is_guest) ? $posted_msg : '',
		'USER_MAIL' => ( !empty($row['email']) && ($row['show_email'] == '1' ) ) ? '<a href="mailto:' . $row['email'] . '" class="basic-button smaller">Mail</a>' : '',			
		'USER_MSN' => (!empty($row['user_msn']) && !empty($user_msn_field) && $user_msn_field['display']) ? '<a href="mailto:' . $row['user_msn'] . '" class="basic-button smaller">MSN</a>' : '',
		'USER_YAHOO' => (!empty($row['user_yahoo']) && !empty($user_yahoo_field) && $user_yahoo_field['display']) ? '<a href="mailto:' . $row['user_yahoo'] . '" class="basic-button smaller">Yahoo</a>' : '',
		'USER_SIGN' => (!empty($row['user_sign']) && !empty($user_sign_field) && $user_sign_field['display']) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
		'USER_WEB' => (!empty($row['user_website']) && !empty($user_website_field) && $user_website_field['display']) ? '<a href="' . $row['user_website'] . '" class="basic-button smaller">Web</a>' : '',
		'USER_WARNING' => $row['warning_percentage'],
		'L_FORUM_QUOTE_LAST_MSG' => ($quote_last_msg == 1 && $i == 0) ? $LANG['forum_quote_last_msg'] : '', //Reprise du dernier message de la page pr�c�dente.
		'C_USER_ONLINE' => !empty($row['connect']),
		'C_FORUM_USER_LOGIN' => !empty($row['display_name']),
		'C_FORUM_MSG_EDIT' => $edit,
		'C_FORUM_MSG_DEL' => $del,
		'C_FORUM_MSG_DEL_MSG' => (!$first_message),
		'C_FORUM_MSG_CUT' => $cut,
		'C_FORUM_USER_EDITOR' => ($row['timestamp_edit'] > 0 && $CONFIG_FORUM['edit_mark'] == '1'), //Ajout du marqueur d'�dition si activ�.
		'C_FORUM_USER_EDITOR_LOGIN' => !empty($row['login_edit']),
		'C_FORUM_MODERATOR' => $moderator,
		'U_FORUM_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
		'U_FORUM_MSG_EDIT' => url('.php?new=msg&amp;idm=' . $row['id'] . '&amp;id=' . $topic['idcat'] . '&amp;idt=' . $id_get),
		'U_FORUM_USER_EDITOR_PROFILE' => UserUrlBuilder::profile($row['user_id_edit'])->rel(),
		'U_FORUM_MSG_DEL' => url('.php?del=1&amp;idm=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_FORUM_WARNING' => url('.php?action=warning&amp;id=' . $row['user_id']),
		'U_FORUM_PUNISHEMENT' => url('.php?action=punish&amp;id=' . $row['user_id']),
		'U_FORUM_MSG_CUT' => url('.php?idm=' . $row['id']),
		'U_VARS_ANCRE' => url('.php?id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '') . $rewrited_title . '.php'),
		'U_VARS_QUOTE' => url('.php?quote=' . $row['id'] . '&amp;id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '-0') . '-0-' . $row['id'] . $rewrited_title . '.php'),
		'USER_PM' => !$is_guest && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) ? '<a href="'. UserUrlBuilder::personnal_message($row['user_id'])->rel() . '" class="basic-button smaller">MP</a>' : '',
	));
	
	//Marqueur de suivis du sujet.
	if (!empty($row['trackid'])) 
	{	
		$track = ($row['track']) ? true : false;
		$track_pm = ($row['trackpm']) ? true : false;
		$track_mail = ($row['trackmail']) ? true : false;
	}
	$j++;
	$i++;
}
$result->dispose();

//Listes les utilisateurs en lignes.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" . url('/forum/topic.php?id=' . $id_get, '/forum/topic-' . $id_get) ."%'");

$vars_tpl = array_merge($vars_tpl, array(
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'SELECT_CAT' => forum_list_cat($topic['idcat'], $CAT_FORUM[$topic['idcat']]['level']), //Retourne la liste des cat�gories, avec les v�rifications d'acc�s qui s'imposent.
	'U_SUSCRIBE' => ($track === false) ? url('.php?t=' . $id_get) : url('.php?ut=' . $id_get),
	'U_SUSCRIBE_PM' => url('.php?token=' . AppContext::get_session()->get_token() . '&amp;' . ($track_pm ? 'utp' : 'tp') . '=' . $id_get),
	'U_SUSCRIBE_MAIL' => url('.php?token=' . AppContext::get_session()->get_token() . '&amp;' . ($track_mail ? 'utm' : 'tm') . '=' . $id_get),
	'IS_TRACK' => $track ? 'true' : 'false',
	'IS_TRACK_PM' => $track_pm ? 'true' : 'false',
	'IS_TRACK_MAIL' => $track_mail ? 'true' : 'false',
	'IS_CHANGE' => $topic['display_msg'] ? 'true' : 'false',
	'U_ALERT' => url('.php?id=' . $id_get),
	'L_TRACK_DEFAULT' => ($track === false) ? $LANG['track_topic'] : $LANG['untrack_topic'],
	'L_SUSCRIBE_DEFAULT' => ($track_mail === false) ? $LANG['track_topic_mail'] : $LANG['untrack_topic_mail'],
	'L_SUSCRIBE_PM_DEFAULT' => ($track_pm === false) ? $LANG['track_topic_pm'] : $LANG['untrack_topic_pm'],
	'L_TRACK' => $LANG['track_topic'],
	'L_UNTRACK' => $LANG['untrack_topic'],
	'L_SUSCRIBE_PM' => $LANG['track_topic_pm'],
	'L_UNSUSCRIBE_PM' => $LANG['untrack_topic_pm'],
	'L_SUSCRIBE' => $LANG['track_topic_mail'],
	'L_UNSUSCRIBE' => $LANG['untrack_topic_mail'],
	'L_ALERT' => $LANG['alert_topic'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online']),
));

//R�cup�ration du message quot�.
$contents = '';
if (!empty($quote_get))
{	
	$quote_msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('user_id', 'contents'), 'WHERE id=:id', array('id' => $quote_get));
	$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $quote_msg['user_id'] . "'");	
	$contents = '[quote=' . $pseudo . ']' . FormatingHelper::unparse($quote_msg['contents']) . '[/quote]';
}

//Formulaire de r�ponse, non pr�sent si verrouill�.
if ($topic['status'] == '0' && !$check_group_edit_auth)
{
	$tpl->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_topic_lock_forum']
	));
}	
elseif (!AppContext::get_current_user()->check_auth($CAT_FORUM[$topic['idcat']]['auth'], WRITE_CAT_FORUM)) //On v�rifie si l'utilisateur a les droits d'�critures.
{
	$tpl->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
	));
}
else
{
	$img_track_display = $track ? 'fa-msg-not-track' : 'fa-msg-track';
	$img_track_pm_display = $track_pm ? 'fa-pm-not-track' : 'fa-pm-track';
	$img_track_mail_display = $track_mail ? 'fa-mail-not-track' : 'fa-mail-track';
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$tpl->put_all(array(
		'C_AUTH_POST' => true,
		'CONTENTS' => $contents,
		'KERNEL_EDITOR' => $editor->display(),
		'ICON_TRACK' => '<i class="fa ' . $img_track_display . '"></i>',
		'ICON_SUSCRIBE_PM' => '<i class="fa ' . $img_track_pm_display . '"></i>',
		'ICON_SUSCRIBE' => '<i class="fa ' . $img_track_mail_display . '"></i>',
		'U_FORUM_ACTION_POST' => url('.php?idt=' . $id_get . '&amp;id=' . $topic['idcat'] . '&amp;new=n_msg&amp;token=' . AppContext::get_session()->get_token()),
	));

	//Affichage du lien pour changer le display_msg du topic et autorisation d'�dition du statut.
	if ($CONFIG_FORUM['activ_display_msg'] == 1 && ($check_group_edit_auth || AppContext::get_current_user()->get_id() == $topic['user_id']))
	{
		$img_msg_display = $topic['display_msg'] ? 'fa-msg-not-display' : 'fa-msg-display';
		$tpl->put_all(array(
			'C_DISPLAY_MSG' => true,
			'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<i class="fa ' . $img_msg_display . '"></i>' : '',
			'L_DISPLAY_MSG' => $CONFIG_FORUM['display_msg'],
			'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG' => $CONFIG_FORUM['explain_display_msg'],
			'L_EXPLAIN_DISPLAY_MSG_BIS' => $CONFIG_FORUM['explain_display_msg_bis'],
			'U_ACTION_MSG_DISPLAY' => url('.php?msg_d=1&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token())
		));
	}
}

$tpl->put_all($vars_tpl);
$tpl_top->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);
	
$tpl->put('forum_top', $tpl_top);
$tpl->put('forum_bottom', $tpl_bottom);
	
$tpl->display();

include('../kernel/footer.php');

?>
