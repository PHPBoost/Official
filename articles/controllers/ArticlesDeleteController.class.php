<?php
/*##################################################
 *		       ArticlesDeleteController.class.php
 *                            -------------------
 *   begin                : March 05, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesDeleteController extends ModuleController
{	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$article = $this->get_article();
		
		if (!ArticlesAuthorizationsService::check_authorizations($article->get_id_cat())->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		ArticlesService::delete('WHERE id=:id', array('id' => $article->get_id()));
		
		ArticlesKeywordsService::delete_all_keywords_relation($article->get_id());
		
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'articles', 'id' => $article->get_id()));
		
		CommentsService::delete_comments_topic_module('articles', $article->get_id());
		
		Feed::clear_cache('articles');
		
		AppContext::get_response()->redirect(ArticlesUrlBuilder::home());
	}
	
	private function get_article(HTTPRequestCustom $request)
	{
		$id = AppContext::get_request()->get_getint('id', 0);
	
		if (!empty($id))
		{
			try {
				return ArticlesService::get_article('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>