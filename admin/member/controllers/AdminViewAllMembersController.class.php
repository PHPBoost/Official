<?php
/*##################################################
 *                      AdminViewAllMembersController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

class AdminViewAllMembersController extends AdminController
{
	private $lang;
	
	private $view;
	
	private $nbr_members_per_page = 25;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		
		return new AdminMembersDisplayResponse($this->view, LangLoader::get_message('members.members-management', 'admin-user-common'));
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('search_member', $this->lang['search_member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('member', LangLoader::get_message('display_name', 'user-common'), '', array(
			'size' => 25))
		);
		
		return $form;
	}
	
	private function build_view($request)
	{
		$admin_lang = LangLoader::get('admin');
		$user_common_lang = LangLoader::get('user-common');
		
		$field = $request->get_value('field', 'registered');
		$sort = $request->get_value('sort', 'top');
		$page = $request->get_int('page', 1);
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'registered' :
				$field_bdd = 'm.registration_date';
			break;
			case 'connect' :
				$field_bdd = 'm.last_connection_date';
			break;
			case 'level' :
				$field_bdd = 'm.level';
			break;
			case 'login' :
				$field_bdd = 'm.display_name';
			break;
			case 'approbation' :
				$field_bdd = 'ia.approved';
			break;
			default :
				$field_bdd = 'm.registration_date';
		}
		
		$pagination = $this->get_pagination($page, $field, $sort);
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'SORT_DISPLAY_NAME_TOP' => AdminMembersUrlBuilder::management('login/top/'. $page)->rel(),
			'SORT_DISPLAY_NAME_BOTTOM' => AdminMembersUrlBuilder::management('login/bottom/'. $page)->rel(),
			'SORT_LEVEL_TOP' => AdminMembersUrlBuilder::management('level/top/'. $page)->rel(),
			'SORT_LEVEL_BOTTOM' => AdminMembersUrlBuilder::management('level/bottom/'. $page)->rel(),
			'SORT_REGISTERED_TOP' => AdminMembersUrlBuilder::management('registered/top/'. $page)->rel(),
			'SORT_REGISTERED_BOTTOM' => AdminMembersUrlBuilder::management('registered/bottom/'. $page)->rel(),
			'SORT_LAST_CONNECT_TOP' => AdminMembersUrlBuilder::management('connect/top/'. $page)->rel(),
			'SORT_LAST_CONNECT_BOTTOM' => AdminMembersUrlBuilder::management('connect/bottom'. $page)->rel(),
			'SORT_APPROBATION_TOP' => AdminMembersUrlBuilder::management('approbation/top/'. $page)->rel(),
			'SORT_APPROBATION_BOTTOM' => AdminMembersUrlBuilder::management('approbation/bottom/'. $page)->rel(),
			'L_USERS_MANAGEMENT' => LangLoader::get_message('members.members-management', 'admin-user-common'),
			'L_DISPLAY_NAME' => $user_common_lang['display_name'],
			'L_MAIL' => $user_common_lang['email'],
			'L_LEVEL' => $user_common_lang['level'],
			'L_APPROBATION' => $user_common_lang['approbation'],
			'L_LAST_CONNECT' => $user_common_lang['last_connection'],
			'L_REGISTERED' => $admin_lang['registered'],
			'L_UPDATE' => $this->lang['update'],
			'L_DELETE' => LangLoader::get_message('delete', 'common'),
			'PAGINATION' => $pagination->display(),
			'FORM' => $this->build_form()->display()
		));
		
		$number_admins = UserService::count_admin_members();
		
		$result = PersistenceContext::get_querier()->select("SELECT m.user_id, m.display_name, m.email, m.registration_date, m.last_connection_date, m.level, m.groups, 
		ia.approved
		FROM " . DB_TABLE_MEMBER . " m
		LEFT JOIN " . DB_TABLE_INTERNAL_AUTHENTICATION ." ia ON ia.user_id = m.user_id
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);
			
			$this->view->assign_block_vars('member_list', array(
				'C_GROUP_COLOR' => !empty($group_color),
				'C_DELETABLE' => $row['level'] != User::ADMIN_LEVEL || ($row['level'] == User::ADMIN_LEVEL && $number_admins > 1),
				'DELETE_LINK' => AdminMembersUrlBuilder::delete($row['user_id'])->rel(),
				'EDIT_LINK' => UserUrlBuilder::edit_profile($row['user_id'])->rel(),
				'DISPLAY_NAME' => $row['display_name'],
				'LEVEL' => UserService::get_level_lang($row['level']),
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'APPROBATION' => $row['approved'] ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common'),
				'MAIL' => $row['email'],
				'LAST_CONNECT' => !empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['never'],
				'REGISTERED' => Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
			));
		}
		$result->dispose();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('main');
		$this->view = new FileTemplate('admin/member/AdminViewAllMembersController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_pagination($page, $field, $sort)
	{
		$number_members = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER);
		
		$pagination = new ModulePagination($page, $number_members, $this->nbr_members_per_page);
		$pagination->set_url(AdminMembersUrlBuilder::management($field . '/' . $sort . '/%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>
