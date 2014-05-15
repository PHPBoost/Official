<?php
/*##################################################
 *                     GalleryHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 10, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class GalleryHomePageExtensionPoint implements HomePageExtensionPoint
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
		
		load_module_lang('gallery');
		
		return $LANG['gallery'];
	}
	
	private function get_view()
	{
		$this->check_authorizations();
		
		global $Cache, $CAT_GALLERY, $Bread_crumb, $LANG, $User, $Session;
		
		require_once(PATH_TO_ROOT . '/gallery/gallery_begin.php');
		
		$g_idcat = retrieve(GET, 'cat', 0);
		$g_idpics = retrieve(GET, 'id', 0);
		$g_views = retrieve(GET, 'views', false);
		$g_notes = retrieve(GET, 'notes', false);
		$g_sort = retrieve(GET, 'sort', '');
		$g_sort = !empty($g_sort) ? 'sort=' . $g_sort : '';
		
		//R�cup�ration du mode d'ordonnement.
		if (preg_match('`([a-z]+)_([a-z]+)`', $g_sort, $array_match))
		{
			$g_type = $array_match[1];
			$g_mode = $array_match[2];
		}
		else
			list($g_type, $g_mode) = array('date', 'desc');
			
		$Template = new FileTemplate('gallery/gallery.tpl');

		$comments_topic = new GalleryCommentsTopic();
		$config = GalleryConfig::load();
		
		$Gallery = new Gallery();
		
		if (!empty($g_idcat))
		{
			if (!isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0)
				AppContext::get_response()->redirect(PATH_TO_ROOT .'/gallery/gallery' . url('.php?error=unexist_cat', '', '&'));
	
			$cat_links = '';
			foreach ($CAT_GALLERY as $id => $array_info_cat)
			{
				if ($id > 0)
				{
					if ($CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'])
						$cat_links .= '&raquo; <a href="' . GalleryUrlBuilder::get_link_cat($id) . '">' . $array_info_cat['name'] . '</a>';
				}
			}
			$clause_cat = " WHERE gc.id_left > '" . $CAT_GALLERY[$g_idcat]['id_left'] . "' AND gc.id_right < '" . $CAT_GALLERY[$g_idcat]['id_right'] . "' AND gc.level = '" . ($CAT_GALLERY[$g_idcat]['level'] + 1) . "' AND gc.aprob = 1";
		}
		else //Racine.
		{
			$cat_links = '';
			$clause_cat = " WHERE gc.level = '0' AND gc.aprob = 1";
			$CAT_GALLERY[0]['auth'] = $config->get_authorizations();
			$CAT_GALLERY[0]['aprob'] = 1;
			$CAT_GALLERY[0]['name'] = $LANG['root'];
			$CAT_GALLERY[0]['level'] = -1;
		}
	
		//Niveau d'autorisation de la cat�gorie
		if (!$User->check_auth($CAT_GALLERY[$g_idcat]['auth'], GalleryAuthorizationsService::READ_AUTHORIZATIONS))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	
		$nbr_pics = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "gallery WHERE idcat = '" . $g_idcat . "' AND aprob = 1", __LINE__, __FILE__);
		$total_cat = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "gallery_cats gc " . $clause_cat, __LINE__, __FILE__);
	
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'unexist_cat')
			$Template->put('message_helper', MessageHelper::display(LangLoader::get_message('e_unexist_cat', 'errors'), E_USER_NOTICE));
	
		//On cr�e une pagination si le nombre de cat�gories est trop important.
		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $total_cat, $config->get_pics_number_per_page());
		$pagination->set_url(new Url('/gallery/gallery.php?p=%d&amp;cat=' . $g_idcat . '&amp;id=' . $g_idpics . '&amp;' . $g_sort));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		//Colonnes des cat�gories.
		$nbr_column_cats = ($total_cat > $config->get_columns_number()) ? $config->get_columns_number() : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);
	
		//Colonnes des images.
		$nbr_column_pics = ($nbr_pics > $config->get_columns_number()) ? $config->get_columns_number() : $nbr_pics;
		$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
		$column_width_pics = floor(100/$nbr_column_pics);
	
		$is_admin = $User->check_level(User::ADMIN_LEVEL) ? true : false;
		$is_modo = ($User->check_auth($CAT_GALLERY[$g_idcat]['auth'], GalleryAuthorizationsService::MODERATION_AUTHORIZATIONS)) ? true : false;
	
		$module_data_path = $Template->get_pictures_data_path();
		$rewrite_title = Url::encode_rewrite($CAT_GALLERY[$g_idcat]['name']);
	
		$Template->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'ARRAY_JS' => '',
			'NBR_PICS' => 0,
			'MAX_START' => 0,
			'START_THUMB' => 0,
			'END_THUMB' => 0,
			'PAGINATION' => $pagination->display(),
			'COLUMNS_NUMBER' => $nbr_column_pics,
			'COLUMN_WIDTH_CATS' => $column_width_cats,
			'COLUMN_WIDTH_PICS' => $column_width_pics,
			'CAT_ID' => $g_idcat,
			'DISPLAY_MODE' => $config->get_pics_enlargement_mode(),
			'GALLERY' => !empty($g_idcat) ? $CAT_GALLERY[$g_idcat]['name'] : $LANG['gallery'],
			'HEIGHT_MAX' => $config->get_mini_max_height(),
			'WIDTH_MAX' => $column_width_pics,
			'MODULE_DATA_PATH' => $module_data_path,
			'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
			'L_APROB' => $LANG['aprob'],
			'L_UNAPROB' => $LANG['unaprob'],
			'L_FILE_FORBIDDEN_CHARS' => $LANG['file_forbidden_chars'],
			'L_TOTAL_IMG' => $g_idcat > 0 ? sprintf($LANG['total_img_cat'], $nbr_pics) : '',
			'L_ADD_IMG' => $LANG['add_pic'],
			'L_GALLERY' => $LANG['gallery'],
			'L_CATEGORIES' => ($CAT_GALLERY[$g_idcat]['level'] >= 0) ? $LANG['sub_album'] : $LANG['album'],
			'L_NAME' => $LANG['name'],
			'L_EDIT' => $LANG['edit'],
			'L_MOVETO' => $LANG['moveto'],
			'L_DELETE' => $LANG['delete'],
			'L_SUBMIT' => $LANG['submit'],
			'L_ALREADY_VOTED' => $LANG['already_vote'],
			'L_ORDER_BY' => $LANG['orderby'] . (isset($LANG[$g_type]) ? ' ' . strtolower($LANG[$g_type]) : ''),
			'L_DIRECTION' => $LANG['direction'],
			'L_DISPLAY' => $LANG['display'],
			'U_INDEX' => url('.php'),
			'U_GALLERY_CAT_LINKS' => $cat_links,
			'U_BEST_VIEWS' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?views=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?views=1'),
			'U_BEST_NOTES' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?notes=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?notes=1'),
			'U_ASC' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'asc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'asc'),
			'U_DESC' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'desc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'desc'),
			'U_ORDER_BY_NAME' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=name_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=name_desc'),
			'U_ORDER_BY_DATE' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=date_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=date_desc'),
			'U_ORDER_BY_VIEWS' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=views_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=views_desc'),
			'U_ORDER_BY_NOTES' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=notes_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=notes_desc'),
			'U_ORDER_BY_COM' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=com_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=com_desc'),
			'L_BEST_VIEWS' => $LANG['best_views'],
			'L_BEST_NOTES' => $LANG['best_notes'],
			'L_ASC' => $LANG['asc'],
			'L_DESC' => $LANG['desc'],
			'L_DATE' => LangLoader::get_message('date', 'date-common'),
			'L_VIEWS' => $LANG['views'],
			'L_NOTES' => $LANG['notes'],
			'L_COM' => $LANG['com_s']
		));
	
		//Cat�gories non autoris�es.
		$unauth_cats_sql = array();
		$unauth_cats = array();
		foreach ($CAT_GALLERY as $idcat => $key)
		{
			if ($idcat > 0 && $CAT_GALLERY[$idcat]['aprob'] == 1)
			{
				if (!$User->check_auth($CAT_GALLERY[$idcat]['auth'], GalleryAuthorizationsService::READ_AUTHORIZATIONS))
				{
					$clause_level = !empty($g_idcat) ? ($CAT_GALLERY[$idcat]['level'] == ($CAT_GALLERY[$g_idcat]['level'] + 1)) : ($CAT_GALLERY[$idcat]['level'] == 0);
					if ($clause_level)
						$unauth_cats_sql[] = $idcat;
					$unauth_cats[] = $idcat;
				}
			}
		}
		$nbr_unauth_cats = count($unauth_cats_sql);
		$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';
	
		##### Cat�gorie disponibles #####
		if ($total_cat > 0 && $nbr_unauth_cats < $total_cat && empty($g_idpics))
		{
			$Template->put('C_GALLERY_CATS', true);
			
			$j = 0;
			$result = $this->sql_querier->query_while ("SELECT gc.id, gc.name, gc.contents, gc.status, (gc.nbr_pics_aprob + gc.nbr_pics_unaprob) AS nbr_pics, gc.nbr_pics_unaprob, g.path
			FROM " . PREFIX . "gallery_cats gc
			LEFT JOIN " . PREFIX . "gallery g ON g.idcat = gc.id AND g.aprob = 1
			" . $clause_cat . $clause_unauth_cats . "
			GROUP BY gc.id
			ORDER BY gc.id_left
			" . $this->sql_querier->limit($pagination->get_display_from(), $config->get_pics_number_per_page()), __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				//Si la miniature n'existe pas (cache vid�) on reg�n�re la miniature � partir de l'image en taille r�elle.
				if (!file_exists('pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + cr�ation miniature
	
				$Template->assign_block_vars('cat_list', array(
					'IDCAT' => $row['id'],
					'CAT' => $row['name'],
					'DESC' => $row['contents'],
					'IMG' => !empty($row['path']) ? '<img src="'. PATH_TO_ROOT.'/gallery/pics/thumbnails/' . $row['path'] . '" alt="" />' : '',
					'EDIT' => $is_admin ? '<a href="'. PATH_TO_ROOT.'/gallery/admin_gallery_cat.php?id=' . $row['id'] . '" title="' . $LANG['cat_edit'] . '" class="fa fa-edit"></a>' : '',
					'LOCK' => ($row['status'] == 0) ? '<i class="fa fa-lock"></>' : '',
					'OPEN_TR' => is_int($j++/$nbr_column_cats) ? '<tr>' : '',
					'CLOSE_TR' => is_int($j/$nbr_column_cats) ? '</tr>' : '',
					'L_NBR_PICS' => sprintf($LANG['nbr_pics_info'], $row['nbr_pics']),
					'U_CAT' => GalleryUrlBuilder::get_link_cat($row['id'],$row['name'])
				));
			}
			$this->sql_querier->query_close($result);
	
			//Cr�ation des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_cats))
			{
				$Template->assign_block_vars('end_table_cats', array(
					'TD_END' => '<td style="margin:15px 0px;width:' . $nbr_column_cats . '%">&nbsp;</td>',
					'TR_END' => (is_int(++$j/$nbr_column_cats)) ? '</tr>' : ''
				));
			}
		}
	
		##### Affichage des photos #####
		if ($nbr_pics > 0)
		{
			switch ($g_type)
			{
				case 'name' :
				$sort_type = 'g.name';
				break;
				case 'date' :
				$sort_type = 'g.timestamp';
				break;
				case 'views' :
				$sort_type = 'g.views';
				break;
				case 'notes' :
				$sort_type = 'notes.average_notes';
				break;
				case 'com' :
				$sort_type = 'com.number_comments';
				break;
				default :
				$sort_type = 'g.timestamp';
			}
			switch ($g_mode)
			{
				case 'desc' :
				$sort_mode = 'DESC';
				break;
				case 'asc' :
				$sort_mode = 'ASC';
				break;
				default:
				$sort_mode = 'DESC';
			}
			$g_sql_sort = ' ORDER BY ' . $sort_type . ' ' . $sort_mode;
			if ($g_views)
				$g_sql_sort = ' ORDER BY g.views DESC';
			elseif ($g_notes)
				$g_sql_sort = ' ORDER BY notes.average_notes DESC';
	
			$Template->put('C_GALLERY_PICS', true);
			
			//Liste des cat�gories.
			$array_cat_list = array(0 => '<option value="-1" %s>' . $LANG['root'] . '</option>');
			$result = $this->sql_querier->query_while("SELECT id, level, name
			FROM " . PREFIX . "gallery_cats
			WHERE aprob = 1
			ORDER BY id_left", __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				if (!in_array($row['id'], $unauth_cats))
				{
					$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
					$array_cat_list[$row['id']] = $User->check_auth($CAT_GALLERY[$row['id']]['auth'], GalleryAuthorizationsService::MODERATION_AUTHORIZATIONS) ? '<option value="' . $row['id'] . '" %s>' . $margin . ' ' . $row['name'] . '</option>' : '';
				}
			}
			$this->sql_querier->query_close($result);
	
	
	
			//Affichage d'une photo demand�e.
			if (!empty($g_idpics))
			{
				$result = $this->sql_querier->query_while("SELECT g.*, m.login, m.user_groups, m.level, notes.average_notes, notes.number_notes, note.note
					FROM " . PREFIX . "gallery g
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
					LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
					LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = " . AppContext::get_current_user()->get_id() . "
					WHERE g.idcat = '" . $g_idcat . "' AND g.id = '" . $g_idpics . "' AND g.aprob = 1
					" . $g_sql_sort . "
					" . $this->sql_querier->limit(0, 1), __LINE__, __FILE__);
				$info_pics = $this->sql_querier->fetch_assoc($result);
				if (!empty($info_pics['id']))
				{
					//Affichage miniatures.
					$id_previous = 0;
					$id_next = 0;
					$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque c�t� de la miniature de la photo affich�e.
					$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);
					list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
					$array_pics = array();
					$array_js = 'var array_pics = new Array();';
					$result = $this->sql_querier->query_while("SELECT g.id, g.idcat, g.path
					FROM " . PREFIX . "gallery g
					WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
					" . $g_sql_sort, __LINE__, __FILE__);
					while ($row = $this->sql_querier->fetch_assoc($result))
					{
						//Si la miniature n'existe pas (cache vid�) on reg�n�re la miniature � partir de l'image en taille r�elle.
						if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
							$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + cr�ation miniature
	
						//Affichage de la liste des miniatures sous l'image.
						$array_pics[] = '<td style="text-align:center;height:' . ($config->get_mini_max_height() + 16) . 'px"><span id="thumb' . $i . '"><a href="gallery' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;sort=' . $g_sort, '-' . $row['idcat'] . '-' . $row['id'] . '.php?sort=' . $g_sort) . '#pics_max' . '"><img src="pics/thumbnails/' . $row['path'] . '" alt="" / ></a></span></td>';
	
						if ($row['id'] == $g_idpics)
						{
							$reach_pics_pos = true;
							$pos_pics = $i;
						}
						else
						{
							if (!$reach_pics_pos)
							{
								$thumbnails_before++;
								$id_previous = $row['id'];
							}
							else
							{
								$thumbnails_after++;
								if (empty($id_next))
									$id_next = $row['id'];
							}
						}
						$array_js .= 'array_pics[' . $i . '] = new Array();' . "\n";
						$array_js .= 'array_pics[' . $i . '][\'link\'] = \'' . GalleryUrlBuilder::get_link_item($row['idcat'],$row['id']) . '#pics_max' . "';\n";
						$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
						$i++;
					}
					$this->sql_querier->query_close($result);
	
					//Liste des cat�gories.
					$cat_list = '';
					foreach ($array_cat_list as $key_cat => $option_value)
						$cat_list .= ($key_cat == $info_pics['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
	
					$activ_note = ($config->is_notation_enabled() && $User->check_level(User::MEMBER_LEVEL) );
					if ($activ_note)
					{
						//Affichage notation.
						$notation = new Notation();
						$notation->set_module_name('gallery');
						$notation->set_id_in_module($info_pics['id']);
						$notation->set_notation_scale($config->get_notation_scale());
						$notation->set_number_notes($info_pics['number_notes']);
						$notation->set_average_notes($info_pics['average_notes']);
						$notation->set_user_already_noted(!empty($info_pics['note']));
					}

					if ($thumbnails_before < $nbr_pics_display_before)
						$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
					if ($thumbnails_after < $nbr_pics_display_after)
						$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;
	
					$html_protected_name = $info_pics['name'];
	
					$comments_topic->set_id_in_module($info_pics['id']);
					$comments_topic->set_url(new Url('/gallery/gallery.php?cat='. $g_idcat .'&id=' . $g_idpics . '&com=0'));
					
					$group_color = User::get_group_color($info_pics['user_groups'], $info_pics['level']);
					
					//Affichage de l'image et de ses informations.
					$Template->put_all(array(
						'C_GALLERY_PICS_MAX' => true,
						'C_GALLERY_PICS_MODO' => $is_modo ? true : false,
						'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
						'C_VIEWS_COUNTER_ENABLED' => $config->is_views_counter_enabled(),
						'C_TITLE_ENABLED' => $config->is_title_enabled(),
						'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
						'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
						'ID' => $info_pics['id'],
						'IMG_MAX' => '<img src="' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $g_idpics . '&amp;cat=' . $g_idcat) . '" alt="" />',
						'NAME' => '<span id="fi_' . $info_pics['id'] . '">' . stripslashes($info_pics['name']) . '</span> <span id="fi' . $info_pics['id'] . '"></span>',
						'POSTOR' => '<a class="small ' . UserService::get_level_class($info_pics['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($info_pics['user_id'])->rel() .'">' . $info_pics['login'] . '</a>',
						'DATE' => gmdate_format('date_format_short', $info_pics['timestamp']),
						'VIEWS' => ($info_pics['views'] + 1),
						'DIMENSION' => $info_pics['width'] . ' x ' . $info_pics['height'],
						'SIZE' => NumberHelper::round($info_pics['weight']/1024, 1),
						'COM' => '<a href="'. GalleryUrlBuilder::get_link_item($info_pics['idcat'],$info_pics['id'],0,$g_sort) .'#comments_list">'. CommentsService::get_number_and_lang_comments('gallery', $info_pics['id']) . '</a>',
						'KERNEL_NOTATION' => $activ_note ? NotationService::display_active_image($notation) : '',
						'COLSPAN' => ($config->get_columns_number() + 2),
						'CAT' => $cat_list,
						'RENAME' => $html_protected_name,
						'RENAME_CUT' => $html_protected_name,
						'IMG_APROB' => ($info_pics['aprob'] == 1) ? 'fa fa-eye-slash' : 'fa fa-eye',
						'ARRAY_JS' => $array_js,
						'NBR_PICS' => ($i - 1),
						'MAX_START' => ($i - 1) - $nbr_column_pics,
						'START_THUMB' => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
						'END_THUMB' => ($pos_pics + $end_thumbnails),
						'L_KB' => $LANG['unit_kilobytes'],
						'L_INFORMATIONS' => $LANG['informations'],
						'L_NAME' => $LANG['name'],
						'L_POSTOR' => $LANG['postor'],
						'L_VIEWS' => $LANG['views'],
						'L_ADD_ON' => $LANG['add_on'],
						'L_DIMENSION' => $LANG['dimension'],
						'L_SIZE' => $LANG['size'],
						'L_NOTE' => $LANG['note'],
						'L_COM' => $LANG['com'],
						'L_EDIT' => $LANG['edit'],
						'L_APROB_IMG' => ($info_pics['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
						'L_THUMBNAILS' => $LANG['thumbnails'],
						'U_DEL' => url('gallery.php?del=' . $info_pics['id'] . '&amp;token=' . $Session->get_token() . '&amp;cat=' . $g_idcat),
						'U_MOVE' => url('gallery.php?id=' . $info_pics['id'] . '&amp;token=' . $Session->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
						'U_PREVIOUS' => ($pos_pics > 0) ? '<a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_previous) . '#pics_max"><i class="fa fa-arrow-left fa-2x"></i></a> <a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_previous) . '#pics_max">' . $LANG['previous'] . '</a>' : '',
						'U_NEXT' => ($pos_pics < ($i - 1)) ? '<a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_next) . '#pics_max">' . $LANG['next'] . '</a> <a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_next) . '#pics_max"><i class="fa fa-arrow-right fa-2x"></i></a>' : '',
						'U_LEFT_THUMBNAILS' => (($pos_pics - $start_thumbnails) > 0) ? '<span id="display_left"><a href="javascript:display_thumbnails(\'left\')"><i class="fa fa-arrow-left fa-2x"></i></a></span>' : '<span id="display_left"></span>',
						'U_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics) ? '<span id="display_right"><a href="javascript:display_thumbnails(\'right\')"><i class="fa fa-arrow-right fa-2x"></i></a></span>' : '<span id="display_right"></span>'
					));
	
					//Affichage de la liste des miniatures sous l'image.
					$i = 0;
					foreach ($array_pics as $pics)
					{
						if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
						{
							$Template->assign_block_vars('list_preview_pics', array(
								'PICS' => $pics
							));
						}
						$i++;
					}
	
					//Commentaires
					if (isset($_GET['com']))
					{
						if ($config->are_comments_enabled())
						{
							$Template->put_all(array(
								'COMMENTS' => CommentsService::display($comments_topic)->render()
							));
						}
						else
						{
							$error_controller = PHPBoostErrors::user_not_authorized();
							DispatchManager::redirect($error_controller);
						}
					}
				}
			}
			else
			{
				$sort = retrieve(GET, 'sort', '');
				
				//On cr�e une pagination si le nombre de photos est trop important.
				$page = AppContext::get_request()->get_getint('pp', 1);
				$pagination = new ModulePagination($page, $nbr_pics, $config->get_pics_number_per_page());
				$pagination->set_url(new Url('/gallery/gallery.php?pp=%d' . (!empty($sort) ? '&amp;sort=' . $sort : '') . '&amp;cat=' . $g_idcat));
				
				if ($pagination->current_page_is_empty() && $page > 1)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
				
				$Template->put_all(array(
					'C_GALLERY_MODO' => $is_modo,
					'C_PAGINATION' => $pagination->has_several_pages(),
					'PAGINATION' => $pagination->display(),
					'L_EDIT' => $LANG['edit'],
					'L_VIEW' => $LANG['view'],
					'L_VIEWS' => $LANG['views']
				));
				
				$is_connected = $User->check_level(User::MEMBER_LEVEL);
				$j = 0;
				$result = $this->sql_querier->query_while("SELECT g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob, m.login, m.user_groups, m.level, notes.average_notes, notes.number_notes, note.note
				FROM " . PREFIX . "gallery g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
				LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
				LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = " . AppContext::get_current_user()->get_id() . "
				WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
				" . $g_sql_sort . "
				" . $this->sql_querier->limit($pagination->get_display_from(), $config->get_pics_number_per_page()), __LINE__, __FILE__);
				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					//Si la miniature n'existe pas (cache vid�) on reg�n�re la miniature � partir de l'image en taille r�elle.
					if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
						$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + cr�ation miniature
					
					//Affichage de l'image en grand.
					if ($config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN) //Ouverture en popup plein �cran.
					{
						$display_link = PATH_TO_ROOT.'/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '" rel="lightbox[1]" onmousedown="increment_view(' . $row['id'] . ');" title="' . str_replace('"', '', stripslashes($row['name']));
						$display_name = PATH_TO_ROOT.'/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '" rel="lightbox[2]" onmousedown="increment_view(' . $row['id'] . ');" title="' . str_replace('"', '', stripslashes($row['name']));
					}
					elseif ($config->get_pics_enlargement_mode() == GalleryConfig::POPUP) //Ouverture en popup simple.
						$display_name = $display_link = 'javascript:increment_view(' . $row['id'] . ');display_pics_popup(\'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')';
					elseif ($config->get_pics_enlargement_mode() == GalleryConfig::RESIZE) //Ouverture en agrandissement simple.
						$display_name = $display_link = 'javascript:increment_view(' . $row['id'] . ');display_pics(' . $row['id'] . ', \'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\')';
					else //Ouverture nouvelle page.
						$display_name = $display_link = url('gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'], 'gallery-' . $row['idcat'] . '-' . $row['id'] . '.php') . '#pics_max';
					
					//Liste des cat�gories.
					$cat_list = '';
					foreach ($array_cat_list as $key_cat => $option_value)
						$cat_list .= ($key_cat == $row['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
					
					$notation = new Notation();
					$notation->set_module_name('gallery');
					$notation->set_notation_scale($config->get_notation_scale());
					$notation->set_id_in_module($row['id']);
					$notation->set_number_notes( $row['number_notes']);
					$notation->set_average_notes($row['average_notes']);
					$notation->set_user_already_noted(!empty($row['note']));
					
					$group_color = User::get_group_color($row['user_groups'], $row['level']);
					
					$comments_topic->set_id_in_module($row['id']);
					
					$html_protected_name = $row['name'];
					$Template->assign_block_vars('pics_list', array(
						'ID' => $row['id'],
						'APROB' => $row['aprob'],
						'IMG' => '<img src="'. PATH_TO_ROOT.'/gallery/pics/thumbnails/' . $row['path'] . '" alt="' . str_replace('"', '', stripslashes($row['name'])) . '" class="gallery_image" />',
						'PATH' => $row['path'],
						'NAME' => $config->is_title_enabled() ? '<a class="small" href="' . $display_name . '"><span id="fi_' . $row['id'] . '">' . TextHelper::wordwrap_html(stripslashes($row['name']), 22, ' ') . '</span></a> <span id="fi' . $row['id'] . '"></span>' : '<span id="fi_' . $row['id'] . '"></span></a> <span id="fi' . $row['id'] . '"></span>',
						'POSTOR' => $config->is_author_displayed() ? '<br />' . $LANG['by'] . (!empty($row['login']) ? ' <a class="small '.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'">' . $row['login'] . '</a>' : ' ' . $LANG['guest']) : '',
						'VIEWS' => $config->is_views_counter_enabled() ? '<br /><span id="gv' . $row['id'] . '">' . $row['views'] . '</span> <span id="gvl' . $row['id'] . '">' . ($row['views'] > 1 ? $LANG['views'] : $LANG['view']) . '</span>' : '',
						'COM' => $config->are_comments_enabled() ? '<br /><a href="'. PATH_TO_ROOT .'/gallery/gallery' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('gallery', $row['id']) . '</a>' : '',
						'KERNEL_NOTATION' => $config->is_notation_enabled() && $is_connected ? NotationService::display_active_image($notation) : NotationService::display_static_image($notation),
						'CAT' => $cat_list,
						'RENAME' => $html_protected_name,
						'RENAME_CUT' => $html_protected_name,
						'IMG_APROB' => ($row['aprob'] == 1) ? 'fa fa-eye-slash' : 'fa fa-eye',
						'OPEN_TR' => is_int($j++/$nbr_column_pics) ? '<tr>' : '',
						'CLOSE_TR' => is_int($j/$nbr_column_pics) ? '</tr>' : '',
						'L_APROB_IMG' => ($row['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
						'U_DEL' => url('gallery.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token() . '&amp;cat=' . $g_idcat),
						'U_MOVE' => url('gallery.php?id=' . $row['id'] . '&amp;token=' . $Session->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
						'U_DISPLAY' => $display_link
					));
				}
				$this->sql_querier->query_close($result);
	
				//Cr�ation des cellules du tableau si besoin est.
				while (!is_int($j/$nbr_column_pics))
				{
					$Template->assign_block_vars('end_table', array(
						'TD_END' => '<td style="margin:15px 0px;width:' . $column_width_pics . '%">&nbsp;</td>',
						'TR_END' => (is_int(++$j/$nbr_column_pics)) ? '</tr>' : ''
					));
				}
			}
		}
	
		return $Template;
	}
	
	private function check_authorizations()
	{
		if (!GalleryAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>