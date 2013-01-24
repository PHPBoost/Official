<?php
/*##################################################
 *                             news_french.php
 *                            -------------------
 *   begin                :  June 20, 2005
 *   copyright            : (C) 2005 Viarre R�gis, Roguelon Geoffrey
 *   email                : crowkait@phpboost.com, liaght@gmail.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
#                     French                       #
 ####################################################

global $NEWS_LANG;

$LANG['e_unexist_news'] = 'La news que vous demandez n\'existe pas !';
$LANG['e_unexist_cat_news'] = 'La cat�gorie que vous demandez n\'existe pas !';

$NEWS_LANG = array(
	'activ_com_n' => 'Activer les commentaires des news',
	'activ_edito' => 'Activer l\'�dito',
	'activ_icon_n' => 'Afficher les ic�nes de cat�gories des news',
	'activ_news_block' => 'Activer les news en bloc',
	'activ_pagination' => 'Activer la pagination',
	'add_category' => 'Ajouter une cat�gorie',
	'add_news' => 'Ajouter une news',
	'alert_delete_news' => 'Supprimer cette news ?',
	'archive' => 'Archives',
	'auth_contribute' => 'Permissions de contribution',
	'auth_moderate' => 'Permissions de mod�ration',
	'auth_read' => 'Permissions de lecture',
	'auth_write' => 'Permissions d\'�criture',

	'cat_news' => 'Cat�gorie de la news',
	'category_desc' => 'Description de la cat�gorie',
	'category_image' => 'Image de la cat�gorie',
	'category_location' => 'Emplacement de la cat�gorie',
	'category_name' => 'Nom de la cat�gorie',
	'category_news' => 'Gestion des cat�gories',
	'configuration_news' => 'Configuration des news',
	'confirm_del_news' => 'Supprimer cette news ?',
	'contribution_counterpart' => 'Compl�ment de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cette news au site). Ce champ est facultatif.',
	'contribution_entitled' => '[News] %s',
	
	'delete_category_and_its_content' => 'Supprimer la cat�gorie et tout son contenu',
	'desc_extend_news' => 'News �tendue',
	'desc_news' => 'News',
	'display_archive' => 'Afficher les archives',
	'display_news_author' => 'Afficher l\'auteur de la news',
	'display_news_date' => 'Afficher la date de la news',

	'edit_news' => '�diter la news',
	'edito_where' => 'Message visible de tous en haut de l\'accueil',
	'explain_removing_category' => 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (fichiers et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de la cat�gorie. <strong>Attention, cette action est irr�versible !</strong>',
	'extend_contents' => 'Lire la suite...',

	'global_auth' => 'Permissions globales',
	'global_auth_explain' => 'Vous d�finissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque cat�gorie',

	'img_desc' => 'Description image',
	'img_link' => 'Adresse de la photo',
	'img_management' => 'Interface image',
	'infinite_loop' => 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie',

	'last_news' => 'Derni�res news',

	'move_category_content' => 'D�placer son contenu dans :',

	'news' => 'News',
	'news_date' => 'Date de la news',
	'nbr_arch_p' => 'Nombre d\'archives par pages',
	'nbr_news_column' => 'Nombre de colonnes pour afficher les news',
	'nbr_news_p' => 'Nombre de news par pages',
	'new_cat_does_not_exist' => 'La cat�gorie cible n\'existe pas',
	'news_management' => 'Gestion des news',
	'news_suggested' => 'News sugg�r�es :',
	'no_news_available' => 'Aucune news disponible pour le moment',
	'notice_contribution' => 'Vous n\'�tes pas autoris� � cr�er une news, cependant vous pouvez proposer une news. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.',

	'on' => 'Le : %s',

	'preview_image' => 'Aper�u image',
	'preview_image_explain' => 'Par d�faut � droite',

	'release_date' => 'Date de parution',
	'removing_category' => 'Suppression d\'une cat�gorie',
	'require_cat' => 'Veuillez choisir une cat�gorie !',
	'required_fields_empty' => 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement',

	'special_auth' => 'Permissions sp�ciales',
	'special_auth_explain' => 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.',
	'successful_operation' => 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s',

	'title_news' => 'Titre de la news',

	'unexisting_category' => 'La cat�gorie que vous avez s�lectionn�e n\'existe pas',

	'until_1' => '(Jusqu\'au %s)',
	'until_2' => '(%s jusqu\'au %s)',

	'waiting_news' => 'News en attente',

	'xml_news_desc' => 'Actualit�s - ',

	'sources' => 'Sources',
	'add_sources' => 'Ajouter des sources',
	'name_sources' => 'Nom de la source',
	'url_sources' => 'Url de la source',
);

?>