<?php
/*##################################################
 *                                constant.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
 *   Constantes utiles
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

require_once('../includes/auth/config.php'); //Fichier de configuration.

//PHPBoost install�? Si non redirection manuelle, car chemin non connu.
if( !defined('PHPBOOST_INSTALLED') )
{
    require_once('../includes/unusual_functions.php');
    redirect(get_server_url_page('install/install.php'));
}

set_magic_quotes_runtime(0); //D�sactivation du magic_quotes_runtime (�chappe les guillemets des sources externes).
//Si register_globals activ�, suppression des variables qui trainent.
if( @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' )
{
    require_once('../includes/unusual_functions.php');
    securit_register_globals();
}

//D�finition des constantes utiles.

define('GUEST_LEVEL', -1); //Niveau Visiteur.
define('MEMBER_LEVEL', 0); //Niveau Membre.
define('MODO_LEVEL', 1); //Niveau Modo.
define('ADMIN_LEVEL', 2); //Niveau Admin.
define('DBTYPE', $dbtype); ///Type de base de donn�es utilis�e.
define('SCRIPT', $_SERVER['PHP_SELF']); //Adresse relative � la racine du script.
define('QUERY_STRING', addslashes($_SERVER['QUERY_STRING'])); //R�cup�re la chaine de variables $_GET.
define('MAGIC_QUOTES', get_magic_quotes_gpc()); //R�cup�re la valeur du magic quotes.
define('PHPBOOST', true); //Permet de s'assurer des inclusions.
define('PREFIX', $table_prefix); //Prefix des tables SQL.
define('ERROR_REPORTING', E_ALL | E_NOTICE);
define('E_USER_REDIRECT', -1); //Erreur avec redirection
define('E_USER_SUCCESS', -2); //Succ�s.
define('HTML_UNPROTECT', false); //Non protection de l'html.

define('AUTH_FILES', 0x01); //Configuration g�n�rale des fichiers

//R�cup�ration de l'ip, essaye de r�cup�rer la v�ritable ip avec un proxy.
if( $_SERVER )  
{
    if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif( isset($_SERVER['HTTP_CLIENT_IP']) ) $ip = $_SERVER['HTTP_CLIENT_IP'];
    else $ip = $_SERVER['REMOTE_ADDR'];
}
else 
{
    if( getenv('HTTP_X_FORWARDED_FOR') ) $ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif( getenv('HTTP_CLIENT_IP') )  $ip = getenv('HTTP_CLIENT_IP');
    else $ip = getenv('REMOTE_ADDR');
}
//On s�curise l'ip => never trust user input!
define('USER_IP', securit($ip));

?>