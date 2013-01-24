<?php
/*##################################################
 *                   SiteDisplayGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 01, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SiteDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	/**
	 * @var BreadCrumb The page breadcrumb
	 */
	private $breadcrumb = null;
	/**
	 * @var ColumnsDisabled
	 */
	private $columns_disabled = null;
	
	private static $main_lang;
	
	public function __construct()
	{
		parent::__construct();
		$this->load_langs();
		$this->set_breadcrumb(new BreadCrumb());
		$this->set_columns_disabled(ThemeManager::get_theme(get_utheme())->get_columns_disabled());
	}
	
	private function load_langs()
	{
		self::$main_lang = LangLoader::get('main');
	}

	/**
	 * {@inheritdoc}
	 */
	function display_header()
	{
		self::set_page_localization($this->get_page_title());

		$template = new FileTemplate('header.tpl');
		
		$theme = ThemeManager::get_theme(get_utheme());
		$customize_interface = $theme->get_customize_interface();
		$header_logo_path = $customize_interface->get_header_logo_path();

		$customization_config = CustomizationConfig::load();
		
		$template->put_all(array(
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'SITE_NAME' => GeneralConfig::load()->get_site_name(),
			'MAINTAIN' => $this->display_site_maintenance(),
			'C_COMPTEUR' => false,
			'C_FAVICON' => $customization_config->favicon_exists(),
			'FAVICON' => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE' => $customization_config->favicon_type(),
			'C_HEADER_LOGO' => !empty($header_logo_path),
			'HEADER_LOGO' => Url::to_rel($header_logo_path),
			'TITLE' => $this->get_page_title(),
			'SITE_DESCRIPTION' => $this->get_seo_meta_data()->get_description(),
			'SITE_KEYWORD' => $this->get_seo_meta_data()->get_keywords(),
			'MODULES_CSS' => $this->get_modules_css_files_html_code(),
			'L_XML_LANGUAGE' => self::$main_lang['xml_lang'],
		));

		$this->display_counter($template);

		$this->display_menus($template);

		//Bread crumb
		$this->get_breadcrumb()->display($template);

		$template->display();
	}

	protected function display_counter(Template $template)
	{
		//If the counter is to be displayed, we display it
		if (GraphicalEnvironmentConfig::load()->is_visit_counter_enabled())
		{
			$compteur = PersistenceContext::get_sql()->query_array(DB_TABLE_VISIT_COUNTER,
				'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);

			$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
			$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';

			$template->put_all(array(
				'L_VISIT' => self::$main_lang['guest_s'],
				'L_TODAY' => self::$main_lang['today'],
				'C_COMPTEUR' => true,
				'COMPTEUR_TOTAL' => $compteur_total,
				'COMPTEUR_DAY' => $compteur_day
			));
		}
	}

	protected function display_menus(Template $template)
	{
		global $MENUS, $Cache;

		if (!@include_once(PATH_TO_ROOT . '/cache/menus.php'))
		{
			//En cas d'�chec, on r�g�n�re le cache
			$Cache->Generate_file('menus');

			//On inclut une nouvelle fois
			if (!@include_once(PATH_TO_ROOT . '/cache/menus.php'))
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
                    $LANG['e_cache_modules'], UserErrorController::FATAL);
                DispatchManager::redirect($controller);
			}
		}

		$columns_disabled = $this->get_columns_disabled();
		
		$enable_header_is_activated = !$columns_disabled->header_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__HEADER]);
		$enable_sub_header_is_activated = !$columns_disabled->sub_header_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__SUB_HEADER]);
		$enable_left_column_is_activated = !$columns_disabled->left_columns_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__LEFT]);
		$enable_right_column_is_activated = !$columns_disabled->right_columns_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__RIGHT]);
		$enable_top_central_is_activated = !$columns_disabled->top_central_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__TOP_CENTRAL]);
		
		$header_content = $enable_header_is_activated ? $MENUS[Menu::BLOCK_POSITION__HEADER] : '';
		$sub_header_content = $enable_sub_header_is_activated ? $MENUS[Menu::BLOCK_POSITION__SUB_HEADER] : '';
		$left_content = $enable_left_column_is_activated ? $MENUS[Menu::BLOCK_POSITION__LEFT] : '';
		$right_content = $enable_right_column_is_activated ? $MENUS[Menu::BLOCK_POSITION__RIGHT] : '';
		$top_central_content = $enable_top_central_is_activated ? $MENUS[Menu::BLOCK_POSITION__TOP_CENTRAL] : '';
		
		$template->put_all(array(
			'C_MENUS_HEADER_CONTENT' => $enable_header_is_activated,
		    'MENUS_HEADER_CONTENT' => $header_content,
			'C_MENUS_SUB_HEADER_CONTENT' => $enable_sub_header_is_activated,
			'MENUS_SUB_HEADER_CONTENT' => $sub_header_content,
			'C_MENUS_LEFT_CONTENT' => $enable_left_column_is_activated,
			'MENUS_LEFT_CONTENT' => $left_content,
			'C_MENUS_RIGHT_CONTENT' => $enable_right_column_is_activated,
			'MENUS_RIGHT_CONTENT' => $right_content,
			'C_MENUS_TOPCENTRAL_CONTENT' => $enable_top_central_is_activated,
			'MENUS_TOPCENTRAL_CONTENT' => $top_central_content
		));
	}

	protected function display_site_maintenance()
	{
		//Users not authorized cannot come here
		parent::process_site_maintenance();

		$template =  new FileTemplate('maintain.tpl');
		
		$maintenance_config = MaintenanceConfig::load();
		if ($this->is_under_maintenance() && $maintenance_config->get_display_duration_for_admin())
		{
			//Dur�e de la maintenance.
			$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000,
			21600, 25200, 28800, 57600, 86400, 172800, 604800);
			$array_delay = array(LangLoader::get_message('unspecified', 'main'), '1 ' . LangLoader::get_message('minute', 'main'),
				'5 ' . LangLoader::get_message('minutes', 'main'), '10 ' . LangLoader::get_message('minutes', 'main'), '15 ' . LangLoader::get_message('minutes', 'main'),
				'30 ' . LangLoader::get_message('minutes', 'main'), '1 ' . LangLoader::get_message('hour', 'main'), '2 ' . LangLoader::get_message('hours', 'main'),
				'3 ' . LangLoader::get_message('hours', 'main'), '4 ' . LangLoader::get_message('hours', 'main'), '5 ' . LangLoader::get_message('hours', 'main'),
				'6 ' . LangLoader::get_message('hours', 'main'), '7 ' . LangLoader::get_message('hours', 'main'), '8 ' . LangLoader::get_message('hours', 'main'),
				'16 ' . LangLoader::get_message('hours', 'main'), '1 ' . LangLoader::get_message('day', 'main'), '2 ' . LangLoader::get_message('hours', 'main'),
				'1 ' . LangLoader::get_message('week', 'main'));

			//Retourne le d�lai de maintenance le plus proche.
			if (!$maintenance_config->is_unlimited_maintenance())
			{
				$key_delay = 0;
				$current_time = time();
				$array_size = count($array_time) - 1;
				$end_timestamp = $maintenance_config->get_end_date()->get_timestamp();
				for ($i = $array_size; $i >= 1; $i--)
				{
					if (($end_timestamp - $current_time) - $array_time[$i] < 0
					&&  ($end_timestamp - $current_time) - $array_time[$i-1] > 0)
					{
						$key_delay = $i-1;
						break;
					}
				}

				//Calcul du format de la date
				$seconds = gmdate_format('s', $end_timestamp, TIMEZONE_SITE);
				$array_release = array(
				gmdate_format('Y', $end_timestamp, TIMEZONE_SITE),
				(gmdate_format('n', $end_timestamp, TIMEZONE_SITE) - 1),
				gmdate_format('j', $end_timestamp, TIMEZONE_SITE),
				gmdate_format('G', $end_timestamp, TIMEZONE_SITE),
				gmdate_format('i', $end_timestamp, TIMEZONE_SITE),
				($seconds < 10) ? trim($seconds, 0) : $seconds);

				$seconds = gmdate_format('s', time(), TIMEZONE_SITE);
				$array_now = array(
				gmdate_format('Y', time(), TIMEZONE_SITE), (gmdate_format('n', time(),
				TIMEZONE_SITE) - 1), gmdate_format('j', time(), TIMEZONE_SITE),
				gmdate_format('G', time(), TIMEZONE_SITE), gmdate_format('i', time(),
				TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
			}
			else //D�lai ind�termin�.
			{
				$key_delay = 0;
				$array_release = array('0', '0', '0', '0', '0', '0');
				$array_now = array('0', '0', '0', '0', '0', '0');
			}

			$template->put_all(array(
				'C_ALERT_MAINTAIN' => true,
				'C_MAINTAIN_DELAY' => true,
				'UNSPECIFIED' => $maintenance_config->is_unlimited_maintenance() ? 0 : 1,
				'DELAY' => isset($array_delay[$key_delay]) ? $array_delay[$key_delay] : '0',
				'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
				'MAINTAIN_NOW_FORMAT' => implode(',', $array_now),
				'L_MAINTAIN_DELAY' => self::$main_lang['maintain_delay'],
				'L_LOADING' => self::$main_lang['loading'],
				'L_DAYS' => self::$main_lang['days'],
				'L_HOURS' => self::$main_lang['hours'],
				'L_MIN' => self::$main_lang['minutes'],
				'L_SEC' => self::$main_lang['seconds'],
			));
		}
		return $template;
	}

	/**
	 * {@inheritdoc}
	 */
	function display_footer()
	{
		global $MENUS;
		$template = new FileTemplate('footer.tpl');

		$theme_configuration = ThemeManager::get_theme(get_utheme())->get_configuration();
		$columns_disabled = $this->get_columns_disabled();
		
		$bottom_top_central_is_activated = !$columns_disabled->bottom_central_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__BOTTOM_CENTRAL]);
		$top_footer_is_activated = !$columns_disabled->top_footer_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__TOP_FOOTER]);
		$footer_is_activated = !$columns_disabled->footer_is_disabled() && !empty($MENUS[Menu::BLOCK_POSITION__FOOTER]);
	
		$bottom_top_central_content = $bottom_top_central_is_activated ? $MENUS[Menu::BLOCK_POSITION__BOTTOM_CENTRAL] : '';
		$top_footer_content = $top_footer_is_activated ? $MENUS[Menu::BLOCK_POSITION__TOP_FOOTER] : '';
		$footer_content = $footer_is_activated ? $MENUS[Menu::BLOCK_POSITION__FOOTER] : '';
		
		$template->put_all(array(
			'THEME' => get_utheme(),
			'C_MENUS_BOTTOM_CENTRAL_CONTENT' => $bottom_top_central_is_activated,
			'MENUS_BOTTOMCENTRAL_CONTENT' => $bottom_top_central_content,
			'C_MENUS_TOP_FOOTER_CONTENT' => $top_footer_is_activated,
			'MENUS_TOP_FOOTER_CONTENT' => $top_footer_content,
			'C_MENUS_FOOTER_CONTENT' => $footer_is_activated,
			'MENUS_FOOTER_CONTENT' => $footer_content,
			'C_DISPLAY_AUTHOR_THEME' => GraphicalEnvironmentConfig::load()->get_display_theme_author(),
			'L_POWERED_BY' => self::$main_lang['powered_by'],
			'L_PHPBOOST_RIGHT' => self::$main_lang['phpboost_right'],
			'L_THEME' => self::$main_lang['theme'],
			'L_THEME_NAME' => $theme_configuration->get_name(),
			'L_BY' => strtolower(self::$main_lang['by']),
			'L_THEME_AUTHOR' => $theme_configuration->get_author_name(),
			'U_THEME_AUTHOR_LINK' => $theme_configuration->get_author_link(),
		    'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version()
		));

		//We add a page to the page displayed counter
		StatsSaver::update_pages_displayed('pages');

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$template->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH' => AppContext::get_bench()->to_string(),
				'REQ' => PersistenceContext::get_querier()->get_executed_requests_count() +
			PersistenceContext::get_sql()->get_executed_requests_number(),
				'L_REQ' => self::$main_lang['sql_req'],
				'L_ACHIEVED' => self::$main_lang['achieved'],
				'L_UNIT_SECOND' => self::$main_lang['unit_seconds_short']
			));
		}
		
		$this->display_counter($template);

		$template->display();
	}

	/**
	 * Returns the bread crumb
	 * @return BreadCrumb The breadcrumb
	 */
	public function get_breadcrumb()
	{
		return $this->breadcrumb;
	}

	/**
	 * Sets the page's bread crumb
	 * @param BreadCrumb $breadcrumb The bread crumb to use
	 */
	public function set_breadcrumb(BreadCrumb $breadcrumb)
	{
		$this->breadcrumb = $breadcrumb;
		$this->breadcrumb->set_graphical_environment($this);
	}
	
	public function get_columns_disabled()
	{
		return $this->columns_disabled;
	}
	
	public function set_columns_disabled($columns_disabled)
	{
		$this->columns_disabled = $columns_disabled;
	}
}
?>