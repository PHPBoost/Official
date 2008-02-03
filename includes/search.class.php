<?php

/*##################################################
 *                              search.class.php
 *                            -------------------
 *   begin                : February 1, 2008
 *   copyright            : (C) 2008 Rouchon Loic
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

define('NB_LINES', 10);
define('CACHE_TIME', 30);
define('CONNEXION_NOT_OPEN', 1);
define('SEARCH_DEPRECATED', 2);
define('MODULE_DOES_NOT_EXISTS', 4);

class Search
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- M�thodes publiques
    function InsertResults ( &$results = Array ( ) )
    /**
     *  Enregistre les r�sultats de la recherche dans la base des r�sultats
     *  si ils n'y sont pas d�j�
     */
    {
        // Nettoyage des erreurs
        $this->resetError(SEARCH_DEPRECATED);
        
        $nbUpdates = 0;
        
        $reqInsert = ''
        $reqUpdate  = "UPDATE ".PREFIX."search_index SET last_search_use='".$time()."' WHERE ( ";
        
        // V�rification de la pr�sence des r�sultats dans le cache
        foreach ( $results as $id_module => $results_lines )
        {
            if ( !in_array ( $id_module, array_keys ( $this->id_search ) ) )
            {   // les r�sultats ne sont pas dans le cache.
                // Ajout des r�sultats dans le cache
                $reqAjoutChamp .= "INSERT INTO ".PREFIX."search_index ";
                $reqAjoutChamp .= "VALUES ('','".$id_module."','".$this->search."','".$this->modules[$id_module]."','".time()."'); ";
                $reqAjoutChamp .= "SELECT id_search FROM ".PREFIX."search_index WHERE id_module='".$id_module."' ";
                $reqAjoutChamp .= "AND search='".$this->search."' AND options='".$this->modules[$id_module]."';";
                
                // R�cup�ration de l'identifiant des nouveaux r�sultats
                $id_search = $this-sql->query($reqAjoutChamp, __LINE__, __FILE__);
                $this->id_search[$id_module] = $id_search;
                
                // Ajout des r�sultats qui ne sont pas dans le cache
                foreach ( $results_lines as $result )
                {
                    $reqInsert .= "INSERT INTO ".PREFIX."search_results ";
                    $reqInsert .= "VALUES ('".$this->id_search[$id_module]."','".$id_module."','".$result['id_content']."',";
                    $reqInsert .= "'".$result['pertinence']."','".$result['id_content']."');";
                }
            }
            else
            {   // Les r�sultats sont dans le cache.
                // Mise � jour du timestamp de derni�re utilisation
                if ( $nbUpdates > 0 )
                { $reqUpdate .= " OR "; }
                $reqUpdate .= " id_search='".$id_search."'";
                $nbUpdates++;
            }
        }
        $reqUpdate .= " ); ";
        
        // Ex�cution des requ�tes de MAJ et d'insertions
        if ( $nbUpdates == 0 )
        { $sql->query_inject($reqUpdate.$reqInsert, __LINE__, __FILE__); }
        else
        { $sql->query_inject($reqInsert, __LINE__, __FILE__); }
    }
    
    function GetResults ( $id_modules = Array ( ) , $offset = 0, $nbLines = NB_LINES)
    /**
     *  Renvoie les r�sultats de la recherche
     */
    {
        $this->resetError( SEARCH_DEPRECATED );
        
        $results = Array ( );
        $numModules = 0;
        $modulesConditions = ''
        
        // Construction des conditions de recherche
        foreach ( $id_modules as $id_module )
        {
            // Teste l'existence de la recherche dans la base sinon signale l'erreur
            if ( in_array ( $id_module, array_keys ( $this->search ) )
            {
                // Conditions de la recherche
                if ( $numModules > 0 )
                { $modulesConditions .= " OR"; }
                $modulesConditions .= " ( id_search='".$this->id_search[$id_module]."' ";
                $modulesConditions .= " AND id_module='".$id_module."' ) "
                $numModules++;
            }
            else
            { $this->setError( SEARCH_DEPRECATED ); }
        }
        
        // R�cup�ration du nombre de r�sultats correspondant � la recherche
        $reqNbResults  = "SELECT COUNT(*) ".PREFIX."search_results".$modulesConditions.
        $nbResults = $sql->query( $reqNbResults, __LINE__, __FILE__ );
        
        // R�cup�ration des $nbLines r�sultats � partir de l'$offset
        $reqResults  = "SELECT id_module, id_content, pertinence, link FROM ".PREFIX."search_results WHERE ";
        $reqResults .= $modulesConditions." ".$this->sql->sql_limit($offset, $nbLines);
        
        // Ex�cution de la requ�te
        $request = $this->sql->query_while( $reqResults, __LINE__, __FILE__ );
        while( $result = $this->sql->sql_fetch_assoc($request) )
        {   // Ajout des r�sultats
            array_push($results, $result)
        }
        //On lib�re la m�moire
        $this->sql->close($request);
        
        return Array ( $nbResults, $results );
    }
    
    function ModulesInCache ( )
    /**
     *  Renvoie la liste des modules pr�sent dans le cache
     */
    {
        return array_keys ( $this->id_search );
    }
    
    function IsInCache ( $id_module )
    /**
     *  Renvoie true si les r�sultats du module sont dans le cache
     */
    {
        return in_array ( $id_module, array_keys ( $this->id_search ) );
    }
    
    function GetErrors (  )
    /**
     *  Renvoie un integer contenant des bits d'erreurs.
     */
    {
        return $this->errors;
    }
    
    //---------------------------------------------------------- Constructeurs
    
    function Search ( &$sql, $search = '', $modules = Array ( ) )
    /**
     *  Constructeur de la classe Search
     */
    {
        $this->errors = 0;
        $this->search = $search;
        $this->modules = $modules;
        $this->id_search = Array ( );
        
        $this->sql = $sql;
        $this->modulesConditions = $this->getModulesConditions ( &$modules );
        
        $this->cleanOldResults ( );
        
        $ids_search = Array ( );
        
        // V�rifications des r�sultats dans le cache.
        $reqCache  = "SELECT id_search, id_module FROM".PREFIX."search_index ";
        $reqCache .= "WHERE search='".$search."' AND ".$this->modulesConditions;
        
        $request = $this->sql->query_while( $reqCache, __LINE__, __FILE__ );
        while( $row = $this->sql->sql_fetch_assoc($request) )
        {   // Ajout des r�sultats s'il fait partie de la liste des modules � traiter
            if ( in_array ( $row[1], array_keys ( $this->modules ) ) )
            { $this->id_search[$row[1]] = $row[0]; }
        }
        $this->sql->close($request);
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
    function getModulesConditions ( &$modules, $withOptions = true )
    /**
     *  G�n�re les conditions de la clause WHERE pour limiter les requ�tes
     *  aux seuls modules avec les bonnes options de recherches concern�s.
     */
    {
        $nbModules = count( $modules );
        
        $modulesConditions = '';
        
        if ( $nbModules > 0 )
        {
            $modulesConditions .= " ( ";
            $i = 0;
            foreach ( $modules as $id_module => $options )
            {
                $modulesConditions .= "( id_module='".$id_module."'";
                if ( $withOptions )
                { $modulesConditions .= " AND options='".$options."'"; }
                $modulesConditions .= " )";
                
                if ( $i < ( $nbModules - 1 ) )
                { $modulesConditions .= " OR " ; }
                else
                { $modulesConditions .= " ) "; }
                $i++;
            }
        }
        
        return $modulesConditions;
    }
    
    function cleanOldResults (  )
    /**
     *  Nettoyage de la bases des recherche
     */
    {
        $reqUpdate  = "DELETE FROM ".PREFIX."search_index WHERE ";
        $reqUpdate .= "last_search_use < '".($time() - (CACHE_TIME * 60))."'";
        $sql->query_inject($reqUpdate, __LINE__, __FILE__);
    }
    
    function setError ( $error = 0 )
    /**
     *  Ajoute l'erreur rencontr� aux erreurs d�j� pr�sentes.
     */
    {
        $this->errors |= $error;
    }
    
    function resetError ( $error )
    /**
     *  Nettoie le bit d'erreur de l'erreur correspondante
     */
    {
        $this->errors = $this->errors &~  $error;
    }
    
    //----------------------------------------------------- Attributs prot�g�s
    var $id_search;
    var $search;
    var $modules;
    var $modulesConditions;
    var $sql;
    var $errors;
}

?>