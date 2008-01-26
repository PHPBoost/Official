<?php
/*##################################################
 *                              contact_begin.php
 *                            -------------------
 *   begin                : November 26, 2007
 *   copyright          : (C) 2007 Viarre r�gis
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

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Autorisation sur le module.
if( !$groups->check_auth($SECURE_MODULE['contact'], ACCESS_MODULE) )
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('contact', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['title_contact']);
$cache->load_file('contact');

?>