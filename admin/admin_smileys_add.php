<?php
/*##################################################
 *                               admin_smileys_add.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *   Admin_theme_ajout, v 2.0.1 
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
###################################################*/

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$error = !empty($_GET['error']) ? trim($_GET['error']) : '';

//Si c'est confirm� on execute
if( !empty($_POST['add']) )
{
	$code_smiley = !empty($_POST['code_smiley']) ? securit($_POST['code_smiley']) : '';
	$url_smiley = !empty($_POST['url_smiley']) ? securit($_POST['url_smiley']) : '';
	
	if( !empty($code_smiley) && !empty($url_smiley) )
	{
		$check_smiley = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."smileys WHERE code_smiley = '" . $code_smiley . "'", __LINE__, __FILE__);
		if( empty($check_smiley) )
		{
			$Sql->Query_inject("INSERT INTO ".PREFIX."smileys (code_smiley,url_smiley) VALUES('" . $code_smiley . "','" . $url_smiley . "')", __LINE__, __FILE__);
		
			###### R�g�n�ration du cache des smileys #######	
			$Cache->Generate_file('smileys');	
		
			redirect(HOST . DIR . '/admin/admin_smileys.php');
		}
		else
			redirect(HOST . DIR . '/admin/admin_smileys_add.php?error=e_smiley_already_exist#errorh');
	}
	else
		redirect(HOST . DIR . '/admin/admin_smileys_add.php?error=incomplete#errorh');
}
elseif( !empty($_FILES['upload_smiley']['name']) ) //Upload et d�compression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en �criture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../images/smileys/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en �criture, upload possible
	{
		include_once('../includes/upload.class.php');
		$Upload = new Upload($dir);
		if( !$Upload->Upload_file('upload_smiley', '`([a-z0-9_-])+\.(jpg|gif|png|bmp)+`i') )
			$error = $Upload->error;
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	redirect(HOST . SCRIPT . $error);	
}
else
{
	$Template->Set_filenames(array(
		'admin_smileys_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_smileys_add.tpl'
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_smiley_already_exist');
	if( in_array($get_error, $array_error) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//On recup�re les dossier des th�mes contenu dans le dossier images/smiley.
	$smiley_options = '';
	$rep = '../images/smileys';
	$y = 0;
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir($rep);
		while( !is_bool($file = readdir($dh)) )
		{	
			if( $file != '.' && $file != '..' && $file != 'index.php' && $file != 'Thumbs.db' )
				$file_array[] = $file; //On cr�e un array, avec les different fichiers.
		}	
		closedir($dh); //On ferme le dossier

		$result = $Sql->Query_while("SELECT url_smiley
		FROM ".PREFIX."smileys", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On recherche les cl�es correspondante � celles trouv�e dans la bdd.
			$key = array_search($row['url_smiley'], $file_array);
			if( $key !== false)
				unset($file_array[$key]); //On supprime ces cl�es du tableau.
		}
		$Sql->Close($result);
		
		foreach($file_array as $smiley)
		{
			if( $y == 0)
			{
				$smiley_options .= '<option value="" selected="selected">--</option>';
				$y++;
			}
			else
				$smiley_options .= '<option value="' . $smiley . '">' . $smiley . '</option>';
		}
	}	
	
	$Template->Assign_vars(array(
		'SMILEY_OPTIONS' => $smiley_options,
		'L_REQUIRE_CODE' => $LANG['require_code'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_REQUIRE' => $LANG['require'],
		'L_SMILEY_MANAGEMENT' => $LANG['smiley_management'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_UPLOAD_SMILEY' => $LANG['upload_smiley'],
		'L_EXPLAIN_UPLOAD_IMG' => $LANG['explain_upload_img'],
		'L_UPLOAD' => $LANG['upload'],
		'L_SMILEY_CODE' => $LANG['smiley_code'],
		'L_SMILEY_AVAILABLE' => $LANG['smiley_available'],
		'L_ADD' => $LANG['add'],
		'L_RESET' => $LANG['reset'],
	));
		
	$Template->Pparse('admin_smileys_add'); 
}

require_once('../includes/admin_footer.php');

?>