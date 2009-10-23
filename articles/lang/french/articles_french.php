<?php
/*##################################################
 *                              articles_french.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
#                                                          French                                                                        #
####################################################

//Erreurs
$LANG['e_unexist_articles'] = 'L\'article que vous avez demand� n\'existe pas';


global $ARTICLES_LANG;
// contribution
$ARTICLES_LANG = array(
	'articles_management' => 'Gestion des articles',
	'recount' => 'Recompter',
	'explain_articles_count' => 'Recompter le nombre d\'articles par cat�gories',
	'nbr_articles_max' => 'Nombre maximum d\'articles affich�s',
	'alert_delete_article' => 'Supprimer cet article ?',
	'select_page' => 'S�lectionnez une page',
	'summary' => 'Sommaire',
	'articles' => 'Articles',
	'title_articles' => 'Articles',
	'xml_articles_desc' => 'Derniers articles',
	'nbr_articles_info' => '%d article(s) dans la cat�gorie',
	'none_article' => 'Aucun article dans cette cat�gorie',
	'sub_categories' => 'Sous cat�gories',
	'written_by' => 'Ecrit par',
	'page_prompt' => 'Titre de la nouvelle page',
	'articles_add' => 'Ajouter un article',
	'article_icon' => 'Ic�ne de l\'article',
	'cat_icon' => 'Ic�ne de la cat�gorie',
	'articles_date' => 'Date de l\'article <span class="text_small">(jj/mm/aa)</span> <br />
	<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)',
	'explain_page' => 'Ins�rer une nouvelle page',
	'contribution_confirmation' => 'Confirmation de contribution',
	'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>',
	'contribution_counterpart' => 'Compl�ment de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet article au site). Ce champ est facultatif.',
	'contribution_entitled' => '[Articles] %s',
	'contribution_success' => 'Votre contribution a bien �t� enregistr�e.',
	'global_auth' => 'Permissions globales',
	'global_auth_explain' => 'Vous d�finissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque cat�gorie',
	'auth_contribute' => 'Permissions de contribution',
	'auth_moderate' => 'Permissions de mod�ration des contributions',
	'auth_read' => 'Permissions de lecture',
	'auth_write' => 'Permissions d\'�criture',
	'add_articles' => 'Ajouter un article',
	'release_date' => 'Date de parution',
	'removing_category' => 'Suppression d\'une cat�gorie',
	'require_cat' => 'Veuillez choisir une cat�gorie !',
	'articles_date' => 'Date de l\'article',
	'required_fields_empty' => 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement',	
	'category_name' => 'Nom de la cat�gorie',
	'category_location' => 'Emplacement de la cat�gorie',
	'category_desc' => 'Description de la cat�gorie',
	'category_image' => 'Image de la cat�gorie',
	'special_auth' => 'Permissions sp�ciales',
	'special_auth_explain' => 'Par d�faut la cat�gorie aura la configuration g�n�rale du module. Vous pouvez lui appliquer des permissions particuli�res.',
	'articles_management' => 'Gestion des articles',
	'add_category' => 'Ajouter une cat�gorie',
	'configuration_articles' => 'Configuration des articles',
	'category_articles' => 'Gestion des cat�gories',
	'required_fields_empty' => 'Des champs requis n\'ont pas �t� renseign�s, merci de renouveler l\'op�ration correctement',
	'unexisting_category' => 'La cat�gorie que vous avez s�lectionn�e n\'existe pas',
	'new_cat_does_not_exist' => 'La cat�gorie cible n\'existe pas',
	'infinite_loop' => 'Vous voulez d�placer la cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui n\'a pas de sens. Merci de choisir une autre cat�gorie',
	'successful_operation' => 'L\'op�ration que vous avez demand�e a �t� effectu�e avec succ�s',
	'explain_removing_category' => 'Vous �tes sur le point de supprimer la cat�gorie. Deux solutions s\'offrent � vous. Vous pouvez soit d�placer l\'ensemble de son contenu (articles et sous cat�gories) dans une autre cat�gorie soit supprimer l\'ensemble de son cat�gorie. <strong>Attention, cette action est irr�versible !</strong>',
	'delete_category_and_its_content' => 'Supprimer la cat�gorie et tout son contenu',
	'move_category_content' => 'D�placer son contenu dans :',
	'edit_articles' => '�diter l\'article',
	'contribution_confirmation' => 'Confirmation de contribution',
	'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et �ventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir particip� � la vie du site !</p>',
	'contribution_counterpart' => 'Compl�ment de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet article au site). Ce champ est facultatif.',
	'contribution_entitled' => '[Articles] %s',
	'contribution_success' => 'Votre contribution a bien �t� enregistr�e.',
	'notice_contribution' => 'Vous n\'�tes pas autoris� � cr�er un article, cependant vous pouvez proposer un article. Votre contribution suivra le parcours classique et sera trait�e dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de fa�on � expliquer votre d�marche � un approbateur.',
	'use_tab'=>"Utilisation des onglets pour la pagination des articles",
	'or_direct_path' => 'Ou chemin direct',
	'waiting_articles' => 'Articles en attentes',
	'no_articles_available' => 'Aucun articles disponible pour le moment',
	
	'article_description'=>"Description de l'article",
	'no_articles_waiting'=>'Aucun articles en attente disponible pour le moment',
	'publicate_articles'=>'Articles publi�s',
	'cat_tpl' => 'Templates de la cat�gorie',
	'articles_tpl' => 'Templates des articles',
	'tpl_explain' => 'Vous d�finissez ici des templates personnalis�s � utiliser pour les articles et la cat�gories courante.',
	'tpl'=>'Templates personnalis�s',
	'source'=>'Sources',
	'add_source'=>'Ajouter une source',
	'source_link'=>'URL de la source',
	'special_auth_explain_articles' => 'Par d�faut l\'article aura la configuration g�n�rale de sa cat�gorie. Vous pouvez lui appliquer des permissions particuli�res.',
	'special_option_explain' => 'Par d�faut l\'article aura la configuration g�n�rale de sa cat�gorie. Vous pouvez lui appliquer des options particul�res.',
	'special_option' => 'Options sp�ciales',
	'articles_mini_config'=> 'Configuration du mini module',
	'nbr_articles_mini'=> 'Nombre d\'articles � afficher',		
	'mini_type'=> 'Type de classement',	
	'articles_best_note' => 'Articles les mieux not�s',
	'articles_more_com' => 'Articles ayant le plus de commentaire',
	'articles_by_date' => 'Derniers articles',
	'articles_most_popular' => 'Articles les plus populaires',
	'author' => 'Auteur',
	'more_article' => 'Plus d\'article',
	'hide'=>'Cacher',
	'enable'=>'Activer',
	'desable'=>'D�sactiver',
	'mail_articles'=>'Envoyer le lien de l\'article par mail',
	'mail_recipient'=>'E-mail du destinataire',
	'sender'=>'Exp�diteur',
	'user_mail'=>'Votre adresse e-mail',
	'subject'=>'Sujet',
	'admin_invalid_email_error' => 'Mail invalide',
	'require_sender'=> 'Veuillez remplir le champs exp�diteur',
	'require_subject'=> ' Veuillez remplir le champs sujet',
	'admin_email_error' => 'L\'adresse de courier �lectronique que vous avez entr�e n\'a pas une forme correcte',
	'link_mail'=>'Envoyer ce lien � un ami',
	'admin_link_mail'=>'Autoriser l\'envoie du liens d\'un article par mail',
	'order_by'=>'Trier par ',
	'extend_field'=>'Champs supl�mentaires',
	'extend_field_explain' => 'Vous pouvez d�clarer ici des champs supl�mentaires pour les articles de cette cat�gorie',
	'extend_field_name'=>'Nom du champ',
	'extend_field_type'=>'Type de champ',
	'extend_field_add'=>'Ajouter un champ',
	'successful_send_mail'=>'Votre mail a �t� envoy� avec succ�s',
	'error_send_mail'=>'Une erreur est survenue veuillez r�essayer plutard',
	'text_link_mail' =>'Ceci est un e-mail de (%s) envoy� par %s (%s). Ce lien pourrait vous int�resser: %s %s',
);
?>