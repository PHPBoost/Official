<?php
/*##################################################
 *                        articles_common.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
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

#####################################################
 #                      French			    #
 ####################################################

$lang = array();

//Titles
$lang['articles'] = 'Articles';
$lang['articles_management'] = 'Gestion des articles';
$lang['articles_configuration'] = 'Configuration';
$lang['articles.add'] = 'Ajouter un article';
$lang['articles.edit'] = 'Modifier un article';
$lang['articles.delete'] = 'Supprimer un article';
$lang['articles.visitor'] = 'Visiteur';
$lang['articles.no_article.category'] = 'Aucun article dans cette cat�gorie';
$lang['articles.no_article'] = 'Aucun article disponible';
$lang['articles.no_notes'] = 'Aucun avis';
$lang['articles.nbr_articles_category'] = ':number article(s) dans la cat�gorie';
$lang['categories_management'] = 'Gestion des cat�gories';
$lang['add_category'] = 'Ajouter une cat�gorie';
$lang['edit_category'] = '�diter une cat�gorie';
$lang['edit_category'] = 'Supprimer une cat�gorie';
$lang['articles.sub_categories'] = 'Sous-cat�gories';
$lang['articles.category'] = 'Cat�gorie';
$lang['articles.feed_name'] = 'Derniers articles';
$lang['articles.pending_articles'] = 'Articles en attente';
$lang['articles.nbr_articles.pending'] = ':number article(s) en attente';
$lang['articles.no_pending_article'] = 'Aucune article en attente pour le moment';
$lang['articles.published_articles'] = 'Articles publi�s';
$lang['articles.select_page'] = 'S�lectionnez une page';
$lang['articles.sources'] = 'Source(s)';
$lang['articles.summary'] = 'Sommaire';
$lang['articles.not_published'] = 'Cet article n\'est pas encore publi�';
$lang['articles.print.article'] = 'Impression d\'un article';
$lang['articles.tags'] = 'Mots cl�s';
$lang['articles.read_more'] = 'Lire plus...';
$lang['articles.date_updated'] = 'Derni�re modification : ';

//Articles configuration
$lang['articles_configuration.number_articles_per_page'] = 'Nombre maximum d\'articles affich�s par page';
$lang['articles_configuration.number_categories_per_page'] = 'Nombre de cat�gories maximum affich�es par page';
$lang['articles_configuration.display_type'] = 'Type d\'affichage des articles';
$lang['articles_configuration.display_type.mosaic'] = 'Mosa�que';
$lang['articles_configuration.display_type.list'] = 'Liste';
$lang['articles_configuration.notation_scale'] = 'Echelle de notation';
$lang['articles_configuration.authorizations.explain'] = 'Vous d�finissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque cat�gorie';

//Category
$lang['admin.categories.manage'] = 'G�rer les cat�gories';
$lang['admin.categories.add'] = 'Ajouter une cat�gorie';
$lang['admin.categories.edit'] = 'Modifier une cat�gorie';
$lang['admin.categories.delete'] = 'Supprimer une cat�gorie';
$lang['delete_category.explain'] = 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous :<br />
									<ol><li>Supprimer l\'ensemble de la cat�gorie (articles et sous-cat�gories).<b>Attention, cette derni�re action est irr�versible !</b></li>
									<li>Vous pouvez d�placer l\'ensemble de son contenu (articles et sous-cat�gories) dans une autre cat�gorie</li></ol>';
$lang['delete_category.choice_solution'] = 'D�placer ou supprimer';
$lang['delete_category.choice_1'] = 'Supprimer la cat�gorie et tout son contenu';
$lang['delete_category.choice_2'] = 'D�placer son contenu dans :';
$lang['delete_category.success-saving'] = 'La solution que vous avez choisie a �t� effectu�e avec succ�s !';

//Form
$lang['articles.form.title'] = 'Titre';
$lang['articles.form.description'] = 'Description (maximum :number caract�res)';
$lang['articles.form.description_enabled'] = 'Activer la description de l\'article';
$lang['articles.form.description_enabled.description'] = 'ou laissez PHPBoost couper le contenu � :number caract�res';
$lang['articles.form.rewrited_title'] = 'Titre de l\'article dans l\'url';
$lang['articles.form.rewrited_title.personalize'] = 'Personnaliser le titre de l\'article dans l\'url';
$lang['articles.form.rewrited_title.description'] = 'Doit contenir uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['articles.form.add_page'] = 'Ins�rer une page';
$lang['articles.form.author_name_displayed'] = 'Afficher le nom de l\'auteur';
$lang['articles.form.notation_enabled'] = 'Activer la notation de l\'article';
$lang['articles.form.picture'] = 'Image de l\'article';
$lang['articles.form.picture.description'] = 'S�lectionnez l\'image ou entrez une url (261x214)';
$lang['articles.form.keywords.description'] = 'Vous permet d\'ajouter des mots cl�s � votre article';
$lang['articles.form.source_name'] = 'Nom de la source';
$lang['articles.form.source_url'] = 'Url de la source';
$lang['articles.form.contribution_entitled'] = '[Article] :title';

//Sort fields title and mode
$lang['articles.sort_filter_title'] = 'Trier par :';
$lang['articles.sort_field.date'] = 'Date';
$lang['articles.sort_field.title'] = 'Titre';
$lang['articles.sort_field.views'] = 'Vues';
$lang['articles.sort_field.com'] = 'Commentaire';
$lang['articles.sort_field.note'] = 'Note';
$lang['articles.sort_field.author'] = 'Auteur';
$lang['articles.sort_mode.asc'] = 'Ascendant';
$lang['articles.sort_mode.desc'] = 'Descendant';

$lang['admin.articles.sort_field.cat'] = 'Cat�gories';
$lang['admin.articles.sort_field.title'] = 'Titre';
$lang['admin.articles.sort_field.author'] = 'Auteur';
$lang['admin.articles.sort_field.date'] = 'Date';
$lang['admin.articles.sort_field.published'] = 'Publi�';

//SEO
$lang['articles.seo.description.root'] = 'Tous les articles du site :site.';
$lang['articles.seo.description.tag'] = 'Tous les articles sur le sujet :subject.';
$lang['articles.seo.description.pending'] = 'Tous les articles en attente.';

?>