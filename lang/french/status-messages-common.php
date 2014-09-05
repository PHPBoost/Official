<?php
/*##################################################
 *                                status-messages-common.php
 *                            -------------------
 *   begin                : April 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

$lang['success'] = 'Succ�s';
$lang['error'] = 'Erreur';

$lang['error.fatal'] = 'Fatale';
$lang['error.notice'] = 'Suggestion';
$lang['error.warning'] = 'Avertissement';
$lang['error.unknow'] = 'Inconnue';

$lang['csrf_invalid_token'] = 'Jeton de session invalide. Veuillez r�essayer car l\'op�ration n\'a pas pu �tre effectu�e.';

//Element
$lang['element.already_exists'] = 'L\'�l�ment que vous demandez existe d�j�.';
$lang['element.unexist'] = 'L\'�l�ment que vous demandez n\'existe pas.';
$lang['element.not_visible'] = 'Cet �l�ment n\'est pas encore ou n\'est plus approuv�, il n\'est pas affich� pour les autres utilisateurs du site.';

$lang['misfit.php'] = 'Version PHP inadapt�e';
$lang['misfit.phpboost'] = 'Version de PHPBoost inadapt�e';

//Process
$lang['process.success'] = 'L\'op�ration s\'est d�roul�e avec succ�s';
$lang['process.error'] = 'Une erreur s\'est produite lors de l\'op�ration';

$lang['confirm.delete'] = 'Voulez-vous vraiment supprimer cet �l�ment ?';

$lang['message.success.config'] = 'La configuration a �t� modifi�e';

//Captcha
$lang['captcha.validation_error'] = 'Le champ de v�rification visuel n\'a pas �t� saisi correctement !';
$lang['captcha.is_default'] = 'Le captcha que vous souhaitez d�sinstaller ou d�sactiver est d�fini sur le site, veuillez d\'abord s�lectionner un autre captcha dans la configuration du contenu.';
$lang['captcha.last_installed'] = 'Dernier captcha, vous ne pouvez pas le supprimer ou le d�sactiver. Veuillez d\'abord en installer un autre.';

//Form
$lang['form.doesnt_match_regex'] = 'La valeur saisie n\'est pas au bon format';
$lang['form.doesnt_match_url_regex'] = 'La valeur saisie doit �tre une url valide';
$lang['form.doesnt_match_mail_regex'] = 'La valeur saisie doit �tre un mail valide';
$lang['form.doesnt_match_length_intervall'] = 'La valeur saisie ne respecte par la longueur d�finie';
$lang['form.doesnt_match_integer_intervall'] = 'La valeur saisie ne respecte pas l\'intervalle d�finie (:lower_bound <= valeur <= :upper_bound)';
$lang['form.has_to_be_filled'] = 'Le champ ":name" doit �tre renseign�';
$lang['form.validation_error'] = 'Veuillez corriger les erreurs du formulaire';
$lang['form.fields_must_be_equal'] = 'Les champs ":field1" et ":field2" doivent �tre �gaux';
$lang['form.fields_must_not_be_equal'] = 'Les champs ":field1" et ":field2" doivent avoir des valeurs diff�rentes';

//User
$lang['user.not_exists'] = 'L\'utilisateur n\'existe pas !';
$lang['user.auth.passwd_flood'] = 'Il vous reste :remaining_tries essai(s) apr�s cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$lang['user.auth.passwd_flood_max'] = 'Vous avez �puis� tous vos essais de connexion, votre compte est verrouill� pendant 5 minutes.';
?>