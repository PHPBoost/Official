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

import('modules/module_interface');

/**
 *  Les arguments de fonction nomm� "$modules" sont assez particulier.
 *
 *  Il s'agit d'un tableau avec comme cl�s le nom des modules et comme
 *  arguments un tableau d'arguments correspondant � la liste des arguments
 *  n�cessaire pour la m�thode de ce module particulier.
 *
 *  Par exemple, la recherche sur le forum peut n�cessiter plus d'options
 *  qu'une recherche sur le wiki.
 *
 */

class ModulesDiscoveryService
{
    //---------------------------------------------------------- Constructeurs
    function ModulesDiscoveryService()
    /**
     *  Constructeur de la classe Modules
     */
    {
        global $MODULES;
        
        $this->loaded_modules = array();
        $this->availables_modules = array();
        foreach ($MODULES as $module_id => $module)
        {
            if (!empty($module['activ']) && $module['activ'] == true)
                $this->availables_modules[] = $module_id;
        }
    }
    
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- M�thodes publiques
    function functionnality($functionnality, $modules)
    /**
     *  V�rifie les fonctionnalit�s des modules et appelle la m�thode
     *  du/des module(s) s�lectionn�(s) avec les bons arguments.
     */
    {
        $results = array();
        foreach ($modules as $moduleName => $args)
        {
            // Instanciation de l'objet $module
            $module = $this->get_module($moduleName);
            // Si le module � d�j� �t� appel� et a d�j� eu une erreur,
            // On nettoie le bit d'erreur correspondant.
            $module->clear_functionnality_error();
            if ($module->has_functionnality($functionnality) == true)
                $results[$moduleName] = $module->functionnality($functionnality, $args);
        }
        return $results;
    }

    
    /**
     * @desc Returns a list with all the modules in it, even with those that have
     * no ModuleInterface.
     * Useful to do generic operations on modules.
     * @return ModuleInterface[] the ModuleInterface list
     */
    function get_all_modules()
    {
        return $this->get_available_modules('none', array(), true);
    }
    
    function get_available_modules($functionnality='none', $modulesList = array(), $included_failure = false)
    /**
     *  Renvoie la liste des modules disposant de la fonctionnalit� demand�e.
     *  Si $modulesList est sp�cifi�, alors on ne recherche que le sous ensemble de celui-ci
     */
    {
        $modules = array();
        if ($modulesList === array())
        {
            global $MODULES;
            foreach (array_keys($MODULES) as $module_id)
            {
			   $module = $this->get_module($module_id);
                if ($included_failure || (!$module->got_error() && $module->has_functionnality($functionnality)))
                    array_push($modules, $module);
            }
        }
        else
        {
            foreach ($modulesList as $module)
            {
                if ($included_failure || (!$module->got_error() && $module->has_functionnality($functionnality)))
                    array_push($modules, $module);
            }
        }
        return $modules;
    }

    function get_module($module_id = '')
    /**
     *  Instancie et renvoie le module demand�.
     */
    {
        $module_constructor = ucfirst($module_id.'Interface');
        
        $include = @include_once(PATH_TO_ROOT . '/' . $module_id . '/' . $module_id . '_interface.class.php');
        if ($include && class_exists($module_constructor))
        {   // The Interface exists
            $module = new $module_constructor();
            
    		if (isset($this->loaded_modules[$module_id]))
    		    return $this->loaded_modules[$module_id];
    		
            if (in_array($module_id, $this->availables_modules))
            {   // The interface is available
                global $User, $MODULES;
                
                if (!$User->check_auth($MODULES[$module_id]['auth'], ACCESS_MODULE))
                {   // ACCESS DENIED
                    $module->set_error(ACCES_DENIED);
                }
            }
            else
            {   // NOT AVAILABLE
                $module->set_error(MODULE_NOT_AVAILABLE);
            }
        }
        else
        {   // NOT IMPLEMENTED
            $module = new ModuleInterface($module_id, MODULE_NOT_YET_IMPLEMENTED);
        }
        
        $this->loaded_modules[$module_id] = $module;
        return $this->loaded_modules[$module_id];
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
