<?php
/*##################################################
 *		                    ForumSearchable.class.php
 *                            -------------------
 *   begin                : February 21, 2012
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

class ForumSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct(true, true);
	}
	
	public function get_search_form($args)
	/**
	 *  Renvoie le formulaire de recherche du forum
	 */
	{
		global $User, $CONFIG_FORUM, $Cache, $CAT_FORUM, $LANG;

		$Tpl = new FileTemplate('forum/forum_search_form.tpl');

		require_once(PATH_TO_ROOT . '/forum/forum_functions.php');
		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		load_module_lang('forum'); //Chargement de la langue du module.
		$Cache->load('forum');

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time = !empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 0;
		$where = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'all';
		$colorate_result = !empty($args['ForumColorate_result']) ? true : false;

		$Tpl->put_all(Array(
            'L_DATE' => $LANG['date'],
            'L_DAY' => $LANG['day'],
            'L_DAYS' => $LANG['day_s'],
            'L_MONTH' => $LANG['month'],
            'L_MONTHS' => $LANG['month'],
            'L_YEAR' => $LANG['year'],
            'IS_SELECTED_30000' => $time == 30000 ? ' selected="selected"' : '',
            'IS_SELECTED_1' => $time == 1 ? ' selected="selected"' : '',
            'IS_SELECTED_7' => $time == 7 ? ' selected="selected"' : '',
            'IS_SELECTED_15' => $time == 15 ? ' selected="selected"' : '',
            'IS_SELECTED_30' => $time == 30 ? ' selected="selected"' : '',
            'IS_SELECTED_180' => $time == 180 ? ' selected="selected"' : '',
            'IS_SELECTED_360' => $time == 360 ? ' selected="selected"' : '',
            'L_OPTIONS' => $LANG['options'],
            'L_TITLE' => $LANG['title'],
            'L_CONTENTS' => $LANG['content'],
            'IS_TITLE_CHECKED' => $where == 'title' ? ' checked="checked"' : '' ,
            'IS_CONTENTS_CHECKED' => $where == 'contents' ? ' checked="checked"' : '' ,
            'IS_ALL_CHECKED' => $where == 'all' ? ' checked="checked"' : '' ,
            'L_COLORATE_RESULTS' => $LANG['colorate_result'],
            'IS_COLORATION_CHECKED' => $colorate_result ? 'checked="checked"' : '',
            'L_CATEGORY' => $LANG['category'],
            'L_ALL_CATS' => $LANG['all'],
            'IS_ALL_CATS_SELECTED' => ($idcat == '-1') ? ' selected="selected"' : '',
		));
		if (is_array($CAT_FORUM))
		{
			foreach ($CAT_FORUM as $id => $key)
			{
				if ($User->check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM))
				{
					$Tpl->assign_block_vars('cats', array(
                        'MARGIN' => ($key['level'] > 0) ? str_repeat('----------', $key['level']) : '----',
                        'ID' => $id,
                        'L_NAME' => $key['name'],
                        'IS_SELECTED' => ($id == $idcat) ? ' selected="selected"' : ''
                        ));
				}
			}
		}
		return $Tpl->render();
	}

	public function get_search_args()
	/**
	 *  Renvoie la liste des arguments de la m�thode <get_search_args>
	 */
	{
		return Array('ForumTime', 'ForumIdcat', 'ForumWhere', 'ForumColorate_result');
	}

	public function get_search_request($args)
	/**
	 *  Renvoie la requ�te de recherche dans le forum
	 */
	{
		global $CAT_FORUM, $User, $Cache;
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		$Cache->load('forum');

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time = !empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 0;
		$where = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'title';
		$colorate_result = !empty($args['ForumColorate_result']) ? true : false;

		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		$auth_cats = '';
		if (is_array($CAT_FORUM))
		{
			foreach ($CAT_FORUM as $id => $key)
			{
				if (!$User->check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM))
				$auth_cats .= $id.',';
			}
		}
		$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

		if ($where == 'all')         // All
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(( 2 * FT_SEARCH_RELEVANCE(t.title, '" . $search."') + FT_SEARCH_RELEVANCE(msg.contents, '" . $search."') ) / 3) * " . $weight . " AS `relevance`,
                " . $this->sql_querier->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE ( FT_SEARCH(t.title, '" . $search."') OR FT_SEARCH(msg.contents, '" . $search."') ) AND msg.timestamp > '" . (time() - $time) . "'
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $this->sql_querier->limit(0, FORUM_MAX_SEARCH_RESULTS);

		if ($where == 'contents')    // Contents
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(FT_SEARCH_RELEVANCE(msg.contents, '" . $search."')) * " . $weight . " AS `relevance`,
                " . $this->sql_querier->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE FT_SEARCH(msg.contents, '" . $search."') AND msg.timestamp > '" . (time() - $time) . "'
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $this->sql_querier->limit(0, FORUM_MAX_SEARCH_RESULTS);
		else                                         // Title only
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                msg.id AS `id_content`,
                t.title AS `title`,
                FT_SEARCH_RELEVANCE(t.title, '" . $search."') * " . $weight . " AS `relevance`,
                " . $this->sql_querier->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE FT_SEARCH(t.title, '" . $search."') AND msg.timestamp > '" . (time() - $time) . "'
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $this->sql_querier->limit(0, FORUM_MAX_SEARCH_RESULTS);
	}



	/**
	 * @desc Return the array containing the result's data list
	 * @param &string[][] $args The array containing the result's id list
	 * @return string[] The array containing the result's data list
	 */
	public function compute_search_results($args)
	{
		$results_data = array();

		$results =& $args['results'];
		$nb_results = count($results);

		$ids = array();
		for ($i = 0; $i < $nb_results; $i++)
		$ids[] = $results[$i]['id_content'];

		$request = "
        SELECT
            msg.id AS msg_id,
            msg.user_id AS user_id,
            msg.idtopic AS topic_id,
            msg.timestamp AS date,
            t.title AS title,
            m.login AS login,
            ext_field.user_avatar AS avatar,
            s.user_id AS connect,
            msg.contents AS contents
        FROM " . PREFIX . "forum_msg msg
        LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id != -1
        LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
        LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
        JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
        WHERE msg.id IN (".implode(',', $ids).")
        GROUP BY t.id";

		$request_results = $this->sql_querier->query_while ($request, __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($request_results))
		{
			$results_data[] = $row;
		}
		$this->sql_querier->query_close($request_results);

		return $results_data;
	}

	/**
	 *  @desc Return the string to print the result
	 *  @param &string[] $result_data the result's data
	 *  @return string[] The string to print the result of a search element
	 */
	public function parse_search_result($result_data)
	{
		global $LANG;

		load_module_lang('forum'); //Chargement de la langue du module.

		$tpl = new FileTemplate('forum/forum_generic_results.tpl');

		$tpl->put_all(Array(
            'L_ON' => $LANG['on'],
            'L_TOPIC' => $LANG['topic']
		));
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($result_data['title']) : '';
		$tpl->put_all(array(
            'USER_ONLINE' => '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . ((!empty($result_data['connect']) && $result_data['user_id'] !== -1) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
            'U_USER_PROFILE' => !empty($result_data['user_id']) ? UserUrlBuilder::profile($result_data['user_id'])->absolute() : '',
            'USER_PSEUDO' => !empty($result_data['login']) ? TextHelper::wordwrap_html($result_data['login'], 13) : $LANG['guest'],
            'U_TOPIC' => PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $result_data['topic_id'], '-' . $result_data['topic_id'] . $rewrited_title . '.php') . '#m' . $result_data['msg_id'],
            'TITLE' => ucfirst($result_data['title']),
            'DATE' => gmdate_format('d/m/y', $result_data['date']),
            'CONTENTS' => FormatingHelper::second_parse($result_data['contents']),
            'USER_AVATAR' => '<img src="' . (UserAccountsConfig::load()->is_default_avatar_enabled() && !empty($result_data['avatar']) ? $result_data['avatar'] : PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' .  UserAccountsConfig::load()->get_default_avatar_name()) . '" alt="" />'
            ));

            return $tpl->render();
	}
}
?>