<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 15
 * @since   	PHPBoost 4.1 - 2015 02 04
*/

class MediaCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return MediaService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return MediaUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return MediaUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return MediaUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return MediaUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return MediaUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'media');
	}

	protected function check_authorizations()
	{
		if (!MediaAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>