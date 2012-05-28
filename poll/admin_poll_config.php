<?php
/*##################################################
 *                           admin_poll_config.php
 *                            -------------------
 *   begin                : June 21, 2005
 *   copyright            : (C) 2005 Viarre R�gis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
$poll_config = PollConfig::load();

if (!empty($_POST['valid']))
{
	$poll_config->set_authorizations(retrieve(POST, 'authorizations', '-1'));
	$poll_config->set_displayed_in_mini_module_list(!empty($_POST['displayed_in_mini_module_list']) ? $_POST['displayed_in_mini_module_list'] : array());
	$poll_config->set_cookie_name(retrieve(POST, 'cookie_name', 'poll', TSTRING_UNCHANGE));
	$poll_config->set_cookie_lenght(!empty($_POST['cookie_lenght']) ? NumberHelper::numeric($_POST['cookie_lenght']) : 30);

	PollConfig::save();
	
	###### R�g�n�ration du cache des sondages #######
	$Cache->Generate_module_file('poll');
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT); 	
}
else	
{		
	$Template->set_filenames(array(
	'admin_poll_config'=> 'poll/admin_poll_config.tpl'
	));

	$Cache->load('poll');
	
	$config_authorizations = $poll_config->get_authorizations();
	
	$i = 0;
	//Liste des sondages
	$poll_list = '';
	$result = $Sql->query_while("SELECT id, question 
	FROM " . PREFIX . "poll
	WHERE archive = 0 AND visible = 1
	ORDER BY timestamp", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$selected = in_array($row['id'], $poll_config->get_displayed_in_mini_module_list()) ? 'selected="selected"' : '';
		$poll_list .= '<option value="' . $row['id'] . '" ' . $selected . ' id="displayed_in_mini_module_list' . $i++ . '">' . $row['question'] . '</option>';
	}
	$Sql->query_close($result); 
	
	$Template->put_all(array(
		'COOKIE_NAME' => $poll_config->get_cookie_name(),
		'COOKIE_LENGHT' => $poll_config->get_cookie_lenght(),		
		'POLL_LIST' => $poll_list,		
		'NBR_POLL' => $i,		
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_POLL_CONFIG_MINI' => $LANG['poll_config_mini'],
		'L_POLL_CONFIG_ADVANCED' => $LANG['poll_config_advanced'],
		'L_DISPLAYED_IN_MINI_MODULE_LIST' => $LANG['displayed_in_mini_module_list'],
		'L_DISPLAYED_IN_MINI_MODULE_LIST_EXPLAIN' => $LANG['displayed_in_mini_module_list_explain'],
		'L_RANK' => $LANG['rank_vote'],
		'L_COOKIE_NAME' => $LANG['cookie_name'],
		'L_COOKIE_LENGHT' => $LANG['cookie_lenght'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_DAYS' => $LANG['days'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	//Rang d'autorisation.
	for ($i = -1; $i <= 2; $i++)
	{
		switch ($i) 
		{	
			case -1:
				$rank = $LANG['guest'];
			break;				
			case 0:
				$rank = $LANG['member'];
			break;				
			case 1: 
				$rank = $LANG['modo'];
			break;		
			case 2:
				$rank = $LANG['admin'];
			break;					
			default: -1;
		} 
		$selected = ($config_authorizations == $i) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('select_authorizations', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	} 
	 
	$Template->pparse('admin_poll_config');	
}

require_once('../admin/admin_footer.php');

?>