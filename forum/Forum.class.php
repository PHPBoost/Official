<?php
/*##################################################
 *                               forum.class.php
 *                            -------------------
 *   begin                : December 10, 2007
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('NO_HISTORY', false);
define('FORUM_EMAIL_TRACKING', 1);
define('FORUM_PM_TRACKING', 2);

class Forum
{
	## Public Methods ##
	//Constructeur
	function Forum()
	{
	}

	//Ajout d'un message.
	function Add_msg($idtopic, $idcat, $contents, $title, $last_page, $last_page_rewrite, $new_topic = false)
	{
		global $CAT_FORUM, $LANG;

		##### Insertion message #####
		$last_timestamp = time();
		$result = PersistenceContext::get_querier()->insert(PREFIX . 'forum_msg', array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id(), 'contents' => FormatingHelper::strparse($contents), 
			'timestamp' => $last_timestamp, 'timestamp_edit' => 0, 'user_id_edit' => 0, 'user_ip' => AppContext::get_request()->get_ip_address()));
		$last_msg_id = $result->get_last_inserted_id();

		//Topic
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET " . ($new_topic ? '' : 'nbr_msg = nbr_msg + 1, ') . "last_user_id = '" . AppContext::get_current_user()->get_id() . "', last_msg_id = '" . $last_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'");

		//On met � jour le last_topic_id dans la cat�gorie dans le lequel le message a �t� post�, et le nombre de messages..
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $idtopic . "', nbr_msg = nbr_msg + 1" . ($new_topic ? ', nbr_topic = nbr_topic + 1' : '') . " WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'");

		//Mise � jour du nombre de messages du membre.
		PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg + 1 WHERE user_id = '" . AppContext::get_current_user()->get_id() . "'");

		//On marque le topic comme lu.
		mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp);

		##### Gestion suivi du sujet mp/mail #####
		if (!$new_topic)
		{
			//Message pr�c�dent ce nouveau message.
			$previous_msg_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MAX(id)', 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $last_msg_id));

			$title_subject = TextHelper::html_entity_decode($title);
			$title_subject_pm = '<a href="' . HOST . DIR . '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id . '">' . $title_subject . '</a>';
			if (AppContext::get_current_user()->get_id() > 0)
			{
				$pseudo = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'display_name', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id()));
				$pseudo_pm = '<a href="'. UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel() .'">' . $pseudo . '</a>';
			}
			else
			{
				$pseudo = $LANG['guest'];
				$pseudo_pm = $LANG['guest'];
			}
			$next_msg_link = '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id;
			$preview_contents = substr($contents, 0, 300);


			//R�cup�ration des membres suivant le sujet.
			$max_time = time() - SessionsConfig::load()->get_active_session_duration();
			$result = PersistenceContext::get_querier()->select("SELECT m.user_id, m.display_name, m.email, tr.pm, tr.mail, v.last_view_id
			FROM " . PREFIX . "forum_track tr
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = tr.user_id
			LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = :idtopic AND v.user_id = tr.user_id
			WHERE tr.idtopic = :idtopic AND v.last_view_id IS NOT NULL AND m.user_id != :user_id", array(
				'idtopic' => $idtopic,
				'user_id' => AppContext::get_current_user()->get_id()
			));
			while ($row = $result->fetch())
			{
				//Envoi un Mail � ceux dont le last_view_id est le message pr�cedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['mail'] == '1')
				{
					AppContext::get_mail_service()->send_from_properties(
						$row['email'], 
						$LANG['forum_mail_title_new_post'], 
						sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject, AppContext::get_current_user()->get_display_name(), $preview_contents, HOST . DIR . $next_msg_link, HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=1', 1)
					);
				}	
				
				//Envoi un MP � ceux dont le last_view_id est le message pr�cedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['pm'] == '1')
				{
					$content = sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject_pm, AppContext::get_current_user()->get_display_name(), $preview_contents, '<a href="'.$next_msg_link.'">'.$next_msg_link.'</a>', '<a href="' . '/forum/action.php?ut=' . $idtopic . '&trt=2>' . '/forum/action.php?ut=' . $idtopic . '&trt=2</a>'); 
					
					PrivateMsg::start_conversation(
						$row['user_id'], 
						$LANG['forum_mail_title_new_post'], 
						nl2br($content), 
						'-1', 
						PrivateMsg::SYSTEM_PM
					);
				}
			}
			$result->dispose();
			
			forum_generate_feeds(); //Reg�n�ration du flux rss.
		}

		return $last_msg_id;
	}

	//Ajout d'un sujet.
	function Add_topic($idcat, $title, $subtitle, $contents, $type)
	{
		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_topics", array('idcat' => $idcat, 'title' => $title, 'subtitle' => $subtitle, 'user_id' => AppContext::get_current_user()->get_id(), 'nbr_msg' => 1, 'nbr_views' => 0, 'last_user_id' => AppContext::get_current_user()->get_id(), 'last_msg_id' => 0, 'last_timestamp' => time(), 'first_msg_id' => 0, 'type' => $type, 'status' => 1, 'aprob' => 0, 'display_msg' => 0));
		$last_topic_id = $result->get_last_inserted_id(); //Dernier topic inser�

		$last_msg_id = $this->Add_msg($last_topic_id, $idcat, $contents, $title, 0, 0, true); //Insertion du message.
		PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('first_msg_id' => $last_msg_id), 'WHERE id=:id', array('id' => $last_topic_id));

		forum_generate_feeds(); //Reg�n�ration des flux flux

		return array($last_topic_id, $last_msg_id);
	}

	//Edition d'un message.
	function Update_msg($idtopic, $idmsg, $contents, $user_id_msg, $history = true)
	{
		$config = ForumConfig::load();
		
		//Marqueur d'�dition du message?
		$edit_mark = (!ForumAuthorizationsService::check_authorizations()->hide_edition_mark()) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . AppContext::get_current_user()->get_id() . "'" : '';
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . FormatingHelper::strparse($contents) . "'" . $edit_mark . " WHERE id = '" . $idmsg . "'");

		$nbr_msg_before = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $idmsg));

		//Calcul de la page sur laquelle se situe le message.
		$msg_page = ceil( ($nbr_msg_before + 1) / $config->get_number_messages_per_page() );
		$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
		$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
			
		//Insertion de l'action dans l'historique.
		if (AppContext::get_current_user()->get_id() != $user_id_msg && $history)
		forum_history_collector(H_EDIT_MSG, $user_id_msg, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $idmsg);

		return $nbr_msg_before;
	}

	//Edition d'un sujet.
	function Update_topic($idtopic, $idmsg, $title, $subtitle, $contents, $type, $user_id_msg)
	{
		//Mise � jour du sujet.
		PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('title' => $title, 'subtitle' => $subtitle, 'type' => $type), 'WHERE id=:id', array('id' => $idtopic));
		//Mise � jour du contenu du premier message du sujet.
		$this->Update_msg($idtopic, $idmsg, $contents, $user_id_msg, NO_HISTORY);

		//Insertion de l'action dans l'historique.
		if (AppContext::get_current_user()->get_id() != $user_id_msg)
		forum_history_collector(H_EDIT_TOPIC, $user_id_msg, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Supression d'un message.
	function Del_msg($idmsg, $idtopic, $idcat, $first_msg_id, $last_msg_id, $last_timestamp, $msg_user_id)
	{
		global $CAT_FORUM;
		
		$config = ForumConfig::load();
		
		if ($first_msg_id != $idmsg) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprim�.
			$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $idmsg));
			//On supprime le message demand�.
			PersistenceContext::get_querier()->delete(PREFIX . 'forum_msg', 'WHERE id=:id', array('id' => $idmsg));
			//On met � jour la table forum_topics.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $idtopic . "'");
			//On retranche d'un messages la cat�gorie concern�e.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'");
			//R�cup�ration du message pr�c�dent celui supprim� afin de rediriger vers la bonne ancre.
			$previous_msg_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg" , 'id', 'WHERE idtopic = :idtopic AND id < :id ORDER BY timestamp DESC', array('idtopic' => $idtopic, 'id' => $idmsg));

			if ($last_msg_id == $idmsg) //On met � jour le dernier message post� dans la liste des topics.
			{
				//On cherche les infos � propos de l'avant dernier message afin de mettre la table forum_topics � jour.
				$id_before_last = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('user_id', 'timestamp'), 'WHERE id=:id', array('id' => $previous_msg_id));
				
				PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('last_user_id' => $id_before_last['user_id'], 'last_msg_id' => $previous_msg_id, 'last_timestamp' => $id_before_last['timestamp']), 'WHERE id=:id', array('id' => $idtopic));

				//On met maintenant a jour le last_topic_id dans les cat�gories.
				$this->Update_last_topic_id($idcat);
			}
				
			//On retire un msg au membre.
			PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg - 1 WHERE user_id = '" . $msg_user_id . "'");
				
			//Mise � jour du dernier message lu par les membres.
			PersistenceContext::get_querier()->update(PREFIX . 'forum_view', array('last_view_id' => $previous_msg_id), 'WHERE last_view_id=:id', array('id' => $idmsg));
			//On marque le topic comme lu, si c'est le dernier du message du topic.
			if ($last_msg_id == $idmsg)
			mark_topic_as_read($idtopic, $previous_msg_id, $last_timestamp);
				
			//Insertion de l'action dans l'historique.
			if ($msg_user_id != AppContext::get_current_user()->get_id())
			{
				//Calcul de la page sur laquelle se situe le message.
				$msg_page = ceil($nbr_msg / $config->get_number_messages_per_page());
				$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
				$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
				forum_history_collector(H_DELETE_MSG, $msg_user_id, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			}
			forum_generate_feeds(); //Reg�n�ration des flux flux
				
			return array($nbr_msg, $previous_msg_id);
		}

		return array(false, false);
	}

	//Suppresion d'un sujet.
	function Del_topic($idtopic, $generate_rss = true)
	{
		global $CAT_FORUM;

		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'user_id'), 'WHERE id=:id', array('id' => $idtopic));
		$topic['user_id'] = (int)$topic['user_id'];

		//On ne supprime pas de msg aux membres ayant post�s dans le topic => trop de requ�tes.
		//On compte le nombre de messages du topic.
		$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));
		$nbr_msg = !empty($nbr_msg) ? NumberHelper::numeric($nbr_msg) : 1;

		//On rippe le topic ainsi que les messages du topic.
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_msg', 'WHERE idtopic=:id', array('id' => $idtopic));
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_topics', 'WHERE id=:id', array('id' => $idtopic));
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_poll', 'WHERE idtopic=:id', array('id' => $idtopic));
		
		//On retranche le nombre de messages et de topic.
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic - 1, nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$topic['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$topic['idcat']]['id_right'] ."' AND level <= '" . $CAT_FORUM[$topic['idcat']]['level'] . "'");

		//On met maintenant a jour le last_topic_id dans les cat�gories.
		$this->Update_last_topic_id($topic['idcat']);

		//Topic supprim�, on supprime les marqueurs de messages lus pour ce topic.
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_view', 'WHERE idtopic=:id', array('id' => $idtopic));

		//On supprime l'alerte.
		$this->Del_alert_topic($idtopic);
		
		//Insertion de l'action dans l'historique.
		if ($topic['user_id'] != AppContext::get_current_user()->get_id())
			forum_history_collector(H_DELETE_TOPIC, $topic['user_id'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));

		if ($generate_rss)
			forum_generate_feeds(); //Reg�n�ration des flux flux
	}

	//Suivi d'un sujet.
	function Track_topic($idtopic, $tracking_type = 0)
	{
		$config = ForumConfig::load();
		
		list($mail, $pm, $track) = array(0, 0, 0);
		if ($tracking_type == 0) //Suivi par email.
			$track = '1';
		elseif ($tracking_type == 1) //Suivi par email.
			$mail = '1';
		elseif ($tracking_type == 2) //Suivi par email.
			$pm = '1';
			
		$exist = PersistenceContext::get_querier()->count(PREFIX . 'forum_track', 'WHERE user_id = :user_id AND idtopic = :idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
		if ($exist == 0)
			PersistenceContext::get_querier()->insert(PREFIX . "forum_track", array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id(), 'track' => $track, 'pm' => $pm, 'mail' => $mail));
		elseif ($tracking_type == 0)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('track' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		elseif ($tracking_type == 1)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('mail' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		elseif ($tracking_type == 2)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('pm' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
			
		//Limite de sujets suivis?
		if (!ForumAuthorizationsService::check_authorizations()->unlimited_topics_tracking())
		{
			//R�cup�re l'id du topic le plus vieux autoris� par la limite de sujet suivis.
			$tracked_topics_number = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as number
			FROM " . PREFIX . "forum_track
			WHERE user_id = :user_id
			ORDER BY id DESC
			LIMIT " . $config->get_max_topic_number_in_favorite(), array(
				'user_id' => AppContext::get_current_user()->get_id()
			));
			
			//Suppression des sujets suivis d�passant le nbr maximum autoris�.
			PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE user_id=:id  AND id < :number', array('id' => AppContext::get_current_user()->get_id(), 'number' => $tracked_topics_number['number']));
		}
	}

	//Retrait du suivi d'un sujet.
	function Untrack_topic($idtopic, $tracking_type = 0)
	{
		if ($tracking_type == 1) //Par mail
		{
			$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("pm", "track"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
			if ($info['track'] == 0 && $info['pm'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('mail' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
		elseif ($tracking_type == 2) //Par mp
		{
			$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("mail", "track"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
			
			if ($info['mail'] == 0 && $info['track'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('pm' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
		else //Suivi
		{
			$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("mail", "pm"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
			
			if ($info['mail'] == 0 && $info['pm'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('track' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
	}

	//Verrouillage d'un sujet.
	function Lock_topic($idtopic)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('status' => 0), 'WHERE id = :id', array('id' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_LOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//D�verrouillage d'un sujet.
	function Unlock_topic($idtopic)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('status' => 1), 'WHERE id = :id', array('id' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_UNLOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//D�placement d'un sujet.
	function Move_topic($idtopic, $idcat, $idcat_dest)
	{
		global $CAT_FORUM;

		//On va chercher le nombre de messages dans la table topics
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("user_id", "nbr_msg"), 'WHERE id=:id', array('id' => $idtopic));
		$topic['nbr_msg'] = !empty($topic['nbr_msg']) ? NumberHelper::numeric($topic['nbr_msg']) : 1;

		//On d�place le topic dans la nouvelle cat�gorie
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('idcat' => $idcat_dest), 'WHERE id = :id', array('id' => $idtopic));

		//On met � jour l'ancienne table
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'");
		//On met maintenant a jour le last_topic_id dans les cat�gories.
		$this->Update_last_topic_id($idcat);

		//On met � jour la nouvelle table
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'");
		//On met maintenant a jour le last_topic_id dans les cat�gories.
		$this->Update_last_topic_id($idcat_dest);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_MOVE_TOPIC, $topic['user_id'], 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//D�placement d'un sujet
	function Cut_topic($id_msg_cut, $idtopic, $idcat, $idcat_dest, $title, $subtitle, $contents, $type, $msg_user_id, $last_user_id, $last_msg_id, $last_timestamp)
	{
		global $$CAT_FORUM;

		//Calcul du nombre de messages d�plac�s.
		$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id >= :id', array('idtopic' => $idtopic, 'id' => $id_msg_cut));
		$nbr_msg = !empty($nbr_msg) ? NumberHelper::numeric($nbr_msg) : 1;

		//Insertion nouveau topic.
		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_topics", array('idcat' => $idcat_dest, 'title' => $title, 'subtitle' => $subtitle, 'user_id' => $msg_user_id, 'nbr_msg' => $nbr_msg, 'nbr_views' => 0, 'last_user_id' => $last_user_id, 'last_msg_id' => $last_msg_id, 'last_timestamp' => $last_timestamp, 'first_msg_id' => $id_msg_cut, 'type' => $type, 'status' => 1, 'aprob' => 0));
		$last_topic_id = $result->get_last_inserted_id(); //Dernier topic inser�

		//Mise � jour du message.
		PersistenceContext::get_querier()->update(PREFIX . "forum_msg", array('contents' => $contents), 'WHERE id = :id', array('id' => $id_msg_cut));
		
		//D�placement des messages.
		PersistenceContext::get_querier()->update(PREFIX . "forum_msg", array('idtopic' => $last_topic_id), 'WHERE idtopic = :idtopic AND id >= :id', array('idtopic' => $idtopic, 'id' => $id_msg_cut));

		//Mise � jour de l'ancien topic
		$previous_topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('id', 'user_id', 'timestamp'), 'WHERE id<:id AND idtopic =:idtopic ORDER BY timestamp DESC LIMIT 0, 1', array('id' => $id_msg_cut, 'idtopic' => $idtopic));
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $idtopic . "'");

		//Mise � jour de l'ancienne cat�gorie, si elle est diff�rente.
		if ($idcat != $idcat_dest)
		{
			//Mise � jour du nombre de messages de la nouvelle cat�gorie, ainsi que du last_topic_id.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'");
			//On met maintenant a jour le last_topic_id dans les cat�gories.
			$this->Update_last_topic_id($idcat_dest);

			//Mise � jour du nombre de messages de l'ancienne cat�gorie, ainsi que du last_topic_id.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'");
		}
		else //Mise � jour du nombre de messages de la cat�gorie, ainsi que du last_topic_id.
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'");

		//On met maintenant a jour le last_topic_id dans les cat�gories.
		$this->Update_last_topic_id($idcat);
			
		//On marque comme lu le message avant le message scind� qui est le dernier message de l'ancienne cat�gorie pour tous les utilisateurs.
		PersistenceContext::get_querier()->update(PREFIX . "forum_view", array('last_view_id' => $previous_topic['id'], 'timestamp' => time()), 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_CUT_TOPIC, 0, 'topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));

		return $last_topic_id;
	}

	//Ajoute une alerte sur un sujet.
	function Alert_topic($alert_post, $alert_title, $alert_contents)
	{
		global $CAT_FORUM, $LANG;

		$topic_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("idcat", "title"), 'WHERE id=:id', array('id' => $alert_post));
		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_alerts", array('idcat' => $topic_infos['idcat'], 'idtopic' => $alert_post, 'title' => $alert_title, 'contents' => $alert_contents, 'user_id' => AppContext::get_current_user()->get_id(), 'status' => 0, 'idmodo' => 0, 'timestamp' => time()));

		$alert_id = $result->get_last_inserted_id();

		$contribution = new Contribution();

		//The id of the file in the module. It's useful when the module wants to search a contribution (we will need it in the file edition)
		$contribution->set_id_in_module($alert_id);
		//The entitled of the contribution
		$contribution->set_entitled(sprintf($LANG['contribution_alert_moderators_for_topics'], stripslashes($alert_title)));
		//The URL where a validator can treat the contribution (in the file edition panel)
		$contribution->set_fixing_url('/forum/moderation_forum.php?action=alert&id=' . $alert_id);
		//Description
		$contribution->set_description(stripslashes($alert_contents));
		//Who is the contributor?
		$contribution->set_poster_id(AppContext::get_current_user()->get_id());
		//The module
		$contribution->set_module('forum');
		//It's an alert, we will be able to manage other kinds of contributions in the module if we choose to use a type.
		$contribution->set_type('alert');

		//Assignation des autorisations d'�criture / Writing authorization assignation
		$contribution->set_auth(
		//On d�place le bit sur l'autorisation obtenue pour le mettre sur celui sur lequel travaille les contributions, � savoir Contribution::CONTRIBUTION_AUTH_BIT
		//We shift the authorization bit to the one with which the contribution class works, Contribution::CONTRIBUTION_AUTH_BIT
		Authorizations::capture_and_shift_bit_auth(
		$CAT_FORUM[$topic_infos['idcat']]['auth'],
		EDIT_CAT_FORUM, Contribution::CONTRIBUTION_AUTH_BIT
		)
		);

		//Sending the contribution to the kernel. It will place it in the contribution panel to be approved
		ContributionService::save_contribution($contribution);
	}

	//Passe en r�solu une alerte sur un sujet.
	function Solve_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_alerts", array('status' => 1, 'idmodo' => AppContext::get_current_user()->get_id()), 'WHERE id = :id', array('id' => $id_alert));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_SOLVE_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert, '', '&');

		//Si la contribution associ�e n'est pas r�gl�e, on la r�gle
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];
			//The contribution is now processed
			$file_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

			//We save the contribution
			ContributionService::save_contribution($file_contribution);
		}
	}

	//Passe en attente une alerte sur un sujet.
	function Wait_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->update(PREFIX . 'forum_alerts', array('status' => 0, 'idmodo' => 0), 'WHERE id=:id', array('id' => $id_alert));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_WAIT_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert);
	}

	//Supprime une alerte sur un sujet.
	function Del_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_alerts', 'WHERE id=:id', array('id' => $id_alert));

		//Si la contribution associ�e n'est pas r�gl�e, on la r�gle
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];

			//We delete the contribution
			ContributionService::delete_contribution($file_contribution);
		}

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_DEL_ALERT);
	}

	//Ajout d'un sondage.
	function Add_poll($idtopic, $question, $answers, $nbr_votes, $type)
	{
		PersistenceContext::get_querier()->insert(PREFIX . "forum_poll", array('idtopic' => $idtopic, 'question' => $question, 'answers' => implode('|', $answers), 'voter_id' => 0, 'votes' => trim(str_repeat('0|', $nbr_votes)), 'type' => NumberHelper::numeric($type)));
	}

	//Edition d'un sondage.
	function Update_poll($idtopic, $question, $answers, $type)
	{
		//V�rification => v�rifie si il n'y a pas de nouvelle r�ponses � ajouter.
		$previous_votes = explode('|', PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_poll", 'votes', 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic)));

		$votes = array();
		foreach ($answers as $key => $answer_value) //R�cup�ration des votes pr�c�dents.
		$votes[$key] = isset($previous_votes[$key]) ? $previous_votes[$key] : 0;
		
		PersistenceContext::get_querier()->update(PREFIX . "forum_poll", array('question' => $question, 'answers' => implode('|', $answers), 'votes' => implode('|', $votes), 'type' => $type), 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));
	}

	//Suppression d'un sondage.
	function Del_poll($idtopic)
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_poll', 'WHERE idtopic=:id', array('id' => $idtopic));
	}

	/**
	 * @desc Returns an ordered tree with all categories informations
	 * @return array[] an ordered tree with all categories informations
	 */
	function get_cats_tree()
	{
		global $LANG, $CAT_FORUM, $Cache;
		$Cache->load('forum');
	  
		if (!(isset($CAT_FORUM) && is_array($CAT_FORUM)))
		{
			$CAT_ARTICLES = array();
		}

		$ordered_cats = array();
		foreach ($CAT_FORUM as $id => $cat)
		{   // Sort by id_left
			$cat['id'] = $id;
			$ordered_cats[NumberHelper::numeric($cat['id_left'])] = array('this' => $cat, 'children' => array());
		}
	  
		$level = 0;
		$cats_tree = array(array('this' => array('id' => 0, 'name' => $LANG['root']), 'children' => array()));
		$parent =& $cats_tree[0]['children'];
		$nb_cats = count($CAT_FORUM);
		foreach ($ordered_cats as $cat)
		{
			if (($cat['this']['level'] == $level + 1) && count($parent) > 0)
			{   // The new parent is the previous cat
				$parent =& $parent[count($parent) - 1]['children'];
			}
			elseif ($cat['this']['level'] < $level)
			{   // Find the new parent (an ancestor)
				$j = 0;
				$parent =& $cats_tree[0]['children'];
				while ($j < $cat['this']['level'])
				{
					$parent =& $parent[count($parent) - 1]['children'];
					$j++;
				}
			}

			// Add the current cat at the good level
			$parent[] = $cat;
			$level = $cat['this']['level'];
		}
		return $cats_tree[0];
	}

	## Private Method ##
	//Met � jour chaque cat�gories quelque soit le niveau de profondeur de la cat�gorie source. Cas le plus favorable et courant seulement 3 requ�tes.
	function update_last_topic_id($idcat)
	{
		global $CAT_FORUM;

		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) //Sous forums pr�sents.
		{
			//Sous forums du forum � mettre � jour.
			$list_cats = array();
			$result = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left BETWEEN :id_left AND :id_right
			ORDER BY id_left", array(
				'id_left' => $CAT_FORUM[$idcat]['id_left'],
				'id_right' => $CAT_FORUM[$idcat]['id_right']
			));
			
			while ($row = $result->fetch())
			$list_cats[] = $row['id'];
			
			$result->dispose();
			$clause = "idcat IN (" . implode(', ', $list_cats) . ")";
		}

		//R�cup�ration du timestamp du dernier message de la cat�gorie.
		$last_timestamp = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE ' . $clause);
		$last_topic_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'id', 'WHERE last_timestamp = :timestamp', array('timestamp' => $last_timestamp));
		PersistenceContext::get_querier()->update(PREFIX . "forum_cats", array('last_topic_id' => (int)$last_topic_id ), 'WHERE id = :id', array('id' => $idcat));
		
		if ($CAT_FORUM[$idcat]['level'] > 1) //Appel recursif si sous-forum.
		{
			//Recherche de l'id du forum parent.
			$idcat_parent = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_left AND id_right > :id_right AND level = :level', array('id_left' => $CAT_FORUM[$idcat]['id_left'], 'id_right' => $CAT_FORUM[$idcat]['id_right'], 'level' => ($CAT_FORUM[$idcat]['level'] - 1)));

			$this->Update_last_topic_id($idcat_parent); //Appel recursif.
		}
	}

	//Emulation de la fonction PHP 5 array_diff_key
	function array_diff_key_emulate()
	{
		$args = func_get_args();
		if (count($args) < 2) {
			user_error('Wrong parameter count for array_diff_key()', E_USER_WARNING);
			return;
		}

		// Check arrays
		$array_count = count($args);
		for ($i = 0; $i !== $array_count; $i++) {
			if (!is_array($args[$i])) {
				user_error('array_diff_key() Argument #' .
				($i + 1) . ' is not an array', E_USER_WARNING);
				return;
			}
		}

		$result = $args[0];
		foreach ($args[0] as $key1 => $value1) {
			for ($i = 1; $i !== $array_count; $i++) {
				foreach ($args[$i] as $key2 => $value2) {
					if ((string) $key1 === (string) $key2) {
						unset($result[$key2]);
						break 2;
					}
				}
			}
		}
		return $result;
	}
}
?>