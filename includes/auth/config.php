<?php
if( !defined('DBSECURE') )
{
	$sql_host = "localhost"; //Adresse serveur mysql.
	$sql_login = "root"; //Login
	$sql_pass = ""; //Mot de passe
	$sql_base = "phpboost"; //Nom de la base de donn�es.
	$table_prefix = "phpboost_"; //Pr�fixe des tables
	$dbtype = "mysql"; //Syst�me de gestion de base de donn�es
	define('DBSECURE', true);
	define('PHPBOOST_INSTALLED', true);
}	
else
{
	exit;
}
?>