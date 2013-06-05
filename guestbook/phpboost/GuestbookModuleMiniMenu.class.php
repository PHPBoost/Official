<?php
/*##################################################
 *                          GuestbookModuleMiniMenu.class.php
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

class GuestbookModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function display($tpl = false)
    {
    	global $LANG;
    	
	    if (!Url::is_current_url('/guestbook/guestbook.php'))
	    {
	    	load_module_lang('guestbook');
			$guestbook_cache = GuestbookMessagesCache::load();
			$guestbook_msgs_cache = $guestbook_cache->get_messages();
	    	$tpl = new FileTemplate('guestbook/guestbook_mini.tpl');
	        MenuService::assign_positions_conditions($tpl, $this->get_block());
	
			$rand = array_rand($guestbook_msgs_cache);
	    	$guestbook_rand = isset($guestbook_msgs_cache[$rand]) ? $guestbook_cache->get_message($rand) : null;
	
			if ($guestbook_rand === null)
			{
				$tpl->put_all(array(
		    		'C_ANY_MESSAGE_GESTBOOK' => false,
					'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
					'L_NO_MESSAGE_GESTBOOK' => $LANG['no_message_guestbook']
		    	));
			}
			else
			{
		    	//Pseudo.
		    	if ($guestbook_rand['user_id'] != -1)
		    	{
					$group_color = User::get_group_color($guestbook_rand['user_groups'], $guestbook_rand['level']);
					$guestbook_login = '<a class="small_link '.UserService::get_level_class($guestbook_rand['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="' . UserUrlBuilder::profile($guestbook_rand['user_id'])->absolute() . '" title="' . $guestbook_rand['login'] . '"><span style="font-weight:bold;">' . TextHelper::wordwrap_html($guestbook_rand['login'], 13) . '</span></a>';
				}
		    	else
		    		$guestbook_login = '<span style="font-style:italic;">' . (!empty($guestbook_rand['login']) ? TextHelper::wordwrap_html($guestbook_rand['login'], 13) : $LANG['guest']) . '</span>';
	
		    	$tpl->put_all(array(
					'C_ANY_MESSAGE_GESTBOOK' => true,
					'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
		    		'RAND_MSG_ID' => $guestbook_rand['id'],
		    		'RAND_MSG_CONTENTS' => (strlen($guestbook_rand['contents']) > 149) ? ucfirst($guestbook_rand['contents']) . ' <a href="' . TPL_PATH_TO_ROOT . '/guestbook/guestbook.php#m'.$guestbook_rand['id'].'" class="small_link">' . $LANG['guestbook_more_contents'] . '</a>' : ucfirst($guestbook_rand['contents']),
		    		'RAND_MSG_LOGIN' => $guestbook_login,
		    		'L_BY' => $LANG['by']
		    	));
			}
			return $tpl->render();
	    }
	    return '';
    }
}
?>