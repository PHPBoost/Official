<?php
/*##################################################
 *                           categories-common.php
 *                            -------------------
 *   begin                : February 07, 2013
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

 ####################################################
 #                     French                       #
 ####################################################

$lang['category'] = 'Cat�gorie';
$lang['categories'] = 'Cat�gories';

//Management
$lang['categories.manage'] = 'G�rer les cat�gories';
$lang['category.add'] = 'Ajouter une cat�gorie';
$lang['category.edit'] = 'Modifier une cat�gorie';
$lang['category.delete'] = 'Supprimer une cat�gorie';

//Errors
$lang['errors.unexisting'] = 'La cat�gorie n\'existe pas.';
$lang['message.no_categories'] = 'Aucune cat�gorie existante';

//Form
$lang['category.form.name'] = 'Nom de la cat�gorie';
$lang['category.form.rewrited_name'] = 'Nom de votre cat�gorie dans l\'url';
$lang['category.form.rewrited_name.description'] = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['category.form.rewrited_name.personalize'] = 'Personnaliser le nom de la cat�gorie dans l\'url';
$lang['category.form.parent'] = 'Emplacement de la cat�gorie';
$lang['category.form.authorizations'] = 'Autorisations';
$lang['category.form.authorizations.read'] = 'Autorisations de lecture';
$lang['category.form.authorizations.write'] = 'Autorisations d\'�criture';
$lang['category.form.authorizations.contribution'] = 'Autorisations de contribution';
$lang['category.form.authorizations.moderation'] = 'Autorisations de mod�ration';
$lang['category.form.authorizations.description'] = 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.';
$lang['category.form.description'] = 'Description de la cat�gorie';
$lang['category.form.image'] = 'Image de la cat�gorie';
$lang['category.form.image.preview'] = 'Pr�visualisation de l\'image';
$lang['category.form.options'] = 'Options';

//Delete category
$lang['delete.category'] = 'Suppression d\'une cat�gorie';
$lang['delete.description'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (articles et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de la cat�gorie. <strong>Attention, cette action est irr�versible !</strong>';
$lang['delete.category_and_content'] = 'Supprimer la cat�gorie et tout son contenu';
$lang['delete.move_in_other_cat'] = 'D�placer son contenu dans :';
?>