<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 K�vin MASSY
 *   email                : soldier.weasel@gmail.com
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
#                      French                      #
 ####################################################


$lang = array(
	//Variables g�n�rales
	'update.title' => 'Mise � jour de PHPBoost',
	'steps.list' => 'Liste des �tapes',
	'step.list.introduction' => 'Pr�ambule',
	'step.list.license' => 'Licence',
	'step.list.server' => 'Configuration du serveur',
	'step.list.database' => 'Configuration base de donn�es',
	'step.list.website' => 'Configuration du site',
	'step.list.execute' => 'Mise � jour',
	'step.list.end' => 'Fin de la mise � jour',
	'installation.progression' => 'Progression de la mise � jour',
	'language.change' => 'Changer de langue',
	'change' => 'Changer',
    'step.previous' => 'Etape pr�c�dente',
    'step.next' => 'Etape suivante',
    'yes' => 'Oui',
    'no' => 'Non',
	'generatedBy' => 'G�n�r� par %s',
	'poweredBy' => 'Boost� par',
	'phpboost.rights' => '',

//Introduction
	'step.introduction.title' => 'Pr�ambule',
	'step.introduction.message' => 'Bienvenue dans l\'assistant de mise � jour de PHPBoost',
    'step.introduction.explanation' => '<p>Merci d\'avoir accord� votre confiance � PHPBoost pour cr�er votre site web.</p>
<p>Pour mettre � jour PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre h�bergement qui devraient �tre fournies par votre h�bergeur. La mise � jour est enti�rement automatis�e, elle ne devrait prendre que quelques minutes. Cliquez sur la fl�che ci-dessous pour d�marrer le processus de mise � jour.</p>
<p>Cordialement, l\'�quipe PHPBoost</p>',

//Configuration du serveur
	'step.server.title' => 'V�rification de la configuration du serveur',
	'step.server.explanation' => '<p>Avant de commencer la mise � jour de PHPBoost, la configuration de votre serveur va �tre v�rifi�e afin d\'�tablir sa compatibilit� avec PHPBoost.</p>
<div class="notice">Veillez � ce que chaque condition obligatoire soit v�rifi�e sans quoi vous risquez d\'avoir des probl�mes en utilisant le logiciel.</div>
<p>En cas de probl�me n\'h�sitez pas � poser vos questions sur le <a href="http://www.phpboost.com/forum/index.php">forum de support</a>.</p>',
	'php.version' => 'Version de PHP',
	'php.version.check' => 'PHP sup�rieur � :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit �tre �quip� d\'une version sup�rieure ou �gale � PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre h�bergeur ou migrez vers un serveur plus r�cent.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => 'Optionnel : L\'activation de ces extensions permet d\'apporter des fonctionnalit�s suppl�mentaires mais n\'est en aucun cas indispensable.',
	'php.extensions.check.gdLibrary' => 'Librairie GD',
	'php.extensions.check.gdLibrary.explanation' => 'Librairie utilis�e pour g�n�rer des images. Utile par exemple pour la protection anti robots, ou les diagrammes des statistiques du site. Certains modules peuvent �galement s\'en servir.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'R��criture des adresses des pages qui les rend plus lisibles et plus propices au r�f�rencement sur les moteurs de recherche',
	'folders.chmod' => 'Autorisations des dossiers',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost n�cessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont chang�es de fa�on automatique. Cependant certains serveurs emp�chent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre h�bergeur.',
	'folders.chmod.refresh' => 'Rev�rifier les dossiers',
	'folder.exists' => 'Existant',
	'folder.doesNotExist' => 'Inexistant',
	'folder.isWritable' => 'Inscriptible',
	'folder.isNotWritable' => 'Non inscriptible',
	'folders.chmod.error' => 'Les r�pertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire � la main pour pouvoir continuer.',

//Base de donn�es
    'step.dbConfig.title' => 'Configuration base de donn�es',
	'db.parameters.config' => 'Param�tres de connexion � la base de donn�es',
	'db.parameters.config.explanation' => '<p>Cette �tape permet de g�n�rer le fichier de configuration qui retiendra les identifiants de connexion � votre base de donn�es. Si vous ne connaissez pas les informations ci-dessous, contactez votre h�bergeur qui vous les transmettra.</p>',
	'dbms.paramters' => 'Param�tres d\'acc�s au <acronym title="Syst�me de Gestion de Base de Donn�es">SGBD</acronym>',
	'dbms.host' => 'Nom de l\'h�te',
	'dbms.host.explanation' => 'URL du serveur qui g�re la base de donn�es, <em>localhost</em> la plupart du temps.',
    'dbms.port' => 'Port du serveur',
    'dbms.port.explanation' => 'Port du serveur qui g�re la base de donn�es, <em>3306</em> la plupart du temps.',
	'dbms.login' => 'Identifiant',
	'dbms.login.explanation' => 'Fourni par l\'h�bergeur',
	'dbms.password' => 'Mot de passe',
	'dbms.password.explanation' => 'Fourni par l\'h�bergeur',
	'schema.properties' => 'Propri�t�s de la base de donn�es',
	'schema' => 'Nom de la base de donn�es',
	'schema.tablePrefix' => 'Pr�fixe des tables',
	'schema.tablePrefix.explanation' => 'Par d�faut <em>phpboost_</em>. A changer si vous avez install� plusieurs fois PHPBoost dans la m�me base de donn�es.',
	'db.config.check' => 'Essayer',
	'db.connection.success' => 'La connexion � la base de donn�es a �t� effectu�e avec succ�s. Vous pouvez poursuivre l\'installation',
	'db.connection.error' => 'Impossible de se connecter � la base de donn�es. Merci de v�rifier vos param�tres.',
	'db.creation.error' => 'La base de donn�es que vous avez indiqu�e n\'existe pas et le syst�me n\'a pas l\'autorisation de la cr�er.',
	'db.unknown.error' => 'Une erreur inconnue a �t� rencontr�e.',
	'phpboost.alreadyInstalled.alert' => 'Il existe d�j� une installation de PHPBoost sur cette base de donn�es avec ce pr�fixe. Si vous continuez, ces tables seront supprim�es et vous perdrez certainement des donn�es.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'h�te !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de donn�es !',
	'phpboost.alreadyInstalled' => 'Installation existante',
	'phpboost.alreadyInstalled.explanation' => '<p>La base de donn�es sur laquelle vous souhaitez installer PHPBoost contient d�j� une installation de PHPBoost.</p>
<p>Si vous effectuez l\'installation sur cette base de donn�es avec cette configuration, vous �craserez les donn�es pr�sentes actuellement. Si vous voulez installer deux fois PHPBoost sur la m�me base de donn�es, utilisez des pr�fixes diff�rents.</p>',
	'phpboost.alreadyInstalled.overwrite' => 'Je souhaite �craser l\'installation de PHPBoost d�j� existante',
	'phpboost.alreadyInstalled.overwrite.confirm' => 'Vous devez confirmer l\'�crasement de la pr�c�dente installation',

//Execute update
	'step.execute.title' => 'Ex�cuter la mise � jour',
	'step.execute.message' => 'Mise � jour du site',
	'step.execute.explanation' => 'Cette �tape va convertir votre site PHPBoost 3.0 vers PHPBoost 4.0.
	<br /><br />
	Attention cette �tape est irr�versible, veuillez par pr�caution sauvegarder votre base de donn�es au pr�alable !',

//configuraton du site
    'step.websiteConfig.title' => 'Configuration du serveur',
	'websiteConfig' => 'Configuration du site',
	'websiteConfig.explanation' => '<p>La configuration de base du site va �tre cr��e dans cette �tape afin de permettre � PHPBoost de fonctionner. Sachez cependant que toutes les donn�es que vous allez rentrer seront ult�rieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce m�me panneau renseigner davantage d\'informations facultatives � propos de votre site.</p>',
	'website.yours' => 'Votre site',
	'website.host' => 'Adresse du site :',
	'website.host.explanation' => 'De la forme http://www.phpboost.com',
	'website.path' => 'Chemin de PHPBoost :',
	'website.path.explanation' => 'Vide si votre site est � la racine du serveur, de la forme /dossier sinon',
	'website.name' => 'Nom du site',
    'website.description' => 'Description du site',
    'website.description.explanation' => '(facultatif) Utile pour le r�f�rencement dans les moteurs de recherche',
    'website.metaKeywords' => 'Mots cl�s du site',
    'website.metaKeywords.explanation' => '(facultatif) A rentrer s�par�s par des virgules, ils servent au r�f�rencement dans les moteurs de recherche',
	'website.timezone' => 'Fuseau horaire du site',
	'website.timezone.explanation' => 'La valeur par d�faut est celle correspondant � la localisation de votre serveur. Pour la France, il s\'agit de GMT + 1. Vous pourrez changer cette valeur par la suite dans le panneau d\'administration.',
	'website.host.required' => 'Vous devez entrer l\'adresse de votre site !',
	'website.name.required' => 'Vous devez entrer le nom de votre site !',
	'website.host.warning' => 'L\'adresse du site que vous avez rentr�e ne correspond pas � celle d�tect�e par le serveur. Souhaitez vous vraiment choisir cette adresse ?',
	'website.path.warning' => 'Le chemin du site sur le serveur que vous avez rentr�e ne correspond pas � celle d�tect�e par le serveur. Souhaitez vous vraiment choisir ce chemin ?',
);
?>
