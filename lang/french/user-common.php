<?php
/*##################################################
 *                           user-common.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

$lang['user'] = 'Utilisateur';
$lang['users'] = 'Utilisateurs';
$lang['profile'] = 'Profil';
$lang['profile.edit'] = 'Edition du profil';
$lang['messages'] = 'Messages de l\'utilisateur';
$lang['maintain'] = 'Maintenance';
$lang['groups'] = 'Groupes';

$lang['profile.edit.success'] = 'Le profil a bien �t� modifi�';
$lang['profile.edit.password.error'] = 'Le mot de passe que vous avez entr� n\'est pas correct';

//Contribution
$lang['contribution.confirmed'] = 'Votre contribution a bien �t� enregistr�e.';
$lang['contribution.confirmed.messages'] = '<p>Vous pourrez la suivre dans le <a href="' . UserUrlBuilder::contribution_panel()->absolute() . '">panneau de contribution</a> 
et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>';


//User fields
$lang['pseudo'] = 'Pseudo';
$lang['pseudo.explain'] = 'Longueur minimale du pseudo : 3 caract�res';
$lang['password'] = 'Mot de passe';
$lang['password.new'] = 'Nouveau mot de passe';
$lang['password.old'] = 'Ancien mot de passe';
$lang['password.old.explain'] = 'Remplir seulement en cas de modification';
$lang['password.confirm'] = 'Confirmer le mot de passe';
$lang['password.explain'] = 'Longueur minimale du mot de passe : 6 caract�res';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Cache l\'email';
$lang['theme'] = 'Th�me';
$lang['theme.preview'] = 'Pr�visualiser le th�me';
$lang['text-editor'] = 'Editeur de texte';
$lang['lang'] = 'Langue';
$lang['timezone.'] = 'Fuseau horaire';
$lang['timezone.choice'] = 'Choix du fuseau horaire';
$lang['timezone.choice.explain'] = 'Permet d\'ajuster l\'heure � votre localisation';
$lang['email.hide'] = 'Cache l\'email';
$lang['level'] = 'Rang';

$lang['registration_date'] = 'Date d\'inscription';
$lang['last_connection'] = 'Derni�re connexion';
$lang['number-messages'] = 'Nombre de messages';
$lang['private_message'] = 'Message priv�';
$lang['delete-account'] = 'Supprimer le compte';
$lang['avatar'] = 'Avatar';

$lang['groups'] = 'Groupes';
$lang['groups.select'] = 'S�l�ctionner un groupe';

//Other
$lang['banned'] = 'Banni';
$lang['connect'] = 'Se connecter';

// Ranks
$lang['visitor'] = 'Visiteur';
$lang['member'] = 'Membre';
$lang['moderator'] = 'Mod�rateur';
$lang['administrator'] = 'Administrateur';

//Forget password
$lang['forget-password'] = 'Mot de passe oubli�';
$lang['forget-password.select'] = 'S�lectionnez le champ que vous voulez renseigner (email ou pseudo)';
$lang['forget-password.success'] = 'Un email vous a �t� envoy� avec un lien pour changer votre mot de passe';
$lang['forget-password.error'] = 'Les informations fournisent ne sont pas correct, veuillez les rectifier et r�essayer';
$lang['change-password'] = 'Changement de mot de passe';
$lang['forget-password.mail.content'] = 'Cher(e) :pseudo,

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui pr�tend l\'�tre) avez demand� � ce qu\'un nouveau mot de passe vous soit envoy� pour votre compte sur :host. 
Si vous n\'avez pas demand� de changement de mot de passe, veuillez l\'ignorer. Si vous continuez � le recevoir, veuillez contacter l\'administrateur du site.

Pour changer de mot de passe, cliquez sur le lien fourni ci-dessous et suivez les indications sur le site.

:change_password_link

Si vous rencontrez des difficult�s, veuillez contacter l\'administrateur du site.

:signature';

//Registration 
$lang['registration'] = 'Inscription';
$lang['registration.validation.mail.explain'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoy� avant de pouvoir vous connecter';
$lang['registration.validation.administrator.explain'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';
$lang['registration.confirm.success'] = 'Votre compte a �t� valid� avec succ�s';
$lang['registration.confirm.error'] = 'Un probl�me est survenue lors de votre activation, v�rifier que votre cl� est bien valide';
$lang['registration.success'] = 'Vous vous �tes bien enregistr� et �tes d�s � pr�sent connect�';
$lang['registration.success.administrator-validation'] = 'Vous vous �tes enregistr� avec succ�s. Cependant un administrateur doit valider votre compte avant de pouvoir l\'utiliser';
$lang['registration.success.mail-validation'] = 'Vous vous �tes enregistr� avec succ�s. Cependant il vous faudra cliquer sur le lien d\'activation contenu dans le mail qui vous a �t� envoy�';
$lang['registration.pending-approval'] = 'Un nouveau membre s\'est inscrit. Son compte doit �tre approuv� avant de pouvoir �tre utilis�.';
$lang['registration.subject-mail'] = 'Confirmation d\'inscription sur :site_name';
$lang['registration.content-mail'] = 'Cher(e) :pseudo,

Tout d\'abord, merci de vous �tre inscrit sur :site_name. Vous faites parti d�s maintenant des membres du site.
En vous inscrivant sur :site_name, vous obtenez un acc�s � la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, �tre reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le th�me par d�faut, �diter votre profil, acc�der � des cat�gories r�serv�es aux membres... Bref vous acc�dez � toute la communaut� du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : :login
Mot de passe : :password

:accounts_validation_explain

:signature';
?>