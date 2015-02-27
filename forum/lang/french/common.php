<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
 ####################################################

$lang['module_title'] = 'Forum';
$lang['module_config_title'] = 'Configuration du module forum';

$lang['forum.actions.add_rank'] = 'Ajouter un rang';
$lang['forum.manage_ranks'] = 'G�rer les rangs';
$lang['forum.ranks_management'] = 'Gestion des rangs';

//config
$lang['config.forum_name'] = 'Nom du forum';
$lang['config.number_topics_per_page'] = 'Nombre de sujets par page';
$lang['config.number_messages_per_page'] = 'Nombre de messages par page';
$lang['config.read_messages_storage_duration'] = 'Dur�e pour laquelle les messages lus par les membres sont stock�s';
$lang['config.read_messages_storage_duration.explain'] = 'En jours. A r�gler suivant le nombre de messages par jour.';
$lang['config.max_topic_number_in_favorite'] = 'Nombre de sujets en favoris maximum pour chaque membre';
$lang['config.edit_mark_enabled'] = 'Marqueurs d\'�dition des messages';
$lang['config.connexion_form_displayed'] = 'Afficher le formulaire de connexion';
$lang['config.left_column_disabled'] = 'Masquer les blocs de gauche du site sur le forum';
$lang['config.right_column_disabled'] = 'Masquer les blocs de droite du site sur le forum';
$lang['config.message_before_topic_title_displayed'] = 'Afficher le message devant le titre du topic';
$lang['config.message_before_topic_title'] = 'Message devant le titre du topic';
$lang['config.message_when_topic_is_unsolved'] = 'Message devant le titre du topic si statut non chang�';
$lang['config.message_when_topic_is_solved'] = 'Message devant le titre du topic si statut chang�';
$lang['config.message_before_topic_title_icon_displayed'] = 'Afficher l\'ic�ne associ�e';
$lang['config.message_before_topic_title_icon_displayed.explain'] = '<i class="fa fa-msg-display"></i> / <i class="fa fa-msg-not-display"></i>';

//authorizations
$lang['authorizations.flood'] = 'Autorisation de flooder';
$lang['authorizations.hide_edition_mark'] = 'D�sactivation du marqueur d\'�dition des messages';
$lang['authorizations.unlimited_topics_tracking'] = 'D�sactivation de la limite de sujet suivis';
?>
