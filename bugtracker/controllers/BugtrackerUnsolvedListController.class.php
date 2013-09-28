<?php
/*##################################################
 *                      BugtrackerUnsolvedListController.class.php
 *                            -------------------
 *   begin                : November 12, 2012
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

class BugtrackerUnsolvedListController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}
	
	private function build_view($request)
	{
		//Configuration load
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$versions = $config->get_versions_detected();
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		
		$field = $request->get_value('field', 'date');
		$sort = $request->get_value('sort', 'desc');
		$current_page = $request->get_getint('page', 1);
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$main_lang = LangLoader::get('main');
		
		if (!empty($filter) && empty($filter_id))
		{
			$filter = $filter_id = '';
		}
		
		$filters = !empty($filter) ? explode('-', $filter) : array();
		$filters_ids = !empty($filter_id) ? explode('-', $filter_id) : array();
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'id' :
				$field_bdd = 'id';
				break;
			case 'title' :
				$field_bdd = 'title';
				break;
			case 'status' :
				$field_bdd = 'status';
				break;
			default :
				$field_bdd = 'submit_date';
				break;
		}
		
		$select_filters = '';
		foreach ($filters as $key => $f)
		{
			$select_filters .= in_array($f, array('type', 'category', 'severity', 'status', 'detected_in')) && $filters_ids[$key] ? "AND " . $f . " = '" . $filters_ids[$key] . "'" : '';
		}
		
		$stats_cache = BugtrackerStatsCache::load();
		$bugs_number = !empty($select_filters) ? BugtrackerService::count("WHERE status <> 'fixed' AND status <> 'rejected'" . $select_filters) : $stats_cache->get_bugs_number(Bug::NEW_BUG) + $stats_cache->get_bugs_number(Bug::ASSIGNED) + $stats_cache->get_bugs_number(Bug::IN_PROGRESS) + $stats_cache->get_bugs_number(Bug::REOPEN);
		
		$pagination = $this->get_pagination($bugs_number, $current_page, $field, $sort, $filter, $filter_id);
		
		$result = PersistenceContext::get_querier()->select("SELECT b.*, com.number_comments, member.*
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = b.id AND com.module_id = 'bugtracker'
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = b.author_id AND member.user_aprob = 1
		WHERE status <> 'fixed' AND status <> 'rejected'" .
		$select_filters . "
		ORDER BY " . $field_bdd . " " . $mode . "
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		$displayed_severities = array();
		
		while ($row = $result->fetch())
		{
			//Author
			$author = new User();
			$author->set_properties($row);
			$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
			
			if (!in_array($row['severity'], $displayed_severities)) $displayed_severities[] = $row['severity'];
			
			$this->view->assign_block_vars('bug', array(
				'C_AUTHOR_GROUP_COLOR'		=> !empty($author_group_color),
				'C_LINE_COLOR'				=> !empty($row['severity']) && isset($severities[$row['severity']]),
				'C_PROGRESS'				=> $config->is_progress_bar_displayed() && $row['progress'],
				'ID'						=> $row['id'],
				'TITLE'						=> ($config->is_cat_in_title_displayed() && $display_categories) ? '[' . $categories[$row['category']] . '] ' . $row['title'] : $row['title'],
				'LINE_COLOR' 				=> stripslashes($severities[$row['severity']]['color']),
				'PROGRESS' 					=> $row['progress'],
				'STATUS'					=> $this->lang['bugs.labels.fields.status'] . ' : ' . $this->lang['bugs.status.' . $row['status']],
				'NUMBER_COMMENTS'			=> (int) $row['number_comments'],
				'L_COMMENTS'				=> $row['number_comments'] <= 1 ? LangLoader::get_message('comment', 'comments-common') : LangLoader::get_message('comments', 'comments-common'),
				'DATE' 						=> gmdate_format($config->get_date_form(), $row['submit_date']),
				'AUTHOR'					=> $author->get_pseudo(),
				'AUTHOR_LEVEL_CLASS'		=> UserService::get_level_class($author->get_level()),
				'AUTHOR_GROUP_COLOR'		=> $author_group_color,
				'LINK_AUTHOR_PROFILE'		=> UserUrlBuilder::profile($author->get_id())->absolute(),
				'LINK_BUG_DETAIL'			=> BugtrackerUrlBuilder::detail($row['id'] . '/' . Url::encode_rewrite($row['title']))->absolute(),
				'LINK_BUG_REOPEN_REJECT'	=> BugtrackerUrlBuilder::reject($row['id'], 'unsolved', $current_page, (!empty($filter) ? $filter : ''), (!empty($filter) ? $filter_id : ''))->absolute(),
				'LINK_BUG_EDIT'				=> BugtrackerUrlBuilder::edit($row['id'] . '/unsolved/' . $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
				'LINK_BUG_HISTORY'			=> BugtrackerUrlBuilder::history($row['id'])->absolute(),
				'LINK_BUG_DELETE'			=> BugtrackerUrlBuilder::delete($row['id'], 'unsolved', $current_page, (!empty($filter) ? $filter : ''), (!empty($filter) ? $filter_id : ''))->absolute(),
				'LINK_COMMENTS'				=> BugtrackerUrlBuilder::detail($row['id'] . '/#comments_list')->absolute()
			));
		}
		
		$this->view->put_all(array(
			'C_IS_ADMIN'				=> BugtrackerAuthorizationsService::check_authorizations()->moderation(),
			'C_COMMENTS'				=> $config->are_comments_enabled(),
			'C_UNSOLVED' 				=> true,
			'C_BUGS' 					=> $result->get_rows_count() > 0,
			'C_DISPLAY_AUTHOR'			=> true,
			'C_PAGINATION'				=> $pagination->has_several_pages(),
			'PAGINATION' 				=> $pagination->display(),
			'BUGS_COLSPAN' 				=> BugtrackerAuthorizationsService::check_authorizations()->moderation() ? 5 : 4,
			'L_UPDATE' 					=> $main_lang['update'],
			'L_DELETE' 					=> $main_lang['delete'],
			'L_ON' 						=> $main_lang['on'],
			'L_BY' 						=> $main_lang['by'],
			'L_GUEST' 					=> $main_lang['guest'],
			'L_NO_BUG' 					=> empty($filters) ? $this->lang['bugs.notice.no_bug'] : (sizeof($filters) > 1 ? $this->lang['bugs.notice.no_bug_matching_filters'] : $this->lang['bugs.notice.no_bug_matching_filter']),
			'L_DATE'					=> $this->lang['bugs.labels.detected'],
			'L_REOPEN_REJECT'			=> $this->lang['bugs.actions.reject'],
			'PICT_REOPEN_REJECT'		=> 'unvisible.png',
			'REOPEN_REJECT_CONFIRM'		=> 'reject',
			'FILTER_LIST'				=> BugtrackerViews::build_filters('unsolved', $bugs_number),
			'PROGRESS_BAR'				=> BugtrackerViews::build_progress_bar(),
			'LEGEND'					=> BugtrackerViews::build_legend($displayed_severities, 'unsolved'),
			'LINK_BUG_ID_TOP' 			=> BugtrackerUrlBuilder::unsolved('id/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_ID_BOTTOM' 		=> BugtrackerUrlBuilder::unsolved('id/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_TITLE_TOP' 		=> BugtrackerUrlBuilder::unsolved('title/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_TITLE_BOTTOM' 	=> BugtrackerUrlBuilder::unsolved('title/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_STATUS_TOP'		=> BugtrackerUrlBuilder::unsolved('status/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_STATUS_BOTTOM'	=> BugtrackerUrlBuilder::unsolved('status/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_DATE_TOP' 		=> BugtrackerUrlBuilder::unsolved('date/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'LINK_BUG_DATE_BOTTOM' 		=> BugtrackerUrlBuilder::unsolved('date/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute()
		));
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($bugs_number, $page, $field, $sort, $filter, $filter_id)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)BugtrackerConfig::load()->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::unsolved($field . '/' . $sort . '/%d' . (!empty($filter) ? '/' . $filter . '/' . $filter_id : '')));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return BugtrackerViews::build_body_view($object->view, 'unsolved');
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		
		$body_view = BugtrackerViews::build_body_view($view, 'unsolved');
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.add'], array('id' => $bug_id));
				break;
			case 'edit':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.edit'], array('id' => $bug_id));
				break;
			case 'fixed':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.fixed'], array('id' => $bug_id));
				break;
			case 'delete':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.delete'], array('id' => $bug_id));
				break;
			case 'reject':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.reject'], array('id' => $bug_id));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.unsolved'], BugtrackerUrlBuilder::unsolved());
		$response->set_page_title($this->lang['bugs.titles.unsolved']);
		
		return $response->display($body_view);
	}
}
?>
