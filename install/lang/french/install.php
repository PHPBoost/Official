<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
#                      French                      #
 ####################################################


$lang = array(
    'install.rank.admin' => 'Administrateur',
    'install.rank.modo' => 'Mod�rateur',
    'install.rank.inactiv' => 'Boosteur Inactif',
    'install.rank.fronde' => 'Booster Fronde',
    'install.rank.minigun' => 'Booster Minigun',
    'install.rank.fuzil' => 'Booster Fuzil',
    'install.rank.bazooka' => 'Booster Bazooka',
    'install.rank.roquette' => 'Booster Roquette',
    'install.rank.mortier' => 'Booster Mortier',
    'install.rank.missile' => 'Booster Missile',
    'install.rank.fusee' => 'Booster Fus�e',

	'chmod.cache.notWritable' => '<h1>Installation de PHPBoost</h1>
<p><strong>Attention</strong> : les dossiers /cache et /cache/tpl n\'existent pas ou ne sont pas inscriptibles. Veuillez les cr�er et/ou changer leur CHMOD (mettre 777) pour pouvoir lancer l\'installation.</p>
<p>Une fois ceci fait, actualisez la page pour continuer ou cliquez <a href="">ici</a>.</p>',

	//Variables g�n�rales
	'installation.title' => 'Installation de PHPBoost',
	'steps.list' => 'Liste des �tapes',
	'step.list.introduction' => 'Pr�ambule',
	'step.list.license' => 'Licence',
	'step.list.server' => 'Configuration du serveur',
	'step.list.database' => 'Configuration base de donn�es',
	'step.list.website' => 'Configuration du site',
	'step.list.admin' => 'Compte administrateur',
	'step.list.end' => 'Fin de l\'installation',
	'installation.progression' => 'Progression de l\'installation',
	'appendices' => 'Annexes',
	'documentation' => 'Documentation',
	'documentation.link' => 'http://www.phpboost.com/wiki/installer-phpboost',
	'installation.restart' => 'Recommencer l\'installation',
	'installation.confirmRestart' => 'Etes-vous certain de vouloir recommencer l\'installation ?',
	'language.change' => 'Changer de langue',
	'change' => 'Changer',
    'step.previous' => 'Etape pr�c�dente',
    'step.next' => 'Etape suivante',
    'yes' => 'Oui',
    'no' => 'Non',
	'unknown' => 'Inconnu',
	'generatedBy' => 'G�n�r� par %s',
	'poweredBy' => 'Boost� par',
	'phpboost.rights' => '',

//Introduction
	'step.welcome.title' => 'Pr�ambule',
	'step.welcome.message' => 'Bienvenue dans l\'assistant d\'installation de PHPBoost',
    'step.welcome.explanation' => '<p>Merci d\'avoir accord� votre confiance � PHPBoost pour cr�er votre site web.</p>
<p>Pour installer PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre h�bergement qui devraient �tre fournies par votre h�bergeur. L\'installation est enti�rement automatis�e, elle ne devrait prendre que quelques minutes. Cliquez sur la fl�che ci-dessous pour d�marrer le processus d\'installation.</p>
<p>Cordialement, l\'�quipe PHPBoost</p>',
	'step.welcome.distribution' => 'Distribution :distribution',
	'step.welcome.distribution.explanation' => '<p>Il existe diff�rentes distributions de PHPBoost permettant � l\'utilisateur d\'obtenir automatiquement une configuration appropri�e � ses besoins. Une distribution contient des modules ainsi que quelques param�trages du syst�me (noyau).</p>
<p>PHPBoost va s\'installer selon la configuration de cette distribution, vous pourrez �videmment par la suite modifier sa configuration et ajouter ou supprimer des modules.</p>',
	'start_install' => 'Commencer l\'installation',

//licence
	'step.license.title' => 'Licence',
	'step.license.agreement' => 'Acceptation des termes de la licence',
	'step.license.require.agreement' => '<p>Vous devez accepter les termes de la licence GNU/GPL pour installer PHPBoost.</p><p>Vous trouverez une traduction non officielle de cette licence en fran�ais <img src="../images/stats/countries/fr.png" alt="Fran�ais" /> <a href="http://www.linux-france.org/article/these/gpl.html">ici</a>.</p>',
	'step.license.terms.title' => 'Termes de la licence',
	'step.license.please_agree' => 'J\'ai pris connaissance des termes de la licence et je les accepte',
	'step.license.submit.alert' => 'Vous devez accepter la licence en cochant le formulaire associ� pour pouvoir continuer !',

//Configuration du serveur
	'step.server.title' => 'V�rification de la configuration du serveur',
	'step.server.explanation' => '<p>Avant de commencer les �tapes d\'installation de PHPBoost, la configuration de votre serveur va �tre v�rifi�e afin d\'�tablir sa compatibilit� avec PHPBoost.</p>
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
	'db.parameters.config.explanation' => '<p>Cette �tape permet de g�n�rer le fichier de configuration qui retiendra les identifiants de connexion � votre base de donn�es. Les tables permettant de faire fonctionner PHPBoost seront automatiquement cr��es lors de cette �tape. Si vous ne connaissez pas les informations ci-dessous, contactez votre h�bergeur qui vous les transmettra.</p>',
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
	'schema.explanation' => 'Fourni par l\'h�bergeur. Si cette base n\'existe pas, PHPBoost essaiera de la cr�er si la configuration le lui permet.',
	'schema.tablePrefix' => 'Pr�fixe des tables',
	'schema.tablePrefix.explanation' => 'Par d�faut <em>phpboost_</em>. A changer si vous souhaitez installer plusieurs fois PHPBoost dans la m�me base de donn�es.',
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
//	'site_config_mail_signature' => 'Cordialement, l\'�quipe du site.',

//administration
    'step.admin.title' => 'Compte administrateur',
	'adminCreation' => 'Cr�ation du compte administrateur',
	'adminCreation.explanation' => '<p>Ce compte donne acc�s au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte par la suite en consultant votre profil.</p>
<p>Par la suite, il sera possible de donner � plusieurs personnes le statut d\'administrateur, ce compte est celui du premier administrateur, sans lequel vous ne pourriez pas g�rer le site.</p>',
	'admin.account' => 'Compte administrateur',
	'admin.login' => 'Pseudo',
	'admin.login.explanation' => 'Minimum 3 caract�res',
	'admin.password' => 'Mot de passe',
	'admin.password.explanation' => 'Minimum 6 caract�res',
	'admin.password.repeat' => 'R�p�ter le mot de passe',
	'admin.email' => 'Courrier �lectronique',
	'admin.email.explanation' => 'Doit �tre valide pour recevoir le code de d�verrouillage',
	'admin.login.required' => 'Vous devez choisir un pseudo',
	'admin.login.length' => 'Votre pseudo est trop court (3 caract�res minimum)',
	'admin.password.required' => 'Vous devez entrer un mot de passe',
	'admin.password.length' => 'Votre mot de passe est trop court (6 caract�res minimum)',
	'admin.confirmPassword.required' => 'Vous devez confirmer votre mot de passe',
	'admin.passwords.mismatch' => 'Les deux mots de passe que vous avez entr�s ne correspondent pas',
	'admin.email.required' => 'Vous devez entrer une adresse de courier �lectronique',
	'admin.email.invalid' => 'L\'adresse de courier �lectronique que vous avez entr�e n\'a pas une forme correcte',
//	'admin_invalid_email_error' => 'Mail invalide',
	'admin.connectAfterInstall' => 'Me connecter � la fin de l\'installation',
	'admin.autoconnect' => 'Rester connect� syst�matiquement � chacune de mes visites',
//	'admin_error' => 'Erreur',
	'admin.created.email.object' => 'Identifiants de votre site cr�� avec PHPBoost (message � conserver)',
	'admin.created.email.unlockCode' => 'Cher %s,

Tout d\'abord, merci d\'avoir choisi PHPBoost pour r�aliser votre site, nous esp�rons qu\'il r�pondra au mieux � vos besoins. Pour tout probl�me n\'h�sitez pas � vous rendre sur le forum http://www.phpboost.com/forum/index.php

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus �tre r�cup�r�s).

Identifiant: %s
Password: %s

A conserver ce code (Il ne vous sera plus d�livr�) : %s

Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�, il vous sera demand� dans le formulaire de connexion directe � l\'administration (%s/admin/admin_index.php)

Cordialement l\'�quipe PHPBoost.',

//Fin de l'installation
    'step.finish.title' => 'Fin de l\'installation',
	'finish.message' => '<fieldset>
                            <legend>PHPBoost est d�sormais install� !</legend>
                            <p class="success">L\'installation de PHPBoost s\'est d�roul�e avec succ�s. L\'�quipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
                            <p>Nous vous conseillons de vous tenir au courant de l\'�volution de PHPBoost via le site de la communaut� francophone, <a href="http://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arriv�e de nouvelles mises � jour. Il est fortement conseill� de tenir votre syst�me � jour afin de profiter des derni�res nouveaut�s et de corriger les �ventuelles failles ou erreurs.</p>
                            <p class="warning">Par mesure de s�curit� nous vous conseillons fortement de supprimer le dossier install et le dossier update et tout ce qu\'il contient. Des personnes mal intentionn�es pourraient relancer le script d\'installation ou le script de mise � jour et �craser certaines de vos donn�es !</p>
                            <p>N\'oubliez pas de consulter la <a href="http://www.phpboost.com/wiki/wiki.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="http://www.phpboost.com/faq/faq.php"><acronym title="Foire Aux Questions">FAQ</acronym></a> qui r�pond aux questions les plus fr�quentes.</p>
                            <p>En cas de probl�me, rendez-vous sur le <a href="http://www.phpboost.com/forum/index.php">forum du support de PHPBoost</a>.</p>
                        </fieldset>
                        <fieldset>
                            <legend>Remerciements</legend>
                            <h2>Membres de la communaut�</h2>
                            <p>Merci � tous les membres de la communaut� qui nous encouragent au quotidien et contribuent � la qualit� du logiciel que ce soit en sugg�rant des nouvelles fonctionnalit�s ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres � une version 4.0 stable et efficace.</p>
                            <p>Merci aux membres des �quipes de PHPBoost et particuli�rement � <strong>soupaloignon</strong> de l\'�quipe communication, <strong>Ptithom</strong>, <strong>aiglobulles</strong>, <strong>55 Escape</strong> et <strong>Micman</strong> pour la documentation, <strong>Schyzo</strong>, <strong>elenwe</strong> et <strong>alyha</strong> pour les graphismes, <strong>DaaX</strong>, <strong>Alain91</strong> et <strong>julienseth78</strong> de l\'�quipe de d�veloppement de modules et <strong>benflovideo</strong> pour la mod�ration de la communaut�.</p>
                            <h2>Projets</h2>
                            <p>PHPBoost utilise diff�rents outils afin d\'�largir ses fonctionnalit�s sans augmenter trop le temps de d�veloppement. Ces outils sont tous libres, distribu�s sous la licence GNU/GPL pour la plupart.</p>
                            <ul>
                                <li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Editeur de texte puissant tr�s utilis� pour le d�veloppement de PHPBoost.</li>
                                <li><a href="http://www.eclipse.org/pdt/">Eclipse <acronym title="PHP Development Tools">PDT</acronym></a> : <acronym title="Integrated Development Environment">IDE</acronym> PHP (outil de d�veloppement PHP) bas� sur Eclipse.</li>
                                <li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Ensemble d\'ic�nes diverses utilis�es sur l\'ensemble de PHPBoost.</li>
                                <li><a href="http://www.phpconcept.net/pclzip/">PCLZIP</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
                                <li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules math�matiques � partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
                                <li><a href="http://tinymce.moxiecode.com/">TinyMCE</a> : Editeur <acronym title="What You See Is What You Get">WYSIWYG</acronym> permettant la mise en page � la vol�e.</li>
                                <li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
                                <li><a href="http://script.aculo.us/">script.aculo.us</a> : Framework Javascript et <acronym title="Asynchronous Javascript And XML">AJAX</acronym></li>
                                <li><a href="http://www.alsacreations.fr/mp3-dewplayer.html">Dewplayer</a> : lecteur audio au format flash</li>
                                <li><a href="http://flowplayer.org">Flowplayer</a> : lecteur vid�o au format flash</li>
                            </ul>
                        </fieldset>
                        <fieldset>
                            <legend>Cr�dits</legend>
                            <ul>
                                <li><strong>R�gis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et d�veloppeur retrait�</li>
                                <li><strong>Beno�t SAUTEL</strong> <em>(alias ben.popeye)</em>, d�veloppeur retrait�</li>
                                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, d�veloppeur retrait�</li>
                                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, d�veloppeur</li>
                            </ul>
                        </fieldset>',
	'site.index' => 'Aller � l\'accueil du site',
	'admin.index' => 'Aller dans le panneau d\'administration'
);
?>
