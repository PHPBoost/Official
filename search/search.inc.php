<?php
/*##################################################
*                               search.inc.php
*                            -------------------
*   begin                : february 5, 2008
*   copyright            : (C) 2008 Rouchon Lo�c
*   email                : horn@phpboost.com
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

require_once ( '../includes/modules.class.php' );
require_once ( '../includes/search.class.php' );

function GetSearchForms(&$modules, &$args)
/**
*  Affiche les formulaires de recherches pour tous les modules.
*/
{
	$searchForms = array();
	foreach($modules as $module)
	{
		if( isset($args[$module->name]) )
			$searchForms[$module->name] = $module->Functionnality('GetSearchForm', $args[$module->name]);
		else
			$searchForms[$module->name] = $module->Functionnality('GetSearchForm', array('search' => ''));
	}
	
	return $searchForms;
}

function GetSearchResults($searchTxt, &$searchModules, &$modulesArgs, &$results, $offset = 0, $nbResults = 10)
/**
*  Ex�cute la recherche si les r�sultats ne sont pas dans le cache et
*  renvoie les r�sultats.
*/
{
	$requests = array();
	$modulesNames = array();
	foreach($searchModules as $module)
	{
		$requests[$module->name] = $module->Functionnality('GetSearchRequest', $modulesArgs[$module->name]);
		array_push($modulesNames, $module->name);
	}
	
	$Search = new Search($searchTxt, $modulesNames);
	$Search->InsertResults($request);
	
	return $Search->GetResults(&$results, &$id_modules, $offset = 0, $nbLines = NB_LINES);
}

?>