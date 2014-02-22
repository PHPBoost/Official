<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *   begin                : December 20, 2007
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');
load_module_lang('shoutbox'); //Chargement de la langue du module.

$add = !empty($_GET['add']) ? true : false;
$del = !empty($_GET['del']) ? true : false;
$refresh = !empty($_GET['refresh']) ? true : false;
$display_date = isset($_GET['display_date']) && ($_GET['display_date'] == '1');

$config_shoutbox = ShoutboxConfig::load();
if ($add)
{
	//Membre en lecture seule?
	if ($User->get_attribute('user_readonly') > time()) 
	{
		echo -6;
		exit;
	}
		
	$shout_pseudo = !empty($_POST['pseudo']) ? TextHelper::strprotect(utf8_decode($_POST['pseudo'])) : $LANG['guest'];
	$shout_contents = !empty($_POST['contents']) ? addslashes(trim(utf8_decode(htmlentities($_POST['contents'], ENT_COMPAT, "UTF-8")))) : '';
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		//Acc�s pour poster.		
		if ($User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_WRITE))
		{
			//Mod anti-flood, autoris� aux membres qui b�nificie de l'autorisation de flooder.
			$check_time = ($User->get_attribute('user_id') !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
				{
					echo -2;
					exit;
				}
			}
			
			//V�rifie que le message ne contient pas du flood de lien.
			$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());		
			if (!TextHelper::check_nbr_links($shout_pseudo, 0)) //Nombre de liens max dans le pseudo.
			{	
				echo -3;
				exit;
			}
			if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message())) //Nombre de liens max dans le message.
			{	
				echo -4;
				exit;
			}
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES('" . $shout_pseudo . "', '" . $User->get_attribute('user_id') . "', '" . $User->get_attribute('level') . "', '" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
			$last_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "shoutbox"); 
			
			$date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, time());
			$date = $date->format(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND);
			
			$array_class = array('member', 'modo', 'admin');
			if ($User->get_attribute('user_id') !== -1)
			{
				$group_color = User::get_group_color($User->get_groups(), $User->get_level(), true);
				$style = $group_color ? 'style="font-size:10px;color:'.$group_color.'"' : 'style="font-size:10px;"';
				$shout_pseudo = ($display_date ? '<span class="text_small">' . $date . ' : </span>' : '') . '<a href="javascript:Confirm_del_shout(' . $last_msg_id . ');" title="' . $LANG['delete'] . '"><img src="'. TPL_PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a> <a class="' . UserService::get_level_class($User->get_level()) . '" '.$style.' href="' . UserUrlBuilder::profile($User->get_attribute('user_id'))->absolute() . '">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest'])  . ' </a>';
			}
			else
				$shout_pseudo = ($display_date ? '<span class="text_small">' . $date . ' : </span>' : '') . '<span class="text_small" style="font-style: italic;">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest']) . ' </span>';
			
			echo "array_shout[0] = '" . $shout_pseudo . "';";
			echo "array_shout[1] = '" . FormatingHelper::second_parse(str_replace(array("\n", "\r"), array('', ''), ucfirst(stripslashes($shout_contents)))) . "';";
			echo "array_shout[2] = '" . $last_msg_id . "';";
		}
		else //utilisateur non autoris�!
			echo -1;
	}
	else
		echo -5;
}
elseif ($refresh)
{
	$array_class = array('member', 'modo', 'admin');
	$result = $Sql->query_while("SELECT s.id, s.login, s.user_id, s.level, s.contents, s.timestamp, m.user_groups
	FROM " . PREFIX . "shoutbox s
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
	ORDER BY timestamp DESC 
	" . $Sql->limit(0, 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];		
		if ($User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_MODERATION) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
			$del = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="'. TPL_PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a>';
		else
			$del = '';
			
		$date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
		$date = $date->format(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND);
		
		if ($row['user_id'] !== -1) 
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			$style = $group_color ? 'style="font-size:10px;color:'.$group_color.'"' : 'style="font-size:10px;"';
			$row['login'] = ($display_date ? '<span class="text_small">' . $date . ' : </span>' : '') . $del . ' <a class="' . UserService::get_level_class($row['level']) . '" '.$style.' href="'. UserUrlBuilder::profile($row['user_id'])->absolute()  . '">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest'])  . ' </a>';
		}
		else
			$row['login'] = ($display_date ? '<span class="text_small">' . $date . ' : </span>' : '') . $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest']) . ' </span>';
		
		echo '<p id="shout_container_' . $row['id'] . '">' . $row['login'] . '<span class="text_small">: ' . str_replace(array("\n", "\r"), array('', ''), ucfirst(FormatingHelper::second_parse(stripslashes($row['contents'])))) . '</span></p>' . "\n";
	}
	$Sql->query_close($result);
}
elseif ($del)
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$shout_id = !empty($_POST['idmsg']) ? NumberHelper::numeric($_POST['idmsg']) : '';
	if (!empty($shout_id))
	{
		$user_id = (int)$Sql->query("SELECT user_id FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
		if ($User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_MODERATION) || ($user_id === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			echo 1;
		}
	}
}

require_once('../kernel/footer_no_display.php');
?>