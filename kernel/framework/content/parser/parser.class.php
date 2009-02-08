<?php
/*##################################################
*                             parser.class.php
*                            -------------------
*   begin                : November 29, 2007
*   copyright            : (C) 2007 R�gis Viarre, Benoit Sautel, Lo�c Rouchon
*   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, horn@phpboost.com
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

//Constantes utilis�es
define('DO_NOT_ADD_SLASHES', false);
define('ADD_SLASHES', true);
define('PARSER_DO_NOT_STRIP_SLASHES', false);
define('PARSER_STRIP_SLASHES', true);
define('PICK_UP', true);
define('REIMPLANT', false);

/**
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class is the basis of all the formatting processings that exist in PHPBoost.
 */
class Parser
{
	######## Public #######
	/**
	 * @desc Build a Parser object. 
	 */
	function Parser()
	{
		$this->content = '';
	}

	/**
	 * @desc Return the content of the parser. If you called a method which parses the content, this content will be parsed.
	 * @param bool $addslashes ADD_SLASHES if you want to escape the slashes in your string 
	 * (you often save a parsed content into the database when you parse it), otherwise DO_NOT_ADD_SLASHES.
	 * @return string The content of the parser.
	 */
	function get_content($addslashes = ADD_SLASHES)
	{
		if ($addslashes)
			return addslashes(trim($this->content));
		else
			return trim($this->content);
	}
	
	/**
	 * @desc Set the content of the parser. When you will call a parse method, it will deal with this content. 
	 * @param string $content Content
	 * @param bool $stripslashes PARSER_DO_NOT_STRIP_SLASHES if you don't want to strip slashes before adding it to the parser, 
	 * otherwise PARSER_DO_NOT_STRIP_SLASHES.
	 */
	function set_content($content, $stripslashes = PARSER_DO_NOT_STRIP_SLASHES)
	{
		if ($stripslashes)
			$this->content = stripslashes($content);
		else
			$this->content = $content;
	}
		
	####### Protected #######
	/**
	 * @var string Content of the parser
	 */
	var $content = '';
	/**
	 * @static
	 * @var string[] List of the tags which have been picked up by the parser
	 */
	var $array_tags = array();
	
	/**
	 * @desc Parse a nested tag
	 * @param string $match The regular expression which matches the tag to replace
	 * @param string $regex The regular expression which matches the replacement
	 * @param string $replace The replacement syntax.
	 */
	function _parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = substr_count($this->content, $match);
		for ($i = 0; $i <= $nbr_match; $i++)
			$this->content = preg_replace($regex, $replace, $this->content); 
	}
}

?>