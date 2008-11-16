<?php
/*##################################################
 *                               admin_begin.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *   Admin, v 1.0.0 
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
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Template->set_filenames(array(
	'admin_extend'=> 'admin/admin_extend.tpl'
));

$Template->assign_vars(array(
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'L_CONFIGURATION' => $LANG['configuration'],
	'L_INDEX_SITE' => $LANG['site'],
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_MEMBER' => $LANG['member'],
	'L_EXTEND_FIELD' => $LANG['extend_field'],
	'L_RANKS' => $LANG['ranks'],
	'L_GROUP' => $LANG['group'],
	'L_THEME' => $LANG['themes'],
	'L_SMILEY' => $LANG['smile'],
	'L_ROBOTS' => $LANG['robots'],	
	'L_ERRORS' => $LANG['errors'],
	'L_COM' => $LANG['com'],
	'L_SITE_DATABASE' => $LANG['database'],
	'L_UPDATER' => $LANG['updater'],
	'L_MODULES' => $LANG['modules'],
	'L_CACHE' => $LANG['cache'],
	'U_INDEX_SITE' => ((substr($CONFIG['start_page'], 0, 1) == '/') ? '..' . $CONFIG['start_page'] : $CONFIG['start_page']) ,
    'L_WEBSITE_UPDATES' => $LANG['website_updates']
));


//Listing des modules disponibles:
$i = 1;
$result = $Sql->query_while("SELECT name 
FROM ".PREFIX."modules
WHERE activ = 1", __LINE__, __FILE__);
$nbr_modules = $Sql->num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."modules WHERE activ = 1 AND admin = 1");
while( $row = $Sql->fetch_assoc($result) )
{
	$config = load_ini_file('../' . $row['name'] . '/lang/', get_ulang());
	if( is_array($config) )
	{	
		if( $config['admin'] == 1 )
		{
			$Template->assign_block_vars('modules_extend', array(
				'NAME' => $config['name'],
				'IMG' => '../' . $row['name'] . '/' . $row['name'] . '.png',
				'START_TR' => ((($i - 1) % 5) == 0 || $i == 1)? '<tr style="text-align:center;">' : '',
				'END_TR' => ((($i % 5) == 0 && $i != 1) || $i == $nbr_modules ) ? '</tr>' : '',			
				'U_ADMIN_MODULE' => '../' . $row['name'] . '/admin_' . $row['name'] . '.php'
			));			
			$i++;
		}
	}
}
$Sql->query_close($result);

//Compl�tion �ventuelle des cases du tableaux.
if( $i != 0 )
{
	$i--;
	while( ($i % 5) != 0 )
	{
		$Template->assign_block_vars('modules_extend.td', array(
			'TD' => '<td class="row2" style="width:20%;">&nbsp;</td>'
		));	
		$i++;
	}
}

$Template->pparse('admin_extend'); 

require_once('../admin/admin_footer.php');

?>