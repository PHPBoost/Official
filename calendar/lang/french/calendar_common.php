<?php
/*##################################################
 *                              calendar_common.php
 *                            -------------------
 *   begin                : November 20, 2012
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

$lang = array();

//Titre du module
$lang['calendar.module_title'] = 'Calendrier';

//Messages divers
$lang['calendar.notice.no_current_action'] = 'Aucun �v�nement pour cette date';

//Actions
$lang['calendar.actions.confirm.del_event'] = 'Supprimer l\'�v�nement ?';

//Titres
$lang['calendar.titles.admin.config'] = 'Configuration';
$lang['calendar.titles.admin.authorizations'] = 'Autorisations';
$lang['calendar.titles.add_event'] = 'Ajouter un �v�nement';
$lang['calendar.titles.edit_event'] = 'Editer l\'�v�nement';
$lang['calendar.titles.events'] = 'Ev�nements';
$lang['calendar.titles.event'] = 'Ev�nement';

//Labels
$lang['calendar.labels.title'] = 'Titre';
$lang['calendar.labels.contents'] = 'Description';
$lang['calendar.labels.location'] = 'Adresse';
$lang['calendar.labels.created_by'] = 'Cr�� par';
$lang['calendar.labels.category'] = 'Cat�gorie';
$lang['calendar.labels.registration_authorized'] = 'Activer l\'inscription des membres � l\'�v�nement';
$lang['calendar.labels.max_registred_members'] = 'Nombre de participants maximum';
$lang['calendar.labels.max_registred_members.explain'] = 'Mettre 0 pour illimit�';
$lang['calendar.labels.repeat_type'] = 'R�p�ter';
$lang['calendar.labels.repeat_number'] = 'Nombre de r�p�titions';
$lang['calendar.labels.repeat.never'] = 'Jamais';
$lang['calendar.labels.repeat.daily'] = 'Tous les jours de la semaine';
$lang['calendar.labels.repeat.daily_not_weekend'] = 'Tous les jours de la semaine (du lundi au vendredi)';
$lang['calendar.labels.repeat.weekly'] = 'Toutes les semaines';
$lang['calendar.labels.repeat.monthly'] = 'Tous les mois';
$lang['calendar.labels.repeat.yearly'] = 'Tous les ans';
$lang['calendar.labels.start_date'] = 'Date de d�but';
$lang['calendar.labels.end_date'] = 'Date de fin';
$lang['calendar.labels.contribution'] = 'Contribution';
$lang['calendar.labels.contribution.explain'] = 'Vous n\'�tes pas autoris� � cr�er un �v�nement, cependant vous pouvez en proposer un. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.';
$lang['calendar.labels.contribution.description'] = 'Compl�ment de contribution';
$lang['calendar.labels.contribution.description.explain'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet �v�nement). Ce champ est facultatif.';

//Explications
$lang['calendar.explain.date'] = '<span class="text_small">(jj/mm/aa)</span>';

//Administration
$lang['calendar.config.category.color'] = 'Couleur';
$lang['calendar.config.category.manage'] = 'G�rer les cat�gories';
$lang['calendar.config.category.add'] = 'Ajouter une cat�gorie';
$lang['calendar.config.category.edit'] = 'Modifier une cat�gorie';
$lang['calendar.config.category.delete'] = 'Supprimer une cat�gorie';
$lang['calendar.config.authorizations.read'] = 'Autorisations de lecture';
$lang['calendar.config.authorizations.write'] = 'Autorisations d\'�criture';
$lang['calendar.config.authorizations.contribution'] = 'Autorisations de contribution';
$lang['calendar.config.authorizations.moderation'] = 'Autorisation de mod�ration';
//Feed name
$lang['calendar.feed.name'] = 'Ev�nements';

//Succ�s
$lang['calendar.success.config'] = 'La configuration a �t� modifi�e';

//Erreurs
$lang['calendar.error.e_unexist_event'] = 'L\'�v�nement s�lectionn� n\'existe pas';
$lang['calendar.error.e_invalid_date'] = 'La date entr�e est invalide';
$lang['calendar.error.e_invalid_start_date'] = 'La date de d�but entr�e est invalide';
$lang['calendar.error.e_invalid_end_date'] = 'La date de fin entr�e est invalide';
?>
