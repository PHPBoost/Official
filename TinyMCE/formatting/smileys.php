<?php
/*##################################################
 *                          smiley_tinymce.class.php
 *                            -------------------
 *   begin                : March 23 2009
 *   copyright            : (C) 2009 R�gis Viarre
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

/**
* @package content
*/

/**
* Constant definition
*/
define('PATH_TO_ROOT', '../..');
require_once(PATH_TO_ROOT . '/kernel/begin.php');
define('TITLE', $LANG['all_smiley']);

$tpl_smileys = new FileTemplate('TinyMCE/smileys.tpl');

//Chargement de la configuration.
$smileys_cache = SmileysCache::load();
$smile_by_line = 9;

$field = retrieve(GET, 'field', 'contents');
$tpl_smileys->put_all(array(
	'FIELD' => $field ,
	'COLSPAN' => $smile_by_line,
	'L_SMILEY' => $LANG['smiley'],
	'L_CLOSE' => $LANG['close'],
	'L_REQUIRE_TEXT' => $LANG['require_text']
));

$nbr_smile = count($smileys_cache->get_smileys());
$j = 0;
foreach($smileys_cache->get_smileys() as $code_smile => $infos)
{
    //On gen�re le tableau pour $smile_by_line colonnes
    $multiple_x = $j / $smile_by_line ;
    $tr_start = (is_int($multiple_x)) ? '<tr>' : '';
    $j++;
    $multiple_x = $j / $smile_by_line ;
    $tr_end = (is_int($multiple_x)) ? '</tr>' : '';

    //Si la ligne n'est pas compl�te on termine par </tr>.
    if ( $nbr_smile == $j )
    {
        $tr_end = '</tr>';
    }

    $tpl_smileys->assign_block_vars('smiley', array(
		'URL' => Url::to_rel('/images/smileys/' . $infos['url_smiley']),
		'IMG' => '<img src="' . Url::to_absolute('/images/smileys/' . $infos['url_smiley']) . '" alt="' . $code_smile . '" title="' . $code_smile . '" />',
		'CODE' => addslashes($code_smile),
		'TR_START' => $tr_start,
		'TR_END' => $tr_end,
    ));

    //Cr�ation des cellules du tableau si besoin est.
    if ( $nbr_smile == $j && $nbr_smile > $smile_by_line )
    {
        while( !is_int($j / $smile_by_line) )
        {
            $tpl_smileys->assign_block_vars('smiley.td', array(
				'TD' => '<td>&nbsp;</td>'
				));
				$j++;
        }
    }
}

$tpl_smileys->display();
?>