<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : August 30, 2007
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php');
include_once('../gallery/gallery_begin.php');
require_once('../includes/header_no_display.php');

//Notation des images.
if( !empty($_GET['note_pics']) && $Member->Check_level(MEMBER_LEVEL) ) //Utilisateur connect�.
{	
	//Initialisation  de la class de gestion des fichiers.
	include_once('../gallery/gallery.class.php');
	$Gallery = new Gallery;
	
	$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
	$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	if( empty($idcat) )
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
	
	if( !isset($CAT_GALLERY[$idcat]) )
		echo 0;
	
	//Autorisation en lecture, notation activ�e, et note comprise dans l'intervalle autoris�.
	if( !empty($id_file) && $note >= 0 && $note <= $CONFIG_GALLERY['note_max'] && $Member->Check_auth($CAT_GALLERY[$idcat]['auth'], READ_CAT_GALLERY) && $CONFIG_GALLERY['activ_note'] == 1 )
		echo $Gallery->Note_pics($id_file, $note, $Member->Get_attribute('user_id'));
	else 
		echo 0;
}
	
if( $Member->Check_level(MODO_LEVEL) ) //Modo
{	
	if( !empty($_GET['rename_pics']) ) //Renomme une image.
	{
		//Initialisation  de la class de gestion des fichiers.
		include_once('../gallery/gallery.class.php');
		$Gallery = new Gallery;
		
		$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
		$name = !empty($_POST['name']) ? securit(utf8_decode($_POST['name'])) : '';
		$previous_name = !empty($_POST['previous_name']) ? securit(utf8_decode($_POST['previous_name'])) : '';
		
		if( !empty($id_file) )
			echo $Gallery->Rename_pics($id_file, $name, $previous_name);
		else 
			echo -1;
	}
	elseif( !empty($_GET['aprob_pics']) )
	{
		//Initialisation  de la class de gestion des fichiers.
		include_once('../gallery/gallery.class.php');
		$Gallery = new Gallery;
		
		$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
		if( !empty($id_file) )
		{
			echo $Gallery->Aprob_pics($id_file);
			//R�g�n�ration du cache des photos al�atoires.
			$Cache->Generate_module_file('gallery');
		}
		else 
			echo 0;
	}
}

?>