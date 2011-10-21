<?php
/*##################################################
 *                                errors.php
 *                            -------------------
 *   begin                : June 27, 2006
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : mickaelhemri@gmail.com
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
#                        French                     #
 ####################################################

$lang = array();

//Erreurs
$lang['error'] = 'Erreur';
$lang['unknow_error'] = 'Erreur inconnue';
$lang['e_auth'] = 'Vous n\'avez pas le niveau requis !';
$lang['e_unexist_module'] = 'Le module associ� n\'existe pas !';
$lang['e_uninstalled_module'] = 'Ce module n\'est pas install� !';
$lang['e_unactivated_module'] = 'Ce module n\'est pas activ� !';
$lang['e_already_installed_module'] = 'Ce module est d�j� install� !';
$lang['e_incomplete'] = 'Tous les champs obligatoires doivent �tre remplis !';
$lang['e_auth_post'] = 'Vous devez �tre inscrit pour poster !';
$lang['e_readonly'] = 'Vous ne pouvez ex�cuter cette action, car vous avez �t� plac� en lecture seule !';
$lang['e_unexist_cat'] = 'La cat�gorie que vous demandez n\'existe pas !';
$lang['e_unexist_file'] = 'Le fichier que vous avez demand� n\'existe pas !';
$lang['e_unexist_page'] = 'La page que vous demandez n\'existe pas !';
$lang['e_mail_format'] = 'Mail invalide !';
$lang['e_unexist_member'] = 'Ce pseudo n\'existe pas !';
$lang['e_unauthorized'] = 'Vous n\'�tes pas autoris� � poster !';
$lang['e_flood'] = 'Vous ne pouvez pas encore poster, r�essayez dans quelques instants';
$lang['e_l_flood'] = 'Nombre maximum de lien(s) internet autoris�(s) dans votre message : %d';
$lang['e_link_pseudo'] = 'Vous ne pouvez pas mettre de lien dans votre pseudo';
$lang['e_php_version_conflict'] = 'Version PHP inadapt�e';

//Cache
$lang['e_cache_modules'] = 'Cache -> La g�n�ration du fichier de cache des modules a �chou� !';

//Upload
$lang['e_upload_max_dimension'] = 'Dimensions maximales du fichier d�pass�es';
$lang['e_upload_max_weight'] = 'Poids maximum du fichier d�pass�';
$lang['e_upload_invalid_format'] = 'Format du fichier invalide';
$lang['e_upload_php_code'] = 'Contenu du fichier invalide, le code php est interdit';
$lang['e_upload_error'] = 'Erreur lors de l\'upload du fichier';
$lang['e_unlink_disabled'] = 'Fonction de suppression des fichiers d�sactiv�e sur votre serveur';
$lang['e_upload_failed_unwritable'] = 'Upload impossible, interdiction d\'�criture dans ce dossier';
$lang['e_upload_already_exist'] = 'Le fichier existe d�j�, �crasement non autoris�';
$lang['e_max_data_reach'] = 'Taille maximale atteinte, supprimez d\'anciens fichiers';

//Membres
$lang['e_pass_mini'] = 'Longueur minimale du nouveau password : 6 caract�res';
$lang['e_pass_same'] = 'Les mots de passe doivent �tre identiques';
$lang['e_pseudo_auth'] = 'Le pseudo entr� est d�j� utilis� !';
$lang['e_mail_auth'] = 'Le mail entr� est d�j� utilis� !';
$lang['e_mail_invalid'] = 'Le mail entr� est invalide !';
$lang['e_unexist_member'] = 'Aucun membre trouv� avec ce pseudo !';
$lang['e_member_ban'] = 'Vous avez �t� banni! Vous pourrez vous reconnecter dans';
$lang['e_member_ban_w'] = 'Vous avez �t� banni pour un comportement abusif! Contactez l\'administrateur s\'il s\'agit d\'une erreur.';
$lang['e_unactiv_member'] = 'Votre compte n\'a pas encore �t� activ� !';
$lang['e_test_connect'] = 'Il vous reste %d essai(s) restant(s) apr�s cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10 minutes pour 5) !';
$lang['e_nomore_test_connect'] = 'Vous avez �puis� tous vos essais de connexion, votre compte est verrouill� pendant 5 minutes';

//Groupes
$lang['e_already_group'] = 'Le membre appartient d�j� au groupe';

//Oubli�
$lang['e_mail_forget'] = 'Le mail entr� ne correspond pas � celui de l\'utilisateur !';
$lang['e_forget_mail_send'] = 'Un mail vient de vous �tre envoy�, avec une cl� d\'activation pour changer votre mot de passe';
$lang['e_forget_confirm_change'] = 'Mot de passe chang� avec succ�s !<br />Vous pouvez d�sormais vous connecter avec le nouveau mot de passe que vous avez choisi.';
$lang['e_forget_echec_change'] = 'Echec le mot de passe ne peut �tre chang�';

//Register
$lang['e_incorrect_verif_code'] = 'Le code de v�rification entr� est incorrect !';

//Mps
$lang['e_pm_full'] = 'Votre boite de messages priv�s est pleine, vous avez <strong>%d</strong> conversation(s) en attente, pour pouvoir la/les lire supprimez d\'anciennes conversations.';
$lang['e_pm_full_post'] = 'Votre boite de messages priv�s est pleine, supprimez d\'anciennes conversations pour pouvoir en envoyer de nouvelles.';
$lang['e_unexist_user'] = 'L\'utilisateur s�lectionn� n\'existe pas !';
$lang['e_pm_del'] = 'Le destinataire a supprim� la conversation, vous ne pouvez plus poster';
$lang['e_pm_noedit'] = 'Le destinataire a d�j� lu votre message, vous ne pouvez plus l\'�diter';
$lang['e_pm_nodel'] = 'Le destinataire a d�j� lu votre message, vous ne pouvez plus le supprimer';

//Gestionnaire d'erreur php
$lang['e_fatal'] = 'Fatale';
$lang['e_notice'] = 'Suggestion';
$lang['e_warning'] = 'Avertissement';
$lang['e_unknow'] = 'Inconnue';
$lang['infile'] = 'dans le fichier';
$lang['atline'] = '� la ligne';

// Too Many Connections
$lang['too_many_connections'] = 'Trop de connexions';
$lang['too_many_connections_explain'] = 'Le nombre maximum de connexions simultan�es � la base de donn�es � �t� atteint.<br />Veuillez r�essayer dans quelques secondes.';
?>
