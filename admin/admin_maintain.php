<?php
/*##################################################
 *                               admin_maintain.php
 *                            -------------------
 *   begin                : Februar 07, 2007
 *   copyright          : (C) 2007 Viarre R�gis
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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

//Si c'est confirm� on execute
if( !empty($_POST['valid']) )
{
	
	$maintain_check = isset($_POST['maintain_check']) ? numeric($_POST['maintain_check']) : 0;
	switch($maintain_check) 
	{
		case 1:
			$maintain = isset($_POST['maintain']) ? numeric($_POST['maintain']) : 0; //D�sactiv� par d�faut.
			if( $maintain != -1 )
				$maintain = !empty($maintain) ? time() + $maintain : '0';	
		break;
		case 2:
			$maintain = isset($_POST['end']) ? trim($_POST['end']) : 0;
			$maintain = strtotimestamp($maintain, $LANG['date_format_short']);
		break;
		default:
		$maintain = '0';
	}

	$CONFIG['maintain_text'] = !empty($_POST['contents']) ? stripslashes(parse($_POST['contents'])) : '';
	$CONFIG['maintain_delay'] = isset($_POST['display_delay']) ? numeric($_POST['display_delay']) : 0;
	$CONFIG['maintain_display_admin'] = isset($_POST['maintain_display_admin']) ? numeric($_POST['maintain_display_admin']) : 0;
	$CONFIG['maintain'] = $maintain;
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### R�g�n�ration du cache $CONFIG #######
	$Cache->Generate_file('config');
	
	redirect(HOST . SCRIPT);
}
else //Sinon on rempli le formulaire	 
{		
	$Template->Set_filenames(array(
		'admin_maintain' => '../templates/' . $CONFIG['theme'] . '/admin/admin_maintain.tpl'
	));
	
	$CONFIG['maintain_delay'] = isset($CONFIG['maintain_delay']) ? $CONFIG['maintain_delay'] : 1;
	$CONFIG['maintain_display_admin'] = isset($CONFIG['maintain_display_admin']) ? $CONFIG['maintain_display_admin'] : 1;

	$check_until = ($CONFIG['maintain'] > (time() + 86400));
	$Template->Assign_vars(array(
		'MAINTAIN_CONTENTS' => !empty($CONFIG['maintain_text']) ? unparse($CONFIG['maintain_text']) : '',
		'DISPLAY_DELAY_ENABLED' => ($CONFIG['maintain_delay'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_DELAY_DISABLED' => ($CONFIG['maintain_delay'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_ENABLED' => ($CONFIG['maintain_display_admin'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_DISABLED' => ($CONFIG['maintain_display_admin'] == 0) ? 'checked="checked"' : '',
		'MAINTAIN_CHECK_NO' => ($CONFIG['maintain'] <= time()) ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_DELAY' => ($CONFIG['maintain'] > time() && $CONFIG['maintain'] <= (time() + 86400)) ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_UNTIL' => $check_until ? ' checked="checked"' : '',
		'DATE_UNTIL' => $check_until ? gmdate_format('date_format_short', $CONFIG['maintain']) : '',
		'L_MAINTAIN' => $LANG['maintain'],
		'L_UNTIL' => $LANG['until'],
		'L_DURING' => $LANG['during'],
		'L_SET_MAINTAIN' => $LANG['maintain_for'],
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
		'L_MAINTAIN_DISPLAY_ADMIN' => $LANG['maintain_display_admin'],
		'L_MAINTAIN_TEXT' => $LANG['maintain_text'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']		
	));
		
	//Dur�e de la maintenance.
	$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 14400, 21600, 28800, 57600); 
	$array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '4 ' . $LANG['hours'], '6 ' . $LANG['hours'], '8 ' . $LANG['hours'], '16 ' . $LANG['hours']); 
	
	$CONFIG['maintain'] = isset($CONFIG['maintain']) ? $CONFIG['maintain'] : -1;
	if( $CONFIG['maintain'] != -1 )
	{
		$key_delay = 0;
		$current_time = time();
		for($i = 11; $i >= 0; $i--)
		{					
			$delay = ($CONFIG['maintain'] - $current_time) - $array_time[$i];		
			if( $delay >= $array_time[$i] ) 
			{	
				$key_delay = $i;
				break;
			}
		}
	}
	else
		$key_delay = -1;

	foreach($array_time as $key => $time)
	{
		$selected = (($key_delay + 1) == $key) ? 'selected="selected"' : '' ;
		$Template->Assign_block_vars('select_maintain', array(
			'DELAY' => '<option value="' . $time . '" ' . $selected . '>' . $array_delay[$key] . '</option>'
		));
	}
	
	include_once('../includes/bbcode.php');
	
	$Template->Pparse('admin_maintain');
}

require_once('../includes/admin_footer.php');

?>