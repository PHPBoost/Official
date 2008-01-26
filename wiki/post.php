<?php
/*##################################################
 *                               post.php
 *                            -------------------
 *   begin                : October 09, 2006
 *   copyright          : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once('../includes/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
load_module_lang('wiki', $CONFIG['lang']);

define('TITLE', $LANG['wiki'] . ': ' . $LANG['wiki_contribuate']);
define('ALTERNATIVE_CSS', 'wiki');


$speed_bar_key = 'wiki_post';
require_once('../wiki/wiki_speed_bar.php');

$is_cat = !empty($_POST['is_cat']) ? 1 : 0;
$is_cat_get = (!empty($_GET['type']) && $_GET['type'] == 'cat')  ? 1 : 0;
$is_cat = $is_cat > 0 ? $is_cat : $is_cat_get;
$id_edit = !empty($_POST['id_edit']) ? numeric($_POST['id_edit']) : 0;
$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
$contents = !empty($_POST['contents']) ? wiki_parse($_POST['contents']) : '';
$contents_preview = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
$id_cat = !empty($_GET['id_parent']) ? numeric($_GET['id_parent']) : 0;
$new_id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$id_cat = $id_cat > 0 ? $id_cat : $new_id_cat;
$preview = !empty($_POST['preview']) ? true : false;
$id_edit_get = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_get;

require_once('../includes/header.php'); 

//Variable d'erreur
$error = '';

if( !empty($contents) ) //On enregistre un article
{
	include_once('../wiki/wiki_functions.php');	
	//On cr�e le menu des paragraphes et on enregistre le menu
	$menu = '';
	
	//Si on d�tecte la syntaxe des menus alors on lance les fonctions, sinon le menu sera vide et non affich�
	if( preg_match('`[\-]{2,6}`isU', $contents) )
	{
		$menu_to_make = html_entity_decode($contents);		
		wiki_explode_menu($menu_to_make, 1); //On �clate le menu en tableaux
		wiki_display_menu($menu_to_make, $menu, 1); //On affiche le menu
		
		 //On ins�re les paragraphes dans la page
		for( $i = 1; $i <= 5; $i++ )
		{
			$contents = preg_replace('`[\n\r]{1}[\-]{' . ($i + 1) . '}[\s]+(.+)[\s]+[\-]{' . ($i + 1) . '}(<br \/>|[\n\r]){1}`U', "\n" . '<div class="wiki_paragraph' .  $i . '" id="$1">$1</div><br />' . "\n", "\n" . $contents . "\n");
			$contents = preg_replace_callback('`id="(.+)">`isU', 'wiki_make_anchors', $contents);
		}
	}
	//On supprime les \n rajout�s en d�but et en fin
	$contents = trim($contents);

	if( $preview )//Pr�visualisation
	{
		$template->assign_block_vars('preview', array(
			'CONTENTS' => second_parse(wiki_no_rewrite(stripslashes($contents))),
			'TITLE' => stripslashes($title)
		));
		if( !empty($menu) )
			$template->assign_block_vars('preview.menu', array(
				'MENU' => stripslashes($menu)
			));
	}
	else //Sinon on poste
	{
		if( $id_edit > 0 )//On �dite un article
		{		
			$article_infos = $sql->query_array("wiki_articles", "encoded_title", "auth", "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__); 
			//Autorisations
			$general_auth = empty($article_infos['auth']) ? true : false;
			$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
			if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_EDIT)) && ($general_auth || $groups->check_auth($article_auth , WIKI_EDIT))) )
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			
			$previous_id_contents = $sql->query("SELECT id_contents FROM ".PREFIX."wiki_articles WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			//On met � jour l'ancien contenu (comme archive)
			$sql->query_inject("UPDATE ".PREFIX."wiki_contents SET activ = 0 WHERE id_contents = '" . $previous_id_contents . "'", __LINE__, __FILE__);
			//On ins�re le contenu
			$sql->query_inject("INSERT INTO ".PREFIX."wiki_contents (id_article, menu, content, activ, user_id, user_ip, timestamp) VALUES ('" . $id_edit . "', '" . $menu . "', '" . $contents . "', 1, " . $session->data['user_id'] . ", '" . USER_IP . "', " . time() . ")", __LINE__, __FILE__);
			//Dernier id enregistr�
			$id_contents = $sql->sql_insert_id("SELECT MAX(id_contents) FROM ".PREFIX."wiki_contents");

	 		//On donne le nouveau id de contenu
			$sql->query_inject("UPDATE ".PREFIX."wiki_articles SET id_contents = '" . $id_contents . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			//Reg�n�ration du flux rss.
			include_once('../includes/rss.class.php'); //Flux rss reg�n�r�!
			$rss = new Rss('wiki/rss.php');
			$rss->cache_path('../cache/');
			$rss->generate_file('javascript', 'rss_wiki');
			$rss->generate_file('php', 'rss2_wiki');
			
			//On redirige
			$redirect = $article_infos['encoded_title'];
			redirect(transid('wiki.php?title=' . $redirect, $redirect, '', '&'));
		}
		elseif( !empty($title) ) //On cr�e un article
		{
			//autorisations
			if( $is_cat && !$groups->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT) )
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			elseif( !$is_cat && !$groups->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE) )
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			
			//On v�rifie que le titre n'existe pas
			$article_exists = $sql->query("SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE encoded_title = '" . url_encode_rewrite($title) . "'", __LINE__, __FILE__);
			
			
			//Si il existe: message d'erreur
			if( $article_exists > 0 )
				$errstr = $LANG['wiki_title_already_exists'];
			else //On enregistre
			{
				$sql->query_inject("INSERT INTO ".PREFIX."wiki_articles (title, encoded_title, id_cat, is_cat) VALUES ('" . $title . "', '" . url_encode_rewrite($title) . "', '" . $new_id_cat . "', '" . $is_cat . "')", __LINE__, __FILE__);
				//On r�cup�re le num�ro de l'article cr��
				$id_article = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."wiki_articles");
				//On ins�re le contenu
				$sql->query_inject("INSERT INTO ".PREFIX."wiki_contents (id_article, menu, content, activ, user_id, user_ip, timestamp) VALUES ('" . $id_article . "', '" . $menu . "', '" . $contents . "', 1, " . $session->data['user_id'] . ", '" . USER_IP . "', " . time() . ")", __LINE__, __FILE__);
				//On met � jour le num�ro du contenu dans la table articles
				$id_contents = $sql->sql_insert_id("SELECT MAX(id_contents) FROM ".PREFIX."wiki_contents");
				$cat_update = '';
				if( $is_cat == 1 )//si c'est une cat�gorie, on la cr�e
				{
					$sql->query_inject("INSERT INTO ".PREFIX."wiki_cats (id_parent, article_id) VALUES (" . $new_id_cat . ", '" . $id_article . "')", __LINE__, __FILE__);
					//on r�cup�re l'id de la derni�re cat�gorie cr��e
					$id_created_cat = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."wiki_articles");
					$cat_update = ", id_cat = '" . $id_created_cat . "'";
					//On r�g�n�re le cache
					$cache->generate_module_file('wiki');
				}
				$sql->query_inject("UPDATE ".PREFIX."wiki_articles SET id_contents = '" . $id_contents . "'" . $cat_update . " WHERE id = " . $id_article, __LINE__, __FILE__);
				
				//Reg�n�ration du flux rss.
				include_once('../includes/rss.class.php'); //Flux rss reg�n�r�!
				$rss = new Rss('wiki/rss.php');
				$rss->cache_path('../cache/');
				$rss->generate_file('javascript', 'rss_wiki');
				$rss->generate_file('php', 'rss2_wiki');
		
				$redirect = $sql->query("SELECT encoded_title FROM ".PREFIX."wiki_articles WHERE id = '" . $id_article . "'", __LINE__, __FILE__);
				redirect(transid('wiki.php?title=' . $redirect, $redirect, '' , '&'));
			}
		}
	}
}

//On propose le formulaire
$template->set_filenames(array('wiki_edit' => '../templates/' . $CONFIG['theme'] . '/wiki/post.tpl'));
$template->assign_vars(array(
	'WIKI_PATH' => $template->module_data_path('wiki'),
));
if( $id_edit > 0 )//On �dite
{
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
	
	//Autorisations
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_EDIT)) && ($general_auth || $groups->check_auth($article_auth , WIKI_EDIT))) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	$article_contents = $sql->query_array('wiki_contents', '*', "WHERE id_contents = '" . $article_infos['id_contents'] . "'", __LINE__, __FILE__);
	$contents = $article_contents['content'];
	if( !empty($article_contents['menu']) ) //On reforme les paragraphes
	{
		$string_regex = '-';
		for( $i = 1; $i <= 5; $i++ )
		{
			$string_regex .= '-';
			$contents = preg_replace('`[\r\n]+<div class="wiki_paragraph' .  $i . '" id=".+">(.+)</div><br />[\r\n]+`sU', "\n" . $string_regex . ' $1 '. $string_regex, "\n" . $contents . "\n");
		}
		$contents = trim($contents);
	}
	
	$l_action_submit = $LANG['update'];
	
	$template->assign_vars(array(
		'SELECTED_CAT' => $id_edit,
	));
}
else
{	
	//autorisations
	if( $is_cat && !$groups->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	elseif( !$is_cat && !$groups->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	if( $id_cat > 0 && array_key_exists($id_cat, $_WIKI_CATS) ) //Cat�gorie pr�selectionn�e
	{
		$template->assign_block_vars('create', array());
		$cats = array();
		$cat_list = display_cat_explorer($id_cat, $cats, 1);
		$cats = array_reverse($cats);
		if( array_key_exists(0, $cats) )
			unset($cats[0]);
		$nbr_cats = count($cats);
		$current_cat = '';
		$i = 1;
		foreach( $cats as $key => $value )
		{
			$current_cat .= $_WIKI_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_WIKI_CATS[$id_cat]['name'];
		$template->assign_vars(array(
			'SELECTED_CAT' => $id_cat,
			'CAT_0' => '',
			'CAT_LIST' => $cat_list,
			'CURRENT_CAT' => $current_cat
		));
	}
	else //Si il n'a pas de cat�gorie parente
	{
		$template->assign_block_vars('create', array());
		$contents = '';
		$result = $sql->query_while("SELECT c.id, a.title, a.encoded_title
		FROM ".PREFIX."wiki_cats c
		LEFT JOIN ".PREFIX."wiki_articles a ON a.id = c.article_id
		WHERE c.id_parent = 0
		ORDER BY title ASC", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$sub_cats_number = $sql->query("SELECT COUNT(*) FROM ".PREFIX."wiki_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			if( $sub_cats_number > 0 )
			{	
				$template->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', 1);"><img src="' . $template->module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '"  style="vertical-align:middle" /></a> 
					<a href="javascript:show_cat_contents(' . $row['id'] . ', 1);"><img src="' . $template->module_data_path('wiki') . '/images/closed_cat.png" id ="img_' . $row['id'] . '" alt="" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:select_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
			else
			{
				$template->assign_block_vars('create.list', array(
					'DIRECTORY' => '<li style="padding-left:17px;"><img src="' . $template->module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:select_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
		}
		$sql->close($result);
		$template->assign_vars(array(
			'SELECTED_CAT' => 0,
			'CAT_0' => 'wiki_selected_cat',
			'CAT_LIST' => '',
			'CURRENT_CAT' => $LANG['wiki_no_selected_cat']
		));
	}
	$l_action_submit = $LANG['submit'];
}

include_once('../includes/bbcode.php');

$template->assign_vars(array(
	'TITLE' => $is_cat == 1 ? ($id_edit == 0 ? $LANG['wiki_create_cat'] : sprintf($LANG['wiki_edit_cat'], $article_infos['title'])) : ($id_edit == 0 ? $LANG['wiki_create_article'] : sprintf($LANG['wiki_edit_article'], $article_infos['title'])),
	'L_TITLE_FIELD' => $LANG['title'],
	'L_CONTENTS' => $LANG['wiki_contents'],
	'L_ALERT_CONTENTS' => $LANG['require_text'],
	'L_ALERT_TITLE' => $LANG['require_title'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUBMIT' => $l_action_submit,
	'L_CAT' => $LANG['wiki_article_cat'],
	'L_CURRENT_CAT' => $LANG['wiki_current_cat'],
	'L_DO_NOT_SELECT_ANY_CAT' => $LANG['wiki_do_not_select_any_cat'],
	'ID_CAT' => $id_edit > 0 ? $article_infos['id_cat'] : '',
	'CONTENTS' => !empty($contents_preview) ? stripslashes($contents_preview) : ($id_edit > 0 ? wiki_unparse(trim($contents)) : ''),
	'ID_EDIT' => $id_edit,
	'IS_CAT' => $is_cat,
	'ID_CAT' => $id_cat,
	'ARTICLE_TITLE' => stripslashes($title),
	'L_PREVIEWING' => $LANG['wiki_previewing'],
	'L_TABLE_OF_CONTENTS' => $LANG['wiki_table_of_contents'],
	'TARGET' => transid('post.php' . ($is_cat == 1 ? '?type=cat' : '')),
));

//outils BBcode en javascript
include_once('../wiki/post_js_tools.php');

//Eventuelles erreurs
if( !empty($errstr) )
	$errorh->error_handler($errstr, E_USER_WARNING);

$template->pparse('wiki_edit');


require_once('../includes/footer.php');

?>