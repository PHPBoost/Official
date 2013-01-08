<?php
/*##################################################
 *                           MediaCommentsTopic.class.php
 *                            -------------------
 *   begin                : September 23, 2011
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

class MediaCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('media');
	}
	
	public function get_authorizations()
	{
		global $MEDIA_CATS, $CONFIG_MEDIA;
		
		$cache = new Cache();
		$cache->load($this->get_module_id());
		
		require_once(PATH_TO_ROOT .'/'. $this->get_module_id() . '/media_constant.php');
		
		$id_cat = $this->get_categorie_id();
		
		$cat_authorizations = $MEDIA_CATS[$id_cat]['auth'];
		if (!is_array($cat_authorizations))
		{
			$cat_authorizations = $CONFIG_MEDIA['root']['auth'];
		}
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($cat_authorizations, MEDIA_AUTH_READ));
		return $authorizations;
	}
	
	public function is_display()
	{
		return true;
	}

	private function get_categorie_id()
	{
		$columns = 'idcat';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'media', $columns, $condition, $parameters);
	}
}
?>