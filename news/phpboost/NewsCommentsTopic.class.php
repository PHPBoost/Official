<?php
/*##################################################
 *                           NewsCommentsTopic.class.php
 *                            -------------------
 *   begin                : April 09, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class NewsCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('news');
	}
	
	public function get_authorizations()
	{
		global $NEWS_CAT, $NEWS_CONFIG;
		
		$cache = new Cache();
		$cache->load($this->get_module_id());
		
		require_once(PATH_TO_ROOT .'/'. $this->get_module_id() . '/news_constants.php');
		
		$id_cat = $this->get_categorie_id();
		
		$cat_authorizations = $NEWS_CAT[$id_cat]['auth'];
		if (!is_array($cat_authorizations))
		{
			$cat_authorizations = $NEWS_CONFIG['global_auth'];
		}
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($cat_authorizations, AUTH_NEWS_READ));
		return $authorizations;
	}
	
	public function is_display()
	{
		$columns = 'visible';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		$aprobation = PersistenceContext::get_querier()->get_column_value(PREFIX . 'news', $columns, $condition, $parameters);
		return $aprobation > 0 ? true : false;
	}

	private function get_categorie_id()
	{
		$columns = 'idcat';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'news', $columns, $condition, $parameters);
	}
}
?>