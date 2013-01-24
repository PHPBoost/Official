<?php
/*##################################################
 *                         HomeAddNewsletterController.class.php
 *                            -------------------
 *   begin                : February 8, 2011
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

class HomeAddNewsletterController extends ModuleController
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->create_newsletters())
		{
			NewsletterAuthorizationsService::get_errors()->create_newsletters();
		}
		
		$type = $request->get_value('type', '');
		
		if ($type !== '' && ($type == 'html' || $type == 'bbcode' || $type == 'text'))
		{
			AppContext::get_response()->redirect(NewsletterUrlBuilder::add_newsletter($type)->absolute());
		}
		
		$this->init();
		
		$tpl = new fileTemplate('newsletter/HomeAddNewsletterController.tpl');
		$tpl->add_lang($this->lang);

		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}
	
	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->absolute());
		$breadcrumb->add($this->lang['newsletter-add'], NewsletterUrlBuilder::add_newsletter()->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter-add']);
		return $response;
	}	
}
?>