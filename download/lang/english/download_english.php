<?php
/*##################################################
 *                              download_english.php
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


####################################################
#                                                          French                                                                        #
####################################################

global $DOWNLOAD_LANG, $LANG;
$DOWNLOAD_LANG = array();

//Gestion des fichiers
$DOWNLOAD_LANG['files_management'] = 'Files management';
$DOWNLOAD_LANG['file_management'] = 'File edition';
$DOWNLOAD_LANG['file_addition'] = 'File addition';
$DOWNLOAD_LANG['add_file'] = 'Add a file';
$DOWNLOAD_LANG['update_file'] = 'Edit a file';
$DOWNLOAD_LANG['warning_previewing'] = 'Be careful, you are watching your file informations. As you don\'t valid editions, they can\'t be applied.';
$DOWNLOAD_LANG['file_image'] = 'File picture adress';
$DOWNLOAD_LANG['require_description'] = 'Please type a description !';
$DOWNLOAD_LANG['require_url'] = 'Please enter a valid adress for the file !';
$DOWNLOAD_LANG['require_creation_date'] = 'Please enter a creation date in the right format (dd/mm/yy) !';
$DOWNLOAD_LANG['require_release_date'] = 'Please enter the release (or update) date in the right format (dd/mm/yy) !';
$DOWNLOAD_LANG['download_add'] = 'Add a file';
$DOWNLOAD_LANG['download_management'] = 'Download management';
$DOWNLOAD_LANG['download_config'] = 'Download configuration';
$DOWNLOAD_LANG['file_list'] = 'Files list';
$DOWNLOAD_LANG['edit_file'] = 'File edition';
$DOWNLOAD_LANG['nbr_download_max'] = 'Maximum number of file displayed per page';
$DOWNLOAD_LANG['nbr_columns_for_cats'] = 'Columns number in which are presented categories';
$DOWNLOAD_LANG['download_date'] = 'File addition date';
$DOWNLOAD_LANG['release_date'] = 'File release (or uptade) date';
$DOWNLOAD_LANG['ignore_release_date'] = 'Ignore the file release date';
$DOWNLOAD_LANG['file_visibility'] = 'File publication';
$DOWNLOAD_LANG['icon_cat'] = 'Category picture';
$DOWNLOAD_LANG['explain_icon_cat'] = 'You can choose a picture in the download/ folder or put its address in the right field';
$DOWNLOAD_LANG['root_description'] = 'Description of the downloads root';

//Titre
$DOWNLOAD_LANG['title_download'] = 'Downloads';

//DL
$DOWNLOAD_LANG['file'] = 'File';
$DOWNLOAD_LANG['size'] = 'Size';
$DOWNLOAD_LANG['download'] = 'Downloads';
$DOWNLOAD_LANG['none_download'] = 'No file in this category';
$DOWNLOAD_LANG['xml_download_desc'] = 'Last files';
$DOWNLOAD_LANG['no_note'] = 'No note';
$DOWNLOAD_LANG['actual_note'] = 'Current note';
$DOWNLOAD_LANG['vote_action'] = 'Vote';
$DOWNLOAD_LANG['add_on_date'] = 'Added on %s';
$DOWNLOAD_LANG['downloaded_n_times'] = 'Downloaded %d times';
$DOWNLOAD_LANG['num_com'] = '%d comment';
$DOWNLOAD_LANG['num_coms'] = '%d comments';
$DOWNLOAD_LANG['this_note'] = 'Note :';
$DOWNLOAD_LANG['short_contents'] = 'Short descrition';
$DOWNLOAD_LANG['complete_contents'] = 'Complete description';
$DOWNLOAD_LANG['url'] = 'File adress';
$DOWNLOAD_LANG['confirm_delete_file'] = 'Do you really want to delete this file ?';
$DOWNLOAD_LANG['download_file'] = 'Download the file';
$DOWNLOAD_LANG['file_infos'] = 'Informations about this file';
$DOWNLOAD_LANG['insertion_date'] = 'Addition date';
$DOWNLOAD_LANG['last_update_date'] = 'Date of release or last uptade';
$DOWNLOAD_LANG['downloaded'] = 'Downloaded';
$DOWNLOAD_LANG['n_times'] = '%d times';
$DOWNLOAD_LANG['num_notes'] = '%d voter(s)';
$DOWNLOAD_LANG['edit_file'] = 'Edit the file';
$DOWNLOAD_LANG['delete_file'] = 'Delete the file';
$DOWNLOAD_LANG['unknown_size'] = 'Unknown';
$DOWNLOAD_LANG['unknown_date'] = 'Unknown';

//Cat�gories
$DOWNLOAD_LANG['add_category'] = 'Add a category';
$DOWNLOAD_LANG['removing_category'] = 'Deletion of a category';
$DOWNLOAD_LANG['explain_removing_category'] = 'You will delete the category. You have two choices. You can move the whole content (questions and sub-categories) in another category or delete the whole category. <strong>Be careful, this action is irreversible !</strong>';
$DOWNLOAD_LANG['delete_category_and_its_content'] = 'Delete the category and all its contents';
$DOWNLOAD_LANG['move_category_content'] = 'Move its contents in :';
$DOWNLOAD_LANG['required_fields'] = 'The * marked files are required !';
$DOWNLOAD_LANG['category_name'] = 'Category name';
$DOWNLOAD_LANG['category_location'] = 'Category location';
$DOWNLOAD_LANG['cat_description'] = 'Category description';
$DOWNLOAD_LANG['num_files_singular'] = '%d file';
$DOWNLOAD_LANG['num_files_plural'] = '%d files';
$DOWNLOAD_LANG['recount_subfiles'] = 'Recount files number of each category';
$DOWNLOAD_LANG['popularity'] = 'Popularity';
$DOWNLOAD_LANG['sort_alpha'] = 'Alphabetic';
$DOWNLOAD_LANG['order_by'] = 'Order by';

//Autorisations
$DOWNLOAD_LANG['auth_read'] = 'Reading permissions';
$DOWNLOAD_LANG['auth_write'] = 'Writing permissions';
$DOWNLOAD_LANG['special_auth'] = 'Special permissions';
$DOWNLOAD_LANG['special_auth_explain'] = 'The category will have the general configuration of the module. You can apply particular permissions.';
$DOWNLOAD_LANG['global_auth'] = 'Overall permissions';
$DOWNLOAD_LANG['global_auth_explain'] = 'Here you define overall permissions of the module. You can change these permissions locally in each category';

//Erreurs
$DOWNLOAD_LANG['successful_operation'] = 'The operation that you have asked for has been made successfully';
$DOWNLOAD_LANG['required_fields_empty'] = 'Whole requiered files are not be typed, please correctly redo the operation';
$DOWNLOAD_LANG['unexisting_category'] = 'The category you have selected does not exist';
$DOWNLOAD_LANG['new_cat_does_not_exist'] = 'The target category does not exist';
$DOWNLOAD_LANG['infinite_loop'] = 'You want to move the category in one of its subcategories or in itself, that makes no sense. Please choose another category';
$DOWNLOAD_LANG['recount_success'] = 'Files number for each category was recounted successfully.';

//Erreur
$LANG['e_unexist_file_download'] = 'The file you asked for does not exist !';
$LANG['e_unexist_category_download'] = 'The category you asked for does not exist !';

?>