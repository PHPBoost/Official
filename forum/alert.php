<?php
/*##################################################
 *                                alert.php
 *                            -------------------
 *   begin                : August 7, 2006
 *   copyright          : (C) 2006 Viarre R�gis / Sautel Beno�t
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$alert = retrieve(GET, 'id', 0);	
$alert_post = retrieve(POST, 'id', 0);	
$topic_id = !empty($alert) ? $alert : $alert_post;
$topic = $Sql->query_array('forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $topic_id . "'", __LINE__, __FILE__);

$cat_name = !empty($CAT_FORUM[$topic['idcat']]['name']) ? $CAT_FORUM[$topic['idcat']]['name'] : '';
$topic_name = !empty($topic['title']) ? $topic['title'] : '';
$Bread_crumb->Add_link($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->Add_link($cat_name, 'forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . url_encode_rewrite($cat_name) . '.php'));
$Bread_crumb->Add_link($topic['title'], 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'));
$Bread_crumb->Add_link($LANG['alert_topic'], '');

define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['alert_topic']);
require_once('../kernel/header.php');

if( empty($alert) && empty($alert_post) || empty($topic['idcat']) ) 
	redirect(HOST . DIR . '/forum/index' . transid('.php'));  

if( !$User->check_level(MEMBER_LEVEL) ) //Si c'est un invit�
    $Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
$Template->Set_filenames(array(
	'forum_alert'=> 'forum/forum_alert.tpl',
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));
	
//On fait un formulaire d'alerte
if( !empty($alert) && empty($alert_post) )
{
	//On v�rifie qu'une alerte sur le m�me sujet n'ait pas �t� post�e
	$nbr_alert = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On affiche le formulaire
	{
		$Template->Assign_vars(array(
			'KERNEL_EDITOR' => display_editor(),
			'L_ALERT' => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN' => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE' => $LANG['alert_title'],
			'L_ALERT_CONTENTS' => $LANG['alert_contents'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title']
		));
		
		$Template->Assign_block_vars('alert_form', array(
			'TITLE' => $topic_name,
			'U_TOPIC' => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'),
			'ID_ALERT' => $alert,
		));	
	}
	else //Une alerte a d�j� �t� post�e
	{
		$Template->Assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back'],
			'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php')
		));	
		
		$Template->Assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}

//Si on enregistre une alerte
if( !empty($alert_post) )
{
	$Template->Assign_vars(array(
		'L_ALERT' => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back'],
		'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert_post, '-' . $alert_post . '-' . url_encode_rewrite($topic_name) . '.php')
	));
	
	//On v�rifie qu'une alerte sur le m�me sujet n'ait pas �t� post�e
	$nbr_alert = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert_post ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On enregistre
	{
		$alert_title = retrieve(POST, 'title', '');
		$alert_contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		
		//Instanciation de la class du forum.
		include_once('../forum/forum.class.php');
		$Forumfct = new Forum;

		$Forumfct->Alert_topic($alert_post, $alert_title, $alert_contents);
		
		$Template->Assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
	}
	else //Une alerte a d�j� �t� post�e
	{
		$Template->Assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
	
}

//Listes les utilisateurs en lignes.
list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
$result = $Sql->query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions s 
LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '/forum/%'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $Sql->fetch_assoc($result) )
{
	switch( $row['level'] ) //Coloration du membre suivant son level d'autorisation. 
	{ 		
		case -1:
		$status = 'visiteur';
		$total_visit++;
		break;			
		case 0:
		$status = 'member';
		$total_member++;
		break;			
		case 1: 
		$status = 'modo';
		$total_modo++;
		break;			
		case 2: 
		$status = 'admin';
		$total_admin++;
		break;
	} 
	$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
	$users_list .= (!empty($row['login']) && $row['level'] != -1) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
}
$Sql->query_close($result);

$total_online = $total_admin + $total_modo + $total_member + $total_visit;
$Template->Assign_vars(array(
	'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['alert_topic'],
	'SID' => SID,
	'MODULE_DATA_PATH' => $Template->Module_data_path('forum'),
	'DESC' => $topic['subtitle'],
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php') . '">' . $CAT_FORUM[$topic['idcat']]['name'] . '</a>',
	'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $topic_id, '-' . $topic_id . '.php') . '">' . $topic['title'] . '</a>',
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
));

$Template->Pparse('forum_alert');	

include('../kernel/footer.php');

?>