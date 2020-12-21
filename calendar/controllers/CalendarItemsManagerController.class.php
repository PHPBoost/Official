<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 21
 * @since       PHPBoost 4.0 - 2013 07 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarItemsManagerController extends AdminModuleController
{
	private $lang;
	private $view;

	private $items_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();
		$config = CalendarConfig::load();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
			new HTMLTableColumn($this->lang['calendar.repetition']),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, 'table', $columns, new HTMLTableSortingRule('start_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['calendar.config.events.management']);
		$table_model->add_permanent_filter('parent_id = 0');

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('event
			LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id'
		);
		foreach ($result as $row)
		{
			$item = new CalendarItem();
			$item->set_properties($row);
			$category = $item->get_content()->get_category();
			$user = $item->get_content()->get_author_user();

			$this->items_number++;
			$this->ids[$this->items_number] = $item->get_id();

			$edit_link = new EditLinkHTMLElement(CalendarUrlBuilder::edit_item(!$item->get_parent_id() ? $item->get_id() : $item->get_parent_id()));
			$delete_link = new DeleteLinkHTMLElement(CalendarUrlBuilder::delete_item($item->get_id()), '', array('data-confirmation' => !$item->belongs_to_a_serie() ? 'delete-element' : ''));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()), $item->get_content()->get_title()), 'left'),
				new HTMLTableRowCell(new SpanHTMLElement(($category->get_id() == Category::ROOT_CATEGORY ? LangLoader::get_message('none_e', 'common') : $category->get_name()), array('data-color-surround' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? $category->get_color() : ($category->get_id() == Category::ROOT_CATEGORY ? $config->get_event_color() : '')), 'pinned')),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell(LangLoader::get_message('from_date', 'main') . ' ' . $item->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('to_date', 'main') . ' ' . $item->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($item->belongs_to_a_serie() ? $this->get_repeat_type_label($item) . ' - ' . $item->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.labels.repeat.times'] : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($item->get_content()->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->items_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						try {
							$item = CalendarService::get_item('WHERE id_event = :id', array('id' => $this->ids[$i]));
						} catch (RowNotFoundException $e) {}

						if ($item)
						{
							$items_list = CalendarService::get_serie_items($item->get_content()->get_id());

							//Delete item
							CalendarService::delete_item($item->get_id(), $item->get_parent_id());

							if (!$item->belongs_to_a_serie() || count($items_list) == 1)
							{
								CalendarService::delete_item_content('WHERE id = :id', array('id' => $item->get_id()));
							}
						}
					}
				}
			}

			CalendarService::clear_cache();

			AppContext::get_response()->redirect(CalendarUrlBuilder::manage_items(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}

	private function get_repeat_type_label(CalendarItem $item)
	{
		$label = CalendarItemContent::NEVER;

		switch ($item->get_content()->get_repeat_type())
		{
			case CalendarItemContent::DAILY :
				$label = LangLoader::get_message('every_day', 'date-common');
				break;

			case CalendarItemContent::WEEKLY :
				$label = LangLoader::get_message('every_week', 'date-common');
				break;

			case CalendarItemContent::MONTHLY :
				$label = LangLoader::get_message('every_month', 'date-common');
				break;

			case CalendarItemContent::YEARLY :
				$label = LangLoader::get_message('every_year', 'date-common');
				break;
		}

		return $label;
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['calendar.config.events.management'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], CalendarUrlBuilder::home());

		$breadcrumb->add($this->lang['calendar.config.events.management'], CalendarUrlBuilder::manage_items());

		return $response;
	}
}
?>