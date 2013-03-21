<?php
/*##################################################
 *                     GuestbookHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 08, 2012
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

class GuestbookHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('guestbook');
		
		return $LANG['title_guestbook'];
	}
	
	private function get_view()
	{
		global $LANG, $Cache, $User, $auth_write, $Session, $Bread_crumb, $level, $id_get, $guestbook, $authorizations;
		
		require_once(PATH_TO_ROOT . '/guestbook/guestbook_begin.php');
		
		$guestbook_config = GuestbookConfig::load();

		$tpl = new FileTemplate('guestbook/guestbook.tpl');
		
		//Construction du formulaire
		if ($User->check_auth($authorizations, GuestbookConfig::AUTH_WRITE))
		{
			$is_edition_mode = !empty($id_get);
			
			$guestbook_fieldset = $LANG['add_msg'];
			$guestbook_login = $LANG['guest'];
			$guestbook_contents = '';
			$has_edit_auth = false;
			$user_id = -1;
			if ($is_edition_mode)
			{
				$row = $this->sql_querier->query_array(PREFIX . 'guestbook', '*', "WHERE id='" . $id_get . "'", __LINE__, __FILE__);
				$user_id = (int)$row['user_id'];
				$guestbook_fieldset = $LANG['update_msg'];
				$guestbook_login = $row['login'];
				$guestbook_contents = FormatingHelper::unparse($row['contents']);
				
				$has_edit_auth = $User->check_auth($authorizations, GuestbookConfig::AUTH_MODO)
					|| ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1);
			}
			
			$is_guest = $is_edition_mode ? $user_id == -1 : !$User->check_level(User::MEMBER_LEVEL);

			$formatter = AppContext::get_content_formatting_service()->get_default_factory();
			$formatter->set_forbidden_tags($guestbook_config->get_forbidden_tags());
				
			//Post form
			$form = new HTMLForm('guestbookForm');
			$fieldset = new FormFieldsetHTML('add_msg', $guestbook_fieldset);
			if ($is_guest) //Visiteur
			{
				$fieldset->add_field(new FormFieldTextEditor('pseudo', $LANG['pseudo'], $guestbook_login, array(
					'class' => 'text', 'required' => $LANG['require_pseudo'], 'maxlength' => 25)
				));
			}
			$fieldset->add_field(new FormFieldRichTextEditor('contents',  $LANG['message'], $guestbook_contents, array(
				'formatter' => $formatter, 'rows' => 10, 'cols' => 47, 'required' => $LANG['require_text'])
			));
			
			if ($is_guest && !$is_edition_mode && $guestbook_config->get_display_captcha()) //Code de v�rification, anti-bots.
			{
				$captcha = new PHPBoostCaptcha();
				$captcha->set_difficulty($guestbook_config->get_captcha_difficulty());
				$fieldset->add_field(new FormFieldCaptcha('captcha', $captcha));
			}
				
			if ($is_edition_mode)
			{
				$submit_button = new FormButtonSubmit($LANG['update'], $LANG['update']);
			}
			else
			{
				$submit_button = new FormButtonDefaultSubmit();
			}
			$form->add_button($submit_button);
			$form->add_button(new FormButtonReset());
				
			$form->add_fieldset($fieldset);
			
			//Formulaire soumis
			if ($submit_button->has_been_submited())
			{
				//Membre en lecture seule?
				if ($User->get_attribute('user_readonly') > time())
				{
					$controller = PHPBoostErrors::user_in_read_only();
					DispatchManager::redirect($controller);
				}
				//Mod anti-flood
				$check_time = 0;
				if ($User->get_attribute('user_id') !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) 
				{
					$check_time = $this->sql_querier->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "guestbook WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
					if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration())) //On calcul la fin du delai.
					{
						$controller = PHPBoostErrors::flood();
						DispatchManager::redirect($controller);
					}
				}
					
				if ($form->validate())
				{
					$guestbook_login = '';	
					if ($form->has_field('pseudo')) {
						$guestbook_login = $form->get_value('pseudo');
					}
					$guestbook_login = empty($guestbook_login) ? ($User->check_level(User::MEMBER_LEVEL) ? $User->get_attribute('login') : $LANG['guest']) : $guestbook_login;
					
					$guestbook_contents = $form->get_value('contents');
					
					//Nombre de liens max dans le message ou dans le login
					if (!TextHelper::check_nbr_links($guestbook_contents, $guestbook_config->get_maximum_links_message())) 
					{
						$controller = PHPBoostErrors::link_flood($guestbook_config->get_maximum_links_message());
						DispatchManager::redirect($controller);
					}
					if (!TextHelper::check_nbr_links($guestbook_login, 0)) 
					{
						$controller = PHPBoostErrors::link_login_flood();
						DispatchManager::redirect($controller);
					}
					
					if ($is_edition_mode && $has_edit_auth) 
					{
						$columns = array(
							'contents' => $guestbook_contents
						);
						
						if ($user_id == -1) {
							$columns['login'] = $guestbook_login;
						}
						
						PersistenceContext::get_querier()->update(PREFIX . "guestbook", $columns, " WHERE id = :id", array('id' => $id_get));

						GuestbookMessagesCache::invalidate();

						AppContext::get_response()->redirect(HOST . SCRIPT. SID2 . '#m' . $id_get);
					}
					else
					{
						$columns = array(
							'contents' => $guestbook_contents,
							'login' => $guestbook_login,
							'user_id' => $User->get_attribute('user_id'),
							'timestamp' => time()
						);
						$result = PersistenceContext::get_querier()->insert(PREFIX . "guestbook", $columns);
						
						$last_msg_id = $result->get_last_inserted_id(); //Dernier message inséré.
			
						GuestbookMessagesCache::invalidate();
			
						AppContext::get_response()->redirect(HOST . SCRIPT . SID2 . '#m' . $last_msg_id);
					}
				}
			}
			
			$tpl->put('GUESTBOOK_FORM', $form->display());
		}
		else
		{
			$tpl->put_all(array(
				'C_ERROR_WRITING_AUTH' => true,
				'L_ERROR_WRITING_AUTH' => $LANG['e_unauthorized']
			));
		}
		
		//On cr�e une pagination si le nombre de msg est trop important.
		$nbr_guestbook = $this->sql_querier->count_table(PREFIX . 'guestbook', __LINE__, __FILE__);

		$Pagination = new DeprecatedPagination();
		$tpl->put_all(array(
			'PAGINATION' => $Pagination->display('guestbook' . url('.php?p=%d'), $nbr_guestbook, 'p', 10, 3),
			'L_DELETE_MSG' => $LANG['alert_delete_msg'],
			'L_GUESTBOOK' => $LANG['title_guestbook']
		));

		//Cr�ation du tableau des rangs.
		$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);

		//Gestion des rangs.
		$ranks_cache = RanksCache::load()->get_ranks();
		$j = 0;
		$result = $this->sql_querier->query_while("SELECT g.id, g.login, g.timestamp, m.user_id, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, ext_field.user_avatar, m.user_msg, ext_field.user_location, ext_field.user_website, ext_field.user_sex, ext_field.user_msn, ext_field.user_yahoo, ext_field.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = g.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = g.user_id
		GROUP BY g.id
		ORDER BY g.timestamp DESC
		" . $this->sql_querier->limit($Pagination->get_first_msg(10, 'p'), 10), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$edit = '';
			$del = '';

			$is_guest = empty($row['user_id']);
			$is_modo = $User->check_level(User::MODERATOR_LEVEL);
			$warning = '';
			$readonly = '';
			if ($is_modo && !$is_guest) //Mod�ration.
			{
				$warning = '&nbsp;<a href="'. PATH_TO_ROOT.'/user/moderation_panel' . url('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>';
				$readonly = '<a href="'. PATH_TO_ROOT.'/user/moderation_panel' . url('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>';
			}

			//Edition/suppression.
			if ($User->check_auth($authorizations, GuestbookConfig::AUTH_MODO) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
			{
				$edit = '&nbsp;&nbsp;<a href="'. PATH_TO_ROOT.'/guestbook/guestbook' . url('.php?edit=1&id=' . $row['id']) . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="'. PATH_TO_ROOT.'/guestbook/guestbook' . url('.php?del=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" onclick="javascript:return Confirm();"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
			}

			//Pseudo.
			if (!$is_guest)
			{
				$guestbook_login = '<a class="msg_link_pseudo" href="'. UserUrlBuilder::profile($row['user_id'])->absolute() .'" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . TextHelper::wordwrap_html($row['mlogin'], 13) . '</span></a>';
			}
			else
			{
				$guestbook_login = '<span style="font-style:italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
			}

			//Rang de l'utilisateur.
			$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
			$user_group = $user_rank;
			$user_rank_icon = '';
			if ($row['level'] === '2') //Rang sp�cial (admins).
			{
				$user_rank = $ranks_cache[-2]['name'];
				$user_group = $user_rank;
				$user_rank_icon = $ranks_cache[-2]['icon'];
			}
			elseif ($row['level'] === '1') //Rang sp�cial (modos).
			{
				$user_rank = $ranks_cache[-1]['name'];
				$user_group = $user_rank;
				$user_rank_icon = $ranks_cache[-1]['icon'];
			}
			else
			{
				foreach ($ranks_cache as $msg => $ranks_info)
				{
					if ($msg >= 0 && $msg <= $row['user_msg'])
					{
						$user_rank = $ranks_info['name'];
						$user_rank_icon = $ranks_info['icon'];
					}
				}
			}

			//Image associ�e au rang.
			$user_assoc_img = !empty($user_rank_icon) ? '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';

			//Affichage des groupes du membre.
			if (!empty($row['user_groups']))
			{
				$user_groups = '';
				$array_user_groups = explode('|', $row['user_groups']);
				foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
				{
					if (is_numeric(array_search($idgroup, $array_user_groups)))
					{
						$user_groups .= !empty($array_group_info['img']) ? '<img src="'. PATH_TO_ROOT.'/images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
					}
				}
			}
			else
			{
				$user_groups = $LANG['group'] . ': ' . $user_group;
			}

			//Membre en ligne?
			$user_online = !empty($row['connect']) ? 'online' : 'offline';

			$user_accounts_config = UserAccountsConfig::load();

			//Avatar
			if (empty($row['user_avatar']))
			{
				$user_avatar = ($user_accounts_config->is_default_avatar_enabled()) ? '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
			}
			else
			{
				$user_avatar = '<img src="' . Url::to_rel($row['user_avatar']) . '" alt="" />';
			}

			//Affichage du sexe et du statut (connect�/d�connect�).
			$user_sex = '';
			if ($row['user_sex'] == 1)
			{
				$user_sex = $LANG['sex'] . ': <img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/man.png" alt="" /><br />';
			}
			elseif ($row['user_sex'] == 2)
			{
				$user_sex = $LANG['sex'] . ': <img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
			}

			//Nombre de message.
			$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];

			//Localisation.
			if (!empty($row['user_location']))
			{
				$user_local = $LANG['place'] . ': ' . $row['user_location'];
				$user_local = $user_local > 15 ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($user_local), 0, 15)) . '...<br />' : $user_local . '<br />';
			}
			else 
			{
				$user_local = '';
			}

			$tpl->assign_block_vars('guestbook',array(
				'ID' => $row['id'],
				'CONTENTS' => ucfirst(FormatingHelper::second_parse($row['contents'])),
				'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
				'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
				'USER_ONLINE' => '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
				'USER_PSEUDO' => $guestbook_login,
				'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
				'USER_IMG_ASSOC' => $user_assoc_img,
				'USER_AVATAR' => $user_avatar,
				'USER_GROUP' => $user_groups,
				'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
				'USER_SEX' => $user_sex,
				'USER_MSG' => !$is_guest ? $user_msg : '',
				'USER_LOCAL' => $user_local,
				'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',
				'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
				'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
				'USER_WEB' => !empty($row['user_website']) ? '<a href="' . $row['user_website'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_website']  . '" title="' . $row['user_website']  . '" /></a>' : '',
				'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
				'PUNISHMENT' => $readonly,
				'DEL' => $del,
				'EDIT' => $edit,
				'U_USER_PM' => !$is_guest ? '<a href="'. PATH_TO_ROOT.'/user/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>' : '',
				'U_ANCHOR' => 'guestbook.php' . SID . '#m' . $row['id']
			));
			$j++;
		}
		$this->sql_querier->query_close($result);

		return $tpl;
	}
}
?>