<?php

/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright          : (C) 2008 Rouchon Lo�c
 *   email                : xhorn37@yahoo.fr
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

define('LIST_FUNCTIONNALITIES','Search,LatestAdds,LatestModifications,MadeBy');
define('ACCES DENIED', -1);
define('MODULE_NOT_AVAILABLE', -2);
define('MODULE_NOT_IMPLEMENTED', -3);
define('FUNCTIONNALITIE_DOES_NOT_EXIST', -4);

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
    function Functionnalitie ( $functionnalitie, $modules )
    /**
     *  V�rifie les fonctionnalit� des modules et appelle la m�thode
     *  du/des module(s) s�lectionn�(s) avec les bons arguments.
     */
    {
        if ( in_array($this->functionnalities, $functionnalitie) )
        {
            $results = Array( );
            foreach($modules as $moduleName => $args)
            {
                // Instanciation de l'objet $module
                $module = $this->GetModule($moduleName);
                if ( $this->checkModuleFunctionnalitie ( $functionnalitie, $module ) )
                { $results[$moduleName] = $module->$functionnalitie($args); }
            }
            return $results;
        }
        else { return FUNCTIONNALITIE_DOES_NOT_EXIST; }
    }

    function GetAvailablesModulesList ( $functionnalitie )
    /**
     *  Renvoie la liste des modules disposant de la fonctionnalit� demand�.
     */
    {
        $modules = Array (  );
        foreach($SECURE_MODULE as $moduleName)
        {
            $module = $this->GetModule($moduleName);
            if ( array_key_exists($module->functionnalities, $functionnalitie) )
            { array_push( $modules, $module ); }
        }
        return $modules;
    }

    function GetModule($moduleName)
    /**
     *  Instancie et renvoie le module demand�.
     */
    {
        if ( !in_array(array_keys($loadedModules), $moduleName) )
        {
            if ( in_array($this->availablesModules, $moduleName) )
            {
                if ( $groups->check_auth($SECURE_MODULE[$moduleName]) )
                {
                    if (@include_once('../'.$moduleName.'/'.$moduleName.'.class.php'))
                    {
                        $this->loadedModules[$moduleName] = new ucfirst($moduleName)();
                    }
                    else { return MODULE_NOT_IMPLEMENTED; }
                }
                else { return ACCES_DENIED; }
            }
            else { return MODULE_NOT_AVAILABLE; }
        }
        return $this->loadedModules[$moduleName];
    }

    //---------------------------------------------------------- Constructeurs
    function Modules (  )
    /**
     *  Constructeur de la classe Modules
     */
    {
        global $groups;
        
        $this->loadedModules = Array(  );
        $this->functionnalities = explode(',',LIST_FUNCTIONNALITIES);
        $cache->load_file('modules');
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
    function checkModuleFunctionnalitie ( $functionnalitie, $module )
    /**
     *  V�rifie que le module impl�mente bien la fonctionnalit� demand�.
     */
    {
        if ( array_key_exists($module, $functionnalitie) )
        { return true; }
        else
        { return false; }
    }

    //----------------------------------------------------- Attributs prot�g�s
    var $functionnalities;
    var $loadedModules;
    var $availablesModules;
}

?>