<?php
/*##################################################
 *                              forum_english.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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


####################################################
#                                                           English                                                                             #
####################################################

global $CONFIG;

//Admin
$LANG['parent_category'] = 'Parent category';
$LANG['subcat'] = 'Subcategory';
$LANG['url_explain'] = 'Transform the forum into weblink (http://...)';
$LANG['lock'] = 'Lock';
$LANG['unlock'] = 'Unlock';
$LANG['cat_edit'] = 'Edit category';
$LANG['del_cat'] = 'Subcategory suppression tool';
$LANG['explain_thread'] = 'The forum you wish to delete contains <strong>1</strong> thread, do you want to preserve it by transferring it in another forum, or delete this thread?';
$LANG['explain_threads'] = 'The forum you wish to delete contains <strong>%d</strong> threads, do you want to preserve them by transferring them in another forum, or delete all threads?';
$LANG['explain_subcat'] = 'The forum you wish to delete contains <strong>1</strong> subforum, do you want to preserve it by transferring in another forum, or delete it and his contents?';
$LANG['explain_subcats'] = 'The forum you wish to delete contains <strong>%d</strong> subforums, do you want to preserve them by transferring them in another forum, or delete all this subforums and their contents?';
$LANG['keep_thread'] = 'Keep thread(s)';
$LANG['keep_subforum'] = 'Keep subforum(s)';
$LANG['move_threads_to'] = 'Move thread(s) to';
$LANG['move_forums_to'] = 'Move sub-forum(s) to';
$LANG['cat_target'] = 'Category target';
$LANG['del_all'] = 'Complete supression';
$LANG['del_cat'] = 'Delete the forum "<strong>%s</strong>", <strong>subforums</strong> and <strong>all</strong> his contents <span class="text_small">(irreversible)</span>';
$LANG['forum_config'] = 'Forum configuration';
$LANG['forum_management'] = 'Forum management';
$LANG['forum_name'] = 'Forum name';
$LANG['nbr_thread_p'] = 'Number of thread per page';
$LANG['nbr_thread_p_explain'] = 'Default 20';
$LANG['nbr_msg_p'] = 'Number of messages per page';
$LANG['nbr_msg_p_explain'] = 'Default 15';
$LANG['time_new_msg'] = 'Duration for which the messages read by the members are stored';
$LANG['time_new_msg_explain'] = 'To adjust according to the number of messages per day, by default 30 days';
$LANG['thread_track_max'] = 'Favorites threads max number';
$LANG['thread_track_max_explain'] = 'Default 40';
$LANG['edit_mark'] = 'Messages edition markers';
$LANG['forum_display_connexion'] = 'Display connexion formular';
$LANG['no_left_column'] = 'Hide left column menu on the forum';
$LANG['no_right_column'] = 'Hide right column menu on the forum';
$LANG['activ_display_msg'] = 'Active message in front of thread';
$LANG['display_msg'] = 'Message before the thread title';
$LANG['explain_display_msg'] = 'Message explanation for members';
$LANG['explain_display_msg_explain'] = 'If status no changed';
$LANG['explain_display_msg_bis_explain'] = 'If status changed';
$LANG['icon_display_msg'] = 'Associated icon';
$LANG['update_data_cached'] = 'Recount number of threads and messages';
$LANG['forum_groups_config'] = 'Groups config';
$LANG['explain_forum_groups'] = 'These configuration are only on the forum';
$LANG['flood_auth'] = 'Allowed flood';
$LANG['edit_mark_auth'] = 'Hide editing mark';
$LANG['track_thread_auth'] = 'Unactivate tracked threads limit';
$LANG['forum_read_feed'] = 'Read the thread';
	
//Require
$LANG['require_thread_p'] = 'Please enter the number of threads per page !';
$LANG['require_nbr_msg_p'] = 'Please enter the number of messages per page !';
$LANG['require_time_new_msg'] = 'Please enter a duration for the sight of new messages !';
$LANG['require_thread_track_max'] = 'Please enter the maximum number of tracked threads !';
	
//Error
$LANG['e_thread_lock_forum'] = 'Locked thread, you can\'t post';
$LANG['e_cat_lock_forum'] = 'Locked category, you can\'t post new thread or message';
$LANG['e_unexist_thread_forum'] = 'This thread doesn\'t exist';
$LANG['e_unexist_cat_forum'] = 'This category doesn\'t exist';
$LANG['e_unable_cut_forum'] = 'You can\'t divide this thread starting the first message';
$LANG['e_cat_write'] = 'You aren\'t allowed to write in this category';

//Alerts
$LANG['alert_delete_thread'] = 'Delete this thread ?';
$LANG['alert_lock_thread'] = 'Lock this thread ?';
$LANG['alert_unlock_thread'] = 'Unlock this thread ?';
$LANG['alert_move_thread'] = 'Move this thread ?';
$LANG['alert_warning'] = 'Warn this member ?';
$LANG['alert_history'] = 'Delete history ?';
$LANG['confirm_mark_as_read'] = 'Mark all threads as read ?';
$LANG['contribution_alert_moderators_for_threads'] = 'thread not complying with the forum rules: %s';

//Titres
$LANG['title_forum'] = 'Forum';
$LANG['title_thread'] = 'Threads';
$LANG['title_post'] = 'Post';
$LANG['title_search'] = 'Search';

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Subforums';
$LANG['thread'] = 'Thread';
$LANG['thread_s'] = 'Threads';
$LANG['author'] = 'Author';
$LANG['advanced_search'] = 'Advanced search';
$LANG['distributed'] = 'Distributed in';
$LANG['mark_as_read'] = 'Mark all threads as read';
$LANG['show_thread_track'] = 'Tracked threads';
$LANG['no_msg_not_read'] = 'No message unread';
$LANG['show_not_reads'] = 'Unread messages';
$LANG['show_last_read'] = 'Last messages read';
$LANG['no_last_read'] = 'message read';
$LANG['last_message'] = 'Last message';
$LANG['last_messages'] = 'Last messages';
$LANG['forum_new_thread'] = 'New thread';
$LANG['post_new_thread'] = 'Post a new thread';
$LANG['forum_edit_thread'] = 'Edit thread';
$LANG['forum_announce'] = 'Announce';
$LANG['forum_postit'] = 'Pinned';
$LANG['forum_lock'] = 'Lock';
$LANG['forum_unlock'] = 'Unlock';
$LANG['forum_move'] = 'Move';
$LANG['forum_move_thread'] = 'Move thread';
$LANG['forum_quote_last_msg'] = 'Repost of the preceding message ';
$LANG['edit_message'] = 'Edit Message';
$LANG['edit_by'] = 'Edit by';
$LANG['no_message'] = 'No message';
$LANG['group'] = 'Group';
$LANG['cut_thread'] = 'Divide this thread starting from this message';
$LANG['forum_cut_thread'] = 'Divide thread';
$LANG['alert_cut_thread'] = 'Divide this thread starting from this message?';
$LANG['track_thread'] = 'Add to favorite';
$LANG['untrack_thread'] = 'Remove from favorites';
$LANG['track_thread_pm'] = 'Track by private message';
$LANG['untrack_thread_pm'] = 'Stop private messsage tracking';
$LANG['track_thread_mail'] = 'Track by email';
$LANG['untrack_thread_mail'] = 'Stop email tracking';
$LANG['alert_thread'] = 'Alert moderators';
$LANG['banned'] = 'Banned';
$LANG['xml_forum_desc'] = 'Last forum thread';
$LANG['alert_modo_explain'] = 'You are about to alert the moderators. You are helping the moderation team by informing us about threads which don\'t comply with certain rules, but will know that when you alert a moderator your pseudo is recorded, it is thus necessary that your request is justified without what you risk sanctions on behalf of the team of the moderators and administrators in the event of abuse. In order to help the team, thank you to explain what does not observe the conditions in this thread.

You wish to alert the moderators about a problem on the following thread';
$LANG['alert_title'] = 'Short description';
$LANG['alert_contents'] = 'Thanks for detailing the problem more in order to help the moderating team';
$LANG['alert_success'] = 'You announced successfully the nonconformity of the thread <em>%title</em>, the moderating team thanks you for having helped it.';
$LANG['alert_thread_already_done'] = 'We thank you for having taken the initiative to help the moderating team, but a member already announced a nonconformity of this thread.';
$LANG['alert_back'] = 'Back to thread';
$LANG['explain_track'] = 'Check Pm to receive a private message, Mail for an email in case of answers in this tracked thread. Check delete box for untrack thread';
$LANG['sub_forums'] = 'Sub-forums';
$LANG['moderation_forum'] = 'Forum moderation';
$LANG['no_threads'] = 'No threads';
$LANG['for_selection'] = 'For the selection';
$LANG['change_status_to'] = 'Set status: %s';
$LANG['change_status_to_default'] = 'Set default status';
$LANG['move_to'] = 'Move to...';

//Recherche
$LANG['search_forum'] = 'Search on the forum';
$LANG['relevance'] = 'Pertinance';
$LANG['no_result'] = 'No result';
$LANG['invalid_req'] = 'Invalid request';
$LANG['keywords'] = 'Key Words (4 characters minimum)';
$LANG['colorate_result'] = 'Colorate results';

//Stats
$LANG['stats'] = 'Statistics';
$LANG['nbr_threads_day'] = 'Number threads per day';
$LANG['nbr_msg_day'] = 'Number messages per day';
$LANG['nbr_threads_today'] = 'Number threads today';
$LANG['nbr_msg_today'] = 'Number messages today';
$LANG['forum_last_msg'] = 'The 10 last threads';
$LANG['forum_popular'] = 'The 10 most famous threads';
$LANG['forum_nbr_answers'] = 'The 10 threads with the highest number of answers';

//History
$LANG['history'] = 'Actions history';
$LANG['history_member_concern'] = 'Member concern';
$LANG['no_action'] = 'No action in database';
$LANG['delete_msg'] = 'Delete message';
$LANG['delete_thread'] = 'Delete thread';
$LANG['lock_thread'] = 'Lock thread';
$LANG['unlock_thread'] = 'Unlock thread';
$LANG['move_thread'] = 'Move thread';
$LANG['cut_thread'] = 'Cut thread';
$LANG['warning_on_user'] = '+10% to member';
$LANG['warning_off_user'] = '-10% to member';
$LANG['set_warning_user'] = 'Warning pourcent modification';
$LANG['more_action'] = 'Show 100 action moreover';
$LANG['ban_user'] = 'Ban member';
$LANG['edit_msg'] = 'Edit message member';
$LANG['edit_thread'] = 'Edit thread member';
$LANG['solve_alert'] = 'Set alert statute to solve';
$LANG['wait_alert'] = 'Set alert statute to standby';
$LANG['del_alert'] = 'Delete alert';

//Member messages
$LANG['show_member_msg'] = 'Show all member\'s messages';

//Poll
$LANG['poll'] = 'Poll(s)';
$LANG['mini_poll'] = 'Mini Poll';
$LANG['poll_main'] = 'This is the place of polls of the site, profit in to deliver your opinion, or simply to answer the polls.';
$LANG['poll_back'] = 'Return to the poll(s)';
$LANG['redirect_none'] = 'No polls available';
$LANG['confirm_vote'] = 'Your vote was taken into account';
$LANG['already_vote'] = 'You have already voted';
$LANG['no_vote'] = 'Your null vote has been considered';
$LANG['poll_vote'] = 'Vote';
$LANG['poll_vote_s'] = 'Votes';
$LANG['poll_result'] = 'Results';
$LANG['alert_delete_poll'] = 'Delete this poll ?';
$LANG['unauthorized_poll'] = 'You aren\'t authorized to vote !';
$LANG['question'] = 'Question';
$LANG['answers'] = 'Answers';
$LANG['poll_type'] = 'Kind of poll';
$LANG['open_menu_poll'] = 'Open poll menu';
$LANG['simple_answer'] = 'Single answer';
$LANG['multiple_answer'] = 'Multiple answer';
$LANG['delete_poll'] = 'Delete poll';

//Post
$LANG['next'] = 'Next';
$LANG['forum_mail_title_new_post'] = 'New post on the forum';
$LANG['forum_mail_new_post'] = 'You track the thread: %s 

You asked a notify in case of answer on it.

%s has reply: 
%s... %s




If you do not wish any more to be informed answers of this thread, click on the link below:
' . HOST . DIR . '/forum/action.php?ut=%d

' . $CONFIG['sign'];

//Alerts
$LANG['alert_management'] = 'Alert management';
$LANG['alert_concerned_thread'] = 'Concerned thread';
$LANG['alert_concerned_cat'] = 'Concerned thread\'s category';
$LANG['alert_login'] = 'Alert postor';
$LANG['alert_msg'] = 'Precisions';
$LANG['alert_not_solved'] = 'Waiting for treatement';
$LANG['alert_solved'] = 'Resolve by ';
$LANG['change_status_to_0'] = 'Set in waiting for treatement';
$LANG['change_status_to_1'] = 'Set in resolve';
$LANG['no_alert'] = 'There is no alert';
$LANG['alert_not_auth'] = 'This alert has been post in a forum in which you haven\'t the moderator rights.';
$LANG['delete_several_alerts'] = 'Are you sure, delete all this alerts?';
$LANG['new_alerts'] = 'new alert';
$LANG['new_alerts_s'] = 'new alerts';
$LANG['action'] = 'Action';

?>
