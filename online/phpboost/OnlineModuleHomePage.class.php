<?php
/*##################################################
 *                           OnlineModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class OnlineModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	private $config;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->init();
		
		$this->check_authorizations();
		$active_sessions_start_time = time() - SessionsConfig::load()->get_active_session_duration();
		$number_users_online = OnlineService::get_number_users_connected('WHERE level <> -1 AND session_time > :time', array('time' => $active_sessions_start_time));
		$pagination = $this->get_pagination($number_users_online);
		
		$users = OnlineService::get_online_users('WHERE s.session_time > :time
		ORDER BY '. $this->config->get_display_order_request() .'
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from(),
				'time' => $active_sessions_start_time
			)
		);
		
		foreach ($users as $user)
		{
			if ($user->get_id() == AppContext::get_current_user()->get_id())
			{
				$user->set_location_script(OnlineUrlBuilder::home()->absolute());
				$user->set_location_title($this->lang['online']);
				$user->set_last_update(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, time()));
			}
			
			$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			
			if ($user->get_level() != User::VISITOR_LEVEL) 
			{
				$this->view->assign_block_vars('users', array(
					'U_PROFILE' => UserUrlBuilder::profile($user->get_id())->absolute(),
					'U_LOCATION' => $user->get_location_script(),
					'U_AVATAR' => $user->get_avatar(),
					'PSEUDO' => $user->get_pseudo(),
					'LEVEL' => UserService::get_level_lang($user->get_level()),
					'LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
					'C_GROUP_COLOR' => !empty($group_color),
					'GROUP_COLOR' => $group_color,
					'TITLE_LOCATION' => $user->get_location_title(),
					'LAST_UPDATE' => $user->get_last_update()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)
				));
			}
		}
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_USERS' => $number_users_online,
			'PAGINATION' => $pagination->display()
		));
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('online_common', 'online');
		$this->view = new FileTemplate('online/OnlineHomeController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = OnlineConfig::load();
	}
	
	private function check_authorizations()
	{
		if (!OnlineAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($number_users_online)
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $number_users_online, (int)$this->config->get_number_members_per_page());
		$pagination->set_url(OnlineUrlBuilder::home('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>
