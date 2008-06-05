<?php
/*##################################################
 *                                wiki_cache.php
 *                            -------------------
 *   begin                : May 21, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if( defined('PHPBOOST') !== true) exit;


function generate_module_file_wiki()
{
	global $Sql;
	
	//Catégories du wiki
	$config = 'global $_WIKI_CATS;' . "\n";
	$config .= '$_WIKI_CATS = array();' . "\n";
	$result = $Sql->Query_while("SELECT c.id, c.id_parent, c.article_id, a.title
		FROM ".PREFIX."wiki_cats c
		LEFT JOIN ".PREFIX."wiki_articles a ON a.id = c.article_id 
		ORDER BY a.title", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$config .= '$_WIKI_CATS[\'' . $row['id'] . '\'] = array(\'id_parent\' => ' . ( !empty($row['id_parent']) ? $row['id_parent'] : '0') . ', \'name\' => ' . var_export($row['title'], true) . ');' . "\n";
	}

	//Configuration du wiki
	$code = 'global $_WIKI_CONFIG;' . "\n" . '$_WIKI_CONFIG = array();' . "\n";
	$CONFIG_WIKI = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'wiki'", __LINE__, __FILE__));
	$CONFIG_WIKI = is_array($CONFIG_WIKI) ? $CONFIG_WIKI : array();
	$CONFIG_WIKI['auth'] = unserialize($CONFIG_WIKI['auth']);
	
	$code .= '$_WIKI_CONFIG = ' . var_export($CONFIG_WIKI, true) . ';' . "\n";
	
	return $config . "\n\r" . $code;
}
	
?>