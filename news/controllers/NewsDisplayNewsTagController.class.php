<?php
class NewsDisplayNewsTagController extends ModuleController
{
	private $tpl;
	private $lang;
	
	/**
	 * @var NewsKeyword
	 */
	private $keyword;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->tpl = new StringTemplate('');
	}
	
	public function build_view()
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$result = PersistenceContext::get_querier()->select('SELECT news.*, relation.id_news, relation.id_keyword, member.level, member.user_groups
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. NewsSetup::$news_keywords_relation_table .' relation ON relation.id_news = news.id
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		WHERE relation.id_keyword = :id_keyword AND (news.approbation_type = 1 OR (news.approbation_type = 2 AND news.start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))', array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));
		
		while ($row = $result->fetch())
		{

		}
	}
	
	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(NewsSetup::$news_keywords_table, array('*'), 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
					
					$keyword = new NewsKeyword();
					$keyword->set_properties($row);
					$this->keyword = $keyword;
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}
	
	private function generate_response()
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->get_keyword()->get_name());
		
		$response->add_breadcrumb_link($this->lang['news'], NewsUrlBuilder::home());
		$response->add_breadcrumb_link($this->get_keyword()->get_name(), NewsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));
	
		return $response->display($this->tpl);
	}
}
?>