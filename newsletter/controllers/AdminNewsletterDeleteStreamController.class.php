<?php
/*##################################################
 *                      AdminNewsletterDeleteStreamController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
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

class AdminNewsletterDeleteStreamController extends AdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		if ($this->stream_exist($id) || $id !== 0)
		{
			$condition = "WHERE id = :id";
			$parameters = array('id' => $id);
			PersistenceContext::get_querier()->delete(NewsletterSetup::$newsletter_table_streams, $condition, $parameters);

			//Delete for subscribers
			$condition = "WHERE stream_id = :stream_id";
			$parameters = array('stream_id' => $id);
			PersistenceContext::get_querier()->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
			
			//Delete archives
			$condition = "WHERE stream_id = :stream_id";
			$parameters = array('stream_id' => $id);
			PersistenceContext::get_querier()->delete(NewsletterSetup::$newsletter_table_archives, $condition, $parameters);
			
			NewsletterStreamsCache::invalidate();
			
			AppContext::get_response()->redirect(NewsletterUrlBuilder::streams());
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('admin.stream-not-existed', 'newsletter_common', 'newsletter'));
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}
	}
	
	private static function stream_exist($id)
	{
		return NewsletterStreamsCache::load()->get_existed_stream($id);
	}
}
?>