<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : October 20, 2012
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminBugtrackerConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminBugtrackerAuthorizationsController', '`^/admin/authorizations?/?$`'),
	new UrlControllerMapper('AdminBugtrackerDeleteParameterController', '`^/admin/delete/([a-z]+)/([0-9]+)?/?$`', array('parameter', 'id')),
	new UrlControllerMapper('AdminBugtrackerDeleteDefaultParameterController', '`^/admin/delete/default/([a-z]+)?/?$`', array('parameter')),
	
	//Lists
	new UrlControllerMapper('BugtrackerUnsolvedListController', '`^/unsolved/success/([a-z_-]+)?/?([0-9]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('success', 'id', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerUnsolvedListController', '`^/unsolved?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('field', 'sort', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerSolvedListController', '`^/solved/success/([a-z_-]+)?/?([0-9]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('success', 'id', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerSolvedListController', '`^/solved(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9_-]+)?/?$`', array('field', 'sort', 'page', 'filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerRoadmapListController', '`^/roadmap/success/([a-z_-]+)?/?([a-z0-9_-]+)?/?([a-z_-]+)?/?([0-9]+)?/?$`', array('success', 'version', 'status', 'page')),
	new UrlControllerMapper('BugtrackerRoadmapListController', '`^/roadmap(?:/([a-z0-9_-]+))?/?([a-z_-]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('version', 'status', 'field', 'sort', 'page')),
	
	//Stats
	new UrlControllerMapper('BugtrackerStatsListController', '`^/stats/success/([a-z_-]+)?/?([0-9]+)?/?$`', array('success', 'id')),
	new UrlControllerMapper('BugtrackerStatsListController', '`^/stats?/?([0-9]+)?/?$`', array('id')),
	
	//Bug history
	new UrlControllerMapper('BugtrackerHistoryListController', '`^/history/success/([a-z_-]+)?/?([0-9]+)?/?$`', array('success', 'id')),
	new UrlControllerMapper('BugtrackerHistoryListController', '`^/history/([0-9]+)?/?([0-9]+)?/?$`', array('id', 'page')),
	
	//Bug detail
	new UrlControllerMapper('BugtrackerDetailController', '`^/detail/success/([a-z_-]+)?/?([0-9]+)?/?$`', array('success', 'id')),
	new UrlControllerMapper('BugtrackerDetailController', '`^/detail/([0-9]+)?/?([0-9A-Za-z_-]+)?/?$`', array('id', 'msg')),
	
	//New bug
	new UrlControllerMapper('BugtrackerFormController', '`^/add?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('back_page', 'page', 'back_filter', 'filter_id')),
	
	//Bug edition
	new UrlControllerMapper('BugtrackerFormController', '`^/edit/([0-9]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('id', 'back_page', 'page', 'back_filter', 'filter_id')),
	
	//Actions
	new UrlControllerMapper('BugtrackerDeleteBugController', '`^/delete/([0-9]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('id', 'back_page', 'page', 'back_filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerChangeBugStatusController', '`^/change_status/([0-9]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('id', 'back_page', 'page', 'back_filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerAjaxCheckStatusChangedController', '`^/check_status_changed/?$`'),
	
	//Filters
	new UrlControllerMapper('BugtrackerAddFilterController', '`^/add_filter/([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('back_page', 'page', 'back_filter', 'filter_id')),
	new UrlControllerMapper('BugtrackerDeleteFilterController', '`^/delete_filter/([0-9]+)?/?([a-z]+)?/?([0-9]+)?/?([a-z_-]+)?/?([a-z0-9-]+)?/?$`', array('id', 'back_page', 'page', 'back_filter', 'filter_id')),
	
	new UrlControllerMapper('BugtrackerUnsolvedListController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
