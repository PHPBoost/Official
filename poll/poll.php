<?php
/*##################################################
 *                               poll.php
 *                            -------------------
 *   begin                : July 14, 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('../poll/poll_begin.php'); 
require_once('../kernel/header.php'); 

$poll = array();
$poll_id = retrieve(GET, 'id', 0);

$now = new Date(DATE_NOW, TIMEZONE_AUTO);

if (!empty($poll_id))
{
	$poll = $Sql->query_array(PREFIX . 'poll', 'id', 'question', 'votes', 'answers', 'type', 'timestamp', "WHERE id = '" . $poll_id . "' AND archive = 0 AND visible = 1 AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)");
	
	//Pas de sondage trouv� => erreur.
	if (empty($poll['id']))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
            $LANG['e_unexist_poll']);
        DispatchManager::redirect($controller);
	}
}	
	
$archives = retrieve(GET, 'archives', false); //On v�rifie si on est sur les archives
$show_result = retrieve(GET, 'r', false); //Affichage des r�sultats.
$now = new Date(DATE_NOW, TIMEZONE_AUTO);

//R�cup�ration des �l�ments de configuration
$config_cookie_name = $poll_config->get_cookie_name();
$config_cookie_lenght = $poll_config->get_cookie_lenght_in_seconds();
$config_displayed_in_mini_module_list = $poll_config->get_displayed_in_mini_module_list();

if (!empty($_POST['valid_poll']) && !empty($poll['id']) && !$archives)
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}
	
	//Autorisation de voter
	if (PollAuthorizationsService::check_authorizations()->write())
	{
		//On note le passage du visiteur par un cookie.
		if (AppContext::get_request()->has_cookieparameter($config_cookie_name)) //Recherche dans le cookie existant.
		{
			$array_cookie = explode('/', AppContext::get_request()->get_cookie($config_cookie_name));
			if (in_array($poll['id'], $array_cookie))
				$check_cookie = true;
			else
			{
				$check_cookie = false;
				
				$array_cookie[] = $poll['id']; //Ajout nouvelle valeur.
				$value_cookie = implode('/', $array_cookie); //On retransforme le tableau en cha�ne.
	
				AppContext::get_response()->set_cookie(new HTTPCookie($config_cookie_name, $value_cookie, time() + $config_cookie_lenght));
			}
		}
		else //G�n�ration d'un cookie.
		{	
			$check_cookie = false;
			AppContext::get_response()->set_cookie(new HTTPCookie($config_cookie_name, $poll['id'], time() + $config_cookie_lenght));
		}
		
		$check_bdd = true;
		if (Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $poll_config->get_authorizations(), PollAuthorizationsService::WRITE_AUTHORIZATIONS)) //Autoris� aux visiteurs, on filtre par ip => fiabilit� moyenne.
		{
			//Injection de l'adresse ip du visiteur dans la bdd.	
			$ip = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE ip = '" . AppContext::get_request()->get_ip_address() . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
			if (empty($ip))
			{
				//Insertion de l'adresse ip.
				$Sql->query_inject("INSERT INTO " . PREFIX . "poll_ip (ip, user_id, idpoll, timestamp) VALUES('" . AppContext::get_request()->get_ip_address() . "', -1, '" . $poll['id'] . "', '" . time() . "')");
				$check_bdd = false;
			}
		}
		else //Autoris� aux membres, on filtre par le user_id => fiabilit� 100%.
		{
			//Injection de l'adresse ip du visiteur dans la bdd.	
			$user_id = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE user_id = '" . AppContext::get_current_user()->get_id() . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
			if (empty($user_id))
			{
				//Insertion de l'adresse ip.
				$Sql->query_inject("INSERT INTO " . PREFIX . "poll_ip (ip, user_id, idpoll, timestamp) VALUES('" . AppContext::get_request()->get_ip_address() . "', '" . AppContext::get_current_user()->get_id() . "', '" . $poll['id'] . "', '" . time() . "')");
				$check_bdd = false;
			}
		}
		
		//Si le cookie n'existe pas et l'ip n'est pas connue on enregistre.
		if ($check_bdd || $check_cookie)
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_already_vote', '-' . $poll['id'] . '.php?error=e_already_vote', '&') . '#message_helper');
		
		//R�cup�ration du vote.
		$check_answer = false;
		$array_votes = explode('|', $poll['votes']);
		if ($poll['type'] == '1') //R�ponse unique.
		{	
			$id_answer = retrieve(POST, 'radio', -1);		
			if (isset($array_votes[$id_answer]))
			{
				$array_votes[$id_answer]++;
				$check_answer = true;
			}
		}
		else //R�ponses multiples.
		{
			//On boucle pour v�rifier toutes les r�ponses du sondage.
			$nbr_answer = count($array_votes);
			for ($i = 0; $i < $nbr_answer; $i++)
			{	
				if (isset($_POST[$i]))
				{
					$array_votes[$i]++;
					$check_answer = true;
				}
			}
		}

		if ($check_answer) //Enregistrement vote du sondage
		{
			$Sql->query_inject("UPDATE " . PREFIX . "poll SET votes = '" . implode('|', $array_votes) . "' WHERE id = '" . $poll['id'] . "'");
						
			if (in_array($poll['id'], $config_displayed_in_mini_module_list) ) //Vote effectu� du mini poll => mise � jour du cache du mini poll.
				$Cache->Generate_module_file('poll');
				
			//Tout s'est bien d�roul�, on redirige vers la page des resultats.
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'));
		}	
		else //Vote blanc
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'));
	}
	else
		AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_unauth_poll', '-' . $poll['id'] . '.php?error=e_unauth_poll', '&') . '#message_helper');
}
elseif (!empty($poll['id']) && !$archives) //Affichage du sondage.
{
	$Template->set_filenames(array(
		'poll'=> 'poll/poll.tpl'
	));

	//R�sultats
	$check_bdd = false;
	if (Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $poll_config->get_authorizations(), PollAuthorizationsService::WRITE_AUTHORIZATIONS)) //Autoris� aux visiteurs, on filtre par ip => fiabilit� moyenne.
	{
		//Injection de l'adresse ip du visiteur dans la bdd.	
		$ip = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE ip = '" . AppContext::get_request()->get_ip_address() . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
		if (!empty($ip))
			$check_bdd = true;
	}
	else //Autoris� aux membres, on filtre par le user_id => fiabilit� 100%.
	{
		//Injection de l'adresse ip du visiteur dans la bdd.	
		$user_id = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "poll_ip WHERE user_id = '" . AppContext::get_current_user()->get_id() . "' AND idpoll = '" . $poll['id'] . "'",  __LINE__, __FILE__);		
		if (!empty($user_id))
			$check_bdd = true;
	}
	
	//Gestion des erreurs
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'e_already_vote':
		$errstr = $LANG['e_already_vote'];
		$type = E_USER_WARNING;
		break;
		case 'e_unauth_poll':
		$errstr = $LANG['e_unauth_poll'];
		$type = E_USER_WARNING;
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, $type));
	
	//Si le cookie existe, ou l'ip est connue on redirige vers les resulats, sinon on prend en compte le vote.
	$array_cookie = array();
    if (AppContext::get_request()->has_cookieparameter($config_cookie_name))
    {
    	$array_cookie = explode('/', AppContext::get_request()->get_cookie($config_cookie_name));
    }
	if ($show_result || in_array($poll['id'], $array_cookie) === true || $check_bdd) //R�sultats
	{		
		$array_answer = explode('|', $poll['answers']);
		$array_vote = explode('|', $poll['votes']);
		
		$sum_vote = array_sum($array_vote);
		$Template->put_all(array(
			'C_POLL_VIEW' => true,
			'C_IS_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'IDPOLL' => $poll['id'],
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short', $poll['timestamp']),
			'VOTES' => $sum_vote,
			'L_POLL' => $LANG['poll'],
			'L_BACK_POLL' => $LANG['poll_back'],
			'L_VOTE' => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote']),
			'L_ON' => $LANG['on'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete']
		));
		
		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Emp�che la division par 0.
		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$Template->assign_block_vars('result', array(
				'ANSWERS' => $answer, 
				'NBRVOTE' => (int)$nbrvote,
				'WIDTH' => NumberHelper::round(($nbrvote * 100 / $sum_vote), 1) * 4, //x 4 Pour agrandir la barre de vote.					
				'PERCENT' => NumberHelper::round(($nbrvote * 100 / $sum_vote), 1)
			));
		}

		$Template->pparse('poll');
	}
	else //Questions.
	{
		$Template->put_all(array(
			'C_POLL_VIEW' => true,
			'C_POLL_QUESTION' => true,
			'C_IS_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'IDPOLL' => $poll['id'],
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short'),
			'VOTES' => 0,
			'ID_R' => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'QUESTION' => $poll['question'],
			'DATE' => gmdate_format('date_format_short', $poll['timestamp']),
			'U_POLL_ACTION' => url('.php?id=' . $poll['id'] . '&amp;token=' . AppContext::get_session()->get_token(), '-' . $poll['id'] . '.php?token=' . AppContext::get_session()->get_token()),
			'U_POLL_RESULT' => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'L_POLL' => $LANG['poll'],
			'L_BACK_POLL' => $LANG['poll_back'],
			'L_VOTE' => $LANG['poll_vote'],
			'L_RESULT' => $LANG['poll_result'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_ON' => $LANG['on']
		));
	
		$z = 0;
		$array_answer = explode('|', $poll['answers']);
		if ($poll['type'] == '1')
		{
			foreach ($array_answer as $answer)
			{						
				$Template->assign_block_vars('radio', array(
					'NAME' => $z,
					'TYPE' => 'radio',
					'ANSWERS' => $answer
				));
				$z++;
			}
		}	
		elseif ($poll['type'] == '0') 
		{
			
			foreach ($array_answer as $answer)
			{						
				$Template->assign_block_vars('checkbox', array(
					'NAME' => $z,
					'TYPE' => 'checkbox',
					'ANSWERS' => $answer
				));
				$z++;	
			}
		}		
		$Template->pparse('poll');
	}
}
elseif ($archives) //Archives.
{
	$_NBR_ELEMENTS_PER_PAGE = 10;
	
	$Template->set_filenames(array(
		'poll'=> 'poll/poll.tpl'
	));
	
	$nbrarchives = $Sql->query("SELECT COUNT(*) as id FROM " . PREFIX . "poll WHERE archive = 1 AND visible = 1");
	
	//On cr�e une pagination si le nombre de sondages est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbrarchives, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/poll/poll.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$Template->put_all(array(
		'C_POLL_ARCHIVES' => true,
		'C_IS_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'L_ARCHIVE' => $LANG['archives'],
		'L_BACK_POLL' => $LANG['poll_back'],
		'L_ON' => $LANG['on'],
		'L_EDIT' => $LANG['edit'],
		'L_DELETE' => $LANG['delete']
	));	
	
	//On recup�re les sondages archiv�s.
	$result = $Sql->query_while("SELECT id, question, votes, answers, type, timestamp
	FROM " . PREFIX . "poll
	WHERE archive = 1 AND visible = 1
	ORDER BY timestamp DESC
	" . $Sql->limit($pagination->get_display_from(), $_NBR_ELEMENTS_PER_PAGE)); 
	while ($row = $Sql->fetch_assoc($result))
	{
		$array_answer = explode('|', $row['answers']);
		$array_vote = explode('|', $row['votes']);
		
		$sum_vote = array_sum($array_vote);
		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Emp�che la division par 0.

		$Template->assign_block_vars('list', array(
			'ID' => $row['id'],
			'QUESTION' => $row['question'],
			'EDIT' => '<a href="' . PATH_TO_ROOT . '/poll/admin_poll' . url('.php?id=' . $row['id']) . '" title="' . $LANG['edit'] . '" class="fa fa-edit"></a>',
			'DEL' => '&nbsp;&nbsp;<a href="' . PATH_TO_ROOT . '/poll/admin_poll' . url('.php?delete=1&amp;id=' . $row['id']) . '" title="' . $LANG['delete'] . '" class="fa fa-delete" data-confirmation="delete-element"></a>',
			'VOTE' => $sum_vote,
			'DATE' => gmdate_format('date_format'),			
			'L_VOTE' => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
		));		

		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$Template->assign_block_vars('list.result', array(
				'ANSWERS' => $answer, 
				'NBRVOTE' => $nbrvote,
				'WIDTH' => NumberHelper::round(($nbrvote * 100 / $sum_vote), 1) * 4, //x 4 Pour agrandir la barre de vote.					
				'PERCENT' => NumberHelper::round(($nbrvote * 100 / $sum_vote), 1),
				'L_VOTE' => (($nbrvote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
			));
		}
	}
	$Sql->query_close($result);

	$Template->pparse('poll');
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('poll');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}
	
require_once('../kernel/footer.php');

?>