<?php
/*##################################################
 *                                member.php
 *                            -------------------
 *   begin                : August 04 2005
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

require_once('../includes/begin.php'); 
define('TITLE', $LANG['member_area']);

$edit_get = !empty($_GET['edit']) ? trim($_GET['edit']) : '';
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';

$title_mbr = !empty($id_get) ? (!empty($edit_get) ? $LANG['profil_edit'] : $LANG['index']) : $LANG['member_s'];
$Speed_bar->Add_link($LANG['member_area'], transid('member.php?id=' . $Member->Get_attribute('user_id') . '&amp;view=1', 'member-' . $Member->Get_attribute('user_id') . '.php?view=1'));
$Speed_bar->Add_link($title_mbr, '');

require_once('../includes/header.php'); 

$view_get = !empty($_GET['view']) ? trim($_GET['view']) : '';
$view_msg = !empty($_GET['msg']) ? numeric($_GET['msg']) : '';
$show_group = !empty($_GET['g']) ? numeric($_GET['g']) : '';
$post_group = !empty($_POST['show_group']) ? numeric($_POST['show_group']) : '';
$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
$get_l_error = !empty($_GET['erroru']) ? trim($_GET['erroru']) : '';

if( !empty($id_get) ) //Espace membre
{	
	$Template->Set_filenames(array(
		'member' => '../templates/' . $CONFIG['theme'] . '/member.tpl'
	));

	if( !empty($edit_get) && $Member->Get_attribute('user_id') === $id_get && ($Member->Check_level(0)) ) //Edition du profil
	{
		//Update profil
		$row = $Sql->Query_array('member', 'user_lang', 'user_theme', 'user_mail', 'user_local', 'user_web', 'user_occupation', 'user_hobbies', 'user_avatar', 'user_show_mail', 'user_editor', 'user_timezone', 'user_sex', 'user_born', 'user_sign', 'user_desc', 'user_msn', 'user_yahoo', "WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
		
		$user_born = '';
		$array_user_born = explode('-', $row['user_born']);
		$date_birth = explode('/', $LANG['date_birth_parse']);
		for($i = 0; $i < 3; $i++)
		{
			if( $date_birth[$i] == 'DD' )
			{	
				$user_born .= $array_user_born[2 - $i];
				$born_day = $array_user_born[2 - $i];
			}
			elseif( $date_birth[$i] == 'MM' )
			{	
				$user_born .= $array_user_born[2 - $i];
				$born_month = $array_user_born[2 - $i];
			}
			elseif( $date_birth[$i] == 'YYYY' )	
			{
				$user_born .= $array_user_born[2 - $i];				
				$born_year = $array_user_born[2 - $i];
			}
			$user_born .= ($i != 2) ? '/' : '';	
		}
		
		$user_sex = '';
		if( !empty($row['user_sex']) )
			$user_sex = ($row['user_sex'] == 1) ? '../templates/' . $CONFIG['theme'] . '/images/man.png' : '../templates/' . $CONFIG['theme'] . '/images/woman.png';
	
		$Template->Assign_vars(array(
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_MEMBER_AREA' => $LANG['member_area'],
			'L_PROFIL_EDIT' => $LANG['profil_edit'],
			'L_REQUIRE' => $LANG['require'],
			'L_MAIL' => $LANG['mail'],
			'L_VALID' => $LANG['valid'],
			'L_PREVIOUS_PASS' => $LANG['previous_pass'],
			'L_EDIT_JUST_IF_MODIF' => $LANG['edit_if_modif'],
			'L_NEW_PASS' => $LANG['new_pass'],
			'L_CONFIRM_PASS' => $LANG['confirm_pass'],
			'L_DEL_MEMBER' => $LANG['del_member'],
			'L_LANG_CHOOSE' => $LANG['choose_lang'],
			'L_OPTIONS' => $LANG['options'],
			'L_THEME_CHOOSE' => $LANG['choose_theme'],
			'L_EDITOR_CHOOSE' => $LANG['choose_editor'],
			'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
			'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
			'L_HIDE_MAIL' => $LANG['hide_mail'],
			'L_HIDE_MAIL_WHO' => $LANG['hide_mail_who'],
			'L_INFO' => $LANG['info'],
			'L_SITE_WEB' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],
			'L_JOB' => $LANG['job'],
			'L_HOBBIES' => $LANG['hobbies'],
			'L_SEX' => $LANG['sex'],
			'L_DATE_OF_BIRTH' => $LANG['date_of_birth'],
			'L_DATE_FORMAT' => $LANG['date_birth_format'],
			'L_BIOGRAPHY' => $LANG['biography'],
			'L_YEARS_OLD' => $LANG['years_old'],
			'L_SIGN' => $LANG['sign'],
			'L_SIGN_WHERE' => $LANG['sign_where'],
			'L_CONTACT' => $LANG['contact'],
			'L_AVATAR_MANAGEMENT' => $LANG['avatar_gestion'],
			'L_CURRENT_AVATAR' => $LANG['current_avatar'],
			'L_WEIGHT_MAX' => $LANG['weight_max'],
			'L_HEIGHT_MAX' => $LANG['height_max'],
			'L_WIDTH_MAX' => $LANG['width_max'],
			'L_UPLOAD_AVATAR' => $LANG['upload_avatar'],
			'L_UPLOAD_AVATAR_WHERE' => $LANG['upload_avatar_where'],
			'L_AVATAR_LINK' => $LANG['avatar_link'],
			'L_AVATAR_LINK_WHERE' => $LANG['avatar_link_where'],
			'L_AVATAR_DEL' => $LANG['avatar_del'],
			'L_UPDATE' => $LANG['update'],
			'L_RESET' => $LANG['reset']
		));
		
		$Template->Assign_block_vars('update', array(
			'USER_THEME' => $row['user_theme'],
			'MAIL' => $row['user_mail'],
			'LOCAL' => $row['user_local'],
			'WEB' => $row['user_web'],
			'OCCUPATION' => $row['user_occupation'],
			'HOBBIES' => $row['user_hobbies'],			
			'USER_AVATAR' => (!empty($row['user_avatar'])) ? '<img src="' . $row['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>',
			'SHOW_MAIL_CHECKED' => ($row['user_show_mail'] == 0) ? 'checked="checked"' : '',
			'USER_BORN' => $user_born,
			'BORN_DAY' => $born_day,
			'BORN_MONTH' => $born_month,
			'BORN_YEAR' => $born_year,
			'USER_SEX' => !empty($user_sex) ? '<img src="' . $user_sex . '" alt="" />' : '',
			'USER_SIGN' => unparse($row['user_sign'], NO_EDITOR_UNPARSE),
			'USER_DESC' => unparse($row['user_desc'], NO_EDITOR_UNPARSE),
			'USER_MSN' => $row['user_msn'],
			'USER_YAHOO' => $row['user_yahoo'],
			'U_MEMBER_ACTION_UPDATE' => transid('.php?id=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php')
		));
				
		//Gestion langue par d�faut.
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		$result = $Sql->Query_while("SELECT lang 
		FROM ".PREFIX."lang
		WHERE activ = 1 AND secure <= '" . $Member->Get_attribute('level') . "'", __LINE__, __FILE__);
		while( $row2 = $Sql->Sql_fetch_assoc($result) )
		{	
			$lang_info = load_ini_file('../lang/', $row2['lang']);
			if( $lang_info )
			{
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $row2['lang'];
				$array_identifier .= 'array_identifier[\'' . $row2['lang'] . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
				$selected = '';
				if( $row2['lang'] == $row['user_lang'] )
				{
					$selected = 'selected="selected"';
					$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
				}			
				$Template->Assign_block_vars('update.select_lang', array(
					'LANG' => '<option value="' . $row2['lang'] . '" ' . $selected . '>' . $lang_name . '</option>'
				));
			}
		}
		$Sql->Close($result);
		
		$Template->Assign_vars(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier
		));
		
		//Gestion th�me par d�faut.
		if( $CONFIG_MEMBER['force_theme'] == 0 ) //Th�mes aux membres autoris�s.
		{
			$result = $Sql->Query_while("SELECT theme 
			FROM ".PREFIX."themes
			WHERE activ = 1 AND secure <= '" . $Member->Get_attribute('level') . "'", __LINE__, __FILE__);
			while( $row2 = $Sql->Sql_fetch_assoc($result) )
			{	
				$theme_info = load_ini_file('../templates/' . $row2['theme'] . '/config/', $CONFIG['lang']);
				if( $theme_info )
				{
					$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $row2['theme'];
					$selected = ($row2['theme'] == $row['user_theme']) ? 'selected="selected"' : '';
					$Template->Assign_block_vars('update.select_theme', array(
						'THEME' => '<option value="' . $row2['theme'] . '" ' . $selected . '>' . $theme_name . '</option>'
					));
				}
			}
			$Sql->Close($result);	
		}
		else //Th�me par d�faut forc�.
		{
			$theme_info = load_ini_file('../templates/' . $CONFIG['theme'] . '/config/', $CONFIG['lang']);
			$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $CONFIG['theme'];
			$Template->Assign_block_vars('update.select_theme', array(
				'THEME' => '<option value="' . $CONFIG['theme'] . '" selected="selected">' . $theme_name . '</option>'
			));
		}
		
		//Editeur texte par d�faut.
		$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
		$select_editors = '';
		foreach($editors as $code => $name)
		{
			$selected = ($code == $row['user_editor']) ? 'selected="selected"' : '';
			$select_editors .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
		}
		$Template->Assign_block_vars('update.select_editor', array(
			'SELECT_EDITORS' => $select_editors
		));
		
		//Gestion fuseau horaire par d�faut.
		$select_timezone = '';
		for($i = -12; $i <= 14; $i++)
		{
			$selected = ($i == $row['user_timezone']) ? 'selected="selected"' : '';
			$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
			$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
		}
		$Template->Assign_block_vars('update.select_timezone', array(
			'SELECT_TIMEZONE' => $select_timezone
		));
					
		//Sex par d�faut
		$array_sex = array('--', $LANG['male'], $LANG['female']);
		$i = 0;
		foreach($array_sex as $value_sex)
		{		
			$selected = ($i == $row['user_sex']) ? 'selected="selected"' : '';

			$Template->Assign_block_vars('update.select_sex', array(
				'SEX' => '<option value="' . $i . '" ' . $selected . '>' . $value_sex . '</option>'
			));
			
			$i++;
		}
		
		//Autorisation d'uploader un avatar sur le serveur.
		if( $CONFIG_MEMBER['activ_up_avatar'] == 1 )
		{
			$Template->Assign_block_vars('update.upload_avatar', array(			
				'WEIGHT_MAX' => $CONFIG_MEMBER['weight_max'],
				'HEIGHT_MAX' => $CONFIG_MEMBER['height_max'],
				'WIDTH_MAX' => $CONFIG_MEMBER['width_max']
			));
		}
		
		//Champs suppl�mentaires.
		$extend_field_exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
		if( $extend_field_exist > 0 )
		{
			$Template->Assign_vars(array(			
				'L_MISCELLANEOUS' => $LANG['miscellaneous']
			));
			$Template->Assign_block_vars('update.miscellaneous', array(			
			));
			$result = $Sql->Query_while("SELECT exc.name, exc.contents, exc.field, exc.require, exc.field_name, exc.possible_values, exc.default_values, ex.*
			FROM ".PREFIX."member_extend_cat exc
			LEFT JOIN ".PREFIX."member_extend ex ON ex.user_id = '" . $id_get . "'
			WHERE exc.display = 1
			ORDER BY exc.class", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{	
				// field: 0 => base de donn�es, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
				$field = '';
				$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
				switch($row['field'])
				{
					case 1:
					$field = '<label><input type="text" size="30" id="' . $row['field_name'] . '" name="' . $row['field_name'] . '" class="text" value="' . $row[$row['field_name']] . '" /></label>';
					break;
					case 2:
					$field = '<label><textarea class="post" rows="4" cols="27" id="' . $row['field_name'] . '" name="' . $row['field_name'] . '"> ' . unparse($row[$row['field_name']]) . '</textarea></label>';
					break;
					case 3:
					$field = '<label><select name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$i = 0;
					foreach($array_values as $values)
					{
						$selected = ($values == $row[$row['field_name']]) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select></label>';
					break;
					case 4:
					$field = '<label><select name="' . $row['field_name'] . '[]" multiple="multiple" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row[$row['field_name']]);
					$i = 0;
					foreach($array_values as $values)
					{
						$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select></label>';
					break;
					case 5:
					$array_values = explode('|', $row['possible_values']);
					foreach($array_values as $values)
					{
						$checked = ($values == $row[$row['field_name']]) ? 'checked="checked"' : '';
						$field .= '<label><input type="radio" name="' . $row['field_name'] . '" value="' . $values . '" id="' . $row['field_name'] . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
					}
					break;
					case 6:
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row[$row['field_name']]);
					$i = 0;
					foreach($array_values as $values)
					{
						$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
						$field .= '<label><input type="checkbox" name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
						$i++;
					}
					break;
				}				
				
				$Template->Assign_block_vars('update.miscellaneous.list', array(
					'NAME' => $row['require'] ? '* ' . ucfirst($row['name']) : ucfirst($row['name']),
					'ID' => $row['field_name'],
					'DESC' => !empty($row['contents']) ? ucfirst($row['contents']) : '',
					'FIELD' => $field
				));
			}
			$Sql->Close($result);	
		}
		
		//Gestion des erreurs.
		switch($get_error)
		{
			case 'pass_mini':
			$errstr = $LANG['e_pass_mini'];
			break;
			case 'pass_same':
			$errstr = $LANG['e_pass_same'];
			break;
			case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
			case 'invalid_mail':
			$errstr = $LANG['e_mail_invalid'];
			break;
			case 'auth_mail':
			$errstr = $LANG['e_mail_auth'];
			break;
			default:
			$errstr = '';
		}
		if( !empty($errstr) )
			$Errorh->Error_handler($errstr, E_USER_NOTICE);  

		if( isset($LANG[$get_l_error]) )
			$Errorh->Error_handler($LANG[$get_l_error], E_USER_WARNING);
	}
	elseif( !empty($_POST['valid']) && ($Member->Get_attribute('user_id') === $id_get) && ($Member->Check_level(0)) ) //Update du profil
	{
		$check_pass = !empty($_POST['pass']) ? true : false;
		$check_pass_bis = !empty($_POST['pass_bis']) ? true : false;
		
		//Changement de password
		if( $check_pass && $check_pass_bis )
		{			
			$password_old_md5 = !empty($_POST['pass_old']) ? md5($_POST['pass_old']) : '';			
			$password = !empty($_POST['pass']) ? trim($_POST['pass']) : '';
			$password_md5 = !empty($password) ? md5($password) : '';
			$password_bis = !empty($_POST['pass_bis']) ? trim($_POST['pass_bis']) : '';
			$password_bis_md5 = !empty($password_bis) ? md5($password_bis) : '';				
			$password_old_bdd = $Sql->Query("SELECT password FROM ".PREFIX."member WHERE user_id = '" . $Member->Get_attribute('user_id') . "'",  __LINE__, __FILE__);
			
			if( !empty($password_old_md5) && !empty($password_md5) && !empty($password_bis_md5) )
			{
				if( $password_old_md5 === $password_old_bdd && $password_md5 === $password_bis_md5 )
				{
					if( strlen($password) >= 6 && strlen($password_bis) >= 6 )
					{
						$Sql->Query_inject("UPDATE ".PREFIX."member SET password = '" . $password_md5 . "' WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__); 
					}
					else //Longueur minimale du password
						redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&error=pass_mini') . '#errorh');
				}
				else //Password non identiques.
					redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&error=pass_same') . '#errorh');
			}
		}
		
		if( $_POST['del_member'] === 'on' ) //Suppression du compte
		{
			$Sql->Query_inject("DELETE FROM ".PREFIX."member WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
			
			//On r�g�n�re le cache
			$Cache->Generate_file('stats');
		}
		
		//Mise � jour du reste de la config.
		$mail = strtolower($_POST['mail']); //Mail en minuscule.
		if( preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", $mail) )
		{
			$user_lang = !empty($_POST['user_lang']) ? securit($_POST['user_lang']) : '';
			$user_theme = !empty($_POST['user_theme']) ? securit($_POST['user_theme']) : '';
			$user_editor = !empty($_POST['user_editor']) ? securit($_POST['user_editor']) : '';
			$user_timezone = !empty($_POST['user_timezone']) ? numeric($_POST['user_timezone']) : '';
			
			$user_mail = securit($mail);
			$user_show_mail = !empty($_POST['user_show_mail']) ? '0' : '1';
			$user_local = !empty($_POST['user_local']) ? securit($_POST['user_local']) : '';
			$user_occupation =  !empty($_POST['user_occupation']) ? securit($_POST['user_occupation']) : '';
			$user_hobbies = !empty($_POST['user_hobbies']) ? securit($_POST['user_hobbies']) : '';
			$user_desc = !empty($_POST['user_desc']) ? parse($_POST['user_desc']) : '';
			$user_sex = !empty($_POST['user_sex']) ? numeric($_POST['user_sex']) : 0;
			$user_sign = !empty($_POST['user_sign']) ? parse($_POST['user_sign']) : '';
			$user_msn = !empty($_POST['user_msn']) ? securit($_POST['user_msn']) : '';
			$user_yahoo = !empty($_POST['user_yahoo']) ? securit($_POST['user_yahoo']) : '';
			
			$user_web = !empty($_POST['user_web']) ? securit($_POST['user_web']) : '';
			$user_web = ( !empty($user_web) && preg_match('!^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$!', trim($_POST['user_web'])) ) ? $user_web : '';
			
			//Gestion de la date de naissance.
			$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
		
			//Gestion de la suppression de l'avatar.
			if( !empty($_POST['delete_avatar']) )
			{
				$user_avatar_path = $Sql->Query("SELECT user_avatar FROM ".PREFIX."member WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
				if( !empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match) )
				{
					if( is_file($user_avatar_path) && isset($match[1]) )
						@unlink('../images/avatars/' . $match[1]);
				}	
				
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_avatar = '' WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
			}

			//Gestion upload d'avatar.					
			$user_avatar = '';
			$dir = '../images/avatars/';
			include_once('../includes/upload.class.php');
			$Upload = new Upload($dir);
			
			if( is_writable($dir) && $CONFIG_MEMBER['activ_up_avatar'] == 1 )
			{
				if( $_FILES['avatars']['size'] > 0 )
				{
					$Upload->Upload_file('avatars', '`([a-z0-9])+\.(jpg|gif|png|bmp)+`i', UNIQ_NAME, $CONFIG_MEMBER['weight_max']*1024);
					if( !empty($Upload->error) ) //Erreur, on arr�te ici
						redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&erroru=' . $Upload->error) . '#errorh');
					else
					{
						$path = $dir . $Upload->filename['avatars'];
						$error = $Upload->Validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
						if( !empty($error) ) //Erreur, on arr�te ici
							redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&erroru=' . $error) . '#errorh');
						else
						{
							//Suppression de l'ancien avatar (sur le serveur) si il existe!
							$user_avatar_path = $Sql->Query("SELECT user_avatar FROM ".PREFIX."member WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
							if( !empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match) )
							{
								if( is_file($user_avatar_path) && isset($match[1]) )
									@unlink('../images/avatars/' . $match[1]);
							}						
							$user_avatar = $path; //Avatar upload� et valid�.
						}
					}
				}
			}
			
			if( !empty($_POST['avatar']) )
			{
				$path = securit($_POST['avatar']);
				$error = $Upload->Validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
				if( !empty($error) ) //Erreur, on arr�te ici
					redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&erroru=' . $error) . '#errorh');
				else
					$user_avatar = $path; //Avatar post� et valid�.
			}
			$user_avatar = !empty($user_avatar) ? " user_avatar = '" . $user_avatar . "', " : '';
			
			if( !empty($user_mail) )
			{
				$check_mail = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member WHERE user_mail = '" . $user_mail . "' AND login <> '" . addslashes($Member->Get_attribute('login')) . "'", __LINE__, __FILE__);		
				$user_mail = "user_mail = '" . $user_mail . "', ";				
				if( $check_mail >= 1 ) //Autre utilisateur avec le m�me mail!
					redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&error=auth_mail') . '#errorh');
				
				//Suppression des images des stats concernant les membres, si l'info � �t� modifi�e.
				$info_mbr = $Sql->Query_array("member", "user_theme", "user_sex", "WHERE user_id = '" . numeric($Member->Get_attribute('user_id')) . "'", __LINE__, __FILE__);
				if( $info_mbr['user_sex'] != $user_sex )
					@unlink('../cache/sex.png');
				if( $info_mbr['user_theme'] != $user_theme )
					@unlink('../cache/theme.png');
				
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_lang = '" . $user_lang . "', user_theme = '" . $user_theme . "', 
				" . $user_mail . "user_show_mail = '" . $user_show_mail . "', user_editor = '" . $user_editor . "', user_timezone = '" . $user_timezone . "', user_local = '" . $user_local . "', 
				" . $user_avatar . "user_msn = '" . $user_msn . "', user_yahoo = '" . $user_yahoo . "',	
				user_web = '" . $user_web . "', user_occupation = '" . $user_occupation . "', user_hobbies = '" . $user_hobbies . "', 
				user_desc = '" . $user_desc . "', user_sex = '" . $user_sex . "', user_born = '" . $user_born . "', 
				user_sign = '" . $user_sign . "' WHERE user_id = '" . numeric($Member->Get_attribute('user_id')) . "'", __LINE__, __FILE__); 
				
				//Champs suppl�mentaires.
				$extend_field_exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
				if( $extend_field_exist > 0 )
				{
					$req_update = '';
					$req_field = '';
					$req_insert = '';
					$result = $Sql->Query_while("SELECT field_name, field, possible_values, regex
					FROM ".PREFIX."member_extend_cat
					WHERE display = 1", __LINE__, __FILE__);
					while( $row = $Sql->Sql_fetch_assoc($result) )
					{
						$field = isset($_POST[$row['field_name']]) ? trim($_POST[$row['field_name']]) : '';
						//Validation par expressions r�guli�res.
						if( is_numeric($row['regex']) && $row['regex'] >= 1 && $row['regex'] <= 5)
						{
							$array_regex = array(
								1 => '`^[0-9]+$`',
								2 => '`^[a-z]+$`',
								3 => '`^[a-z0-9]+$`',
								4 => '`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`',
								5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`'
							);
							$row['regex'] = $array_regex[$row['regex']];
						}
						
						$valid_field = true;
						if( !empty($row['regex']) && $row['field'] <= 2 )
						{
							if( @preg_match($row['regex'], trim($field)) )
								$valid_field = true;
							else
								$valid_field = false;
						}
						
						if( $row['field'] == 2 )
							$field = parse($field);
						elseif( $row['field'] == 4 )
						{
							$array_field = is_array($field) ? $field : array();
							$field = '';
							foreach($array_field as $value)
								$field .= securit($value) . '|';
						}
						elseif( $row['field'] == 6 )
						{
							$field = '';
							$i = 0;
							$array_possible_values = explode('|', $row['possible_values']);
							foreach($array_possible_values as $value)
							{
								$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? addslashes($value) . '|' : '';
								$i++;
							}
						}
						else
							$field = securit($field);
							
						if( !empty($field) )
						{
							if( $valid_field ) //Validation par expression r�guli�re si pr�sente.
							{
								$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
								$req_field .= $row['field_name'] . ', ';
								$req_insert .= '\'' . trim($field, '|') . '\', ';
							}
						}
					}
					$Sql->Close($result);	
					
					$check_member = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend WHERE user_id = '" . numeric($Member->Get_attribute('user_id')) . "'", __LINE__, __FILE__);
					if( $check_member )
					{	
						if( !empty($req_update) )
							$Sql->Query_inject("UPDATE ".PREFIX."member_extend SET " . trim($req_update, ', ') . " WHERE user_id = '" . numeric($Member->Get_attribute('user_id')) . "'", __LINE__, __FILE__); 
					}
					else
					{	
						if( !empty($req_insert) )
							$Sql->Query_inject("INSERT INTO ".PREFIX."member_extend (user_id, " . trim($req_field, ', ') . ") VALUES ('" . numeric($Member->Get_attribute('user_id')) . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
					}
				}
				
				redirect(HOST . DIR . '/member/member' . transid('.php?id=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php', '&'));
			}
			else
				redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&error=incomplete') . '#errorh');
		}	
		else
			redirect(HOST . DIR . '/member/member' . transid('.php?id=' .  $id_get . '&edit=1&error=invalid_mail') . '#errorh');
	}	
	elseif( !empty($view_get) && $Member->Get_attribute('user_id') === $id_get && ($Member->Check_level(0)) ) //Zone membre
	{
		//Info membre
		$msg_mbr = !empty($CONFIG_MEMBER['msg_mbr']) ? $CONFIG_MEMBER['msg_mbr'] : ''; //On parse le message!
		$msg_mbr = '<br />' . $msg_mbr . '<br />';
	
		//Chargement de la configuration.
		$Cache->Load_file('files');

		//Droit d'acc�s?.
		$is_auth_files = $Member->Check_auth($CONFIG_FILES['auth_files'], AUTH_FILES);
	
		$Template->Assign_vars(array(
			'SID' => SID,
			'LANG' => $CONFIG['lang'],
			'COLSPAN' => $is_auth_files ? 3 : 2,
			'L_PROFIL' => $LANG['profil'],
			'L_WELCOME' => $LANG['welcome'],
			'L_PROFIL_EDIT' => $LANG['profil_edit'],
			'L_FILES_MANAGEMENT' => $LANG['files_management'],
			'L_PRIVATE_MESSAGE' =>  $LANG['private_message']
		));
		
		$Template->Assign_block_vars('msg_mbr', array(
			'USER_NAME' => $Member->Get_attribute('login'),
			'PM' => $Member->Get_attribute('user_pm'),
			'IMG_PM' => ($Member->Get_attribute('user_pm') > 0) ? 'new_pm.gif' : 'pm.png',
			'MSG_MBR' => $msg_mbr,
			'U_MEMBER_ID' => transid('.php?id=' . $Member->Get_attribute('user_id') . '&amp;edit=true'),
			'U_MEMBER_PM' => transid('.php?pm=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php')
		));
		
		//Affichage du lien vers l'interface des fichiers.
		if( $is_auth_files )
		{
			$Template->Assign_block_vars('msg_mbr.files_management', array(
			));
		}
	}
	else  //Profil public du membre.
	{
		$row = $Sql->Query_array('member', 'user_id', 'level', 'login', 'user_groups', 'user_mail', 'user_local', 'user_web', 'user_occupation', 'user_hobbies', 'user_avatar', 'user_show_mail', 'timestamp', 'user_sex', 'user_born', 'user_sign', 'user_desc', 'user_msn', 'user_msg', 'user_yahoo', 'last_connect', 'user_ban', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		$user_born = $Sql->Query("SELECT " . $Sql->Sql_date_diff('user_born') . " FROM ".PREFIX."member WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		if( empty($row['user_id']) ) //V�rification de l'existance du membre. 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		
		//Derni�re connexion, si vide => date d'enregistrement du membre.
		$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp']; 
	
		$user_mail = ( $row['user_show_mail'] == 1 ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
		
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '&nbsp;';
		$user_avatar = !empty($row['user_avatar']) ? '<img src="' . $row['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>';
		
		$user_sex = !empty($row['user_sex']) ? $row['user_sex'] : '&nbsp;';
		switch($user_sex)
		{		
			case 0:
			$user_sex = '&nbsp;';
			break;
			case 1:
			$user_sex = $LANG['male'] . ' <img src="../templates/' . $CONFIG['theme'] . '/images/man.png" alt="" style="vertical-align:middle;" />';
			break;
			case 2:
			$user_sex = $LANG['female'] . ' <img src="../templates/' . $CONFIG['theme'] . '/images/woman.png" alt="" style="vertical-align:middle;" />';
			break;
			default:
			$user_sex = '&nbsp;';
		}
		
		switch($row['level'])
		{		
			case 0:
			$user_rank = $LANG['member'];
			break;
			case 1:
			$user_rank = $LANG['modo'];
			break;
			case 2:
			$user_rank = $LANG['admin'];
			break;
		}
		
		$Template->Assign_vars(array(
			'LANG' => $CONFIG['lang'],
			'L_PROFIL' => $LANG['profil'],
			'L_PROFIL_EDIT' => $LANG['profil_edit'],
			'L_AVATAR' => $LANG['avatar'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_STATUT' => $LANG['status'],
			'L_GROUPS' => $LANG['groups'],
			'L_REGISTERED' => $LANG['registered_on'],
			'L_LAST_CONNECT' => $LANG['last_connect'],
			'L_NBR_MESSAGE' => $LANG['nbr_message'],			
			'L_WEB_SITE' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],
			'L_JOB' => $LANG['job'],
			'L_HOBBIES' => $LANG['hobbies'],
			'L_SEX' => $LANG['sex'],
			'L_AGE' => $LANG['age'],
			'L_BIOGRAPHY' => $LANG['biography'],
			'L_CONTACT' => $LANG['contact'],
			'L_MAIL' => $LANG['mail'],
			'L_PRIVATE_MESSAGE' => $LANG['private_message']
		));							
				
		$Template->Assign_block_vars('profil', array(
			'USER_NAME' => $row['login'],
			'MAIL' => $user_mail,
			'STATUT' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
			'USER_AVATAR' => $user_avatar,
			'USER_MSG' => $row['user_msg'],			
			'LOCAL' => !empty($row['user_local']) ? $row['user_local'] : '&nbsp;',
			'WEB' => $user_web,
			'OCCUPATION' => !empty($row['user_occupation']) ? $row['user_occupation'] : '&nbsp;',
			'HOBBIES' => !empty($row['user_hobbies']) ? $row['user_hobbies'] : '&nbsp;',
			'USER_SEX' =>  $user_sex,
			'USER_AGE' => ($row['user_born'] != '0000-00-00' && $user_born > 0 && $user_born < 125 ) ? $user_born . ' ' . $LANG['years_old'] : $LANG['unknow'],
			'USER_DESC' => !empty($row['user_desc']) ? $row['user_desc'] : '&nbsp;',
			'USER_MSN' => !empty($row['user_msn']) ? $row['user_msn'] : '&nbsp;',
			'USER_YAHOO' => !empty($row['user_yahoo']) ? $row['user_yahoo'] : '&nbsp;',
			'U_MEMBER_PM' => transid('.php?pm=' . $id_get, '-' . $id_get . '.php')
		));
				
		//Liste des groupes du membre.		
		$i = 0;
		$user_groups = explode('|', $row['user_groups']);
		foreach($user_groups as $key => $group_id)
		{
			$group = $Sql->Query_array('group', 'id', 'name', 'img', "WHERE id = '" . numeric($group_id) . "'", __LINE__, __FILE__);
			if( !empty($group['id']) )
			{	
				
				$Template->Assign_block_vars('profil.groups', array(
					'USER_GROUP' => ($i != 0 ? '<br /><br />' : '') . '<a href="member' . transid('.php?g=' . $group_id, '-0.php?g=' . $group_id) . '">' . (!empty($group['img']) ? '<img src="../images/group/' . $group['img'] . '" alt="' . $group['name'] . '" title="' . $group['name'] . '" style="vertical-align:middle;" />'  : '') . '</a> <a href="member' . transid('.php?g=' . $group_id, '-0.php?g=' . $group_id) . '">' . $group['name'] . '</a>'
				));
			}
			$i++;
		}
		if( $i == 0 ) 
		{	
			$Template->Assign_block_vars('profil.groups', array(
					'USER_GROUP' => $LANG['member']
			));
		}
		
		if( $Member->Get_attribute('user_id') === $id_get )
		{
			$Template->Assign_block_vars('profil.edit', array(
				'THEME' => $CONFIG['theme'],
				'U_MEMBER_SCRIPT' => HOST . DIR . '/member/member' . transid('.php?id=' . $Member->Get_attribute('user_id') . '&amp;edit=1')
			));
		}
		elseif( $Member->Get_attribute('level') === 2 )
		{ 
			$Template->Assign_block_vars('profil.edit', array(
				'THEME' => $CONFIG['theme'],
				'U_MEMBER_SCRIPT' => HOST . DIR . '/admin/admin_members.php?id=' . $id_get . '&amp;edit=1'
			));
		}
		
		//Champs suppl�mentaires.
		$extend_field_exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
		if( $extend_field_exist > 0 )
		{
			$Template->Assign_vars(array(			
				'L_MISCELLANEOUS' => $LANG['miscellaneous']
			));
			$Template->Assign_block_vars('profil.miscellaneous', array(			
			));
			$result = $Sql->Query_while("SELECT exc.name, exc.contents, exc.field, exc.field_name, exc.possible_values, exc.default_values, ex.*
			FROM ".PREFIX."member_extend_cat exc
			LEFT JOIN ".PREFIX."member_extend ex ON ex.user_id = '" . $id_get . "'
			WHERE exc.display = 1
			ORDER BY exc.class", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{	
				// field: 0 => base de donn�es, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
				$field = '';
				$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
				switch($row['field'])
				{
					case 1:
						$field = $row[$row['field_name']];
					break;
					case 2:
						$field = second_parse($row[$row['field_name']]);
					break;
					case 3:
						$field = $row[$row['field_name']];
					break;
					case 4:
						$field = implode(', ', explode('|', $row[$row['field_name']]));
					break;
					case 5:
						$field = $row[$row['field_name']];
					break;
					case 6:
						$field = implode(', ', explode('|', $row[$row['field_name']]));
					break;
				}				
				
				$Template->Assign_block_vars('profil.miscellaneous.list', array(
					'NAME' => ucfirst($row['name']),
					'DESC' => !empty($row['contents']) ? $row['contents'] : '',
					'FIELD' => $field
				));
			}
			$Sql->Close($result);	
		}
	}

	$Template->Pparse('member');
}
elseif( !empty($show_group) || !empty($post_group) ) //Vue du groupe.
{
	$user_group = !empty($show_group) ? $show_group : $post_group;
	
	$Template->Set_filenames(array(
		'member' => '../templates/' . $CONFIG['theme'] . '/member.tpl'
	));
	
	$group = $Sql->Query_array('group', 'name', 'img', "WHERE id = '" . $user_group . "'", __LINE__, __FILE__);
	
	$Template->Assign_vars(array(
		'SID' => SID,
		'L_BACK' => $LANG['back'],
		'L_SELECT_GROUP' => $LANG['select_group'],
		'L_LIST' => $LANG['liste'],
		'L_SEARCH' => $LANG['search'],
		'L_AVATAR' => $LANG['avatar'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_STATUT' => $LANG['status'],
		'U_SELECT_SHOW_GROUP' => "'member.php?g=' + this.options[this.selectedIndex].value"
	));
		
	$Template->Assign_block_vars('group', array(
		'ADMIN_GROUPS' => ($Member->Check_level(2)) ? '<a href="' . HOST . DIR . '/admin/admin_groups.php?id=' . $user_group . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt ="" style="vertical-align:middle;" /></a>' : '',
		'GROUP_NAME' => $group['name']
	));
		
	//Liste des groupes.
	$result = $Sql->Query_while("SELECT id, name FROM ".PREFIX."group", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('group.select', array(
			'OPTION' => '<option value="' . $row['id'] .'">' . $row['name'] . '</option>'
		));
	}
		
	//Liste des membres appartenant au groupe.
	//Liste des membres du groupe.
	$members = $Sql->Query("SELECT members FROM ".PREFIX."group WHERE id = '" . numeric($user_group) . "'", __LINE__, __FILE__);
	$members = explode('|', $members);
	foreach($members as $key => $user_id)
	{
		$row = $Sql->Query_array('member', 'user_id', 'login', 'level', 'user_avatar', 'user_warning', 'user_ban', "WHERE user_id = '" . numeric($user_id) . "'", __LINE__, __FILE__);
		if( !empty($row['user_id']) )
		{
			//Gestion des rangs
			switch($row['level'])
			{		
				case 0:
				$user_rank = $LANG['member'];
				break;
				case 1:
				$user_rank = $LANG['modo'];
				break;
				case 2:
				$user_rank = $LANG['admin'];
				break;
			}
				
			//Avatar	.
			$user_avatar = !empty($row['user_avatar']) ? '<img class="valign_middle" src="' . $row['user_avatar'] . '" alt=""	/>' : '';
			if( empty($row['user_avatar']) && $CONFIG_MEMBER['activ_avatar'] == '1') 
				$user_avatar = '<img style="vertical-align:middle;" src="../templates/' . $CONFIG['theme'] . '/images/' .  $CONFIG_MEMBER['avatar_url'] . '" alt="" />';
			
			$Template->Assign_block_vars('group.list', array(
				'USER_AVATAR' => $user_avatar,
				'USER_RANK' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
				'U_MEMBER' => '<a href="member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>'
			));	
		}
	}
	
	$Template->Pparse('member');	
}
else //Show all member!
{
  	$Template->Set_filenames(array(
		'member' => '../templates/' . $CONFIG['theme'] . '/member.tpl'
	));
	
	//Recherche d'un member si javascript bloqu�.
	$login = '';
	if( !empty($_POST['search_member']) )
	{
		$login = !empty($_POST['login']) ? securit($_POST['login']) : '';
		$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
		if( !empty($user_id) )
			redirect(HOST . DIR . '/member/member' . transid('.php?id=' . $user_id, '-' . $user_id . '.php', '&'));
		else
			$login = $LANG['no_result'];
	}
	
	$Template->Assign_block_vars('all', array(
		'LOGIN' => $login
	));	

	$Template->Assign_vars(array(
		'SID' => SID,
		'LANG' => $CONFIG['lang'],
		'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
		'L_SELECT_GROUP' => $LANG['select_group'],
		'L_SEARCH_MEMBER' => $LANG['search_member'],
		'L_LIST' => $LANG['liste'],
		'L_SEARCH' => $LANG['search'],
		'L_PROFIL' => $LANG['profil'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_MAIL' => $LANG['mail'],
		'L_REGISTERED' => $LANG['registered_on'],
		'L_MESSAGE' => $LANG['message'],
		'L_LOCALISATION' => $LANG['localisation'],
		'L_LAST_CONNECT' => $LANG['last_connect'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'L_WEB_SITE' => $LANG['web_site'],
		'U_SELECT_SHOW_GROUP' => "'member.php?g=' + this.options[this.selectedIndex].value",
		'U_MEMBER_ALPHA_TOP' => transid('.php?sort=alph&amp;mode=desc', '-0.php?sort=alph&amp;mode=desc'),
		'U_MEMBER_ALPHA_BOTTOM' => transid('.php?sort=alph&amp;mode=asc', '-0.php?sort=alph&amp;mode=asc'),
		'U_MEMBER_TIME_TOP' => transid('.php?sort=time&amp;mode=desc', '-0.php?sort=time&amp;mode=desc'),
		'U_MEMBER_TIME_BOTTOM' => transid('.php?sort=time&amp;mode=asc', '-0.php?sort=time&amp;mode=asc'),
		'U_MEMBER_MSG_TOP' => transid('.php?sort=msg&amp;mode=desc', '-0.php?sort=msg&amp;mode=desc'),
		'U_MEMBER_MSG_BOTTOM' => transid('.php?sort=msg&amp;mode=asc', '-0.php?sort=msg&amp;mode=asc'),
		'U_MEMBER_LAST_TOP' => transid('.php?sort=last&amp;mode=desc', '-0.php?sort=last&amp;mode=desc'),
		'U_MEMBER_LAST_BOTTOM' => transid('.php?sort=last&amp;mode=asc', '-0.php?sort=last&amp;mode=asc')
	));
	
	//Liste des groupes.
	$result = $Sql->Query_while("SELECT id, name 
	FROM ".PREFIX."group", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('all.group', array(
			'OPTION' => '<option value="' . $row['id'] .'">' . $row['name'] . '</option>'
		));
	}
	
	$nbr_member = $Sql->Count_table('member', __LINE__, __FILE__);
	
	$get_sort = !empty($_GET['sort']) ? trim($_GET['sort']) : '';	
	switch($get_sort)
	{
		case 'time' : 
		$sort = 'timestamp';
		break;		
		case 'last' : 
		$sort = 'last_connect';
		break;		
		case 'msg' :
		$sort = 'user_msg';
		break;		
		case 'alph' : 
		$sort = 'login';
		break;		
		default :
		$sort = 'timestamp';
	}
	
	$get_mode = !empty($_GET['mode']) ? trim($_GET['mode']) : '';	
	$mode = ($get_mode == 'asc' || $get_mode == 'desc') ? strtoupper(trim($_GET['mode'])) : '';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On cr�e une pagination si le nombre de membre est trop important.
	include_once('../includes/pagination.class.php'); 
	$Pagination = new Pagination();
		
	$Template->Assign_vars(array(
		'PAGINATION' => '&nbsp;<strong>' . $LANG['page'] . ' :</strong> ' . $Pagination->Display_pagination('member' . transid('.php' . (!empty($unget) ? '&amp;' : '?') . 'p=%d', '-0-%d.php' . $unget), $nbr_member, 'p', 25, 3)
	));

	$result = $Sql->Query_while("SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, user_local, user_web, last_connect 
	FROM ".PREFIX."member
	ORDER BY " . $sort . " " . $mode . 
	$Sql->Sql_limit($Pagination->First_msg(25, 'p'), 25), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '&nbsp;';
		$user_msg = !empty($row['user_msg']) ? $row['user_msg'] : '0';
		
		$user_mail = ( $row['user_show_mail'] == 1 ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
		
		$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
		
		$Template->Assign_block_vars('all.member', array(
			'PSEUDO' => $row['login'],
			'MAIL' => $user_mail,
			'MSG' => $user_msg,
			'LOCAL' => !empty($row['user_local']) ? $row['user_local'] : '&nbsp;',
			'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
			'WEB' => $user_web,
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'U_MEMBER_ID' => transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_MEMBER_PM' => transid('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php')
		));
	}
	$Sql->Close($result);
	
	$Template->Pparse('member');
} 

require_once('../includes/footer.php'); 

?>