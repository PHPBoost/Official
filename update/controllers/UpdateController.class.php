<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 22
 * @since       PHPBoost 3.0 - 2012 02 29
*/

abstract class UpdateController extends AbstractController
{
	const DEFAULT_LOCALE = 'french';

	protected $lang = array();

	protected function load_lang(HTTPRequestCustom $request)
	{
		$this->redirect_to_https_if_needed($request);
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', UpdateController::DEFAULT_LOCALE));
		LangLoader::set_locale($locale);
		UpdateUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get('update', 'update');
	}

	protected function redirect_to_https_if_needed(HTTPRequestCustom $request)
	{
		if (!$request->get_is_https() && Url::check_url_validity(str_replace('http://', 'https://', $request->get_site_url())))
			AppContext::get_response()->redirect(str_replace('http://', 'https://', $request->get_current_url()));
	}
}
?>
