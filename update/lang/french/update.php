<?php
/*##################################################
 *                                update.php
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
	'unknown' => 'Inconnu',
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
	'php.extensions.check.gdLibrary.explanation' => 'Librairie utilis�e pour g�n�rer des images. Utile par exemple pour la protection anti robots ou les diagrammes des statistiques du site. Certains modules peuvent �galement s\'en servir.',
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
	'db.creation.error' => 'La base de donn�es que vous avez indiqu�e n\'existe pas.',
	'db.unknown.error' => 'Une erreur inconnue a �t� rencontr�e.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'h�te !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de donn�es !',
	'db.unexisting_database' => 'La base de donn�e n\'existe pas. Veuillez v�rifier vos param�tres.',
	'phpboost.notInstalled' => 'Installation inexistante',
	'phpboost.notInstalled.explanation' => '<p>La base de donn�es sur laquelle vous souhaitez mettre � jour PHPBoost ne contient pas d\'installation.</p>
	<p> Veuillez v�rifier que vous avez bien saisi le bon pr�fixe et la bonne base de donn�es.</p>',

//Execute update
	'step.execute.title' => 'Ex�cuter la mise � jour',
	'step.execute.message' => 'Mise � jour du site',
	'step.execute.explanation' => 'Cette �tape va convertir votre site PHPBoost 3.0 vers PHPBoost 4.0.
	<br /><br />
	Attention cette �tape est irr�versible, veuillez par pr�caution sauvegarder votre base de donn�es au pr�alable !',
	
	'finish.message' => '<fieldset>
                            <legend>PHPBoost est d�sormais mis � jour!</legend>
                            <p class="success">La mise � jour de PHPBoost s\'est d�roul�e avec succ�s. L\'�quipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
                            <p>Nous vous conseillons de vous tenir au courant de l\'�volution de PHPBoost via le site de la communaut� francophone, <a href="http://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arriv�e de nouvelles mises � jour. Il est fortement conseill� de tenir votre syst�me � jour afin de profiter des derni�res nouveaut�s et de corriger les �ventuelles failles ou erreurs.</p>
                            <p class="warning">Par mesure de s�curit� nous vous conseillons fortement de supprimer le dossier install et tout ce qu\'il contient, des personnes mal intentionn�es pourraient relancer le script d\'installation et �craser certaines de vos donn�es !</p>
                            <p>N\'oubliez pas la <a href="http://www.phpboost.com/wiki/wiki.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="http://www.phpboost.com/faq/faq.php"><acronym title="Foire Aux Questions">FAQ</acronym></a> qui r�pond aux questions les plus fr�quentes.</p>
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
