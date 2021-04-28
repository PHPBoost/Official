<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 28
 * @since       PHPBoost 2.0 - 2008 03 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

$request = AppContext::get_request();

$top_view = new FileTemplate('forum/forum_top.tpl');
$bottom_view = new FileTemplate('forum/forum_bottom.tpl');
$top_view->add_lang(array_merge(
	LangLoader::get('common', 'forum'),
	LangLoader::get('common-lang'),
	LangLoader::get('user-lang'),
));
$bottom_view->add_lang(array_merge(
	LangLoader::get('common', 'forum'),
	LangLoader::get('common-lang'),
	LangLoader::get('user-lang'),
));

$is_guest = AppContext::get_current_user()->get_id() == -1;
$is_connected = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
$nbr_msg_not_read = 0;
if (!$is_guest) {
	//Calcul du temps de péremption, ou de dernière vue des messages par rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();

	//Vérification des autorisations.
	$authorized_categories = CategoriesService::get_authorized_categories();

	//Si on est sur un topic, on le supprime dans la requête => si ce topic n'était pas lu il ne sera plus dans la liste car désormais lu.
	$clause_topic = '';
	if (TextHelper::strpos(SCRIPT, '/forum/topic.php') !== false) {
		$id_get = $request->get_getint('id', 0);
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}

	$nbr_msg_not_read = 0;

	//Requête pour compter le nombre de messages non lus.
	try {
		$row = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as nbr_msg_not_read
		FROM " . PREFIX . "forum_topics t
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
		LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = :user_id
		WHERE t.last_timestamp >= :last_timestamp AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . " AND c.id IN :authorized_categories", array(
			'authorized_categories' => $authorized_categories,
			'last_timestamp' => $max_time_msg,
			'user_id' => AppContext::get_current_user()->get_id()
		));
		$nbr_msg_not_read = $row['nbr_msg_not_read'];
	} catch (RowNotFoundException $e) {

	}
}

//Formulaire de connexion sur le forum.
if ($config->is_connexion_form_displayed()) {
	$display_connexion = array(
		'C_USER_NOTCONNECTED' => !$is_connected,
		'C_FORUM_CONNEXION'   => true,

		'L_CONNECT'     => LangLoader::get_message('connection', 'user-common'),
		'L_DISCONNECT'  => LangLoader::get_message('disconnect', 'user-common'),
		'L_AUTOCONNECT' => LangLoader::get_message('autoconnect', 'user-common'),
		'L_REGISTER'    => LangLoader::get_message('register', 'user-common')
	);
	$top_view->put_all($display_connexion);
	$bottom_view->put_all($display_connexion);
}

$vars_tpl = array(
	'C_USER_CONNECTED'         => $is_connected,
	'C_DISPLAY_UNREAD_DETAILS' => !$is_guest,
	'C_MODERATION_PANEL'       => AppContext::get_current_user()->check_level(1),

	'FORUM_NAME'             => $config->get_forum_name(),
	'UNREAD_MESSAGES_NUMBER' => $nbr_msg_not_read,

	'U_TRACKED_TOPICS'    => Url::to_rel('/forum/track.php'),
	'U_LAST_MESSAGE_READ' => Url::to_rel('/forum/lastread.php'),
	'U_UNREAD_MESSAGES'   => Url::to_rel('/forum/unread.php'),
	'U_UNANSWERED_TOPICS' => Url::to_rel('/forum/noanswer.php'),
	'U_MARK_AS_READ'      => Url::to_rel('/forum/action' . url('.php?read=1', '')),

	'L_SHOW_TOPIC_TRACK'    => $LANG['show_topic_track'],
	'L_SHOW_LAST_READ'      => $LANG['show_last_read'],
	'L_SHOW_NOT_READS'      => $LANG['show_not_reads'],
	'L_SHOW_NO_ANSWER'      => $LANG['show.no.answer'],
	'L_MARK_AS_READ'        => $LANG['mark_as_read'],
	'C_IS_GUEST'            => !$is_guest,
	'L_FORUM_INDEX'         => $LANG['forum_index'],
	'L_MODERATION_PANEL'    => $LANG['moderation_panel'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_AUTH_ERROR'          => LangLoader::get_message('error.auth', 'status-messages-common'),
	'L_SHOW_MY_MSG'         => $LANG['show_my_msg'],
);

$top_view->put_all($vars_tpl);
$bottom_view->put_all($vars_tpl);

?>
