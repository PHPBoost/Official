<?php
/*##################################################
 *                           admin-config-common.php
 *                            -------------------
 *   begin                : April 12, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

$lang = array(
	'mail_config' => 'Configuration de l\'envoi de mail',
	'general_mail_config' => 'Configuration g�n�rale',
	'default_mail_sender' => 'Adresse de l\'exp�diteur par d�faut',
	'default_mail_sender_explain' => 'Adresse qui sera utilis�e quand l\'adresse de l\'exp�diteur n\'est pas sp�cifi�e.',
	'administrators_mails' => 'Adresse des administrateurs',
	'administrators_mails_explain' => 'Liste des adresses mail (s�par�es par des virgules) � qui seront envoy�s les mails destin�s aux administrateurs.',
	'mail_signature' => 'Signature des mails',
	'mail_signature_explain' => 'La signature sera ajout�e � la fin de chaque mail envoy� par PHPBoost',
	'send_protocol' => 'Protocole d\'envoi',
	'send_protocol_explain' => 'G�n�ralement, les h�bergeurs configurent correctement le serveur pour qu\'il soit directement capable d\'envoyer des mails.
			Cependant, certains utilisateurs souhaitent modifier la fa�on dont le serveur exp�die les mails, dans ce cas-l� il faut utiliser une configuration SMTP sp�cifique qui se 
			qui s\'active en cochant la case ci-dessous. Une fois le serveur SMTP configur�, il sera utilis� par tous les envois de mail de PHPBoost.',
	'use_custom_smtp_configuration' => 'Utiliser une configuration SMTP sp�cifique',
	'custom_smtp_configuration' => 'Configuration SMTP personnalis�e',
	'smtp_host' => 'H�te',
	'smtp_port' => 'Port',
	'smtp_login' => 'Login',
	'smtp_password' => 'Mot de passe',
	'smtp_secure_protocol' => 'Protocole s�curis�',
	'smtp_secure_protocol_none' => 'Aucun',
	'smtp_secure_protocol_tls' => 'TLS',
	'smtp_secure_protocol_ssl' => 'SSL',
	'mail_config_saved' => 'La configuration a bien �t� enregistr�e',
	
	//General config
	'general-config.success' => 'La configuration g�n�rale du site a �t� enregistr�e avec succ�s',
	'general-config' => 'Configuration g�n�rale',
	'general-config.site_name' => 'Nom du site',
	'general-config.site_description' => 'Description du site',
	'general-config.site_description-explain' => '(facultatif) Utile pour le r�f�rencement dans les moteurs de recherche',
	'general-config.site_keywords' => 'Mots cl�s du site',
	'general-config.site_keywords-explain' => '(facultatif) A rentrer s�par�s par des virgules, ils servent au r�f�rencement dans les moteurs de recherche',
	
	'general-config.visit_counter' => 'Compteur',
	'general-config.page_bench' => 'Benchmark',
	'general-config.page_bench-explain' => 'Affiche le temps de rendu de la page et le nombre de requ�tes SQL',
	'general-config.display_theme_author' => 'Info sur le th�me',
	'general-config.display_theme_author-explain' => 'Affiche des informations sur le th�me dans le pied de page',
	
	//Advanced config
	'advanced-config.success' => 'La configuration avanc�e du site a �t� enregistr�e avec succ�s',
	'advanced-config' => 'Configuration avanc�e',
	'advanced-config.site_url' => 'URL du serveur',
	'advanced-config.site_url-explain' => 'Ex : http://www.phpboost.com',
	'advanced-config.site_path' => 'Chemin de PHPBoost',
	'advanced-config.site_path-explain' => 'Vide par d�faut : site � la racine du serveur',
	'advanced-config.site_timezone' => 'Choix du fuseau horaire',
	'advanced-config.site_timezone-explain' => 'Permet d\'ajuster l\'heure � votre localisation',
	
	'url-rewriting' => 'Activation de la r��criture des urls',
	'url-rewriting.explain' => 'L\'activation de la r��criture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compr�hensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre r�f�rencement sera grandement optimis� gr�ce � cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les h�bergeurs. Cette page va vous permettre de tester si votre serveur supporte la r��criture des urls. Si apr�s le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> � la racine de votre site via acc�s FTP � votre serveur, puis revenez sur cette page et d�sactivez la r��criture.',
	
	'config.not-available' => '<span style="color:#B22222;font-weight:bold;">Non disponible sur votre serveur</span>',
	'config.available' => '<span style="color:#008000;font-weight:bold;">Disponible sur votre serveur</span>',

	'htaccess-manual-content' => 'Contenu du fichier .htaccess',
	'htaccess-manual-content.explain' => 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez int�grer au fichier .htaccess qui se trouve � la racine du site, par exemple pour forcer une configuration du serveur web Apache.',
	
	'sessions-config' => 'Connexion utilisateurs',
	'sessions-config.cookie-name' => 'Nom du cookie des sessions',
	'sessions-config.cookie-name.style-wrong' => 'Le nom du cookie doit �tre obligatoirement une suite de lettre et de chiffre',
	'sessions-config.cookie-duration' => 'Dur�e de la session',
	'sessions-config.cookie-duration.explain' => '3600 secondes conseill�',
	'sessions-config.active-session-duration' => 'Dur�e utilisateurs actifs',
	'sessions-config.active-session-duration.explain' => '300 secondes conseill�',
	'sessions-config.integer-required' => 'La valeur doit �tre un nombre',
	
	'miscellaneous' => 'Divers',
	'miscellaneous.output-gziping-enabled' => 'Activation de la compression des pages, ceci acc�l�re la vitesse d\'affichage',
	'miscellaneous.unlock-administration' => 'Code de d�verrouillage',
	'miscellaneous.unlock-administration.explain' => 'Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.',
	'miscellaneous.unlock-administration.request' => 'Renvoyer le code de d�verrouillage',
	'miscellaneous.debug-mode' => 'Mode Debug',
	'miscellaneous.debug-mode.explain' => 'Ce mode est particuli�rement utile pour les d�veloppeurs car les erreurs sont affich�es explicitement. Il est d�conseill� d\'utiliser ce mode sur un site en production.',
	'miscellaneous.debug-mode.type' => 'S�l�ction du mode debug',
	'miscellaneous.debug-mode.type.normal' => 'Normal',
	'miscellaneous.debug-mode.type.strict' => 'Stricte',

	'unlock-code.title' => 'Mail � conserver',
	'unlock-code.content' => 'Code � conserver (Il ne vous sera plus d�livr�) : :unlock_code
	<br /><br />
	Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�.
	Il vous sera demand� dans le formulaire de connexion directe � l\'administration (:server_url/admin/admin_index.php)'

);
?>