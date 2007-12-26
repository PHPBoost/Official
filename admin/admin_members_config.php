<?php
/*##################################################
 *                               admin_members_config.php
 *                            -------------------
 *   begin                : April 15, 2006
 *   copyright          : (C) 2006 Viarre R�gis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

if( !empty($_POST['msg_mbr']) ) //Message aux membres.
{
	$config_member['activ_register'] = !empty($_POST['activ_register']) ? numeric($_POST['activ_register']) : 0;
	$config_member['msg_mbr'] = !empty($_POST['contents']) ? stripslashes(parse($_POST['contents'])) : '';
	$config_member['msg_register'] = $CONFIG_MEMBER['msg_register'];
	$config_member['activ_mbr'] = isset($_POST['activ_mbr']) ? numeric($_POST['activ_mbr']) : 0; //d�sactiv� par defaut. 
	$config_member['verif_code'] = (isset($_POST['verif_code']) && @extension_loaded('gd')) ? numeric($_POST['verif_code']) : 0; //d�sactiv� par defaut. 
	$config_member['delay_unactiv_max'] = isset($_POST['delay_unactiv_max']) ? numeric($_POST['delay_unactiv_max']) : ''; 
	$config_member['force_theme'] = isset($_POST['force_theme']) ? numeric($_POST['force_theme']) : '0'; //D�sactiv� par d�faut.
	$config_member['activ_up_avatar'] = isset($_POST['activ_up_avatar']) ? numeric($_POST['activ_up_avatar']) : '0'; //D�sactiv� par d�faut.
	$config_member['width_max'] = !empty($_POST['width_max']) ? numeric($_POST['width_max']) : '120';
	$config_member['height_max'] = !empty($_POST['height_max']) ? numeric($_POST['height_max']) : '120';
	$config_member['weight_max'] = !empty($_POST['weight_max']) ? numeric($_POST['weight_max']) : '20';
	$config_member['activ_avatar'] = isset($_POST['activ_avatar']) ? numeric($_POST['activ_avatar']) : 0;
	$config_member['avatar_url'] = !empty($_POST['avatar_url']) ? securit($_POST['avatar_url']) : '';
	
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_member)) . "' WHERE name = 'member'", __LINE__, __FILE__); //MAJ	
	
	###### R�g�n�ration du cache $CONFIG_MEMBER #######
	$cache->generate_file('member');
	
	header('location:' . HOST . SCRIPT); 	
	exit;
}
else
{			
	$template->set_filenames(array(
		'admin_members_config' => '../templates/' . $CONFIG['theme'] . '/admin/admin_members_config.tpl'
	));
	
	$template->assign_vars(array(
		'ACTIV_REGISTER_ENABLED' => $CONFIG_MEMBER['activ_register'] == 1 ? 'selected="selected"' : '',
		'ACTIV_REGISTER_DISABLED' => $CONFIG_MEMBER['activ_register'] == 0 ? 'selected="selected"' : '',
		'VERIF_CODE_ENABLED' => ($CONFIG_MEMBER['verif_code'] == 1 && @extension_loaded('gd')) ? 'checked="checked"' : '',
		'VERIF_CODE_DISABLED' => ($CONFIG_MEMBER['verif_code'] == 0) ? 'checked="checked"' : '',
		'DELAY_UNACTIV_MAX' => !empty($CONFIG_MEMBER['delay_unactiv_max']) ? $CONFIG_MEMBER['delay_unactiv_max'] : '',
		'ALLOW_THEME_ENABLED' => ($CONFIG_MEMBER['force_theme'] == 0) ? 'checked="checked"' : '',
		'ALLOW_THEME_DISABLED' => ($CONFIG_MEMBER['force_theme'] == 1) ? 'checked="checked"' : '',
		'AVATAR_UP_ENABLED' => ($CONFIG_MEMBER['activ_up_avatar'] == 1) ? 'checked="checked"' : '',
		'AVATAR_UP_DISABLED' => ($CONFIG_MEMBER['activ_up_avatar'] == 0) ? 'checked="checked"' : '',
		'AVATAR_ENABLED' => ($CONFIG_MEMBER['activ_avatar'] == 1) ? 'checked="checked"' : '',
		'AVATAR_DISABLED' => ($CONFIG_MEMBER['activ_avatar'] == 0) ? 'checked="checked"' : '',
		'WIDTH_MAX' => !empty($CONFIG_MEMBER['width_max']) ? $CONFIG_MEMBER['width_max'] : '120',
		'HEIGHT_MAX' => !empty($CONFIG_MEMBER['height_max']) ? $CONFIG_MEMBER['height_max'] : '120',
		'WEIGHT_MAX' => !empty($CONFIG_MEMBER['weight_max']) ? $CONFIG_MEMBER['weight_max'] : '20',
		'AVATAR_URL' => !empty($CONFIG_MEMBER['avatar_url']) ? $CONFIG_MEMBER['avatar_url'] : '',
		'CONTENTS' => unparse($CONFIG_MEMBER['msg_mbr']),
		'GD_DISABLED' => (!@extension_loaded('gd')) ? 'disabled="disabled"' : '',
		'L_KB' => $LANG['unit_kilobytes'],
		'L_PX' => $LANG['unit_pixels'],
		'L_ACTIV_REGISTER' => $LANG['activ_register'],
		'L_REQUIRE_MAX_WIDTH' => $LANG['require_max_width'],
		'L_REQUIRE_HEIGHT' => $LANG['require_height'],
		'L_REQUIRE_WEIGHT' => $LANG['require_weight'],
		'L_MEMBERS_MANAGEMENT' => $LANG['members_management'],
		'L_MEMBERS_ADD' => $LANG['members_add'],
		'L_MEMBERS_CONFIG' => $LANG['members_config'],
		'L_MEMBERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_MEMBERS_MSG' => $LANG['members_msg'],
		'L_ACTIV_MBR' => $LANG['activ_mbr'],
		'L_DELAY_UNACTIV_MAX' => $LANG['delay_activ_max'],
		'L_DELAY_UNACTIV_MAX_EXPLAIN' => $LANG['delay_activ_max_explain'],
		'L_DAYS' => $LANG['days'],
		'L_VERIF_CODE' => $LANG['verif_code'],
		'L_VERIF_CODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_ALLOW_THEME_MBR' => $LANG['allow_theme_mbr'],
		'L_AVATAR_MANAGEMENT' => $LANG['avatar_management'],
		'L_ACTIV_UP_AVATAR' => $LANG['activ_up_avatar'],
		'L_WIDTH_MAX_AVATAR' => $LANG['width_max_avatar'],
		'L_WIDTH_MAX_AVATAR_EXPLAIN' => $LANG['width_max_avatar_explain'],
		'L_HEIGHT_MAX_AVATAR' => $LANG['height_max_avatar'],
		'L_HEIGHT_MAX_AVATAR_EXPLAIN' => $LANG['height_max_avatar_explain'],
		'L_WEIGHT_MAX_AVATAR' => $LANG['weight_max_avatar'],
		'L_WEIGHT_MAX_AVATAR_EXPLAIN' => $LANG['weight_max_avatar_explain'],
		'L_ACTIV_DEFAUT_AVATAR' => $LANG['activ_defaut_avatar'],
		'L_ACTIV_DEFAUT_AVATAR_EXPLAIN' => $LANG['activ_defaut_avatar_explain'],
		'L_URL_DEFAUT_AVATAR' => $LANG['url_defaut_avatar'],
		'L_URL_DEFAUT_AVATAR_EXPLAIN' => $LANG['url_defaut_avatar_explain'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_CONTENTS' => $LANG['contents'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	#####################Activation du mail par le membre pour s'inscrire##################
	$array = array(0 => $LANG['no_activ_mbr'], 1 => $LANG['mail'], 2 => $LANG['admin']);
	foreach($array as $key => $value )
	{
		$selected = ( $CONFIG_MEMBER['activ_mbr'] == $key ) ? 'selected="selected"' : '' ;		
		$template->assign_block_vars('select_activ_mbr', array(
			'MODE' => '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>'
		));
	}
	
	include_once('../includes/bbcode.php');
	$template->assign_var_from_handle('BBCODE', 'bbcode');

	
	$template->pparse('admin_members_config'); 
}

include_once('../includes/admin_footer.php');

?>