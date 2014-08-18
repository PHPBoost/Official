<?php
/*##################################################
 *                           admin-modules-common.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

//Title
$lang['modules.module_management'] = 'Gestion des modules';
$lang['modules.add_module'] = 'Ajouter un module';
$lang['modules.update_module'] = 'Mettre � jour un module';
$lang['modules.delete_module'] = 'Suppression ou d�sactivation d\'un module';
$lang['modules.installed_modules'] = 'Modules install�s';

//Upload
$lang['modules.upload_module'] = 'Uploader un module';
$lang['modules.upload_description'] = 'L\'archive upload�e doit �tre au format zip ou gzip';

//Module
$lang['modules.name'] = 'Nom';
$lang['modules.description'] = 'Description';
$lang['modules.author'] = 'Auteur';
$lang['modules.compatibility'] = 'Compatibilit�';
$lang['modules.php_version'] = 'Version PHP';
$lang['modules.url_rewrite_rules'] = 'R�gles de r��criture d\'URL';
$lang['modules.page_admin'] = 'Administration';
$lang['modules.modules_available'] = 'Modules disponibles';
$lang['modules.installed_activated_modules'] = 'Modules install�s et activ�s';
$lang['modules.installed_not_activated_modules'] = 'Modules d�sactiv�s';

//Module management
$lang['modules.activate_module'] = 'Activer';
$lang['modules.install_module'] = 'Installer';
$lang['modules.activated_module'] = 'Activ�';
$lang['modules.authorization'] = 'Autorisation d\'acc�s';
$lang['modules.delete'] = 'D�sinstaller';

//Messages
$lang['modules.upload_success'] = 'L\'archive a �t� upload�e avec succ�s';
$lang['modules.upload_invalid_format'] = 'Le format de l\'archive n\'est pas valide';
$lang['modules.already_installed'] = 'Ce module est d�j� install�';
$lang['modules.upload_error'] = 'Il y a eu une erreur lors de l\'upload';
$lang['modules.module_not_upgradable'] = 'Ce module ne peut pas �tre mis � jour';
$lang['modules.updates_available'] = 'Mises � jour disponibles';
$lang['modules.config_conflict'] = 'Conflit avec la configuration du module, installation impossible!';

//Delete module
$lang['modules.drop_files'] = 'Supprimer tous les fichiers du module';

//Update
$lang['modules.upgrade_module'] = 'Mettre � jour';
?>