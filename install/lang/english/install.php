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
#                      English                      #
 ####################################################


$lang = array(
   'install.rank.admin' => 'Administrator',
   'install.rank.modo' => 'Moderator',
   'install.rank.inactiv' => 'Inactive booster',
   'install.rank.fronde' => 'Slingshot booster',
   'install.rank.minigun' => 'Minigun booster',
   'install.rank.fuzil' => 'Fuzil booster',
   'install.rank.bazooka' => 'Bazooka booster',
   'install.rank.roquette' => 'Rocket booster',
   'install.rank.mortier' => 'Mortar booster',
   'install.rank.missile' => 'Missile booster',
   'install.rank.fusee' => 'Spaceship boost',

   'chmod.cache.notWritable' => '<h1>PHPBoost installation</h1>
<p><strong>Warning</strong> : the folders cache and cache/tpl must exist and be writable. Please create and/or set them the right CHMOD (777) to be able to continue the installation.</p>
<p>Once it is done, please refresh the page to continue or click <a href="">here</a>.</p>',

// General variables
	'installation.title' => 'PHPBoost installation',
	'steps.list' => 'Steps list',
	'step.list.introduction' => 'Preamble',
	'step.list.license' => 'License',
	'step.list.server' => 'Server Configuration',
	'step.list.database' => 'Database settings',
	'step.list.website' => 'General settings',
	'step.list.admin' => 'Administrator details',
	'step.list.end' => 'End of installation',
	'installation.progression' => 'Installation progression',
	'appendices' => 'Appendices',
	'documentation' => 'Documentation',
	'documentation.link' => 'http://www.phpboost.org/wiki/install-phpboost',
	'installation.restart' => 'Restart the installation',
	'installation.confirmRestart' => 'Are you sure you want to restart the installation ?',
	'language.change' => 'Change language',
	'change' => 'Change',
    'step.previous' => 'Previous step',
    'step.next' => 'Next step',
    'yes' => 'Yes',
    'no' => 'No',
	'unknown' => 'Unknown',
	'generatedBy' => 'Powered by %s',
	'poweredBy' => 'Powered by',
	'phpboost.rights' => '',


// Introduction
	'step.welcome.title' => 'Preamble',
	'step.welcome.message' => 'Welcome to PHPBoost installation wizard',
	'step.welcome.explanation' => '<p>Thank you for choosing PHPBoost to build your website.</p>
<p>Before starting installation, be sure to have to hand Database information provided by your hosting company. The installation is automatic and should take only a few minutes of your time.  To start the installation process, please click on the green arrow down below.</p>
<p>Best regards, the PHPBoost team</p>',
	'step.welcome.distribution' => ':distribution Distribution',
	'step.welcome.distribution.explanation' => '<p>There are several distributions which allow you to setup easily their website according to your needs. PHPBoost will install the kernel and modules according to the chosen distribution. After the installation is complete, you will be able to change settings and add/remove modules. </p>',
	'start_install' => 'Start installation',

// License
	'step.license.title' => 'License',
	'step.license.agreement' => 'End-user license agreement',
	'step.license.require.agreement' => 'You must accept the GNU/GPL license terms to install PHPBoost.',
	'step.license.terms.title' => 'License terms',
	'step.license.please_agree' => 'I have read and understand the End-user License Terms which applies to this software',
	'step.license.submit.alert' => 'You have to agree to the end-user license by checking the form !',

// Server setup
	'step.server.title' => 'Looking up server configuration...',
	'step.server.explanation' => '<p>Before starting installation, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="http://www.phpboost.org/forum/index.php">Support Forums</a>.</p>
<div class="notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>',
	'php.version' => 'PHP version',
	'php.version.check' => 'PHP higher than :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Required:</span> To run PHPBoost, your server must run PHP :min_php_version or higher. Below that, you might have issues with some modules.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => '<span style="font-weight:bold;">Optional :</span> The activation of these extensions will provide additional features but it is not essential to its operation.',
	'php.extensions.check.gdLibrary' => 'GD library',
	'php.extensions.check.gdLibrary.explanation' => 'Library used to generate pictures such as Robot Protection, Statistics Graphics and much more.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Not only does it rewrite URLs, but it helps a lot with search engine robots.',
	'folders.chmod' => 'Directories permissions',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Required:</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help in our <a href="http://www.phpboost.net/wiki/change-the-chmod-of-a-directory" title="PHPBoost documentation : chmod">PHPBoost documentation</a> or on your host website.',
	'folders.chmod.refresh' => 'Double-check directories permissions',
	'folder.exists' => 'Found',
	'folder.doesNotExist' => 'Not found',
	'folder.isWritable' => 'Writable',
	'folder.isNotWritable' => 'Not writable',
	'folders.chmod.error' => 'Some directories seem to be missing and/or not writable. You need to create the missing directories or make the directories writable.',

// Database
    'step.dbConfig.title' => 'Database settings',
	'db.parameters.config' => 'Database connection parameters',
	'db.parameters.config.explanation' => '<p>This step will generate a configuration file that contains the database settings and database tables will also be created at the same time. If you don\'t know your database settings, you might be able to find them in your host panel or need to ask for them from your host support.</p>',
	'dbms.paramters' => 'DBMS connection parameters',
	'dbms.host' => 'Database server hostname or DSN:',
	'dbms.host.explanation' => 'Database managing system server URL, often <em>localhost</em>',
    'dbms.port' => 'Database port',
    'dbms.port.explanation' => 'Database server port, <em>3306</em> most of the time.',
	'dbms.login' => 'Database username',
	'dbms.login.explanation' => 'Provided by your host',
	'dbms.password' => 'Database password',
	'dbms.password.explanation' => 'Provided by your host',
	'schema.properties' => 'Database properties',
	'schema' => 'Database name',
	'schema.explanation' => 'Provided by your host. If that database doesn\'t exist, PHPBoost will try to create it.',
	'schema.tablePrefix' => 'Prefix for tables in database',
	'schema.tablePrefix.explanation' => 'Default value is <em>phpboost_</em>. You will need to change this value if you want to install PHPBoost several times in the same database.',
	'db.config.check' => 'Try to establish database connection!',
	'db.connection.success' => 'The connection to the database has been established successfully. You can proceed to the next step.',
	'db.connection.error' => 'Could not connect to the database. Please check the settings you have entered.',
	'db.creation.error' => 'The database name you entered doesn\'t exist and we can\'t create it for you. You need to manually create the database.',
	'db.unknown.error' => 'An unknown error has occured.',
	'phpboost.alreadyInstalled.alert' => 'A PHPBoost installation has been found on this database with the prefix you entered. <span style="font-weight:bold;">If you proceed, all the data in this database will be lost.</span>',
	'db.required.host' => 'You must enter database hostname!',
	'db.required.port' => 'You must enter database port!',
	'db.required.login' => 'You must enter database username!',
	'db.required.schema' => 'You must enter database name!',
	'phpboost.alreadyInstalled' => 'Existing installation',
	'phpboost.alreadyInstalled.explanation' => '<p>The database name you entered contains a PHPBoost installation with the same table prefix.</p>
<p>If you proceed with the installation, all the data in your database will be lost. If you want to install another PHPBoost, you absolutely need to use another table prefix.</p>',
	'phpboost.alreadyInstalled.overwrite' => 'Yes, I want to overwrite the existing data and proceed with the installation.',
	'phpboost.alreadyInstalled.overwrite.confirm' => 'Please, confirm the previous installation override',

// Website settings
    'step.websiteConfig.title' => 'Server settings',
	'websiteConfig' => 'Website settings',
	'websiteConfig.explanation' => '<p>At this stage, some basic settings will be created that will allow PHPBoost to work. Anything you enter in this form may be modified after installation in the administration panel. Also after the installation, you will have to set advanced configurations in the admin panel.</p>',
	'website.yours' => 'Your website',
	'website.host' => 'Website url :',
	'website.host.explanation' => 'e.g. http://www.phpboost.com',
	'website.path' => 'PHPBoost path :',
	'website.path.explanation' => 'The path where PHPBoost is located relative to the domain name, e.g. /PHPBoost.',
	'website.name' => 'Website name',
	'website.timezone' => 'System timezone',
	'website.timezone.explanation' => 'The default value is the server one. You will be able to change this value later in the administration panel.',
	'website.description' => 'Website description',
	'website.description.explanation' => '<span style="font-weight:bold;">Optional:</span> Useful for search engine optimization',
	'website.metaKeywords' => 'Website keywords',
	'website.metaKeywords.explanation' => '<span style="font-weight:bold;">Optional:</span> Enter keywords separated by commas.',
	'website.host.required' => 'You must enter your website\'s url !',
	'website.name.required' => 'You must enter your website\'s name !',
	'website.host.warning' => 'The website address you entered doesn\'t match your server address. Are you sure you want to keep to proceed ?',
	'website.path.warning' => 'The website path you entered doesn\'t correspond to path powered by the server, are you sure you want to keep the path you entered ?',
//	'site_config_mail_signature' => 'Best regards, the site team.',

// Administration
    'step.admin.title' => 'Admin Account',
	'adminCreation' => 'Administrator account creation',
	'adminCreation.explanation' => '<p>This account gives you access to administration panel in which you can setup your website.</p>
<p>You will be able to grant administrator rights to other members later. Here you just create the first administrator account, without which you can\'t manage your website.</p>',
	'admin.account' => 'Administrator account',
	'admin.login' => 'Administrator username',
	'admin.login.explanation' => 'Enter a username between 3 and 25 characters in length',
	'admin.password' => 'Administrator password',
	'admin.password.explanation' => 'Enter a password between 6 and 30 characters in length',
	'admin.password.repeat' => 'Confirm administrator password',
	'admin.email' => 'Contact e-mail address',
	'admin.email.explanation' => 'Must be valid to receive unlock administration code.',
	'admin.login.required' => 'You must enter a username !',
	'admin.login.length' => 'Your username is too short (at least 3 characters)',
	'admin.password.required' => ' You must enter a password !',
	'admin.password.length' => 'Your password is too short (at least 6 characters)',
	'admin.confirmPassword.required' => 'You must confirm your password !',
	'admin.passwords.mismatch' => 'The passwords you entered did not match.',
	'admin.email.required' => 'You must enter an e-mail address !',
	'admin.email.invalid' => 'The email address you entered is invalid.',
//	'admin_invalid_email_error' => 'The email address you entered is invalid.',
	'admin.connectAfterInstall' => 'Log me on at the end of the installation',
	'admin.autoconnect' => 'Log me on automatically each visit',
//	'admin_error' => 'Error',
	'admin.created.email.object' => 'PHPBoost : message to be preserved',
	'admin.created.email.unlockCode' => 'Dear %s,

First of all, thank you for powering your website with PHPBoost software, we hope you will be satisfied. If you have any problems ask your questions on the PHPBoost support forum : http://www.phpboost.org/forum/index.php

Here is your login and password (don\'t lose them, you will need them to setup your website) :

Login: %s
Password: %s

Please note the following code (It won\'t be delivered anymore unless you ask to in the administration panel) : %s

If your website undergoes a hacking attempt, you will have to use this security code to unlock the administration panel. You will be asked to enter the security code when you log in to the administration panel.  (%s/admin/admin_index.php)

Best regards,
The PHPBoost Team.',

// End of installation
    'step.finish.title' => 'End of installation',
	'finish.message' => '<fieldset>
                            <legend>PHPBoost is now installed and ready to run !</legend>
                            <p class="success">The installation of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
                            <p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="http://www.phpboost.org">www.phpboost.org</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
                            <p class="warning">For security reasons we also recommand you to delete the installation and update folders and all their contents. Hackers could manage to run the installation or the update script and you could lose data !</p>
                            <p>Don\'t forget to consult the <a href="http://www.phpboost.org/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="http://www.phpboost.org/faq/faq.php"><acronym title="Frequently Asked Questions">FAQ</acronym></a>.</p>
                            <p>If you have any problem please go to the <a href="http://www.phpboost.org/forum/">support forum of PHPBoost</a>.</p>
                        </fieldset>
                        <fieldset>
                            <legend>Thanks</legend>
                            <h2>Members</h2>
                            <p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful version 4.0.</p>
                            <p>Thanks to the members of our teams and particulary to <strong>soupaloignon</strong> for communication team, <strong>Ptithom</strong>, <strong>aiglobulles</strong>, <strong>55 Escape</strong> and <strong>Micman</strong> for the documentation writing, <strong>Schyzo</strong>, <strong>elenwe</strong> and <strong>alyha</strong> for the graphics, <strong>DaaX</strong>, <strong>Alain91</strong> and <strong>julienseth78</strong> for the modules development and <strong>benflovideo</strong> for the moderation of the community.</p>
                            <h2>Other projects</h2>
                            <p>PHPBoost uses different tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools are under GNU/GPL license.</p>
                            <ul>
                                <li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Very powerful text editor used for the whole development, thanks a lot !</li>
                                <li><a href="http://www.eclipse.org/pdt/">Eclipse <acronym title="PHP Development Tools">PDT</acronym></a> : Eclipse based PHP <acronym title="Integrated Development Environment">IDE</acronym> (development tool).</li>
                                <li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Icon set used in the whole interface.</li>
                                <li><a href="http://www.phpconcept.net/pclzip/">PCLZIP by PHPConcept</a> : PHP library which manage work with zip files.</li>
                                <li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
                                <li><a href="http://tinymce.moxiecode.com/">TinyMCE</a> : TinyMCE is a <acronym title="What You See Is What You Get">WYSIWYG</acronym> editor which allows users to see their text formatting in real time.</li>
                                <li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Generic Syntax Highlighter used to highlight the source code of many programming languages.</li>
                                <li><a href="http://script.aculo.us/">script.aculo.us</a> : Javascript and <acronym title="Asynchronous Javascript And XML">AJAX</acronym> Framework</li>
                                <li><a href="http://www.alsacreations.fr/mp3-dewplayer.html">Dewplayer</a> : flash audio reader</li>
                                <li><a href="http://flowplayer.org">Flowplayer</a> : flash video reader</li>
                            </ul>
                        </fieldset>
                        <fieldset>
                            <legend>Credits</legend>
                            <ul>
                                <li><strong>R�gis VIARRE</strong> <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer retired</li>
                                <li><strong>Beno�t SAUTEL</strong> <em>(alias ben.popeye)</em>, developer retired</li>
                                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, developer retired</li>
                                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, developer</li>
                            </ul>
                        </fieldset>',
	'site.index' => 'Go to the website',
	'admin.index' => 'Go to the administration panel'
);
?>