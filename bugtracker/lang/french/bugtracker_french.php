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
# French                                           #
####################################################
 
//Titre du module
$LANG['bugs.module_title'] = 'Bugtracker';

//Messages divers
$LANG['bugs.notice.no_one'] = 'Personne';
$LANG['bugs.notice.none'] = 'Aucun';
$LANG['bugs.notice.none_e'] = 'Aucune';
$LANG['bugs.notice.no_bug'] = 'Aucun bug n\'a �t� d�clar�';
$LANG['bugs.notice.no_bug_solved'] = 'Aucun bug n\'a �t� corrig�';
$LANG['bugs.notice.no_bug_fixed'] = 'Aucun bug n\'a �t� corrig� dans cette version';
$LANG['bugs.notice.no_version'] = 'Aucune version existante';
$LANG['bugs.notice.no_type'] = 'Aucun type n\'a �t� d�clar�';
$LANG['bugs.notice.no_category'] = 'Aucune cat�gorie n\'a �t� d�clar�e';
$LANG['bugs.notice.no_priority'] = 'Aucune priorit� n\'a �t� d�clar�e';
$LANG['bugs.notice.no_severity'] = 'Aucun niveau n\'a �t� d�clar�';
$LANG['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$LANG['bugs.notice.contents_update'] = 'Mise � jour du contenu';
$LANG['bugs.notice.new_comment'] = 'Nouveau commentaire';
$LANG['bugs.notice.reproduction_method_update'] = 'Mise � jour de la m�thode de reproduction';
$LANG['bugs.notice.not_defined'] = 'Non d�fini';
$LANG['bugs.notice.not_defined_e_date'] = 'Date non d�finie';
$LANG['bugs.notice.require_login'] = 'Veuillez saisir un pseudo !';
$LANG['bugs.notice.require_type'] = 'Veuillez saisir un nom pour le nouveau type !';
$LANG['bugs.notice.require_category'] = 'Veuillez saisir un nom pour la nouvelle cat�gorie !';
$LANG['bugs.notice.require_priority'] = 'Veuillez saisir un nom pour la nouvelle priorit� !';
$LANG['bugs.notice.require_severity'] = 'Veuillez saisir un nom pour le nouveau niveau !';
$LANG['bugs.notice.require_version'] = 'Veuillez saisir un nom pour la nouvelle version !';
$LANG['bugs.notice.require_choose_type'] = 'Veuillez choisir le type votre bug !';
$LANG['bugs.notice.require_choose_category'] = 'Veuillez choisir la cat�gorie votre bug !';
$LANG['bugs.notice.require_choose_priority'] = 'Veuillez choisir la priorit� de votre bug !';
$LANG['bugs.notice.require_choose_severity'] = 'Veuillez choisir le niveau de votre bug !';
$LANG['bugs.notice.require_choose_detected_in'] = 'Veuillez choisir la version dans laquelle votre bug a �t� d�tect� !';
$LANG['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$LANG['bugs.actions'] = 'Actions';
$LANG['bugs.actions.add'] = 'Nouveau bug';
$LANG['bugs.actions.delete'] = 'Supprimer le bug';
$LANG['bugs.actions.edit'] = 'Editer le bug';
$LANG['bugs.actions.history'] = 'Historique du bug';
$LANG['bugs.actions.reject'] = 'Rejeter le bug';
$LANG['bugs.actions.reopen'] = 'R�-ouvrir le bug';
$LANG['bugs.actions.confirm.del_bug'] = 'Etes-vous s�r de vouloir supprimer ce bug de la liste ? (toute l\'historique associ�e sera supprim�e)';
$LANG['bugs.actions.confirm.del_version'] = 'Etes-vous s�r de vouloir supprimer cette version ?';
$LANG['bugs.actions.confirm.del_type'] = 'Etes-vous s�r de vouloir supprimer ce type ?';
$LANG['bugs.actions.confirm.del_category'] = 'Etes-vous s�r de vouloir supprimer cette cat�gorie ?';
$LANG['bugs.actions.confirm.del_priority'] = 'Etes-vous s�r de vouloir supprimer cette priorit� ?';
$LANG['bugs.actions.confirm.del_severity'] = 'Etes-vous s�r de vouloir supprimer ce niveau ?';

//Titres
$LANG['bugs.titles.add_bug'] = 'Nouveau bug';
$LANG['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$LANG['bugs.titles.add_type'] = 'Ajout d\'un nouveau type';
$LANG['bugs.titles.add_category'] = 'Ajout d\'une nouvelle cat�gorie';
$LANG['bugs.titles.add_priority'] = 'Ajout d\'une nouvelle priorit�';
$LANG['bugs.titles.add_severity'] = 'Ajout d\'un nouveau niveau';
$LANG['bugs.titles.edit_bug'] = 'Edition du bug';
$LANG['bugs.titles.history_bug'] = 'Historique du bug';
$LANG['bugs.titles.view_bug'] = 'Bug';
$LANG['bugs.titles.roadmap'] = 'Feuille de route';
$LANG['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$LANG['bugs.titles.bugs_stats'] = 'Statistiques';
$LANG['bugs.titles.bugs_treatment'] = 'Traitement du bug';
$LANG['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$LANG['bugs.titles.versions'] = 'Versions';
$LANG['bugs.titles.types'] = 'Types';
$LANG['bugs.titles.categories'] = 'Cat�gories';
$LANG['bugs.titles.priorities'] = 'Priorit�s';
$LANG['bugs.titles.severities'] = 'Niveaux';
$LANG['bugs.titles.admin.management'] = 'Gestion bugtracker';
$LANG['bugs.titles.admin.config'] = 'Configuration';
$LANG['bugs.titles.admin.authorizations'] = 'Autorisations';
$LANG['bugs.titles.choose_version'] = 'Version � afficher';
$LANG['bugs.titles.solved_bugs'] = 'Bugs r�solus';
$LANG['bugs.titles.unsolved_bugs'] = 'Bugs non-r�solus';
$LANG['bugs.titles.contents_value_title'] = 'Description par d�faut d\'un bug';
$LANG['bugs.titles.contents_value'] = 'Description par d�faut';

//Libell�s
$LANG['bugs.labels.fields.id'] = 'ID';
$LANG['bugs.labels.fields.title'] = 'Titre';
$LANG['bugs.labels.fields.contents'] = 'Description';
$LANG['bugs.labels.fields.author_id'] = 'D�tect� par';
$LANG['bugs.labels.fields.submit_date'] = 'D�tect� le';
$LANG['bugs.labels.fields.fix_date'] = 'Corrig� le';
$LANG['bugs.labels.fields.status'] = 'Etat';
$LANG['bugs.labels.fields.type'] = 'Type';
$LANG['bugs.labels.fields.category'] = 'Cat�gorie';
$LANG['bugs.labels.fields.reproductible'] = 'Reproductible';
$LANG['bugs.labels.fields.reproduction_method'] = 'M�thode de reproduction';
$LANG['bugs.labels.fields.severity'] = 'Niveau';
$LANG['bugs.labels.fields.priority'] = 'Priorit�';
$LANG['bugs.labels.fields.detected_in'] = 'D�tect� dans la version';
$LANG['bugs.labels.fields.fixed_in'] = 'Corrig� dans la version';
$LANG['bugs.labels.fields.assigned_to_id'] = 'Assign� �';
$LANG['bugs.labels.fields.updater_id'] = 'Modifi� par';
$LANG['bugs.labels.fields.update_date'] = 'Modifi� le';
$LANG['bugs.labels.fields.updated_field'] = 'Champ modifi�';
$LANG['bugs.labels.fields.old_value'] = 'Ancienne valeur';
$LANG['bugs.labels.fields.new_value'] = 'Nouvelle valeur';
$LANG['bugs.labels.fields.change_comment'] = 'Commentaire';
$LANG['bugs.labels.fields.version'] = 'Version';
$LANG['bugs.labels.fields.version_detected_in'] = 'Afficher dans la liste "D�tect� dans la version"';
$LANG['bugs.labels.fields.version_fixed_in'] = 'Afficher dans la liste "Corrig� dans la version"';
$LANG['bugs.labels.fields.version_detected'] = 'Version d�tect�e';
$LANG['bugs.labels.fields.version_fixed'] = 'Version corrig�e';
$LANG['bugs.labels.color'] = 'Couleur';
$LANG['bugs.labels.number'] = 'Nombre de bugs';
$LANG['bugs.labels.number_corrected'] = 'Nombre de bugs corrig�s';
$LANG['bugs.labels.top_10_posters'] = 'Top 10 : posteurs';
$LANG['bugs.labels.default'] = 'Par d�faut';
$LANG['bugs.labels.del_default_value'] = 'Supprimer la valeur par d�faut';
$LANG['bugs.labels.type_mandatory'] = 'Section "Type" obligatoire ?';
$LANG['bugs.labels.category_mandatory'] = 'Section "Cat�gorie" obligatoire ?';
$LANG['bugs.labels.severity_mandatory'] = 'Section "Niveau" obligatoire ?';
$LANG['bugs.labels.priority_mandatory'] = 'Section "Priorit�" obligatoire ?';
$LANG['bugs.labels.detected_in_mandatory'] = 'Section "D�tect� dans la version" obligatoire ?';
$LANG['bugs.labels.date_format'] = 'Format d\'affichage de la date';
$LANG['bugs.labels.date_time'] = 'Date et heure';
$LANG['bugs.labels.fixed'] = 'Corrig�';
$LANG['bugs.labels.release_date'] = 'Date de parution';

//Etats
$LANG['bugs.status.new'] = 'Nouveau';
$LANG['bugs.status.assigned'] = 'Assign�';
$LANG['bugs.status.fixed'] = 'Corrig�';
$LANG['bugs.status.reopen'] = 'R�-ouvert';
$LANG['bugs.status.rejected'] = 'Rejet�';

//Explications
$LANG['bugs.explain.contents'] = 'D�tails qui seront utiles pour la r�solution du bug';
$LANG['bugs.explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrig�s pour chaque version';
$LANG['bugs.explain.pm'] = 'Permet d\'envoyer un MP dans les cas suivants :<br />
- Commentaire sur un bug<br />
- Edition d\'un bug<br />
- Suppression d\'un bug<br />
- Assignation d\'un bug<br />
- Rejet d\'un bug<br />
- R�ouverture d\'un bug<br />';
$LANG['bugs.explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'�volution...';
$LANG['bugs.explain.category'] = 'Cat�gorie des demandes. Exemples : Noyau, Module...';
$LANG['bugs.explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$LANG['bugs.explain.priority'] = 'Priorit� des demandes. Exemples : Basse, Normale, Elev�e...';
$LANG['bugs.explain.version'] = 'Liste des versions du produit.';
$LANG['bugs.explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br /><br />';
$LANG['bugs.explain.contents_value'] = 'Entrez ci-dessous la description par d�faut � afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pr�-remplie.';

//MP
$LANG['bugs.pm.assigned.title'] = '[%s] Le bug #%d vous a �t� assign� par %s';
$LANG['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le d�tail du bug :
%s';
$LANG['bugs.pm.comment.title'] = '[%s] Le bug #%d a �t� comment� par %s';
$LANG['bugs.pm.comment.contents'] = '%s a ajout� le commentaire suivant au bug #%d :

%s

Lien vers le bug :
%s';
$LANG['bugs.pm.edit.title'] = '[%s] Le bug #%d a �t� modifi� par %s';
$LANG['bugs.pm.edit.contents'] = '%s a modifi� les champs suivants dans le bug #%d :

%s

Lien vers le bug :
%s';
$LANG['bugs.pm.reopen.title'] = '[%s] Le bug #%d a �t� r�-ouvert par %s';
$LANG['bugs.pm.reopen.contents'] = '%s a r�-ouvert le bug #%d.
Lien vers le bug :
%s';
$LANG['bugs.pm.reject.title'] = '[%s] Le bug #%d a �t� rejet� par %s';
$LANG['bugs.pm.reject.contents'] = '%s a rejet� le bug #%d.
Lien vers le bug :
%s';
$LANG['bugs.pm.delete.title'] = '[%s] Le bug #%d a �t� supprim� par %s';
$LANG['bugs.pm.delete.contents'] = '%s a supprim� le bug #%d.';

//Recherche
$LANG['bugs.search.where'] = 'O� ?';
$LANG['bugs.search.where.title'] = 'Titre';
$LANG['bugs.search.where.contents'] = 'Contenu';

//Configuration
$LANG['bugs.config.items_per_page'] = 'Nombre de bugs par page'; 
$LANG['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug "Rejet�"';
$LANG['bugs.config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug "Ferm�"';
$LANG['bugs.config.activ_com'] = 'Activer les commentaires';
$LANG['bugs.config.activ_roadmap'] = 'Activer la feuille de route';
$LANG['bugs.config.activ_cat_in_title'] = 'Afficher la cat�gorie dans le titre du bug';
$LANG['bugs.config.activ_pm'] = 'Activer l\'envoi de MP';

//Autorisations
$LANG['bugs.config.auth'] = 'Autorisations';
$LANG['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$LANG['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$LANG['bugs.config.auth.create_advanced'] = 'Autorisation avanc�e pour signaler un bug';
$LANG['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorit� du bug';
$LANG['bugs.config.auth.moderate'] = 'Autorisation de mod�ration des bugs';

//Erreurs
$LANG['bugs.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de bugs par page\"';
$LANG['bugs.error.e_no_user_assigned'] = 'Ce bug n\'a �t� assign� � aucun utilisateur, l\'�tat ne pas passer � "' . $LANG['bugs.status.assigned'] . '"';
$LANG['bugs.error.e_no_fixed_version'] = 'Veuillez s�lectionner la version de correction avant de passer � l\'�tat "' . $LANG['bugs.status.fixed'] . '"';
$LANG['bugs.error.e_config_success'] = 'La configuration a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_types_success'] = 'La liste des types a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_categories_success'] = 'La liste des cat�gories a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_severities_success'] = 'La liste des niveaux a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_priorities_success'] = 'La liste des priorit�s a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_versions_success'] = 'La liste des versions a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_edit_success'] = 'Le bug a �t� modifi� avec succ�s';
$LANG['bugs.error.e_delete_success'] = 'Le bug a �t� supprim� avec succ�s';
$LANG['bugs.error.e_reject_success'] = 'Le bug a �t� rejet�';
$LANG['bugs.error.e_reopen_success'] = 'Le bug a �t� r�-ouvert';
$LANG['bugs.error.e_unexist_bug'] = 'Ce bug n\'existe pas';

?>