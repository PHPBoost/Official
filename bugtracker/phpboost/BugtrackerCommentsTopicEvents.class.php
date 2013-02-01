<?php
/*##################################################
 *                              BugtrackerCommentsTopicEvents.class.php
 *                            -------------------
 *   begin                : October 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class BugtrackerCommentsTopicEvents extends CommentsTopicEvents
{
	public function execute_add_comment_event()
	{
		global $LANG;
		$lang = $LANG;
		//$lang = LangLoader::get('bugtracker_common', 'bugtracker');
		
		$sql_querier = PersistenceContext::get_querier();
		$current_user = AppContext::get_current_user();
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$bugtracker_config = BugtrackerConfig::load();
		$pm_activated = $bugtracker_config->get_pm_activated();
		
		// R�cup�reration du contenu du commentaire
		$comment = stripslashes(FormatingHelper::strparse(AppContext::get_request()->get_poststring('comments_message', '')));
		
		//R�cup�ration de l'id du bug
		$bug_id = $this->comments_topic->get_id_in_module();
		
		//R�cup�ration de l'id de l'auteur et de la personne a qui est assign� le bug
		$result = $sql_querier->select_single_row(PREFIX . 'bugtracker', array('author_id', 'assigned_to_id'), 'WHERE id=:id', array(
			'id' => $bug_id
		));
		
		//R�cup�ration de l'id des personnes qui ont mis � jour le bug
		$updaters_ids = array($result['author_id']);
		if (!empty($result['assigned_to_id']) && $result['assigned_to_id'] != $result['author_id'])
			$updaters_ids[] = $result['assigned_to_id'];
		
		$result_uid = $sql_querier->select('SELECT updater_id FROM ' . PREFIX . 'bugtracker_history WHERE bug_id=:id GROUP BY updater_id', array(
			'id' => $bug_id
		), SelectQueryResult::FETCH_ASSOC);
		while ($row = $result_uid->fetch())
		{
			if ($row['updater_id'] != $result['author_id'] && $row['updater_id'] != $result['assigned_to_id'])
				$updaters_ids[] = $row['updater_id'];
		}
		
		//Ajout d'une ligne dans l'historique du bug
		$sql_querier->insert(PREFIX . 'bugtracker_history', array(
			'bug_id' => $bug_id, 
			'updater_id' => $current_user->get_id(),
			'update_date' => $now->get_timestamp(),
			'change_comment' => $lang['bugs.notice.new_comment'],
		));
		
		// Envoi d'un MP � chaque utilisateur qui a contribu� au bug lorsqu'un commentaire est post�
		foreach ($updaters_ids as $updater_id)
		{
			
			if (($pm_activated == true) && $current_user->get_attribute('user_id') != $updater_id)
			{
				PrivateMsg::start_conversation(
					$updater_id, 
					sprintf($lang['bugs.pm.comment.title'], $lang['bugs.module_title'], $bug_id, $current_user->get_login()), 
					nl2br(sprintf($lang['bugs.pm.comment.contents'], $current_user->get_login(), $bug_id, $comment, '<a href="' . HOST . DIR . '/bugtracker/bugtracker.php?view&id=' . $bug_id . '&com=0#comments_list">' . HOST . DIR . '/bugtracker/bugtracker.php?view&id=' . $bug_id . '&com=0#comments_list</a>')), 
					'-1', 
					PrivateMsg::SYSTEM_PM
				);
			}
		}
		
		return true;
	}
}
?>
