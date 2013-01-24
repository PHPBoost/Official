<?php
/*##################################################
 *                          SearchModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class SearchModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__HEADER;
    }
    
	public function admin_display()
    {
        return '';
    }

	public function display($tpl = false)
    {
    	global $LANG;
  		load_module_lang('search');
    	
	    $search = retrieve(REQUEST, 'q', '');
	
	    $tpl = new FileTemplate('search/search_mini.tpl');
	
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	    $tpl->put_all(Array(
	        'SEARCH' => $LANG['title_search'],
	        'TEXT_SEARCHED' => !empty($search) ? stripslashes(retrieve(REQUEST, 'q', '')) : $LANG['search'] . '...',
	        'WARNING_LENGTH_STRING_SEARCH' => addslashes($LANG['warning_length_string_searched']),
	    	'L_SEARCH' => $LANG['search'],
	        'U_FORM_VALID' => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
	        'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
	        'U_ADVANCED_SEARCH' => url(TPL_PATH_TO_ROOT . '/search/search.php'),
	    ));
	
	    return $tpl->render();
    }
}
?>