<?php
/*##################################################
 *                               shoutbox_mini.php
 *                            -------------------
 *   begin                : July 29, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
  *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

if( defined('PHP_BOOST') !== true ) exit;

//Mini Shoutbox non activ�e si sur la page archive shoutbox.
if( SCRIPT !== DIR . '/shoutbox/shoutbox.php' )
{
	@include_once('../shoutbox/lang/' . $CONFIG['lang'] . '/shoutbox_' . $CONFIG['lang'] . '.php');
	###########################Insertion##############################
	if( !empty($_POST['shoutbox']) )
	{		
		//Membre en lecture seule?
		if( $session->data['user_readonly'] > time() ) 
		{
			$errorh->error_handler('e_readonly', E_USER_REDIRECT); 
			exit;
		}
			
		$shout_pseudo = !empty($_POST['shout_pseudo']) ? clean_user($_POST['shout_pseudo']) : $LANG['guest']; //Pseudo post�.
		$shout_contents = !empty($_POST['shout_contents']) ? trim($_POST['shout_contents']) : '';	
		if( !empty($shout_pseudo) && !empty($shout_contents) )
		{		
			//Chargement du cache
			$cache->load_file('shoutbox');	
			//Acc�s pour poster.
			if( $session->check_auth($session->data, $CONFIG_SHOUTBOX['shoutbox_auth']) )
			{
				//Mod anti-flood, autoris� aux membres qui b�nificie de l'autorisation de flooder.
				$check_time = ($session->data['user_id'] !== -1 && $CONFIG['anti_flood'] == 1) ? $sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."shoutbox WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__) : '';
				if( !empty($check_time) && !$groups->check_auth($groups->user_groups_auth, AUTH_FLOOD) )
				{
					if( $check_time >= (time() - $CONFIG['delay_flood']) )
					{
						header('location:' . HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=flood', '', '&'));
						exit;
					}
				}
				
				//V�rifie que le message ne contient pas du flood de lien.				
				$shout_contents = parse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
				if( !check_nbr_links($shout_pseudo, 0) ) //Nombre de liens max dans le pseudo.
				{	
					header('location:' . HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=lp_flood', '', '&'));
					exit;
				}
				if( !check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link']) ) //Nombre de liens max dans le message.
				{	
					header('location:' . HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=l_flood', '', '&'));
					exit;
				}
					
				$sql->query_inject("INSERT INTO ".PREFIX."shoutbox (login,user_id,contents,timestamp) VALUES('" . $shout_pseudo . "', '" . $session->data['user_id'] . "','" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
				
				header('location:' . HOST . transid(SCRIPT . '?' . QUERY_STRING, '', '&'));
				exit;
			}
			else //utilisateur non autoris�!
			{
				header('location:' . HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=auth', '', '&'));
				exit;
			}
		}	
	}
	
	###########################Affichage##############################
	$template->set_filenames(array(
		'shoutbox_mini' => '../templates/' . $CONFIG['theme'] . '/shoutbox/shoutbox_mini.tpl'
	 ));

	//Pseudo du membre connect�.
	if( $session->data['user_id'] !== -1 )
		$template->assign_block_vars('hidden_shout', array(
			'PSEUDO' => $session->data['login']
		));
	else
		$template->assign_block_vars('visible_shout', array(
			'PSEUDO' => $LANG['guest']
		));
		
	$template->assign_vars(array(
		'SID' => SID,		
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_ALERT_UNAUTH_POST' => $LANG['e_unauthorized'],
		'L_ALERT_FLOOD' => $LANG['e_flood'],
		'L_ALERT_LINK_FLOOD' => sprintf($LANG['e_l_flood'], $CONFIG_SHOUTBOX['shoutbox_max_link']),
		'L_ALERT_LINK_PSEUDO' => $LANG['e_link_pseudo'],
		'L_ALERT_INCOMPLETE' => $LANG['e_incomplete'],
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'L_SHOUTBOX' => $LANG['title_shoutbox'],
		'L_MESSAGE' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_ARCHIVE' => $LANG['archive']
	));
	
	$result = $sql->query_while("SELECT id, login, user_id, contents 
	FROM ".PREFIX."shoutbox 
	ORDER BY timestamp DESC 
	" . $sql->sql_limit(0, 25), __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$row['user_id'] = (int)$row['user_id'];		
		if( $session->check_auth($session->data, 1) || ($row['user_id'] === $session->data['user_id'] && $session->data['user_id'] !== -1) )
			$del = '<script type="text/javascript"><!-- 
			document.write(\'<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a>\'); 
			--></script><noscript><a href="../shoutbox/shoutbox' . transid('.php?del=true&amp;id=' . $row['id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a></noscript>';
		else
			$del = '';
	
		if( $row['user_id'] !== -1 ) 
			$row['login'] = $del . ' <a class="small_link" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
		else
			$row['login'] = $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
		
		$template->assign_block_vars('shout',array(
			'IDMSG' => $row['id'],
			'PSEUDO' => $row['login'],
			'CONTENTS' => ucfirst($row['contents']), //Majuscule premier caract�re.
			'USER_ID' => $row['user_id'],
			'DEL' => $del
		));							
	}
	$sql->close($result);
	
	$template->pparse('shoutbox_mini'); 
}

?>