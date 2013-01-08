<?php
/*##################################################
 *                       AdminMemberAddController.class.php
 *                            -------------------
 *   begin                : December 27, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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

class AdminMemberAddController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/admin/admin_members.php');
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, LangLoader::get_message('members.add-member', 'admin-members-common'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
	}

	private function build_form()
	{
		$form = new HTMLForm('member-add');
		
		$fieldset = new FormFieldsetHTML('add_member', LangLoader::get_message('members.add-member', 'admin-members-common'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist())
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['email'], '', array(
			'class' => 'text', 'maxlength' => 255, 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist())
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array('required' => true)));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '', array('required' => true)));
		
		$fieldset->add_field(new FormFieldRanksSelect('rank', $this->lang['rank'], FormFieldRanksSelect::MEMBER));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function save()
	{
		$user_authentification = new UserAuthentification($this->form->get_value('login'), $this->form->get_value('password'));
		$user = new User();
		$user->set_level($this->form->get_value('rank')->get_raw_value());
		$user->set_email($this->form->get_value('mail'));
		$user->set_approbation(true);		
		UserService::create($user_authentification, $user);
		
		StatsCache::invalidate();
	}
}
?>