<?php
/*##################################################
 *                           NewsletterModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 12, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class NewsletterModuleHomePage implements ModuleHomePage
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	
	private $view;
	private $nbr_streams_per_page = 25;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$request = AppContext::get_request();
		$this->init();
		
		$current_page = $request->get_int('page', 1);
		$nbr_streams = PersistenceContext::get_sql()->count_table(NewsletterSetup::$newsletter_table_streams, __LINE__, __FILE__);
		$nbr_pages =  ceil($nbr_streams / $this->nbr_streams_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/newsletter', '')->absolute());
		$this->view->put_all(array(
			'C_STREAMS' => (float)$nbr_streams,
			'LINK_SUBSCRIBE' => NewsletterUrlBuilder::subscribe()->absolute(),
			'LINK_UNSUBSCRIBE' => NewsletterUrlBuilder::unsubscribe()->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_streams_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT id, name, description, picture, visible, auth
		FROM " . NewsletterSetup::$newsletter_table_streams . "
		LIMIT ". $this->nbr_streams_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$read_auth = NewsletterAuthorizationsService::id_stream($row['id'])->read();
			if ($read_auth && $row['visible'] == 1)
			{
				$read_archives_auth = NewsletterAuthorizationsService::id_stream($row['id'])->read_archives();
				$read_subscribers_auth = NewsletterAuthorizationsService::id_stream($row['id'])->read_subscribers();
				$this->view->assign_block_vars('streams_list', array(
					'PICTURE' => TPL_PATH_TO_ROOT . $row['picture'],
					'NAME' => $row['name'],
					'DESCRIPTION' => $row['description'],
					'VIEW_ARCHIVES' => $read_archives_auth ? '<a href="' . NewsletterUrlBuilder::archives($row['id'])->absolute() . '">'. $this->lang['newsletter.view_archives'] .'</a>' : $this->lang['newsletter.not_level'],
					'VIEW_SUBSCRIBERS' => $read_subscribers_auth ? '<a href="' . NewsletterUrlBuilder::subscribers($row['id'])->absolute() . '">'. $this->lang['newsletter.view_subscribers'] .'</a>' : $this->lang['newsletter.not_level'],
				));
			}
		}
		
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $this->view);
		$body_view->put_all(array(
			'C_CREATE_AUTH' => NewsletterAuthorizationsService::default_authorizations()->create_newsletters(),
			'LINK_CREATE' => NewsletterUrlBuilder::add_newsletter()->absolute()
		));
		return $body_view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>