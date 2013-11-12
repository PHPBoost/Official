<?php
/*##################################################
 *                                pm.php
 *                            -------------------
 *   begin                : July 12, 2006
 *   copyright            : (C) 2006 Viarre R�gis
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
define('TITLE', $LANG['title_pm']);
$Bread_crumb->add($LANG['member_area'], UserUrlBuilder::home()->rel());
$Bread_crumb->add($LANG['title_pm'], UserUrlBuilder::personnal_message()->rel());
require_once('../kernel/header.php');

//Interdit aux non membres.
if (!$User->check_level(User::MEMBER_LEVEL))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$pm_get = retrieve(GET, 'pm', 0);
$pm_id_get = retrieve(GET, 'id', 0);
$pm_del_convers = retrieve(GET, 'del_convers', false);
$quote_get = retrieve(GET, 'quote', 0);
$page = retrieve(GET, 'p', 0);
$post = retrieve(GET, 'post', false);
$pm_edit = retrieve(GET, 'edit', 0);
$pm_del = retrieve(GET, 'del', 0);
$read = retrieve(GET, 'read', false);

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

//Marque les messages priv�s comme lus
if ($read)
{
	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	$max_pm_number = UserAccountsConfig::load()->get_max_private_messages_number();
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, $max_pm_number);
	$unlimited_pm = $User->check_level(User::MODERATOR_LEVEL) || ($limit_group === -1);

	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages priv�s non visibles.
	
	$j = 0;
	$result = $Sql->query_while("SELECT pm.last_msg_id, pm.user_view_pm
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.idconvers = pm.id AND msg.id = pm.last_msg_id
	WHERE " . $User->get_attribute('user_id') . " IN (pm.user_id, pm.user_id_dest) AND pm.last_user_id <> '" . $User->get_attribute('user_id') . "' AND msg.view_status = 0
	ORDER BY pm.last_timestamp DESC ", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On saute l'it�ration si la limite est d�pass�.
		$j++;
		if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
			continue;
		$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET view_status = 1 WHERE id = '" . $row['last_msg_id'] . "'", __LINE__, __FILE__);
	}
	$Sql->query_close($result);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = '" . $nbr_waiting_pm . "' WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	AppContext::get_response()->redirect(UserUrlBuilder::personnal_message());
}

$convers = retrieve(POST, 'convers', false);
if ($convers && empty($pm_edit) && empty($pm_del)) //Envoi de conversation.
{
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$login = retrieve(POST, 'login', '');
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	//V�rification de la boite de l'exp�diteur.
	if (PrivateMsg::count_conversations($User->get_attribute('user_id')) >= $limit_group && (!$User->check_level(User::MODERATOR_LEVEL) && !($limit_group === -1))) //Bo�te de l'exp�diteur pleine.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_pm_full_post', '', '&') . '#message_helper');
		
	if (!empty($title) && !empty($contents) && !empty($login))
	{
		//On essaye de r�cup�rer le user_id, si le membre n'a pas cliqu� une fois la recherche AJAX termin�e.
		$user_id_dest = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
		if (!empty($user_id_dest) && $user_id_dest != $User->get_attribute('user_id'))
		{
			//Envoi de la conversation, v�rification de la boite si pleine => erreur
			list($pm_convers_id, $pm_msg_id) = PrivateMsg::start_conversation($user_id_dest, $title, FormatingHelper::strparse($contents, array(), false), $User->get_attribute('user_id'));
			//Succ�s redirection vers la conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_convers_id, '-0-' . $pm_convers_id . '.php', '&') . '#m' . $pm_msg_id);
		}
		else //Destinataire non trouv�.
			AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_unexist_user', '', '&') . '#message_helper');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/user/pm' . url('.php?post=1&error=e_incomplete', '', '&') . '#message_helper');
}
elseif (!empty($post) || (!empty($pm_get) && $pm_get != $User->get_attribute('user_id')) && $pm_get > '0') //Interface pour poster la conversation.
{
	$tpl = new FileTemplate('user/pm.tpl');

	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_RECIPIENT' => $LANG['require_recipient'],
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => $LANG['require'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers'],
		'L_RECIPIENT' => $LANG['recipient'],
		'L_SEARCH' => $LANG['search'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$login = !empty($pm_get) ? $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $pm_get . "'", __LINE__, __FILE__) : '';
	
	$tpl->assign_block_vars('post_convers', array(
		'U_ACTION_CONVERS' => url('.php?token=' . $Session->get_token()),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_USER_VIEW' => '<a href="' . UserUrlBuilder::personnal_message($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>',
		'LOGIN' => $login
	));
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	if (!$User->check_level(User::MODERATOR_LEVEL) && !($limit_group === -1) && $nbr_pm >= $limit_group)
		$tpl->put('message_helper', MessageHelper::display($LANG['e_pm_full_post'], E_USER_WARNING));
	else
	{
		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_unexist_user':
				$errstr = $LANG['e_unexist_member'];
				$type = E_USER_WARNING;
				break;
			case 'e_pm_full_post':
				$errstr = $LANG['e_pm_full_post'];
				$type = E_USER_WARNING;
				break;
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
			break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $type));
	}
	
	$tpl->assign_block_vars('post_convers.user_id_dest', array(
	));
	
	$tpl->display();
}
elseif (!empty($_POST['prw_convers']) && empty($mp_edit)) //Pr�visualisation de la conversation.
{
	$tpl = new FileTemplate('user/pm.tpl');
	
	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE' => $LANG['require'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers'],
		'L_RECIPIENT' => $LANG['recipient'],
		'L_SEARCH' => $LANG['search'],
		'L_TITLE' => $LANG['title'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$tpl->assign_block_vars('post_convers', array(
		'U_ACTION_CONVERS' => url('.php?token=' . $Session->get_token()),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_USER_VIEW' => '<a href="' . MemberUrlBuilder::profile($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>',
		'LOGIN' => !empty($_POST['login']) ? stripslashes($_POST['login']) : '',
		'TITLE' => !empty($_POST['title']) ? stripslashes($_POST['title']) : '',
		'CONTENTS' => !empty($_POST['contents']) ? stripslashes($_POST['contents']) : ''
	));
	
	$tpl->assign_block_vars('post_convers.show_convers', array(
		'DATE' => gmdate_format('date_format'),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents'])))
	));
	
	$tpl->assign_block_vars('post_convers.user_id_dest', array(
	));
	
	$tpl->display();
}
elseif (!empty($_POST['prw']) && empty($pm_edit) && empty($pm_del)) //Pr�visualisation du message.
{
	//On r�cup�re les info de la conversation.
	$convers_title = $Sql->query("SELECT title FROM " . DB_TABLE_PM_TOPIC . "  WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
	
	$tpl = new FileTemplate('user/pm.tpl');

	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$tpl->assign_block_vars('show_pm', array(
		'DATE' => gmdate_format('date_format'),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents']))),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get .'.php') . '">' . $convers_title . '</a>',
		'U_USER_VIEW' => '<a href="' . UserUrlBuilder::profile($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>',
	));
	
	$tpl->assign_block_vars('post_pm', array(
		'CONTENTS' => !empty($_POST['contents']) ? stripslashes($_POST['contents']) : '',
		'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get . '&amp;token=' . $Session->get_token())
	));
	
	$tpl->display();
}
elseif (!empty($_POST['pm']) && !empty($pm_id_get) && empty($pm_edit) && empty($pm_del)) //Envoi de messages.
{
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	if (!empty($contents))
	{
		//user_view_pm => nombre de messages non lu par l'un des 2 participants.
		
		//On r�cup�re les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'user_id', 'user_id_dest', 'user_convers_status', 'nbr_msg', 'user_view_pm', 'last_user_id', "WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
		
		//R�cup�ration de l'id du destinataire.
		$user_id_dest = ($convers['user_id_dest'] == $User->get_attribute('user_id')) ? $convers['user_id'] : $convers['user_id_dest'];
		
		if ($convers['user_convers_status'] == '0' && $user_id_dest > '0') //On v�rifie que la conversation n'a pas �t� supprim�e chez le destinataire, et que ce n'est pas un mp automatique du site.
		{
			//Vu par exp et pas par dest  => 1
			//Vu par dest et pas par exp  => 2
			if ($convers['user_id'] == $User->get_attribute('user_id')) //Le membre est le cr�ateur de la conversation.
				$status = 1;
			elseif ($convers['user_id_dest'] == $User->get_attribute('user_id')) //Le membre est le destinataire de la conversation.
				$status = 2;
			
			//Envoi du message priv�.
			$pm_msg_id = PrivateMsg::send($user_id_dest, $pm_id_get, FormatingHelper::strparse($contents, array(), false), $User->get_attribute('user_id'), $status);

			//Calcul de la page vers laquelle on redirige.
			$last_page = ceil( ($convers['nbr_msg'] + 1) / 25);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? '&p=' . $last_page : '';
			
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . $last_page, '-0-' . $pm_id_get . $last_page_rewrite . '.php', '&') . '#m' . $pm_msg_id);
		}
		else //Le destinataire a supprim� la conversation.
			AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_pm_del', '-0-' . $pm_id_get . '-0.php?error=e_pm_del', '&') . '#message_helper');
	}
	else //Champs manquants.
		AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm_id_get . '&error=e_incomplete', '-0-' . $pm_id_get . '-0-e_incomplete.php', '&') . '#message_helper');
}
elseif ($pm_del_convers) //Suppression de conversation.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	
	$Pagination = new DeprecatedPagination();
	$pagination_pm = 25;

	//Conversation pr�sente chez les deux membres: user_convers_status => 0.
	//Conversation supprim�e chez l'expediteur: user_convers_status => 1.
	//Conversation supprim�e chez le destinataire: user_convers_status => 2.
	$result = $Sql->query_while("SELECT id, user_id, user_id_dest, user_convers_status, last_msg_id
	FROM " . DB_TABLE_PM_TOPIC . "
	WHERE
	(
		" . $User->get_attribute('user_id') . " IN (user_id, user_id_dest)
	)
	AND
	(
		user_convers_status = 0
		OR
		(
			(user_id_dest = '" . $User->get_attribute('user_id') . "' AND user_convers_status = 1)
			OR
			(user_id = '" . $User->get_attribute('user_id') . "' AND user_convers_status = 2)
		)
	)
	ORDER BY last_timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg($pagination_pm, 'p'), $pagination_pm), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$del_convers = isset($_POST[$row['id']]) ? trim($_POST[$row['id']]) : '';
		if ($del_convers == 'on')
		{
			$del_convers = false;
			if ($row['user_id'] == $User->get_attribute('user_id')) //Expediteur.
			{
				$expd = true;
				if ($row['user_convers_status'] == 2)
					$del_convers = true;
			}
			elseif ($row['user_id_dest'] == $User->get_attribute('user_id')) //Destinataire
			{
				$expd = false;
				if ($row['user_convers_status'] == 1)
					$del_convers = true;
			}
			
			$view_status = $Sql->query("SELECT view_status FROM " . DB_TABLE_PM_MSG . " WHERE id = '" . $row['last_msg_id'] . "'", __LINE__, __FILE__);
			$update_nbr_pm = ($view_status == '0') ? true : false;
			PrivateMsg::delete_conversation($User->get_attribute('user_id'), $row['id'], $expd, $del_convers, $update_nbr_pm);
		}
	}
	
	AppContext::get_response()->redirect('/user/pm' . url('.php?pm=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php', '&'));
}
elseif (!empty($pm_del)) //Suppression du message priv�, si le destinataire ne la pas encore lu.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$pm = $Sql->query_array(DB_TABLE_PM_MSG, 'idconvers', 'contents', 'view_status', "WHERE id = '" . $pm_del . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if (!empty($pm['idconvers'])) //Permet de v�rifier si le message appartient bien au membre.
	{
		//On r�cup�re les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'title', 'user_id', 'user_id_dest', 'last_msg_id', "WHERE id = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
		if ($pm_del ==  $convers['last_msg_id']) //On �dite uniquement le dernier message.
		{
			if ($convers['user_id'] == $User->get_attribute('user_id')) //Expediteur.
			{
				$expd = true;
				$pm_to = $convers['user_id_dest'];
			}
			elseif ($convers['user_id_dest'] == $User->get_attribute('user_id')) //Destinataire
			{
				$expd = false;
				$pm_to = $convers['user_id'];
			}

			$view = false;
			if ($pm['view_status'] == '1') //Le membre a d�j� lu le message => �chec.
				$view = true;
				
			//Le destinataire n'a pas lu le message => on peut �diter.
			if ($view === false)
			{
				$id_first = $Sql->query("SELECT MIN(id) FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
				if ($pm_del > $id_first) //Suppression du message.
				{
					$pm_last_msg = PrivateMsg::delete($pm_to, $pm_del, $pm['idconvers']);
					AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'] . '.php', '&') . '#m' . $pm_last_msg);
				}
				elseif ($pm_del == $id_first) //Suppression de la conversation.
				{
					PrivateMsg::delete_conversation($pm_to, $pm['idconvers'], $expd, PrivateMsg::DEL_PM_CONVERS, PrivateMsg::UPDATE_MBR_PM);
					AppContext::get_response()->redirect('/user/pm.php' . SID2);
				}
			}
			else //Le membre a d�j� lu le message on ne peux plus le supprimer.
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['e_pm_nodel']);
                DispatchManager::redirect($controller);
			}
		}
		else //Echec.
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else //Echec.
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_edit)) //Edition du message priv�, si le destinataire ne la pas encore lu.
{
	$pm = $Sql->query_array(DB_TABLE_PM_MSG, 'idconvers', 'contents', 'view_status', "WHERE id = '" . $pm_edit . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if (!empty($pm['idconvers'])) //Permet de v�rifier si le message appartient bien au membre.
	{
		//On r�cup�re les info de la conversation.
		$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'title', 'user_id', 'user_id_dest', "WHERE id = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
		
		$view = false;
		if ($pm['view_status'] == '1') //Le membre a d�j� lu le message => �chec.
				$view = true;
			
		//Le destinataire n'a pas lu le message => on peut �diter.
		if ($view === false)
		{
			$id_first = $Sql->query("SELECT MIN(id) as id FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm['idconvers'] . "'", __LINE__, __FILE__);
			if (!empty($_POST['convers']) XOR !empty($_POST['edit_pm']))
			{
				$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
				$title = retrieve(POST, 'title', '');
				
				if (!empty($_POST['edit_pm']) && !empty($contents))
				{
					if ($pm_edit > $id_first) //Maj du message.
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET contents = '" . $contents . "', timestamp = '" . time() . "' WHERE id = '" . $pm_edit . "'", __LINE__, __FILE__);
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				elseif (!empty($_POST['convers']) && !empty($title)) //Maj de la conversation, si il s'agit du premier message.
				{
					if ($pm_edit == $id_first)
					{
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET title = '" . $title . "', last_timestamp = '" . time() . "' WHERE id = '" . $pm['idconvers'] . "' AND last_msg_id = '" . $pm_edit . "'", __LINE__, __FILE__);
						$Sql->query_inject("UPDATE " . DB_TABLE_PM_MSG . " SET contents = '" . $contents . "', timestamp = '" . time() . "' WHERE id = '" . $pm_edit . "'", __LINE__, __FILE__);
					}
					else //Echec.
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
				}
				else //Champs manquants.
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                        $LANG['e_incomplete']);
                    DispatchManager::redirect($controller);
				}
				
				//Succ�s redirection vers la conversation.
				AppContext::get_response()->redirect('/user/pm' . url('.php?id=' . $pm['idconvers'], '-0-' . $pm['idconvers'] . '.php', '&') . '#m' . $pm_edit);
			}
			else //Interface d'�dition
			{
				$tpl = new FileTemplate('user/pm.tpl');
				
				$tpl->put_all(array(
					'KERNEL_EDITOR' => $editor->display(),
					'L_REQUIRE_MESSAGE' => $LANG['require_text'],
					'L_REQUIRE' => $LANG['require'],
					'L_EDIT' => $LANG['edit'],
					'L_PRIVATE_MESSAGE' => $LANG['private_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset']
				));
				
				$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
				$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
				
				$tpl->assign_block_vars('edit_pm', array(
					'CONTENTS' => (!empty($_POST['prw_convers']) XOR !empty($_POST['prw'])) ? $contents : FormatingHelper::unparse($pm['contents']),
					'U_ACTION_EDIT' => url('.php?edit=' . $pm_edit . '&amp;token=' . $Session->get_token()),
					'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
					'U_USER_VIEW' => '<a href="' . UserUrlBuilder::profile($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>'
				));
				
				if (!empty($_POST['prw_convers']) XOR !empty($_POST['prw']))
				{
					$tpl->assign_block_vars('edit_pm.show_pm', array(
						'DATE' => gmdate_format('date_format'),
						'CONTENTS' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($_POST['contents']))),
					));
				}

				if ($id_first == $pm_edit) //Premier message de la convers => Edition de celle-ci
				{
					$tpl->put_all(array(
						'SUBMIT_NAME' => 'convers',
						'L_TITLE' => $LANG['title'],
					));
					
					$tpl->assign_block_vars('edit_pm.title', array(
						'TITLE' => (!empty($_POST['prw_convers']) XOR !empty($_POST['prw']) ) ? $title : $convers['title']
					));
				}
				else
					$tpl->put_all(array(
						'SUBMIT_NAME' => 'edit_pm',
					));
					
				$tpl->display();
			}
		}
		else //Le membre a d�j� lu le message on ne peux plus �diter.
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                $LANG['e_pm_noedit']);
            DispatchManager::redirect($controller);
		}
	}
	else //Echec.
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($pm_id_get)) //Messages associ�s � la conversation.
{
	$tpl = new FileTemplate('user/pm.tpl');
	
	//On cr�e une pagination si le nombre de MP est trop important.
	
	$Pagination = new DeprecatedPagination();

	//On r�cup�re les info de la conversation.
	$convers = $Sql->query_array(DB_TABLE_PM_TOPIC, 'id', 'title', 'user_id', 'user_id_dest', 'nbr_msg', 'last_msg_id', 'last_user_id', 'user_view_pm', "WHERE id = '" . $pm_id_get . "' AND '" . $User->get_attribute('user_id') . "' IN (user_id, user_id_dest)", __LINE__, __FILE__);

	//V�rification des autorisations.
	if (empty($convers['id']) || ($convers['user_id'] != $User->get_attribute('user_id') && $convers['user_id_dest'] != $User->get_attribute('user_id')))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if ($convers['user_view_pm'] > 0 && $convers['last_user_id'] != $User->get_attribute('user_id')) //Membre n'ayant pas encore lu la conversation.
	{
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_MEMBER . " SET user_pm = user_pm - " . (int)$convers['user_view_pm'] . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_PM_TOPIC . " SET user_view_pm = 0 WHERE id = '" . $pm_id_get . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_PM_MSG . " SET view_status = 1 WHERE idconvers = '" . $convers['id'] . "' AND user_id <> '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	}
	
	$pagination_msg = 25;
	$tpl->assign_block_vars('pm', array(
		'PAGINATION' => $Pagination->display('pm' . url('.php?id=' . $pm_id_get . '&amp;p=%d', '-0-' . $pm_id_get . '-%d.php'), $convers['nbr_msg'], 'p', $pagination_msg, 3),
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_TITLE_CONVERS' => '<a href="pm' . url('.php?id=' . $pm_id_get, '-0-' . $pm_id_get .'.php') . '">' . $convers['title'] . '</a>',
		'U_USER_VIEW' => '<a href="' . UserUrlBuilder::profile($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>'
	));

	$tpl->put_all(array(
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_RESPOND' => $LANG['respond'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_EDIT' => $LANG['edit'],
		'L_DELETE' => $LANG['delete'],
		'L_RESET' => $LANG['reset']
	));
	
	//Message non lu par autre membre que user_id view_status => 0.
	//Message lu par autre membre que user_id view_status => 1.
	
	$is_guest_in_convers = false;
	$page = retrieve(GET, 'p', 0); //Red�finition de la variable $page pour prendre en compte les redirections.
	$quote_last_msg = ($page > 1) ? 1 : 0; //On enl�ve 1 au limite si on est sur une page > 1, afin de r�cup�rer le dernier msg de la page pr�c�dente.
	$i = 0;
	$j = 0;
	$result = $Sql->query_while("SELECT msg.id, msg.user_id, msg.timestamp, msg.view_status, m.login, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, ext_field.user_avatar, m.user_msg, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, msg.contents
	FROM " . DB_TABLE_PM_MSG . " msg
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id <> -1
	WHERE msg.idconvers = '" . $pm_id_get . "'
	ORDER BY msg.timestamp
	" . $Sql->limit(($Pagination->get_first_msg($pagination_msg, 'p') - $quote_last_msg), ($pagination_msg + $quote_last_msg)), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];
		$is_admin = ($row['user_id'] === -1);
		if ($is_admin)
			$row['level'] = 2;
		
		if( !$is_guest_in_convers )
			$is_guest_in_convers = empty($row['login']);
		
		//Membre en ligne?
		$user_online = !empty($row['connect']) ? 'online' : 'offline';
		
		//Avatar
		if (empty($row['user_avatar']))
			$user_avatar = UserAccountsConfig::load()->is_default_avatar_enabled() ? '../templates/' . get_utheme() . '/images/' .  UserAccountsConfig::load()->get_default_avatar_name() : '';
		else
			$user_avatar = Url::to_rel($row['user_avatar']);
			
		//Reprise du dernier message de la page pr�c�dente.
		$row['contents'] = ($quote_last_msg == 1 && $i == 0) ? '<span class="text_strong">' . $LANG['quote_last_msg'] . '</span><br /><br />' . $row['contents'] : $row['contents'];
		$i++;
		
		$group_color = User::get_group_color($row['user_groups'], $row['level']);
		
		$tpl->assign_block_vars('pm.msg', array(
			'C_MODERATION_TOOLS' => (($User->get_attribute('user_id') === $row['user_id'] && $row['id'] === $convers['last_msg_id']) && ($row['view_status'] === '0')), //Dernier mp �ditable. et si le destinataire ne la pas encore lu
			'C_VISITOR' => $is_admin,
			'C_GROUP_COLOR' => !empty($group_color),
			
			'ID' => $row['id'],
			'CONTENTS' => FormatingHelper::second_parse($row['contents']),
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format', $row['timestamp']),
			'USER_AVATAR' => $user_avatar,
			'PSEUDO' => $is_admin ? $LANG['admin'] : (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']),
			'LEVEL_CLASS' => UserService::get_level_class($row['level']),
			'GROUP_COLOR' => $group_color,
		
			'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			
			'L_LEVEL' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1') : $LANG['banned']),
		));
		
		//Marqueur de suivis du sujet.
		if (!empty($row['track']))
			$track = true;
		$j++;
	}
	$Sql->query_close($result);

	//R�cup�ration du message quot�.
	if (!empty($quote_get))
	{
		$quote_msg = $Sql->query_array(DB_TABLE_PM_MSG, 'user_id', 'contents', "WHERE id = '" . $quote_get . "'", __LINE__, __FILE__);
		$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $quote_msg['user_id'] . "'", __LINE__, __FILE__);
		
		$contents = '[quote=' . $pseudo . ']' . FormatingHelper::unparse($quote_msg['contents']) . '[/quote]';
	}
	else
		$contents = '';

	if ($convers['user_id'] > 0 && !$is_guest_in_convers)
	{
		$tpl->put_all(array(
			'KERNEL_EDITOR' => $editor->display(),
		));
		
		$tpl->assign_block_vars('post_pm', array(
			'CONTENTS' => $contents,
			'U_PM_ACTION_POST' => url('.php?id=' . $pm_id_get . '&amp;token=' . $Session->get_token(), '-0-' . $pm_id_get . '.php?token=' . $Session->get_token())
		));
		
		//Gestion des erreurs
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'e_incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
				break;
			case 'e_pm_del':
				$errstr = $LANG['e_pm_del'];
				$type = E_USER_WARNING;
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $type));
	}
	
	$tpl->display();
}
else //Liste des conversation, dans la boite du membre.
{
	$tpl = new FileTemplate('user/pm.tpl');

	$nbr_pm = PrivateMsg::count_conversations($User->get_attribute('user_id'));
	
	//On cr�e une pagination si le nombre de MP est trop important.
	
	$Pagination = new DeprecatedPagination();

	$pagination_pm = 25;
	$pagination_msg = 25;
	
	$limit_group = $User->check_max_value(PM_GROUP_LIMIT, UserAccountsConfig::load()->get_max_private_messages_number());
	$unlimited_pm = $User->check_level(User::MODERATOR_LEVEL) || ($limit_group === -1);
	$pm_max = $unlimited_pm ? $LANG['illimited'] : $limit_group;
	
	$tpl->assign_block_vars('convers', array(
		'NBR_PM' => $pagination_pm,
		'PM_POURCENT' => '<strong>' . $nbr_pm . '</strong> / <strong>' . $pm_max . '</strong>',
		'PAGINATION' => $Pagination->display('pm' . url('.php?p=%d', '-0-0-%d.php'), $nbr_pm, 'p', $pagination_pm, 3),
		'U_MARK_AS_READ' => 'pm.php?read=1',
		'L_MARK_AS_READ' => $LANG['mark_pm_as_read'],
		'U_USER_ACTION_PM' => url('.php?del_convers=1&amp;p=' . $page . '&amp;token=' . $Session->get_token()),
		'U_USER_VIEW' => '<a href="' . UserUrlBuilder::profile($User->get_attribute('user_id'))->rel() . '">' . $LANG['member_area'] . '</a>',
		'U_PM_BOX' => '<a href="pm.php' . SID . '">' . $LANG['pm_box'] . '</a>',
		'U_POST_NEW_CONVERS' => 'pm' . url('.php?post=1', ''),
		'L_POST_NEW_CONVERS' => $LANG['post_new_convers']
	));
	
	//Aucun message priv�.
	if ($nbr_pm == 0)
	{
		$tpl->assign_block_vars('convers.no_pm', array(
			'L_NO_PM' => $LANG['no_pm']
		));
	}
	$nbr_waiting_pm = 0;
	if (!$unlimited_pm && $nbr_pm > $limit_group)
	{
		$nbr_waiting_pm = $nbr_pm - $limit_group; //Nombre de messages priv�s non visibles.
		//Gestion erreur.
		if ($nbr_waiting_pm > 0)
		{
			$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['e_pm_full'], $nbr_waiting_pm), E_USER_WARNING));
		}
	}
	
	$tpl->put_all(array(
		'L_REQUIRE_MESSAGE' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
		'L_PRIVATE_MSG' => $LANG['private_message'],
		'L_PM_BOX' => $LANG['pm_box'],
		'L_TITLE' => $LANG['title'],
		'L_PARTICIPANTS' => $LANG['participants'],
		'L_MESSAGE' => $LANG['replies'],
		'L_LAST_MESSAGE' => $LANG['last_message'],
		'L_STATUS' => $LANG['status'],
		'L_DELETE' => $LANG['delete'],
		'L_READ' => $LANG['read'],
		'L_TRACK' => $LANG['pm_track'],
		'L_NOT_READ' => $LANG['not_read']
	));

	//Conversation pr�sente chez les deux membres: user_convers_status => 0.
	//Conversation supprim�e chez l'expediteur: user_convers_status => 1.
	//Conversation supprim�e chez le destinataire: user_convers_status => 2.
	$i = 0;
	$j = 0;
	$result = $Sql->query_while("SELECT pm.id, pm.title, pm.user_id, pm.user_id_dest, pm.user_convers_status, pm.nbr_msg, pm.last_user_id, pm.last_msg_id, pm.last_timestamp, msg.view_status, m.login AS login, m1.login AS login_dest, m2.login AS last_login, m.level AS level, m1.level AS dest_level, m2.level AS last_level, m.user_groups AS user_groups, m1.user_groups AS dest_groups, m2.user_groups AS last_groups
	FROM " . DB_TABLE_PM_TOPIC . "  pm
	LEFT JOIN " . DB_TABLE_PM_MSG . " msg ON msg.id = pm.last_msg_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = pm.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = pm.user_id_dest
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = pm.last_user_id
	WHERE
	(
		" . $User->get_attribute('user_id') . " IN (pm.user_id, pm.user_id_dest)
	)
	AND
	(
		pm.user_convers_status = 0
		OR
		(
			(pm.user_id_dest = '" . $User->get_attribute('user_id') . "' AND pm.user_convers_status = 1)
			OR
			(pm.user_id = '" . $User->get_attribute('user_id') . "' AND pm.user_convers_status = 2)
		)
	)
	ORDER BY pm.last_timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg($pagination_pm, 'p'), $pagination_pm), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On saute l'it�ration si la limite est d�pass�, si ce n'est pas un message priv� du syst�me.
		if ($row['user_id'] != -1)
		{
			$j++;
			if (!$unlimited_pm && ($nbr_waiting_pm - $j) >= 0)
				continue;
		}
		
		$view = false;
		$track = false;
		if ($row['last_user_id'] == $User->get_attribute('user_id')) //Le membre est le dernier posteur.
		{
			$view = true;
			if ($row['view_status'] === '0') //Le d�stinataire n'a pas encore lu le message.
				$track = true;
		}
		else //Le membre n'est pas le dernier posteur.
		{
			if ($row['view_status'] === '1') //Le membre a d�j� lu le message.
				$view = true;
		}
	
		$img_announce = 'announce';
		//V�rifications des messages Lu/non Lus.
		if ($view === false) //Nouveau message (non lu).
			$img_announce = 'new_' . $img_announce;
		if ($track === true) //Marqueur de reception du message
			$img_announce = $img_announce . '_track';
			
		//Ancre vers vers le dernier message post�.
		$last_page = ceil( $row['nbr_msg'] / $pagination_msg);
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? 'p=' . $last_page . '&amp;' : '';
		
		$group_color = User::get_group_color($row['user_groups'], $row['level']);
		
		if ($row['user_id'] == -1)
			$author = $LANG['admin'];
		elseif (!empty($row['login']))
			$author = '<a href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '" class="small_link '.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>';
		else
			$author = '<strike>' . $LANG['guest'] . '</strike>';
			
		$participants = ($row['login_dest'] != $User->get_attribute('login')) ? $row['login_dest'] : $author;
		$user_id_dest = $row['user_id_dest'] != $User->get_attribute('user_id') ? $row['user_id_dest'] : $row['user_id'];
		$participants_group_color = ($participants != $LANG['admin'] && $participants != '<strike>' . $LANG['guest'] . '</strike>') ? User::get_group_color($row['dest_groups'], $row['dest_level']) : '';
		$participants = !empty($participants) ? '<a href="' . UserUrlBuilder::profile($user_id_dest)->rel() . '" class="'.UserService::get_level_class($row['dest_level']).'"' . (!empty($participants_group_color) ? ' style="color:' . $participants_group_color . '"' : '') . '>' . $participants . '</a>' : '<strike>' . $LANG['admin']. '</strike>';
		
		//Affichage du dernier message post�.
		$last_group_color = User::get_group_color($row['last_groups'], $row['last_level']);
		$last_msg = '<a href="pm' . url('.php?' . $last_page . 'id=' . $row['id'], '-0-' . $row['id'] . $last_page_rewrite . '.php') . '#m' . $row['last_msg_id'] . '" title=""><img src="../templates/' . get_utheme() . '/images/ancre.png" alt="" /></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />';
		$last_msg .= ($row['user_id'] == -1) ? $LANG['by'] . ' ' . $LANG['admin'] : $LANG['by'] . ' <a href="' . UserUrlBuilder::profile($row['last_user_id'])->rel() . '" class="small_link '.UserService::get_level_class($row['last_level']).'"' . (!empty($last_group_color) ? ' style="color:' . $last_group_color . '"' : '') . '>' . $row['last_login'] . '</a>';

		$tpl->assign_block_vars('convers.list', array(
			'INCR' => $i,
			'ID' => $row['id'],
			'ANNOUNCE' => '../templates/' . get_utheme() . '/images/' . $img_announce,
			'TITLE' => $row['title'],
			'MSG' => ($row['nbr_msg'] - 1),
			'U_PARTICIPANTS' => (($row['user_convers_status'] != 0) ? '<strike>' . $participants . '</strike>' : $participants),
			'U_CONVERS'	=> url('.php?id=' . $row['id'] . '&amp;token=' . $Session->get_token(), '-0-' . $row['id'] . '.php?token=' . $Session->get_token()),
			'U_AUTHOR' => $LANG['by'] . ' ' . $author,
			'U_LAST_MSG' => $last_msg
		));
		$i++;
	}
	$Sql->query_close($result);
	
	$tpl->display();
}

	
include('../kernel/footer.php');

?>