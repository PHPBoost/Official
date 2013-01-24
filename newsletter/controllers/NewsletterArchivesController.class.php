<?php
/*##################################################
 *                      NewsletterArchivesController.class.php
 *                            -------------------
 *   begin                : March 21, 2011
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

class NewsletterArchivesController extends ModuleController
{
	private $lang;
	private $view;
	private $id_stream;
	
	private $nbr_archives_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->id_stream = $request->get_int('id_stream', 0);
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$current_page = $request->get_int('page', 1);
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		if (!NewsletterAuthorizationsService::id_stream($this->id_stream)->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}
		
		if (!NewsletterStreamsCache::load()->get_existed_stream($this->id_stream))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('admin.stream-not-existed', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
		
		switch ($field)
		{
			case 'stream' :
				$field_bdd = 'stream_id';
			break;
			case 'subject' :
				$field_bdd = 'subject';
			break;
			case 'date' :
				$field_bdd = 'timestamp';
			break;
			case 'subscribers' :
				$field_bdd = 'nbr_subscribers';
			break;
			default :
				$field_bdd = 'timestamp';
		}
		
		$stream_condition = empty($this->id_stream) ? "" : "WHERE stream_id = '". $this->id_stream ."'";
		$nbr_archives = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, $stream_condition);
		$nbr_pages =  ceil($nbr_archives / $this->nbr_archives_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(NewsletterUrlBuilder::archives($this->id_stream .'/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'C_ARCHIVES' => (float)$nbr_archives,
			'C_SPECIFIC_STREAM' => !empty($this->id_stream),
			'NUMBER_COLUMN' => empty($this->id_stream) && !empty($nbr_archives) ? 4 : 3,
			'SORT_STREAM_TOP' => NewsletterUrlBuilder::archives($this->id_stream .'/stream/top/'. $current_page)->absolute(),
			'SORT_STREAM_BOTTOM' => NewsletterUrlBuilder::archives($this->id_stream .'/stream/bottom/'. $current_page)->absolute(),
			'SORT_SUBJECT_TOP' => NewsletterUrlBuilder::archives($this->id_stream .'/subject/top/'. $current_page)->absolute(),
			'SORT_SUBJECT_BOTTOM' => NewsletterUrlBuilder::archives($this->id_stream .'/subject/bottom/'. $current_page)->absolute(),
			'SORT_DATE_TOP' => NewsletterUrlBuilder::archives($this->id_stream .'/date/top/'. $current_page)->absolute(),
			'SORT_DATE_BOTTOM' => NewsletterUrlBuilder::archives($this->id_stream .'/date/bottom/'. $current_page)->absolute(),
			'SORT_SUBSCRIBERS_TOP' => NewsletterUrlBuilder::archives($this->id_stream .'/subscribers/top/'. $current_page)->absolute(),
			'SORT_SUBSCRIBERS_BOTTOM' => NewsletterUrlBuilder::archives($this->id_stream .'/subscribers/bottom/'. $current_page)->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_archives_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . NewsletterSetup::$newsletter_table_archives . "
		". $stream_condition ."
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_archives_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$stream_cache = NewsletterStreamsCache::load()->get_stream($row['stream_id']);
			$this->view->assign_block_vars('archives_list', array(
				'STREAM_NAME' => $stream_cache['name'],
				'VIEW_STREAM' => NewsletterUrlBuilder::archives($stream_cache['id'])->absolute(),
				'VIEW_ARCHIVE' => NewsletterUrlBuilder::archive($row['id'])->absolute(),
				'SUBJECT' => $row['subject'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'NBR_SUBSCRIBERS' => $row['nbr_subscribers'],
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterArchivesController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->absolute());
		$breadcrumb->add($this->lang['archives.list'], NewsletterUrlBuilder::archives()->absolute());
		
		if ($this->id_stream > 0)
		{
			$stream_cache = NewsletterStreamsCache::load()->get_stream($this->id_stream);
			$breadcrumb->add($stream_cache['name'], NewsletterUrlBuilder::archives($this->id_stream)->absolute());
		}
		
		$response->get_graphical_environment()->set_page_title($this->lang['archives.list']);
		return $response;
	}
}
?>