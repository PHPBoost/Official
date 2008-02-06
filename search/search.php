<?php
/*##################################################
 *                               search.php
 *                            -------------------
 *   begin                : January 27, 2008
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

//------------------------------------------------------- Headers and Language
require_once ( '../includes/begin.php' );
load_module_lang ( 'search', $CONFIG['lang'] );
// define('ALTERNATIVE_CSS', 'search');

//------------------------------------------------------------- Other includes
require_once ( '../includes/modules.class.php' );
require_once ( '../search/search.inc.php' );


//--------------------------------------------------------------------- Params
define ( 'NB_RESULTS_PER_PAGE', 10 );

// A prot�ger imp�rativement;
$pageNum = !empty ( $_GET['pageNum'] ) ? numeric ($_GET['pageNum'] ) : 1;
$module = !empty ( $_GET['module'] ) ? securit ( $_GET['module'] ) : '';
$search = !empty ( $_GET['search'] ) ? securit ( $_GET['search'] ) : '';

//----------------------------------------------------------------------- Main

if ( $search != '' )
{
    $results = Array ( );
    $modulesArgs = Array ( );
    $modules = new Modules ( );
    
    // Listes des modules de recherches
    $searchModules = $modules->GetAvailablesModules ( 'GetSearchRequest' );
    // Ajout du param�tre search � tous les modules
    foreach ( $searchModules as $module )
    {
        $modulesArgs[$module->name] = Array ('search' => $args );
    }
    
    // Chargement des modules avec formulaires
    $formsModule = $modules->GetAvailablesModules ( 'GetSearchForm', $searchModules );
    
    // Ajout de la liste des param�tres de recherches sp�cifiques � chaque module
    foreach ( $formsModule as $formModule)
    {
        if ( $formModule->HasFunctionnalitie ( 'GetSearchArgs' ) )
        {
            // R�cup�ration de la liste des param�tres
            $formModuleArgs = $formModule->Functionnalitie ( 'GetSearchArgs' );
            // Ajout des param�tres optionnels sans les s�curis�s.
            // Ils sont s�curis�s � l'int�rieur de chaque module.
            foreach ( $formModuleArgs as $arg )
            {
                array_push ( $modulesArgs[$formModule->name], $_POST[$arg] );
            }
        }
    }
    
    // Affiche les formulaires pr�rempli pr�compl�t�es
    $searchForms = GetSearchForms ( $formsModule , $modulesArgs );
    
    $nbResults = GetSearchResults ( $search, $searchModules, $modulesArgs, $results, ($p - 1), ($p - 1 + NB_RESULTS_PER_PAGE ) );
}
else
{
    // Listes des modules de recherches
    $searchModules = $modules->GetAvailablesModules ( 'GetSearchRequest' );
    // Chargement des modules avec formulaires
    $formsModule = $modules->GetAvailablesModules ( 'GetSearchForm', $searchModules );
    // Affiche les formulaires vides
    
    $searchForms = GetSearchForms ( $formsModule );
}

//--------------------------------------------------------------------- Footer
require_once( '../includes/footer.php' );

?>