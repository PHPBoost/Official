<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 30
 * @since       PHPBoost 5.3 - 2020 03 12
*/

class DefaultDisplayItemController extends AbstractItemController
{
	protected $item;
	protected $category;

	protected $current_url;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->check_authorizations();
		$this->update_views_number($request);
		$this->build_view();

		return $this->generate_response();
	}

	protected function init(HTTPRequestCustom $request)
	{
		$this->current_url = self::get_module()->get_configuration()->has_categories() ? ItemsUrlBuilder::display($this->get_item()->get_category()->get_id(), $this->get_item()->get_category()->get_rewrited_name(), $this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id) : ItemsUrlBuilder::display_item($this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id);
	}

	protected function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			try {
				$this->item = self::get_items_manager()->get_item($id);
			} catch (RowNotFoundException $e) {
				$this->RowNotFoundException();
			}
		}
		return $this->item;
	}

	protected function update_views_number(HTTPRequestCustom $request)
	{
		if (!$this->get_item()->is_published())
		{
			$this->view->put('NOT_PUBLISHED_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), $this->current_url->rel()))
			{
				self::get_items_manager()->update_views_number($this->get_item());
			}
		}
	}

	protected function build_view()
	{
		if (in_array('comments', $this->enabled_features))
		{
			$comments_topic = new DefaultCommentsTopic(self::$module_id, $this->get_item());
			$comments_topic->set_id_in_module($this->get_item()->get_id());
			$comments_topic->set_url($this->current_url);

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		if (self::get_module()->get_configuration()->feature_is_enabled('keywords'))
		{
			$keywords = $this->get_item()->get_keywords();
			$this->view->put('C_KEYWORDS', !empty($keywords));

			foreach ($keywords as $keyword)
			{
				$this->view->assign_block_vars('keywords', array(
					'NAME' => $keyword->get_name(),
					'URL' => ItemsUrlBuilder::display_tag($keyword->get_rewrited_name(), self::$module_id)->rel()
				));
			}
		}

		if (in_array('notation', $this->enabled_features))
		{
			$this->view->put('NOTATION', NotationService::display_active_image($this->get_item()->get_notation()));
		}

		if (self::get_module()->get_configuration()->feature_is_enabled('sources'))
		{
			foreach ($this->get_item()->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('sources', $this->get_item()->get_template_source_vars($name));
			}
		}

		$this->view->put_all($this->get_item()->get_template_vars());
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('framework/content/items/ModuleItemController.tpl');
	}

	protected function check_authorizations()
	{
		$authorizations = self::get_module()->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_category()->get_id(), self::$module_id) : ItemsAuthorizationsService::check_authorizations(self::$module_id);
		
		$current_user = AppContext::get_current_user();
		$not_authorized = !$authorizations->moderation() && !$authorizations->write() && (!$authorizations->contribution() || $this->get_item()->get_author_user()->get_id() != $current_user->get_id());
		
		switch ($this->get_item()->get_publishing_state())
		{
			case Item::PUBLISHED:
				if (!$authorizations->read())
				{
					$this->display_user_not_authorized_page();
				}
			break;
			case Item::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->is_guest()))
				{
					$this->display_user_not_authorized_page();
				}
			break;
			case Item::DEFERRED_PUBLICATION:
				if (!$this->get_item()->is_published() && ($not_authorized || ($current_user->is_guest())))
				{
					$this->display_user_not_authorized_page();
				}
			break;
			default:
				$this->display_unexisting_page();
			break;
		}
	}

	protected function get_additionnal_seo_properties()
	{
		return array();
	}

	protected function get_seo_page_type()
	{
		return '';
	}

	protected function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_item()->get_title(), (self::get_module()->get_configuration()->has_categories() && $this->get_item()->get_category()->get_id() != Category::ROOT_CATEGORY ? $this->get_item()->get_category()->get_name() . ' - ' : '') . self::get_module()->get_configuration()->get_name());
		$graphical_environment->get_seo_meta_data()->set_canonical_url($this->current_url);
		if (self::get_module()->get_configuration()->has_rich_items())
			$graphical_environment->get_seo_meta_data()->set_description($this->get_item()->get_real_summary());

		if (self::get_module()->get_configuration()->has_rich_items() && $this->get_item()->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->get_item()->get_thumbnail());
		
		if ($seo_page_type = $this->get_seo_page_type())
			$graphical_environment->get_seo_meta_data()->set_page_type($seo_page_type);

		$graphical_environment->get_seo_meta_data()->set_additionnal_properties($this->get_additionnal_seo_properties());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module()->get_configuration()->get_name(), ModulesUrlBuilder::home());

		if (self::get_module()->get_configuration()->has_categories())
		{
			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_item()->get_category()->get_id(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ItemsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id));
			}
		}
		$breadcrumb->add($this->get_item()->get_title(), $this->current_url);

		return $response;
	}

	public static function get_view($module_id = '')
	{
		$object = new self($module_id);
		$object->check_authorizations();
		$object->update_views_number();
		$object->build_view();
		return $object->view;
	}
}
?>
