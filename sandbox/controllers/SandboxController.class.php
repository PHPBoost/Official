<?php
/*##################################################
 *                          SandboxController.class.php
 *                            -------------------
 *   begin                : December 20, 2009
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

class SandboxController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		try 
		{
			//Exemple d'utilisation des cookies
			echo AppContext::get_request()->get_cookie('session_data');
			AppContext::get_response()->set_cookie(new HTTPCookie('testcookie', 'test'));
			echo AppContext::get_request()->get_cookie('testcookie');
		}
		catch (UnexistingHTTPParameterException $ex) {}
		
		$view = new View('sandbox/SandboxController.tpl');
		$form = $this->build_form();
		if ($request->is_post_method())
		{
			if ($form->validate())
			{
				$view->assign_vars(array(
					'C_RESULT' => true, 
					'TEXT' => $form->get_value('text'),
					'MULTI_LINE_TEXT' => $form->get_value('multi_line_text'),
					'RICH_TEXT' => $form->get_value('rich_text'),
					'RADIO' => $form->get_value('radio')->get_label(),
					'CHECKBOX' => var_export($form->get_value('checkbox'), true),
					'SELECT' => $form->get_value('select')->get_label(),
					'HIDDEN' => $form->get_value('hidden'),
					'DATE' => $form->get_value('date')->format(DATE_FORMAT_SHORT),
					'DATE_TIME' => $form->get_value('date_time')->format(DATE_FORMAT)					 
				));

				$file = $form->get_value('file');
				if ( $file !== null)
				{
					$view->assign_vars(array('FILE' => $file->get_name() . ' - ' . $file->get_size() . 'b - ' . $file->get_mime_type()));
				}
			}
		}
		$view->add_subtemplate('form', $form->export());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{
		$form = new HTMLForm('sandboxForm');

		// FIELDSET
		$fieldset = new FormFieldset('Fieldset');
		$form->add_fieldset($fieldset);

		// SINGLE LINE TEXT
		$fieldset->add_field(new FormFieldTextEditor('text', 'Champ texte', 'toto', array(
			'class' => 'text', 'maxlength' => 25, 'description' => 'nom'),
			array(new RegexFormFieldConstraint('`^[a-z0-9_]+$`i'))
		));

		// MULTI LINE TEXT
		$fieldset->add_field(new FormFieldMultiLineTextEditor('multi_line_text', 'Champ texte multi lignes', 'toto', 
			array('rows' => 6, 'cols' => 47, 'description' => 'Description')
		));

		// RICH TEXT
		$fieldset->add_field(new FormFieldRichTextEditor('rich_text', 'Champ texte riche', 'toto <strong>tata</strong>'));

		// RADIO
		$default_option = new FormFieldRadioChoiceOption('Choix 1', '1');
		$fieldset->add_field(new FormFieldRadioChoice('radio', 'Choix �num�ration', $default_option, 
			array(
				$default_option,
				new FormFieldRadioChoiceOption('Choix 2', '2')
			)
		));

		// CHECKBOX
		$fieldset->add_field(new FormFieldCheckbox('checkbox', 'Case � cocher', FormFieldCheckbox::CHECKED));

		// SELECT
		$default_select_option = new FormFieldSelectChoiceOption('Choix 1', '1');
		$fieldset->add_field(new FormFieldSelectChoice('select', 'Liste d�roulante', $default_select_option,
			array(
				$default_select_option,
					new FormFieldSelectChoiceOption('Choix 2', '2'),
					new FormFieldSelectChoiceOption('Choix 3', '3'),
					new FormFieldSelectChoiceGroupOption('Groupe 1', array(
					new FormFieldSelectChoiceOption('Choix 4', '4'),
					new FormFieldSelectChoiceOption('Choix 5', '5'),
				)),
					new FormFieldSelectChoiceGroupOption('Groupe 2', array(
					new FormFieldSelectChoiceOption('Choix 6', '6'),
					new FormFieldSelectChoiceOption('Choix 7', '7'),
				))
			)
		));

		$fieldset2 = new FormFieldset('Fieldset 2');
		$form->add_fieldset($fieldset2);

		// CAPTCHA
		$fieldset2->add_field(new FormFieldCaptcha());

		// HIDDEN
		$fieldset2->add_field(new FormFieldHidden('hidden', 'hidden'));

		// FREE FIELD
		$fieldset2->add_field(new FormFieldFree('free', 'Champ libre', 'Valeur champ libre'));

		// DATE
		$fieldset2->add_field(new FormFieldDate('date', 'Date', new Date()));

		// DATE TIME
		$fieldset2->add_field(new FormFieldDateTime('date_time', 'Heure', new Date()));

		// FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('file', 'Fichier'));

		return $form;
	}
}
?>
