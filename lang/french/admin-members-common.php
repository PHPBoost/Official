<?php
/*##################################################
 *                           admin-members-common.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
#                     French                       #
 ####################################################
 
$lang = array();

// Title 
$lang['members.config-members'] = 'Configuration des membres';
$lang['members.members-management'] = 'Gestion des membres';
$lang['members.add-member'] = 'Ajouter un membre';
$lang['members.members-punishment'] = 'Gestion des sanctions';
$lang['members.edit-member'] = 'Edition d\'un membre';
$lang['members.rules'] = 'R�glement';

//Configuration
$lang['members.config.registration-activation'] = 'Activer l\'inscription des membres';
$lang['members.config.type-activation'] = 'Mode d\'activation du compte membre';
$lang['members.config.unactivated-accounts-timeout'] = 'Nombre de jours apr�s lequel les membres non activ�s sont effac�s';
$lang['members.config.unactivated-accounts-timeout-explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$lang['members.config.captcha-activation'] = 'Code de v�rification visuel';
$lang['members.config.captcha-difficulty'] = 'Difficult� du code de v�rification';
$lang['members.config.upload-avatar-server-authorization'] = 'Autoriser l\'upload d\'avatar sur le serveur';
$lang['members.config.activation-resize-avatar'] = 'Activer le redimensionnement automatique des images';
$lang['members.activation-resize-avatar-explain'] = 'Attention votre serveur doit avoir l\'extension GD charg�e';
$lang['members.config.maximal-width-avatar'] = 'Largeur maximale de l\'avatar';
$lang['members.config.maximal-width-avatar-explain'] = 'Par d�faut 120';
$lang['members.config.maximal-height-avatar'] = 'Hauteur maximale de l\'avatar';
$lang['members.config.maximal-height-avatar-explain'] = 'Par d�faut 120';
$lang['members.config.maximal-weight-avatar'] = 'Poids maximal de l\'avatar en Ko';
$lang['members.config.maximal-weight-avatar-explain'] = 'Par d�faut 20';
$lang['members.config.default-avatar-activation'] = 'Activer l\'avatar par d�faut';
$lang['members.config.default-avatar-activation-explain'] = 'Met un avatar aux membres qui n\'en ont pas';
$lang['members.config.default-avatar-link'] = 'Adresse de l\'avatar par d�faut';
$lang['members.default-avatar-link-explain'] = 'Mettre dans le dossier images de votre th�me';
$lang['members.config.authorization-read-member-profile'] = 'Vous d�finissez ici les permissions de lecture de la liste des membres ainsi que certaines informations personnelles comme leurs emails.';
$lang['members.config.welcome-message'] = 'Message � tous les membres';
$lang['members.config.welcome-message-content'] = 'Message de bienvenue affich� dans le profil du membre';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Gestion des avatars';
$lang['members.config.authorization'] = 'Autorisations';

//Other fieldset add and edit title
$lang['members.member-management'] = 'Gestion du membre';
$lang['members.punishment-management'] = 'Gestion des sanctions';

//Activation type
$lang['members.config.type-activation.auto'] = 'Automatique';
$lang['members.config.type-activation.mail'] = 'Mail';
$lang['members.config.type-activation.admin'] = 'Administrateur';

//Rules
$lang['members.rules.registration-agreement-description'] = 'Entrez ci-dessous le r�glement � afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun r�glement.';
$lang['members.rules.registration-agreement'] = 'Contenu du r�glement';

//Other
$lang['members.valid'] = 'Valide';
?>