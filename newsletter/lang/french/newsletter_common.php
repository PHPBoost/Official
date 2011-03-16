<?php
/*##################################################
 *                             newsletter_common.php
 *                            -------------------
 *   begin                :  March 11, 2011
 *   copyright            : (C) 2011 MASSY K�vin
 *   email                : soldier.weasel@gmail.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
 ####################################################

$lang = array();
$lang['newsletter'] = 'Newsletter';
$lang['admin.newsletter-config'] = 'Configuration';
$lang['admin.newsletter-archives'] = 'Archives';
$lang['admin.newsletter-subscribers'] = 'Liste des inscrits';
$lang['admin.newsletter-streams'] = 'Gestion des flux';
$lang['subscribe.newsletter'] = 'S\'abonner aux newsletters';
$lang['subscriber.edit'] = 'Editer un inscrit';

$lang['admin.mail-sender'] = 'Adresse d\'envoi';
$lang['admin.mail-sender-explain'] = 'Adresse mail valide';
$lang['admin.newsletter-name'] = 'Nom de la newsletter';
$lang['admin.newsletter-name-explain'] = 'Objet du mail envoy�';

$lang['admin.newsletter-authorizations'] = 'Autorisations';
$lang['auth.read'] = 'Autorisations d\'acc�s aux flux';
$lang['auth.archives-read'] = 'Autorisations de lecture des archives';
$lang['auth.subscribers-read'] = 'Autorisations de lecture de la liste des inscrits';
$lang['auth.subscribers-moderation'] = 'Autorisations de mod�rer les inscrits';
$lang['auth.subscribe'] = 'Autorisations de s\'enregistrer aux newsletters';
$lang['auth.create-newsletter'] = 'Autorisations de cr�er une newsletter';

$lang['subscribers.list'] = 'Liste des inscrits';
$lang['subscribers.pseudo'] = 'Pseudo';
$lang['subscribers.mail'] = 'Mail';
$lang['subscribers.delete'] = 'Voulez vous vraiment supprimer cette personne des inscrits ?';
$lang['subscribers.no_users'] = 'Aucun inscrits';

$lang['newsletters_names'] = 'Noms des newsletters';
$lang['subscribers.visitor'] = 'Visiteur';

//Categories
$lang['streams.add'] = 'Ajouter un flux';
$lang['streams.edit'] = 'Modifier un flux';
$lang['streams.name'] = 'Nom';
$lang['streams.description'] = 'Description';
$lang['streams.picture'] = 'Image de repr�sentation';
$lang['streams.visible'] = 'Afficher';
$lang['streams.picture-preview'] = 'Pr�visualiser l\'image du flux';
$lang['streams.auth.read'] = 'Autorisations d\'acc�s au flux';
$lang['streams.auth.subscribers-read'] = 'Autorisations de lecture des inscrits';
$lang['streams.auth.subscribers-moderation'] = 'Autorisations de mod�ration des inscrits';
$lang['streams.auth.create-newsletter'] = 'Autorisations de cr�er une newsletter';
$lang['streams.auth.subscribe'] = 'Autorisations de s\'enregistrer � la newsletter';
$lang['streams.auth.archives-read'] = 'Autorisations de lecture des archives';
$lang['streams.active-advanced-authorizations'] = 'Activer les autorisations avanc�s du flux';
$lang['streams.visible-no'] = 'Non';
$lang['streams.visible-yes'] = 'Oui';
$lang['streams.no_cats'] = 'Aucun flux';

//Newsletter
$lang['newsletter-add'] = 'Ajouter une newsletter';
$lang['newsletter.title'] = 'Titre de la newsletter';
$lang['newsletter.contents'] = 'Contenu';

//Subscribe
$lang['newsletter.subscribe_newsletters'] = 'S\'abonner � une newsletter';
$lang['subscribe.mail'] = 'Mail';
$lang['subscribe.newsletter_choice'] = 'Choisissez les newsletters auquelles vous souhaitez vous abonner';

// Unscribe
$lang['newsletter.unsubscribe_newsletters'] = 'Ce d�sabonner � une newsletter';
$lang['newsletter.delete_all_streams'] = 'Se d�sinscrire de tout les flux';
$lang['unsubscribe.newsletter'] = 'Se d�sinscrire des newsletters';
$lang['unsubscribe.newsletter_choice'] = 'Choisissez les newsletters ou vous souhaitez rester abonner';


//Types
$lang['newsletter.types.choice'] = 'Veuillez s�l�ctionner un type de message';
$lang['newsletter.types.null'] = '--';
$lang['newsletter.types.text'] = 'Texte';
$lang['newsletter.types.text_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous ne pourrez proc�der � aucune mise en forme du message.';
$lang['newsletter.types.bbcode'] = 'BBCode';
$lang['newsletter.types.bbcode_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous pouvez formater le texte gr�ce au BBCode, le langage de mise en forme simplifi�e adopt� sur tout le portail.';
$lang['newsletter.types.html'] = 'HTML';
$lang['newsletter.types.html_explain'] = '<span style="color:red;"><strong>Utilisateurs exp�riment�s seulement</strong></span><br />Vous pouvez mettre en forme le texte � votre guise, mais vous devez conna�tre le langage html.';
$lang['newsletter.types.next'] = 'Suivant';

//Other
$lang['newsletter.page'] = 'Page';
$lang['newsletter.list_newsletters'] = 'Listes des newsletters';
$lang['newsletter.no_newsletters'] = 'Aucunes newsletter de disponible';
$lang['newsletter.view_archives'] = 'Voir les archives';
$lang['newsletter.number-subscribers'] = 'Vous avez :number personnes d\'inscrits � cette newsletter';

//Errors
$lang['admin.success-saving-config'] = 'Vous avez modifi� avec succ�s la configuration';
$lang['admin.success-add-categorie'] = 'Cat�gorie ajout� avec succ�s';
$lang['admin.categorie-not-existed'] = 'La cat�gorie demand� n\'existe pas';
$lang['admin.success-add-newsletter'] = 'La cat�gorie � bien �t� ajout�';
$lang['admin.success-edit-categorie'] = 'La cat�gorie � bien �t� modifi�';
$lang['admin.success-delete-categorie'] = 'La cat�gorie � bien �t� supprim�';
$lang['success-subscribe'] = 'Vous vous �tes inscrit au(x) newsletter(s) avec succ�s';
$lang['success-unsubscribe'] = 'Vous vous �tes d�sinscrit des newsletters avec succ�s';
$lang['success-delete-subscriber'] = 'Vous avez supprim� la personne inscrite avec succ�s';
$lang['success-edit-subscriber'] = 'Vous avez �dit� la personne inscrite avec succ�s';
$lang['error-subscriber-not-existed'] = 'L\'incrit n\'existe pas';

$lang['newsletter.not_level'] = 'Vous n\'avez pas les autorisations';

//Register extended field
$lang['extended_fields.newsletter.name'] = 'S\'abonner aux newsletters';
$lang['extended_fields.newsletter.description'] = 'S�l�ctionner le ou les newsletter dont vous souhaitez �tre inscrit';
?>