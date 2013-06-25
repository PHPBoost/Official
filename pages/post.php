<?php
/*##################################################
*                               post.php
*                            -------------------
*   begin                : August 12, 2007
*   copyright            : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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

require_once('../kernel/begin.php'); 
require_once('../pages/pages_begin.php'); 
include_once('pages_functions.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$id_edit = retrieve(GET, 'id', 0);
$id_edit_post = retrieve(POST, 'id_edit', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_post;
$title = retrieve(POST, 'title', '');
$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
$count_hits = !empty($_POST['count_hits']) ? 1 : 0;
$enable_com = !empty($_POST['comments_activated']) ? 1 : 0;
$own_auth = !empty($_POST['own_auth']);
$is_cat = !empty($_POST['is_cat']) ? 1 : 0;
$id_cat = retrieve(POST, 'id_cat', 0);
$display_print_link = !empty($_POST['display_print_link']) ? 1 : 0;
$preview = !empty($_POST['preview']);
$del_article = retrieve(GET, 'del', 0);

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

//Variable d'erreur
$error = '';
if ($id_edit > 0)
	define('TITLE', $LANG['pages_edition']);
else
	define('TITLE', $LANG['pages_creation']);
	
if ($id_edit > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', 'display_print_link', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
	$Bread_crumb->add(TITLE, url('post.php?id=' . $id_edit));
	$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . Url::encode_rewrite($_PAGES_CATS[$id]['name']), Url::encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if ($User->check_auth($config_authorizations, EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'));
	
require_once('../kernel/header.php');

//On cr�e ou on �dite une page
if (!empty($contents))
{
	if ($own_auth)
	{
		//G�n�ration du tableau des droits.
		$array_auth_all = Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM);
		$page_auth = addslashes(serialize($array_auth_all));
	}
	else
		$page_auth = '';
	
	//on ne pr�visualise pas, donc on poste le message ou on l'�dite
	if (!$preview)
	{
		//Edition d'une page
		if ($id_edit > 0)
		{
			$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'contents', 'auth', 'encoded_title', 'is_cat', 'id_cat', 'display_print_link', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			
			//Autorisation particuli�re ?
			$special_auth = !empty($page_infos['auth']);
			$array_auth = unserialize($page_infos['auth']);
			//V�rification de l'autorisation d'�diter la page
			if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($config_authorizations, EDIT_PAGE)))
				AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
			
			//on v�rifie que la cat�gorie ne s'ins�re pas dans un de ses filles
			if ($page_infos['is_cat'] == 1)
			{
				$sub_cats = array();
				pages_find_subcats($sub_cats, $page_infos['id_cat']);
				$sub_cats[] = $page_infos['id_cat'];
				if (in_array($id_cat, $sub_cats)) //Si l'ancienne cat�gorie ne contient pas la nouvelle (sinon boucle infinie)
					$error = 'cat_contains_cat';
			}
			
			//Articles (on �dite l'entr�e de l'article pour la cat�gorie donc aucun probl�me)
			if ($page_infos['is_cat'] == 0)
			{		
				//On met � jour la table
				$Sql->query_inject("UPDATE " . PREFIX . "pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $enable_com . "', auth = '" . $page_auth . "', id_cat = '" . $id_cat . "', display_print_link = '" . $display_print_link . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				//On redirige vers la page mise � jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
			//cat�gories : risque de boucle infinie
			elseif ($page_infos['is_cat'] == 1 && empty($error))
			{
				//Changement de cat�gorie m�re ? => on met � jour la table cat�gories
				if ($id_cat != $page_infos['id_cat'])
				{
					$Sql->query_inject("UPDATE " . PREFIX . "pages_cats SET id_parent = '" . $id_cat . "' WHERE id = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
				}
				//On met � jour la table
				$Sql->query_inject("UPDATE " . PREFIX . "pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $enable_com . "', auth = '" . $page_auth . "', display_print_link = '" . $display_print_link . "'  WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				//R�g�n�ration du cache
				$Cache->Generate_module_file('pages');
				//On redirige vers la page mise � jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
		}
		//Cr�ation d'une page
		elseif (!empty($title))
		{
			if (!$User->check_auth($config_authorizations, EDIT_PAGE))
				AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
			
			$encoded_title = Url::encode_rewrite($title);
			$is_already_page = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
			
			//Si l'article n'existe pas d�j�, on enregistre
			if ($is_already_page == 0)
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "pages (title, encoded_title, contents, user_id, count_hits, activ_com, timestamp, auth, is_cat, id_cat, display_print_link) VALUES ('" . $title . "', '" . $encoded_title . "', '" .  pages_parse($contents) . "', '" . $User->get_attribute('user_id') . "', '" . $count_hits . "', '" . $enable_com . "', '" . time() . "', '" . $page_auth . "', '" . $is_cat . "', '" . $id_cat . "', '" . $display_print_link . "')", __LINE__, __FILE__);
				//Si c'est une cat�gorie
				if ($is_cat > 0)
				{
					$last_id_page = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "pages");  
					$Sql->query_inject("INSERT INTO " . PREFIX . "pages_cats (id_parent, id_page) VALUES ('" . $id_cat . "', '" . $last_id_page . "')", __LINE__, __FILE__);
					$last_id_pages_cat = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "pages_cats");
					$Sql->query_inject("UPDATE " . PREFIX . "pages SET id_cat = '" . $last_id_pages_cat . "' WHERE id = '" . $last_id_page . "'", __LINE__, __FILE__);
					//R�g�n�ration du cache
					$Cache->Generate_module_file('pages');
				}
				//On redirige vers la page mise � jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
			}
			//Sinon, message d'erreur
			else
			{
				$error = 'page_already_exists';
			}
		}
	}
	else
		$error = 'preview';
}
//Suppression d'une page
elseif ($del_article > 0)
{
    //V�rification de la validit� du jeton
    $Session->csrf_get_protect();
    
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', 'display_print_link', "WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	
	//Autorisation particuli�re ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
		
	//la page existe bien, on supprime
	if (!empty($page_infos['title']))
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE redirect = '" . $del_article . "'", __LINE__, __FILE__);
		CommentsService::delete_comments_topic_module('pages', $del_article);
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=delete_success', '', '&'));
	}
	else
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=delete_failure', '', '&'));
}

$Template = new FileTemplate('pages/post.tpl');

if ($id_edit > 0)
{
	//Autorisation particuli�re ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//V�rification de l'autorisation d'�diter la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));
	
	//Erreur d'enregistrement ?
	if ($error == 'cat_contains_cat')
		$Template->put('message_helper', MessageHelper::display($LANG['pages_cat_contains_cat'], E_USER_WARNING));
	elseif ($error == 'preview')
	{
		$Template->put('message_helper', MessageHelper::display($LANG['pages_notice_previewing'], E_USER_NOTICE));
		$Template->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}

	//G�n�ration de l'arborescence des cat�gories
	$cats = array();
	//num�ro de la cat�gorie de la page ou de la cat�gorie
	$id_cat_display = $page_infos['is_cat'] == 1 ? $_PAGES_CATS[$page_infos['id_cat']]['id_parent'] : $page_infos['id_cat'];
	$cat_list = display_cat_explorer($id_cat_display, $cats, 1);
	
	$Template->put_all(array(
		'CONTENTS' => !empty($error) ? stripslashes($contents) : pages_unparse($page_infos['contents']),
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($page_infos['count_hits'] == 1 ? 'checked="checked"' : ''),
		'COMMENTS_ACTIVATED_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') : ($page_infos['activ_com'] == 1 ? 'checked="checked"' : ''),
		'DISPLAY_PRINT_LINK_CHECKED' => !empty($error) ? ($display_print_link == 1 ? 'checked="checked"' : '') : ($page_infos['display_print_link'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => !empty($page_infos['auth']) ? 'checked="checked"' : '',
		'CAT_0' => $id_cat_display == 0 ? 'pages_selected_cat' : '',
		'ID_CAT' => $id_cat_display,
		'SELECTED_CAT' => $id_cat_display,
		'CHECK_IS_CAT' => 'disabled="disabled"' . ($page_infos['is_cat'] == 1 ? ' checked="checked"' : '')
	));
}
else
{
	//Autorisations
	if (!$User->check_auth($config_authorizations, EDIT_PAGE))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
		
	//La page existe d�j� !
	if ($error == 'page_already_exists')
		$Template->put('message_helper', MessageHelper::display($LANG['pages_already_exists'], E_USER_WARNING));
	elseif ($error == 'preview')
	{
		$Template->put('message_helper', MessageHelper::display($LANG['pages_notice_previewing'], E_USER_NOTICE));
		$Template->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}
	if (!empty($error))
		$Template->put_all(array(
			'CONTENTS' => stripslashes($contents),
			'PAGE_TITLE' => stripslashes($title)
		));
	
	$Template->assign_block_vars('create', array());
	
	//G�n�ration de l'arborescence des cat�gories
	$cats = array();
	$cat_list = display_cat_explorer(0, $cats, 1);
	$current_cat = $LANG['pages_root'];
	
	$Template->put_all(array(
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($pages_config->get_count_hits_activated() == true ? 'checked="checked"' : ''),
		'COMMENTS_ACTIVATED_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') :($pages_config->get_comments_activated() == true ? 'checked="checked"' : ''),
		'DISPLAY_PRINT_LINK_CHECKED' => !empty($error) ? ($display_print_link == 1 ? 'checked="checked"' : '') : 'checked="checked"',
		'OWN_AUTH_CHECKED' => '',
		'CAT_0' => 'pages_selected_cat',
		'ID_CAT' => '0',
		'SELECTED_CAT' => '0'
	));
}

if (!empty($page_infos['auth']))
	$array_auth = unserialize($page_infos['auth']);
else
	$array_auth = $config_authorizations;

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$Template->put_all(array(
	'ID_EDIT' => $id_edit,
	'SELECT_READ_PAGE' => Authorizations::generate_select(READ_PAGE, $array_auth),
	'SELECT_EDIT_PAGE' => Authorizations::generate_select(EDIT_PAGE, $array_auth),
	'SELECT_READ_COM' => Authorizations::generate_select(READ_COM, $array_auth),
	'OWN_AUTH_DISABLED' => !empty($page_infos['auth']) ? 'false' : 'true',
	'DISPLAY' => empty($page_infos['auth']) ? 'display:none;' : '',
	'CAT_LIST' => $cat_list,
	'KERNEL_EDITOR' => $editor->display(),
	'L_AUTH' => $LANG['pages_auth'],
	'L_COMMENTS_ACTIVATED' => $LANG['pages_comments_activated'],
	'L_DISPLAY_PRINT_LINK' => $LANG['pages_display_print_link'],
	'L_COUNT_HITS' => $LANG['pages_count_hits_activated'],
	'L_ALERT_CONTENTS' => $LANG['page_alert_contents'],
	'L_ALERT_TITLE' => $LANG['page_alert_title'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_OWN_AUTH' => $LANG['pages_own_auth'],
	'L_IS_CAT' => $LANG['pages_is_cat'],
	'L_CAT' => $LANG['pages_parent_cat'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_PATH' => $LANG['pages_page_path'],
	'L_PROPERTIES' => $LANG['pages_properties'],
	'L_TITLE_POST' => $id_edit > 0 ? sprintf($LANG['pages_edit_page'], $page_infos['title']) : $LANG['pages_creation'],
	'L_TITLE_FIELD' => $LANG['page_title'],
	'L_CONTENTS' => $LANG['page_contents'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUMBIT' => $LANG['submit'],
	'L_ROOT' => $LANG['pages_root'],
	'L_PREVIEWING' => $LANG['pages_previewing'],
	'L_CONTENTS_PART' => $LANG['pages_contents_part'],
	'L_SUBMIT' => $id_edit > 0 ? $LANG['update'] : $LANG['submit'],
	'TARGET' => url('post.php?token=' . $Session->get_token())
));

$Template->display();

require_once('../kernel/footer.php'); 

?>