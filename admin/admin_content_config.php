<?php
/*##################################################
 *                               admin_content_config.php
 *                            -------------------
 *   begin                : July 4 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT.'/admin/admin_header.php');

if (!empty($_POST['submit']) )
{
	$content_formatting_config = ContentFormattingConfig::load();
	$content_formatting_config->set_default_editor(retrieve(POST, 'formatting_language', ''));
	$content_formatting_config->set_html_tag_auth(Authorizations::build_auth_array_from_form(1));
	$content_formatting_config->set_forbidden_tags(isset($_POST['forbidden_tags']) ? $_POST['forbidden_tags'] : array());
	ContentFormattingConfig::save();
	
	$content_management_config = ContentManagementConfig::load();
	$content_management_config->set_anti_flood_enabled((boolean)retrieve(POST, 'anti_flood', 0));
	$content_management_config->set_anti_flood_duration(retrieve(POST, 'delay_flood', 0));
	ContentManagementConfig::save();
	
	$user_accounts_config = UserAccountsConfig::load();
	$user_accounts_config->set_max_private_messages_number(retrieve(POST, 'pm_max', 25));
	UserAccountsConfig::save();
	
	AppContext::get_response()->redirect('/admin/admin_content_config.php');	
}
//Sinon on rempli le formulaire
else	
{		
	$template = new FileTemplate('admin/admin_content_config.tpl');
	
	$content_formatting_config = ContentFormattingConfig::load();
	$content_management_config = ContentManagementConfig::load();

	$j = 0;
	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $code => $name)
	{	
		$template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'CODE' => $code,
			'TAG_NAME' => $name,
			'C_ENABLED' => in_array($code, $content_formatting_config->get_forbidden_tags())
		));
	}
	
	foreach (AppContext::get_content_formatting_service()->get_available_editors() as $id => $name)
	{
		$template->assign_block_vars('formatting_language', array(
			'ID' => $id,
			'NAME' => $name,
			'C_SELECTED' => $id == $content_formatting_config->get_default_editor()
		));
	}
	
	$template->put_all(array(
		'SELECT_AUTH_USE_HTML' => Authorizations::generate_select(1, $content_formatting_config->get_html_tag_auth()),
		'NBR_TAGS' => $j,
		'PM_MAX' => UserAccountsConfig::load()->get_max_private_messages_number(),
		'DELAY_FLOOD' => $content_management_config->get_anti_flood_duration(),
		'FLOOD_ENABLED' => $content_management_config->is_anti_flood_enabled() ? 'checked="checked"' : '',
		'FLOOD_DISABLED' => !$content_management_config->is_anti_flood_enabled() ? 'checked="checked"' : '',
		
		'L_POST_MANAGEMENT' => $LANG['post_management'],
		'L_PM_MAX' => $LANG['pm_max'],
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_ANTI_FLOOD' => $LANG['anti_flood'],
		'L_INT_FLOOD' => $LANG['int_flood'],
		'L_PM_MAX_EXPLAIN' => $LANG['pm_max_explain'],
		'L_ANTI_FLOOD_EXPLAIN' => $LANG['anti_flood_explain'],
		'L_INT_FLOOD_EXPLAIN' => $LANG['int_flood_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIVE' => $LANG['unactiv'],
		
		'L_CONTENT_CONFIG' => $LANG['content_config_extend'],
		'L_DEFAULT_LANGUAGE' => $LANG['default_formatting_language'],
		'L_LANGUAGE_CONFIG' => $LANG['content_language_config'],
		'L_HTML_LANGUAGE' => $LANG['content_html_language'],
		'L_AUTH_USE_HTML' => $LANG['content_auth_use_html'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->display(); // traitement du modele	
}

require_once(PATH_TO_ROOT.'/admin/admin_footer.php');
?>