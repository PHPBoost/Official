<?php
/*##################################################
 *                               admin_poll.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright            : (C) 2005 Viarre R�gis
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
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//On recup�re les variables.
$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$del = !empty($_GET['delete']) ? true : false;

$poll_config = PollConfig::load();

//Liste des sondages affich�s dans le mini module
$config_displayed_in_mini_module_list = $poll_config->get_displayed_in_mini_module_list();

if ($del && !empty($id)) //Suppresion poll
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$Cache->load('poll');
	
	//On supprime des tables config et reponses des polls.
	PersistenceContext::get_querier()->delete(PREFIX . 'poll', 'WHERE id=:id', array('id' => $id));
	
	###### R�g�n�ration du cache si le sondage fait parti de la liste des sondages affich�s dans le mini-module #######
	if (in_array($id, $config_displayed_in_mini_module_list))
	{
		$displayed_in_mini_module_list = $config_displayed_in_mini_module_list;
		unset($displayed_in_mini_module_list[array_search($id, $displayed_in_mini_module_list)]);
		
		$poll_config->set_displayed_in_mini_module_list($displayed_in_mini_module_list);
		
		PollConfig::save();
		
		$Cache->Generate_module_file('poll');
	}
	AppContext::get_response()->redirect('/poll/admin_poll.php');
}
elseif (!empty($_POST['valid']) && !empty($id_post)) //inject
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$Cache->load('poll');
	
	$question = retrieve(POST, 'question', '');
	$type = retrieve(POST, 'type', 0);
	$archive = retrieve(POST, 'archive', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);
	$get_visible = retrieve(POST, 'visible', 0);
	
	//On verifie les conditions!
	if (!empty($question) && !empty($id_post))
	{
		$start_date = new Date($start);
		$end_date = new Date($end);
		
		$start_timestamp = $start_date->get_timestamp();
		$end_timestamp = $end_date->get_timestamp();
		
		$visible = 1;
		if ($get_visible == 2)
		{
			if ($start_timestamp > time())
				$visible = 2;
			elseif ($start_timestamp == 0)
				$visible = 1;
			else //Date inf�rieur � celle courante => inutile.
				$start_timestamp = 0;

			if ($end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0)
				$visible = 2;
			elseif ($start_timestamp != 0) //Date inf�rieur � celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
		{	
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{	
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		
		$date = new Date($current_date);
		$timestamp = $date->get_timestamp();
		if ($timestamp > 0)
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
		else
			$timestamp = time();
			
		$answers = '';
		$votes = '';
		$check_nbr_answer = 0;
		for ($i = 0; $i < 20; $i++)
		{
			if (!empty($_POST['a'.$i]))
			{
				$answers .= str_replace('|', '', retrieve(POST, 'a'.$i, '')) . '|';
				$votes .= str_replace('|', '', retrieve(POST, 'v'.$i, 0)) . '|';
				$check_nbr_answer++;
			}
		}
		$votes = trim($votes, '|');
		
		PersistenceContext::get_querier()->update(PREFIX . "poll", array('question' => $question, 'answers' => substr($answers, 0, strlen($answers) - 1), 'votes' => $votes, 'type' => $type, 'archive' => $archive, 'visible' => $visible, 'start' => $start_timestamp, 'end' => $start_timestamp, 'timestamp' => $timestamp), 'WHERE id = :id', array('id' => $id_post));
		
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	}
	else
		AppContext::get_response()->redirect('/poll/admin_poll.php?id= ' . $id_post . '&error=incomplete#message_helper');
}	
elseif (!empty($id))
{
	$tpl = new FileTemplate('poll/admin_poll_management2.tpl');

	$row = PersistenceContext::get_querier()->select_single_row(PREFIX . 'poll', array('*'), 'WHERE id=:id', array('id' => $id));

	$tpl->put_all(array(
		'IDPOLL' => $row['id'],
		'QUESTIONS' => $row['question'],
		'TYPE_UNIQUE' => ($row['type'] == '1') ? 'checked="checked"' : '',
		'TYPE_MULTIPLE' => ($row['type'] == '0') ? 'checked="checked"' : '',
		'ARCHIVES_ENABLED' => ($row['archive'] == '1') ? 'checked="checked"' : '',
		'ARCHIVES_DISABLED' => ($row['archive'] == '0') ? 'checked="checked"' : '',	
		'CURRENT_DATE' => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),
		'DAY_RELEASE_S' => !empty($row['start']) ? Date::to_format($row['start'], 'd') : '',
		'MONTH_RELEASE_S' => !empty($row['start']) ? Date::to_format($row['start'], 'm') : '',
		'YEAR_RELEASE_S' => !empty($row['start']) ? Date::to_format($row['start'], 'Y') : '',
		'DAY_RELEASE_E' => !empty($row['end']) ? Date::to_format($row['end'], 'd') : '',
		'MONTH_RELEASE_E' => !empty($row['end']) ? Date::to_format($row['end'], 'm') : '',
		'YEAR_RELEASE_E' => !empty($row['end']) ? Date::to_format($row['end'], 'Y') : '',
		'DAY_DATE' => !empty($row['timestamp']) ? Date::to_format($row['timestamp'], 'd') : '',
		'MONTH_DATE' => !empty($row['timestamp']) ? Date::to_format($row['timestamp'], 'm') : '',
		'YEAR_DATE' => !empty($row['timestamp']) ? Date::to_format($row['timestamp'], 'Y') : '',
		'VISIBLE_WAITING' => (($row['visible'] == 2 || !empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($row['visible'] == 1 && empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($row['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => ((!empty($row['start'])) ? Date::to_format($row['start'], Date::FORMAT_DAY_MONTH_YEAR) : ''),
		'END' => ((!empty($row['end'])) ? Date::to_format($row['end'], Date::FORMAT_DAY_MONTH_YEAR) : ''),
		'HOUR' => Date::to_format($row['timestamp'], 'H'),
		'MIN' => Date::to_format($row['timestamp'], 'i'),
		'DATE' => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),
		'L_REQUIRE_QUESTION' => $LANG['require_question'],
		'L_REQUIRE_ANSWER' => $LANG['require_answer'],
		'L_REQUIRE_ANSWER_TYPE' => $LANG['require_answer_type'],
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_QUESTION' => $LANG['question'],
		'L_ANSWER_TYPE' => $LANG['answer_type'],
		'L_ANSWERS' => $LANG['answers'],
		'L_SINGLE' => $LANG['single'],
		'L_MULTIPLE' => $LANG['multiple'],
		'L_YES' => LangLoader::get_message('yes', 'common'),
		'L_NO' => LangLoader::get_message('no', 'common'),
		'L_NUMBER_VOTE' => $LANG['number_vote'],
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_POLL_DATE' => $LANG['poll_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['incomplete'], MessageHelper::NOTICE));
	
	$array_answer = explode('|', $row['answers']);
	$array_vote = explode('|', $row['votes']);
	
	$sum_vote = array_sum($array_vote);
	$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Emp�che la division par 0.
	
	//Liste des choix des sondages => 20 maxi
	$i = 0;
	$array_poll = array_combine($array_answer, $array_vote);
	foreach ($array_poll as $answer => $nbrvote)
	{
		$percent = NumberHelper::round(($nbrvote * 100 / $sum_vote), 1);
		$tpl->assign_block_vars('answers', array(
			'ID' => $i,
			'ANSWER' => !empty($answer) ? $answer : ''
		));
		$tpl->assign_block_vars('votes', array(
			'ID' => $i,
			'VOTES' => isset($nbrvote) ? $nbrvote : '',
			'PERCENT' => isset($percent) ? $percent . '%' : ''
		));
		$i++;
	}
	
	$tpl->put_all(array(
		'MAX_ID' => $i
	));
	
	$tpl->display();
}
else
{
	$_NBR_ELEMENTS_PER_PAGE = 20;
	
	$tpl = new FileTemplate('poll/admin_poll_management.tpl');
	 
	$nbr_poll = PersistenceContext::get_querier()->count(PREFIX . 'poll');

	//On cr�e une pagination si le nombre de sondages est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_poll, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/poll/admin_poll.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$tpl->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_QUESTION' => $LANG['question'],
		'L_POLLS' => $LANG['polls'],
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_PSEUDO' => LangLoader::get_message('display_name', 'user-common'),
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update']
	)); 

	$result = PersistenceContext::get_querier()->select("SELECT p.id, p.question, p.archive, p.timestamp, p.visible, p.start, p.end, p.user_id, m.display_name, m.groups, m.level
	FROM " . PREFIX . "poll p
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON p.user_id = m.user_id
	ORDER BY p.timestamp DESC 
	LIMIT :number_items_per_page OFFSET :display_from",
		array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)
	);
	while ($row = $result->fetch())
	{
		if ($row['visible'] == 2)
			$aprob = $LANG['waiting'];
		elseif ($row['visible'] == 1)
			$aprob = LangLoader::get_message('yes', 'common');
		else
			$aprob = LangLoader::get_message('no', 'common');
			
		$archive = ( $row['archive'] == 1) ?  LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common');
		
		//On reccourci le lien si il est trop long pour �viter de d�former l'administration.
		$question = strlen($row['question']) > 45 ? substr($row['question'], 0, 45) . '...' : $row['question'];
		
		$visible = '';
		if ($row['start'] > 0)
			$visible .= Date::to_format($row['start'], Date::FORMAT_DAY_MONTH_YEAR);
		if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . Date::to_format($row['end'], Date::FORMAT_DAY_MONTH_YEAR);
		elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . Date::to_format($row['end'], Date::FORMAT_DAY_MONTH_YEAR);
		
		$group_color = User::get_group_color($row['groups'], $row['level']);
		
		$tpl->assign_block_vars('questions', array(
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'QUESTIONS' => $question,
			'IDPOLL' => $row['id'],
			'PSEUDO' => $row['display_name'],
			'USER_GROUP_COLOR' => $group_color,
			'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
			'DATE' => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),
			'ARCHIVES' => $archive,
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : ''),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
		));
	}
	$result->dispose();	
	
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>