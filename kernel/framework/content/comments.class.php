<?php
/*##################################################
 *                             comments.class.php
 *                            -------------------
 *   begin                : March 08, 2008
 *   copyright            : (C) 2008 Viarre R�gis, Sautel Benoit
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com
 * v 2.0
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('INTEGRATED_IN_ENVIRONMENT', true);
define('POP_UP_WINDOW', false);
define('KERNEL_SCRIPT', true);

class Comments
{
	## Public Methods ##
	//Constructeur.
	//Script est le nom du module, idprov la cl� primaire enti�re de l'item � commenter dans le script, vars est le lien avec %d r�serv� au module
	function Comments($script, $idprov, $vars, $module_folder = '', $is_kernel_script = false)
	{
		$this->module_folder = !empty($module_folder) ? strprotect($module_folder) : strprotect($script);
		list($this->script, $this->idprov, $this->vars, $this->path) = array(strprotect($script), numeric($idprov), $vars, PATH_TO_ROOT . '/' . $this->module_folder . '/');
		
		$this->is_kernel_script = $is_kernel_script;
	}
	
	//Ajoute un commentaire et retourne l'identifiant ins�r�.
	function add($contents, $login)
	{
		global $Sql, $User;
		
		$Sql->query_inject("INSERT INTO " . DB_TABLE_COM . " (idprov, login, user_id, contents, timestamp, script, path, user_ip) VALUES('" . $this->idprov . "', '" . $login . "', '" . $User->get_attribute('user_id') . "', '" . $contents . "', '" . time() . "', '" . $this->script . "', '.." . strprotect(str_replace(DIR, '', SCRIPT) . '?' . QUERY_STRING) . "', '" . USER_IP . "')", __LINE__, __FILE__);
		$idcom = $Sql->insert_id("SELECT MAX(idcom) FROM " . DB_TABLE_COM);
		
		//Incr�mente le nombre de commentaire dans la table du script concern�.
		$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com = nbr_com + 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return $idcom;
	}
	
	//Edition d'un commentaire
	function update($contents, $login)
	{
		global $Sql;
		$Sql->query_inject("UPDATE " . DB_TABLE_COM . " SET contents = '" . $contents . "', login = '" . $login . "' WHERE idcom = '" . $this->idcom . "' AND idprov = '" . $this->idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
	}
	
	//Suppression d'un commentaire
	function del()
	{
		global $Sql;
		
		//S�lectionne le message pr�c�dent � celui qui va �tre supprim�.
		$lastid_com = $Sql->query("SELECT idcom
		FROM " . PREFIX . "com
		WHERE idcom < '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "'
		ORDER BY idcom DESC
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
		
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idcom = '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com= nbr_com - 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return $lastid_com;
	}
	
	//Supprime tous les commentaires de l'item (li� � la suppression de l'item)
	function delete_all($idprov)
	{
		global $Sql;
		
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
	}
	
	//Verrouille les commentaires
	function lock($lock)
	{
		global $Sql;

		$Sql->query_inject("UPDATE ".PREFIX.$this->sql_table." SET lock_com = '" . $lock . "' WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
	}
	
	//V�rifie que le syst�me de commentaires est bien charg�.
	function is_loaded()
	{
		global $Errorh;
		
		if (empty($this->sql_table)) //Erreur avec le module non pr�vu pour g�rer les commentaires.
			$Errorh->handler('e_unexist_page', E_USER_REDIRECT);
			
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->vars));
	}
	
	//Met � jour l'id du commentaire.
	function set_arg($idcom, $path = '')
	{
		if (!empty($path))
			$this->path = $path;
		$this->idcom = (int)max($idcom, 0);
		
		//Si c'est un module qui appelle
		if (!$this->is_kernel_script)
			list($this->sql_table, $this->nbr_com, $this->lock_com) = $this->_get_info_module();
		//Sinon c'est le noyau
		else
			list($this->sql_table, $this->nbr_com, $this->lock_com) = $this->_get_info_kernel_script();
	}
	
	//Accesseur
	function get_attribute($varname)
	{
		return $this->$varname;
	}
	
	//M�thode d'affichage
	function display($integrated_in_environment = INTEGRATED_IN_ENVIRONMENT, $Template = false, $page_path_to_root = '')
	{
		global $Cache, $User, $Errorh, $Sql, $LANG, $CONFIG, $CONFIG_USER, $CONFIG_COM, $_array_rank, $_array_groups_auth, $Session;
		
		if ($integrated_in_environment)
		{
			$idcom_get = retrieve(GET, 'com', 0);
			$idcom_post = retrieve(POST, 'idcom', 0);
		    $idcom = $idcom_post > 0 ? $idcom_post : $idcom_get;
			
		    $this->set_arg($idcom); //On met � jour les attributs de l'objet.
		}
		
	    $vars_simple = sprintf($this->vars, 0);
		$delcom = retrieve(GET, 'delcom', 0);
		$editcom = retrieve(GET, 'editcom', 0);
		$updatecom = retrieve(GET, 'updatecom', false);
		
		$path_redirect = $this->path . sprintf(str_replace('&amp;', '&', $this->vars), 0) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&path_to_root=' . $page_path_to_root : '');
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/content/com.tpl');
		
		//Commentaires charg�s?
		if ($this->is_loaded())
		{
			//Chargement du cache
			$Cache->load('com');
			
			###########################Insertion##############################
			if (retrieve(POST, 'valid_com', false) && !$updatecom)
			{
				//Membre en lecture seule?
				if ($User->get_attribute('user_readonly') > time())
					$Errorh->handler('e_auth', E_USER_REDIRECT);
				
				$login = retrieve(POST, 'login', ''); //Pseudo post�.
				$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
				
				if (!empty($login) && !empty($contents))
				{
					//Status des commentaires, verrouill�/d�verrouill�?
					if ($this->lock_com >= 1 && !$User->check_level(MODO_LEVEL))
						redirect($path_redirect);
					
					//Autorisation de poster des commentaires?
					if ($User->check_level($CONFIG_COM['com_auth']))
					{
						//Mod anti-flood, autoris� aux membres qui b�nificie de l'autorisation de flooder.
						$check_time = ($User->get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . DB_TABLE_COM . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
						if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
						{
							if ($check_time >= (time() - $CONFIG['delay_flood'])) //On calcule la fin du delai.
								redirect($path_redirect . '&errorh=flood#errorh');
						}
						
						$contents = strparse($contents, $CONFIG_COM['forbidden_tags']);
						
						//Correction des chemins du BBCode
						if (!$integrated_in_environment && !empty($page_path_to_root))
							$contents = str_replace('"' . PATH_TO_ROOT . '/', '"' . $page_path_to_root . '/', $contents);
							
						if (!check_nbr_links($login, 0)) //Nombre de liens max dans le pseudo.
							redirect($path_redirect . '&errorh=l_pseudo#errorh');
						if (!check_nbr_links($contents, $CONFIG_COM['max_link'])) //Nombre de liens max dans le message.
							redirect($path_redirect . '&errorh=l_flood#errorh');
						
						//R�cup�ration de l'adresse de la page.
						$last_idcom = $this->add($contents, $login);
						
						//R�direction vers la page pour �viter le double post!
						redirect($path_redirect . '#m' . $last_idcom);
					}
					else //utilisateur non autoris�!
						redirect($path_redirect . '&errorh=auth#errorh');
				}
				else
					redirect($path_redirect . '&errorh=incomplete#errorh');
			}
			elseif ($updatecom || $delcom > 0 || $editcom > 0) //Mod�ration des commentaires.
			{
				//Membre en lecture seule?
				if ($User->get_attribute('user_readonly') > time())
					$Errorh->handler('e_auth', E_USER_REDIRECT);
				
				$row = $Sql->query_array(DB_TABLE_COM, '*', "WHERE idcom = '" . $this->idcom . "' AND idprov = '" . $this->idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
				$row['user_id'] = (int)$row['user_id'];
				
				if ($this->idcom != 0 && ($User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))) //Mod�ration des commentaires.
				{
					if ($delcom > 0) //Suppression du commentaire.
					{
					    $Session->csrf_get_protect();
						$lastid_com = $this->del();
						$lastid_com = !empty($lastid_com) ? '#m' . $lastid_com : '';
						
						//Succ�s redirection.
						redirect($path_redirect . $lastid_com);
					}
					elseif ($editcom > 0) //Edition du commentaire.
					{
						$Template->assign_vars(array(
							'CURRENT_PAGE_COM' => $integrated_in_environment,
							'POPUP_PAGE_COM' => !$integrated_in_environment,
							'AUTH_POST_COM' => true
						));
						
						//Pseudo du membre connect�.
						if ($row['user_id'] !== -1)
							$Template->assign_vars(array(
								'C_HIDDEN_COM' => true,
								'LOGIN' => $User->get_attribute('login')
							));
						else
							$Template->assign_vars(array(
								'C_VISIBLE_COM' => true,
								'LOGIN' => $row['login']
							));
						
						$Template->assign_vars(array(
							'IDPROV' => $row['idprov'],
							'IDCOM' => $row['idcom'],
							'SCRIPT' => $this->script,
							'CONTENTS' => unparse($row['contents']),
							'DATE' => gmdate_format('date_format', $row['timestamp']),
							'THEME' => get_utheme(),
							'KERNEL_EDITOR' => display_editor('contents', $CONFIG_COM['forbidden_tags']),
							'L_LANGUAGE' => substr(get_ulang(), 0, 2),
							'L_EDIT_COMMENT' => $LANG['edit_comment'],
							'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
							'L_REQUIRE_TEXT' => $LANG['require_text'],
							'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
							'L_LOGIN' => $LANG['pseudo'],
							'L_MESSAGE' => $LANG['message'],
							'L_RESET' => $LANG['reset'],
							'L_PREVIEW' => $LANG['preview'],
							'L_PREVIEW' => $LANG['preview'],
							'L_SUBMIT' => $LANG['update'],
							'U_ACTION' => $this->path . sprintf($this->vars, $this->idcom) . '&updatecom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&path_to_root=' . $page_path_to_root : '')
						));
					}
					elseif ($updatecom) //Mise � jour du commentaire.
					{
						$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
						$login = retrieve(POST, 'login', '');
						
						if (!empty($contents) && !empty($login))
						{
							$contents = strparse($contents, $CONFIG_COM['forbidden_tags']);
							
							//Correction des chemins du BBCode
							if (!$integrated_in_environment && !empty($page_path_to_root))
								$contents = str_replace('"' . PATH_TO_ROOT . '/', '"' . $page_path_to_root . '/', $contents);
							
							if (!check_nbr_links($contents, $CONFIG_COM['max_link'])) //Nombre de liens max dans le message.
								redirect($path_redirect . '&errorh=l_flood#errorh');

							$this->update($contents, $login);
							
							//Succ�s redirection.
							redirect($path_redirect . '#m' . $this->idcom);
						}
						else //Champs incomplet!
							redirect($path_redirect . '&errorh=incomplete#errorh');
					}
					else
						redirect($path_redirect . '&errorh=incomplete#errorh');
				}
				else
					$Errorh->handler('e_auth', E_USER_REDIRECT);
			}
			elseif (isset($_GET['lock']) && $User->check_level(MODO_LEVEL)) //Verrouillage des commentaires.
			{
				$Session->csrf_get_protect();
				
				if ($User->check_level(MODO_LEVEL))
				{
					$lock = retrieve(GET, 'lock', 0);
					$this->lock($lock);
				}
				redirect($path_redirect . '#anchor_' . $this->script);
			}
			else
			{
				###########################Affichage##############################
				$get_quote = retrieve(GET, 'quote', 0);
				$contents = '';
				
				if ($get_quote > 0)
				{
					$info_com = $Sql->query_array(DB_TABLE_COM, 'login', 'contents', "WHERE script = '" . $this->script . "' AND idprov = '" . $this->idprov . "' AND idcom = '" . $get_quote . "'", __LINE__, __FILE__);
					$contents = '[quote=' . $info_com['login'] . ']' . $info_com['contents'] . '[/quote]';
				}

				//On cr�e une pagination si le nombre de commentaires est trop important.
				import('util/pagination');
				$pagination = new Pagination();

				$Template->assign_vars(array(
					'CURRENT_PAGE_COM' => $integrated_in_environment,
					'POPUP_PAGE_COM' => !$integrated_in_environment
				));
				
				//Affichage du lien de verrouillage/d�verrouillage.
				if ($User->check_level(MODO_LEVEL))
				{
					$Template->assign_vars(array(
						'COM_LOCK' => true,
						'IMG' => ($this->lock_com >= 1) ? 'unlock' : 'lock',
						'L_LOCK' => ($this->lock_com >= 1) ? $LANG['unlock'] : $LANG['lock'],
						'U_LOCK' => $this->path . (($this->lock_com >= 1) ? $vars_simple . '&amp;lock=0&amp;token=' . $Session->get_token() : $vars_simple . '&amp;lock=1&amp;token=' . $Session->get_token()) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')
					));
				}
				
				//Gestion des erreurs.
				$get_error = !empty($_GET['errorh']) ? trim($_GET['errorh']) :'';
				$errno = E_USER_NOTICE;
				switch ($get_error)
				{
					case 'auth':
						$errstr = $LANG['e_unauthorized'];
						$errno = E_USER_WARNING;
						break;
					case 'l_flood':
						$errstr = sprintf($LANG['e_l_flood'], $CONFIG_COM['max_link']);
						break;
					case 'l_pseudo':
						$errstr = $LANG['e_link_pseudo'];
						break;
					case 'flood':
						$errstr = $LANG['e_flood'];
						break;
					case 'incomplete':
						$errstr = $LANG['e_incomplete'];
						break;
					default:
						$errstr = '';
				}
				
				if (!empty($errstr))
					$Errorh->handler($errstr, E_USER_NOTICE);
				
				//Affichage du formulaire pour poster si les commentaires ne sont pas v�rrouill�
				if (!$this->lock_com || $User->check_level(MODO_LEVEL))
				{
					if ($User->check_level($CONFIG_COM['com_auth']))
						$Template->assign_vars(array(
							'AUTH_POST_COM' => true
						));
					else
						$Errorh->handler($LANG['e_unauthorized'], E_USER_NOTICE);
					
					//Pseudo du membre connect�.
					if ($User->get_attribute('user_id') !== -1)
						$Template->assign_vars(array(
							'C_HIDDEN_COM' => true,
							'LOGIN' => $User->get_attribute('login')
						));
					else
						$Template->assign_vars(array(
							'C_VISIBLE_COM' => true,
							'LOGIN' => $LANG['guest']
						));
				}
				else
					$Errorh->handler($LANG['com_locked'], E_USER_NOTICE);
				
				$get_pos = strpos($_SERVER['QUERY_STRING'], '&pc');
				
				if ($get_pos)
					$get_page = substr($_SERVER['QUERY_STRING'], 0, $get_pos) . '&amp;pc';
				else
					$get_page = $_SERVER['QUERY_STRING'] . '&amp;pc';
				
				$Template->assign_vars(array(
					'C_COM_DISPLAY' => $this->get_attribute('nbr_com') > 0 ? true : false,
					'PAGINATION_COM' => $pagination->display($this->path . $vars_simple . '&amp;pc=%d#anchor_' . $this->script, $this->nbr_com, 'pc', $CONFIG_COM['com_max'], 3),
					'LANG' => get_ulang(),
					'IDCOM' => '',
					'IDPROV' => $this->idprov,
					'SCRIPT' => $this->script,
					'PATH' => SCRIPT,
					'UPDATE' => ($integrated_in_environment == true) ? SID : '',
					'VAR' => $vars_simple,
					'KERNEL_EDITOR' => display_editor('contents', $CONFIG_COM['forbidden_tags']),
					'L_XML_LANGUAGE' => $LANG['xml_lang'],
					'L_TITLE' => ($CONFIG['com_popup'] == 0 || $integrated_in_environment === true) ? $LANG['title_com'] : '',
					'THEME' => get_utheme(),
					'CONTENTS' => unparse($contents),
					'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
					'L_ADD_COMMENT' => $LANG['add_comment'],
					'L_LOGIN' => $LANG['pseudo'],
					'L_MESSAGE' => $LANG['message'],
					'L_QUOTE' => $LANG['quote'],
					'L_RESET' => $LANG['reset'],
					'L_PREVIEW' => $LANG['preview'],
					'L_SUBMIT' => $LANG['submit'],
					'U_ACTION' => $this->path . sprintf($this->vars, $this->idcom) . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&path_to_root=' . $page_path_to_root : '')
				));
				
				//Cr�ation du tableau des rangs.
				$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
				
				//Gestion des rangs.
				$Cache->load('ranks');
				$j = 0;
				$result = $Sql->query_while("SELECT c.idprov, c.idcom, c.login, c.user_id, c.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, c.contents
				FROM " . DB_TABLE_COM . " c
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
				LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
				WHERE c.script = '" . $this->script . "' AND c.idprov = '" . $this->idprov . "'
				GROUP BY c.idcom
				ORDER BY c.timestamp DESC
				" . $Sql->limit($pagination->get_first_msg($CONFIG_COM['com_max'], 'pc'), $CONFIG_COM['com_max']), __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					$row['user_id'] = (int)$row['user_id'];
					$edit = '';
					$del = '';
					
					$is_guest = ($row['user_id'] === -1);
					$is_modo = $User->check_level(MODO_LEVEL);
					$warning = '';
					$readonly = '';
					if ($is_modo && !$is_guest) //Mod�ration.
					{
						$warning = '&nbsp;<a href="' . PATH_TO_ROOT . '/member/moderation_panel' . url('.php?action=warning&amp;id=' . $row['user_id'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')) . '" title="' . $LANG['warning_management'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>';
						$readonly = '<a href="' . PATH_TO_ROOT . '/member/moderation_panel' . url('.php?action=punish&amp;id=' . $row['user_id'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '')) . '" title="' . $LANG['punishment_management'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>';
					}
					
					//Edition/suppression.
					if ($is_modo || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
					{
						$edit = '&nbsp;&nbsp;<a href="' . $this->path . sprintf($this->vars, $row['idcom']) . '&editcom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
						$del = '&nbsp;&nbsp;<a href="' . $this->path . sprintf($this->vars, $row['idcom']) . '&amp;token=' . $Session->get_token() . '&amp;delcom=1' . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script . '" onclick="javascript:return Confirm();"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
					}
					
					//Pseudo.
					if (!$is_guest)
						$com_pseudo = '<a class="msg_link_pseudo" href="' . PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
					else
						$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
					
					//Rang de l'utilisateur.
					$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
					$user_group = $user_rank;
					$user_rank_icon = '';
					if ($row['level'] === '2') //Rang sp�cial (admins).
					{
						$user_rank = $_array_rank[-2][0];
						$user_group = $user_rank;
						$user_rank_icon = $_array_rank[-2][1];
					}
					elseif ($row['level'] === '1') //Rang sp�cial (modos).
					{
						$user_rank = $_array_rank[-1][0];
						$user_group = $user_rank;
						$user_rank_icon = $_array_rank[-1][1];
					}
					else
					{
						foreach ($_array_rank as $msg => $ranks_info)
						{
							if ($msg >= 0 && $msg <= $row['user_msg'])
							{
								$user_rank = $ranks_info[0];
								$user_rank_icon = $ranks_info[1];
								break;
							}
						}
					}
					
					//Image associ�e au rang.
					$user_assoc_img = !empty($user_rank_icon) ? '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
								
					//Affichage des groupes du membre.
					if (!empty($row['user_groups']) && $_array_groups_auth)
					{
						$user_groups = '';
						$array_user_groups = explode('|', $row['user_groups']);
						foreach ($_array_groups_auth as $idgroup => $array_group_info)
						{
							if (is_numeric(array_search($idgroup, $array_user_groups)))
								$user_groups .= !empty($array_group_info['img']) ? '<img src="' . PATH_TO_ROOT . '/images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
						}
					}
					else
						$user_groups = $LANG['group'] . ': ' . $user_group;
					
					//Membre en ligne?
					$user_online = !empty($row['connect']) ? 'online' : 'offline';
					
					//Avatar
					if (empty($row['user_avatar']))
						$user_avatar = ($CONFIG_USER['activ_avatar'] == '1' && !empty($CONFIG_USER['avatar_url'])) ? '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' .  $CONFIG_USER['avatar_url'] . '" alt="" />' : '';
					else
						$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
					
					//Affichage du sexe et du statut (connect�/d�connect�).
					$user_sex = '';
					if ($row['user_sex'] == 1)
						$user_sex = $LANG['sex'] . ': <img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/man.png" alt="" /><br />';
					elseif ($row['user_sex'] == 2)
						$user_sex = $LANG['sex'] . ': <img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
							
					//Nombre de message.
					$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];
					
					//Localisation.
					if (!empty($row['user_local']))
					{
						$user_local = $LANG['place'] . ': ' . $row['user_local'];
						$user_local = $user_local > 15 ? substr_html($user_local, 0, 15) . '...<br />' : $user_local . '<br />';
					}
					else $user_local = '';
					
					$contents = ucfirst(second_parse($row['contents']));
					
					//Correction des chemins du BBCode
					if (!$integrated_in_environment && !empty($page_path_to_root))
						$contents = str_replace('"' . $page_path_to_root . '/', '"' . PATH_TO_ROOT . '/', $contents);
					
					$Template->assign_block_vars('com_list', array(
						'ID' => $row['idcom'],
						'CONTENTS' => $contents,
						'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
						'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
						'USER_ONLINE' => '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
						'USER_PSEUDO' => $com_pseudo,
						'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
						'USER_IMG_ASSOC' => $user_assoc_img,
						'USER_AVATAR' => $user_avatar,
						'USER_GROUP' => $user_groups,
						'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
						'USER_SEX' => $user_sex,
						'USER_MSG' => !$is_guest ? $user_msg : '',
						'USER_LOCAL' => $user_local,
						'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',
						'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
						'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
						'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . second_parse($row['user_sign']) : '',
						'USER_WEB' => !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
						'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
						'PUNISHMENT' => $readonly,
						'DEL' => $del,
						'EDIT' => $edit,
						'U_USER_PM' => '<a href="' . PATH_TO_ROOT . '/member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>',
						'U_ANCHOR' => $this->path . $this->vars . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#m' . $row['idcom'],
						'U_QUOTE' => $this->path . sprintf($this->vars, $row['idcom']) . '&amp;quote=' . $row['idcom'] . ((!empty($page_path_to_root) && !$integrated_in_environment) ? '&amp;path_to_root=' . $page_path_to_root : '') . '#anchor_' . $this->script
					));
					$j++;
				}
				$Sql->query_close($result);
			}
			return $Template->parse(TEMPLATE_STRING_MODE);
		}
		else
			return 'error : class Comments loaded uncorrectly';
	}
	
	## Private Methods ##
	//R�cup�ration de la table du module associ�e aux commentaires.
	function _get_info_module()
	{
		global $Sql, $CONFIG;

		//R�cup�ration des informations sur le module.
		$info_module = load_ini_file(PATH_TO_ROOT . '/' . $this->module_folder . '/lang/', get_ulang());
		
		$check_script = false;
		if (isset($info_module['com']))
		{
			if ($info_module['com'] == $this->script)
			{
				$info_sql_module = $Sql->query_array(PREFIX . strprotect($info_module['com']), "id", "nbr_com", "lock_com", "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				if ($info_sql_module['id'] == $this->idprov)
					$check_script = true;
			}
		}
		return $check_script ? array(strprotect($info_module['com']), $info_sql_module['nbr_com'], (bool)$info_sql_module['lock_com']) : array('', 0, 0);
	}
	
	//Initialisation des param�tres quand il s'agit du noyau qui appelle
	function _get_info_kernel_script()
	{
		global $Sql, $CONFIG;
		
		$row_infos = $Sql->query_array(PREFIX . $this->script, "id", "nbr_com", "lock_com", "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return array($this->script, $row_infos['nbr_com'], (bool)$row_infos['lock_com']);
	}
	
	## Private attributes ##
	var $script = '';
	var $idprov = 0;
	var $idcom = 0;
	var $path = '';
	var $vars = '';
	var $module_folder = '';
	var $sql_table = '';
	var $nbr_com = 0;
	var $lock_com = 0;
	var $is_kernel_script = false;
}

?>