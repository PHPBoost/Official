<?php

/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
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

require_once('../includes/module_interface.class.php');

/**
 *  Les arguments de fonction nomm� "$modules" sont assez particulier.
 *
 *  Il s'agit d'un tableau avec comme cl�s le nom des modules et comme
 *  arguments un tableau d'arguments correspondant � la liste des arguments
 *  n�cessaire pour la m�thode de ce module particulier.
 *
 *  Par exemple, la recherche sur le forum peut n�cessiter plus d'option
 *  qu'une recherche sur le wiki.
 *
 */

class Modules
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- M�thodes publiques
    function Functionnality($functionnality, $modules)
    /**
     *  V�rifie les fonctionnalit�s des modules et appelle la m�thode
     *  du/des module(s) s�lectionn�(s) avec les bons arguments.
     */
    {
        $results = array();
        foreach($modules as $moduleName => $args)
        {
            // Instanciation de l'objet $module
            $module = $this->GetModule($moduleName);
            // Si le module � d�j� �t� appel� et a d�j� eu une erreur,
            // On nettoie le bit d'erreur correspondant.
            $Module->clearFunctionnalityError();
            if( $Module->hasFunctionnality($functionnality) == true )
				$results[$moduleName] = $Module->Functionnality($functionnality, $args);
        }
        return $results;
    }

    function GetAvailablesModules($functionnality, $modulesList = array())
    /**
     *  Renvoie la liste des modules disposant de la fonctionnalit� demand�e.
     *  Si $modulesList est sp�cifi�, alors on ne recherche que le sous ensemble de celui-ci
     */
    {
        $modules = array();
        if( $modulesList === array() )
        {
            global $SECURE_MODULE;
            foreach(array_keys($SECURE_MODULE) as $moduleName)
            {
                $module = $this->GetModule($moduleName);
                if( $module->GetErrors == 0 && $module->HasFunctionnality($functionnality) )
                    array_push($modules, $module);
            }
        }
        else
        {
            foreach($modulesList as $module)
            {
                if( $module->GetErrors == 0 && $module->HasFunctionnality($functionnality) )
                    array_push($modules, $module);
            }
        }
        return $modules;
    }

    function GetModule($moduleName = '')
    /**
     *  Instancie et renvoie le module demand�.
     */
    {
        if( !isset($this->loadedModules[$moduleName]) )
        {
            if( in_array($moduleName, $this->availablesModules) )
            {
                global $Member, $SECURE_MODULE;
                if( $Member->check_level($SECURE_MODULE[$moduleName]) )
                {
                    if( @include_once('../'.$moduleName.'/'.$moduleName.'_interface.class.php') )
                    {
                        $moduleConstructor = ucfirst($moduleName.'Interface');
                        $Module = new $moduleConstructor();
                    }
                    else
						$Module = new ModuleInterface($moduleName, MODULE_NOT_YET_IMPLEMENTED);
                }
                else
					$Module = new ModuleInterface($moduleName, ACCES_DENIED);
            }
            else
				$Module = new ModuleInterface($moduleName, MODULE_NOT_AVAILABLE); 
            $this->loadedModules[$moduleName] = $Module;
        }
        return $this->loadedModules[$moduleName];
    }

    //---------------------------------------------------------- Constructeurs
    function Modules()
    /**
     *  Constructeur de la classe Modules
     */
    {
        global $SECURE_MODULE;
        
        $this->loadedModules = array();
        $this->availablesModules = array_keys($SECURE_MODULE);
    }

    //------------------------------------------------------------------ PRIVE
    /**
     *  Pour des raisons de compatibilit� avec PHP 4, les mots-cl�s private,
     *  protected et public ne sont pas utilis�.
     *  
     *  L'appel aux m�thodes et/ou attributs PRIVE/PROTEGE est donc possible.
     *  Cependant il est strictement d�conseill�, car cette partie du code
     *  est suceptible de changer sans avertissement et donc vos modules ne
     *  fonctionnerai plus.
     *  
     *  Bref, utilisation � vos risques et p�rils !!!
     *  
     */
    //----------------------------------------------------- M�thodes prot�g�es

    //----------------------------------------------------- Attributs prot�g�s
    var $loadedModules;
    var $availablesModules;
}

?>
