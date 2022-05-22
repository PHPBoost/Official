<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 22
 * @since       PHPBoost 3.0 - 2010 09 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class InstallController extends AbstractController
{
	protected $lang = array();
	protected $locale = '';

	protected function load_lang(HTTPRequestCustom $request)
	{
		$this->redirect_to_https_if_needed($request);
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', ''));
		$this->locale = in_array($locale, InstallationServices::get_available_langs()) ? $locale : InstallationServices::get_default_lang();
		LangLoader::set_locale($this->locale);
		InstallUrlBuilder::set_locale($this->locale);
		$this->lang = LangLoader::get_all_langs('install', $this->locale);
	}

	protected function redirect_to_https_if_needed(HTTPRequestCustom $request)
	{
		if (!$request->get_is_https() && Url::check_url_validity(str_replace('http://', 'https://', $request->get_site_url())))
			AppContext::get_response()->redirect(str_replace('http://', 'https://', $request->get_current_url()));
	}
}
?>
