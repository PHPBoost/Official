<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxIconsController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxIconsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'FA'              => self::get_fa(),
			'ICOMOON'         => self::build_markup('sandbox/pagecontent/icons/icomoon.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function get_fa()
	{
		$view = new FileTemplate('sandbox/pagecontent/icons/fa.tpl');
		$view->add_lang(array_merge($this->lang));

		// Social
		$icons = array(
			array('fab', 'facebook-f', '\f39e'),
			array('fab', 'google-plus-g', '\f0d5'),
			array('fab', 'twitter', '\f099'),
			array('fas', 'hashtag', '\f292')
		);

		foreach ($icons as $icon)
		{
			$view->assign_block_vars('social', array(
				'PREFIX' => $icon[0],
				'FA'     => $icon[1],
				'CODE'   => $icon[2]
			));
		}

		// Responsive
		$icons = array(
			array('fas', 'tv', '\f26c'),
			array('fas', 'desktop', '\f108'),
			array('fas', 'laptop', '\f109'),
			array('fas', 'tablet-alt', '\f3fa'),
			array('fas', 'mobile-alt', '\f3cd')
		);

		foreach ($icons as $icon)
		{
			$view->assign_block_vars('responsive', array(
				'PREFIX' => $icon[0],
				'FA'     => $icon[1],
				'CODE'   => $icon[2]
			));
		}

		return $view;
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);

		return $view;
	}


	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.icons'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.icons'], SandboxUrlBuilder::icons()->rel());

		return $response;
	}
}
?>
