<?php
/*##################################################
 *                         UpdateDBConfigController.class.php
 *                            -------------------
 *   begin                : March 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class UpdateDBConfigController extends UpdateController
{
	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var HTMLForm
	 */
	private $submit_button;
	/**
	 * @var FormFieldsetHTML
	 */
	private $overwrite_fieldset;
	/**
	 * @var FormFieldCheckbox
	 */
	private $overwrite_field;
	private $error = null;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$host = $this->form->get_value('host');
			$port = $this->form->get_value('port');
			$login = $this->form->get_value('login');
			$password = $this->form->get_value('password');
			$schema = $this->form->get_value('schema');
			$tables_prefix = $this->form->get_value('tablesPrefix');
			$this->handle_form($host, $port, $login, $password, $schema, $tables_prefix);
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('databaseForm');

		$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['dbms.paramters']);
		$this->form->add_fieldset($fieldset_server);

		$host = new FormFieldTextEditor('host', $this->lang['dbms.host'], 'localhost',
		array('description' => $this->lang['dbms.host.explanation'], 'required' => $this->lang['db.required.host']));
		$fieldset_server->add_field($host);
		$port = new FormFieldTextEditor('port', $this->lang['dbms.port'], '3306',
		array('description' => $this->lang['dbms.port.explanation'], 'required' => $this->lang['db.required.port']));
		$port->add_constraint(new FormFieldConstraintIntegerRange(1, 65536));
		$fieldset_server->add_field($port);
		$login = new FormFieldTextEditor('login', $this->lang['dbms.login'], 'root',
		array('description' => $this->lang['dbms.login.explanation'], 'required' => $this->lang['db.required.login']));
		$fieldset_server->add_field($login);
		$password = new FormFieldPasswordEditor('password', $this->lang['dbms.password'], '',
		array('description' => $this->lang['dbms.password.explanation']));
		$fieldset_server->add_field($password);

		$fieldset_schema = new FormFieldsetHTML('schemaConfig', $this->lang['schema.properties']);
		$this->form->add_fieldset($fieldset_schema);

		$schema = new FormFieldTextEditor('schema', $this->lang['schema'], '',
		array('required' => $this->lang['db.required.schema']));
		$schema->add_event('change', '$FFS(\'overwriteFieldset\').disable()');
		$fieldset_schema->add_field($schema);
		$tables_prefix = new FormFieldTextEditor('tablesPrefix', $this->lang['schema.tablePrefix'], 'phpboost_',
		array('description' => $this->lang['schema.tablePrefix.explanation']));
		$fieldset_schema->add_field($tables_prefix);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLink($this->lang['step.previous'], UpdateUrlBuilder::server_configuration(), 'templates/images/left.png');
		$action_fieldset->add_element($back);
		$check_request = new AjaxRequest(UpdateUrlBuilder::check_database(), 'function(response){
		alert(response.responseJSON.message);
		}');
		$check = new FormButtonAjax($this->lang['db.config.check'], $check_request, 'templates/images/refresh.png',
		array($host, $port, $login, $password, $schema, $tables_prefix), '$HF(\'databaseForm\').validate()');
		$action_fieldset->add_element($check);
		$this->submit_button = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'database');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$service = new UpdateServices();
		$status = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
		switch ($status)
		{
			case UpdateServices::CONNECTION_SUCCESSFUL:
				$this->create_connection($service, $host, $port, $login, $password, $schema, $tables_prefix);
				break;
			case UpdateServices::CONNECTION_ERROR:
				$this->error = $this->lang['db.connection.error'];
				break;
			case UpdateServices::UNEXISTING_DATABASE:
				$this->error = $this->lang['db.unexisting_database'];
				break;
			case UpdateServices::UNKNOWN_ERROR:
			default:
				$this->error = $this->lang['db.unknown.error'];
				break;
		}
	}

	private function create_connection(UpdateServices $service, $host, $port, $login, $password, $schema, $tables_prefix)
	{
		if ($service->is_already_installed($tables_prefix))
		{
			PersistenceContext::close_db_connection();
			$service->create_connection(DBFactory::MYSQL, $host, $port, $schema, $login, $password, $tables_prefix);
			AppContext::get_response()->redirect(UpdateUrlBuilder::update());
		}
		else
		{
			$this->error = $this->lang['phpboost.notInstalled.explanation'];
		}
	}

	/**
	 * @return UpdateDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('update/database.tpl');
		$this->view->put('DATABASE_FORM', $this->form->display());
		if (!empty($this->error))
		{
			$this->view->put('ERROR', $this->error);
		}
		$step_title = $this->lang['step.dbConfig.title'];
		$response = new UpdateDisplayResponse(2, $step_title, $this->view);
		return $response;
	}
}
?>