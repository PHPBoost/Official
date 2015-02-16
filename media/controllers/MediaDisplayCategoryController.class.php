<?php
/*##################################################
 *                         MediaDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : February 4, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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

class MediaDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function build_view()
	{
		global $LANG, $MEDIA_LANG;
		
		require_once('media_constant.php');
		load_module_lang('media');
		$config = MediaConfig::load();
		$category = $this->get_category();
		$authorized_categories = MediaService::get_authorized_categories($category->get_id());
		
		//Children categories
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= media_cats.id, media_cats.*,
		(SELECT COUNT(*) FROM '. MediaSetup::$media_table .' media
		WHERE media.idcat = @id_cat
		AND infos = 2
		) AS mediafiles_number
		FROM ' . MediaSetup::$media_cats_table .' media_cats
		WHERE media_cats.id_parent = :id_category
		AND media_cats.id IN :authorized_categories
		ORDER BY media_cats.id_parent, media_cats.c_order', array(
			'id_category' => $category->get_id(),
			'authorized_categories' => $authorized_categories
		));
		
		$nbr_cat_displayed = 0;
		while ($row = $result->fetch())
		{
			$nbr_cat_displayed++;
			$category_image = new Url($row['image']);
			
			if ($nbr_cat_displayed % $config->get_columns_number_per_line() == 1)
			{
				$this->tpl->assign_block_vars('row', array());
			}

			$this->tpl->assign_block_vars('row.list_cats', array(
				'ID' => $row['id'],
				'NAME' => $row['name'],
				'WIDTH' => floor(100 / (float)$config->get_columns_number_per_line()),
				'SRC' => $category_image->rel(),
				'NUM_MEDIA' => sprintf(($row['mediafiles_number'] > 1 ? $MEDIA_LANG['num_medias'] : $MEDIA_LANG['num_media']), $row['mediafiles_number']),
				'U_CAT' => PATH_TO_ROOT . url('/media/media.php?cat=' . $row['id'], '/media/media-0-' . $row['id'] . '+' . $row['rewrited_name'] . '.php'),
				'U_ADMIN_CAT' => PATH_TO_ROOT . url('/media/admin_media_cats.php?edit=' . $row['id'])
			));
		}
		$result->dispose();
		
		$this->tpl->put_all(array(
			'C_CATEGORIES' => true,
			'C_SUB_CATS' => $nbr_cat_displayed,
			'C_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'C_MODO' => MediaAuthorizationsService::check_authorizations($category->get_id())->moderation(),
			'C_DESCRIPTION' => $category->get_description(),
			'L_MODO_PANEL' => $LANG['modo_panel'],
			'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
			'L_BY' => $MEDIA_LANG['media_added_by'],
			'TITLE' => $category->get_id() == Category::ROOT_CATEGORY ? LangLoader::get_message('module_title', 'common', 'media'): $category->get_name(),
			'U_ADMIN_CAT' => PATH_TO_ROOT . '/media/admin_media_cats.php?edit=' . $category->get_id(),
			'DESCRIPTION' => FormatingHelper::second_parse($category->get_description()),
			'ID_CAT' => $category->get_id()
		));
	
		//Contenu de la cat�gorie
		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
		$selected_fields = array('alpha' => '', 'date' => '', 'nbr' => '', 'note' => '', 'com' => '', 'asc' => '', 'desc' => '');

		switch ($get_sort)
		{
			case 'alpha':
				$sort = 'name';
				$selected_fields['alpha'] = ' selected="selected"';
				break;
			default:
			case 'date':
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
				break;
			case 'nbr':
				$sort = 'counter';
				$selected_fields['nbr'] = ' selected="selected"';
				break;
			case 'note':
				$sort = 'average_notes';
				$selected_fields['note'] = ' selected="selected"';
				break;
			case 'com':
				$sort = 'com.number_comments';
				$selected_fields['com'] = ' selected="selected"';
				break;
		}

		if ($mode == 'ASC')
		{
			$selected_fields['asc'] = ' selected="selected"';
		}
		else
		{
			$selected_fields['desc'] = ' selected="selected"';
		}

		$this->tpl->put_all(array(
			'C_DISPLAY_NOTATION' => $config->is_notation_enabled(),
			'C_DISPLAY_COMMENTS' => $config->are_comments_enabled(),
			'L_ALPHA' => $MEDIA_LANG['sort_title'],
			'L_DATE' => LangLoader::get_message('date', 'date-common'),
			'L_NBR' => $MEDIA_LANG['sort_popularity'],
			'L_NOTE' => LangLoader::get_message('note', 'common'),
			'L_COM' => $LANG['com'],
			'L_DESC' => $LANG['desc'],
			'L_ASC' => $LANG['asc'],
			'L_ORDER_BY' => LangLoader::get_message('sort_by', 'common'),
			'SELECTED_ALPHA' => $selected_fields['alpha'],
			'SELECTED_DATE' => $selected_fields['date'],
			'SELECTED_NBR' => $selected_fields['nbr'],
			'SELECTED_NOTE' => $selected_fields['note'],
			'SELECTED_COM' => $selected_fields['com'],
			'SELECTED_ASC' => $selected_fields['asc'],
			'SELECTED_DESC' => $selected_fields['desc']
		));
		
		$condition = 'WHERE idcat = :idcat AND infos = :infos';
		$parameters = array(
			'idcat' => $category->get_id(),
			'infos' => MEDIA_STATUS_APROBED
		);
		
		//On cr�e une pagination si le nombre de fichiers est trop important.
		$page = AppContext::get_request()->get_getint('p', 1);
		$mediafiles_number = MediaService::count($condition, $parameters);
		$pagination = new ModulePagination($page, $mediafiles_number, $config->get_items_number_per_page());
		$pagination->set_url(new Url('/media/media.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $category->get_id() . '&amp;p=%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$notation = new Notation();
		$notation->set_module_name('media');
		$notation->set_notation_scale($config->get_notation_scale());
		
		$result = PersistenceContext::get_querier()->select("SELECT v.id, v.iduser, v.name, v.timestamp, v.counter, v.infos, v.contents, mb.display_name, mb.groups, mb.level, notes.average_notes, com.number_comments
			FROM " . PREFIX . "media AS v
			LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id
			LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON v.id = notes.id_in_module AND notes.module_name = 'media'
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON v.id = com.id_in_module AND com.module_id = 'media'
			" . $condition . "
			ORDER BY " . $sort . " " . $mode . "
			LIMIT :number_items_per_page OFFSET :display_from", array_merge($parameters, array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		)));
		
		$this->tpl->put_all(array(
			'C_FILES' => $result->get_rows_count() > 0,
			'C_DISPLAY_NO_FILE_MSG' => $result->get_rows_count() == 0 && $category->get_id() != Category::ROOT_CATEGORY,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'TARGET_ON_CHANGE_ORDER' => ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? 'media-0-' . $category->get_id() . '.php?' : 'media.php?cat=' . $category->get_id() . '&'
		));
		
		while ($row = $result->fetch())
		{
			$notation->set_id_in_module($row['id']);
			
			$group_color = User::get_group_color($row['groups'], $row['level']);
			
			$this->tpl->assign_block_vars('file', array(
				'NAME' => $row['name'],
				'IMG_NAME' => str_replace('"', '\"', $row['name']),
				'C_DESCRIPTION' => !empty($row['contents']),
				'DESCRIPTION' => FormatingHelper::second_parse($row['contents']),
				'POSTER' => $MEDIA_LANG['media_added_by'] . ' : ' . !empty($row['display_name']) ? '<a href="' . UserUrlBuilder::profile($row['iduser'])->rel() . '" class="'.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a>' : $LANG['guest'],
				'DATE' => sprintf($MEDIA_LANG['add_on_date'], Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR)),
				'COUNT' => sprintf($MEDIA_LANG['view_n_times'], $row['counter']),
				'NOTE' => NotationService::display_static_image($notation),
				'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $category->get_id() . '+' . Url::encode_rewrite($row['name']) . '.php'),
				'U_ADMIN_UNVISIBLE_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?unvisible=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_ADMIN_EDIT_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_COM_LINK' => '<a href="'. PATH_TO_ROOT .'/media/media' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['id'] . '-' . $category->get_id() . '+' . Url::encode_rewrite($row['name']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('media', $row['id']) . '</a>'
			));
		}
		$result->dispose();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'media');
		$this->tpl = new FileTemplate('media/media.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('cat', 0);
			if (!empty($id))
			{
				try {
					$this->category = MediaService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = MediaService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!MediaAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(MediaUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], MediaUrlBuilder::home());
		
		$categories = array_reverse(MediaService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), MediaUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->tpl;
	}
}
?>
