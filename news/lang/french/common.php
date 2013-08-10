<?php
/*##################################################
 *		                         common.class.php
 *                            -------------------
 *   begin                : February 20, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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

$lang['news'] = 'News';
$lang['news.add'] = 'Ajouter une news';
$lang['news.edit'] = 'Modifier une news';
$lang['news.pending'] = 'News en attente';
$lang['news.manage'] = 'G�rer les news';

$lang['news.message.delete'] = 'Voulez vous vraiment supprimer la news ?';
$lang['news.message.no_items'] = 'Aucune news n\'est disponible pour le moment';

$lang['news.seo.description.root'] = 'Toutes les news du site :site.';
$lang['news.seo.description.tag'] = 'Toutes les news sur le sujet :subject.';
$lang['news.seo.description.pending'] = 'Toutes les news en attente.';

$lang['news.form.name'] = 'Nom de la news';
$lang['news.form.rewrited_name'] = 'Nom de votre news dans l\'url';
$lang['news.form.rewrited_name.description'] = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['news.form.rewrited_name.personalize'] = 'Personnaliser le nom de la news dans l\'url';
$lang['news.form.category'] = 'Cat�gorie de la news';
$lang['news.form.contents'] = 'Contenu';
$lang['news.form.short_contents'] = 'Condens� de la news';
$lang['news.form.short_contents.enabled'] = 'Personnaliser le condens� de la news';
$lang['news.form.short_contents.enabled.description'] = 'Ou laisser PHPBoost couper la news � :number caract�res.';
$lang['news.form.approbation'] = 'Publication';
$lang['news.form.approbation.not'] = 'Garder en brouillon';
$lang['news.form.approbation.now'] = 'Publier maintenant';
$lang['news.form.approbation.date'] = 'Publication diff�r�e';
$lang['news.form.approved.not'] = 'Gard� en brouillon';
$lang['news.form.approved.now'] = 'Publi�';
$lang['news.form.approved.date'] = 'Publi� en diff�r�';
$lang['news.form.date.start'] = 'A partir du';
$lang['news.form.date.end'] = 'Juqu\'au';
$lang['news.form.date.creation'] = 'Date de cr�ation de la news';
$lang['news.form.date.end.enable'] = 'D�finir une date de fin de publication';
$lang['news.form.top_list'] = 'Placer la news en t�te de liste';
$lang['news.form.other'] = 'Autre';
$lang['news.form.keywords'] = 'Mots cl�s';
$lang['news.form.keywords.description'] = 'Vous permet d\'ajouter des mots cl�s � votre news';
$lang['news.form.picture'] = 'Image de la news';
$lang['news.form.picture.preview'] = 'Preview de l\'image';
$lang['news.form.sources'] = 'Sources';
$lang['news.form.sources.name'] = 'Nom de la source';
$lang['news.form.sources.url'] = 'Adresse de la source';
$lang['news.form.contribution'] = 'Contribution';
$lang['news.form.contribution.explain'] = 'Vous n\'�tes pas autoris� � cr�er une news, cependant vous pouvez proposer une news. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.';
$lang['news.form.contribution.description'] = 'Compl�ment de contribution';
$lang['news.form.contribution.description.explain'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cette news au site). Ce champ est facultatif.';
$lang['news.form.contribution.entitled'] = '[News] :name';

$lang['admin.config'] = 'Configuration';
$lang['admin.config.number_news_per_page'] = 'Nombre de news par page';
$lang['admin.config.number_columns_display_news'] = 'Nombre de colonnes pour afficher les news';
$lang['admin.config.display_condensed'] = 'Afficher le condens� de la news et non la news toute enti�re';
$lang['admin.config.number_character_to_cut'] = 'Nombre de caract�re pour couper la news';
$lang['admin.config.comments_enabled'] = 'Activer les commentaires';
$lang['admin.config.news_suggestions_enabled'] = 'Activer l\'affichage des suggestions';
$lang['admin.config.display_type'] = 'Type d\'affichage des news';
$lang['admin.config.display_type.block'] = 'Affichage en block';
$lang['admin.config.display_type.list'] = 'Affichage en liste';
$lang['admin.config.authorizations'] = 'Autorisations';
$lang['admin.config.authorizations.read'] = 'Autorisations de lecture';
$lang['admin.config.authorizations.write'] = 'Autorisations d\'�criture';
$lang['admin.config.authorizations.contribution'] = 'Autorisations de contribution';
$lang['admin.config.authorizations.moderation'] = 'Autorisation de mod�ration';

$lang['admin.categories.manage'] = 'G�rer les cat�gories';
$lang['admin.categories.add'] = 'Ajouter une cat�gorie';
$lang['admin.categories.edit'] = 'Modifier une cat�gorie';
$lang['admin.categories.delete'] = 'Supprimer une cat�gorie';
//Feed name
$lang['feed.name'] = 'Actualit�s';
?>