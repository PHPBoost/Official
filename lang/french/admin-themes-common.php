<?php
/*##################################################
 *                           admin-themes-common.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
$lang['themes.management'] = 'Gestion des th�mes';
$lang['themes.installed'] = 'Th�mes install�s';
$lang['themes.add'] = 'Ajouter un th�me';
$lang['themes.not_installed'] = 'Th�mes non install�s';
$lang['themes.delete'] = 'Suppression du th�me';

//Installed th�mes
$lang['themes.name'] = 'Nom';
$lang['themes.description'] = 'Description';
$lang['themes.authorisations'] = 'Autorisations';
$lang['themes.activated'] = 'Activ�';

//Theme
$lang['themes.author'] = 'Auteur';
$lang['themes.compatibility'] = 'Compatibilit�';
$lang['themes.html_version'] = 'Version HTML';
$lang['themes.css_version'] = 'Version CSS';
$lang['themes.main_color'] = 'Couleurs dominantes';
$lang['themes.variable-width'] = 'Extensible';
$lang['themes.width'] = 'Largeur';
$lang['themes.bot_informed'] = 'Non renseign�';
$lang['themes.view_real_preview'] = 'Voir en taille r�elle';
$lang['themes.default_theme_explain'] = 'Le th�me par d�faut ne peut pas �tre d�sinstall�, d�sactiv� ou r�serv�';

//Others
$lang['themes.yes'] = 'Oui';
$lang['themes.no'] = 'Non';
$lang['themes.visitor'] = 'Visiteur';

//Add th�me
$lang['themes.add_theme'] = 'Ajouter le th�me';
$lang['themes.add.not_theme'] = 'Aucun th�me � installer';

//Upload
$lang['themes.upload'] = 'Uploader un th�me';
$lang['themes.upload.description'] = 'L\'archive upload�e doit �tre au format zip ou gzip';

//Errors
$lang['themes.already_exist'] = 'Le th�me existe d�j�';
$lang['themes.upload.invalid_format'] = 'Le format de l\'archive n\'est pas valide';
$lang['themes.upload.error'] = 'Erreur avec l\'upload du fichier';
$lang['themes.not_compatible'] = 'Le th�me est apparemment incompatible avec votre version actuelle de PHPBoost, il a tout de m�me �t� install�. Si un probl�me subvient veuillez contacter l\'auteur du th�me.';

//Delete theme
$lang['themes.delete.drop_files'] = 'Supprimer tous les fichiers du th�me';
?>