<?php
/*##################################################
 *                               admin_files_config.php
 *                            -------------------
 *   begin                : July 09, 2007
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

require_once(PATH_TO_ROOT . '/kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/kernel/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_files = array();
	$config_files['size_limit'] = isset($_POST['size_limit']) ? max(numeric($_POST['size_limit'], 'float') * 1024, 1) : 500;
	$config_files['bandwidth_protect'] = isset($_POST['bandwidth_protect']) ? numeric($_POST['bandwidth_protect']) : 1;
	$auth_extensions = isset($_POST['auth_extensions']) ? $_POST['auth_extensions'] : '';
	$auth_extensions_sup = !empty($_POST['auth_extensions_sup']) ? preg_split('`, ?`', trim($_POST['auth_extensions_sup'])) : '';

	if( is_array($auth_extensions_sup) )
	{	
		foreach($auth_extensions_sup as $extension)
		{
			if( !isset($auth_extensions[$extension]) && $extension != 'php' ) 
				array_push($auth_extensions, $extension);
		}
	}
	$config_files['auth_extensions'] = $auth_extensions;

	//G�n�ration du tableau des droits.
	$array_auth_all = $Group->Return_array_auth(AUTH_FILES);
	$config_files['auth_files'] = serialize($array_auth_all);
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_files)) . "' WHERE name = 'files'", __LINE__, __FILE__);
	
	###### R�g�n�ration du cache dela configuration #######
	$Cache->Generate_file('files');
	
	//R�g�n�ration du htaccess.
	$Cache->Generate_htaccess(); 
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_files_config'=> 'admin/admin_files_config.tpl'
	));
	
	$Cache->Load_file('files');
	
	$CONFIG_FILES['auth_extensions'] = is_array($CONFIG_FILES['auth_extensions']) ? $CONFIG_FILES['auth_extensions'] : array();
	$array_ext_sup = $CONFIG_FILES['auth_extensions'];
	$array_extensions_type = array(
		$LANG['files_image'] => array('jpg', 'bmp', 'gif', 'png', 'tif', 'svg', 'ico'),
		$LANG['files_archives'] => array('rar', 'zip', 'gz'), 
		$LANG['files_text'] => array('txt', 'doc', 'pdf', 'ppt', 'xls', 'odt', 'odp', 'ods', 'odg', 'odc', 'odf', 'odb', 'xcf'),
		$LANG['files_media'] => array('flv', 'mp3', 'ogg', 'mpg', 'mov', 'swf', 'wav', 'wmv', 'midi', 'mng', 'qt'), 
		$LANG['files_prog'] => array('c', 'h', 'cpp', 'java', 'py', 'css', 'html', 'xml'),
		$LANG['files_misc'] => array('ttf', 'tex', 'rtf', 'psd')
	);

	$i = 0;
	$auth_extensions = '';
	foreach($array_extensions_type as $file_type => $array_extensions)
	{
		$auth_extensions .= '<optgroup label="' . $file_type . '">';
		foreach($array_extensions as $key => $extension)
		{
			$extension_key = array_search($extension, $CONFIG_FILES['auth_extensions']);
			$selected = ($extension_key !== false) ? ' selected="selected"' : '';
			$auth_extensions .= '<option value="' . $extension . '" id="ext' . $i . '"' . $selected . '>' . $extension . '</option>';
			if( isset($array_ext_sup[$extension_key]) )
				unset($array_ext_sup[$extension_key]);
			$i++;
		}
		$auth_extensions .= '</optgroup>';
	}
	
	$array_ranks = array(0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']); //Cr�ation du tableau des rangs.	 
	$array_auth = isset($CONFIG_FILES['auth_files']) ? $CONFIG_FILES['auth_files'] : array(); //R�cup�ration des tableaux des autorisations et des groupes.
	
	$Template->Assign_vars(array(
		'NBR_EXTENSIONS' => $i,
		'AUTH_FILES' => $Group->Generate_select_auth(AUTH_FILES, $array_auth, array(2 => true)),
		'SIZE_LIMIT' => isset($CONFIG_FILES['size_limit']) ? number_round($CONFIG_FILES['size_limit']/1024, 2) : '0.5',
		'BANDWIDTH_PROTECT_ENABLED' => $CONFIG_FILES['bandwidth_protect'] == 1 ? 'checked="checked"' : '',
		'BANDWIDTH_PROTECT_DISABLED' => $CONFIG_FILES['bandwidth_protect'] == 0 ? 'checked="checked"' : '',
		'AUTH_EXTENSIONS' => $auth_extensions,
		'AUTH_EXTENSIONS_SUP' => implode(', ', $array_ext_sup),
		'L_MB' => $LANG['unit_megabytes'],
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_CONFIG_FILES' => $LANG['files_config'],
		'L_REQUIRE' => $LANG['require'],	
		'L_AUTH_FILES' => $LANG['auth_files'],
		'L_SIZE_LIMIT' => $LANG['size_limit'],	
		'L_BANDWIDTH_PROTECT' => $LANG['bandwidth_protect'],
		'L_BANDWIDTH_PROTECT_EXPLAIN' => $LANG['bandwidth_protect_explain'],
		'L_AUTH_EXTENSIONS' => $LANG['auth_extensions'],
		'L_EXTEND_EXTENSIONS' => $LANG['extend_extensions'],
		'L_EXTEND_EXTENSIONS_EXPLAIN' => $LANG['extend_extensions_explain'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->Pparse('admin_files_config'); // traitement du modele	
}

require_once(PATH_TO_ROOT . '/kernel/admin_footer.php');

?>