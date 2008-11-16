<?php
/*##################################################
 *                               admin_menus.php
 *                            -------------------
 *   begin                : March, 05 2007
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);

$top = retrieve(GET, 'top', '');
$bottom = retrieve(GET, 'bot', '');
$move = retrieve(GET, 'move', '');

//Si c'est confirm� on execute
if( !empty($_POST['valid']) )
{
	$result = $Sql->query_while("SELECT id, activ, auth
	FROM ".PREFIX."menus", __LINE__, __FILE__);
	while( $row = $Sql->fetch_assoc($result) )
	{
		$activ = retrieve(POST, $row['id'] . 'activ', 0);
		$auth = retrieve(POST, $row['id'] . 'auth', -1);
		
		$Sql->query_inject("UPDATE ".PREFIX."menus SET activ = '" . $activ . "', auth = '" . $auth . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->query_close($result);
	
	$Cache->Generate_file('menus');
	$Cache->Generate_file('css');
	
	redirect(HOST . SCRIPT);
}
elseif( isset($_GET['activ']) && !empty($id) ) //Gestion de l'activation pour un module donn�.
{
	$previous_location = $Sql->query("SELECT location FROM ".PREFIX."menus WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$max_class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE location = '" . $previous_location . "' AND activ = 1", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = '" . ($max_class + 1) . "', activ = 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('menus');
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);
}
elseif( isset($_GET['unactiv']) && !empty($id) ) //Gestion de l'inactivation pour un module donn�.
{
	$info_menu = $Sql->query_array("menus", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = 0, activ = 0 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//R�ordonnement du classement.
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . $info_menu['location'] . "'", __LINE__, __FILE__);

	$Cache->Generate_file('menus');
	$Cache->Generate_file('css');
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);
}
elseif( !empty($move) && !empty($id) ) //Gestion de la s�curit� pour un module donn�.
{
	$info_menu = $Sql->query_array("menus", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$max_class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."menus WHERE location = '" . $move . "' AND activ = 1", __LINE__, __FILE__);
	
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . $info_menu['location'] . "' AND activ = 1", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE ".PREFIX."menus SET class = '" . ($max_class + 1) . "', location = '" . $move . "', activ = 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('menus');
	$Cache->Generate_file('css');
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);
}
elseif( ($top || $bottom) && !empty($id) ) //Monter/descendre.
{
	if( $top )
	{
		$info_menu = $Sql->query_array("menus", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$top = $Sql->query("SELECT id FROM ".PREFIX."menus WHERE location = '" . $info_menu['location'] . "' AND class = '" . ($info_menu['class'] - 1) . "' AND activ = 1", __LINE__, __FILE__);
		if( !empty($top) )
		{
			$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class + 1 WHERE id = '" . $top . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		$Cache->Generate_file('menus');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
	elseif( $bottom )
	{
		$info_menu = $Sql->query_array("menus", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$bottom = $Sql->query("SELECT id FROM ".PREFIX."menus WHERE location = '" . $info_menu['location'] . "' AND class = '" . ($info_menu['class'] + 1) . "' AND activ = 1", __LINE__, __FILE__);
		if( !empty($bottom) )
		{
			$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class - 1 WHERE id = '" . $bottom . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE ".PREFIX."menus SET class = class + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		
		$Cache->Generate_file('menus');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
}
else
{
	$Template->set_filenames(array(
		'admin_menus_management'=> 'admin/admin_menus_management.tpl'
	));
	
	$Cache->load('themes'); //R�cup�ration de la configuration des th�mes.

	//R�cup�ration du class le plus grand pour chaque positionnement possible.
	$array_max = array();
	$result = $Sql->query_while("SELECT MAX(class) AS max, location
	FROM ".PREFIX."menus
	GROUP BY location
	ORDER BY class", __LINE__, __FILE__);
	
	while( $row = $Sql->fetch_assoc($result) )
		$array_max[$row['location']] = $row['max'];
	
	$Sql->query_close($result);
	
	$i = 0;
	$uncheck_modules = $MODULES; //On r�cup�re tous les modules install�s.
	$installed_menus_perso = array(); //Menu perso dans le dossier /menus
	$installed_menus = array();
	$uninstalled_menus = array();
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $Sql->query_while("SELECT id, class, name, contents, location, activ, auth, added
	FROM ".PREFIX."menus
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->fetch_assoc($result) )
	{
		if( $row['added'] == 2 ) //Menu perso dans le dossier /menus
			$installed_menus_perso[] = $row['name'];
		
		if( $row['added'] == 0 ) //On r�cup�re la liste des modules install�s et non install�s parmis la liste des menus qui y sont ratach�s.
		{
			$config = load_ini_file('../' . $row['name'] . '/lang/', get_ulang());
			if( is_array($config) && !empty($config) )
			{
				unset($uncheck_modules[$row['name']]); //Module v�rifi�!
				$array_menus = parse_ini_array($config['mini_module']);
				foreach($array_menus as $module_path => $location)
				{
					if( strpos($row['contents'], $module_path) !== false ) //Module trouv�.
					{
						$installed_menus[$row['name']][$module_path] = $location;
						if( isset($uninstalled_menus[$row['name']][$module_path]) )
							unset($uninstalled_menus[$row['name']][$module_path]);
					}
					else
					{
						$uninstalled_menus[$row['name']][$module_path] = $location;
						if( isset($installed_menus[$row['name']][$module_path]) )
							unset($installed_menus[$row['name']][$module_path]);
					}
				}
					
				$row['name'] = !empty($config['name']) ? $config['name'] : $row['name'];
			}
		}
		
		$block_position = $row['location'];
		if( ($row['location'] == 'left' || $row['location'] == 'right') && (!$THEME_CONFIG[get_utheme()]['right_column'] && !$THEME_CONFIG[get_utheme()]['left_column']) )
			$block_position = 'main';
		elseif( ($row['location'] == 'left' || (!$THEME_CONFIG[get_utheme()]['right_column'] && $row['location'] == 'right')) && $THEME_CONFIG[get_utheme()]['left_column'] )
			$block_position = 'left'; //Si on atteint le premier ou le dernier id on affiche pas le lien inapropri�.
		elseif( ($row['location'] == 'right' || (!$THEME_CONFIG[get_utheme()]['left_column'] && $row['location'] == 'left')) && $THEME_CONFIG[get_utheme()]['right_column'] )
			$block_position = 'right';
		
		if( $row['activ'] == 1 && !empty($block_position) )
		{
			//Affichage r�duit des diff�rents modules.
			$Template->assign_block_vars('mod_' . $block_position, array(
				'IDMENU' => $row['id'],
				'NAME' => ucfirst($row['name']),
				'EDIT' => '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a>',
				'DEL' => ($row['added'] == 1 || $row['added'] == 2) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onclick="javascript:return Confirm_menu();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'CONTENTS' => ($row['added'] == 1) ? '<br />' . second_parse($row['contents']) : '',
				'UP' => ($row['class'] > 1) ? '<a href="admin_menus.php?top=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/up.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
				'DOWN' => ($array_max[$row['location']] != $row['class']) ? '<a href="admin_menus.php?bot=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/down.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;unactiv=' + this.options[this.selectedIndex].value"
			));
		}
		else //Affichage des menus d�sactiv�s
		{
			$Template->assign_block_vars('mod_main', array(
				'IDMENU' => $row['id'],
				'NAME' => ucfirst($row['name']),
				'EDIT' => '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a>',
				'DEL' => ($row['added'] == 1 || $row['added'] == 2) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onclick="javascript:return Confirm_menu();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'CONTENTS' => ($row['added'] == 1) ? '<br />' . second_parse($row['contents']) : '',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;activ=' + this.options[this.selectedIndex].value"
			));
		}
		$i++;
	}
	$Sql->query_close($result);
	
	//On v�rifie pour les modules qui n'ont pas de menu associ�, qu'ils n'en ont toujours pas.
	foreach($uncheck_modules as $name => $auth)
	{
		$modules_config[$name] = load_ini_file('../' . $name . '/lang/', get_ulang());
		if( !empty($modules_config[$name]['mini_module']) )
		{
			$array_menus = parse_ini_array($modules_config[$name]['mini_module']);
			foreach($array_menus as $module_path => $location)
				$uninstalled_menus[$name][$module_path] = $location; //On ajoute le menu.
		}
	}
	//On liste les menus non install�s.
	foreach($uninstalled_menus as $name => $array_menu)
	{
		$i = 1;
		foreach($array_menu as $path => $location)
		{
			if( file_exists('../' . $name . '/' . $path) ) //Fichier pr�sent.
			{
				$idmodule = $name . '+' . $i++;
				$Template->assign_block_vars('mod_main_uninstalled', array(
					'NAME' => ucfirst($modules_config[$name]['name']),
					'U_INSTALL' => "admin_menus_add.php?idmodule=" . $idmodule . "&amp;install=1"
				));
			}
		}
	}

	//On recup�re les menus dans le dossier /menus
	$rep = '../menus/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir($rep);
		while( !is_bool($file = readdir($dh)) )
		{
			//Si c'est un repertoire, on affiche.
			if( preg_match('`[a-z0-9()_-]\.php`i', $file) && $file != 'index.php' && !in_array(str_replace('.php', '', $file), $installed_menus_perso) )
				$file_array[] = $file; //On cr�e un array, avec les different dossiers.
		}
		closedir($dh); //On ferme le dossier

		foreach($file_array as $name)
		{
			$Template->assign_block_vars('mod_main_uninstalled', array(
				'NAME' => ucfirst(str_replace('.php', '', $name)),
				'U_INSTALL' => "admin_menus_add.php?idmodule=" . $name . "+0&amp;install=1"
			));
		}
	}
	
	$colspan = 1;
	$colspan += (int)$THEME_CONFIG[get_utheme()]['right_column'];
	$colspan += (int)$THEME_CONFIG[get_utheme()]['left_column'];
	
	$Template->assign_vars(array(
		'COLSPAN' => $colspan,
		'LEFT_COLUMN' => $THEME_CONFIG[get_utheme()]['left_column'],
		'RIGHT_COLUMN' => $THEME_CONFIG[get_utheme()]['right_column'],
		'L_INDEX' => $LANG['index'],
		'L_CONFIRM_DEL_MENU' => $LANG['confirm_del_menu'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_MOVETO' => $LANG['moveto'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
		'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
		'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
		'L_HEADER' => $LANG['menu_header'],
		'L_SUB_HEADER' => $LANG['menu_subheader'],
		'L_LEFT_MENU' => $LANG['menu_left'],
		'L_RIGHT_MENU' => $LANG['menu_right'],
		'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
		'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
		'L_TOP_FOOTER' => $LANG['menu_top_footer'],
		'L_FOOTER' => $LANG['menu_footer'],
		'L_MENUS_AVAILABLE' => ($i > 0) ? $LANG['available_menus'] : $LANG['no_available_menus'],
		'L_INSTALL' => $LANG['install'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->pparse('admin_menus_management');
}

require_once('../admin/admin_footer.php');

?>
