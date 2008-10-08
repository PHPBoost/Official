<?php
/*##################################################
 *                               constant.php
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

set_magic_quotes_runtime(0); //D�sactivation du magic_quotes_runtime (�chappe les guillemets des sources externes).
//Si register_globals activ�, suppression des variables qui trainent.
if( @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' )
{
    require_once(PATH_TO_ROOT . '/kernel/framework/unusual_functions.php');
    securit_register_globals();
}

### D�finition des constantes utiles. ###
define('GUEST_LEVEL', -1); //Niveau Visiteur.
define('MEMBER_LEVEL', 0); //Niveau Membre.
define('MODO_LEVEL', 1); //Niveau Modo.
define('MODERATOR_LEVEL', 1); //Niveau Modo.
define('ADMIN_LEVEL', 2); //Niveau Admin.
define('SCRIPT', $_SERVER['PHP_SELF']); //Adresse relative � la racine du script.
define('QUERY_STRING', addslashes($_SERVER['QUERY_STRING'])); //R�cup�re la chaine de variables $_GET.
define('MAGIC_QUOTES', get_magic_quotes_gpc()); //R�cup�re la valeur du magic quotes.
define('PHPBOOST', true); //Permet de s'assurer des inclusions.
define('ERROR_REPORTING', E_ALL | E_NOTICE);
define('E_USER_REDIRECT', -1); //Erreur avec redirection
define('E_USER_SUCCESS', -2); //Succ�s.
define('HTML_UNPROTECT', false); //Non protection de l'html.

### Autorisations ###
define('AUTH_MENUS', 0x01); //Autorisations en lecture des menus.
define('AUTH_FILES', 0x01); //Configuration g�n�rale des fichiers
define('ACCESS_MODULE', 0x01); //Acc�s � un module.
define('AUTH_FLOOD', 'auth_flood'); //Droit de flooder.
define('PM_GROUP_LIMIT', 'pm_group_limit'); //Aucune limite de messages priv�s.
define('DATA_GROUP_LIMIT', 'data_group_limit'); //Aucune limite de donn�es uploadables.

//Types des variables en request.
define('GET', 1); 
define('POST', 2);
define('REQUEST', 3);
define('COOKIE', 4); 
define('TBOOL', 'boolean'); 
define('TINTEGER', 'integer'); 
define('TDOUBLE', 'double'); 
define('TFLOAT', 'double'); 
define('TSTRING', 'string'); 
define('TSTRING_PARSE', 'string_parse'); 
define('TSTRING_UNSECURE', 'string_unsecure'); 
define('TSTRING_HTML', 'string_html'); 
define('TSTRING_UNCHANGE', 'string_unchanged'); 
define('TARRAY', 'array'); 
define('TUNSIGNED_INT', 'uint'); 
define('TUNSIGNED_DOUBLE', 'udouble'); 
define('TUNSIGNED_FLOAT', 'udouble'); 

//R�cup�ration de l'ip, essaye de r�cup�rer la v�ritable ip avec un proxy.
if( $_SERVER )  
{
    if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) 
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif( isset($_SERVER['HTTP_CLIENT_IP']) ) 
		$ip = $_SERVER['HTTP_CLIENT_IP'];
    else 
		$ip = $_SERVER['REMOTE_ADDR'];
}
else 
{
    if( getenv('HTTP_X_FORWARDED_FOR') ) 
		$ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif( getenv('HTTP_CLIENT_IP') )  
		$ip = getenv('HTTP_CLIENT_IP');
    else 
		$ip = getenv('REMOTE_ADDR');
}
define('USER_IP', addslashes($ip));

?>