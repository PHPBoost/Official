<?php
/*##################################################
 *                        ThemesSwitcherModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 K�vin MASSY
 *   email                : reidlos@phpboost.com
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

class ThemesSwitcherModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__RIGHT;
    }

	public function display($tpl = false)
    {
    	$themeswitcher_lang = LangLoader::get('themeswitcher_common', 'ThemesSwitcher');
		$user = AppContext::get_current_user();
		
		$switchtheme = !empty($_GET['switchtheme']) ? urldecode($_GET['switchtheme']) : '';
	    if (!empty($switchtheme))
	    {
	        if ($user->check_level(User::MEMBER_LEVEL))
	        {
	            AppContext::get_session()->csrf_get_protect();
	        }
	
	    	if (preg_match('`[ a-z0-9_-]{3,20}`i', $switchtheme) && strpos($switchtheme, '\'') === false)
	    	{
	    		$user->update_user_theme($switchtheme); //Mise � jour du th�me du membre.
	    		if (QUERY_STRING != '')
	    		{
					$query_string = preg_replace('`token=[^&]+`', '', QUERY_STRING);
					$query_string = preg_replace('`&switchtheme=[^&]+`', '', $query_string);
					AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
	    		}
				else
	    			AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	    	}
	    }
	
	    $tpl = new FileTemplate('ThemesSwitcher/themeswitcher.tpl');
	
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	
	    foreach (ThemeManager::get_activated_themes_map() as $id => $value)
		{
	    	if ($value->check_auth())
	    	{
				$selected = ($user->get_theme() == $id) ? ' selected="selected"' : '';
	    		$tpl->assign_block_vars('themes', array(
	    			'NAME' => $value->get_configuration()->get_name(),
	    			'IDNAME' => $id,
	    			'SELECTED' => $selected
	    		));
	    	}
	    }
	
	    $tpl->put_all(array(
	    	'DEFAULT_THEME' => UserAccountsConfig::load()->get_default_theme(),
	    	'L_SWITCH_THEME' => $themeswitcher_lang['switch_theme'],
	    	'L_DEFAULT_THEME' => $themeswitcher_lang['defaut_theme'],
	    	'L_SUBMIT' => LangLoader::get_message('submit', 'main')
	    ));
	
	    return $tpl->render();
    }
}
?>