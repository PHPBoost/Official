<?php
/*##################################################
 *                            moderation_panel.php
 *                            -------------------
 *   begin                : March 20, 2007
 *   copyright            : (C) 2007 Viarre R�gis
 *   email                :  crowkait@phpboost.com
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

$Bread_crumb->add($LANG['member_area'], UserUrlBuilder::home()->absolute());
$Bread_crumb->add($LANG['moderation_panel'], UserUrlBuilder::moderation_panel()->absolute());

$action = retrieve(GET, 'action', 'warning', TSTRING_UNCHANGE);
$id_get = retrieve(GET, 'id', 0);
switch ($action)
{
	case 'ban':
		$Bread_crumb->add($LANG['bans'], UserUrlBuilder::moderation_panel('ban')->absolute());
		break;
	case 'punish':
		$Bread_crumb->add($LANG['punishment'], UserUrlBuilder::moderation_panel('punish')->absolute());
		break;
	case 'warning':
	default:
		$Bread_crumb->add($LANG['warning'], UserUrlBuilder::moderation_panel('warning')->absolute());
}

define('TITLE', $LANG['moderation_panel']);
require_once('../kernel/header.php');

if (!$User->check_level(User::MODERATOR_LEVEL)) //Si il n'est pas mod�rateur
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$moderation_panel_template = new FileTemplate('user/moderation_panel.tpl');	

$moderation_panel_template->put_all(array(
	'SID' => SID,
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_PUNISHMENT' => $LANG['punishment'],
	'L_WARNING' => $LANG['warning'],
	'L_BAN' => $LANG['bans'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_USERS_BAN' => $LANG['ban_management'],
	'U_WARNING' => UserUrlBuilder::moderation_panel('warning')->absolute(),
	'U_PUNISH' => UserUrlBuilder::moderation_panel('punish')->absolute(),
	'U_BAN' => UserUrlBuilder::moderation_panel('ban')->absolute()
));

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('action_contents');
	
if ($action == 'punish')
{
	//Gestion des utilisateurs
	$readonly = retrieve(POST, 'new_info', 0);
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if (!empty($id_get) && !empty($_POST['valid_user'])) //On met �  jour le niveau d'avertissement
	{
		if ($id_get != $User->get_attribute('user_id'))
		{
			if (!empty($readonly_contents))
			{
				MemberSanctionManager::remove_write_permissions($id_get, $readonly, MemberSanctionManager::SEND_MP, str_replace('%date', gmdate_format('date_format', $readonly), $readonly_contents));
			}
		}
		else
		{
			MemberSanctionManager::remove_write_permissions($id_get, $readonly, MemberSanctionManager::NO_SEND_CONFIRMATION, str_replace('%date', gmdate_format('date_format', $readonly), $readonly_contents));
		}
		
		AppContext::get_response()->redirect(HOST . DIR . url('/user/moderation_panel.php?action=punish', '', '&'));
	}
	
	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['punishment_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST' => 'punish_user',
		'U_ACTION' => UserUrlBuilder::moderation_panel('punish')->absolute()
	));
	
	if (empty($id_get)) //On liste les membres qui ont d�j� un avertissement
	{
		if (!empty($_POST['search_member']))
		{
			$login = retrieve(POST, 'login_mbr', '');
			$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('punish', $user_id)->absolute());
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('punish')->absolute());
		}	
				
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_punish_until'],
			'L_ACTION_USER' => $LANG['punishment_management'],
			'L_PROFILE' => $LANG['profile'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));	
			
		$i = 0;
		$result = $Sql->query_while("SELECT user_id, login, level, user_groups, user_readonly
		FROM " . PREFIX . "member
		WHERE user_readonly > " . time() . "
		ORDER BY user_readonly DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => gmdate_format('date_format', $row['user_readonly']),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('punish', $row['user_id'])->absolute() .'"><img src="../templates/' . get_utheme() . '/images/readonly.png" alt="" /></a>',
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->absolute(),
			));
			
			$i++;
		}
		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_punish'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'level', 'user_groups', 'user_readonly', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
				
		//On cr�e le formulaire select
		$select = '';
		//Dur�e de la sanction.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], '10 ' . strtolower($LANG['years'])); 

		$diff = ($member['user_readonly'] - time());	
		$key_sanction = 0;
		if ($diff > 0)
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement. 
			for ($i = 11; $i > 0; $i--)
			{					
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg) 
				{	
					$key_sanction = $i + 1;
					break;
				}
			}
		}
		//Affichge des sanctions
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . strtolower($array_sanction[$key]) . '</option>';
		}	
		
		$group_color = User::get_group_color($member['user_groups'], $member['level']);
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['login'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . $LANG['minute'], $LANG['user_readonly_changed']),
			'INFO' => $array_sanction[$key_sanction],
			'SELECT' => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
			'if (replace_value != \'326592000\'){'. "\n" .
			'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
			'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
			'var i;
			for (i = 0; i <= 12; i++)
			{
				if (array_time[i] == replace_value)
				{
					replace_value = array_sanction[i];
					break;
				}
			}' . "\n" .
			'if (replace_value != \'' . addslashes($LANG['no']) . '\')' . "\n" .
			'{' . "\n" .
				'contents = contents.replace(regex, replace_value);' . "\n" .
				'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
			'} else' . "\n" .
			'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
			'document.getElementById(\'action_info\').innerHTML = replace_value;}',
			'REGEX'=> '/[0-9]+ [a-zA-Z]+/',
			'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('ban', $id_get)->absolute() . '&amp;token=' . $Session->get_token(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->absolute(),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['submit']
		));		
	}
}
else if ($action == 'warning')
{
	$new_warning_level = retrieve(POST, 'new_info', 0);
	$warning_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if ($new_warning_level >= 0 && $new_warning_level <= 100 && isset($_POST['new_info']) && !empty($id_get) && !empty($_POST['valid_user'])) //On met �  jour le niveau d'avertissement
	{
		$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		//Mod�rateur ne peux avertir l'admin (logique non?).
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(User::ADMIN_LEVEL)))
		{
			if ($new_warning_level <= 100) //Ne peux pas mettre des avertissements sup�rieurs � 100.
			{
				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-m�me.
				if ($id_get != $User->get_attribute('user_id'))
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::SEND_MP, $warning_contents);				
				}
				else
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::NO_SEND_CONFIRMATION, $warning_contents);
				}
			}
		}
		
		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning')->absolute());
	}
	
	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['warning_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['warning_management'],
		'U_XMLHTTPREQUEST' => 'warning_user',		
		'U_ACTION' => UserUrlBuilder::moderation_panel('warning')->absolute() . '&amp;' . $Session->get_token()
	));
	
	if (empty($id_get)) //On liste les membres qui ont d�j� un avertissement
	{
		if (!empty($_POST['search_member']))
		{
			$login = retrieve(POST, 'login_mbr', '');
			$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning', $user_id)->absolute());
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning')->absolute());
		}		
		
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['warning_management'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));
		
		$i = 0;
		$result = $Sql->query_while("SELECT user_id, login, level, user_groups, user_warning
		FROM " . PREFIX . "member
		WHERE user_warning > 0
		ORDER BY user_warning", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => $row['user_warning'] . '%',
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('warning', $row['user_id'])->absolute() .'"><img src="../templates/' . get_utheme() . '/images/admin/important.png" alt="" /></a>',
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->absolute()
			));
			
			$i++;
		}
		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'level', 'user_groups', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
					
		//On cr�e le formulaire select
		$select = '';
		$j = 0;
		for ($j = 0; $j <=10; $j++)
		{
			if (10 * $j == $member['user_warning']) 
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}
		
		$group_color = User::get_group_color($member['user_groups'], $member['level']);
		
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['login'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'ALTERNATIVE_PM' => str_replace('%level%', $member['user_warning'], $LANG['user_warning_level_changed']),
			'INFO' => $LANG['user_warning_level'] . ': ' . $member['user_warning'] . '%',
			'SELECT' => $select,
			'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX'=> '/ [0-9]+%/',
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('warning', $id_get)->absolute() . '&amp;token=' . $Session->get_token(),
			'U_PM' => UserUrlBuilder::personnal_message($id_get)->absolute(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->absolute(),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['change_user_warning']
		));			
	}
}
else 
{
	$user_ban = retrieve(POST, 'user_ban', '', TSTRING_UNCHANGE);
	$user_ban = $user_ban > 0 ? (time() + $user_ban) : 0;
	if (!empty($_POST['valid_user']) && !empty($id_get)) //On banni le membre
	{
		$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'user_warning', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		MemberSanctionManager::banish($id_get, $user_ban, MemberSanctionManager::SEND_MAIL);

		if ($user_ban == 0 && $info_mbr['user_warning'] == 100)
		{
			MemberSanctionManager::remove_write_permissions($id_get, 90, MemberSanctionManager::NO_SEND_CONFIRMATION);			
		}
	
		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban')->absolute());
	}
	
	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['ban_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['ban_management'],
		'U_XMLHTTPREQUEST' => 'ban_user',
		'U_ACTION' => UserUrlBuilder::moderation_panel('ban')->absolute() . '&amp;token=' . $Session->get_token()
	));
	
	if (empty($id_get)) //On liste les membres qui ont d�j� un avertissement
	{
		if (!empty($_POST['search_member']))
		{
			$login = retrieve(POST, 'login_mbr', '');
			$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban', $user_id)->absolute());
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban')->absolute());
		}	
		
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_ban_until'],
			'L_ACTION_USER' => $LANG['ban_management'],
			'L_PROFILE' => $LANG['profile'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));	
			
		$i = 0;
		$result = $Sql->query_while("SELECT user_id, login, level, user_groups, user_ban, user_warning
		FROM " . PREFIX . "member
		WHERE user_ban > " . time() . " OR user_warning = 100
		ORDER BY user_ban", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => ($row['user_warning'] != 100) ? gmdate_format('date_format', $row['user_ban']) : $LANG['illimited'],
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('ban', $row['user_id'])->absolute()  .'"><img src="../templates/' . get_utheme() . '/images/admin/forbidden.png" alt="" /></a>',
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->absolute(),
			));
			
			$i++;
		}
		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_ban'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'level', 'user_groups', 'user_ban', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		$group_color = User::get_group_color($member['user_groups'], $member['level']);
		
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_BAN' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['login'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'U_PM' => UserUrlBuilder::personnal_message($id_get)->absolute(),
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('ban', $id_get)->absolute() . '&amp;token=' . $Session->get_token(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->absolute(),
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_BAN' => $LANG['ban_user'],
			'L_DELAY_BAN' => $LANG['user_ban_delay'],
		));	
		
		//Temps de bannissement.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['illimited']); 
		
		$diff = ($member['user_ban'] - time());	
		$key_sanction = 0;
		if ($diff > 0)
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement. 
			for ($i = 11; $i >= 0; $i--)
			{					
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg)  
				{	
					$key_sanction = $i + 1;
					break;
				}
			}
		}
		if ($member['user_warning'] == 100)
			$key_sanction = 12;
			
		//Affichge des sanctions
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$moderation_panel_template->assign_block_vars('select_ban', array(
				'TIME' => '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>'
			));
		}	
	}
}

$moderation_panel_template->display();

require_once('../kernel/footer.php');

?>