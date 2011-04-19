<?php
/*##################################################
 *                           admin-members-common.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 K�vin MASSY
 *   email                : soldier.weasel@gmail.com
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

//Messages 
$lang['members.config.success-saving'] = 'La configuration a �t� enregistr�e avec succ�s';
$lang['members.add-member.success'] = 'Le membre a �t� ajout� avec succ�s';
$lang['members.member-edit.success'] = 'Le membre a bien �t� modifi�';

// Title 
$lang['members.config-members'] = 'Configuration des membres';
$lang['members.members-management'] = 'Gestion des membres';
$lang['members.add-member'] = 'Ajouter un membre';
$lang['members.members-punishment'] = 'Gestion des sanctions';
$lang['members.edit-member'] = 'Edition d\'un membre';

//Configuration
$lang['members.config.registration-activation'] = 'Activer l\'inscription des membres';
$lang['members.config.type-activation'] = 'Mode d\'activation du compte membre';
$lang['members.config.unactivated-accounts-timeout'] = 'Dur�e apr�s laquelle les membres non activ�s sont effac�s';
$lang['members.config.unactivated-accounts-timeout-explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$lang['members.config.captcha-activation'] = 'Code de v�rification visuel';
$lang['members.config.captcha-difficulty'] = 'Difficult� du code de v�rification';
$lang['members.config.theme-choice-permission'] = 'Permission aux membres de choisir leur th�me';
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
$lang['members.config.welcome-message-content'] = 'Message de bienvenue affich� dans le panel du membre';

//Other fieldset configuration title
$lang['members.config.avatars-management'] = 'Gestion des avatars';
$lang['members.config.authorization'] = 'Autorisations';

//Add and edit member
$lang['members.pseudo'] = 'Pseudo';
$lang['members.mail'] = 'Adresse email';
$lang['members.hide-mail'] = 'Cacher l\'email';
$lang['members.password'] = 'Mot de passe';
$lang['members.confirm-password'] = 'Confirmer le mot de passe';
$lang['members.rank'] = 'Rang';
$lang['members.rank.member'] = 'Membre';
$lang['members.rank.modo'] = 'Mod�rateur';
$lang['members.rank.admin'] = 'Administrateur';
$lang['members.approbation'] = 'Approbation';
$lang['members.groups'] = 'Groupes';

//Other fieldset add and edit title
$lang['members.member-management'] = 'Gestion du membre';
$lang['members.punishment-management'] = 'Gestion des sanctions';

//Activation type
$lang['members.config.type-activation.auto'] = 'Automatique';
$lang['members.config.type-activation.mail'] = 'Mail';
$lang['members.config.type-activation.admin'] = 'Administrateur';

//Other
$lang['members.valid'] = 'Valide';
?>