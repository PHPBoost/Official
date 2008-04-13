<?php
/*##################################################
 *                                module_mini.php
 *                            -------------------
 *   begin                : April 06, 2006
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

if( defined('PHPBOOST') !== true)	exit;

if( @!include('../cache/modules_mini.php') )
{
	$Cache->generate_file('modules_mini');
	
	//On inclue une nouvelle fois
	if( @!include('../cache/modules_mini.php') )
		$Errorh->Error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
}

?>