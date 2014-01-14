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
$lang['bugs.module_title'] = 'Rapport de bugs';

//Messages divers
$lang['bugs.notice.no_one'] = 'Personne';
$lang['bugs.notice.none'] = 'Aucun';
$lang['bugs.notice.none_e'] = 'Aucune';
$lang['bugs.notice.no_bug'] = 'Aucun bug n\'a �t� d�clar�';
$lang['bugs.notice.no_bug_solved'] = 'Aucun bug n\'a �t� corrig�';
$lang['bugs.notice.no_bug_fixed'] = 'Aucun bug n\'a �t� corrig� dans cette version';
$lang['bugs.notice.no_bug_in_progress'] = 'Aucun bug n\'est en cours de correction dans cette version';
$lang['bugs.notice.no_bug_matching_filter'] = 'Aucun bug ne correspond au filtre s�lectionn�';
$lang['bugs.notice.no_bug_matching_filters'] = 'Aucun bug ne correspond aux filtres s�lectionn�s';
$lang['bugs.notice.no_version'] = 'Aucune version existante';
$lang['bugs.notice.no_type'] = 'Aucun type existant';
$lang['bugs.notice.no_category'] = 'Aucune cat�gorie existante';
$lang['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$lang['bugs.notice.contents_update'] = 'Mise � jour du contenu';
$lang['bugs.notice.new_comment'] = 'Nouveau commentaire';
$lang['bugs.notice.reproduction_method_update'] = 'Mise � jour de la m�thode de reproduction';
$lang['bugs.notice.not_defined'] = 'Non d�fini';
$lang['bugs.notice.not_defined_e_date'] = 'Date non d�finie';
$lang['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$lang['bugs.actions'] = 'Actions';
$lang['bugs.actions.add'] = 'Nouveau bug';
$lang['bugs.actions.delete'] = 'Supprimer le bug';
$lang['bugs.actions.edit'] = 'Editer le bug';
$lang['bugs.actions.history'] = 'Historique du bug';
$lang['bugs.actions.reject'] = 'Rejeter le bug';
$lang['bugs.actions.reopen'] = 'R�-ouvrir le bug';
$lang['bugs.actions.confirm.reopen_bug'] = 'Etes-vous s�r de vouloir r�-ouvrir ce bug ?';
$lang['bugs.actions.confirm.reject_bug'] = 'Etes-vous s�r de vouloir rejeter ce bug ?';
$lang['bugs.actions.confirm.del_version'] = 'Etes-vous s�r de vouloir supprimer cette version ?';
$lang['bugs.actions.confirm.del_type'] = 'Etes-vous s�r de vouloir supprimer ce type ?';
$lang['bugs.actions.confirm.del_category'] = 'Etes-vous s�r de vouloir supprimer cette cat�gorie ?';
$lang['bugs.actions.confirm.del_priority'] = 'Etes-vous s�r de vouloir supprimer cette priorit� ?';
$lang['bugs.actions.confirm.del_severity'] = 'Etes-vous s�r de vouloir supprimer ce niveau ?';
$lang['bugs.actions.confirm.del_default_value'] = 'Etes-vous s�r de vouloir la valeur par d�faut ?';
$lang['bugs.actions.confirm.del_filter'] = 'Etes-vous s�r de vouloir supprimer ce filtre ?';

//Titres
$lang['bugs.titles.add'] = 'Nouveau bug';
$lang['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$lang['bugs.titles.add_type'] = 'Ajouter un nouveau type de bug';
$lang['bugs.titles.add_category'] = 'Ajouter une nouvelle cat�gorie';
$lang['bugs.titles.edit'] = 'Edition du bug';
$lang['bugs.titles.history'] = 'Historique';
$lang['bugs.titles.detail'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Feuille de route';
$lang['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$lang['bugs.titles.stats'] = 'Statistiques';
$lang['bugs.titles.bugs_treatment'] = 'Traitement du bug';
$lang['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$lang['bugs.titles.versions'] = 'Versions';
$lang['bugs.titles.types'] = 'Types';
$lang['bugs.titles.categories'] = 'Cat�gories';
$lang['bugs.titles.priorities'] = 'Priorit�s';
$lang['bugs.titles.severities'] = 'Niveaux';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Autorisations';
$lang['bugs.titles.admin.authorizations.manage'] = 'G�rer les autorisations';
$lang['bugs.titles.admin.module_config'] = 'Configuration du module bugtracker';
$lang['bugs.titles.admin.module_authorizations'] = 'Configuration des autorisations du module bugtracker';
$lang['bugs.titles.choose_version'] = 'Version � afficher';
$lang['bugs.titles.solved'] = 'Bugs r�solus';
$lang['bugs.titles.unsolved'] = 'Bugs non-r�solus';
$lang['bugs.titles.contents_value_title'] = 'Description par d�faut d\'un bug';
$lang['bugs.titles.contents_value'] = 'Description par d�faut';
$lang['bugs.titles.filter'] = 'Filtre';
$lang['bugs.titles.filters'] = 'Filtres';
$lang['bugs.titles.legend'] = 'L�gende';
$lang['bugs.titles.informations'] = 'Informations';
$lang['bugs.titles.version_informations'] = 'Informations sur la version';

//Libell�s
$lang['bugs.labels.fields.id'] = 'ID';
$lang['bugs.labels.fields.title'] = 'Titre';
$lang['bugs.labels.fields.contents'] = 'Description';
$lang['bugs.labels.fields.author_id'] = 'D�tect� par';
$lang['bugs.labels.fields.submit_date'] = 'D�tect� le';
$lang['bugs.labels.fields.fix_date'] = 'Corrig� le';
$lang['bugs.labels.fields.status'] = 'Etat';
$lang['bugs.labels.fields.type'] = 'Type';
$lang['bugs.labels.fields.category'] = 'Cat�gorie';
$lang['bugs.labels.fields.reproductible'] = 'Reproductible';
$lang['bugs.labels.fields.reproduction_method'] = 'M�thode de reproduction';
$lang['bugs.labels.fields.severity'] = 'Niveau';
$lang['bugs.labels.fields.priority'] = 'Priorit�';
$lang['bugs.labels.fields.progress'] = 'Avancement';
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
$lang['bugs.labels.fields.version_detected'] = 'Version d�tect�e';
$lang['bugs.labels.fields.version_fixed'] = 'Version corrig�e';
$lang['bugs.labels.fields.version_release_date'] = 'Date de sortie';
$lang['bugs.labels.page'] = 'Page';
$lang['bugs.labels.color'] = 'Couleur';
$lang['bugs.labels.number'] = 'Nombre de bugs';
$lang['bugs.labels.number_fixed'] = 'Nombre de bugs corrig�s';
$lang['bugs.labels.number_in_progress'] = 'Nombre de bugs en cours de correction';
$lang['bugs.labels.top_posters'] = 'Top posteurs';
$lang['bugs.labels.login'] = 'Pseudo';
$lang['bugs.labels.default'] = 'Par d�faut';
$lang['bugs.labels.default_value'] = 'Valeur par d�faut';
$lang['bugs.labels.del_default_value'] = 'Supprimer la valeur par d�faut';
$lang['bugs.labels.type_mandatory'] = 'Section <b>Type</b> obligatoire ?';
$lang['bugs.labels.category_mandatory'] = 'Section <b>Cat�gorie</b> obligatoire ?';
$lang['bugs.labels.severity_mandatory'] = 'Section <b>Niveau</b> obligatoire ?';
$lang['bugs.labels.priority_mandatory'] = 'Section <b>Priorit�</b> obligatoire ?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section <b>D�tect� dans la version</b> obligatoire ?';
$lang['bugs.labels.date_format'] = 'Format d\'affichage de la date';
$lang['bugs.labels.date_time'] = 'Date et heure';
$lang['bugs.labels.detected'] = 'D�tect�';
$lang['bugs.labels.fixed'] = 'Corrig�';
$lang['bugs.labels.fix_bugs_per_version'] = 'Nombre de bugs corrig�s par version';
$lang['bugs.labels.release_date'] = 'Date de parution';
$lang['bugs.labels.not_yet_fixed'] = 'Pas encore corrig�';
$lang['bugs.labels.alert_fix'] = 'Passer l\'alerte en r�gl�';
$lang['bugs.labels.alert_delete'] = 'Supprimer l\'alerte';
$lang['bugs.labels.matching_selected_filter'] = 'correspondants au filtre s�lectionn�';
$lang['bugs.labels.matching_selected_filters'] = 'correspondants aux filtres s�lectionn�s';
$lang['bugs.labels.save_filters'] = 'Sauvegarder les filtres';
$lang['bugs.labels.version_name'] = 'Nom de la version';

//Etats
$lang['bugs.status.new'] = 'Nouveau';
$lang['bugs.status.assigned'] = 'Assign�';
$lang['bugs.status.in_progress'] = 'En cours';
$lang['bugs.status.fixed'] = 'Corrig�';
$lang['bugs.status.reopen'] = 'R�ouvert';
$lang['bugs.status.rejected'] = 'Rejet�';

//Explications
$lang['bugs.explain.contents'] = 'D�tails qui seront utiles pour la r�solution du bug';
$lang['bugs.explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrig�s pour chaque version. Affich�e s\'il y a au moins une version dans la liste.';
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
$lang['bugs.pm.assigned.title'] = '[Rapport de bugs] Le bug #:id vous a �t� assign� par :author';
$lang['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le d�tail du bug :
:link';
$lang['bugs.pm.comment.title'] = '[Rapport de bugs] Le bug #:id a �t� comment� par :author';
$lang['bugs.pm.comment.contents'] = ':author a ajout� le commentaire suivant au bug #:id :

:comment

Lien vers le bug :
:link';
$lang['bugs.pm.edit.title'] = '[Rapport de bugs] Le bug #:id a �t� modifi� par :author';
$lang['bugs.pm.edit.contents'] = ':author a modifi� les champs suivants dans le bug #:id :

:fields

Lien vers le bug :
:link';
$lang['bugs.pm.reopen.title'] = '[Rapport de bugs] Le bug #:id a �t� r�-ouvert par :author';
$lang['bugs.pm.reopen.contents'] = ':author a r�-ouvert le bug #:id.
Lien vers le bug :
:link';
$lang['bugs.pm.reject.title'] = '[Rapport de bugs] Le bug #:id a �t� rejet� par :author';
$lang['bugs.pm.reject.contents'] = ':author a rejet� le bug #:id.
Lien vers le bug :
:link';
$lang['bugs.pm.delete.title'] = '[Rapport de bugs] Le bug #:id a �t� supprim� par :author';
$lang['bugs.pm.delete.contents'] = ':author a supprim� le bug #:id.';

//Recherche
$lang['bugs.search.where'] = 'O� ?';
$lang['bugs.search.where.title'] = 'Titre';
$lang['bugs.search.where.contents'] = 'Description';

//Configuration
$lang['bugs.config.items_per_page'] = 'Nombre de bugs affich�s par page'; 
$lang['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug <b>Rejet�</b>';
$lang['bugs.config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug <b>Ferm�</b>';
$lang['bugs.config.activ_com'] = 'Activer les commentaires';
$lang['bugs.config.activ_roadmap'] = 'Activer la feuille de route';
$lang['bugs.config.activ_stats'] = 'Activer les statistiques';
$lang['bugs.config.activ_stats_top_posters'] = 'Afficher la liste des membres qui ont post� le plus de bugs';
$lang['bugs.config.stats_top_posters_number'] = 'Nombre d\'utilisateurs affich�s';
$lang['bugs.config.activ_progress_bar'] = 'Afficher la barre de progression des bugs';
$lang['bugs.config.activ_admin_alerts'] = 'Activer les alertes administrateur';
$lang['bugs.config.admin_alerts_levels'] = 'Niveau du bug pour d�clencher l\'alerte';
$lang['bugs.config.admin_alerts_fix_action'] = 'Action � la fermeture d\'un bug';
$lang['bugs.config.activ_cat_in_title'] = 'Afficher la cat�gorie dans le titre du bug';
$lang['bugs.config.pm'] = 'Messages Priv�s';
$lang['bugs.config.activ_pm'] = 'Activer l\'envoi de Messages Priv�s (MP)';
$lang['bugs.config.activ_pm.comment'] = 'Envoyer un MP lors de l\'ajout d\'un nouveau commentaire';
$lang['bugs.config.activ_pm.assign'] = 'Envoyer un MP lors de l\'assignation d\'un bug';
$lang['bugs.config.activ_pm.edit'] = 'Envoyer un MP lors de l\'�dition d\'un bug';
$lang['bugs.config.activ_pm.reject'] = 'Envoyer un MP lors du rejet d\'un bug';
$lang['bugs.config.activ_pm.reopen'] = 'Envoyer un MP lors de la r�ouverture d\'un bug';
$lang['bugs.config.activ_pm.delete'] = 'Envoyer un MP lors de la suppression d\'un bug';

//Autorisations
$lang['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$lang['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$lang['bugs.config.auth.create_advanced'] = 'Autorisation avanc�e pour signaler un bug';
$lang['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorit� du bug';
$lang['bugs.config.auth.moderate'] = 'Autorisation de mod�ration des bugs';

//Erreurs
$lang['bugs.error.e_no_fixed_version'] = 'Veuillez s�lectionner la version de correction avant de passer � l\'�tat "' . $lang['bugs.status.fixed'] . '"';
$lang['bugs.error.e_unexist_bug'] = 'Ce bug n\'existe pas';
$lang['bugs.error.e_unexist_parameter'] = 'Ce param�tre n\'existe pas';
$lang['bugs.error.e_unexist_type'] = 'Ce type n\'existe pas';
$lang['bugs.error.e_unexist_category'] = 'Cette cat�gorie n\'existe pas';
$lang['bugs.error.e_unexist_severity'] = 'Ce niveau n\'existe pas';
$lang['bugs.error.e_unexist_priority'] = 'Cette priorit� n\'existe pas';
$lang['bugs.error.e_unexist_version'] = 'Cette version n\'existe pas';
$lang['bugs.error.e_already_rejected_bug'] = 'Ce bug est d�j� rejet�';
$lang['bugs.error.e_already_reopen_bug'] = 'Ce bug est d�j� r�-ouvert';
$lang['bugs.error.e_unexist_pm_type'] = 'Ce type de MP n\'existe pas';

//Succ�s
$lang['bugs.success.add'] = 'Le bug #:id a �t� ajout�';
$lang['bugs.success.edit'] = 'Le bug #:id a �t� modifi�';
$lang['bugs.success.fixed'] = 'Le bug #:id a �t� corrig�';
$lang['bugs.success.delete'] = 'Le bug #:id a �t� supprim�';
$lang['bugs.success.reject'] = 'Le bug #:id a �t� rejet�';
$lang['bugs.success.reopen'] = 'Le bug #:id a �t� r�-ouvert';
?>
