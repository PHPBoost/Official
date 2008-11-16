<?php

/**
 *                      module_interface.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Lo�c Rouchon
 *   email                : horn@phpboost.com
 *
 * @author      Lo�c Rouchon
 * @copyright   ( C) 2008 Lo�c Rouchon - horn@phpboost.com
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 *###################################################
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
 *###################################################
 */

define('MODULE_NOT_AVAILABLE', 1);
define('ACCES_DENIED', 2);
define('MODULE_NOT_YET_IMPLEMENTED', 4);
define('FUNCTIONNALITY_NOT_IMPLEMENTED', 8);
define('MODULE_ATTRIBUTE_DOES_NOT_EXIST', 16);

/**
 * @author Lo�c Rouchon horn@phpboost.com
 * @desc This Class allow you to call methods on a ModuleInterface extended class
 * that you're not sure of the method's availality. It also provides a set of
 * generic methods that you could use to integrate your module with others, or
 * allow your module to share services.
 * @package modules
 * @subpackage modules-services
 */
class ModuleInterface
{
    //-------------------------------------------------------------- CONSTRUCTOR
    /**
     * @desc ModuleInterface constructor
     * @param string $moduleId the module id. It's the name of the folder in witch the module is
     * @param int $error allow you to instanciate your module with an error code
     */
    function ModuleInterface($moduleId = '', $error = 0)
    {
        /**
         * @global  CONFIG
         */
        global $CONFIG;
        $this->id = $moduleId;
        $this->name = $this->id;
        $this->attributes =array();
        $this->infos = array();
        $this->functionnalities = array();

        // Get the config.ini informations
        $this->infos = load_ini_file(PATH_TO_ROOT . '/' . $this->id . '/lang/', uget_lang());
        if ( isset($this->infos['name']) )
            $this->name = $this->infos['name'];

        if( $error == 0 )
        {
            $class = ucfirst($moduleId).'Interface';
            // Get modules methods
            $module_methods = get_class_methods($class); // PHP4 returns it in lower case
            // generics module Methods from ModuleInterface
            $generics_methods = get_class_methods('ModuleInterface'); // PHP4 returns it in lower case
            $generics_methods[] = $class;
            
            $methods_diff = array_diff($module_methods, $generics_methods);
            
            // keep only public methods from the functionnalities list
            foreach( $methods_diff as $method )
            {
                if( substr($method, 0, 1) != '_' )
                    $this->functionnalities[] = $method;
            }
        }
        $this->errors = $error;
    }

    //----------------------------------------------------------- PUBLIC METHODS
    /**
     * @return string Return the id of the module
     */
    function get_id()
    {
        return $this->id;
    }

    /**
     * @return string Return the name of the module
     */
    function get_name()
    {
        return $this->name;
    }

    /**
     * @return mixed[] All informations that you could find in the .ini file of the module,  his functionnalities and his name
     */
    function get_infos()
    {
        return array(
            'name' => $this->name,
            'infos' => $this->infos,
            'functionnalities' => $this->functionnalities,
        );
    }

    /**
     * @param $attribute the attribute identifier in the dictionary
     * @return mixed The value of the attribute identified by the string $attribute
     *  in the intern dictionary if existing. Else, the MODULE_ATTRIBUTE_DOES_NOT_EXIST flag is raised and it
     *  returns -1
     */
    function get_attribute($attribute)

    {
        $this->_clear_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        if ( isset($this->attributes[$attribute]) )
            return $this->attributes[$attribute];
        
        $this->_set_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        return -1;
    }

    /**
     * @desc Set the $value of the attribute identified by the string $attribute.
     * @param string $attribute the attribute identifier
     * @param mixed $value the value to set
     */
    function set_attribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * @desc Delete the attribute and free its memory.
     * @param string $attribute the attribute identifier
     */
    function unset_attribute($attribute)
    {
        unset($this->attributes[$attribute]);
    }

    /**
     * @desc Returns the last error. If called with no arguments, returns true if an error has occured
     *  otherwise, false. If the method got an argument,
     * @param int $error to check a specific error, 0 otherwise
     * @return returns true if the specified $error has occured otherwise, false.
     */
    function got_error($error = 0)
    {
        if ( $error == 0 )
            return $this->errors != 0;
        else
            return ($this->errors & $error) != 0;
    }

    /**
     * @return int Returns the current errors flags
     */
    function get_errors()
    {
        return $this->errors;
    }

    /**
     * @desc Check the existance of the functionnality and if exists call it.
     *  If she's not available, the FUNCTIONNALITY_NOT_IMPLEMENTED flag is raised.
     * @param string $functionnality the name of the method you want to call
     * @param mixed $args the args you want to pass to the $functionnality method
     * @return mixed the $functionnality returns or if non-existing, false
     */
    function functionnality($functionnality, $args = null)
    {
        $this->_clear_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
        if( $this->has_functionnality($functionnality) )
            return $this->$functionnality($args);
        $this->_set_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
        return false;
    }

    /**
     * @desc Check the availability of the functionnality (hook)
     * @param string $functionnality the name of the method you want to check the availability
     * @return bool true if the functionnality exists, false otherwise
     */
    function has_functionnality($functionnality)
    {
        return in_array(strtolower($functionnality), $this->functionnalities);
    }

    /**
     * @desc Check the availability of the functionnalities (hook)
     * @param string[] $functionnalities the names of the methods you want to check the availability
     * @return bool true if all functionnalities exist, false otherwise
     */
    function has_functionnalities($functionnalities)
    {
        $nbFunctionnalities = count($functionnalities);
        for ( $i = 0; $i < $nbFunctionnalities; $i++ )
            $functionnalities[$i] = strtolower($functionnalities[$i]);
        return $functionnalities === array_intersect($functionnalities, $this->functionnalities);
    }

    //------------------------------------------------------------------ PRIVATE

    //-------------------------------------------------------- PROTECTED METHODS
    /**
     * @desc Set the flag error.
     * @param int $error the error flag to raised
     */
    function _set_error($error = 0)
    {
        $this->errors |= $error;
    }

    /**
     * @desc Clear the $error error flag
     * @param int $error the error flag to clear
     */
    function _clear_error($error)
    {
        $this->errors &= (~$error);
    }

    //----------------------------------------------------- PROTECTED ATTRIBUTES
    /**
     * @access protected
     * @var string the module identifier
     */
    var $id;
    /**
     * @access protected
     * @var string the module full name
     */
    var $name;
    /**
     * @access protected
     * @var mixed module's informations contained in ini files
     */
    var $infos;
    /**
     * @access protected
     * @var string[] list of the functionnalities provided
     */
    var $functionnalities;
    /**
     * @access protected
     * @var int error flag
     */
    var $errors;
    /**
     * @access protected
     * @var mixed[string] the attributes dictionary
     */
    var $attributes;




}

?>
