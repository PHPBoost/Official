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
);

?>