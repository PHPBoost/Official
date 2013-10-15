<?php
/*##################################################
 *                               management.php
 *                            -------------------
 *   begin                :  August 13, 2008
 *   copyright            : (C) 2008 Viarre R�gis
 *   email                : regis.viarre@phpboost.com
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

require_once('../kernel/begin.php');
require_once('news_begin.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$now = new Date(DATE_NOW, TIMEZONE_AUTO);

$news_categories = new NewsCats();

$new = retrieve(GET, 'new', 0);
$cat = retrieve(GET, 'cat', 0);
$edit = retrieve(GET, 'edit', 0);
$delete = retrieve(GET, 'del', 0);

if ($delete > 0)
{
	$Session->csrf_get_protect();
	$news = $Sql->query_array(DB_TABLE_NEWS, '*', "WHERE id = '" . $delete . "'", __LINE__, __FILE__);

	if (!empty($news['id']) && ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id')))
	{
		$Sql->query_inject("DELETE FROM " . DB_TABLE_NEWS . " WHERE id = '" . $delete . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . DB_TABLE_EVENTS . " WHERE module = 'news' AND id_in_module = '" . $delete . "'", __LINE__, __FILE__);

		CommentsService::delete_comments_topic_module('news', $news['id']);
	    
	    Feed::clear_cache('news');

		AppContext::get_response()->redirect('news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php'));
	}
	elseif (empty($news['id']))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_news']);
        DispatchManager::redirect($controller);
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($_POST['submit']))
{
	$sources = array();
	for ($i = 0; $i < 50; $i++)
	{	
		if (retrieve(POST,'name'.$i,false,TSTRING))
		{	
			$sources[$i]['name'] = retrieve(POST, 'name'.$i, '');
			$sources[$i]['url'] = retrieve(POST, 'url'.$i, '');
		}
	}
	
	$release = MiniCalendar::retrieve_date('release');
	
	$news = array(
		'id' => retrieve(POST, 'id', 0, TINTEGER),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'user_id' => retrieve(POST, 'user_id', 0, TINTEGER),
		'title' => retrieve(POST, 'title', '', TSTRING),
		'desc' => retrieve(POST, 'contents', '', TSTRING_PARSE),
		'extend_desc' => retrieve(POST, 'extend_contents', '', TSTRING_PARSE),
		'counterpart' => retrieve(POST, 'counterpart', '', TSTRING_PARSE),
		'visible' => retrieve(POST, 'visible', 0, TINTEGER),
		'release' => $release->get_timestamp(),
		'release_hour' => retrieve(POST, 'release_hour', 0, TINTEGER),
		'release_min' => retrieve(POST, 'release_min', 0, TINTEGER),
		'img' => retrieve(POST, 'img', '', TSTRING),
		'alt' => retrieve(POST, 'alt', '', TSTRING),
		'sources' => addslashes(serialize($sources))
	);
	
	$start = MiniCalendar::retrieve_date('start');
	$start_hour = retrieve(POST, 'start_hour', 0, TINTEGER);
	$start_minutes = retrieve(POST, 'start_min', 0, TINTEGER);
	
	$end = MiniCalendar::retrieve_date('end');
	$end_hour = retrieve(POST, 'end_hour', 0, TINTEGER);
	$end_minutes = retrieve(POST, 'end_min', 0, TINTEGER);
	
	$news['start'] = $start->get_timestamp() + 3600 * $start_hour + 60 * $start_minutes;
	$news['end'] = $end->get_timestamp() + 3600 * $end_hour + 60 * $end_minutes;
	

	if (($news['id'] > 0 && ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_CONTRIBUTE))) || ($news['id'] == 0 && ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_CONTRIBUTE))))
	{
		// Errors.
		if (empty($news['title']))
		{
			$Template->put('message_helper', MessageHelper::display($LANG['e_require_title'], E_USER_NOTICE));
		}
		elseif (empty($news['desc']))
		{
			$Template->put('message_helper', MessageHelper::display($LANG['e_require_desc'], E_USER_NOTICE));
		}
		else
		{
			// $start & $end.
			if ($news['visible'] == 2)
			{
				// Start.
				if ($news['start'] <= $now->get_timestamp() && $news['end'] <= $now->get_timestamp())
				{
					$news['start'] = $news['end'] = 0;
					$news['visible'] = 1;
				}
				else
				{
					// End.
					if ($news['end'] <= $now->get_timestamp())
					{
						$news['end'] = 0;
					}
				}
			}
			else
			{
				$news['start'] = $news['end'] = 0;
			}

			// Release.
			$news['release'] += ($news['release_hour'] * 60 + $news['release_min']) * 60;
			if ($news['release'] == 0)
			{
				$news['release'] = $now->get_timestamp();
			}

			// Image.
			$img = new Url($news['img']);

			if ($news['id'] > 0)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_NEWS . " SET idcat = '" . $news['idcat'] . "', title = '" . $news['title'] . "', contents = '" . $news['desc'] . "', extend_contents = '" . $news['extend_desc'] . "', img = '" . $img->relative() . "', alt = '" . $news['alt'] . "', visible = '" . $news['visible'] . "', start = '" .  $news['start'] . "', end = '" . $news['end'] . "', timestamp = '" . $news['release'] . "', sources = '" . $news['sources'] . "'
				WHERE id = '" . $news['id'] . "'", __LINE__, __FILE__);

				if ($news['visible'])
				{
					$corresponding_contributions = ContributionService::find_by_criteria('news', $news['id']);

					if (count($corresponding_contributions) > 0)
					{
						$news_contribution = $corresponding_contributions[0];
						$news_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

						ContributionService::save_contribution($news_contribution);
					}
				}
			}
			else
			{
				$auth_contrib = !$User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_CONTRIBUTE);

				$news['visible'] = $auth_contrib ? 0 : $news['visible'];
				
				$Sql->query_inject("INSERT INTO " . DB_TABLE_NEWS . " (idcat, title, contents, extend_contents, timestamp, visible, start, end, user_id, img, alt, sources)
				VALUES('" . $news['idcat'] . "', '" . $news['title'] . "', '" . $news['desc'] . "', '" . $news['extend_desc'] . "', '" . $news['release'] . "', '" . $news['visible'] . "', '" . $news['start'] . "', '" . $news['end'] . "', '" . $User->get_attribute('user_id') . "', '" . $img->relative() . "', '" . $news['alt'] . "', '" . $news['sources'] . "')", __LINE__, __FILE__);

				$news['id'] = $Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_NEWS);

				//If the poster couldn't write, it's a contribution and we put it in the contribution panel, it must be approved
				if ($auth_contrib)
				{
					//Importing the contribution classes
					$news_contribution = new Contribution();

					//The id of the file in the module. It's useful when the module wants to search a contribution (we will need it in the file edition)
					$news_contribution->set_id_in_module($news['id']);
					//The description of the contribution (the counterpart) to explain why did the contributor contributed
					$news_contribution->set_description(stripslashes($news['counterpart']));
					//The entitled of the contribution
					$news_contribution->set_entitled(sprintf($NEWS_LANG['contribution_entitled'], $news['title']));
					//The URL where a validator can treat the contribution (in the file edition panel)
					$news_contribution->set_fixing_url('/news/management.php?edit=' . $news['id']);
					//Who is the contributor?
					$news_contribution->set_poster_id($User->get_attribute('user_id'));
					//The module
					$news_contribution->set_module('news');
					//Assignation des autorisations d'�criture / Writing authorization assignation
					$news_contribution->set_auth(
						//On d�place le bit sur l'autorisation obtenue pour le mettre sur celui sur lequel travaille les contributions, � savoir Contribution::CONTRIBUTION_AUTH_BIT
						//We shift the authorization bit to the one with which the contribution class works, Contribution::CONTRIBUTION_AUTH_BIT
						Authorizations::capture_and_shift_bit_auth(
							//On fusionne toutes les autorisations pour obtenir l'autorisation d'�criture dans la cat�gorie s�lectionn�e :
							//C'est la fusion entre l'autorisation de la racine et de l'ensemble de la branche des cat�gories
							//We merge the whole authorizations of the branch constituted by the selected category
							Authorizations::merge_auth(
								$NEWS_CONFIG['global_auth'],
								//Autorisation de l'ensemble de la branche des cat�gories jusqu'� la cat�gorie demand�e
								$news_categories->compute_heritated_auth($news['idcat'], AUTH_NEWS_MODERATE, Authorizations::AUTH_CHILD_PRIORITY),
								AUTH_NEWS_MODERATE, Authorizations::AUTH_CHILD_PRIORITY
							),
							AUTH_NEWS_MODERATE, Contribution::CONTRIBUTION_AUTH_BIT
						)
					);

					//Sending the contribution to the kernel. It will place it in the contribution panel to be approved
					ContributionService::save_contribution($news_contribution);

					//Redirection to the contribution confirmation page
					AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
				}
			}

			// Feeds Regeneration
			
			Feed::clear_cache('news');

			if ($news['visible'] && $news['start'] == 0)
			{
				AppContext::get_response()->redirect('news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . Url::encode_rewrite($news['title']) . '.php'));
			}
			else
			{
				AppContext::get_response()->redirect(url('news.php'));
			}
		}
	}
	else
	{
	   $error_controller = PHPBoostErrors::user_not_authorized();
	   DispatchManager::redirect($error_controller);
	}
}
else
{
	$tpl = new FileTemplate('news/management.tpl');

	if ($edit > 0)
	{
		$news = $Sql->query_array(DB_TABLE_NEWS, '*', "WHERE id = '" . $edit . "'", __LINE__, __FILE__);
		
		$auth_edit = $news['idcat'] > 0 ? $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id') : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id');
		
		if (!empty($news['id']) && $auth_edit)
		{
			define('TITLE', $NEWS_LANG['edit_news'] . ' : ' . addslashes($news['title']));
			$news_categories->bread_crumb($news['idcat']);
			$Bread_crumb->add($news['title'], 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . Url::encode_rewrite($news['title']) . '.php'));
			$Bread_crumb->add($NEWS_LANG['edit_news'], url('management.php?edit=' . $news['id']));
			
			// Calendrier.
			$start_calendar = new MiniCalendar('start');
			$start = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, ($news['start'] > 0 ? $news['start'] : $now->get_timestamp()));
			$start_calendar->set_date($start);
			$end_calendar = new MiniCalendar('end');
			$end = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, ($news['end'] > 0 ? $news['end'] : $now->get_timestamp()));
			$end_calendar->set_date($end);
			$end_calendar->set_style('margin-left:150px;');
			$release_calendar = new MiniCalendar('release');
			$release = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, ($news['timestamp'] > 0 ? $news['timestamp'] : $now->get_timestamp()));
			$release_calendar->set_date($release);
			
			$sources = unserialize($news['sources']);

			$i = 0;
			foreach ($sources as $value)
			{	
				$tpl->assign_block_vars('sources', array(
					'I' => $i,
					'NAME' => stripslashes($value['name']),
					'URL' => stripslashes($value['url'])
				));
				$i++;
			}	
			if($i==0)
			{
				$tpl->assign_block_vars('sources', array(
					'I' => 0,
					'NAME' => '',
					'URL' => ''
				));
			}
			
			$tpl->put_all(array(
				'C_ADD' => true,
				'C_CONTRIBUTION' => false,
				'JS_CONTRIBUTION' => 'false',
				'RELEASE_CALENDAR_ID' => $release_calendar->get_html_id(),
				'TITLE' => $news['title'],
				'NB_FIELDS_SOURCES' => $i == 0 ? 1 : $i,
				'CONTENTS' => FormatingHelper::unparse($news['contents']),
				'EXTEND_CONTENTS' => FormatingHelper::unparse($news['extend_contents']),
				'VISIBLE_WAITING' => $news['visible'] && (!empty($news['start']) || !empty($news['end'])),
				'VISIBLE_ENABLED' => $news['visible'] && empty($news['start']) && empty($news['end']),
				'VISIBLE_UNAPROB' => !$news['visible'],
				'START_CALENDAR' => $start_calendar->display(),
				'START_HOUR' => !empty($news['start']) ? $start->get_hours() : '',
				'START_MIN' => !empty($news['start']) ? $start->get_minutes() : '',
				'END_CALENDAR' => $end_calendar->display(),
				'END_HOUR' => !empty($news['end']) ? $end->get_hours() : '',
				'END_MIN' => !empty($news['end']) ? $end->get_minutes() : '',
				'RELEASE_CALENDAR' => $release_calendar->display(),
				'RELEASE_HOUR' => !empty($news['timestamp']) ? $release->get_hours() : '',
				'RELEASE_MIN' => !empty($news['timestamp']) ? $release->get_minutes() : '',
				'IMG_PREVIEW' => FormatingHelper::second_parse_url($news['img']),
				'IMG' => $news['img'],
				'ALT' => $news['alt'],
				'IDNEWS' => $news['id'],
				'USER_ID' => $news['user_id']
			));

			$news_categories->build_select_form($news['idcat'], 'idcat', 'idcat', 0, AUTH_NEWS_READ, $NEWS_CONFIG['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $tpl);
		}
		else
		{
	       $error_controller = PHPBoostErrors::user_not_authorized();
	       DispatchManager::redirect($error_controller);
		}
	}
	else
	{
		if (!$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE) && !$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE))
		{
	       $error_controller = PHPBoostErrors::user_not_authorized();
	       DispatchManager::redirect($error_controller);
		}
		else
		{
			$auth_contrib = !$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE) && $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE);
			$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));
			$Bread_crumb->add($NEWS_LANG['add_news'], url('management.php?new=1'));
			define('TITLE', $NEWS_LANG['add_news']);

			// Calendrier.
			$now = new Date(DATE_NOW, TIMEZONE_SYSTEM);
			$start_calendar = new MiniCalendar('start');
			$start_calendar->set_date($now);
			$end_calendar = new MiniCalendar('end');
			$end_calendar->set_date($now);
			$end_calendar->set_style('margin-left:150px;');
			$release_calendar = new MiniCalendar('release');
			$release_calendar->set_date($now);

			$tpl->put_all(array(
				'C_ADD' => false,
				'C_CONTRIBUTION' => $auth_contrib,
				'JS_CONTRIBUTION' => $auth_contrib ? 'true' : 'false',
				'RELEASE_CALENDAR_ID' => $release_calendar->get_html_id(),
				'TITLE' => '',
				'CONTENTS' => '',
				'NB_FIELDS_SOURCES' => 1,
				'EXTEND_CONTENTS' => '',
				'VISIBLE_WAITING' => 0,
				'VISIBLE_ENABLED' => 1,
				'VISIBLE_UNAPROB' => 0,
				'START_CALENDAR' => $start_calendar->display(),
				'START_HOUR' => '',
				'START_MIN' => '',
				'END_CALENDAR' => $end_calendar->display(),
				'END_HOUR' => '',
				'END_MIN' => '',
				'RELEASE_CALENDAR' => $release_calendar->display(),
				'RELEASE_HOUR' => $now->get_hours(),
				'RELEASE_MIN' => $now->get_minutes(),
				'IMG' => '',
				'ALT' => '',
				'IDNEWS' => '0',
				'USER_ID' => $User->get_attribute('user_id')
			));

			$tpl->assign_block_vars('sources', array(
				'I' => 0,
				'NAME' => '',
				'URL' => ''
			));

			
			$cat = $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_WRITE) ? $cat : 0;
			$news_categories->build_select_form($cat, 'idcat', 'idcat', 0, AUTH_NEWS_READ, $NEWS_CONFIG['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $tpl);
		}
	}
	require_once('../kernel/header.php');

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$extend_contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$extend_contents_editor->set_identifier('extend_contents');
	
	$counterpart_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$counterpart_editor->set_identifier('counterpart');
	
	$tpl->put_all(array(
		'NOW_DATE' => $now->format(DATE_FORMAT_SHORT, TIMEZONE_SYSTEM),
		'NOW_HOUR' => $now->get_hours(),
		'NOW_MIN' => $now->get_minutes(),
		'L_NAME_SOURCES' => $NEWS_LANG['name_sources'],
		'L_URL_SOURCES' => $NEWS_LANG['url_sources'],
		'L_ADD_SOURCES' => $NEWS_LANG['add_sources'],
		'L_ADD_EDIT_NEWS' => $edit > 0 ? $NEWS_LANG['edit_news'] : $NEWS_LANG['add_news'],
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE_NEWS' => $NEWS_LANG['title_news'],
		'L_CATEGORY' => $NEWS_LANG['cat_news'],
		'L_DESC' => $NEWS_LANG['desc_news'],
		'L_DESC_EXTEND' => $NEWS_LANG['desc_extend_news'],
		'KERNEL_EDITOR' => $editor->display(),
		'KERNEL_EDITOR_EXTEND' => $extend_contents_editor->display(),
		'CONTRIBUTION_COUNTERPART_EDITOR' => $counterpart_editor->display(),
		'L_TO_DATE' => $LANG['to_date'],
		'L_FROM_DATE' => $LANG['from_date'],
		'L_AT' => $LANG['at'],
		'L_RELEASE_DATE' => $NEWS_LANG['release_date'],
		'L_NEWS_DATE' => $NEWS_LANG['news_date'],
		'L_UNIT_HOUR' => strtolower($LANG['unit_hour']),
		'L_IMMEDIATE' => $LANG['now'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_IMG_MANAGEMENT' => $NEWS_LANG['img_management'],
		'L_PREVIEW_IMG' => $NEWS_LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $NEWS_LANG['preview_image_explain'],
		'L_IMG_LINK' => $NEWS_LANG['img_link'],
		'L_IMG_DESC' => $NEWS_LANG['img_desc'],
		'L_BB_UPLOAD' => LangLoader::get_message('bb_upload', 'editor-common'),
		'L_CONTRIBUTION_LEGEND' => $LANG['contribution'],
		'L_NOTICE_CONTRIBUTION' => $NEWS_LANG['notice_contribution'],
		'L_CONTRIBUTION_COUNTERPART' => $NEWS_LANG['contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $NEWS_LANG['contribution_counterpart_explain'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));

	$tpl->display();
}

require_once('../kernel/footer.php');

?>
