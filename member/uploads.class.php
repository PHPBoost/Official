<?php
/*##################################################
 *                             uploads.class.php
 *                            -------------------
 *   begin                : April 18, 2007
 *   copyright          : (C) 2007 Viarre R�gis
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('EMPTY_FOLDER', true);
define('ADMIN_NO_CHECK', true);

class Uploads
{
	## Public Attributes ##
	var $error = ''; //Gestion des erreurs
	
	
	## Public Methods ##	
	//Ajout d'un dossier virtuel
	function Add_folder($id_parent, $user_id, $name)
	{
		global $Sql;
		
		$check_folder = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE name = '" . $name . "' AND id_parent = '" . $id_parent . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if (!empty($check_folder) || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)))
			return 0;
			
		$Sql->query_inject("INSERT INTO ".PREFIX."upload_cat (id_parent, user_id, name) VALUES ('" . $id_parent . "', '" . $user_id . "', '" . $name . "')", __LINE__, __FILE__);
	
		return $Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."upload_cat");
	}	
	
	//Suppression recursive du dossier et de son contenu.
	function Del_folder($id_folder, $empty_folder = false)
	{
		global $Sql;
		static $i = 0;
		
		//Suppression des fichiers.
		$result = $Sql->query_while("SELECT path
		FROM ".PREFIX."upload 
		WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			delete_file(PATH_TO_ROOT . '/upload/' . $row['path']);
		
		//Suppression des entr�es dans la base de donn�es
		if ($empty_folder && $i == 0) //Non suppression du dossier racine.
			$i++;
		else
			$Sql->query_inject("DELETE FROM ".PREFIX."upload_cat WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			
		$Sql->query_inject("DELETE FROM ".PREFIX."upload WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);			
		$result = $Sql->query_while("SELECT id 
		FROM ".PREFIX."upload_cat 
		WHERE id_parent = '" . $id_folder . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (!empty($row['id']))
				$this->del_folder($row['id'], false);
		}
	}
	
	//Suppression d'un fichier
	function Del_file($id_file, $user_id, $admin = false)
	{	
		global $Sql;
		
		if ($admin) //Administration, on ne v�rifie pas l'appartenance.
		{
			$name = $Sql->query("SELECT path FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			delete_file(PATH_TO_ROOT . '/upload/' . $name);
			return '';
		}		
		else
		{
			$check_id_auth = $Sql->query("SELECT user_id FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			//Suppression d'un fichier.
			if ($check_id_auth == $user_id)
			{
				$name = $Sql->query("SELECT path FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				delete_file(PATH_TO_ROOT . '/upload/' . $name);
				return '';
			}
			return 'e_auth';
		}
	}
	
	//Renomme un dossier virtuel
	function Rename_folder($id_folder, $name, $previous_name, $user_id, $admin = false)
	{
		global $Sql;
		
		//V�rification de l'unicit� du nom du dossier.
		$info_folder = $Sql->query_array("upload_cat", "id_parent", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		$check_folder = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE id_parent = '" . $info_folder['id_parent'] . "' AND name = '" . $name . "' AND id <> '" . $id_folder . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_folder > 0 || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)))
			return '';
		
		if ($admin) //Administration, on ne v�rifie pas l'appartenance.
		{
			$Sql->query_inject("UPDATE ".PREFIX."upload_cat SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_folder['user_id'])
			{
				$Sql->query_inject("UPDATE ".PREFIX."upload_cat SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
	
	//Renomme un fichier virtuel
	function Rename_file($id_file, $name, $previous_name, $user_id, $admin = false)
	{
		global $Sql;
		
		//V�rification de l'unicit� du nom du fichier.
		$info_cat = $Sql->query_array("upload", "idcat", "user_id", "WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
		$check_file = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."upload WHERE idcat = '" . $info_cat['idcat'] . "' AND name = '" . $name . "' AND id <> '" . $id_file . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_file > 0 || preg_match('`/|\\\|"|<|>|\||\?`', stripslashes($name)))
			return '/';
			
		if ($admin) //Administration, on ne v�rifie pas l'appartenance.
		{
			$Sql->query_inject("UPDATE ".PREFIX."upload SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_cat['user_id'])
			{
				$Sql->query_inject("UPDATE ".PREFIX."upload SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
		
	//D�placement dun dossier.
	function Move_folder($move, $to, $user_id, $admin = false)
	{		
		global $Sql;
		
		if ($admin) //Administration, on ne v�rifie pas l'appartenance.
		{
			//Changement de propri�taire du fichier.
			$change_user_id = $Sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if ($to == 0) //D�placement dossier racine du membre.
			{	
				$get_mbr_folder = $Sql->query("SELECT id FROM ".PREFIX."upload_cat WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
				$Sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			
			//V�rification de l'appartenance du dossier de destination.
			$check_user_id_move = $Sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $Sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				$Sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//D�placement dun fichier.
	function Move_file($move, $to, $user_id, $admin = false)
	{
		global $Sql;
		
		if ($admin) //Administration, on ne v�rifie pas l'appartenance.
		{
			//Changement de propri�taire du fichier.
			$change_user_id = $Sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);	
			$Sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if ($to == 0) //D�placement dossier racine du membre.
			{	
				$get_mbr_folder = $Sql->query("SELECT id FROM ".PREFIX."upload_cat WHERE user_id = '" . $user_id . "' AND id_parent = 0", __LINE__, __FILE__);	
				$Sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}	

			//V�rification de l'appartenance du dossier de destination.
			$check_user_id_move = $Sql->query("SELECT user_id FROM ".PREFIX."upload WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $Sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				$Sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//Fonction qui d�termine toutes les sous-cat�gories d'une cat�gorie (r�cursive)
	function Find_subfolder($array_folders, $id_cat, &$array_child_folder)
	{
		//On parcourt les cat�gories et on d�terminer les cat�gories filles
		foreach ($array_folders as $key => $value)
		{
			if ($value == $id_cat)
			{
				$array_child_folder[] = $key;
				//On rappelle la fonction pour la cat�gorie fille
				$this->Find_subfolder($array_folders, $key, $array_child_folder);
			}
		}
	}
	
	//R�cup�ration du r�pertoire courant (administration).
	function get_admin_url($id_folder, $pwd, $member_link = '')
	{		
		global $LANG, $Sql;
		
		$parent_folder = $Sql->query_array("upload_cat", "id_parent", "name", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		if (!empty($parent_folder['id_parent']))
		{	
			$pwd .= $this->get_admin_url($parent_folder['id_parent'], $pwd, $member_link);	
			return $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return ($parent_folder['user_id'] == '-1') ? $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>' : $pwd . '/' . $member_link . '<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
	}
	
	//R�cup�ration du r�pertoire courant.
	function get_url($id_folder, $pwd, $popup)
	{		
		global $LANG, $Sql;
		
		$parent_folder = $Sql->query_array("upload_cat", "id_parent", "name", "WHERE id = '" . $id_folder . "' AND user_id <> -1", __LINE__, __FILE__);
		if (!empty($parent_folder['id_parent']))
		{	
			$pwd .= $this->get_url($parent_folder['id_parent'], $pwd, $popup);	
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
	}
	
	//R�cup�ration de la taille totale utilis�e par un membre.
	function Member_memory_used($user_id)
	{
		global $Sql;
		
		return $Sql->query("SELECT SUM(size) FROM ".PREFIX."upload WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	}

	//Conversion mimetype -> image.
	function get_img_mimetype($type)
	{
		global $LANG;
		
		$filetype = sprintf($LANG['file_type'], strtoupper($type));
		switch($type)
		{
			//Images
			case 'jpg':			
			case 'png':
			case 'gif':
			case 'bmp':
			case 'svg':
			$img = $type . '.png';
			$filetype = sprintf($LANG['image_type'], strtoupper($type));
			break;
			//Archives
			case 'rar':
			case 'gz':
			case 'zip':
			$img = 'zip.png';
			$filetype = sprintf($LANG['zip_type'], strtoupper($type));
			break;
			//Pdf
			case 'pdf':
			$img = 'pdf.png';
			$filetype = $LANG['adobe_pdf'];
			break;
			//Son
			case 'wav':
			case 'mp3':
			$img = 'audio.png';
			$filetype = sprintf($LANG['audio_type'], strtoupper($type));
			break;
			//Sripts
			case 'html':
			$img = 'html.png';
			break;
			case 'js':
			case 'php':
			$img = 'script.png';
			break;
			//Vid�os
			case 'wmv':
			case 'avi':
			$img = 'video.png';
			break;
			//Executables
			case 'exe':
			$img = 'exec.png';
			break;
			default:
			$img = 'text.png';
			$filetype = sprintf($LANG['document_type'], strtoupper($type));
		}	
		
		return array('img' => $img, 'filetype' => $filetype);
	}	
	
	
	## Private Attributes ##
	var $base_directory; //R�pertoire de destination des fichiers.
	var $extension = array(); //Extension des fichiers.
	var $filename = array(); //Nom des fichiers.
}

?>