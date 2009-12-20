<?php
/*##################################################
 *                         AbstractParser.class.php
 *                            -------------------
 *   begin                : November 29, 2007
 *   copyright            : (C) 2007 R�gis Viarre, Benoit Sautel, Lo�c Rouchon
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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
 * @subpackage parser
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class is the basis of all the formatting processings that exist in PHPBoost.
 */
abstract class AbstractParser implements FormattingParser
{
    const PICK_UP = true;
    const REIMPLANT = false;
    /**
     * @var string Content of the parser
     */
    protected $content = '';
    /**
     * @var string[] List of the tags which have been picked up by the parser
     */
    protected $array_tags = array();
    /**
     * @var string Path to root of the page in which has been written the content to parse.
     */
    protected $path_to_root = PATH_TO_ROOT;

    /**
     * @var string Path of the page in which has been written the content to parse.
     */
    protected $page_path = '';

    /**
     * @desc Builds a Parser object.
     */
    public function __construct()
    {
        $this->content = '';
        $this->page_path = $_SERVER['PHP_SELF'];
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#get_content($addslashes)
     */
    public function get_content($addslashes = FormattingParser::ADD_SLASHES)
    {
        if ($addslashes)
        {
            return addslashes(trim($this->content));
        }
        else
        {
            return trim($this->content);
        }
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#set_content($content, $stripslashes)
     */
    public function set_content($content, $stripslashes = FormattingParser::DONT_STRIP_SLASHES)
    {
        if ($stripslashes)
        {
            $this->content = stripslashes($content);
        }
        else
        {
            $this->content = $content;
        }
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#set_path_to_root($path)
     */
    public function set_path_to_root($path)
    {
        $this->path_to_root = $path;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#get_path_to_root()
     */
    public function get_path_to_root()
    {
        return $this->path_to_root;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#set_page_path($page_path)
     */
    public function set_page_path($page_path)
    {
        $this->page_path = $page_path;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/framework/content/formatting/parser/FormattingParser#get_page_path()
     */
    public function get_page_path()
    {
        return $this->page_path;
    }

    /**
     * @desc Parses a nested tag
     * @param string $match The regular expression which matches the tag to replace
     * @param string $regex The regular expression which matches the replacement
     * @param string $replace The replacement syntax.
     */
    protected function _parse_imbricated($match, $regex, $replace)
    {
        $nbr_match = substr_count($this->content, $match);
        for ($i = 0; $i <= $nbr_match; $i++)
        {
            $this->content = preg_replace($regex, $replace, $this->content);
        }
    }
}

?>