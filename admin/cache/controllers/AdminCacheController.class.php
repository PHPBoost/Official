<?php
/*##################################################
 *                        AdminCacheController.class.php
 *                            -------------------
 *   begin                : August 5 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

class AdminCacheController extends AbstractAdminFormPageController
{
	private $lang;

	public function __construct()
	{
		$this->lang = LangLoader::get('admin-cache-common');
		parent::__construct(LangLoader::get_message('process.success', 'errors-common'));
	}

	protected function create_form()
	{
		$form = new HTMLForm('cache_config');
		$fieldset = new FormFieldsetHTML('explain', $this->lang['cache']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHTML('exp', $this->lang['explain_data_cache']));
		$button = new FormButtonSubmit($this->lang['clear_cache'], 'button');
		$this->set_submit_button($button);
		$form->add_button($button);
		$this->set_form($form);
	}

	protected function handle_submit()
	{
		AppContext::get_cache_service()->clear_cache();
		HtaccessFileCache::regenerate();
	}

	protected function generate_response(View $view)
	{
		return new AdminCacheMenuDisplayResponse($view, $this->lang['cache']);
	}
}
?>