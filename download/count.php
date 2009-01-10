<?php
/*##################################################
 *                               count.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright            : (C) 2005 Viarre R�gis
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

require_once('../kernel/begin.php');

$idurl = retrieve(GET, 'id', 0);

if (!empty($idurl))
{
	$Sql->query_inject("UPDATE " . PREFIX . "download SET count = count + 1 WHERE id = '" . $idurl . "'", __LINE__, __FILE__); //MAJ du counteur.
	$info_file = $Sql->query_array(PREFIX . "download", "url", "size", "WHERE id = '" . $idurl . "'", __LINE__, __FILE__);
	
	if (empty($info_file['url']))
		$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
	
	//Si c'est une adresse relative
	if (strpos($info_file['url'], '://') === false)	
	{
    	//Redirection vers le fichier demand�
    	$filesize = @filesize(str_replace(HOST . DIR . '/', '../', $info_file['url']));
    	$filesize = ($filesize !== false) ? $filesize : (!empty($info_file) ? number_round($info_file['size']*1048576, 0) : false);
    	if ($filesize !== false)
    		header('Content-Length: ' . $filesize);
    	header('content-type:application/force-download');
    	header('Content-Disposition:attachment;filename="' . substr(strrchr($info_file['url'], '/'), 1) . '"');
    	header('Expires:0');
    	header('Cache-Control:must-revalidate');
    	header('Pragma:public');
    	if (@readfile($info_file['url']) === false)
    		redirect($info_file['url']);
	}
	//Si c'est une adresse absolue, ce n'est pas la peine d'aller chercher les informations du fichier
	else
	{
	    redirect($info_file['url']);
	}
}
else
	$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
?>
