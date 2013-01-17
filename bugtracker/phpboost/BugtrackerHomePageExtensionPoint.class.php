<?php
/*##################################################
 *                              BugtrackerHomePageExtensionPoint.class.php
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

class BugtrackerHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $BUGTRACKER_LANG;
		
		load_module_lang('bugtracker');
		
		return $BUGTRACKER_LANG['bugs.module_title'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $Errorh, $LANG, $Session;
		
		$bugtracker_config = BugtrackerConfig::load();
		
		//R�cup�ration des param�tres de configuration
		$authorizations = $bugtracker_config->get_authorizations();
		$items_per_page = $bugtracker_config->get_items_per_page();
		$comments_activated = $bugtracker_config->get_comments_activated();
		$roadmap_activated = $bugtracker_config->get_roadmap_activated();
		$cat_in_title_activated = $bugtracker_config->get_cat_in_title_activated();
		$types = $bugtracker_config->get_types();
		$categories = $bugtracker_config->get_categories();
		$versions = $bugtracker_config->get_versions();
		$severities = $bugtracker_config->get_severities();
		$rejected_bug_color = $bugtracker_config->get_rejected_bug_color();
		$fixed_bug_color = $bugtracker_config->get_fixed_bug_color();
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		
		$nbr_versions = array_keys($versions);
		
		require_once(PATH_TO_ROOT . '/bugtracker/bugtracker_begin.php');

		$tpl = new FileTemplate('bugtracker/bugtracker.tpl');
		
		//checking authorization
		if (!$User->check_auth($authorizations, BugtrackerConfig::BUG_READ_AUTH_BIT))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		//Nombre de bugs
		$nbr_bugs = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "bugtracker WHERE status <> 'fixed' AND status <> 'rejected'", __LINE__, __FILE__);
		
		$Pagination = new DeprecatedPagination();
		
		$get_sort = retrieve(GET, 'sort', '');
		switch ($get_sort)
		{
			case 'id' :
				$sort = 'id';
				break;
			case 'title' :
				$sort = 'title';
				break;
			case 'type' :
				$sort = 'type';
				break;
			case 'severity' :
				$sort = 'severity';
				break;
			case 'status' :
				$sort = 'status';
				break;
			case 'comments' :
				$sort = 'number_comments';
				break;
			case 'date' :
				$sort = 'submit_date';
				break;
			default :
				$sort = 'submit_date';
		}
		
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		
		if ($User->check_auth($authorizations, BugtrackerConfig::BUG_CREATE_AUTH_BIT))
		{
			$tpl->put_all(array(
				'C_ADD' 	=> true,
				'L_ADD' 	=> $LANG['bugs.actions.add']
			));
		}
		
		$no_bugs_colspan = 4;
		//Activation de la colonne "Actions" si administrateur
		if ($User->check_auth($authorizations, BugtrackerConfig::BUG_MODERATE_AUTH_BIT))
		{
			$tpl->put_all(array(
				'C_IS_ADMIN'	=> true
			));
			$no_bugs_colspan = $no_bugs_colspan + 1;
		}
		
		if ($comments_activated == true) $no_bugs_colspan = $no_bugs_colspan + 1;
		if ($display_types == true) $no_bugs_colspan = $no_bugs_colspan + 1;
		if ($display_severities == true) $no_bugs_colspan = $no_bugs_colspan + 1;
		
		$tpl->put_all(array(
			'C_DISPLAY_TYPES' 		=> $display_types,
			'C_DISPLAY_SEVERITIES'	=> $display_severities,
			'C_NO_BUGS' 			=> empty($nbr_bugs) ? true : false,
			'NO_BUGS_COLSPAN' 		=> $no_bugs_colspan,
			'C_COM' 				=> ($comments_activated == true) ? true : false,
			'C_ROADMAP' 			=> ($roadmap_activated == true && !empty($nbr_versions)) ? true : false,
			'PAGINATION' 			=> $Pagination->display(PATH_TO_ROOT .'/bugtracker/bugtracker' . url('.php?p=%d' . (!empty($get_sort) ? '&amp;sort=' . $get_sort : '') . (!empty($get_mode) ? '&amp;mode=' . $get_mode : '')), $nbr_bugs, 'p', $items_per_page, 3),
			'L_CONFIRM_DEL_BUG' 	=> $LANG['bugs.actions.confirm.del_bug'],
			'L_ID' 					=> $LANG['bugs.labels.fields.id'],
			'L_TITLE'				=> $LANG['bugs.labels.fields.title'],
			'L_TYPE'				=> $LANG['bugs.labels.fields.type'],
			'L_SEVERITY'			=> $LANG['bugs.labels.fields.severity'],
			'L_STATUS'				=> $LANG['bugs.labels.fields.status'],
			'L_DATE'				=> $LANG['bugs.labels.fields.submit_date'],
			'L_COMMENTS'			=> $LANG['title_com'],
			'L_ROADMAP' 			=> $LANG['bugs.titles.roadmap'],
			'L_NO_BUG' 				=> $LANG['bugs.notice.no_bug'],
			'L_ACTIONS' 			=> $LANG['bugs.actions'],
			'L_UPDATE' 				=> $LANG['update'],
			'L_HISTORY' 			=> $LANG['bugs.actions.history'],
			'L_REJECT' 				=> $LANG['bugs.actions.reject'],
			'L_DELETE' 				=> $LANG['delete'],
			'L_UNSOLVED' 			=> $LANG['bugs.titles.unsolved_bugs'],
			'L_SOLVED' 				=> $LANG['bugs.titles.solved_bugs'],
			'L_STATS' 				=> $LANG['bugs.titles.bugs_stats'],
			'U_BUG_LIST'			=> PATH_TO_ROOT . '/bugtracker/bugtracker.php',
			'U_BUG_SOLVED'			=> PATH_TO_ROOT . '/bugtracker/bugtracker.php?solved',
			'U_BUG_ROADMAP'			=> PATH_TO_ROOT . '/bugtracker/bugtracker.php?roadmap',
			'U_BUG_STATS'			=> PATH_TO_ROOT . '/bugtracker/bugtracker.php?stats',
			'U_BUG_ADD'				=> PATH_TO_ROOT . '/bugtracker/bugtracker.php?add',
			'U_BUG_ID_TOP' 			=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=id&amp;mode=desc'),
			'U_BUG_ID_BOTTOM' 		=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=id&amp;mode=asc'),
			'U_BUG_TITLE_TOP' 		=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=title&amp;mode=desc'),
			'U_BUG_TITLE_BOTTOM' 	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=title&amp;mode=asc'),
			'U_BUG_TYPE_TOP' 		=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=type&amp;mode=desc'),
			'U_BUG_TYPE_BOTTOM' 	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=type&amp;mode=asc'),
			'U_BUG_SEVERITY_TOP' 	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=severity&amp;mode=desc'),
			'U_BUG_SEVERITY_BOTTOM'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=severity&amp;mode=asc'),
			'U_BUG_STATUS_TOP'		=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=status&amp;mode=desc'),
			'U_BUG_STATUS_BOTTOM'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=status&amp;mode=asc'),
			'U_BUG_COMMENTS_TOP' 	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=comments&amp;mode=desc'),
			'U_BUG_COMMENTS_BOTTOM'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=comments&amp;mode=asc'),
			'U_BUG_DATE_TOP' 		=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=date&amp;mode=desc'),
			'U_BUG_DATE_BOTTOM' 	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?sort=date&amp;mode=asc')
		));
		
		$tpl->assign_block_vars('list', array());
		
		$result = $this->sql_querier->query_while("SELECT b.*, com.number_comments
		FROM " . PREFIX . "bugtracker b
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = b.id AND com.module_id = 'bugtracker'
		WHERE status <> 'fixed' AND status <> 'rejected'
		ORDER BY " . $sort . " " . $mode .
		$this->sql_querier->limit($Pagination->get_first_msg($items_per_page, 'p'), $items_per_page), __LINE__, __FILE__); //Bugs enregistr�s.
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			switch ($row['status'])
			{
				case 'fixed' :
					$line_color = 'style="background-color:' . $fixed_bug_color . ';"';
					break;
				case 'rejected' :
					$line_color = 'style="background-color:' . $rejected_bug_color . ';"';
					break;
				default :
					$line_color = '';
			}
			
			//Nombre de commentaires
			$nbr_coms = $row['number_comments'];
			$tpl->assign_block_vars('list.bug', array(
				'ID'			=> $row['id'],
				'U_BUG_VIEW'	=> PATH_TO_ROOT .'/bugtracker/bugtracker' . url('.php?view&amp;id=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
				'TITLE'			=> ($cat_in_title_activated == true && $display_categories) ? '[' . $categories[$row['category']] . '] ' . $row['title'] : $row['title'],
				'TYPE'			=> (!empty($row['type']) && isset($types[$row['type']])) ? stripslashes($types[$row['type']]) : $LANG['bugs.notice.none'],
				'SEVERITY'		=> (!empty($row['severity']) && isset($severities[$row['severity']])) ? stripslashes($severities[$row['severity']]['name']) : $LANG['bugs.notice.none'],
				'STATUS'		=> $LANG['bugs.status.' . $row['status']],
				'LINE_COLOR' 	=> $line_color,
				'SEVERITY_COLOR'=> (!empty($row['severity']) && isset($severities[$row['severity']])) ? 'style="background-color:' . stripslashes($severities[$row['severity']]['color']) . ';"' : '',
				'COMMENTS'		=> '<a href="' . PATH_TO_ROOT .'/bugtracker/bugtracker' . url('.php?view&id=' . $row['id'] . '#comments_list') . '">' . (empty($nbr_coms) ? 0 : $nbr_coms) . '</a>',
				'DATE' 			=> gmdate_format($bugtracker_config->get_date_format(), $row['submit_date']),
				'U_BUG_REJECT'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?reject&amp;id=' . $row['id']),
				'U_BUG_EDIT'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?edit&amp;id=' . $row['id']),
				'U_BUG_HISTORY'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?history&amp;id=' . $row['id']),
				'U_BUG_DELETE'	=> PATH_TO_ROOT . '/bugtracker/bugtracker' . url('.php?delete&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token())
			));
		}
		$this->sql_querier->query_close($result);
		
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'edit_success':
				$errstr = $LANG['bugs.error.e_edit_success'];
				$errtyp = E_USER_SUCCESS;
				break;
			case 'delete_success':
				$errstr = $LANG['bugs.error.e_delete_success'];
				$errtyp = E_USER_SUCCESS;
				break;
			case 'reject_success':
				$errstr = $LANG['bugs.error.e_reject_success'];
				$errtyp = E_USER_SUCCESS;
				break;
			case 'reopen_success':
				$errstr = $LANG['bugs.error.e_reopen_success'];
				$errtyp = E_USER_SUCCESS;
				break;
			default:
				$errstr = '';
				$errtyp = E_USER_NOTICE;
		}
		if (!empty($errstr))
			$tpl->put('message_helper', MessageHelper::display($errstr, $errtyp));
			
		return $tpl;
	}
}
?>