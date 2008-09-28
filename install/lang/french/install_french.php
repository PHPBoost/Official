<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : September 28, 2008
 *   copyright            : (C) 2008 	Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
#                                                           French                                                                               #
####################################################

$LANG = array();
$LANG['page_title'] = 'Installation de PHPBoost';
$LANG['steps_list'] = 'Liste des �tapes';
$LANG['introduction'] = 'Pr�ambule';
$LANG['config_server'] = 'Configuration du serveur';
$LANG['database_config'] = 'Configuration de la base de donn�es';
$LANG['advanced_config'] = 'Configuration du site';
$LANG['administrator_account_creation'] = 'Compte administrateur';
$LANG['modules_installation'] = 'Installation des modules';
$LANG['end'] = 'Fin de l\'installation';
$LANG['install_progress'] = 'Progression de l\'installation';
$LANG['generated_by'] = 'G�n�r� par %s';
$LANG['previous_step'] = 'Etape pr�c�dente';
$LANG['next_step'] = 'Etape suivante';
$LANG['query_loading'] = 'Chargement de la requ�te au serveur';
$LANG['query_sent'] = 'Requ�te envoy�e au serveur, attente d\'une r�ponse';
$LANG['query_processing'] = 'Traitement de la requ�te en cours';
$LANG['query_success'] = 'Traitement termin�';
$LANG['query_failure'] = 'Traitement �chou�';

//Introduction
$LANG['intro_title'] = 'Bienvenue dans l\'assistant d\'installation de PHPBoost';
$LANG['intro_explain'] = '<p>Merci d\'avoir accord� votre confiance � PHPBoost pour cr�er votre site web.</p>
<p>Pour installer PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre h�bergement qui devraient �tre fournies par votre h�bergeur. L\'installation est enti�rement automatis�e, elle ne devrait prendre que quelques minutes. Cliquez sur la fl�che ci-dessous pour d�marrer le processus d\'installation.</p>
<p>Cordialement l\'�quipe PHPBoost</p>';
$LANG['start_install'] = 'Commencer l\'installation';

//licence
$LANG['license'] = 'Licence';
$LANG['require_license_agreement'] = 'Vous devez accepter les termes de la licence GNU/GPL pour installer PHPBoost.';
$LANG['license_agreement'] = 'Acceptation des termes de la licence';
$LANG['license_terms'] = 'Termes de la licence';
$LANG['please_agree_license'] = 'J\'ai pris connaissance et j\'accepte les termes de la licence.';
$LANG['alert_agree_license'] = 'Vous devez accepter la licence en cochant le formulaire associ� pour pouvoir continuer !';

//Configuration du serveur
$LANG['config_server_title'] = 'V�rification de la configuration du serveur';
$LANG['config_server_explain'] = '<p>Avant de commencer les �tapes d\'installation de PHPBoost, la configuration de votre serveur va �tre v�rifi�e afin d\'�tablir sa compatibilit� avec PHPBoost. Veillez � ce que chaque condition obligatoire soit v�rifi�e sans quoi vous risquez d\'avoir des probl�mes en utilisant le logiciel.</p>
<p>En cas de probl�me n\'h�sitez pas � poser vos questions sur le <a href="http://www.phpboost.com/forum/index.php">forum de support</a>.</p>';
$LANG['php_version'] = 'Version de PHP';
$LANG['check_php_version'] = 'PHP sup�rieur � 4.1.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit �tre �quip� d\'une version sup�rieure ou �gale � PHP 4.1.0. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre h�bergeur ou migrez vers un serveur plus r�cent.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = 'Optionnel : L\'activation de ces extensions permet d\'apporter des fonctionnalit�s suppl�mentaires mais n\'est en aucun cas indispensable.';
$LANG['gd_library'] = 'Librairie GD';
$LANG['gd_library_explain'] = 'Librairie utilis�e pour g�n�rer des images. Utile par exemple pour la protection anti robots';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'R��criture des adresses des pages qui les rend plus lisibles et plus propices au r�f�rencement sur les moteurs de recherche';
$LANG['auth_dir'] = 'Autorisations des dossiers';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost n�cessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont chang�es de fa�on automatique. Cependant certains serveurs emp�chent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre h�b�geur.';
$LANG['refresh_chmod'] = 'Rev�rifier les dossiers';
$LANG['existing'] = 'Existant';
$LANG['unexisting'] = 'Inexistant';
$LANG['writable'] = 'Inscriptible';
$LANG['unwritable'] = 'Non inscriptible';
$LANG['unknown'] = 'Ind�terminable';

//Base de donn�es
$LANG['db_title'] = 'Param�tres de connexion � la base de donn�es';
$LANG['db_explain'] = '<p>Cette �tape permet de g�n�rer le fichier de configuration qui retiendra les identifiants de connexion � votre base de donn�es. Les tables permettant de faire fonctionner PHPBoost seront automatiquement cr��es lors de cette �tape. Si vous ne connaissez pas les informations ci-dessous, contactez votre h�b�rgeur qui vous les transmettra</a>.';
$LANG['dbms'] = 'Syst�me de gestion de base de donn�es';
$LANG['choose_dbms'] = 'Choisir le syst�me';
$LANG['choose_dbms_explain'] = 'MySQL par d�faut sur la plupart des serveurs';
$LANG['db_informations'] = 'Param�tres de la base de donn�es';
$LANG['db_host_name'] = 'Nom de l\'h�te';
$LANG['db_host_name_explain'] = 'Adresse du serveur qui g�re la base de donn�es, localhost la plupart du temps';
$LANG['db_login'] = 'Identifiant';
$LANG['db_login_explain'] = 'Fourni par l\'h�bergeur';
$LANG['db_password'] = 'Mot de passe';
$LANG['db_password_explain'] = 'Fourni par l\'h�bergeur';
$LANG['db_name'] = 'Nom de la base de donn�es';
$LANG['db_name_explain'] = 'Fourni par l\'h�bergeur';
$LANG['db_prefix'] = 'Prefixe des tables';
$LANG['db_prefix_explain'] = 'Par d�faut <em>phpboost_</em>';
$LANG['test_db_config'] = 'Essayer';
$LANG['result'] = 'R�sultats';
$LANG['empty_field'] = 'Le champ %s est vide';
$LANG['field_dbms'] = 'syst�me de gestion de base de donn�es';
$LANG['field_host'] = 'h�te';
$LANG['field_login'] = 'identifiant';
$LANG['field_password'] = 'mot de passe';
$LANG['field_database'] = 'nom de la base de donn�es';
$LANG['db_error_dbms'] = 'Le syst�me de gestion de base de donn�es que vous avez choisi n\'existe pas';
$LANG['db_error_connexion'] = 'Impossible de se connecter � la base de donn�es. Merci de v�rifier vos param�tres.';
$LANG['db_error_selection'] = 'Impossible de s�lectionner la base de donn�es. Merci de v�rifier son existence.';
$LANG['db_success'] = 'La connexion � la base de donn�es a �t� effectu�e avec succ�s. Vous pouvez poursuivre l\'installation';
$LANG['require_hostname'] = 'Vous devez renseigner le nom de l\'h�te !';
$LANG['require_login'] = 'Vous devez renseigner l\'identifiant de connexion !';
$LANG['require_db_name'] = 'Vous devez renseigner le nom de la base de donn�es !';
$LANG['db_result'] = 'R�sultats du test';

//configuraton du site
$LANG['config_site_explain'] = 'Configuration du site<br />
La configuration de base du site va �tre cr��e dans cette �tape afin de permettre � PHPBoost de fonctionner. Sachez cependant que toutes les donn�es que vous allez rentrer seront ult�rieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce m�me panneau renseigner davantage d\'informations facultatives � propos de votre site.';
$LANG['your_site'] = 'Votre site';
$LANG['site_url'] = 'Adresse du site :';
$LANG['site_url_explain'] = 'De la forme http://www.google.fr';
$LANG['site_path'] = 'Chemin de PHPBoost :';
$LANG['site_path_explain'] = 'Vide si votre site est � la racine du serveur, de la forme /dossier sinon';
$LANG['default_language'] = 'Langue du site par d�faut';
$LANG['default_theme'] = 'Th�me du site par d�faut';
$LANG['site_name'] = 'Nom du site';
$LANG['site_description'] = 'Description du site';
$LANG['site_description_explain'] = '(facultatif) Utile pour le r�f�rencement dans les moteurs de recherche';
$LANG['site_keywords'] = 'Mots cl�s du site';
$LANG['site_keywords_explain'] = '(facultatif) A rentrer s�par�s par des virgules, ils servent au r�f�rencement dans les moteurs de recherche';
$LANG['require_site_url'] = 'Vous devez entrer l\'adresse de votre site !';
$LANG['require_site_name'] = 'Vous devez entrer le nom de votre site !';
$LANG['confirm_site_url'] = 'L\'adresse du site que vous avez rentr�e ne correspond pas � celle d�tect�e par le serveur. Souhaitez vous vraiment choisir cette adresse ?';
$LANG['confirm_site_path'] = 'Le chemin du site sur le serveur que vous avez rentr�e ne correspond pas � celle d�tect�e par le serveur. Souhaitez vous vraiment choisir ce chemin ?';

//administration
$LANG['admin_account_creation_explain'] = 'Cr�ation du compte administrateur
<br />Ce compte donne acc�s au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte par la suite en consultant votre profil.';
$LANG['admin_account'] = 'Compte administrateur';
$LANG['admin_pseudo'] = 'Pseudo';
$LANG['admin_pseudo_explain'] = 'Minimum 3 caract�res';
$LANG['admin_password'] = 'Mot de passe';
$LANG['admin_password_explain'] = 'Minimum 6 caract�res';
$LANG['admin_password_repeat'] = 'R�p�ter le mot de passe';
$LANG['admin_mail'] = 'Courrier �lectronique';
$LANG['admin_mail_explain'] = 'Doit �tre valide pour recevoir le code de d�verrouillage';
$LANG['admin_lang'] = 'Langue';
$LANG['admin_require_login'] = 'Vous devez entrer un pseudo';
$LANG['admin_login_too_short'] = 'Votre pseudo est trop court (3 caract�res minimum)';
$LANG['admin_require_password'] = 'Vous devez entrer un mot de passe';
$LANG['admin_require_password_repeat'] = 'Vous devez confirmer votre mot de passe';
$LANG['admin_require_mail'] = 'Vous devez entrer une adresse email';
$LANG['admin_passwords_error'] = 'Les deux mots de passe que vous avez entr�s ne correspondent pas';
$LANG['admin_email_error'] = 'L\'adresse email que vous avez fournie n\'a pas une forme correcte';
$LANG['admin_create_session'] = 'Me connecter � la fin de l\'installation';
$LANG['admin_auto_connection'] = 'Rester connect� syst�matiquement � chacune de mes visites';
$LANG['admin_error'] = 'Erreur';
$LANG['admin_mail_object'] = 'PHPBoost : message � conserver';
$LANG['admin_mail_unlock_code'] = 'Cher %s,

Tout d\'abord, merci d\'avoir choisi PHPBoost pour r�aliser votre site, nous esp�rons qu\'il repondra au mieux � vos besoins. Pour tout probl�me n\'h�sitez pas � vous rendre sur le forum http://www.phpboost.com/forum/index.php

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus �tre r�cup�r�s).

Identifiant: %s 
Password: %s

A conserver ce code (Il ne vous sera plus d�livr�) : %s

Ce code permet le d�verrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionn�, il vous sera demand� dans le formulaire de connexion directe � l\'administration (%s/admin/admin.php) 

Cordialement l\'�quipe PHPBoost.';

//Installation des modules
$LANG['modules_explain'] = 'Installation des modules
<br />Vous pouvez d�s maintenant installer des modules qui vous permettront d\'�laborer � votre convenance votre site. Nous vous proposons quelques pr�selections afin de faciliter l\'installation mais vous pouvez composer votre propre s�lection. A noter que vous pourrez par la suite installer et d�sinstaller n\'importe quel module, cette �tape ne vous engage � rien mais vous permet de partir sur une configuration adapt�e � vos besoins.';
$LANG['modules_list'] = 'Liste des modules disponibles';
$LANG['modules_preselections'] = 'Pr�s�lections de modules propos�es';
$LANG['modules_no_module'] = 'Aucun module';
$LANG['modules_all'] = 'Tous les modules disponibles';
$LANG['modules_community'] = 'Portail communautaire';
$LANG['modules_publication'] = 'Site de publication';
$LANG['modules_perso'] = 'Personnalis�';
$LANG['modules_other_options'] = 'Autres options';
$LANG['modules_activ_member_accounts'] = 'Activer l\'inscription des membres';
$LANG['modules_index_module'] = 'Module de d�marrage : ';
$LANG['modules_default_index'] = 'Page par d�faut';
$LANG['modules_require_javascript'] = 'Vous devez activer le javascript pour pouvoir profiter pleinement des pr�selections et de la page de d�marrage';

//Fin de l'installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost est d�sormais install� !</legend>
							<p class="success">
								L\'installation de PHPBoost s\'est d�roul�e avec succ�s. L\'�quipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.
							</p>
							<p>Sur l\'accueil de l\'administration, vous retrouverez les news du site officiel de PHPBoost en temps r�el, pensez � y jeter un coup de d\'oeil de temps en temps pour �tre au courant des nouveaut�s. Sur cette m�me page vous serez aussi averti des mises � jour disponibles concernant le noyau ou un de vos modules. Nous vous conseillons de tenir votre version de PHPBoost � jour afin de profiter des derni�res fonctionnalit�s ainsi que de corriger les �ventuelles failles ou erreurs.</p>
							<p class="warning">
								Par mesure de s�curit� nous vous conseillons fortement de supprimer le dossier install et tout ce qu\'il contient, des personnes mal intentionn�es pourraient relancer le script d\'installation et �craser certaines de vos donn�es !</p>
							<p>N\'oubliez pas la <a href="http://www.phpboost.com/wiki/index.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost.</p>
							<p>En cas de probl�me, rendez-vous sur le forum du support de PHPBoost : <a href="http://www.phpboost.com/forum/index.php">forum PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Remerciements</legend>
							Membres
							<br />
							<ul>
								<li>Merci � tous les membres qui nous ont encourag� et qui nous ont signal� tous les bugs qu\'ils ont pu rencontrer, ce qui aura permis � PHPBoost 2.1 d\'�tre stable.</li>
								<li>Merci aux membres de l\'�quipe de d�veloppement de modules (Florent), de l\'�quipe graphique (KONA, tonyck), de l\'�quipe de traduction (Forensic) et de l\'�quipe de r�daction (Ptithom, Mat)</li>
							</ul>
							<br />
							Projets
							<br />
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Editeur texte surpuissant utilis� pour la totalit� du d�veloppement, un immense merci!</li>
								<li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Ensemble d\'ic�nes diverses utilis�es sur l\'ensemble de PHPBoost.</li>
								<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP par PHPConcept</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
								<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules math�matiques � partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
							</ul>
							<p style="text-align:center"><img src="images/npp_logo.gif" alt="" /></p>
						</fieldset>
						<fieldset>
							<legend>Cr�dits</legend>
							<ul>
								<li>R�gis VIARRE <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et d�veloppeur</li>
								<li>Beno�t SAUTEL <em>(alias ben.popeye)</em>, d�veloppeur</li>
								<li>Lo�c ROUCHON <em>(alias horn)</em>, d�veloppeur</li>
							</ul>
						</fieldset>';
$LANG['site_index'] = 'Aller � l\'accueil du site';
						
//Enregistrement en ligne
$LANG['register_online'] = 'Enregistrement en ligne';
$LANG['register_online_explain'] = 'Il est possible de vous enregistrer automatiquement en ligne. L\'enregistrement en ligne permettra � votre site d\'appara�tre automatiquement sur la liste des portails PHPBoost install�s du site officiel de PHPBoost (<a href="http://www.phpboost.com/phpboost/list.php">liste des portails install�s</a>).
<br />
Vous n\'�tes pas oblig� de vous enregistrer, nous vous proposons simplement ce service afin de vous aider � faire conna�tre votre site.  Si vous installez PHPBoost pour le tester en local ou en ligne ou que vous souhaitez que votre site ne soit pas connu du public vous ne devez pas l\'enregistrer.
<br />
<div class="notice">Attention : vous devez �tre connect� � Internet pour pouvoir enregistrer votre site en ligne.</div>';
$LANG['register'] = 'M\'enregistrer';
$LANG['register_i_want_to'] = 'Je souhaite m\'enregistrer en ligne et ainsi appara�tre sur le site officiel de PHPBoost.';

//Divers
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['appendices'] = 'Annexes';
$LANG['documentation'] = 'Documentation';
$LANG['documentation_link'] = 'http://www.phpboost.com/wiki/installer-phpboost';
$LANG['restart_installation'] = 'Recommencer l\'installation';
$LANG['confirm_restart_installation'] = addslashes('Etes-vous certain de vouloir recommencer l\'installation ?');
$LANG['change_lang'] = 'Changer de langue';
$LANG['change'] = 'Changer';
		
?>