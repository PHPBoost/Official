<?php
/*##################################################
 *                             updates.class.php
 *                            -------------------
 *   begin                : August 17 2008
 *   copyright            : (C) 2008 Lo�c Rouchon
 *   email                : loic.rouchon@phpboost.com
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

//define('PHPBOOST_OFFICIAL_REPOSITORY', '../../../tools/repository/main.xml'); // Test repository
define('PHPBOOST_OFFICIAL_REPOSITORY', 'http://www.phpboost.com/repository/main.xml');    // Official repository
define('PHP_MIN_VERSION_UPDATES', '5');

define('CHECK_KERNEL', 0X01);
define('CHECK_MODULES', 0X02);
define('CHECK_THEMES', 0X04);
define('CHECK_ALL_UPDATES', CHECK_KERNEL|CHECK_MODULES|CHECK_THEMES);

require_once(PATH_TO_ROOT . '/kernel/framework/core/application.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/core/repository.class.php');


class Updates
{    
    function Updates($checks = CHECK_ALL_UPDATES)
    {
        $this->_load_apps($checks);
        $this->_load_repositories();
        $this->_check_repositories();
    }
    
    function _load_apps($checks = CHECK_ALL_UPDATES)
    {
        if( phpversion() > PHP_MIN_VERSION_UPDATES )
        {
            global $CONFIG;
            
            if( $checks & CHECK_KERNEL )
            {   // Add the kernel to the check list
                $this->apps[] = new Application('kernel', $CONFIG['lang'], APPLICATION_TYPE__KERNEL, $CONFIG['version'], PHPBOOST_OFFICIAL_REPOSITORY);
            }
            
            if( $checks & CHECK_KERNEL )
            {
                global $MODULES;
                // Add Modules
                $kModules = array_keys($MODULES);
                foreach( $kModules as $module )
                {
                    $infos = get_ini_config(PATH_TO_ROOT . '/' . $module . '/lang/', $CONFIG['lang']);
                    if( !empty($infos['repository']) )
                        $this->apps[] = new Application($module, $CONFIG['lang'], APPLICATION_TYPE__MODULE, $infos['version'], $infos['repository']);
                }
            }
            
            if( $checks & CHECK_THEMES )
            {   
                global $THEME_CONFIG;
                // Add Themes
                $kThemes = array_keys($THEME_CONFIG);
                foreach( $kThemes as $theme )
                {
                    $infos = get_ini_config(PATH_TO_ROOT . '/templates/' . $theme . '/config/', $CONFIG['lang']);
                    if( !empty($infos['repository']) )
                        $this->apps[] = new Application($theme, $CONFIG['lang'], APPLICATION_TYPE__TEMPLATE, $infos['css_version'], $infos['repository']);
                }
            }
        }
    }
    
    function _load_repositories()
    {
        if( phpversion() > PHP_MIN_VERSION_UPDATES )
        {
            foreach( $this->apps as $app )
            {
                $rep = $app->get_repository();
                if( !empty($rep) && !isset($this->repositories[$rep]) )
                    $this->repositories[$rep] = new Repository($rep);
            }
        }
    }
    
    function _check_repositories()
    {
        if( phpversion() > PHP_MIN_VERSION_UPDATES )
        {
            foreach( $this->apps as $app )
            {
                $result = $this->repositories[$app->get_repository()]->check($app);
                if( $result !== null )
                {   // processing to the update notification
                    $this->_add_update_alert($result);
                }
            }
        }
    }
    
    function _add_update_alert(&$app)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/events/administrator_alert_service.class.php');
        $identifier = $app->get_identifier();
        // We verify that the alert is not already registered
        if( AdministratorAlertService::find_by_identifier($identifier, 'updates', 'kernel') === null )
        {
            $alert = new AdministratorAlert();
            global $LANG, $CONFIG;
            require_once(PATH_TO_ROOT . '/lang/' . $CONFIG['lang'] . '/admin.php');
            if( $app->get_type() == APPLICATION_TYPE__KERNEL )
                $alert->set_entitled(sprintf($LANG['kernel_update_available'], $app->get_version()));
            else
                $alert->set_entitled(sprintf($LANG['update_available'], $app->get_type(), $app->get_name(), $app->get_version()));
            
            $alert->set_fixing_url('admin/admin_update_detail.php?identifier=' . $identifier);
            $alert->set_priority($app->get_priority());
            $alert->set_properties(serialize($app));
            $alert->set_type('updates');
            $alert->set_identifier($identifier);
            
            //Save
            AdministratorAlertService::save_alert($alert);
        }
    }
    
    var $repositories = array();
    var $apps = array();
};

?>
