<?php
/*##################################################
 *                               download.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright          : (C) 2005 Viarre R�gis
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
require_once('../download/download_begin.php');
require_once('../kernel/header.php');

if( $file_id > 0 ) //Contenu
{
	$Template->Set_filenames(array('download'=> 'download/download.tpl'));
	
	if( $download_info['size'] > 1 )
		$size_tpl = $download_info['size'] . ' ' . $LANG['unit_megabytes'];
	elseif( $download_info['size'] > 0 )
		$size_tpl = ($download_info['size'] * 1024) . ' ' . $LANG['unit_kilobytes'];
	else
		$size_tpl = $DOWNLOAD_LANG['unknown_size'];
	
	include('../kernel/framework/util/date.class.php');
 	$creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $download_info['timestamp']);
 	$release_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $download_info['release_timestamp']);
	
	//Affichage notation.
	include_once('../kernel/framework/note.class.php'); 
	$Note = new Note('download', $file_id, transid('download.php?id=' . $file_id, 'category-' . $category_id . '-' . $file_id . '.php'), $CONFIG_DOWNLOAD['note_max'], '', NOTE_NODISPLAY_NBRNOTES);
	
	$Template->Assign_vars(array(
		'C_DISPLAY_DOWNLOAD' => true,
		'C_IMG' => !empty($download_info['image']),
		'C_EDIT_AUTH' => $auth_write,
		'MODULE_DATA_PATH' => $Template->Module_data_path('download'),
		'ID_FILE' => $file_id,
		'NAME' => $download_info['title'],
		'CONTENTS' => second_parse($download_info['contents']),
		'CREATION_DATE' => $creation_date->Format_date(DATE_FORMAT_SHORT),
		'RELEASE_DATE' => $release_date->Get_timestamp() > 0 ? $release_date->Format_date(DATE_FORMAT_SHORT) : $DOWNLOAD_LANG['unknown_date'],
		'SIZE' => $size_tpl,
		'COUNT' => $download_info['count'],
		'THEME' => $CONFIG['theme'],
		'COM' => com_display_link($download_info['nbr_com'], '../download/download' . transid('.php?id=' . $file_id . '&amp;com=0', '-' . $category_id . '-' . $file_id . '.php?com=0'), $file_id, 'download'),
		'KERNEL_NOTATION' => $Note->display_form(),
		'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$download_info['count']),
		'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], (int)$download_info['nbrnote']),
		'U_IMG' => $download_info['image'],
		'IMAGE_ALT' => str_replace('"', '\"', $download_info['title']),
		'LANG' => $CONFIG['lang'],
		'U_COM' => com_display_link($download_info['nbr_com'], '../download/download' . transid('.php?id=' . $file_id . '&amp;com=0', '-' . $file_id . '+' . url_encode_rewrite($download_info['title']) . '.php?com=0'), $file_id, 'download'),
		'L_DATE' => $LANG['date'],
		'L_SIZE' => $LANG['size'],
		'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
		'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
		'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
		'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
		'L_RELEASE_DATE' => $DOWNLOAD_LANG['last_update_date'],
		'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
		'L_EDIT_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['edit_file']),
		'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $DOWNLOAD_LANG['confirm_delete_file']),
		'L_DELETE_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['delete_file']),
		'U_EDIT_FILE' => transid('management.php?edit=' . $file_id),
		'U_DELETE_FILE' => transid('management.php?del=' . $file_id),
		'U_DOWNLOAD_FILE' => transid('count.php?id=' . $file_id, 'file-' . $file_id . '+' . url_encode_rewrite($download_info['title']) . '.php')
	));
	
	//Affichage commentaires.
	if( isset($_GET['com']) )
	{
		$Template->Assign_vars(array(
			'COMMENTS' => display_comments('download', $file_id, transid('download.php?id=' . $file_id . '&amp;com=%s', 'download-' . $file_id . '.php?com=%s'))
		));
	}
	
	$Template->Pparse('download');
}
else
{
	$Template->Set_filenames(array('download'=> 'download/download.tpl'));
	
	$Template->Assign_vars(array(
		'C_ADMIN' => $auth_write,
		'U_ADMIN_CAT' => $category_id > 0 ? transid('admin_download_cat.php?edit=' . $category_id) : transid('admin_download_cat.php'),
		'C_DOWNLOAD_CAT' => true,
		'TITLE' => sprintf($DOWNLOAD_LANG['title_download'] . ($category_id > 0 ? ' - ' . $DOWNLOAD_CATS[$category_id]['name'] : '')),
		'C_DESCRIPTION' => !empty($DOWNLOAD_CATS[$category_id]['contents']) || ($category_id == 0 && !empty($CONFIG_DOWNLOAD['root_contents'])),
		'DESCRIPTION' => $category_id > 0 ? second_parse($DOWNLOAD_CATS[$category_id]['contents']) : second_parse($CONFIG_DOWNLOAD['root_contents']),
		'C_ADD_FILE' => $auth_write,
		'U_ADD_FILE' => transid('management.php?new=1&idcat=' . $category_id),
		'L_ADD_FILE' => $DOWNLOAD_LANG['add_file']
	));
	
	//let's check if there are some subcategories
	$num_subcats = 0;
	foreach( $DOWNLOAD_CATS as $id => $value )
	{
		if( $id != 0 && $value['id_parent'] == $category_id )
			$num_subcats ++;
	}

	//listing of subcategories
	if( $num_subcats > 0 )
	{
		$Template->Assign_vars(array(
			'C_SUB_CATS' => true
		));	
		
		$i = 1;
		
		foreach( $DOWNLOAD_CATS as $id => $value )
		{
			//List of children categories
			if( $id != 0 && $value['visible'] && $value['id_parent'] == $category_id && (empty($value['auth']) || $Member->Check_auth($value['auth'], READ_CAT_DOWNLOAD)) )
			{
				if ( $i % $CONFIG_DOWNLOAD['nbr_column'] == 1 )
					$Template->Assign_block_vars('row', array());
				$Template->Assign_block_vars('row.list_cats', array(
					'ID' => $id,
					'NAME' => $value['name'],
					'WIDTH' => floor(100 / (float)$CONFIG_DOWNLOAD['nbr_column']),
					'SRC' => $value['icon'],
					'IMG_NAME' => addslashes($value['name']),
					'NUM_FILES' => sprintf(((int)$value['num_files'] > 1 ? $DOWNLOAD_LANG['num_files_plural'] : $DOWNLOAD_LANG['num_files_singular']), (int)$value['num_files']),
					'U_CAT' => transid('download.php?cat=' . $id, 'category-' . $id . '+' . url_encode_rewrite($value['name']) . '.php'),
					'U_ADMIN_CAT' => transid('admin_download_cat.php?edit=' . $id),
					'C_CAT_IMG' => !empty($value['icon'])
				));
					
				$i++;
			}
		}
	}
	
	//Contenu de la cat�gorie	
	$nbr_files = (int)$Sql->Query("SELECT COUNT(*) FROM ".PREFIX."download WHERE visible = 1 AND idcat = '" . $category_id . "'", __LINE__, __FILE__);
	
	if( $nbr_files > 0 )
	{
		$get_sort = retrieve(GET, 'sort', '');	
		$get_mode = retrieve(GET, 'mode', '');
		$selected_fields = array(
			'alpha' => '',
			'size' => '',
			'date' => '',
			'hits' => '',
			'note' => '',
			'asc' => '',
			'desc' => ''
			);
		
		switch($get_sort)
		{
			case 'alpha' : 
			$sort = 'title';
			$selected_fields['alpha'] = ' selected="selected"';
			break;	
			case 'size' : 
			$sort = 'size';
			$selected_fields['size'] = ' selected="selected"';
			break;			
			case 'date' : 
			$sort = 'timestamp';
			$selected_fields['date'] = ' selected="selected"';
			break;		
			case 'hits' : 
			$sort = 'count';
			$selected_fields['hits'] = ' selected="selected"';
			break;		
			case 'note' :
			$sort = 'note';
			$selected_fields['note'] = ' selected="selected"';
			break;
			default :
			$sort = 'timestamp';
			$selected_fields['date'] = ' selected="selected"';
		}
		
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		if( $mode == 'ASC' )
			$selected_fields['asc'] = ' selected="selected"';
		else
			$selected_fields['desc'] = ' selected="selected"';
		
		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
		
		$Template->Assign_vars(array(
			'L_FILE' => $DOWNLOAD_LANG['file'],
			'L_ALPHA' => $DOWNLOAD_LANG['sort_alpha'],
			'L_SIZE' => $LANG['size'],
			'L_DATE' => $LANG['date'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_POPULARITY' => $DOWNLOAD_LANG['popularity'],
			'L_DESC' => $LANG['desc'],
			'L_ASC' => $LANG['asc'],
			'L_NOTE' => $LANG['note'],
			'L_ORDER_BY' => $DOWNLOAD_LANG['order_by'],
			'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $DOWNLOAD_LANG['confirm_delete_file']),
			'SELECTED_ALPHA' => $selected_fields['alpha'],
			'SELECTED_SIZE' => $selected_fields['size'],
			'SELECTED_DATE' => $selected_fields['date'],
			'SELECTED_HITS' => $selected_fields['hits'],
			'SELECTED_NOTE' => $selected_fields['note'],
			'SELECTED_ASC' => $selected_fields['asc'],
			'SELECTED_DESC' => $selected_fields['desc']
		));
			
		//On cr�e une pagination si le nombre de fichiers est trop important.
		include_once('../kernel/framework/pagination.class.php'); 
		$Pagination = new Pagination();
		
		//Notes
		include_once('../kernel/framework/note.class.php');
		$Note = new Note(null, null, null, null, '', NOTE_NO_CONSTRUCT);
		
		$Template->Assign_vars(array(
			'PAGINATION' => $Pagination->Display_pagination(transid('download.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $category_id . '&amp;p=%d', 'category-' . $category_id . '-%d.php' . $unget), $nbr_files, 'p', $CONFIG_DOWNLOAD['nbr_file_max'], 3),
			'C_FILES' => true,
			'TARGET_ON_CHANGE_ORDER' => $CONFIG['rewrite'] ? 'category-' . $category_id . '.php?' : 'download.php?cat=' . $category_id . '&'
			));

		$result = $Sql->Query_while("SELECT id, title, timestamp, size, count, note, nbrnote, nbr_com, image, short_contents
		FROM ".PREFIX."download
		WHERE visible = 1 AND idcat = '" . $category_id . "'
		ORDER BY " . $sort . " " . $mode . 
		$Sql->Sql_limit($Pagination->First_msg($CONFIG_DOWNLOAD['nbr_file_max'], 'p'), $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('file', array(			
				'NAME' => $row['title'],
				'IMG_NAME' => str_replace('"', '\"', $row['title']),
				'C_DESCRIPTION' => !empty($row['short_contents']),
				'DESCRIPTION' => second_parse($row['short_contents']),
				'DATE' => sprintf($DOWNLOAD_LANG['add_on_date'], gmdate_format('date_format_short', $row['timestamp'])),
				'COUNT_DL' => sprintf($DOWNLOAD_LANG['downloaded_n_times'], $row['count']),
				'NOTE' => $row['nbrnote'] > 0 ? $Note->display_img((int)$row['note'], $CONFIG_DOWNLOAD['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
				'SIZE' => ($row['size'] >= 1) ? number_round($row['size'], 1) . ' ' . $LANG['unit_megabytes'] : (number_round($row['size'], 1) * 1024) . ' ' . $LANG['unit_kilobytes'],
				'COMS' => (int)$row['nbr_com'] > 1 ? sprintf($DOWNLOAD_LANG['num_coms'], $row['nbr_com']) : sprintf($DOWNLOAD_LANG['num_com'], $row['nbr_com']),
				'C_IMG' => !empty($row['image']),
				'IMG' => $row['image'],
				'U_DOWNLOAD_LINK' => transid('download.php?id=' . $row['id'], 'download-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
				'U_ADMIN_EDIT_FILE' => transid('management.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_FILE' => transid('management.php?del=' . $row['id'])
			));
		}
		$Sql->Close($result);
	}
	else
	{
		$Template->Assign_vars(array(
			'L_NO_FILE_THIS_CATEGORY' => $DOWNLOAD_LANG['none_download'],
			'C_NO_FILE' => true
		));
	}
		
	$Template->Pparse('download');
}
	
require_once('../kernel/footer.php'); 

?>
