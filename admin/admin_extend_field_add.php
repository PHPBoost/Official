<?php
/*##################################################
 *                               admin_extend_field_add.php
 *                            -------------------
 *   begin                : June 16, 2007
 *   copyright            : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid'])) //Insertion du nouveau champs.
{
	$error = ExtendFieldAdmin::insert_or_update_field();
	if(empty($error))
	{
		AppContext::get_response()->redirect('/admin/admin_extend_field.php');
	}
	else
		AppContext::get_response()->redirect('/admin/admin_extend_field_add.php?error='.$error.'#errorh');
}
else
{
	$Template->set_filenames(array(
		'admin_extend_field_add'=> 'admin/admin_extend_field_add.tpl'
	));
	
	//Gestion erreur.
	$get_error = $extend_field->get_error();
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif ($get_error == 'exist_field')
		$Errorh->handler($LANG['e_exist_field'], E_USER_NOTICE);
		
	$Template->assign_vars(array(
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_DEFAULT_FIELD_VALUE' => $LANG['default_field_possible_values'],
		'L_EXTEND_FIELD_MANAGEMENT' => $LANG['extend_field_management'],
		'L_EXTEND_FIELD_ADD' => $LANG['extend_field_add'],
		'L_EXTEND_FIELD' => $LANG['extend_field'],
		'L_REQUIRE' => $LANG['require'],
		'L_NAME' => $LANG['name'],
		'L_TYPE' => $LANG['type'],
		'L_DESC' => $LANG['description'],
		'L_REQUIRED_FIELD' => $LANG['require_field'],
		'L_REQUIRED_FIELD_EXPLAIN' => $LANG['required_field_explain'],
		'L_REQUIRED' => $LANG['required'],
		'L_NOT_REQUIRED' => $LANG['not_required'],
		'L_SHORT_TEXT' => $LANG['short_text'],
		'L_LONG_TEXT' => $LANG['long_text'],
		'L_SEL_UNIQ' => $LANG['sel_uniq'],
		'L_SEL_MULT' => $LANG['sel_mult'],
		'L_CHECK_UNIQ' => $LANG['check_uniq'],
		'L_CHECK_MULT' => $LANG['check_mult'],
		'L_PERSO_REGEXP' => $LANG['personnal_regex'],
		'L_PREDEF_REGEXP' => $LANG['predef_regexp'],
		'L_FIGURES' => $LANG['figures'],
		'L_LETTERS' => $LANG['letters'],
		'L_FIGURES_LETTERS' => $LANG['figures_letters'],
		'L_MAIL' => $LANG['mail'],
		'L_WEBSITE' => $LANG['website'],
		'L_POSSIBLE_VALUES' => $LANG['possible_values'],
		'L_POSSIBLE_VALUES_EXPLAIN' => $LANG['possible_values_explain'],
		'L_DEFAULT_VALUE' => $LANG['default_values'],
		'L_DEFAULT_VALUE_EXPLAIN' => $LANG['default_values_explain'],
		'L_REGEX' => $LANG['regex'],
		'L_REGEX_EXPLAIN' => $LANG['regex_explain'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $LANG['submit'],
	));
	
	$Template->pparse('admin_extend_field_add');		
}

require_once('../admin/admin_footer.php');

?>