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

if( defined('PHP_BOOST') !== true ) exit;

require_once ( '../includes/modules.class.php' );
require_once ( '../includes/search.class.php' );


$Cache->Load_file('search');
global $SEARCH_CONFIG;

define ( 'NB_RESULTS_PER_PAGE', $SEARCH_CONFIG['nb_results_per_page']);

function GetSearchForms(&$modules, &$args)
/**
 *  Affiche les formulaires de recherches pour tous les modules.
 */
{
    $searchForms = array();
    foreach( $modules as $module )
    {
        if( isset($args[$module->name]) )
            $searchForms[$module->name] = $module->Functionnality('GetSearchForm', $args[$module->name]);
        else
            $searchForms[$module->name] = $module->Functionnality('GetSearchForm', array('search' => ''));
    }
    
    return $searchForms;
}

function ExecuteSearch($Search, &$searchModules, &$modulesArgs, &$results)
/**
 *  Ex�cute la recherche
 */
{
    $requests = array();
    
    foreach($searchModules as $module)
    {
        if( !$Search->IsInCache($module->name) )
        {
            // On rajoute l'identifiant de recherche comme parametre pour faciliter la requete
            $modulesArgs[$module->name]['id_search'] = $Search->id_search[$module->name];
            $requests[$module->name] = $module->Functionnality('GetSearchRequest', $modulesArgs[$module->name]);
        }
    }
    
    $Search->InsertResults($requests);
}

function GetSearchResults($searchTxt, &$searchModules, &$modulesArgs, &$results, &$idsSearch, $justInsert = false)
/**
 *  Ex�cute la recherche si les r�sultats ne sont pas dans le cache et
 *  renvoie les r�sultats.
 */
{
    $modulesNames = array();
    $modulesOptions = array();
    
    // Generation des noms des modules utilis�s et de la chaine options
    foreach($searchModules as $module)
    {
        array_push($modulesNames, $module->name);
        // enleve la chaine search de la chaine options et la tronque a 255 caracteres
        $modulesOptions[$module->name] = md5(implode('|', $modulesArgs[$module->name]));
    }
    
    $Search = new Search($searchTxt, $modulesOptions);
    ExecuteSearch($Search, $searchModules, $modulesArgs, $results);
    $idsSearch = $Search->id_search;
    
    if ( !$justInsert )
        return $Search->GetResults($results, $modulesNames);
    else
        return -1;
}

function Get_HTML_Results($results, &$htmlResults, $Modules, $resultsName)
/**
 *  Renvoie une chanee contenant les resultats
 */
{
    $i = 0;
    $j = 0;
    $htmlResults = '';
    foreach( $results as $result )
    {
        $module = $Modules->GetModule($result['module']);
        
        if ( $i == NB_RESULTS_PER_PAGE )
        {
            $htmlResults .= '</ul></div>';
            $i = 0;
        }
        if ( $i == 0 )
        {
            $htmlResults .= '<div id="results'.ucfirst($resultsName).'_'.$j.'" style="display:none"><ul class="search_results">';
            $j++;
        }
        
        $htmlResults .= '<li>';
        if ( !$module->HasFunctionnality('ParseSearchResult') )
        {
            $htmlResults .= '<div class="result">';
            $htmlResults .= '<span><i>'.$result['relevance'].'</i></span> - ';
            $htmlResults .= '<span><b>'.ucfirst($result['module']).'</b></span> - ';
            $htmlResults .= '<a href="'.transid($result['link']).'">'.$result['title'].'</a>';
            $htmlResults .= '</div>';
        }
        else $htmlResults .= $module->Functionnality('ParseSearchResult', array($result));
        
        $htmlResults .= '</li>';
        $i++;
    }
    
    if ( count($results) > 0 )
        $htmlResults .= '</ul></div>';
}

?>