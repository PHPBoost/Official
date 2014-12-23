<?php
/*##################################################
 *                          ShoutboxModuleMiniMenu.class.php
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

class ShoutboxModuleMiniMenu extends ModuleMiniMenu
{    
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function admin_display()
	{
		return '';
	}
	
	public function display($tpl = false)
	{
		//Mini Shoutbox non activ�e si sur la page archive shoutbox.
		if (!Url::is_current_url('/shoutbox/') && ShoutboxAuthorizationsService::check_authorizations()->read())
		{
			$tpl = new FileTemplate('shoutbox/ShoutboxModuleMiniMenu.tpl');
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$lang = LangLoader::get('common', 'shoutbox');
			$tpl->add_lang($lang);
			
			$config = ShoutboxConfig::load();
			$forbidden_tags = array_flip($config->get_forbidden_formatting_tags());
			
			if ($config->is_shout_bbcode_enabled())
			{
				$smileys_cache = SmileysCache::load();
				$max_smileys_number = 30; //Nombre de smileys maximim avant affichage d'un lien vers la popup.
				$smileys_per_line = 5; //Smileys par ligne.
				
				$smileys_displayed_number = 0;
				foreach ($smileys_cache->get_smileys() as $code_smile => $infos)
				{
					$smileys_displayed_number++;
					
					$tpl->assign_block_vars('smileys', array(
						'C_END_LINE' => $smileys_displayed_number % $smileys_per_line == 0,
						'URL' => TPL_PATH_TO_ROOT . '/images/smileys/' . $infos['url_smiley'],
						'CODE' => addslashes($code_smile)
					));
					
					if ($smileys_displayed_number == $max_smileys_number)
					{
						$tpl->put('C_BBCODE_SMILEY_MORE', true); //Lien vers tous les smiley!
						break;
					}
				}
				
				
			}
			
			$tpl->put_all(array(
				'C_MEMBER' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
				'C_DISPLAY_FORM' => ShoutboxAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly(),
				'C_VALIDATE_ONKEYPRESS_ENTER' => $config->is_validation_onkeypress_enter_enabled(),
				'C_DISPLAY_SHOUT_BBCODE' => ModulesManager::is_module_installed('BBCode') && $config->is_shout_bbcode_enabled(),
				'C_BOLD_DISABLED' => isset($forbidden_tags['b']),
				'C_ITALIC_DISABLED' => isset($forbidden_tags['i']),
				'C_UNDERLINE_DISABLED' => isset($forbidden_tags['u']),
				'C_STRIKE_DISABLED' => isset($forbidden_tags['s']),
				'C_AUTOMATIC_REFRESH_ENABLED' => $config->is_automatic_refresh_enabled(),
				'SHOUTBOX_PSEUDO' => AppContext::get_current_user()->get_display_name(),
				'SHOUT_REFRESH_DELAY' => $config->get_refresh_delay(),
				'L_ALERT_LINK_FLOOD' => sprintf(LangLoader::get_message('e_l_flood', 'errors'), $config->get_max_links_number_per_message()),
				'SHOUTBOX_MESSAGES' => ShoutboxAjaxRefreshMessagesController::get_view()
			));
			
			return $tpl->render();
		}
		return '';
	}
}
?>