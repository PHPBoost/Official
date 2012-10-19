<?php
/*##################################################
 *                              bugtracker_french.php
 *                            -------------------
 *   begin                : February 01, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 #						French						#
 ####################################################

$lang = array();

//Titre du module
$lang['bugs.module_title'] = 'Bugtracker';

//Messages divers
$lang['bugs.notice.no_one'] = 'Personne';
$lang['bugs.notice.none'] = 'Aucun';
$lang['bugs.notice.none_e'] = 'Aucune';
$lang['bugs.notice.no_bug'] = 'Aucun bug n\'a �t� d�clar�';
$lang['bugs.notice.no_bug_solved'] = 'Aucun bug n\'a �t� corrig�';
$lang['bugs.notice.no_bug_fixed'] = 'Aucun bug n\'a �t� corrig� dans cette version';
$lang['bugs.notice.no_version'] = 'Aucune version existante';
$lang['bugs.notice.no_type'] = 'Aucun type n\'a �t� d�clar�';
$lang['bugs.notice.no_category'] = 'Aucune cat�gorie n\'a �t� d�clar�e';
$lang['bugs.notice.no_priority'] = 'Aucune priorit� n\'a �t� d�clar�e';
$lang['bugs.notice.no_severity'] = 'Aucun niveau n\'a �t� d�clar�';
$lang['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$lang['bugs.notice.contents_update'] = 'Mise � jour du contenu';
$lang['bugs.notice.new_comment'] = 'Nouveau commentaire';
$lang['bugs.notice.reproduction_method_update'] = 'Mise � jour de la m�thode de reproduction';
$lang['bugs.notice.not_defined'] = 'Non d�fini';
$lang['bugs.notice.require_login'] = 'Veuillez saisir un pseudo !';
$lang['bugs.notice.require_type'] = 'Veuillez saisir un nom pour le nouveau type !';
$lang['bugs.notice.require_category'] = 'Veuillez saisir un nom pour la nouvelle cat�gorie !';
$lang['bugs.notice.require_priority'] = 'Veuillez saisir un nom pour la nouvelle priorit� !';
$lang['bugs.notice.require_severity'] = 'Veuillez saisir un nom pour le nouveau niveau !';
$lang['bugs.notice.require_version'] = 'Veuillez saisir un nom pour la nouvelle version !';
$lang['bugs.notice.require_choose_type'] = 'Veuillez choisir le type votre bug !';
$lang['bugs.notice.require_choose_category'] = 'Veuillez choisir la cat�gorie votre bug !';
$lang['bugs.notice.require_choose_priority'] = 'Veuillez choisir la priorit� de votre bug !';
$lang['bugs.notice.require_choose_severity'] = 'Veuillez choisir le niveau de votre bug !';
$lang['bugs.notice.require_choose_detected_in'] = 'Veuillez choisir la version dans laquelle votre bug a �t� d�tect� !';
$lang['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$lang['bugs.actions'] = 'Actions';
$lang['bugs.actions.add'] = 'Nouveau bug';
$lang['bugs.actions.delete'] = 'Supprimer le bug';
$lang['bugs.actions.edit'] = 'Editer le bug';
$lang['bugs.actions.history'] = 'Historique du bug';
$lang['bugs.actions.reject'] = 'Rejeter le bug';
$lang['bugs.actions.reopen'] = 'R�-ouvrir le bug';
$lang['bugs.actions.confirm.del_bug'] = 'Etes-vous s�r de vouloir supprimer ce bug de la liste ? (toute l\'historique associ�e sera supprim�e)';
$lang['bugs.actions.confirm.del_version'] = 'Etes-vous s�r de vouloir supprimer cette version ?';
$lang['bugs.actions.confirm.del_type'] = 'Etes-vous s�r de vouloir supprimer ce type ?';
$lang['bugs.actions.confirm.del_category'] = 'Etes-vous s�r de vouloir supprimer cette cat�gorie ?';
$lang['bugs.actions.confirm.del_priority'] = 'Etes-vous s�r de vouloir supprimer cette priorit� ?';
$lang['bugs.actions.confirm.del_severity'] = 'Etes-vous s�r de vouloir supprimer ce niveau ?';

//Titres
$lang['bugs.titles.add_bug'] = 'Nouveau bug';
$lang['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$lang['bugs.titles.add_type'] = 'Ajout d\'un nouveau type';
$lang['bugs.titles.add_category'] = 'Ajout d\'une nouvelle cat�gorie';
$lang['bugs.titles.add_priority'] = 'Ajout d\'une nouvelle priorit�';
$lang['bugs.titles.add_severity'] = 'Ajout d\'un nouveau niveau';
$lang['bugs.titles.edit_bug'] = 'Edition du bug';
$lang['bugs.titles.history_bug'] = 'Historique du bug';
$lang['bugs.titles.view_bug'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Feuille de route';
$lang['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$lang['bugs.titles.bugs_stats'] = 'Statistiques';
$lang['bugs.titles.bugs_treatment'] = 'Traitement du bug';
$lang['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$lang['bugs.titles.disponible_versions'] = 'Versions disponibles';
$lang['bugs.titles.disponible_types'] = 'Types disponibles';
$lang['bugs.titles.disponible_categories'] = 'Cat�gories disponibles';
$lang['bugs.titles.disponible_priorities'] = 'Priorit�s disponibles';
$lang['bugs.titles.disponible_severities'] = 'Niveaux disponibles';
$lang['bugs.titles.admin.management'] = 'Gestion bugtracker';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Authorisations';
$lang['bugs.titles.choose_version'] = 'Version � afficher';
$lang['bugs.titles.solved_bugs'] = 'Bugs r�solus';
$lang['bugs.titles.unsolved_bugs'] = 'Bugs non-r�solus';
$lang['bugs.titles.contents_value_title'] = 'Description par d�faut d\'un bug';
$lang['bugs.titles.contents_value'] = 'Description par d�faut';

//Libell�s
$lang['bugs.labels.fields.id'] = 'ID';
$lang['bugs.labels.fields.title'] = 'Titre';
$lang['bugs.labels.fields.contents'] = 'Description';
$lang['bugs.labels.fields.author_id'] = 'D�tect� par';
$lang['bugs.labels.fields.submit_date'] = 'D�tect� le';
$lang['bugs.labels.fields.status'] = 'Etat';
$lang['bugs.labels.fields.type'] = 'Type';
$lang['bugs.labels.fields.category'] = 'Cat�gorie';
$lang['bugs.labels.fields.reproductible'] = 'Reproductible';
$lang['bugs.labels.fields.reproduction_method'] = 'M�thode de reproduction';
$lang['bugs.labels.fields.severity'] = 'Niveau';
$lang['bugs.labels.fields.priority'] = 'Priorit�';
$lang['bugs.labels.fields.detected_in'] = 'D�tect� dans la version';
$lang['bugs.labels.fields.fixed_in'] = 'Corrig� dans la version';
$lang['bugs.labels.fields.assigned_to_id'] = 'Assign� �';
$lang['bugs.labels.fields.updater_id'] = 'Modifi� par';
$lang['bugs.labels.fields.update_date'] = 'Modifi� le';
$lang['bugs.labels.fields.updated_field'] = 'Champ modifi�';
$lang['bugs.labels.fields.old_value'] = 'Ancienne valeur';
$lang['bugs.labels.fields.new_value'] = 'Nouvelle valeur';
$lang['bugs.labels.fields.change_comment'] = 'Commentaire';
$lang['bugs.labels.fields.version'] = 'Version';
$lang['bugs.labels.fields.version_detected_in'] = 'Afficher dans la liste "D�tect� dans la version"';
$lang['bugs.labels.fields.version_fixed_in'] = 'Afficher dans la liste "Corrig� dans la version"';
$lang['bugs.labels.fields.version_detected'] = 'Version d�tect�e';
$lang['bugs.labels.fields.version_fixed'] = 'Version corrig�e';
$lang['bugs.labels.color'] = 'Couleur';
$lang['bugs.labels.number'] = 'Nombre de bugs';
$lang['bugs.labels.number_corrected'] = 'Nombre de bugs corrig�s';
$lang['bugs.labels.top_10_posters'] = 'Top 10 : posteurs';
$lang['bugs.labels.default'] = 'Par d�faut';
$lang['bugs.labels.del_default_value'] = 'Supprimer la valeur par d�faut';
$lang['bugs.labels.type_mandatory'] = 'Section "Type" obligatoire ?';
$lang['bugs.labels.category_mandatory'] = 'Section "Cat�gorie" obligatoire ?';
$lang['bugs.labels.severity_mandatory'] = 'Section "Niveau" obligatoire ?';
$lang['bugs.labels.priority_mandatory'] = 'Section "Priorit�" obligatoire ?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section "D�tect� dans la version" obligatoire ?';
$lang['bugs.labels.date_format'] = 'Format d\'affichage de la date';
$lang['bugs.labels.date_time'] = 'Date et heure';

//Etats
$lang['bugs.status.new'] = 'Nouveau';
$lang['bugs.status.assigned'] = 'Assign�';
$lang['bugs.status.fixed'] = 'Corrig�';
$lang['bugs.status.reopen'] = 'R�-ouvert';
$lang['bugs.status.rejected'] = 'Rejet�';

//Explications
$lang['bugs.explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrig�s pour chaque version';
$lang['bugs.explain.pm'] = 'Permet d\'envoyer un MP dans les cas suivants :<br />
- Commentaire sur un bug<br />
- Edition d\'un bug<br />
- Suppression d\'un bug<br />
- Assignation d\'un bug<br />
- Rejet d\'un bug<br />
- R�ouverture d\'un bug<br />';
$lang['bugs.explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'�volution...';
$lang['bugs.explain.category'] = 'Cat�gorie des demandes. Exemples : Noyau, Module...';
$lang['bugs.explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$lang['bugs.explain.priority'] = 'Priorit� des demandes. Exemples : Basse, Normale, Elev�e...';
$lang['bugs.explain.version'] = 'Liste des versions du produit.';
$lang['bugs.explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br /><br />';
$lang['bugs.explain.contents_value'] = 'Entrez ci-dessous la description par d�faut � afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pr�-remplie.';

//MP
$lang['bugs.pm.assigned.title'] = '[%s] Le bug #%d vous a �t� assign� par %s';
$lang['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le d�tail du bug :
%s';
$lang['bugs.pm.comment.title'] = '[%s] Le bug #%d a �t� comment� par %s';
$lang['bugs.pm.comment.contents'] = '%s a ajout� le commentaire suivant au bug #%d :

%s

Lien vers le bug :
%s';
$lang['bugs.pm.edit.title'] = '[%s] Le bug #%d a �t� modifi� par %s';
$lang['bugs.pm.edit.contents'] = '%s a modifi� les champs suivants dans le bug #%d :

%s

Lien vers le bug :
%s';
$lang['bugs.pm.reopen.title'] = '[%s] Le bug #%d a �t� r�-ouvert par %s';
$lang['bugs.pm.reopen.contents'] = '%s a r�-ouvert le bug #%d.
Lien vers le bug :
%s';
$lang['bugs.pm.reject.title'] = '[%s] Le bug #%d a �t� rejet� par %s';
$lang['bugs.pm.reject.contents'] = '%s a rejet� le bug #%d.
Lien vers le bug :
%s';
$lang['bugs.pm.delete.title'] = '[%s] Le bug #%d a �t� supprim� par %s';
$lang['bugs.pm.delete.contents'] = '%s a supprim� le bug #%d.
Lien vers le bug :
%s';

//Recherche
$lang['bugs.search.where'] = 'O� ?';
$lang['bugs.search.where.title'] = 'Titre';
$lang['bugs.search.where.contents'] = 'Contenu';

//Configuration
$lang['bugs.config.items_per_page'] = 'Nombre de bugs par page'; 
$lang['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug "Rejet�"';
$lang['bugs.config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug "Ferm�"';
$lang['bugs.config.activ_com'] = 'Activer les commentaires';
$lang['bugs.config.activ_roadmap'] = 'Activer la feuille de route';
$lang['bugs.config.activ_cat_in_title'] = 'Afficher la cat�gorie dans le titre du bug';
$lang['bugs.config.activ_pm'] = 'Activer l\'envoi de MP';

//Autorisations
$lang['bugs.config.auth'] = 'Autorisations';
$lang['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$lang['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$lang['bugs.config.auth.create_advanced'] = 'Autorisation avanc�es pour signaler un bug';
$lang['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorit� du bug';
$lang['bugs.config.auth.moderate'] = 'Autorisation de mod�ration des bugs';

//Erreurs
$lang['bugs.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de bugs par page\"';
$lang['bugs.error.e_no_user_assigned'] = 'Ce bug n\'a �t� assign� � aucun utilisateur, l\'�tat ne pas passer � "' . $lang['bugs.status.assigned'] . '"';
$lang['bugs.error.e_no_fixed_version'] = 'Veuillez s�lectionner la version de correction avant de passer � l\'�tat "' . $lang['bugs.status.fixed'] . '"';
$lang['bugs.error.e_config_success'] = 'La configuration a �t� modifi�e avec succ�s';
$lang['bugs.error.e_edit_success'] = 'Le bug a �t� modifi� avec succ�s';
$lang['bugs.error.e_delete_success'] = 'Le bug a �t� supprim� avec succ�s';
$lang['bugs.error.e_reject_success'] = 'Le bug a �t� rejet�';
$lang['bugs.error.e_reopen_success'] = 'Le bug a �t� r�-ouvert';
$lang['bugs.error.e_unexist_bug'] = 'Ce bug n\'existe pas';
$lang['admin.success-saving-config'] = 'Vous avez modifi� la configuration avec succ�s ';

?>