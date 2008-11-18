<?php
/*##################################################
 *                               admin_guestbook.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('guestbook'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']) )
{
	$config_guestbook = array();
	$config_guestbook['guestbook_auth'] = retrieve(POST, 'guestbook_auth', -1);
	$config_guestbook['guestbook_forbidden_tags'] = isset($_POST['guestbook_forbidden_tags']) ? serialize($_POST['guestbook_forbidden_tags']) : serialize(array());
	$config_guestbook['guestbook_max_link'] = retrieve(POST, 'guestbook_max_link', -1);
		
	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_guestbook)) . "' WHERE name = 'guestbook'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('guestbook');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_guestbook_config'=> 'guestbook/admin_guestbook_config.tpl'
	));
	
	$Cache->load('guestbook');
	
	//Balises interdites
	$i = 0;
	$tags = '';
	$CONFIG_GUESTBOOK['guestbook_forbidden_tags'] = isset($CONFIG_GUESTBOOK['guestbook_forbidden_tags']) ? $CONFIG_GUESTBOOK['guestbook_forbidden_tags'] : $array_tags;
	foreach (Content::get_available_tags() as $name)
	{
		$selected = '';
		if (in_array($name, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']))
			$selected = 'selected="selected"';
		$tags .= '<option id="tag' . $i++ . '" value="' . $name . '" ' . $selected . '>' . $name . '</option>';
	}
	
	$Template->assign_vars(array(
		'TAGS' => $tags,
		'NBR_TAGS' => $i,
		'MAX_LINK' => isset($CONFIG_GUESTBOOK['guestbook_max_link']) ? $CONFIG_GUESTBOOK['guestbook_max_link'] : '-1',
		'L_REQUIRE' => $LANG['require'],	
		'L_GUESTBOOK' => $LANG['title_guestbook'],
		'L_GUESTBOOK_CONFIG' => $LANG['guestbook_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
		
	$CONFIG_GUESTBOOK['guestbook_auth'] = isset($CONFIG_GUESTBOOK['guestbook_auth']) ? $CONFIG_GUESTBOOK['guestbook_auth'] : '-1';	
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

		$selected = ($CONFIG_GUESTBOOK['guestbook_auth'] == $i) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	}
	
	$Template->pparse('admin_guestbook_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>