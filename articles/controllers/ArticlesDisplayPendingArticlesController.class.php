<?php
/*##################################################
 *		    ArticlesDisplayPendingArticlesController.class.php
 *                            -------------------
 *   begin                : March 28, 2013
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
class ArticlesDisplayPendingArticlesController extends ModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_form($field, $mode)
	{
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
		
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters');
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldLabel($this->lang['articles.sort_filter_title']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function build_view($request)
	{
		$now = new Date();
		
		$mode = $request->get_getstring('sort', 'desc');
		$field = $request->get_getstring('field', 'date');
			
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';

		switch ($field)
		{
			case 'title':
				$sort_field = 'title';
				break;
			case 'view':
				$sort_field = 'number_view';
				break;
			case 'com':
				$sort_field = 'number_comments';
				break;
			case 'note':
				$sort_field = 'number_notes';
				break;
			case 'author':
				$sort_field = 'author_user_id';
				break;
			default:
				$sort_field = 'date_created';
				break;
		}
		
		$current_page = ($request->get_getint('page', 1) > 0) ? $request->get_getint('page', 1) : 1;
		$nbr_articles_per_page = ArticlesConfig::load()->get_number_articles_per_page();

		$limit_page = (($current_page - 1) * $nbr_articles_per_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, notes.number_notes, notes.average_notes, note.note 
		FROM '. ArticlesSetup::$articles_table .' articles
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = "articles"
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = "articles" AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		WHERE articles.published = 0 OR (articles.published = 2 AND (articles.publishing_start_date > :timestamp_now 
		AND articles.publishing_end_date < :timestamp_now)) ORDER BY ' . $sort_field . ' ' . $sort_mode . ' LIMIT ' . $nbr_articles_per_page .
		' OFFSET ' . $limit_page, 
			array(
				'timestamp_now' => $now->get_timestamp(),
				), SelectQueryResult::FETCH_ASSOC
		);
		
		$nbr_articles_pending = $result->get_rows_count();
		
		$pagination = $this->get_pagination($nbr_articles_pending, $sort_field, $sort_mode);
		
		$this->build_form($field, $mode);
		
		$this->view->put_all(array(
			'C_PENDING_ARTICLES' => false,
			'C_PUBLISHED_ARTICLES' => true,
			'C_ARTICLES_CAT' => false,
			'C_MOSAIC' => false,
			'L_TOTAL_PENDING_ARTICLE' => $nbr_articles_pending > 0 ? StringVars::replace_vars($this->lang['articles.nbr_articles.pending'], array('number' => $nbr_articles_pending)) : '', 
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article(Category::ROOT_CATEGORY)->absolute(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication(Category::ROOT_CATEGORY)->rel()
		));
		
		if($nbr_articles_pending > 0)
		{	
			$add_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution();
			$auth_moderation = ArticlesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY)->moderation();
			$comments_enabled = ArticlesConfig::load()->get_comments_enabled();
			
			$this->view->put_all(array(
				'C_MODERATE' => $auth_moderation,
				'C_ARTICLES_FILTERS' => true,
				'C_ADD' => $add_auth,
				'C_COMMENTS_ENABLED' => $comments_enabled,
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display()
			));
						
			while($row = $result->fetch())
			{
				$article = new Articles();
				$article->set_properties($row);
				
				$keywords = ArticlesService::get_keywords_manager()->get_keywords('articles');
				
				$keywords_list = $this->build_keywords_list($keywords);
		
				$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($article->get_id_category());
				
				$this->view->assign_block_vars('articles',  array_merge($article->get_tpl_vars()), array(
					'C_KEYWORDS' => $keywords->get_rows_count() > 0 ? true : false,
					'L_CAT_NAME' => $category->get_name(),
					'U_CATEGORY' => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->absolute(),
					'U_KEYWORDS_LIST' => $keywords_list
				));
			}
		}
		else 
		{
			$this->view->put_all(array(
				'L_NO_PENDING_ARTICLE' => $this->lang['articles.no_pending_article']
			));
		}
		$this->view->put('FORM', $this->form->display());
	}
	
	private function build_keywords_list($keywords)
	{
		$keywords_list = '';
		$nbr_keywords = $keywords->get_rows_count();
		
		while ($row = $keywords->fetch())
		{	
			$keywords_list .= '<a class="small" href="' . ArticlesUrlBuilder::display_tag($row['rewrited_name'])->absolute() . '">' . $row['name'] . '</a>';
			if ($nbr_keywords - 1 > 0)
			{
				$keywords_list .= ', ';
				$nbr_keywords--;
			}
		}
		return $keywords_list;
	}
	
	private function check_authorizations()
	{
		if(!(ArticlesAuthorizationsService::check_authorizations()->read()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function list_sort_fields()
	{
		$options = array();

		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'view');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author');

		return $options;
	}
	
	private function get_pagination($nbr_articles_pending, $sort_field, $sort_mode)
	{
		$current_page = AppContext::get_request()->get_getint('page', 1);
		
		$pagination = new ModulePagination($current_page, $nbr_articles_pending, ArticlesConfig::load()->get_number_articles_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_pending_articles($sort_field, $sort_mode, '/%d'));
		
		if ($pagination->current_page_is_empty() && $current_page > 1)
	        {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
	        }
	
		return $pagination;
	}
	
	private function generate_response()
	{
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($this->lang['articles.pending_articles']);
		$response->set_page_description($this->lang['articles.seo.description.pending']);
		
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['articles.pending_articles'], ArticlesUrlBuilder::display_pending_articles());
	
		return $response->display($this->view);
	}
}
?>