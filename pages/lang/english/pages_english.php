<?php
/*##################################################
 *                              pages_english.php
 *                            -------------------
 *   begin                : August 18, 2007
 *   copyright          : (C) 2007 Beno�t Sautel
 *   email                : ben.popeye@phpboost.com
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
#                                                           English                                                                             #
####################################################

//G�n�ralit�s
$LANG['pages'] = 'Pages';

$LANG['page_hits'] = 'This page has been seen %d times';

//Administration
$LANG['pages_count_hits'] = 'Count hits';
$LANG['pages_count_hits_explain'] = 'Can be changed for each page.';
$LANG['pages_auth_read'] = 'Read page';
$LANG['pages_auth_edit'] = 'Edit page';
$LANG['pages_auth_read_com'] = 'Read and write commentaries';
$LANG['pages_auth'] = 'Permissions';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Deselect all';
$LANG['ranks'] = 'Ranks';
$LANG['groups'] = 'Groups';

//Cr�ation / �dition d'une page
$LANG['pages_edition'] = 'Editing a page';
$LANG['pages_creation'] = 'Creating a page';
$LANG['pages_edit_page'] = 'Edition of the page <em>%s</em>';
$LANG['page_title'] = 'Page title';
$LANG['page_contents'] = 'Page contents';
$LANG['pages_edit'] = 'Edit this page';
$LANG['pages_delete'] = 'Delete this page';
$LANG['pages_create'] = 'Create a page';
$LANG['pages_activ_com'] = 'Activate commentaries';
$LANG['pages_own_auth'] = 'Apply individual permissions to this page';
$LANG['pages_is_cat'] = 'This page is a category';
$LANG['pages_parent_cat'] = 'Parent category';
$LANG['pages_page_path'] = 'Path';
$LANG['pages_properties'] = 'Properties';
$LANG['pages_no_selected_cat'] = 'No selected category';
$LANG['explain_select_multiple'] = 'Press Ctrl then click into the list to select several options';
$LANG['pages_previewing'] = 'Preview';
$LANG['pages_contents_part'] = 'Page contents';
$LANG['pages_delete_success'] = 'The page has been deleted successful.';
$LANG['pages_delete_failure'] = 'The page hasn\'t been deleted. An error occured.';
$LANG['pages_confirm_delete'] = 'Are you sure you want to delete this page ?';

//Divers
$LANG['pages_links_list'] = 'Tools';
$LANG['pages_com'] = 'Commentaries';
$LANG['pages_explorer'] = 'Explorer';
$LANG['pages_root'] = 'Root';
$LANG['pages_cats_tree'] = 'Categories tree';
$LANG['pages_display_coms'] = 'Commentaries (%d)';
$LANG['pages_post_com'] = 'Post a comment';
$LANG['pages_page_com'] = 'Commentaries of the page %s';

//Accueil
$LANG['pages_explain'] = 'You are in the controll panel of pages module. Here you can manage your whole pages.
<div class="question">You can use both BBCode and HTML syntax to create your page but PHP scripts are forbidden for security reasons.
<br />
To create links between different pages of this module you have to use the <em>link</em> tag which doesn\'t appear in the BBCode toolbar, but the syntax is for instance : [link=title-of-the-page]Link up to page[/link].</div>';
$LANG['pages_redirections'] = 'Redirections';
$LANG['pages_num_pages'] = '%d existing page(s)';
$LANG['pages_num_coms'] = '%d commentaries on the whole pages, which corresponds to %1.1f commentary by page';
$LANG['pages_stats'] = 'Statistics';
$LANG['pages_tools'] = 'Tools';

//Redirections et renommer
$LANG['pages_rename'] = 'Rename';
$LANG['pages_redirection_management'] = 'Redirections management';
$LANG['pages_rename_page'] = 'Rename the page <em>%s</em>';
$LANG['pages_new_title'] = 'New title of this page';
$LANG['pages_create_redirection'] = 'Create a redirection from the previous title to the actual ?';
$LANG['pages_explain_rename'] = 'You are just going to rename the page. You have to know that every link pointing up to that page will be broken. It\'s the reason why you have possibility to create a redirection from the previous title to the new, which won\'t break those links.';
$LANG['pages_confirm_delete_redirection'] = addslashes('Are you sure you want to delete this redirection ?');
$LANG['pages_delete_redirection'] = 'Delete this redirection';
$LANG['pages_redirected_from'] = 'Redirected from <em>%s</em>';
$LANG['pages_redirection_title'] = 'Redirection title';
$LANG['pages_redirection_target'] = 'Redirection target';
$LANG['pages_redirection_actions'] = 'Actions';
$LANG['pages_manage_redirection'] = 'Consult every redirection pointing to this page';
$LANG['pages_no_redirection'] = 'No existing redirection';
$LANG['pages_create_redirection'] = 'Create a redirection up to this article';
$LANG['pages_creation_redirection'] = 'Creating a redirection';
$LANG['pages_creation_redirection_title'] = 'Creating a redirection up to %s';
$LANG['pages_redirection_title'] = 'Redirection name';
$LANG['pages_remove_this_cat'] = 'Deleting the category : <em>%s</em>';
$LANG['pages_remove_all_contents'] = 'Delete its whole contents';
$LANG['pages_move_all_contents'] = 'Move its whole contents into the folowing folder :';
$LANG['pages_future_cat'] = 'Category in which you want to move those elements';
$LANG['pages_change_cat'] = 'Change category';
$LANG['pages_delete_cat'] = 'Deleting a category';
$LANG['pages_confirm_remove_cat'] = 'Are you sure you want to delete this category ?';
 
//Erreurs
$LANG['pages_not_found'] = 'The page you are asking doesn\'t exist';
$LANG['pages_error_auth_read'] = 'You haven\'t go necessary permissions to read this page';
$LANG['pages_error_auth_com'] = 'You haven\'t necessary permissions to read comments related to this page';
$LANG['pages_error_unactiv_com'] = 'Comments related to this page have been disabled.';
$LANG['page_alert_title'] = 'You have to enter a title';
$LANG['page_alert_contents'] = 'You have to enter a contents for your page';
$LANG['pages_already_exists'] = 'The title you have chosen already exists. You must choose another, because titles must be uniques.';
$LANG['pages_cat_contains_cat'] = 'The category you have selected to put this category is contained by herself or one of its sons, which is not possible. Please select another category.';
$LANG['pages_notice_previewing'] = 'You are previewing the contents you have entered. No edition has been done into the database, you must submit your page if you want to take into consideration your edition.';

//Admin
$LANG['pages_config'] = 'Configuration';
$LANG['pages_management'] = 'Pages management';

?>