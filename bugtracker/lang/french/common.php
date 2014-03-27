<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : November 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
$lang['module_title'] = 'Rapport de bugs';

//Messages divers
$lang['notice.no_one'] = 'Personne';
$lang['notice.none'] = 'Aucun';
$lang['notice.none_e'] = 'Aucune';
$lang['notice.no_bug'] = 'Aucun bug n\'a �t� d�clar�';
$lang['notice.no_bug_solved'] = 'Aucun bug n\'a �t� corrig�';
$lang['notice.no_bug_fixed'] = 'Aucun bug n\'a �t� corrig� dans cette version';
$lang['notice.no_bug_in_progress'] = 'Aucun bug n\'est en cours de correction dans cette version';
$lang['notice.no_bug_matching_filter'] = 'Aucun bug ne correspond au filtre s�lectionn�';
$lang['notice.no_bug_matching_filters'] = 'Aucun bug ne correspond aux filtres s�lectionn�s';
$lang['notice.no_version_roadmap'] = 'Veuillez ajouter au moins une version dans la configuration pour afficher la feuille de route.';
$lang['notice.no_version'] = 'Aucune version existante';
$lang['notice.no_type'] = 'Aucun type existant';
$lang['notice.no_category'] = 'Aucune cat�gorie existante';
$lang['notice.no_history'] = 'Ce bug n\'a aucun historique';
$lang['notice.contents_update'] = 'Mise � jour du contenu';
$lang['notice.new_comment'] = 'Nouveau commentaire';
$lang['notice.reproduction_method_update'] = 'Mise � jour de la m�thode de reproduction';
$lang['notice.not_defined'] = 'Non d�fini';
$lang['notice.not_defined_e_date'] = 'Date non d�finie';

//Actions
$lang['actions'] = 'Actions';
$lang['actions.add'] = 'Nouveau bug';
$lang['actions.history'] = 'Historique';
$lang['actions.change_status'] = 'Changer l\'�tat du bug';
$lang['actions.confirm.del_version'] = 'Etes-vous s�r de vouloir supprimer cette version ?';
$lang['actions.confirm.del_type'] = 'Etes-vous s�r de vouloir supprimer ce type ?';
$lang['actions.confirm.del_category'] = 'Etes-vous s�r de vouloir supprimer cette cat�gorie ?';
$lang['actions.confirm.del_priority'] = 'Etes-vous s�r de vouloir supprimer cette priorit� ?';
$lang['actions.confirm.del_severity'] = 'Etes-vous s�r de vouloir supprimer ce niveau ?';
$lang['actions.confirm.del_default_value'] = 'Etes-vous s�r de vouloir la valeur par d�faut ?';
$lang['actions.confirm.del_filter'] = 'Etes-vous s�r de vouloir supprimer ce filtre ?';

//Titres
$lang['titles.add'] = 'Nouveau bug';
$lang['titles.add_version'] = 'Ajout d\'une nouvelle version';
$lang['titles.add_type'] = 'Ajouter un nouveau type de bug';
$lang['titles.add_category'] = 'Ajouter une nouvelle cat�gorie';
$lang['titles.edit'] = 'Edition du bug';
$lang['titles.change_status'] = 'Changement d\'�tat du bug';
$lang['titles.delete'] = 'Suppression du bug';
$lang['titles.history'] = 'Historique du bug';
$lang['titles.detail'] = 'Bug';
$lang['titles.roadmap'] = 'Feuille de route';
$lang['titles.bugs_infos'] = 'Informations sur le bug';
$lang['titles.stats'] = 'Statistiques';
$lang['titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$lang['titles.versions'] = 'Versions';
$lang['titles.types'] = 'Types';
$lang['titles.categories'] = 'Cat�gories';
$lang['titles.priorities'] = 'Priorit�s';
$lang['titles.severities'] = 'Niveaux';
$lang['titles.admin.config'] = 'Configuration';
$lang['titles.admin.authorizations'] = 'Autorisations';
$lang['titles.admin.authorizations.manage'] = 'G�rer les autorisations';
$lang['titles.admin.module_config'] = 'Configuration du module bugtracker';
$lang['titles.admin.module_authorizations'] = 'Configuration des autorisations du module bugtracker';
$lang['titles.choose_version'] = 'Version � afficher';
$lang['titles.solved'] = 'Bugs r�solus';
$lang['titles.unsolved'] = 'Bugs non-r�solus';
$lang['titles.contents_value_title'] = 'Description par d�faut d\'un bug';
$lang['titles.contents_value'] = 'Description par d�faut';
$lang['titles.filter'] = 'Filtre';
$lang['titles.filters'] = 'Filtres';
$lang['titles.legend'] = 'L�gende';
$lang['titles.informations'] = 'Informations';
$lang['titles.version_informations'] = 'Informations sur la version';

//Libell�s
$lang['labels.fields.id'] = 'ID';
$lang['labels.fields.title'] = 'Titre';
$lang['labels.fields.contents'] = 'Description';
$lang['labels.fields.submit_date'] = 'D�tect� le';
$lang['labels.fields.fix_date'] = 'Corrig� le';
$lang['labels.fields.status'] = 'Etat';
$lang['labels.fields.type'] = 'Type';
$lang['labels.fields.category'] = 'Cat�gorie';
$lang['labels.fields.reproductible'] = 'Reproductible';
$lang['labels.fields.reproduction_method'] = 'M�thode de reproduction';
$lang['labels.fields.severity'] = 'Niveau';
$lang['labels.fields.priority'] = 'Priorit�';
$lang['labels.fields.progress'] = 'Avancement';
$lang['labels.fields.detected_in'] = 'D�tect� dans la version';
$lang['labels.fields.fixed_in'] = 'Corrig� dans la version';
$lang['labels.fields.assigned_to_id'] = 'Assign� �';
$lang['labels.fields.updater_id'] = 'Modifi� par';
$lang['labels.fields.update_date'] = 'Modifi� le';
$lang['labels.fields.updated_field'] = 'Champ modifi�';
$lang['labels.fields.old_value'] = 'Ancienne valeur';
$lang['labels.fields.new_value'] = 'Nouvelle valeur';
$lang['labels.fields.change_comment'] = 'Commentaire';
$lang['labels.fields.version'] = 'Version';
$lang['labels.fields.version_detected'] = 'Version d�tect�e';
$lang['labels.fields.version_fixed'] = 'Version corrig�e';
$lang['labels.fields.version_release_date'] = 'Date de sortie';
$lang['labels.page'] = 'Page';
$lang['labels.color'] = 'Couleur';
$lang['labels.number'] = 'Nombre de bugs';
$lang['labels.number_fixed'] = 'Nombre de bugs corrig�s';
$lang['labels.number_in_progress'] = 'Nombre de bugs en cours de correction';
$lang['labels.top_posters'] = 'Top posteurs';
$lang['labels.login'] = 'Pseudo';
$lang['labels.default'] = 'Par d�faut';
$lang['labels.default_value'] = 'Valeur par d�faut';
$lang['labels.del_default_value'] = 'Supprimer la valeur par d�faut';
$lang['labels.type_mandatory'] = 'Section <b>Type</b> obligatoire ?';
$lang['labels.category_mandatory'] = 'Section <b>Cat�gorie</b> obligatoire ?';
$lang['labels.severity_mandatory'] = 'Section <b>Niveau</b> obligatoire ?';
$lang['labels.priority_mandatory'] = 'Section <b>Priorit�</b> obligatoire ?';
$lang['labels.detected_in_mandatory'] = 'Section <b>D�tect� dans la version</b> obligatoire ?';
$lang['labels.detected'] = 'D�tect�';
$lang['labels.detected_in'] = 'D�tect� dans';
$lang['labels.fixed'] = 'Corrig�';
$lang['labels.fix_bugs_per_version'] = 'Nombre de bugs corrig�s par version';
$lang['labels.release_date'] = 'Date de parution';
$lang['labels.not_yet_fixed'] = 'Pas encore corrig�';
$lang['labels.alert_fix'] = 'Passer l\'alerte en r�gl�';
$lang['labels.alert_delete'] = 'Supprimer l\'alerte';
$lang['labels.matching_selected_filter'] = 'correspondants au filtre s�lectionn�';
$lang['labels.matching_selected_filters'] = 'correspondants aux filtres s�lectionn�s';
$lang['labels.save_filters'] = 'Sauvegarder les filtres';
$lang['labels.version_name'] = 'Nom de la version';

//Etats
$lang['status.new'] = 'Nouveau';
$lang['status.pending'] = 'En attente';
$lang['status.assigned'] = 'Assign�';
$lang['status.in_progress'] = 'En cours';
$lang['status.fixed'] = 'Corrig�';
$lang['status.reopen'] = 'R�ouvert';
$lang['status.rejected'] = 'Rejet�';

//Explications
$lang['explain.contents'] = 'D�tails qui seront utiles pour la r�solution du bug';
$lang['explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrig�s pour chaque version. Affich�e s\'il y a au moins une version dans la liste.';
$lang['explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'�volution...';
$lang['explain.category'] = 'Cat�gorie des demandes. Exemples : Noyau, Module...';
$lang['explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$lang['explain.priority'] = 'Priorit� des demandes. Exemples : Basse, Normale, Elev�e...';
$lang['explain.version'] = 'Liste des versions du produit.';
$lang['explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br /><br />';
$lang['explain.contents_value'] = 'Entrez ci-dessous la description par d�faut � afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pr�-remplie.';
$lang['explain.delete_comment'] = 'Facultatif. Permet d\'ajouter un commentaire dans le Message Priv� de suppression du bug.';
$lang['explain.change_status_select_fix_version'] = 'Vous pouvez s�lectionner une version pour que le bug soit pr�sent dans la feuille de route.';
$lang['explain.change_status_comments_message'] = 'Facultatif. Permet de commenter le bug et d\'ajouter ce commentaire dans le Message Priv� si son envoi est activ�.';

//MP
$lang['pm.with_comment'] = '

Commentaire :
:comment';
$lang['pm.edit_fields'] = '

:fields';
$lang['pm.bug_link'] = '

<a href=":link">Lien vers le bug</a>';

$lang['pm.assigned.title'] = '[Rapport de bugs] Le bug #:id vous a �t� assign�';
$lang['pm.assigned.contents'] = ':author vous a assign� le bug #:id.';

$lang['pm.comment.title'] = '[Rapport de bugs] Le bug #:id a �t� comment�';
$lang['pm.comment.contents'] = ':author a ajout� un commentaire au bug #:id.';

$lang['pm.edit.title'] = '[Rapport de bugs] Le bug #:id a �t� modifi�';
$lang['pm.edit.contents'] = ':author a modifi� les champs suivants dans le bug #:id :';

$lang['pm.fixed.title'] = '[Rapport de bugs] Le bug #:id a �t� corrig�';
$lang['pm.fixed.contents'] = ':author a corrig� le bug #:id.';

$lang['pm.reopen.title'] = '[Rapport de bugs] Le bug #:id a �t� r�-ouvert';
$lang['pm.reopen.contents'] = ':author a r�-ouvert le bug #:id.';

$lang['pm.rejected.title'] = '[Rapport de bugs] Le bug #:id a �t� rejet�';
$lang['pm.rejected.contents'] = ':author a rejet� le bug #:id.';

$lang['pm.pending.title'] = '[Rapport de bugs] Le bug #:id a �t� mis en attente';
$lang['pm.pending.contents'] = ':author a mis en attente le bug #:id.';

$lang['pm.in_progress.title'] = '[Rapport de bugs] Le bug #:id est en cours de correction';
$lang['pm.in_progress.contents'] = ':author a mis le bug #:id en cours de correction.';

$lang['pm.delete.title'] = '[Rapport de bugs] Le bug #:id a �t� supprim�';
$lang['pm.delete.contents'] = ':author a supprim� le bug #:id.';

//Recherche
$lang['search.where'] = 'O� ?';
$lang['search.where.title'] = 'Titre';
$lang['search.where.contents'] = 'Description';

//Configuration
$lang['config.items_per_page'] = 'Nombre de bugs affich�s par page'; 
$lang['config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug <b>Rejet�</b>';
$lang['config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug <b>Ferm�</b>';
$lang['config.activ_roadmap'] = 'Activer la feuille de route';
$lang['config.activ_stats'] = 'Activer les statistiques';
$lang['config.activ_stats_top_posters'] = 'Afficher la liste des membres qui ont post� le plus de bugs';
$lang['config.stats_top_posters_number'] = 'Nombre d\'utilisateurs affich�s';
$lang['config.progress_bar'] = 'Barre de progression';
$lang['config.activ_progress_bar'] = 'Afficher la barre de progression des bugs';
$lang['config.status.new'] = 'Pourcentage d\'un <b>Nouveau</b> bug';
$lang['config.status.pending'] = 'Pourcentage d\'un bug <b>En attente</b>';
$lang['config.status.assigned'] = 'Pourcentage d\'un bug <b>Assign�</b>';
$lang['config.status.in_progress'] = 'Pourcentage d\'un bug <b>En cours</b>';
$lang['config.status.fixed'] = 'Pourcentage d\'un bug <b>Corrig�</b>';
$lang['config.status.reopen'] = 'Pourcentage d\'un bug <b>R�ouvert</b>';
$lang['config.status.rejected'] = 'Pourcentage d\'un bug <b>Rejet�</b>';
$lang['config.admin_alerts'] = 'Alertes administrateur';
$lang['config.activ_admin_alerts'] = 'Activer les alertes administrateur';
$lang['config.admin_alerts_levels'] = 'Niveau du bug pour d�clencher l\'alerte';
$lang['config.admin_alerts_fix_action'] = 'Action � la fermeture d\'un bug';
$lang['config.pm'] = 'Messages Priv�s';
$lang['config.activ_pm'] = 'Activer l\'envoi de Messages Priv�s (MP)';
$lang['config.activ_pm.comment'] = 'Envoyer un MP lors de l\'ajout d\'un nouveau commentaire';
$lang['config.activ_pm.in_progress'] = 'Envoyer un MP lors du passage � l\'�tat <b>En cours</b>';
$lang['config.activ_pm.fix'] = 'Envoyer un MP lors de la correction d\'un bug';
$lang['config.activ_pm.pending'] = 'Envoyer un MP lors de la mise en attente d\'un bug';
$lang['config.activ_pm.assign'] = 'Envoyer un MP lors de l\'assignation d\'un bug';
$lang['config.activ_pm.edit'] = 'Envoyer un MP lors de l\'�dition d\'un bug';
$lang['config.activ_pm.reject'] = 'Envoyer un MP lors du rejet d\'un bug';
$lang['config.activ_pm.reopen'] = 'Envoyer un MP lors de la r�ouverture d\'un bug';
$lang['config.activ_pm.delete'] = 'Envoyer un MP lors de la suppression d\'un bug';
$lang['config.delete_parameter.type'] = 'Suppression d\'un type';
$lang['config.delete_parameter.category'] = 'Suppression d\'une cat�gorie';
$lang['config.delete_parameter.version'] = 'Suppression d\'une version';
$lang['config.delete_parameter.description.type'] = 'Vous �tes sur le point de supprimer un type de bug. Deux solutions s\'offrent � vous. Vous pouvez soit affecter un autre type � l\'ensemble des bugs associ�s � ce type, soit supprimer l\'ensemble des bugs associ�s � ce type. Si aucune action n\'est choisie sur cette page, le type de bug sera supprim� et les bugs conserv�s (en supprimant leur type). <strong>Attention, cette action est irr�versible !</strong>';
$lang['config.delete_parameter.description.category'] = 'Vous �tes sur le point de supprimer une cat�gorie de bug. Deux solutions s\'offrent � vous. Vous pouvez soit affecter une autre cat�gorie � l\'ensemble des bugs associ�s � cette cat�gorie, soit supprimer l\'ensemble des bugs associ�s � cette cat�gorie. Si aucune action n\'est choisie sur cette page, la cat�gorie sera supprim�e et les bugs conserv�s (en supprimant leur cat�gorie). <strong>Attention, cette action est irr�versible !</strong>';
$lang['config.delete_parameter.description.version'] = 'Vous �tes sur le point de supprimer une version. Deux solutions s\'offrent � vous. Vous pouvez soit affecter une autre version � l\'ensemble des bugs associ�s � cette version, soit supprimer l\'ensemble des bugs associ�s � cette version. Si aucune action n\'est choisie sur cette page, la version sera supprim�e et les bugs conserv�s (en supprimant leur version). <strong>Attention, cette action est irr�versible !</strong>';
$lang['config.delete_parameter.move_into_another'] = 'D�placer les bugs associ�s dans :';
$lang['config.delete_parameter.parameter_and_content.type'] = 'Supprimer le type de bug et tous les bugs associ�s';
$lang['config.delete_parameter.parameter_and_content.category'] = 'Supprimer la cat�gorie et tous les bugs associ�s';
$lang['config.delete_parameter.parameter_and_content.version'] = 'Supprimer la version et tous les bugs associ�s';
$lang['config.display_type_column'] = 'Afficher la colonne <b>Type</b> dans les tableaux';
$lang['config.display_category_column'] = 'Afficher la colonne <b>Cat�gorie</b> dans les tableaux';
$lang['config.display_priority_column'] = 'Afficher la colonne <b>Priorit�</b> dans les tableaux';
$lang['config.display_detected_in_column'] = 'Afficher la colonne <b>D�tect� dans</b> dans les tableaux';

//Autorisations
$lang['config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$lang['config.auth.create'] = 'Autorisation de signaler un bug';
$lang['config.auth.create_advanced'] = 'Autorisation avanc�e pour signaler un bug';
$lang['config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorit� du bug';
$lang['config.auth.moderate'] = 'Autorisation de mod�ration des bugs';

//Erreurs
$lang['error.e_unexist_bug'] = 'Ce bug n\'existe pas';
$lang['error.e_unexist_parameter'] = 'Ce param�tre n\'existe pas';
$lang['error.e_unexist_type'] = 'Ce type n\'existe pas';
$lang['error.e_unexist_category'] = 'Cette cat�gorie n\'existe pas';
$lang['error.e_unexist_severity'] = 'Ce niveau n\'existe pas';
$lang['error.e_unexist_priority'] = 'Cette priorit� n\'existe pas';
$lang['error.e_unexist_version'] = 'Cette version n\'existe pas';
$lang['error.e_already_rejected_bug'] = 'Ce bug est d�j� rejet�';
$lang['error.e_already_reopen_bug'] = 'Ce bug est d�j� r�-ouvert';
$lang['error.e_already_fixed_bug'] = 'Ce bug est d�j� corrig�';
$lang['error.e_already_pending_bug'] = 'Ce bug est d�j� en attente';
$lang['error.e_status_not_changed'] = 'Veuillez changer l\'�tat du bug';

//Succ�s
$lang['success.add'] = 'Le bug #:id a �t� ajout�';
$lang['success.edit'] = 'Le bug #:id a �t� modifi�';
$lang['success.new'] = 'Le bug #:id a �t� pass� � l\'�tat <b>Nouveau</b>';
$lang['success.fixed'] = 'Le bug #:id a �t� corrig�';
$lang['success.in_progress'] = 'Le bug #:id est en cours de r�solution';
$lang['success.delete'] = 'Le bug #:id a �t� supprim�';
$lang['success.reject'] = 'Le bug #:id a �t� rejet�';
$lang['success.reopen'] = 'Le bug #:id a �t� r�-ouvert';
$lang['success.assigned'] = 'Le bug #:id a �t� assign�';
$lang['success.pending'] = 'Le bug #:id a �t� mis en attente';
?>
