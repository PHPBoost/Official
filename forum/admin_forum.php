<?php
/*##################################################
 *                               admin_forum.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

require_once('../forum/forum_begin.php');

$id = retrieve(GET, 'id', 0);
$del = retrieve(GET, 'del', 0);
$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);
$update_cached = retrieve(GET, 'upd', 0);

//Si c'est confirm� on execute
if (!empty($_POST['valid']) && !empty($id))
{
	$Cache->load('forum');

	$to = retrieve(POST, 'category', 0);
	$name = retrieve(POST, 'name', '');
	$url = retrieve(POST, 'url', '');
	$type = retrieve(POST, 'type', '');
	$subname = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
	$status = retrieve(POST, 'status', 1);
	$aprob = retrieve(POST, 'aprob', 0);

	$subname = FormatingHelper::strparse($subname, array(
	    4 => 'title',
	    5 => 'style',
	    8 => 'quote',
	    9 => 'hide',
	    10 => 'list',
	    15 => 'align',
	    16 => 'float',
	    19 => 'indent',
	    20 => 'pre',
	    21 => 'table',
	    22 => 'swf',
	    23 => 'movie',
	    24 => 'sound',
	    25 => 'code',
	    26 => 'math',
	    27 => 'anchor',
	    28 => 'acronym',
	    29 => 'block',
	    30 => 'fieldset',
	    31 => 'mail',
	    32 => 'line',
	    33 => 'wikipedia',
	    34 => 'html'
    ));

	if ($type == 1)
	{
		$url = '';
		$parent_category = 0;
	}
	elseif ($type == 2)
		$url = '';
	elseif ($type == 3)
	{
		$status = 1;
		if (empty($url)) //Ne doit pas etre vide dans tout les cas.
			$url = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'url', 'WHERE id=:id', array('id' => $id));
	}

	//Generation du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_FORUM, WRITE_CAT_FORUM, EDIT_CAT_FORUM);
	if (!empty($name))
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_cats", array('name' => $name, 'subname' => $subname, 'url' => $url, 'status' => $status, 'aprob' => $aprob, 'auth' => serialize($array_auth_all)), 'WHERE id = :id', array('id' => $id));

		if ($type != 3 || !empty($to))
		{
			//Empeche le deplacement dans une categorie fille.
			$to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id=:id AND id_left NOT BETWEEN :id_left AND :id_right', array('id' => $to, 'id_left' => $CAT_FORUM[$id]['id_left'], 'id_right' => $CAT_FORUM[$id]['id_right']));

			//Categorie parente changee?
			$change_cat = !empty($to) ? !($CAT_FORUM[$to]['id_left'] < $CAT_FORUM[$id]['id_left'] && $CAT_FORUM[$to]['id_right'] > $CAT_FORUM[$id]['id_right'] && ($CAT_FORUM[$id]['level'] - 1) == $CAT_FORUM[$to]['level']) : $CAT_FORUM[$id]['level'] > 0;
			if ($change_cat)
			{
				$admin_forum = new Admin_forum();
				$admin_forum->move_cat($id, $to);
			}
			else
				$Cache->Generate_module_file('forum'); //Regeneration du cache.
		}
		else
			$Cache->Generate_module_file('forum'); //Regeneration du cache.
	}
	else
		AppContext::get_response()->redirect('/forum/admin_forum.php?id=' . $id . '&error=incomplete');

    forum_generate_feeds();
	AppContext::get_response()->redirect('/forum/admin_forum.php');
}
elseif (!empty($del)) //Suppression de la categorie/sous-categorie.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Cache->load('forum');
	$confirm_delete = false;
	$idcat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id=:id', array('id' => $del));
	if (!empty($idcat) && isset($CAT_FORUM[$idcat]))
	{
		//On v�rifie si la cat�gorie contient des sous forums.
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		//On v�rifie si la cat�gorie ne contient pas de topic.
		$check_topic = PersistenceContext::get_querier()->count(PREFIX . 'forum_topics', 'WHERE idcat=:idcat', array('idcat' => $idcat));

		if ($check_topic == 0 && $nbr_sub_cat == 0) //Si vide on supprime simplement, la cat�gorie.
		{
			$confirm_delete = true;
			$admin_forum = new Admin_forum();
			$admin_forum->del_cat($idcat, $confirm_delete);

			forum_generate_feeds();
			AppContext::get_response()->redirect(HOST . SCRIPT);
		}
		else //Sinon on propose de deplacer les topics existants dans une autre categorie.
		{
			if (empty($_POST['del_cat']))
			{
				$tpl = new FileTemplate('forum/admin_forum_cat_del.tpl');

				if ($check_topic > 0) //Conserve les topics.
				{
					//Listing des categories disponibles, sauf celle qui va �tre supprim�e.
					$forums = '';
					$result = PersistenceContext::get_querier()->select("SELECT id, name, level
					FROM " . PREFIX . "forum_cats
					WHERE id_left NOT BETWEEN :id_left AND :id_right AND url = ''
					ORDER BY id_left", array(
						'id_left' => $CAT_FORUM[$idcat]['id_left'],
						'id_left' => $CAT_FORUM[$idcat]['id_right']
					));
					while ($row = $result->fetch())
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$disabled = ($row['level'] > 0) ? '' : ' disabled="disabled"';
						$forums .= '<option value="' . $row['id'] . '"' . $disabled . '>' . $margin . ' ' . $row['name'] . '</option>';
					}
					$result->dispose();

					$tpl->assign_block_vars('topics', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_topic'],
						'L_MOVE_TOPICS' => $LANG['move_topics_to'],
						'L_EXPLAIN_CAT' => sprintf((($check_topic > 1) ? $LANG['explain_topics'] : $LANG['explain_topic']), $check_topic)
					));
				}
				if ($nbr_sub_cat > 0) //Concerne uniquement les sous-forums.
				{
					//Listing des categories disponibles, sauf celle qui va etre supprimee.
					$forums = '<option value="0">' . $LANG['root'] . '</option>';
					$result = PersistenceContext::get_querier()->select("SELECT id, name, level
					FROM " . PREFIX . "forum_cats
					WHERE id_left NOT BETWEEN :id_left AND :id_right AND url = ''
					ORDER BY id_left", array(
						'id_left' => $CAT_FORUM[$idcat]['id_left'],
						'id_left' => $CAT_FORUM[$idcat]['id_right']
					));
					while ($row = $result->fetch())
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$forums .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
					}
					$result->dispose();

					$tpl->assign_block_vars('subforums', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_subforum'],
						'L_MOVE_FORUMS' => $LANG['move_sub_forums_to'],
						'L_EXPLAIN_CAT' => sprintf((($nbr_sub_cat > 1) ? $LANG['explain_subcats'] : $LANG['explain_subcat']), $nbr_sub_cat)
					));
				}

				$forum_name = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'name', 'WHERE id = :id', array('id' => $idcat));
				$tpl->put_all(array(
					'IDCAT' => $idcat,
					'FORUM_NAME' => $forum_name,
					'L_REQUIRE_SUBCAT' => $LANG['require_subcat'],
					'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
					'L_CAT_MANAGEMENT' => $LANG['cat_management'],
					'L_ADD_CAT' => $LANG['cat_add'],
					'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks_management', 'common', 'forum'),
					'L_FORUM_ADD_RANKS' => LangLoader::get_message('forum.actions.add_rank', 'common', 'forum'),
					'L_CAT_TARGET' => $LANG['cat_target'],
					'L_DEL_ALL' => $LANG['del_all'],
					'L_DEL_FORUM_CONTENTS' => sprintf($LANG['del_forum_contents'], $forum_name),
					'L_SUBMIT' => $LANG['submit'],
				));

				$tpl->display();
			}
			else //Traitements.
			{
				if (!empty($_POST['del_conf'])) //Suppression compl�te.
					$confirm_delete = true;

				$admin_forum = new Admin_forum();
				$admin_forum->del_cat($idcat, $confirm_delete);

				forum_generate_feeds();
				AppContext::get_response()->redirect(HOST . SCRIPT);
			}
		}
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif ($update_cached) //Mise à jour des données stockées en cache dans la bdd.
{
	$result = PersistenceContext::get_querier()->select("SELECT id, id_left, id_right
	FROM " . PREFIX . "forum_cats
	WHERE level > 0");
	while ($row = $result->fetch())
	{
		$cat_list = array($row['id']);
		if (($row['id_right'] - $row['id_left']) > 1)
		{
			$result2 = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left >= :id_left AND id_right <= :id_right", array(
				'id_left' => $row['id_left'],
				'id_right' => $row['id_right']
			));
			
			while ($row2 = $result2->fetch())
				$cat_list[] = $row2['id'];
			
			$result2->dispose();
		}
		
		$info_cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("COUNT(*) as nbr_topic", "SUM(nbr_msg) as nbr_msg"), "WHERE idcat IN :ids_list", array('ids_list' => $cat_list));
		PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('nbr_topic' => $info_cat['nbr_topic'], 'nbr_msg' => $info_cat['nbr_msg']), 'WHERE id=:id', array('id' => $row['id']));
	}
	$result->dispose();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif (!empty($id) && !empty($move)) //Monter/descendre.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Cache->load('forum');

	//Cat�gorie existe?
	if (!isset($CAT_FORUM[$id]))
		AppContext::get_response()->redirect('/forum/admin_forum.php');

	$admin_forum = new Admin_forum();

	if ($move == 'up' || $move == 'down')
		$admin_forum->move_updown_cat($id, $move);

	forum_generate_feeds();
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif (!empty($id))
{
	$Cache->load('forum');

	$tpl = new FileTemplate('forum/admin_forum_cat_edit.tpl');

	$forum_info = PersistenceContext::get_querier()->select_single_row(PREFIX . "forum_cats", array("id_left", "id_right", "level", "name", "subname", "url", "status", "aprob", "auth"), 'WHERE id = :id', array('id' => $id));

	//Listing des categories disponibles, sauf celle qui va etre supprimee.
	$forums = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = PersistenceContext::get_querier()->select("SELECT id, id_left, id_right, name, level
	FROM " . PREFIX . "forum_cats
	WHERE id_left NOT BETWEEN :id_left AND :id_right
	ORDER BY id_left", array(
		'id_left' => $CAT_FORUM[$idcat]['id_left'],
		'id_right' => $CAT_FORUM[$idcat]['id_right']
	));
	while ($row = $result->fetch())
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id_left'] < $forum_info['id_left'] && $row['id_right'] > $forum_info['id_right'] && ($forum_info['level'] - 1) == $row['level'] ) ? ' selected="selected"' : '';
		$forums .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
	}
	$result->dispose();

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));

	$is_root = ($forum_info['level'] > 0);

	$array_auth = !empty($forum_info['auth']) ? unserialize(stripslashes($forum_info['auth'])) : array(); //Récupération des tableaux des autorisations et des groupes.

	//Type de forum
	$type = 2;
	if (!empty($forum_info['url']))
		$type = 3;
	elseif ($forum_info['level'] == 0)
		$type = 1;

	$tpl->put_all(array(
		'ID' => $id,
		'TYPE' => $type,
		'CATEGORIES' => $forums,
		'NAME' => $forum_info['name'],
		'URL' => $forum_info['url'],
		'DESC' => FormatingHelper::unparse($forum_info['subname']),
		'CHECKED_APROB' => ($forum_info['aprob'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_APROB' => ($forum_info['aprob'] == 0) ? 'checked="checked"' : '',
		'CHECKED_STATUS' => ($forum_info['status'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_STATUS' => ($forum_info['status'] == 0) ? 'checked="checked"' : '',
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_FORUM, $array_auth),
		'AUTH_WRITE' => $is_root ? Authorizations::generate_select(WRITE_CAT_FORUM, $array_auth) : Authorizations::generate_select(WRITE_CAT_FORUM, $array_auth, array(), GROUP_DEFAULT_IDSELECT, GROUP_DISABLE_SELECT),
		'AUTH_EDIT' => $is_root ? Authorizations::generate_select(EDIT_CAT_FORUM, $array_auth) : Authorizations::generate_select(EDIT_CAT_FORUM, $array_auth, array(), GROUP_DEFAULT_IDSELECT, GROUP_DISABLE_SELECT),
		'DISABLED' => $is_root ? '0' : '1',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks_management', 'common', 'forum'),
		'L_FORUM_ADD_RANKS' => LangLoader::get_message('forum.actions.add_rank', 'common', 'forum'),
		'L_EDIT_CAT' => $LANG['cat_edit'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_APROB' => $LANG['visible'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_URL' => $LANG['url'],
		'L_URL_EXPLAIN' => $LANG['url_explain'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => LangLoader::get_message('yes', 'common'),
		'L_NO' => LangLoader::get_message('no', 'common'),
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit']
	));

	$tpl->display();
}
else
{
	$tpl = new FileTemplate('forum/admin_forum_cat.tpl');

	$tpl->put_all(array(
		'L_CONFIRM_DEL' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks_management', 'common', 'forum'),
		'L_FORUM_ADD_RANKS' => LangLoader::get_message('forum.actions.add_rank', 'common', 'forum'),
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => LangLoader::get_message('yes', 'common'),
		'L_NO' => LangLoader::get_message('no', 'common'),
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => LangLoader::get_message('add', 'common'),
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_UPDATE_DATA_CACHED' => $LANG['update_data_cached']
	));

	$max_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'MAX(id_left)', '');
	$list_cats_js = '';
	$array_js = '';
	$i = 0;
	$result = PersistenceContext::get_querier()->select("SELECT id, id_left, id_right, level, name, subname, url, status
	FROM " . PREFIX . "forum_cats
	ORDER BY id_left");
	while ($row = $result->fetch())
	{
		//On assigne les variables pour le POST en precisant l'idurl.
		$tpl->assign_block_vars('list', array(
			'I' => $i,
			'ID' => $row['id'],
			'NAME' => (strlen($row['name']) > 60) ? (substr($row['name'], 0, 60) . '...') : $row['name'],
			'INDENT' => $row['level'] * 35, //Indentation des sous catégories.
			'LOCK' => ($row['status'] == 0) ? '<i class="fa fa-lock"></i>' : '',
			'URL' => !empty($row['url']) ? '<a href="' . $row['url'] . '"><img src="./forum_mini.png" alt="" class="valign-middle" /></a> ' : '',
			'U_FORUM_VARS' => !empty($row['url']) ? $row['url'] : (($row['level'] > 0) ? 'forum' . url('.php?id=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php') : url('index.php?id=' . $row['id'], 'cat-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'))
		));

		$list_cats_js .= $row['id'] . ', ';

		$array_js .= 'array_cats[' . $row['id'] . '] = new Array();' . "\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";\n";
		$i++;
	}
	$result->dispose();

	$tpl->put_all(array(
		'LIST_CATS' => trim($list_cats_js, ', '),
		'ARRAY_JS' => $array_js,
		'ID_END' => ($i - 1)
	));

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>