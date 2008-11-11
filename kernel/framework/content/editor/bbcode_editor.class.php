<?php
/*##################################################
*                          bbcode_editor.class.php
*                            -------------------
*   begin                : July 5 2008
*   copyright            : (C) 2008 R�gis Viarre
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

import('content/editor/editor');

class BBCodeEditor extends ContentEditor
{
	function BBCodeEditor()
	{
		parent::ContentEditor();
	}
	
	//Affiche le formulaire
	function display()
	{
		global $CONFIG, $Sql, $LANG, $Cache, $User, $CONFIG_UPLOADS, $_array_smiley_code;
		
		$template = $this->get_template();

		//Chargement de la configuration.
		$Cache->load('uploads');
		$Cache->load('smileys');
		
		$template->assign_vars(array(
			'C_BBCODE_TINYMCE_MODE' => false,
			'C_BBCODE_NORMAL_MODE' => true,
			'C_EDITOR_NOT_ALREADY_INCLUDED' => !defined('EDITOR_ALREADY_INCLUDED'),
			'EDITOR_NAME' => 'bbcode',
			'FIELD' => $this->identifier,
			'FORBIDDEN_TAGS' => !empty($this->forbidden_tags) ? implode(',', $this->forbidden_tags) : '',
			'UPLOAD_MANAGEMENT' => $User->check_auth($CONFIG_UPLOADS['auth_files'], AUTH_FILES) ? '<a style="font-size: 10px;" title="' . $LANG['bb_upload'] . '" href="#" onclick="window.open(\'' . PATH_TO_ROOT . '/member/upload.php?popup=1&amp;fd=' . $this->identifier  . '\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;"><img src="' . PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/images/upload/files_add.png" alt="" /></a>' : '',
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_BB_SMILEYS' => $LANG['bb_smileys'],
			'L_BB_BOLD' => $LANG['bb_bold'],
			'L_BB_ITALIC' => $LANG['bb_italic'],
			'L_BB_UNDERLINE' => $LANG['bb_underline'],
			'L_BB_STRIKE' => $LANG['bb_strike'],
			'L_BB_TITLE' => $LANG['bb_title'],
			'L_BB_CONTAINER' => $LANG['bb_container'],
			'L_BB_STYLE' => $LANG['bb_style'],
			'L_BB_URL' => $LANG['bb_link'],
			'L_BB_IMG' => $LANG['bb_picture'],		
			'L_BB_QUOTE' => $LANG['bb_quote'],		
			'L_BB_HIDE' => $LANG['bb_hide'],
			'L_BB_COLOR' => $LANG['bb_color'],
			'L_BB_SIZE' => $LANG['bb_size'],
			'L_BB_SMALL' => $LANG['bb_small'],
			'L_BB_LARGE' => $LANG['bb_large'],
			'L_BB_LEFT' => $LANG['bb_left'],
			'L_BB_CENTER' => $LANG['bb_center'],
			'L_BB_RIGHT' => $LANG['bb_right'],
			'L_BB_FLOAT_LEFT' => $LANG['bb_float_left'],
			'L_BB_FLOAT_RIGHT' => $LANG['bb_float_right'],	
			'L_BB_SUP' => $LANG['bb_sup'],
			'L_BB_SUB' => $LANG['bb_sub'],
			'L_BB_INDENT' => $LANG['bb_indent'],
			'L_BB_LIST' => $LANG['bb_list'],
			'L_BB_TABLE' => $LANG['bb_table'],
			'L_BB_SWF' => $LANG['bb_swf'],
			'L_BB_MOVIE' => $LANG['bb_movie'],
			'L_BB_SOUND' => $LANG['bb_sound'],
			'L_BB_CODE' => $LANG['bb_code'],		
			'L_BB_MATH' => $LANG['bb_math'],
			'L_BB_ANCHOR' => $LANG['bb_anchor'],
			'L_BB_HELP' => $LANG['bb_help'],
			'L_URL_PROMPT' => $LANG['bb_url_prompt'],
			'L_TITLE' => $LANG['title'],
			'L_CONTAINER' => $LANG['bb_container'],
			'L_BLOCK' => $LANG['bb_block'],
			'L_FIELDSET' => $LANG['bb_fieldset'],
			'L_STYLE' => $LANG['style'],
			'L_QUESTION' => $LANG['question'],
			'L_NOTICE' => $LANG['notice'],
			'L_WARNING' => $LANG['warning'],
			'L_ERROR' => $LANG['error'],
			'L_SUCCESS' => $LANG['success'],
			'L_SIZE' => $LANG['size'],
			'L_CODE' => $LANG['code'],
			'L_TEXT' => $LANG['bb_text'],
			'L_SCRIPT' => $LANG['bb_script'],
			'L_WEB' => $LANG['bb_web'],
			'L_PROG' => $LANG['bb_prog'],
			'L_TABLE_HEAD' => $LANG['head_table'],
			'L_ADD_HEAD' => $LANG['head_add'],
			'L_LINES' => $LANG['lines'],
			'L_COLS' => $LANG['cols'],
			'L_ORDERED_LIST' => $LANG['ordered_list'],
			'L_INSERT_LIST' => $LANG['insert_list'],
			'L_INSERT_TABLE' => $LANG['insert_table'],
			'L_PHPBOOST_LANGUAGES' => $LANG['phpboost_languages']
		));
		
		foreach($this->forbidden_tags as $forbidden_tag) //Balises interdite.
		{		
			$template->assign_vars(array( 
				'AUTH_' . strtoupper($forbidden_tag) => 'style="opacity:0.3;filter:alpha(opacity=30);cursor:default;"',
				'DISABLED_' . strtoupper($forbidden_tag) => 'if( false ) '
			));
		}
		
		//Inclusion du cache des smileys pour �viter une requ�te inutile.
		$Cache->load('smileys');
		
		$smile_max = 28; //Nombre de smiley maximim avant affichage d'un lien vers popup.
		$smile_by_line = 5; //Smiley par ligne.
		
		$height_max = 50;
		$width_max = 50;
		$nbr_smile = count($_array_smiley_code);
		$i = 1;	
		$z = 0;
		foreach($_array_smiley_code as $code_smile => $url_smile)
		{
			if( $z == $smile_max )
			{ 
				$z++;
				break;		
			}
			
			$width_source = 18; //Valeur par d�faut.
			$height_source = 18;		
			
			// On recup�re la hauteur et la largeur de l'image.
			list($width_source, $height_source) = @getimagesize(PATH_TO_ROOT . '/images/smileys/' . $url_smile);
			if( $width_source > $width_max || $height_source > $height_max )
			{
				if( $width_source > $height_source )
				{
					$ratio = $width_source / $height_source;
					$width = $width_max;
					$height = $width / $ratio;
				}
				else
				{
					$ratio = $height_source / $width_source;
					$height = $height_max;
					$width = $height / $ratio;
				}
			}
			else
			{
				$width = $width_source;
				$height = $height_source;
			}
			
			$img = '<img src="' . PATH_TO_ROOT . '/images/smileys/' . $url_smile . '" height="' . $height . '" width="' . $width . '" alt="' . $code_smile . '" title="' . $code_smile . '" />'; 
						
			$template->assign_block_vars('smiley', array(
				'IMG' => $img,
				'CODE' => addslashes($code_smile),
				'END_LINE' => $i % $smile_by_line == 0 ? '<br />' : ''
			));	
			
			$i++;	
			$z++;
		}	

		if( $z > $smile_max ) //Lien vers tous les smiley!
		{		
			$template->assign_vars(array(
				'C_BBCODE_SMILEY_MORE' => true,
				'L_ALL_SMILEY' => $LANG['all_smiley'],
				'L_SMILEY' => $LANG['smiley']
			));
		}
		
		if( !defined('EDITOR_ALREADY_INCLUDED') ) //Editeur d�j� includ�.
			define('EDITOR_ALREADY_INCLUDED', true);
		
		return $template->parse(TEMPLATE_STRING_MODE);
	}
}

?>