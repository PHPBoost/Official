<?php
/*##################################################
 *                              BugtrackerExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 
define('BUGTRACKER_MAX_SEARCH_RESULTS', 50);

class BugtrackerExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;
	
    public function __construct() //Constructeur de la classe
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('bugtracker');
    }
	
	/**
	* @method Recuperation du cache
	*/
	function get_cache()
	{
		$BUGS_CONFIG = BugtrackerConfig::load();
		
		return 'global $BUGS_CONFIG;' . "\n" . '$BUGS_CONFIG = ' . var_export(is_array($BUGS_CONFIG) ? $BUGS_CONFIG : array(), true) . ';' . "\n\n";
	}
	
	public function home_page()
	{
		return new BugtrackerHomePageExtensionPoint();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('bugtracker.css');
		return $module_css_files;
	}

	public function search()
	{
		return new BugtrackerSearchable();
	}
	
	public function comments()
    {
    	return new CommentsTopics(array(
			new BugtrackerCommentsTopic()
		));
    }	
}
?>