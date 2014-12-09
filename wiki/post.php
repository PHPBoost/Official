<?php
/*##################################################
 *                               post.php
 *                            -------------------
 *   begin                : October 09, 2006
 *   copyright            : (C) 2006 Sautel Benoit
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

require_once('../kernel/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
load_module_lang('wiki');
$config = WikiConfig::load();

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

define('TITLE', $LANG['wiki_contribuate']);

$bread_crumb_key = 'wiki_post';
require_once('../wiki/wiki_bread_crumb.php');

$is_cat = retrieve(POST, 'is_cat', false) ? 1 : 0;
$is_cat_get = (retrieve(GET, 'type', '') == 'cat') ? 1 : 0;
$is_cat = $is_cat > 0 ? $is_cat : $is_cat_get;
$id_edit = retrieve(POST, 'id_edit', 0);
$title = retrieve(POST, 'title', '');
$encoded_title = retrieve(GET, 'title', '');
$contents = wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));
$contents_preview = TextHelper::htmlspecialchars(retrieve(POST, 'contents', '', TSTRING_UNCHANGE));
$id_cat = retrieve(GET, 'id_parent', 0);
$new_id_cat = retrieve(POST, 'id_cat', 0);
$id_cat = $id_cat > 0 ? $id_cat : $new_id_cat;
$preview = !empty($_POST['preview']) ? true : false;
$id_edit_get = retrieve(GET, 'id', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_get;

require_once('../kernel/header.php'); 

//Variable d'erreur
$error = '';

$tpl = new FileTemplate('wiki/post.tpl');

$captcha = AppContext::get_captcha_service()->get_default_factory();
if (!empty($contents)) //On enregistre un article
{
	include_once('../wiki/wiki_functions.php');	
	//On cr�e le menu des paragraphes et on enregistre le menu
	$menu = '';
	
	//Si on d�tecte la syntaxe des menus alors on lance les fonctions, sinon le menu sera vide et non affich�
	if (preg_match('`[\-]{2,6}`isU', $contents))
	{
		$menu_list = wiki_explode_menu($contents); //On �clate le menu en tableaux
		$menu = wiki_display_menu($menu_list); //On affiche le menu
	}
	
	if ($preview)//Pr�visualisation
	{
		$tpl->assign_block_vars('preview', array(
			'CONTENTS' => FormatingHelper::second_parse(wiki_no_rewrite(stripslashes($contents))),
			'TITLE' => stripslashes($title)
		));
		if (!empty($menu))
		{
			$tpl->assign_block_vars('preview.menu', array(
				'MENU' => $menu
			));
		}
	}
	else //Sinon on poste
	{
		if ($id_edit > 0)//On �dite un article
		{
			$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('encoded_title', 'auth'), 'WHERE id = :id', array('id' => $id_edit));
			//Autorisations
			$general_auth = empty($article_infos['auth']) ? true : false;
			$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
			if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT))))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			} 
			
			$previous_id_contents = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'id_contents', 'WHERE id = :id', array('id' => $id_edit));
			//On met � jour l'ancien contenu (comme archive)
			PersistenceContext::get_querier()->update(PREFIX . "wiki_contents", array('activ' => 0), 'WHERE id_contents = :id', array('id' => $previous_id_contents));
			//On ins�re le contenu
			$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_contents", array('id_article' => $id_edit, 'menu' => $menu, 'content' => $contents, 'activ' => 1, 'user_id' => AppContext::get_current_user()->get_id(), 'user_ip' => AppContext::get_request()->get_ip_address(), 'timestamp' => time()));
			//Dernier id enregistr�
			$id_contents = $result->get_last_inserted_id();
            
	 		//On donne le nouveau id de contenu
			PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $id_contents), 'WHERE id = :id', array('id' => $id_edit));
        
            // Feeds Regeneration
            
            Feed::clear_cache('wiki');
			
			//On redirige
			$redirect = $article_infos['encoded_title'];
			AppContext::get_response()->redirect(url('wiki.php?title=' . $redirect, $redirect, '', '&'));
		}
		elseif (!empty($title)) //On cr�e un article
		{
			//autorisations
			if ($is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_CAT))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			} 
			elseif (!$is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			} 
			elseif (!$captcha->is_valid() && !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
			{
				$error_controller = new UserErrorController(
					LangLoader::get_message('error', 'status-messages-common'),
					LangLoader::get_message('captcha.validation_error', 'status-messages-common'),
					UserErrorController::NOTICE
				);
				DispatchManager::redirect($error_controller);
			}
			
			//On v�rifie que le titre n'existe pas
			$article_exists = PersistenceContext::get_querier()->count(PREFIX . "wiki_articles", 'WHERE encoded_title = :encoded_title', array('encoded_title' => Url::encode_rewrite($title)));
			
			//Si il existe: message d'erreur
			if ($article_exists > 0)
				$errstr = $LANG['wiki_title_already_exists'];
			else //On enregistre
			{
				$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_articles", array('title' => $title, 'encoded_title' => Url::encode_rewrite($title), 'id_cat' => $new_id_cat, 'is_cat' => $is_cat, 'undefined_status' => '', 'auth' => ''));
				//On r�cup�re le num�ro de l'article cr��
				$id_article = $result->get_last_inserted_id();
				//On ins�re le contenu
				$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_contents", array('id_article' => $id_article, 'menu' => $menu, 'content' => $contents, 'activ' => 1, 'user_id' => AppContext::get_current_user()->get_id(), 'user_ip' => AppContext::get_request()->get_ip_address(), 'timestamp' => time()));
				//On met � jour le num�ro du contenu dans la table articles
				$id_contents = $result->get_last_inserted_id();
				if ($is_cat == 1)//si c'est une cat�gorie, on la cr�e
				{
					$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_cats", array('id_parent' => $new_id_cat, 'article_id' => $id_article));
					//on r�cup�re l'id de la derni�re cat�gorie cr��e
					$id_created_cat = $result->get_last_inserted_id();
					PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $shout_contents, 'id_cat' => $id_created_cat), 'WHERE id = :id', array('id' => $id_article));
					//On r�g�n�re le cache
					$Cache->Generate_module_file('wiki');
				}
				else
					PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $shout_contents), 'WHERE id = :id', array('id' => $id_article));
				
                // Feeds Regeneration
                
                Feed::clear_cache('wiki');
                
				$redirect = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'encoded_title', 'WHERE id = :id', array('id' => $id_article));
				AppContext::get_response()->redirect(url('wiki.php?title=' . $redirect, $redirect, '' , '&'));
			}
		}
	}
}

if ($id_edit > 0)//On �dite
{
	$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('*'), 'WHERE id = :id', array('id' => $id_edit));
	
	//Autorisations
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	} 
	
	$article_contents = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_contents', array('*'), 'WHERE id_contents = :id', array('id' => $article_infos['id_contents']));
	$contents = $article_contents['content'];
	if (!empty($article_contents['menu'])) //On reforme les paragraphes
	{
		$string_regex = '-';
		for ($i = 1; $i <= 5; $i++)
		{
			$string_regex .= '-';
			$contents = preg_replace('`[\r\n]+<(?:div|h[1-5]) class="wiki_paragraph' .  $i . '" id=".+">(.+)</(?:div|h[1-5])><br />[\r\n]+`sU', "\n" . $string_regex . ' $1 '. $string_regex, "\n" . $contents . "\n");
		}
		$contents = trim($contents);
	}
	
	$l_action_submit = $LANG['update'];
	
	$tpl->put_all(array(
		'SELECTED_CAT' => $id_edit,
	));
}
else
{
	//autorisations
	if ($is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_CAT))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	} 
	elseif (!$is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
	
	if (!empty($encoded_title))
		$tpl->put('message_helper', MessageHelper::display($LANG['wiki_article_does_not_exist'], MessageHelper::WARNING));	
	
	if ($id_cat > 0 && array_key_exists($id_cat, $_WIKI_CATS)) //Cat�gorie pr�selectionn�e
	{
		$tpl->assign_block_vars('create', array());
		$cats = array();
		$cat_list = display_cat_explorer($id_cat, $cats, 1);
		$cats = array_reverse($cats);
		if (array_key_exists(0, $cats))
			unset($cats[0]);
		$nbr_cats = count($cats);
		$current_cat = '';
		$i = 1;
		foreach ($cats as $key => $value)
		{
			$current_cat .= $_WIKI_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_WIKI_CATS[$id_cat]['name'];
		$tpl->put_all(array(
			'SELECTED_CAT' => $id_cat,
			'CAT_0' => '',
			'CAT_LIST' => $cat_list,
			'CURRENT_CAT' => $current_cat
		));
	}
	else //Si il n'a pas de cat�gorie parente
	{
		$tpl->assign_block_vars('create', array());
		$contents = '';
		$result = PersistenceContext::get_querier()->select("SELECT c.id, a.title, a.encoded_title
		FROM " . PREFIX . "wiki_cats c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
		WHERE c.id_parent = 0
		ORDER BY title ASC");
		while ($row = $result->fetch())
		{
			$module_data_path = PATH_TO_ROOT . '/wiki/templates';
			$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "wiki_cats", 'WHERE id_parent = :id', array('id' => $row['id']));
			if ($sub_cats_number > 0)
			{	
				$tpl->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $row['id'] . ', 1);"><i class="fa fa-plus-square-o" id="img2_' . $row['id'] . '"></i><i class="fa fa-folder" id ="img_' . $row['id'] . '"></i></a><a id="class_' . $row['id'] . '" href="javascript:select_cat(' . $row['id'] . ');">' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
			else
			{
				$tpl->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li class="sub"><a id="class_' . $row['id'] . '" href="javascript:select_cat(' . $row['id'] . ');"><i class="fa fa-folder"></i>' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
		}
		$result->dispose();
		$tpl->put_all(array(
			'SELECTED_CAT' => 0,
			'CAT_0' => 'selected',
			'CAT_LIST' => '',
			'CURRENT_CAT' => $LANG['wiki_no_selected_cat']
		));
	}
	$l_action_submit = $LANG['submit'];
}

//On travaille uniquement en BBCode, on force le langage de l'�diteur
$content_editor = AppContext::get_content_formatting_service()->get_default_factory();
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$tpl->put_all(array(
	'C_VERIF_CODE' => !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'TITLE' => $is_cat == 1 ? ($id_edit == 0 ? $LANG['wiki_create_cat'] : sprintf($LANG['wiki_edit_cat'], $article_infos['title'])) : ($id_edit == 0 ? $LANG['wiki_create_article'] : sprintf($LANG['wiki_edit_article'], $article_infos['title'])),
	'KERNEL_EDITOR' => $editor->display(),
	'ID_CAT' => $id_edit > 0 ? $article_infos['id_cat'] : '',
	'CONTENTS' => !empty($contents_preview) ? $contents_preview : ($id_edit > 0 ? wiki_unparse(trim($contents)) : ''),
	'ID_EDIT' => $id_edit,
	'IS_CAT' => $is_cat,
	'ID_CAT' => $id_cat,
	'VERIF_CODE' => $captcha->display(),
	'ARTICLE_TITLE' => !empty($encoded_title) ? $encoded_title : stripslashes($title),'L_TITLE_FIELD' => $LANG['title'],
	'TARGET' => url('post.php' . ($is_cat == 1 ? '?type=cat&amp;token=' . AppContext::get_session()->get_token() : '?token=' . AppContext::get_session()->get_token())),
	'L_CONTENTS' => $LANG['wiki_contents'],
	'L_ALERT_CONTENTS' => $LANG['require_text'],
	'L_ALERT_TITLE' => $LANG['require_title'],
	'L_REQUIRE' => $LANG['require'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUBMIT' => $l_action_submit,
	'L_CAT' => $LANG['wiki_article_cat'],
	'L_CURRENT_CAT' => $LANG['wiki_current_cat'],
	'L_DO_NOT_SELECT_ANY_CAT' => $LANG['wiki_do_not_select_any_cat'],
	'L_PREVIEWING' => $LANG['wiki_previewing'],
	'L_TABLE_OF_CONTENTS' => $LANG['wiki_table_of_contents'],
));

//outils BBcode en javascript
include_once('../wiki/post_js_tools.php');
$tpl->put('post_js_tools', $jstools_tpl);

//Eventuelles erreurs
if (!empty($errstr))
	$tpl->put('message_helper', MessageHelper::display($errstr, MessageHelper::WARNING));

$tpl->display();


require_once('../kernel/footer.php');

?>