<?php 
/*##################################################
 *                               begin.php
 *                            -------------------
 *   begin                : Februar 08, 2006
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

if (!defined(PATH_TO_ROOT))
    define('PATH_TO_ROOT', '..');

header('Content-type: text/html; charset=iso-8859-1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date du pass�
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // toujours modifi�
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');
	
//Inclusion des fichiers
require_once(PATH_TO_ROOT . '/kernel/framework/bench.class.php');
$Bench = new Bench; //D�but du benchmark
$Bench->Start_bench('site');
require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php'); //Fonctions de base.
require_once(PATH_TO_ROOT . '/kernel/constant.php'); //Constante utiles.
require_once(PATH_TO_ROOT . '/kernel/framework/content/mathpublisher.php'); //Gestion des formules math�matiques.
require_once(PATH_TO_ROOT . '/kernel/framework/errors.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/db/' . DBTYPE . '.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/cache.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/members/sessions.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/members/member.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/members/groups.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/breadcrumb.class.php');

//Instanciation des objets indispensables au noyau.
$Errorh = new Errors; //!\\Initialisation  de la class des erreurs//!\\
$Template = new Template; //!\\Initialisation des templates//!\\
$Sql = new Sql($sql_host, $sql_login, $sql_pass, $sql_base); //!\\Initialisation  de la class sql//!\\
unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.

$Cache = new Cache; //!\\Initialisation  de la class de gestion du cache//!\\
$Bread_crumb = new Bread_crumb; //!\\Initialisation  de la class de la speed bar//!\\

//Chargement ddes fichiers cache, indispensables au noyau.
$CONFIG = array();
$Cache->Load_file('config'); //Requ�te des configuration g�n�rales, $CONFIG variable globale.
$Cache->Load_file('groups'); //Cache des groupes.
$Cache->Load_file('member'); //Chargement de la configuration des membres.
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);

$Session = new Sessions; //!\\Initialisation  de la class des sessions//!\\

//Activation de la buff�risation de sortie
if( $CONFIG['ob_gzhandler'] == 1 )
	ob_start('ob_gzhandler'); //Activation de la compression de donn�es
else
	ob_start();
	
//R�cup�ration des informations sur le membre.
$Session->Session_info();

$Group = new Group($_array_groups_auth); //!\\Initialisation  de la class de gestion des groupes//!\\
$Member = new Member($Session->data, $_array_groups_auth); //!\\Initialisation  de la class de gestion des membres//!\\

//D�finition de la constante de transmission des infos de session.
if( $Session->session_mod )
{
	define('SID', '?sid=' . $Member->Get_attribute('session_id') . '&amp;suid=' . $Member->Get_attribute('user_id'));
	define('SID2', '?sid=' . $Member->Get_attribute('session_id') . '&suid=' . $Member->Get_attribute('user_id'));
}
else
{
	define('SID', '');
	define('SID2', '');
}

//Si le th�me n'existe pas on prend le suivant pr�sent sur le serveur/
$CONFIG['theme'] = find_require_dir(PATH_TO_ROOT . '/templates/', ($Member->Get_attribute('user_theme') == '' || $CONFIG_MEMBER['force_theme'] == 1) ? $CONFIG['theme'] : $Member->Get_attribute('user_theme'));

//Si le dossier de langue n'existe pas on prend le suivant exisant.
$CONFIG['lang'] = find_require_dir(PATH_TO_ROOT . '/lang/', ($Member->Get_attribute('user_lang') == '' ? $CONFIG['lang'] : $Member->Get_attribute('user_lang')));
$LANG = array();
require_once(PATH_TO_ROOT . '/lang/' . $CONFIG['lang'] . '/main.php'); //!\\ Langues //!\\
require_once(PATH_TO_ROOT . '/lang/' . $CONFIG['lang'] . '/errors.php'); //Inclusion des langues des erreurs.

//Chargement du cache du jour actuel.
$Cache->Load_file('day');
//On v�rifie que le jour n'a pas chang� => sinon on execute les requ�tes.. (simulation d'une tache cron).
if( gmdate_format('j', time(), TIMEZONE_SITE) != $_record_day && !empty($_record_day) ) 
{
    //Inscription du nouveau jour dans le fichier en cache.
    $Cache->Generate_file('day');
    
    //V�rification pour emp�cher une double mise � jour.
    $check_update = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."stats WHERE stats_year = '" . gmdate_format('Y', time(), TIMEZONE_SYSTEM) . "' AND stats_month = '" . gmdate_format('m', time(), TIMEZONE_SYSTEM) . "' AND stats_day = '" . gmdate_format('d', time(), TIMEZONE_SYSTEM) . "'", __LINE__, __FILE__);
    
    require_once(PATH_TO_ROOT . '/kernel/changeday.php');
}

include_once(PATH_TO_ROOT . '/kernel/connect.php'); //Inclusion du gestionnaire de connexion.
	
//Cache des autorisations des modules
$Cache->Load_file('modules');

//Autorisation sur le module charg�
define('MODULE_NAME', get_module_name());
if( isset($MODULES[MODULE_NAME]) && $MODULES[MODULE_NAME]['activ'] == 1 )
{
	if( !$Member->Check_auth($MODULES[MODULE_NAME]['auth'], ACCESS_MODULE) ) //Acc�s non autoris� !
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}

?>