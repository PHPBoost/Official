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
$LANG['bugs.notice.no_version'] = 'Aucune version existante';
$LANG['bugs.notice.no_type'] = 'Aucun type n\'a �t� d�clar�';
$LANG['bugs.notice.no_category'] = 'Aucune cat�gorie n\'a �t� d�clar�e';
$LANG['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$LANG['bugs.notice.contents_update'] = 'Mise � jour du contenu';
$LANG['bugs.notice.reproduction_method_update'] = 'Mise � jour de la m�thode de reproduction';
$LANG['bugs.notice.not_defined'] = 'Non d�fini';
$LANG['bugs.notice.require_login'] = 'Veuillez saisir un pseudo !';
$LANG['bugs.notice.require_type'] = 'Veuillez saisir un type !';
$LANG['bugs.notice.require_category'] = 'Veuillez saisir une cat�gorie !';
$LANG['bugs.notice.require_version'] = 'Veuillez saisir une version !';
$LANG['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$LANG['bugs.actions'] = 'Actions';
$LANG['bugs.actions.add'] = 'Signaler un nouveau bug';
$LANG['bugs.actions.delete'] = 'Supprimer le bug';
$LANG['bugs.actions.edit'] = 'Editer le bug';
$LANG['bugs.actions.history'] = 'Historique du bug';
$LANG['bugs.actions.confirm.del_bug'] = 'Etes-vous s�r de vouloir supprimer ce bug de la liste ? (toute l\'historique associ�e sera supprim�e)';
$LANG['bugs.actions.confirm.del_version'] = 'Etes-vous s�r de vouloir supprimer cette version ?';
$LANG['bugs.actions.confirm.del_type'] = 'Etes-vous s�r de vouloir supprimer ce type ?';
$LANG['bugs.actions.confirm.del_category'] = 'Etes-vous s�r de vouloir supprimer cette cat�gorie ?';

//Titres
$LANG['bugs.titles.add_bug'] = 'Nouveau bug';
$LANG['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$LANG['bugs.titles.add_type'] = 'Ajout d\'un nouveau type';
$LANG['bugs.titles.add_category'] = 'Ajout d\'une nouvelle cat�gorie';
$LANG['bugs.titles.edit_bug'] = 'Edition du bug';
$LANG['bugs.titles.history_bug'] = 'Historique du bug';
$LANG['bugs.titles.view_bug'] = 'Affichage du bug';
$LANG['bugs.titles.bugs_list'] = 'Liste des bugs';
$LANG['bugs.titles.roadmap'] = 'Roadmap';
$LANG['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$LANG['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$LANG['bugs.titles.disponible_versions'] = 'Versions disponibles';
$LANG['bugs.titles.disponible_types'] = 'Types disponibles';
$LANG['bugs.titles.disponible_categories'] = 'Cat�gories disponibles';
$LANG['bugs.titles.admin.management'] = 'Gestion bugtracker';
$LANG['bugs.titles.admin.config'] = 'Configuration Bugtracker';
$LANG['bugs.titles.edit_type'] = 'Edition d\'un type';
$LANG['bugs.titles.edit_category'] = 'Edition d\'une cat�gorie';
$LANG['bugs.titles.edit_version'] = 'Edition d\'une version';
$LANG['bugs.titles.choose_version'] = 'Version � afficher';

//Libell�s
$LANG['bugs.labels.fields.id'] = 'ID';
$LANG['bugs.labels.fields.title'] = 'Titre';
$LANG['bugs.labels.fields.contents'] = 'Description';
$LANG['bugs.labels.fields.author_id'] = 'D�tect� par';
$LANG['bugs.labels.fields.submit_date'] = 'D�tect� le';
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

//Priorit�s
$LANG['bugs.priority.none'] = 'Aucune';
$LANG['bugs.priority.low'] = 'Basse';
$LANG['bugs.priority.normal'] = 'Normale';
$LANG['bugs.priority.high'] = 'Elev�e';
$LANG['bugs.priority.urgent'] = 'Urgente';
$LANG['bugs.priority.immediate'] = 'Imm�diate';

//Etats
$LANG['bugs.status.new'] = 'Nouveau';
$LANG['bugs.status.assigned'] = 'Assign�';
$LANG['bugs.status.fixed'] = 'Corrig�';
$LANG['bugs.status.closed'] = 'Ferm�';
$LANG['bugs.status.reopen'] = 'R�-ouvert';
$LANG['bugs.status.rejected'] = 'Rejet�';

//Importance
$LANG['bugs.severity.minor'] = 'Mineur';
$LANG['bugs.severity.major'] = 'Majeur';
$LANG['bugs.severity.critical'] = 'Bloquant';

//Configuration
$LANG['bugs.config.items_per_page'] = 'Nombre de bugs par page'; 
$LANG['bugs.config.severity_color_label'] = 'Couleur du niveau d\'un bug';
$LANG['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug "Rejet�"';
$LANG['bugs.config.closed_bug_color_label'] = 'Couleur de la ligne d\'un bug "Ferm�"';
$LANG['bugs.config.activ_com'] = 'Activer les commentaires';

//Explications
$LANG['bugs.explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'�volution...<br />
<br />
Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br />';
$LANG['bugs.explain.category'] = 'Cat�gorie des demandes. Exemples : Noyau, Module...<br />
<br />
Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br />';
$LANG['bugs.explain.version'] = 'Liste des versions du produit.<br />
<br />
Remarques :<br />
- Si la liste est vide, l\'option "D�tect� dans la version" ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribu�e par d�faut au bug<br />';
$LANG['bugs.explain.default_content'] = 'Merci de nous donner les informations demand�es ci-dessous, elles nous seront utiles pour la r�solution du bug :
Syst�me d\'exploitation :
Navigateur Web :
Version de test (Site de test, archive zip) :
Installation locale ou sur un serveur web ? :
Lien :
----------------------------------------------------------------------
';

//MP
$LANG['bugs.pm.assigned.title'] = '[%s] Le bug #%d vous a �t� assign�';
$LANG['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le d�tail du bug :
%s';

//Recherche
$LANG['bugs.search.where'] = 'O� ?';
$LANG['bugs.search.where.title'] = 'Titre';
$LANG['bugs.search.where.contents'] = 'Contenu';

//Autorisations
$LANG['bugs.config.auth'] = 'Autorisations';
$LANG['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$LANG['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$LANG['bugs.config.auth.create_advanced'] = 'Autorisation avanc�es pour signaler un bug';
$LANG['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorit� du bug';

//Erreurs
$LANG['bugs.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de bugs par page\"';
$LANG['bugs.error.e_no_user_assigned'] = 'Ce bug n\'a �t� assign� � aucun utilisateur, l\�tat ne pas passer � "' . $LANG['bugs.status.assigned'] . '"';
$LANG['bugs.error.e_no_closed_version'] = 'Veuillez s�lectionner la version de correction avant de passer � l\'�tat "' . $LANG['bugs.status.closed'] . '"';
$LANG['bugs.error.e_config_success'] = 'La configuration a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_edit_success'] = 'Le bug a �t� modifi� avec succ�s';
$LANG['bugs.error.e_edit_type_success'] = 'Le type a �t� modifi� avec succ�s';
$LANG['bugs.error.e_edit_category_success'] = 'La cat�gorie a �t� modifi�e avec succ�s';
$LANG['bugs.error.e_edit_version_success'] = 'La version a �t� modifi� avec succ�s';
?>