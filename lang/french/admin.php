<?php
/*##################################################
 *                                admin.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
#                    French                        #
 ####################################################

$lang = array();
 
$lang['xml_lang'] = 'fr';
$lang['administration'] = 'Administration';
$lang['no_administration'] = 'Aucune administration associ�e avec ce module!';

//Titre Modules par d�fauts
$lang['index'] = 'Index';
$lang['tools'] = 'Outils';
$lang['link_management'] = 'Gestion des liens';
$lang['menu_management'] = 'Menus';
$lang['moderation'] = 'Panneau mod�ration';
$lang['maintain'] = 'Maintenance';
$lang['updater'] = 'Mises � jour';
$lang['extend_field'] = 'Champs membres';
$lang['ranks'] = 'Rangs';
$lang['terms'] = 'R�glement';
$lang['pages'] = 'Pages';
$lang['files'] = 'Fichiers';
$lang['themes'] = 'Th�mes';
$lang['languages'] = 'Langues';
$lang['smile'] = 'Smileys';
$lang['comments'] = 'Commentaires';
$lang['group'] = 'Groupes';
$lang['stats'] = 'Statistiques';
$lang['errors'] = 'Erreurs archiv�es';
$lang['server'] = 'Serveur';
$lang['phpinfo'] = 'PHP info';
$lang['cache'] = 'Cache';
$lang['punishement'] = 'Sanction';
$lang['extend_menu'] = 'Menu �tendu';

//Form
$lang['add'] = 'Ajouter';

//Alertes formulaires
$lang['alert_same_pass'] = 'Les mots de passe ne sont pas identiques!';
$lang['alert_max_dim'] = 'Le fichier d�passe les largeurs et hauteurs maximales sp�cifi�es !';
$lang['alert_error_avatar'] = 'Erreur d\'enregistrement de l\'avatar!';
$lang['alert_error_img'] = 'Erreur d\'enregistrement de l\'image!';
$lang['alert_invalid_file'] = 'Le fichier image n\'est pas valide (jpg, gif, png!)';
$lang['alert_max_weight'] = 'Image trop lourde';
$lang['alert_s_already_use'] = 'Code du smiley d�j� utilis�!';
$lang['alert_no_cat'] = 'Aucun nom/cat�gorie saisi';
$lang['alert_fct_unlink'] = 'Suppression des miniatures impossible. Vous devez supprimer manuellement sur le ftp!';
$lang['alert_no_login'] = 'Le pseudo entr� n\'existe pas!';

//Requis
$lang['require'] = 'Les Champs marqu�s * sont obligatoires!';
$lang['require_title'] = 'Veuillez entrer un titre !';
$lang['require_text'] = 'Veuillez entrer un texte!';
$lang['require_password'] = 'Veuillez entrer un mot de passe!';
$lang['require_cat'] = 'Veuillez entrer une cat�gorie!';
$lang['require_cat_create'] = 'Aucune cat�gorie trouv�e, veuillez d\'abord en cr�er une';
$lang['require_serv'] = 'Veuillez entrer un nom pour le serveur!';
$lang['require_name'] = 'Veuillez entrer un nom!';
$lang['require_cookie_name'] = 'Veuillez entrer un nom de cookie!';
$lang['require_session_time'] = 'Veuillez entrer une dur�e pour la session!';
$lang['require_session_invit'] = 'Veuillez entrer une dur�e pour la session invit�!';
$lang['require_pass'] = 'Veuillez entrer un mot de passe!';
$lang['require_rank'] = 'Veuillez entrer un rang!';
$lang['require_code'] = 'Veuillez entrer un code pour le smiley!';
$lang['require_max_width'] = 'Veuillez entrer une largeur maximale pour les avatars!';
$lang['require_height'] = 'Veuillez entrer une hauteur maximale pour les avatars!';
$lang['require_weight'] = 'Veuillez entrer un poids maximum pour les avatars!';
$lang['require_rank_name'] = 'Veuillez entrer un nom pour le rang!';
$lang['require_nbr_msg_rank'] = 'Veuillez entrer un nombre de messages pour le rang!';
$lang['require_subcat'] = 'Veuillez s�lectionner une sous-cat�gorie!';
$lang['require_file_name'] = 'Vous devez saisir un nom de fichier';

//Confirmations.
$lang['redirect'] = 'Redirection en cours...';
$lang['del_entry'] = 'Supprimer l\'entr�e?';
$lang['confirm_del_member'] = 'Supprimer le membre? (d�finitif !)';
$lang['confirm_del_admin'] = 'Supprimer un admin? (irr�versible !)';
$lang['confirm_theme'] = 'Supprimer le th�me?';
$lang['confirm_del_smiley'] = 'Supprimer le smiley?';
$lang['confirm_del_cat'] = 'Supprimer cette cat�gorie ?';
$lang['confirm_del_article'] = 'Supprimer cet article?';
$lang['confirm_del_rank'] = 'Supprimer ce rang ?';
$lang['confirm_del_group'] = 'Supprimer ce groupe ?';
$lang['confirm_del_member_group'] = 'Supprimer ce membre du groupe ?';

//bbcode
$lang['bb_bold'] = 'Texte en gras : [b]texte[/b]';
$lang['bb_italic'] = 'Texte en italique : [i]texte[/i]';
$lang['bb_underline'] = 'Texte soulign� : [u]texte[/u]';
$lang['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$lang['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$lang['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$lang['bb_color'] = 'Couleur du texte : [color=X]texte de taille X[/color]';
$lang['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$lang['bb_code'] = 'Ins�rer du code (PHP color�) [code]texte[/code]';
$lang['bb_left'] = 'Positionner � gauche : [align=left]objet � gauche[/align]';
$lang['bb_center'] = 'Centrer : [align=center]objet centr�[/align]';
$lang['bb_right'] = 'Positionner � droite : [align=right]objet � droite[/align]';

//Commun
$lang['pseudo'] = 'Pseudo';
$lang['yes'] = 'Oui';
$lang['no'] = 'Non';
$lang['description'] = 'Description';
$lang['view'] = 'Vu';
$lang['views'] = 'Vues';
$lang['name'] = 'Nom';
$lang['title'] = 'Titre';
$lang['message'] = 'Message';
$lang['aprob'] = 'Approbation';
$lang['unaprob'] = 'D�sapprobation';
$lang['url'] = 'Adresse';
$lang['categorie'] = 'Cat�gorie';
$lang['note'] = 'Note';
$lang['date'] = 'Date';
$lang['com'] = 'Commentaires';
$lang['size'] = 'Taille';
$lang['file'] = 'Fichier';
$lang['download'] = 'T�l�charg�';
$lang['delete'] = 'Supprimer';
$lang['user_ip'] = 'Adresse ip';
$lang['localisation'] = 'Localisation';
$lang['activ'] = 'Activ�';
$lang['unactiv'] = 'D�sactiv�';
$lang['unactivate'] = 'D�sactiver';
$lang['activate'] = 'Activer';
$lang['img'] = 'Image';
$lang['activation'] = 'Activation';
$lang['position'] = 'Position';
$lang['path'] = 'Chemin';
$lang['on'] = 'Le';
$lang['at'] = '�';
$lang['registered'] = 'Enregistr�';
$lang['website'] = 'Site web';
$lang['search'] = 'Recherche';
$lang['mail'] = 'Mail';
$lang['password'] = 'Mot de passe';
$lang['contact'] = 'Contact';
$lang['info'] = 'Informations';
$lang['language'] = 'Langue';
$lang['sanction'] = 'Sanction';
$lang['ban'] = 'Banni';
$lang['theme'] = 'Th�me';
$lang['code'] = 'Code';
$lang['status'] = 'Statut';
$lang['question'] = 'Question';
$lang['answers'] = 'R�ponses';
$lang['archived'] = 'Archiv�';
$lang['galerie'] = 'Galerie' ;
$lang['select'] = 'S�lectionner';
$lang['pics'] = 'Photos';
$lang['empty'] = 'Vider';
$lang['show'] = 'Consulter';
$lang['link'] = 'Lien';
$lang['type'] = 'Type';
$lang['of'] = 'de';
$lang['autoconnect'] = 'Connexion automatique';
$lang['unspecified'] = 'Non sp�cifi�';
$lang['configuration'] = 'Configuration';
$lang['management'] = 'Gestion';
$lang['add'] = 'Ajouter';
$lang['category'] = 'Cat�gorie';
$lang['site'] = 'Site';
$lang['modules'] = 'Modules';
$lang['powered_by'] = 'Boost� par';
$lang['release_date'] = 'Date de parution jj/mm/aa';
$lang['immediate'] = 'Imm�diate';
$lang['waiting'] = 'En attente';
$lang['stats'] = 'Statistiques';
$lang['cat_management'] = 'Gestion des cat�gories';
$lang['cat_add'] = 'Ajouter une cat�gorie';
$lang['visible'] = 'Visible';
$lang['undefined'] = 'Ind�termin�';
$lang['nbr_cat_max'] = 'Nombre de cat�gories maximum affich�es';
$lang['nbr_column_max'] = 'Nombre de colonnes';
$lang['note_max'] = 'Echelle de notation';
$lang['max_link'] = 'Nombre de liens maximum dans le message';
$lang['max_link_explain'] = 'Mettre -1 pour illimit�';
$lang['generate'] = 'G�n�rer';
$lang['or_direct_path'] = 'Ou chemin direct';
$lang['unknow_bot'] = 'Bot inconnu';
$lang['captcha_difficulty'] = 'Difficult� du code de v�rification';

//Connexion
$lang['unlock_admin_panel'] = 'D�verrouillage de l\'administration';
$lang['flood_block'] = 'Il vous reste %d essai(s) apr�s cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$lang['flood_max'] = 'Vous avez �puis� tous vos essais de connexion, votre compte est verrouill� pendant 5 minutes';

//Rang
$lang['rank_management'] = 'Gestion des rangs';
$lang['upload_rank'] = 'Uploader une image de rang';
$lang['upload_rank_format'] = 'JPG, GIF, PNG, BMP autoris�s';
$lang['rank_add'] = 'Ajouter un rang';
$lang['rank'] = 'Rang';
$lang['special_rank'] = 'Rang sp�cial';
$lang['rank_name'] = 'Nom du Rang';
$lang['nbr_msg'] = 'Nombre de message(s)';
$lang['img_assoc'] = 'Image associ�e';
$lang['guest'] = 'Visiteur';
$lang['a_member'] = 'membre';
$lang['member'] = 'Membre';
$lang['a_modo'] = 'modo';
$lang['modo'] = 'Mod�rateur';
$lang['a_admin'] = 'admin';
$lang['admin'] = 'Administrateur';

//Index
$lang['update_available'] = 'Mises � jour disponibles';
$lang['core_update_available'] = 'Nouvelle version <strong>%s</strong> du noyau disponible, pensez � mettre � jour PHPBoost! <a href="http://www.phpboost.com">Plus d\'informations</a>';
$lang['no_core_update_available'] = 'Aucune nouvelle version disponible, le syst�me est � jour!';
$lang['module_update_available'] = 'Des mises � jour des modules sont disponibles!';
$lang['no_module_update_available'] = 'Aucune mise � jour des modules, vous �tes � jour!';
$lang['unknow_update'] = 'Impossible de d�terminer si une mise � jour est disponible!';
$lang['user_online'] = 'Utilisateur(s) en ligne';
$lang['last_update'] = 'Derni�re mise � jour';
$lang['quick_links'] = 'Liens rapides';
$lang['members_managment'] = 'Gestion des membres';
$lang['menus_managment'] = 'Gestion des menus';
$lang['modules_managment'] = 'Gestion des modules';
$lang['last_comments'] = 'Derniers commentaires';
$lang['view_all_comments'] = 'Voir tous les commentaires';
$lang['writing_pad'] = 'Bloc-notes';
$lang['writing_pad_explain'] = 'Cet emplacement est r�serv� pour y saisir vos notes personnelles.';

//Alertes administrateur
$lang['administrator_alerts'] = 'Alertes';
$lang['administrator_alerts_list'] = 'Liste des alertes';
$lang['no_unread_alert'] = 'Aucune alerte en attente';
$lang['unread_alerts'] = 'Des alertes non trait�es sont en attente.';
$lang['no_administrator_alert'] = 'Aucune alerte existante';
$lang['display_all_alerts'] = 'Voir toutes les alertes';
$lang['priority'] = 'Priorit�';
$lang['priority_very_high'] = 'Imm�diat';
$lang['priority_high'] = 'Urgent';
$lang['priority_medium'] = 'Moyenne';
$lang['priority_low'] = 'Faible';
$lang['priority_very_low'] = 'Tr�s faible';
$lang['administrator_alerts_action'] = 'Actions';
$lang['admin_alert_fix'] = 'R�gler';
$lang['admin_alert_unfix'] = 'Passer l\'alerte en non r�gl�e';
$lang['confirm_delete_administrator_alert'] = 'Etes-vous s�r de vouloir supprimer cette alerte ?';

//Config
$lang['config_main'] = 'Configuration g�n�rale';
$lang['auth_members'] = 'Permissions';
$lang['auth_read_members'] = 'Configuration des autorisations de lecture';
$lang['auth_read_members_explain'] = 'Vous d�finissez ici les permissions de lecture de la liste des membres ainsi que certaines informations personnelles comme leurs emails.';
$lang['config_advanced'] = 'Configuration avanc�e';
$lang['config_mail'] = 'Envoi de mail';
$lang['serv_name'] = 'URL du serveur';
$lang['serv_path'] = 'Chemin de PHPBoost';
$lang['serv_path_explain'] = 'Vide par d�faut : site � la racine du serveur';
$lang['site_name'] = 'Nom du site';
$lang['serv_name_explain'] = 'Ex : http://www.phpboost.com';
$lang['site_desc'] = 'Description du site';
$lang['site_desc_explain'] = '(facultatif) Utile pour le r�f�rencement dans les moteurs de recherche';
$lang['site_keywords'] = 'Mots cl�s du site';
$lang['site_keywords_explain'] = '(facultatif) A rentrer s�par�s par des virgules, ils servent au r�f�rencement dans les moteurs de recherche';
$lang['default_language'] = 'Langue (par d�faut) du site';
$lang['default_theme'] = 'Th�me (par d�faut) du site';
$lang['start_page'] = 'Page de d�marrage du site';
$lang['no_module_starteable'] = 'Aucun module de d�marrage trouv�';
$lang['other_start_page'] = 'Autre adresse relative ou absolue';
$lang['activ_gzhandler'] = 'Activation de la compression des pages, ceci acc�l�re la vitesse d\'affichage';
$lang['activ_gzhandler_explain'] = 'Attention votre serveur doit le supporter';
$lang['view_com'] = 'Affichage des commentaires';
$lang['rewrite'] = 'Activation de la r��criture des urls';
$lang['explain_rewrite'] = 'L\'activation de la r��criture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compr�hensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre r�f�rencement sera grandement optimis� gr�ce � cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les h�bergeurs. Cette page va vous permettre de tester si votre serveur supporte la r��criture des urls. Si apr�s le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> � la racine de votre site via acc�s FTP � votre serveur, puis revenez sur cette page et d�sactivez la r��criture.';
$lang['server_rewrite'] = 'R��criture des urls sur votre serveur';
$lang['htaccess_manual_content'] = 'Contenu du fichier .htaccess';
$lang['htaccess_manual_content_explain'] = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez int�grer au fichier .htaccess qui se trouve � la racine du site, par exemple pour forcer une configuration du serveur web Apache.';
$lang['current_page'] = 'Page courante';
$lang['new_page'] = 'Nouvelle fen�tre';
$lang['compt'] = 'Compteur';
$lang['bench'] = 'Benchmark';
$lang['bench_explain'] = 'Affiche le temps de rendu de la page et le nombre de requ�tes SQL';
$lang['theme_author'] = 'Info sur le th�me';
$lang['theme_author_explain'] = 'Affiche des informations sur le th�me dans le pied de page';
$lang['debug_mode'] = 'Mode Debug';
$lang['debug_mode_explain'] = 'Ce mode est particuli�rement utile pour les d�veloppeurs car les erreurs sont affich�es explicitement. Il est d�conseill� d\'utiliser ce mode sur un site en production.';
$lang['user_connexion'] = 'Connexion utilisateurs';
$lang['cookie_name'] = 'Nom du cookie des sessions';
$lang['session_time'] = 'Dur�e de la session';
$lang['session_time_explain'] = '3600 secondes conseill�';
$lang['session invit'] = 'Dur�e utilisateurs actifs';
$lang['session invit_explain'] = '300 secondes conseill�';
$lang['post_management'] = 'Gestion des posts';
$lang['pm_max'] = 'Nombre maximum de messages priv�s';
$lang['anti_flood'] = 'Anti-flood';
$lang['int_flood'] = 'Intervalle minimal de temps entre les messages';
$lang['pm_max_explain'] = 'Illimit� pour administrateurs et mod�rateurs';
$lang['anti_flood_explain'] = 'Emp�che les messages trop rapproch�s, sauf si les visiteurs sont autoris�s';
$lang['int_flood_explain'] = '7 secondes par d�faut';
$lang['confirm_unlock_admin'] = 'Un email va vous �tre envoy� avec le code de d�verrouillage';
$lang['unlock_admin_confirm'] = 'Le code de d�verrouillage a �t� renvoy� avec succ�s';
$lang['unlock_admin'] = 'Code de d�verrouillage';
$lang['unlock_admin_explain'] = 'Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.';
$lang['send_unlock_admin'] = 'Renvoyer le code de d�verrouillage';
$lang['unlock_title_mail'] = 'Mail � conserver';
$lang['unlock_mail'] = 'Code � conserver (Il ne vous sera plus d�livr�) : %s

Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.
Il vous sera demand� dans le formulaire de connexion directe � l\'administration (votreserveur/admin/admin_index.php)

' . MailServiceConfig::load()->get_mail_signature();

//Maintain
$lang['maintain_auth'] = 'Autorisation d\'acc�s au site durant la maintenance';
$lang['maintain_for'] = 'Mettre le site en maintenance';
$lang['maintain_delay'] = 'Afficher la dur�e de la maintenance';
$lang['maintain_display_admin'] = 'Afficher la dur�e de la maintenance � l\'administrateur';
$lang['maintain_text'] = 'Texte � afficher lorsque la maintenance du site est en cours';

//Gestion des modules
$lang['modules_management'] = 'Gestion des modules';
$lang['add_modules'] = 'Ajouter un module';
$lang['update_modules'] = 'Mettre � jour un module';
$lang['update_module'] = 'Mettre � jour';
$lang['upload_module'] = 'Uploader un module';
$lang['del_module'] = 'Supprimer le module';
$lang['del_module_data'] = 'Les donn�es du module vont �tre supprim�es, attention vous ne pourrez plus les r�cup�rer!';
$lang['del_module_files'] = 'Supprimer les fichiers du module';
$lang['author'] = 'Auteurs';
$lang['compat'] = 'Compatibilit�';
$lang['use_sql'] = 'Utilise SQL';
$lang['use_cache'] = 'Utilise le cache';
$lang['alternative_css'] = 'Utilise un css alternatif';
$lang['modules_installed'] = 'Modules install�s';
$lang['modules_available'] = 'Modules disponibles';
$lang['no_modules_installed'] = 'Aucun module install�';
$lang['no_modules_available'] = 'Aucun module disponible';
$lang['install'] = 'Installer';
$lang['uninstall'] = 'D�sinstaller';
$lang['starteable_page'] = 'Page de d�marrage';
$lang['table'] = 'Table';
$lang['tables'] = 'Tables';
$lang['new_version'] = 'Nouvelle version';
$lang['installed_version'] = 'Version install�e';
$lang['e_config_conflict'] = 'Conflit avec la configuration du module, installation impossible!';

//Rapport syst�me
$lang['system_report'] = 'Rapport syst�me';
$lang['server'] = 'Serveur';
$lang['php_version'] = 'Version de PHP';
$lang['dbms_version'] = 'Version du SGBD';
$lang['dg_library'] = 'Librairie GD';
$lang['url_rewriting'] = 'R��criture des URL';
$lang['register_globals_option'] = 'Option <em>register globals</em>';
$lang['phpboost_config'] = 'Configuration de PHPBoost';
$lang['kernel_version'] = 'Version du noyau';
$lang['output_gz'] = 'Compression des pages';
$lang['directories_auth'] = 'Autorisation des r�pertoires';
$lang['system_report_summerization'] = 'R�capitulatif';
$lang['system_report_summerization_explain'] = 'Ceci est le r�capitulatif du rapport. Cela vous sera particuli�rement utile lorsque pour du support on vous demandera la configuration de votre syst�me';

//Gestion de l'upload
$lang['explain_upload_img'] = 'L\'image upload�e doit �tre au format jpg, gif, png ou bmp';
$lang['explain_archive_upload'] = 'L\'archive upload�e doit �tre au format zip ou gzip';

//Gestion des fichiers
$lang['auth_files'] = 'Autorisation requise pour l\'activation de l\'interface de fichiers';
$lang['size_limit'] = 'Taille maximale des uploads autoris�s aux membres';
$lang['bandwidth_protect'] = 'Protection de la bande passante';
$lang['bandwidth_protect_explain'] = 'Interdiction d\'acc�s aux fichiers du r�pertoire upload depuis un autre serveur';
$lang['auth_extensions'] = 'Extensions autoris�es';
$lang['extend_extensions'] = 'Extensions autoris�es suppl�mentaires';
$lang['extend_extensions_explain'] = 'S�parez les extensions avec des virgules';
$lang['files_image'] = 'Images';
$lang['files_archives'] = 'Archives';
$lang['files_text'] = 'Textes';
$lang['files_media'] = 'Media';
$lang['files_prog'] = 'Programmation';
$lang['files_misc'] = 'Divers';

//Gestion des menus
$lang['confirm_del_menu'] = 'Supprimer ce menu?';
$lang['confirm_delete_element'] = 'Voulez vous vraiment supprimer cet �l�ment?';
$lang['menus_management'] = 'Gestion des menus';
$lang['menus_content_add'] = 'Menu de contenu';
$lang['menus_links_add'] = 'Menu de liens';
$lang['menus_feed_add'] = 'Menu de flux';
$lang['menus_edit'] = 'Modifier le menu';
$lang['menus_add'] = 'Ajouter un menu';
$lang['vertical_menu'] = 'Menu vertical';
$lang['horizontal_menu'] = 'Menu horizontal';
$lang['tree_menu'] = 'Menu arborescent';
$lang['vertical_scrolling_menu'] = 'Menu vertical d�roulant';
$lang['horizontal_scrolling_menu'] = 'Menu horizontal d�roulant';
$lang['available_menus'] = 'Menus disponibles';
$lang['no_available_menus'] = 'Aucun menu disponible';
$lang['menu_header'] = 'T�te de page';
$lang['menu_subheader'] = 'Sous ent�te';
$lang['menu_left'] = 'Menu gauche';
$lang['menu_right'] = 'Menu droit';
$lang['menu_top_central'] = 'Menu central haut';
$lang['menu_bottom_central'] = 'Menu central bas';
$lang['menu_top_footer'] = 'Sur pied de page';
$lang['menu_footer'] = 'Pied de page';
$lang['location'] = 'Emplacement';
$lang['use_tpl'] = 'Utiliser la structure des templates';
$lang['add_sub_element'] = 'Ajouter un �l�ment';
$lang['add_sub_menu'] = 'Ajouter un sous-menu';
$lang['display_title'] = 'Afficher le titre';
$lang['choose_feed_in_list'] = 'Veuillez choisir un flux dans la liste';
$lang['feed'] = 'flux';
$lang['availables_feeds'] = 'Flux disponibles';
$lang['valid_position_menus'] = 'Valider la position des menus';
$lang['themes_management'] = 'G�rer le th�me';

$lang['menu_configurations'] = 'Configurations';
$lang['menu_configurations_list'] = 'Liste des configurations de menus';
$lang['menus'] = 'Menus';
$lang['menu_configuration_name'] = 'Nom';
$lang['menu_configuration_match_regex'] = 'Correspond �';
$lang['menu_configuration_edit'] = 'Editer';
$lang['menu_configuration_configure'] = 'Configurer';
$lang['menu_configuration_default_name'] = 'Configuration par d�faut';
$lang['menu_configuration_configure_default_config'] = 'Configurer la configuration par d�faut';
$lang['menu_configuration_edition'] = 'Edition d\'une configuration de menu';
$lang['menu_configuration_edition_name'] = 'Nom de la configuration';
$lang['menu_configuration_edition_match_regex'] = 'Expression r�guli�re de correspondance';

//Gestion du contenu
$lang['content_config'] = 'Contenu';
$lang['content_config_extend'] = 'Configuration du contenu';
$lang['default_formatting_language'] = 'Langage de formatage du contenu par d�faut du site
<span style="display:block;">Chaque utilisateur pourra choisir</span>';
$lang['content_language_config'] = 'Langage de formatage';
$lang['content_html_language'] = 'Langage HTML';
$lang['content_auth_use_html'] = 'Niveau d\'autorisation pour ins�rer du langage HTML
<span style="display:block">Attention : le code HTML peut contenir du code Javascript qui peut constituer une source de faille de s�curit� si quelqu\'un y ins�re un code malveillant. Veillez donc � n\'autoriser seulement les personnes de confiance � ins�rer du HTML.</span>';

//Smiley
$lang['upload_smiley'] = 'Uploader un smiley';
$lang['smiley'] = 'Smiley';
$lang['add_smiley'] = 'Ajouter smiley';
$lang['smiley_code'] = 'Code du smiley (ex : :D)';
$lang['smiley_available'] = 'Smileys disponibles';
$lang['edit_smiley'] = 'Edition des smileys';
$lang['smiley_management'] = 'Gestion des smileys';
$lang['e_smiley_already_exist'] = 'Le smiley existe d�j�';

//Th�mes
$lang['upload_theme'] = 'Uploader un th�me';
$lang['theme_on_serv'] = 'Th�mes disponibles sur le serveur';
$lang['no_theme_on_serv'] = 'Aucun th�me <strong>compatible</strong> disponible sur le serveur';
$lang['theme_management'] = 'Gestion des th�mes';
$lang['theme_add'] = 'Ajouter un th�me';
$lang['theme'] = 'Th�me';
$lang['e_theme_already_exist'] = 'Le th�me existe d�j�';
$lang['xhtml_version'] = 'Version Html';
$lang['css_version'] = 'Version Css';
$lang['main_colors'] = 'Couleurs dominantes';
$lang['width'] = 'Largeur';
$lang['exensible'] = 'Extensible';
$lang['del_theme'] = 'Suppression du th�me';
$lang['del_theme_files'] = 'Supprimer tous les fichiers du th�me';
$lang['explain_default_theme'] = 'Le th�me par d�faut ne peut pas �tre d�sinstall�, d�sactiv�, ou r�serv�';
$lang['activ_left_column'] = 'Activer la colonne de gauche';
$lang['activ_right_column'] = 'Activer la colonne de droite';
$lang['manage_theme_columns'] = 'G�rer les colonnes du th�me';

//Langues
$lang['upload_lang'] = 'Uploader une langue';
$lang['lang_on_serv'] = 'Langues disponibles sur le serveur';
$lang['no_lang_on_serv'] = 'Aucune langue disponible sur le serveur';
$lang['lang_management'] = 'Gestion des langues';
$lang['lang_add'] = 'Ajouter une langue';
$lang['lang'] = 'Langue';
$lang['e_lang_already_exist'] = 'La langue existe d�j�';
$lang['del_lang'] = 'Suppression de la langue';
$lang['del_lang_files'] = 'Supprimer les fichiers de la langue';
$lang['explain_default_lang'] = 'La langue par d�faut ne peut pas �tre d�sinstall�e, d�sactiv�e ou r�serv�e';

//Comments
$lang['com_management'] = 'Gestion des commentaires';
$lang['com_config'] = 'Configuration des commentaires';
$lang['com_max'] = 'Nombre de commentaires par page';
$lang['rank_com_post'] = 'Rang pour pouvoir poster des commentaires';
$lang['display_topic_com'] = 'Voir la discussion';
$lang['display_recent_com'] = 'Voir les derniers commentaires';

//Gestion membre
$lang['job'] = 'Emploi';
$lang['hobbies'] = 'Loisirs';
$lang['members_management'] = 'Gestion des Membres';
$lang['members_add'] = 'Ajouter un membre';
$lang['members_config'] = 'Configuration des membres';
$lang['members_punishment'] = 'Gestion des sanctions';
$lang['members_msg'] = 'Message � tous les membres';
$lang['search_member'] = 'Rechercher un membre';
$lang['joker'] = 'Utilisez * pour joker';
$lang['no_result'] = 'Aucun r�sultat';
$lang['minute'] = 'minute';
$lang['minutes'] = 'minutes';
$lang['hour'] = 'heure';
$lang['hours'] = 'heures';
$lang['day'] = 'jour';
$lang['days'] = 'jours';
$lang['week'] = 'semaine';
$lang['month'] = 'mois';
$lang['life'] = 'A vie';
$lang['confirm_password'] = 'Confirmer le mot de passe';
$lang['confirm_password_explain'] = 'Remplir seulement en cas de modification';
$lang['hide_mail'] = 'Cacher l\'email';
$lang['hide_mail_explain'] = 'Aux autres utilisateurs';
$lang['website_explain'] = 'Valide sinon non pris en compte';
$lang['member_sign'] = 'Signature';
$lang['member_sign_explain'] = 'Appara�t sous chacun de vos messages';
$lang['avatar_management'] = 'Gestion avatar';
$lang['activ_up_avatar'] = 'Autoriser l\'upload d\'avatar sur le serveur';
$lang['enable_auto_resizing_avatar'] = 'Activer le redimensionnement automatique des images';
$lang['enable_auto_resizing_avatar_explain'] = 'Attention votre serveur doit avoir l\'extension GD charg�e';
$lang['current_avatar'] = 'Avatar actuel';
$lang['upload_avatar'] = 'Uploader avatar';
$lang['upload_avatar_where'] = 'Avatar directement h�berg� sur le serveur';
$lang['avatar_link'] = 'Lien avatar';
$lang['avatar_link_where'] = 'Adresse directe de l\'avatar';
$lang['avatar_del'] = 'Supprimer l\'avatar courant';
$lang['no_avatar'] = 'Aucun avatar';
$lang['weight_max'] = 'Poids maximum';
$lang['height_max'] = 'Hauteur maximale';
$lang['width_max'] = 'Largeur maximale';
$lang['sex'] = 'Sexe';
$lang['male'] = 'Homme';
$lang['female'] = 'Femme';
$lang['verif_code'] = 'Code de v�rification visuel';
$lang['verif_code_explain'] = 'Bloque les robots';
$lang['delay_activ_max'] = 'Dur�e apr�s laquelle les membres non activ�s sont effac�s';
$lang['delay_activ_max_explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$lang['activ_mbr'] = 'Mode d\'activation du compte membre';
$lang['no_activ_mbr'] = 'Automatique';
$lang['allow_theme_mbr'] = 'Permission aux membres de choisir leur th�me';
$lang['width_max_avatar'] = 'Largeur maximale de l\'avatar';
$lang['width_max_avatar_explain'] = 'Par d�faut 120';
$lang['height_max_avatar'] = 'Hauteur maximale de l\'avatar';
$lang['height_max_avatar_explain'] = 'Par d�faut 120';
$lang['weight_max_avatar'] = 'Poids maximal de l\'avatar en ko';
$lang['weight_max_avatar_explain'] = 'Par d�faut 20';
$lang['avatar_management'] = 'Gestion des avatars';
$lang['activ_defaut_avatar'] = 'Activer l\'avatar par d�faut';
$lang['activ_defaut_avatar_explain'] = 'Met un avatar aux membres qui n\'en ont pas';
$lang['url_defaut_avatar'] = 'Adresse de l\'avatar par d�faut';
$lang['url_defaut_avatar_explain'] = 'Mettre dans le dossier images de votre th�me ';
$lang['user_punish_until'] = 'Sanction jusqu\'au';
$lang['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais ne peut plus poster sur le site entier (commentaires, etc...)';
$lang['weeks'] = 'semaines';
$lang['life'] = 'A vie';
$lang['readonly_user'] = 'Membre en lecture seule';
$lang['activ_register'] = 'Activer l\'inscription des membres';

//R�glement
$lang['explain_terms'] = 'Entrez ci-dessous le r�glement � afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun r�glement.';

//Gestion des groupes
$lang['groups_management'] = 'Gestion des groupes';
$lang['groups_add'] = 'Ajouter un groupe';
$lang['auth_flood'] = 'Autorisation de flooder';
$lang['pm_group_limit'] = 'Limite de messages priv�s';
$lang['pm_group_limit_explain'] = 'Mettre -1 pour illimit�';
$lang['data_group_limit'] = 'Limite de donn�es uploadables';
$lang['data_group_limit_explain'] = 'Mettre -1 pour illimit�';
$lang['color_group'] = 'Couleur';
$lang['color_group_explain'] = 'Couleur associ�e au groupe en hexad�cimal (ex: #FF6600)';
$lang['img_assoc_group'] = 'Image associ�e au groupe';
$lang['img_assoc_group_explain'] = 'Mettre dans le dossier images/group/';
$lang['add_mbr_group'] = 'Ajouter un membre au groupe';
$lang['mbrs_group'] = 'Membres du groupe';
$lang['auths'] = 'Autorisations';
$lang['auth_access'] = 'Autorisation d\'acc�s';
$lang['auth_read'] = 'Droits de lecture';
$lang['auth_write'] = 'Droits d\'�criture';
$lang['auth_edit'] = 'Droits de mod�ration';
$lang['upload_group'] = 'Uploader une image de groupe';

//Robots
$lang['robot'] = 'Robot';
$lang['robots'] = 'Robots';
$lang['erase_rapport'] = 'Effacer le rapport';
$lang['number_r_visit'] = 'Nombre de visite(s)';

//Divers
$lang['select_type_bbcode'] = 'BBCode';
$lang['select_type_html'] = 'HTML';

//Statistiques
$lang['stats'] = 'Statistiques';
$lang['more_stats'] = 'Plus de stats';
$lang['site'] = 'Site';
$lang['browser_s'] = 'Navigateurs';
$lang['fai'] = 'Fournisseurs d\'acc�s Internet';
$lang['all_fai'] = 'Voir la liste compl�te des fournisseurs d\'acc�s Internet';
$lang['10_fai'] = 'Voir les 10 principaux fournisseurs d\'acc�s Internet';
$lang['os'] = 'Syst�mes d\'exploitation';
$lang['number'] = 'Nombre ';
$lang['start'] = 'Cr�ation du site';
$lang['stat_lang'] = 'Pays des visiteurs';
$lang['all_langs'] = 'Voir la liste compl�te des pays des visiteurs';
$lang['10_langs'] = 'Voir les 10 principaux pays des visiteurs';
$lang['visits_year'] = 'Voir les statistiques de l\'ann�e';
$lang['unknown'] = 'Inconnu';
$lang['last_member'] = 'Dernier membre';
$lang['top_10_posters'] = 'Top 10 : posteurs';
$lang['version'] = 'Version';
$lang['colors'] = 'Couleurs';
$lang['calendar'] = 'Calendrier';
$lang['events'] = 'Ev�nements';
$lang['january'] = 'Janvier';
$lang['february'] = 'F�vrier';
$lang['march'] = 'Mars';
$lang['april'] = 'Avril';
$lang['may'] = 'Mai';
$lang['june'] = 'Juin';
$lang['july'] = 'Juillet';
$lang['august'] = 'Ao�t';
$lang['september'] = 'Septembre';
$lang['october'] = 'Octobre';
$lang['november'] = 'Novembre';
$lang['december'] = 'D�cembre';
$lang['monday'] = 'Lun';
$lang['tuesday'] = 'Mar';
$lang['wenesday'] = 'Mer';
$lang['thursday'] = 'Jeu';
$lang['friday'] = 'Ven';
$lang['saturday'] = 'Sam';
$lang['sunday']	= 'Dim';

// Updates
$lang['website_updates'] = 'Mises � jour';
$lang['kernel'] = 'Noyau';
$lang['themes'] = 'Th�mes';
$lang['update_available'] = 'Le %1$s %2$s est disponible dans sa version %3$s';
$lang['kernel_update_available'] = 'PHPBoost est disponible dans sa nouvelle version %s';
$lang['app_update__download'] = 'T�l�chargement';
$lang['app_update__download_pack'] = 'Pack complet';
$lang['app_update__update_pack'] = 'Pack de mise � jour';
$lang['author'] = 'Auteur';
$lang['authors'] = 'Auteurs';
$lang['new_features'] = 'Nouvelles Fonctionnalit�s';
$lang['improvments'] = 'Am�liorations';
$lang['fixed_bugs'] = 'Corrections de bugs';
$lang['security_improvments'] = 'Am�liorations de s�curit�';
$lang['unexisting_update'] = 'La mise � jour recherch�e n\'existe pas';
$lang['updates_are_available'] = 'Des mises � jours sont disponibles.<br />Veuillez les effectuer au plus vite.';
$lang['availables_updates'] = 'Mises � jour disponibles';
$lang['details'] = 'D�tails';
$lang['more_details'] = 'Plus de d�tails';
$lang['download_the_complete_pack'] = 'T�l�chargez le pack complet';
$lang['download_the_update_pack'] = 'T�l�chargez le pack de mise � jour';
$lang['no_available_update'] = 'Aucune mise � jour n\'est disponible pour l\'instant.';
$lang['incompatible_php_version'] = 'Impossible de v�rifier la pr�sence de mise � jour.
Veuillez utiliser la version %s ou ult�rieure de PHP.<br />Si vous ne pouvez utiliser PHP5,
veuillez v�rifier la pr�sence de ces mises � jour sur notre <a href="http://www.phpboost.com">site officiel</a>.';
$lang['check_for_updates_now'] = 'V�rifier la pr�sence de mises � jour';

// DEPRECATED
global $LANG;
$LANG = array_merge($LANG, $lang);
?>
