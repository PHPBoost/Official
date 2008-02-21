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
require_once('../includes/begin.php');
load_module_lang('search');
// define('ALTERNATIVE_CSS', 'search');

//------------------------------------------------------------- Other includes
require_once('../includes/modules.class.php');
require_once('../search/search.inc.php');

$Template->Set_filenames(array(
'search_mini_form' => '../templates/'.$CONFIG['theme'].'/search/search_mini_form.tpl',
'search_forms' => '../templates/'.$CONFIG['theme'].'/search/search_forms.tpl',
'search_results' => '../templates/'.$CONFIG['theme'].'/search/search_results.tpl'
));

//--------------------------------------------------------------------- Params
define ( 'NB_RESULTS_PER_PAGE', 10);

// A prot�ger imp�rativement;
$pageNum = !empty($_GET['pageNum']) ? numeric($_GET['pageNum']) : 1;
$module = !empty($_GET['module']) ? securit($_GET['module']) : '';
$search = !empty($_GET['search']) ? securit($_GET['search']) : '';

//----------------------------------------------------------------------- Main

$Modules = new Modules();
$modulesArgs = array();

if( $search != '' )
{
	$results = array();
	
	// Listes des modules de recherches
	$searchModules = $Modules->GetAvailablesModules( 'GetSearchRequest');
	// Ajout du param�tre search � tous les modules
	foreach( $searchModules as $module)
	{
		$modulesArgs[$module->name] = array('search' => $args);
	}
	
	// Chargement des modules avec formulaires
	$formsModule = $Modules->GetAvailablesModules('GetSearchForm', $searchModules);
	
	// Ajout de la liste des param�tres de recherches sp�cifiques � chaque module
	foreach( $formsModule as $formModule)
	{
		if( $formModule->HasFunctionnality('GetSearchArgs') )
		{
			// R�cup�ration de la liste des param�tres
			$formModuleArgs = $formModule->Functionnality('GetSearchArgs');
			// Ajout des param�tres optionnels sans les s�curis�s.
			// Ils sont s�curis�s � l'int�rieur de chaque module.
			foreach( $formModuleArgs as $arg)
			{
				$modulesArgs[$formModule->name][$arg] = $_POST[$arg];
			}
		}
	}
	
	// G�n�ration des formulaires pr�compl�t�s et passage aux templates
	$searchForms = GetSearchForms( $formsModule, $modulesArgs );
	$Template->Assign_block_vars( 'forms', array( $searchForms ) );
	
	// G�n�ration des r�sultats et passage aux templates
	$nbResults = GetSearchResults( $search, $searchModules, $modulesArgs, $results, ($p - 1), ($p - 1 + NB_RESULTS_PER_PAGE));
	$Template->Assign_vars( array( 'nbResults' => $nbResults ) ) ;
	
	$htmlResults = array();
	foreach( $results as $result )
	{
		// A v�rifier que les r�sultats renvoy� par la m�thode GetResults de la classe Search
		// Renvoie bien un tableau associatif
		$module = $Modules->GetModule( $result['id_module'] );
		if( $module->HasFunctionnality( 'ParseSearchResult' ) )
			array_push( $htmlResults, $module->Functionnality( 'ParseSearchResult', array( $result ) ) );
		else
			array_push( $htmlResults, $result );
	}
	
	$Template->Assign_vars( array( 'htmlResults' => $htmlResults ) ) ;
	
	// parsage des formulaires de recherches
	$Template->Pparse('search_forms');
	// parsage des r�sultats de la recherche
	$Template->Pparse('search_results');
}
else
{
	// Listes des modules de recherches
	$searchModules = $Modules->GetAvailablesModules( 'GetSearchRequest' );
	// Chargement des modules avec formulaires
	$formsModule = $Modules->GetAvailablesModules( 'GetSearchForm', $searchModules );
	
	// G�n�ration des formulaires et passage aux templates
	$searchForms = GetSearchForms( $formsModule, $modulesArgs );
	$Template->Assign_block_vars( 'forms', array( $searchForms ) );
	
	// parsage de la page
	$Template->Pparse ( 'search_forms' );
}

//--------------------------------------------------------------------- Footer
require_once('../includes/footer.php');

?>