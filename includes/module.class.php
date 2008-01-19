<?php

/*##################################################
 *                              module.class.php
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

define('NOT_YET_IMPLEMENTED',-1);

class Module
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- M�thodes publiques
    function GetInfo (  )
    /**
     *  Renvoie le nom du module, les informations trouv�e dans le config.ini
     *  du module ainsi que les fonctionnalit�s dont dispose le module.
     */
    {
        return Array(   'name' => $this->name,
                        'infos' => $this->infos,
                        'functionnalities' => $this->functionnalities,
                    );
    }
    
    function Search ( $args = null )
    /**
     *  Effectue une recherche dans ce module
     */
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function LatestAdds ( $args = null )
    /**
     *  Renvoie la liste des derniers ajouts
     */
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function LatestModifications ( $args = null )
    /**
     *  Renvoie la liste des dernieres modifications
     */
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function MadeBy ( $args = null )
    /**
     *  Renvoie la liste des actions du/des utilisateurs/groupes
     */
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    
    //---------------------------------------------------------- Constructeurs
    function Module ( $moduleName = 'empty-module'  )
    /**
     * Constructeur de la classe Module
     */
    {
        $this->name = $moduleName;
        // r�cup�ration des infos sur le module � partir du fichier module.ini
        $this->infos = parse_ini_file('../'.$this->name.'/lang/'.$CONFIG['lang'].'/config.ini');
        $this->functionnalities = parse_ini_file('../'.$this->name.'/functionnalities.ini');
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
    var $name;
    var $infos;
    var $functionnalities;
}

?>