<?php
/*##################################################
 *                               admin_header.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis
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

if( defined('PHP_BOOST') !== true) exit;

if( !defined('TITLE') )
	define('TITLE', $LANG['unknow']);
	
$Session->Session_check(TITLE); //V�rification de la session.

$Template->Set_filenames(array(
	'admin_header' => '../templates/' . $CONFIG['theme'] . '/admin/admin_header.tpl'
));

$Template->Assign_vars(array(
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => TITLE,
	'THEME' => $CONFIG['theme'],
));

$Template->Pparse('admin_header'); // traitement du modele

//!\\ Connexion � l'administration //!\\
require_once('../includes/admin_access.php');

$Template->Set_filenames(array(
	'admin_sub_header' => '../templates/' . $CONFIG['theme'] . '/admin/admin_sub_header.tpl'
));

$Template->Assign_vars(array(
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme'],
	'L_ADMINISTRATION' => $LANG['administration'],
	'L_INDEX' => $LANG['index'],
	'L_SITE' => $LANG['site'],
	'L_INDEX_SITE' => $LANG['site'],
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_DISCONNECT' => $LANG['disconnect'],
	'L_TOOLS' => $LANG['tools'],
	'L_CONFIGURATION' => $LANG['configuration'],
	'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
	'L_ADD' => $LANG['add'],
	'L_MANAGEMENT' => $LANG['management'],
	'L_PUNISHEMENT' => $LANG['punishement'],
	'L_UPDATE_MODULES' => $LANG['update_module'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_DATABASE_QUERY' => $LANG['db_executed_query'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_MEMBER' => $LANG['member_s'],
	'L_EXTEND_FIELD' => $LANG['extend_field'],
	'L_RANKS' => $LANG['ranks'],
	'L_TERMS' => $LANG['terms'],
	'L_GROUP' => $LANG['group'],
	'L_CONTENTS' => $LANG['contents'],
	'L_PAGES' => $LANG['pages'],
	'L_FILES' => $LANG['files'],
	'L_THEME' => $LANG['themes'],
	'L_LANG' => $LANG['languages'],
	'L_SMILEY' => $LANG['smile'],
	'L_STATS' => $LANG['stats'],	
	'L_ERRORS' => $LANG['errors'],
	'L_PHPINFO' => $LANG['phpinfo'],
	'L_COMMENTS' => $LANG['comments'],
	'L_SITE_DATABASE' => $LANG['database'],
	'L_UPDATER' => $LANG['updater'],
	'L_MODULES' => $LANG['modules'],
	'L_CACHE' => $LANG['cache'],
	'L_EXTEND_MENU' => $LANG['extend_menu'],
	'U_INDEX_SITE' => ((substr($CONFIG['start_page'], 0, 1) == '/') ? '..' . $CONFIG['start_page'] : $CONFIG['start_page']) 
));

//Parcours d'une chaine sous la forme d'un simili tableau php. Retourne un tableau correctement construit.
function extract_admin_links($links_format)
{
	$links_format = preg_replace('` ?=> ?`', '=', $links_format);
	$links_format = preg_replace(' ?, ?', ',', $links_format) . ' ';
	list($key, $value, $open, $cursor, $check_value, $admin_links) = array('', '', '', 0, false, array());
	$string_length = strlen($links_format);
	while( $cursor < $string_length ) //Parcours lin�aire.
	{
		$char = substr($links_format, $cursor, 1);
		if( !$check_value ) //On r�cup�re la cl�.
		{
			if( $char != '=' )
				$key .= $char;
			else
				$check_value =  true;
		}
		else //On r�cup�re la valeur associ� � la cl�e, une fois celle-ci r�cup�r�e.
		{
			if( $char == '(' ) //On marque l'ouverture de la parenth�se.
				$open = $key;
			
			if( $char != ',' && $char != '(' && $char != ')' && ($cursor+1) < $string_length ) //Si ce n'est pas un caract�re d�limiteur, on la fin => on concat�ne.
				$value .= $char;
			else
			{
				if( !empty($open) && !empty($value)) //On ins�re dans la cl� marqu� pr�c�demment � l'ouveture de la parenth�se.
					$admin_links[$open][$key] = $value;
				else
					$admin_links[$key] = $value; //Ajout simple.
				list($key, $value, $check_value) = array('', '', false);
			}
			if( $char == ')' )
			{
				$open = ''; //On supprime le marqueur.
				$cursor++; //On avance le curseur pour faire sauter la virugle apr�s la parenth�se.
			}
		}
		$cursor++;
	}
	return $admin_links;
}

//Listing des modules disponibles:
$modules_config = array();
foreach($SECURE_MODULE as $name => $auth)
{
	$modules_config[$name] = load_ini_file('../' . $name . '/lang/', $CONFIG['lang']);
	if( is_array($modules_config[$name]) )
	{	
		if( $modules_config[$name]['admin'] == 1 )
		{
			if( !empty($modules_config[$name]['admin_links']) )
			{	
				$admin_links = extract_admin_links($modules_config[$name]['admin_links']);
				$links = '';
				foreach($admin_links as $key => $value)
				{
					if( is_array($value) )
					{	
						$links .= '<li class="extend" onmouseover="show_menu(\'7' . $name . '\', 2);" onmouseout="hide_menu(2);"><a href="#" style="background-image:url(../' . $name . '/' . $name . '_mini.png);cursor:default;">' . $key . '</a><ul id="sssmenu7' . $name . '">';
						foreach($value as $key2 => $value2)
							$links .= '<li><a href="../' . $name . '/' . $value2 . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key2 . '</a></li>';
						$links .= '</ul></li>';
					}
					else
						$links .= '<li><a href="../' . $name . '/' . $value . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key . '</a></li>';
				}
				
				$Template->Assign_block_vars('modules', array(
					'C_ADVANCED_LINK' => true,
					'C_DEFAULT_LINK' => false,
					'ID' => $name,
					'LINKS' => $links,
					'DM_A_STYLE' => ' style="background-image:url(../' . $name . '/' . $name . '_mini.png);"',
					'NAME' => $modules_config[$name]['name'],
					'U_ADMIN_MODULE' => '../' . $name . '/admin_' . $name . '.php'
				));
			}
			else
			{
				$Template->Assign_block_vars('modules', array(
					'C_DEFAULT_LINK' => true,
					'C_ADVANCED_LINK' => false,
					'DM_A_STYLE' => ' style="background-image:url(../' . $name . '/' . $name . '_mini.png);"',
					'NAME' => $modules_config[$name]['name'],
					'U_ADMIN_MODULE' => '../' . $name . '/admin_' . $name . '.php'
				));
			}
		}
	}	
}

$Template->Pparse('admin_sub_header'); 

?>