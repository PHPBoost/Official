<?php
/*##################################################
 *                              download_french.php
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

global $DOWNLOAD_LANG;
$DOWNLOAD_LANG = array();

//Admin
$DOWNLOAD_LANG['download_add'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['download_management'] = 'Gestion T�l�chargements';
$DOWNLOAD_LANG['download_config'] = 'Configuration des t�l�chargements';
$DOWNLOAD_LANG['file_list'] = 'Liste des fichiers';
$DOWNLOAD_LANG['edit_file'] = 'Edition du fichier';
$DOWNLOAD_LANG['nbr_download_max'] = 'Nombre maximum de fichiers affich�s';
$DOWNLOAD_LANG['download_date'] = 'Date du fichier <span class="text_small">(jj/mm/aa)</span> <br />
<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)';
$DOWNLOAD_LANG['icon_cat'] = 'Image de la cat�gorie';
$DOWNLOAD_LANG['explain_icon_cat'] = 'Vous pouvez choisir une image du r�pertoire download/ ou mettre son adresse dans le champ pr�vu � cet effet';
$DOWNLOAD_LANG['root_description'] = 'Description de la racine des t�l�chargements';

//Titre
$DOWNLOAD_LANG['title_download'] = 'T�l�chargements';

//DL
$DOWNLOAD_LANG['file'] = 'Fichier';
$DOWNLOAD_LANG['size'] = 'Taille';
$DOWNLOAD_LANG['download'] = 'T�l�chargements';
$DOWNLOAD_LANG['none_download'] = 'Aucun fichier dans cette cat�gorie';
$DOWNLOAD_LANG['xml_download_desc'] = 'Derniers fichiers';
$DOWNLOAD_LANG['no_note'] = 'Aucune note';
$DOWNLOAD_LANG['actual_note'] = 'Note actuelle';
$DOWNLOAD_LANG['vote_action'] = 'Voter';
$DOWNLOAD_LANG['add_on_date'] = 'Ajout� le %s';
$DOWNLOAD_LANG['downloaded_n_times'] = 'T�l�charg� %d fois';
$DOWNLOAD_LANG['num_com'] = '%d commentaire';
$DOWNLOAD_LANG['num_coms'] = '%d commentaires';
$DOWNLOAD_LANG['this_note'] = 'Note :';
$DOWNLOAD_LANG['short_contents'] = 'Courte description';
$DOWNLOAD_LANG['complete_contents'] = 'Description compl�te';
$DOWNLOAD_LANG['url'] = 'Adresse du fichier';
$DOWNLOAD_LANG['confirm_delete_file'] = 'Etes-vous certain de vouloir supprimer ce fichier ?';
$DOWNLOAD_LANG['download_file'] = 'T�l�charger le fichier';
$DOWNLOAD_LANG['file_infos'] = 'Informations sur le fichier';
$DOWNLOAD_LANG['insertion_date'] = 'Date d\'ajout';
$DOWNLOAD_LANG['last_update_date'] = 'Date de sortie ou de derni�re mise � jour';
$DOWNLOAD_LANG['downloaded'] = 'T�l�charg�';
$DOWNLOAD_LANG['n_times'] = '%d fois';
$DOWNLOAD_LANG['num_notes'] = '%d votant(s)';
$DOWNLOAD_LANG['edit_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['delete_file'] = 'Supprimer le fichier';

//Gestion des fichiers
$DOWNLOAD_LANG['files_management'] = 'Gestion des fichiers';
$DOWNLOAD_LANG['file_management'] = 'Modification d\'un fichier';
$DOWNLOAD_LANG['file_addition'] = 'Ajout d\'un fichier';
$DOWNLOAD_LANG['add_file'] = 'Ajouter le fichier';

//Cat�gories
$DOWNLOAD_LANG['add_category'] = 'Ajouter une cat�gorie';
$DOWNLOAD_LANG['removing_category'] = 'Suppression d\'une cat�gorie';
$DOWNLOAD_LANG['explain_removing_category'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (questions et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de son cat�gorie. <strong>Attention, cette action est irr�versible !</strong>';
$DOWNLOAD_LANG['delete_category_and_its_content'] = 'Supprimer la cat�gorie et tout son contenu';
$DOWNLOAD_LANG['move_category_content'] = 'D�placer son contenu dans :';
$DOWNLOAD_LANG['required_fields'] = 'Les champs marqu�s * sont obligatoires !';
$DOWNLOAD_LANG['category_name'] = 'Nom de la cat�gorie';
$DOWNLOAD_LANG['category_location'] = 'Emplacement de la cat�gorie';
$DOWNLOAD_LANG['cat_description'] = 'Description de la cat�gorie';
$DOWNLOAD_LANG['num_files_singular'] = '%d fichier';
$DOWNLOAD_LANG['num_files_plural'] = '%d fichiers';

//Autorisations
$DOWNLOAD_LANG['auth_read'] = 'Permissions de lecture';
$DOWNLOAD_LANG['auth_write'] = 'Permissions d\'�criture';
$DOWNLOAD_LANG['special_auth'] = 'Permissions sp�ciales';
$DOWNLOAD_LANG['special_auth_explain'] = 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.';
$DOWNLOAD_LANG['global_auth'] = 'Permissions globales';
$DOWNLOAD_LANG['global_auth_explain'] = 'Vous d�finissez ici les permissions globales au module. Vous pourrez changer ces permissions localement sur chaque cat�gorie';

//Erreurs
$DOWNLOAD_LANG['successful_operation'] = 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s';
$DOWNLOAD_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement';
$DOWNLOAD_LANG['unexisting_category'] = 'La cat�gorie que vous avez s�lectionn�e n\'existe pas';
$DOWNLOAD_LANG['new_cat_does_not_exist'] = 'La cat�gorie cible n\'existe pas';
$DOWNLOAD_LANG['infinite_loop'] = 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie';
$DOWNLOAD_LANG['recount_success'] = 'Le nombre de fichiers pour chaque cat�gorie a �t� recompt� avec succ�s.';

//Erreurs
$LANG['e_unexist_file_download'] = 'Le fichier que vous demandez n\'existe pas !';
?>