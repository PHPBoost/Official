<?php
/*##################################################
 *                       AdminModuleAddController.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminModuleAddController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
	
		$this->upload_form();
		
		$this->build_view();
		
		foreach ($this->get_modules_not_installed() as $name => $module)
		{
			try {
				if ($request->get_string('add-' . $module->get_id()))
				{
					$activate = $request->get_bool('activated-' . $module->get_id(), false);
					$this->install_module($module->get_id(), $activate);
				}
			}
			catch (UnexistingHTTPParameterException $e)	{}
		}
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}
		
		$this->view->put('UPLOAD_FORM', $this->form->display());
		
		return new AdminModulesDisplayResponse($this->view, $this->lang['modules.add_module']);
	}
	
	private function init()
	{	
		$this->load_lang();
		$this->view = new FileTemplate('admin/modules/AdminModuleAddController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-modules-common');
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_module');
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['modules.upload_module']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldFilePicker('file', $this->lang['modules.upload_description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);	
		
		$this->form = $form;
	}
	
	private function build_view()
	{
		$modules_not_installed = $this->get_modules_not_installed();
		foreach ($modules_not_installed as $id => $module)
		{
			$configuration = $module->get_configuration();
			$author = $configuration->get_author();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();
			
			$this->view->assign_block_vars('available', array(
				'ID' => $module->get_id(),
				'NAME' => ucfirst($configuration->get_name()),
				'ICON' => $module->get_id(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR' => !empty($author_email) ? '<a href="mailto:' . $author_email . '">' . $author . '</a>' : $author,
				'AUTHOR_WEBSITE' => !empty($author_website) ? '<a href="' . $author_website . '"><img src="' . TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : '',
				'DESCRIPTION' => $configuration->get_description(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'PHP_VERSION' => $configuration->get_php_version(),
				'URL_REWRITE_RULES' => $configuration->get_url_rewrite_rules()		
			));
		}
		
		$this->view->put_all(array(
			'C_MODULES_AVAILABLE' => count($modules_not_installed) > 0 ? true : false,
		));
	}
	
	private function get_modules_not_installed()
	{
		$modules_not_installed = array();
		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder_name != 'lang' && !ModulesManager::is_module_installed($folder_name))
			{
				try
				{
					$module = new Module($folder_name);
					$module_configuration = $module->get_configuration();
					$modules_not_installed[$folder_name] = $module;
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}
		sort($modules_not_installed);
		return $modules_not_installed;
	}
	
	private function install_module($module_id, $activate)
	{
		switch(ModulesManager::install_module($module_id, $activate))
		{
			case ModulesManager::CONFIG_CONFLICT:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_config_conflict', 'errors'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::UNEXISTING_MODULE:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_unexist_module', 'errors'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::MODULE_ALREADY_INSTALLED:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_already_installed_module', 'errors'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::PHP_VERSION_CONFLICT:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_php_version_conflict', 'errors'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::MODULE_INSTALLED:
			default: 
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.install_success'], MessageHelper::SUCCESS, 10));
		}
	}
	
	private function upload_module()
	{
		$modules_folder = PATH_TO_ROOT . '/';
		if (!is_writable($modules_folder))
		{
			$is_writable = @chmod($dir, 0755);
		}
		else
		{
			$is_writable = true;
		}
		
		if ($is_writable)
		{
			$file = $this->form->get_value('file');
			if ($file !== null)
			{
				if (!ModulesManager::is_module_installed($file->get_name_without_extension()))
				{
					$upload = new Upload($modules_folder);
					$upload->disableContentCheck();
					if ($upload->file('upload_module_file', '`([A-Za-z0-9-_]+)\.(gzip|zip)+$`i'))
					{
						$archive_path = $modules_folder . $upload->get_filename();
						if ($upload->get_extension() == 'gzip')
						{
							import('php/pcl/pcltar', LIB_IMPORT);
							PclTarExtract($upload->get_filename(), $modules_folder);
							
							$file = new File($archive_path);
							$file->delete();
						}
						else if ($upload->get_extension() == 'zip')
						{
							import('php/pcl/pclzip', LIB_IMPORT);
							$zip = new PclZip($archive_path);
							$zip->extract(PCLZIP_OPT_PATH, $modules_folder, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$file = new File($archive_path);
							$file->delete();
						}
						$this->install_module($file->get_name_without_extension(), true);
					}
					else
					{
						$this->view->put('MSG', MessageHelper::display($this->lang['modules.upload_invalid_format'], MessageHelper::NOTICE, 4));
					}
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.already_installed'], MessageHelper::NOTICE, 4));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.upload_error'], MessageHelper::NOTICE, 4));
			}
		}
	}
}
?>