<?php
/*##################################################
 *                              video_french.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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


####################################################
#                                                          French                                                                        #
####################################################

global $VIDEO_LANG;
$VIDEO_LANG = array();

//Autorisations
$VIDEO_LANG['auth_read'] = 'Permissions de lecture';
$VIDEO_LANG['auth_write'] = 'Permissions d\'�criture';
$VIDEO_LANG['auth_aprob'] = 'Permissions d\'approuver';
$VIDEO_LANG['auth_flood'] = 'Permissions de flooder';
$VIDEO_LANG['auth_edit'] = 'Permissions d\'�diter';
$VIDEO_LANG['auth_delete'] = 'Permissions de supprimer';
$VIDEO_LANG['auth_moderate'] = 'Permissions de mod�rer';
$VIDEO_LANG['special_auth'] = 'Permissions sp�ciales';
$VIDEO_LANG['special_auth_explain'] = 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.';

//G�n�ralit�s
$VIDEO_LANG['video'] = 'Vid�o';
$VIDEO_LANG['all_cats'] = 'Toutes les cat�gories';

//Management
$VIDEO_LANG['video_management'] = 'Gestion des vid�os';
$VIDEO_LANG['video_configuration'] = 'Configuration des vid�os';
$VIDEO_LANG['video_list'] = 'Liste des vid�os';
$VIDEO_LANG['cats_management'] = 'Gestion des cat�gories';
$VIDEO_LANG['add_cat'] = 'Ajouter une cat�gorie';
$VIDEO_LANG['show_all_answers'] = 'Afficher toutes les r�ponses';
$VIDEO_LANG['hide_all_answers'] = 'Cacher toutes les r�ponses';
$VIDEO_LANG['move'] = 'D�placer';
$VIDEO_LANG['moving_a_question'] = 'D�placement d\'une question';
$VIDEO_LANG['target_category'] = 'Cat�gorie cible';

//Others
$VIDEO_LANG['recount_success'] = 'Le nombre de vid�os pour chaque cat�gorie a �t� recompt� avec succ�s.';
$VIDEO_LANG['recount_video_number'] = 'Recompter le nombre de vid�os pour chaque cat�gorie';

//Avertissement
$VIDEO_LANG['required_fields'] = 'Les champs marqu�s * sont obligatoires !';
$VIDEO_LANG['require_entitled'] = 'Veuillez entrer l\'intitul� de la question';
$VIDEO_LANG['require_answer'] = 'Veuillez entrer la r�ponse';
$VIDEO_LANG['require_cat_name'] = 'Veuillez entrer le nom de la cat�gorie';

//Administration / categories
$VIDEO_LANG['category'] = 'Cat�gorie';
$VIDEO_LANG['category_name'] = 'Nom de la cat�gorie';
$VIDEO_LANG['category_location'] = 'Emplacement de la cat�gorie';
$VIDEO_LANG['category_image'] = 'Image de la cat�gorie';
$VIDEO_LANG['removing_category'] = 'Suppression d\'une cat�gorie';
$VIDEO_LANG['explain_removing_category'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (questions et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de son cat�gorie. <strong>Attention, cette action est irr�versible !</strong>';
$VIDEO_LANG['delete_category_and_its_content'] = 'Supprimer la cat�gorie et tout son contenu';
$VIDEO_LANG['move_category_content'] = 'D�placer son contenu dans :';
$VIDEO_LANG['faq_name'] = 'Nom de la VIDEO';
$VIDEO_LANG['faq_name_explain'] = 'Le nom de la VIDEO appara�tra dans le titre et dans l\'arborescence de chaque page';
$VIDEO_LANG['nbr_cols'] = 'Nombre de cat�gories par colonne';
$VIDEO_LANG['nbr_cols_explain'] = 'Ce nombre est le nombre de colonnes dans lesquelles seront pr�sent�es les sous cat�gories d\'une cat�gorie';
$VIDEO_LANG['display_mode_admin_explain'] = 'Vous pouvez choisir la fa�on dont les questions seront affich�es. Le mode en ligne permet d\'afficher les questions et un clic sur la question affiche la r�ponse, alors que le mode en blocs affiche l\'encha�nement des questions et des r�ponses. Il sera possible de choisir pour chaque cat�gorie le mode d\'affichage, il ne s\'agit ici que de la configuration par d�faut.';
$VIDEO_LANG['general_auth'] = 'Autorisations g�n�rales';
$VIDEO_LANG['general_auth_explain'] = 'Vous configurez ici les autorisations g�n�rales de lecture et d\'�criture sur la VIDEO. Vous pourrez ensuite pour chaque cat�gorie lui appliquer des autorisations particuli�res.';

//Gestion
$VIDEO_LANG['cat_properties'] = 'Propri�t�s de la cat�gorie';
$VIDEO_LANG['cat_description'] = 'Description';
$VIDEO_LANG['go_back_to_cat'] = 'Retour � la cat�gorie';
$VIDEO_LANG['display_mode'] = 'Mode d\'affichage';
$VIDEO_LANG['display_block'] = 'Par blocs';
$VIDEO_LANG['display_inline'] = 'En lignes';
$VIDEO_LANG['display_auto'] = 'Automatique';
$VIDEO_LANG['display_explain'] = 'En automatique l\'affichage suivra la configuration g�n�rale, en ligne les r�ponses seront masqu�es et un clic sur la question affichera la r�ponse correspondante tandis que en blocs les questions seront suivies de leurs r�ponses.';
$VIDEO_LANG['global_auth'] = 'Autorisations sp�ciales';
$VIDEO_LANG['global_auth_explain'] = 'Permet d\'appliquer des autorisations particuli�res � la cat�gorie. Attention les autorisations de lecture se transmettent dans les sous cat�gories, c\'est-�-dire que si vous ne pouvez pas voir une cat�gorie vous ne pouvez pas voir ses filles.';
$VIDEO_LANG['read_auth'] = 'Autorisations de lecture';
$VIDEO_LANG['write_auth'] = 'Autorisations d\'�criture';
$VIDEO_LANG['questions_list'] = 'Liste des questions';
$VIDEO_LANG['ranks'] = 'Rangs';
$VIDEO_LANG['insert_question'] = 'Ins�rer une question';
$VIDEO_LANG['insert_question_begening'] = 'Ins�rer une question au d�but';
$VIDEO_LANG['update'] = 'Modifier';
$VIDEO_LANG['delete'] = 'Supprimer';
$VIDEO_LANG['up'] = 'Monter';
$VIDEO_LANG['down'] = 'Descendre';
$VIDEO_LANG['confirm_delete'] = 'Etes-vous s�r de vouloir supprimer cette question ?';
$VIDEO_LANG['category_management'] = 'Gestion d\'une cat�gorie';
$VIDEO_LANG['category_manage'] = 'G�rer la cat�gorie';
$VIDEO_LANG['question_edition'] = 'Modification d\'une question';
$VIDEO_LANG['question_creation'] = 'Cr�ation d\'une question';
$VIDEO_LANG['question'] = 'Question';
$VIDEO_LANG['entitled'] = 'Intitul�';
$VIDEO_LANG['answer'] = 'R�ponse';

//Errors
$VIDEO_LANG['successful_operation'] = 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s';
$VIDEO_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement';
$VIDEO_LANG['unexisting_category'] = 'La cat�gorie que vous avez s�lectionn�e n\'existe pas';
$VIDEO_LANG['new_cat_does_not_exist'] = 'La cat�gorie cible n\'existe pas';
$VIDEO_LANG['infinite_loop'] = 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie';

?>