<?php
/*##################################################
 *                           bread_crumb.class.php
 *                            -------------------
 *   begin                : February 16, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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

/**
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class is used to represent the bread crumb displayed on each page of the website.
 * It enables the user to locate himself in the whole site.
 * A bread crumb can look like this: Home >> My module >> First level category >> Second level category >>
 * Third level category >> .. >> My page >> Edition
 */
class BreadCrumb
{
    /**
     * @desc Builds a BreadCrumb object.
     */
    function BreadCrumb()
    {
    }

    /**
     * @desc Adds a link in the bread crumb. This link will be put at the end of the list.
     * @param string $text Name of the page
     * @param string $target Link whose target is the page
     */
    function add($text, $target = '')
    {
        if (!empty($text))
        {
            $this->array_links[] = array($text, $target);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Reverses the whole list of the links. It's very useful when it's easier for you to make the list in the reverse way, at the
     * end, you only need to reverse the list and it will be ok.
     */
    function reverse()
    {
        $this->array_links = array_reverse($this->array_links);
    }

    /**
     * @desc Removes the last link of the list
     */
    function remove_last()
    {
        array_pop($this->array_links);
    }

    /**
     * @desc Displays the bread crumb.
     */
    function display()
    {
        global $Template, $CONFIG, $LANG;

        if (empty($this->array_links))
        {
            $this->add(stripslashes(TITLE), HOST . SCRIPT . SID);
        }

        $Template->assign_vars(array(
			'START_PAGE' 	=> get_start_page(),
			'L_INDEX' 		=> $LANG['home']	
        ));

        foreach ($this->array_links as $key => $array)
        {
            $Template->assign_block_vars('link_bread_crumb', array(
				'URL' => $array[1],
				'TITLE' => $array[0]
            ));
        }
    }

    /**
     * @desc Removes all the existing links.
     */
    function clean()
    {
        $this->array_links = array();
    }

    ## Attributs prot�g�s #
    /**
     * @var string[][] List of the links
     */
    var $array_links = array();
}

?>