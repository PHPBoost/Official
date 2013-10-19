<?php
/*##################################################
 *                      CalendarDeleteController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
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

/**
 * @author Julien BRISWALTER <julien.briswalter@phpboost.com>
 */
class CalendarDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$event = $this->get_event($request);
		
		$this->check_authorizations();
		
		//Delete event
		CalendarService::delete('WHERE id=:id', array('id' => $this->event->get_id()));
		CalendarService::delete_participants($this->event->get_id());
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'calendar', 'id' => $this->event->get_id()));
		
		//Delete event comments
		CommentsService::delete_comments_topic_module('calendar', $this->event->get_id());
		
		Feed::clear_cache('calendar');
		
		CalendarCurrentMonthEventsCache::invalidate();
		
		if ($request->get_getvalue('return', '') == 'admin')
			AppContext::get_response()->redirect(CalendarUrlBuilder::manage_events($request->get_getvalue('field', ''), $request->get_getvalue('sort', ''), $request->get_getint('page', 1)));
		else
			AppContext::get_response()->redirect(CalendarUrlBuilder::home($this->event->get_start_date()->get_year() . '/'. $this->event->get_start_date()->get_month()));
	}
	
	private function get_event(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id))
		{
			try {
				$this->event = CalendarService::get_event('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function check_authorizations()
	{
		if (!$this->event->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
}
?>
