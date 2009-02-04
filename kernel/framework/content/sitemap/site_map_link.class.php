<?php
/*##################################################
 *                           sitemaplink.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   SitemapLink
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

import('util/date');
import('content/sitemap/site_map_element');

/**
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a link of a site map.
 */
class SiteMapLink extends SiteMapElement
{
    /**
     * @desc Builds a SiteMapLink object
     * @param $name string Name of the target page
     * @param $link Url link
     * @param $change_freq string Frequency taken into the following enum: SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY,
     * SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY, SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER,
     * SITEMAP_FREQ_DEFAULT
     * @param $priority string Priority taken into the following enum: SITEMAP_PRIORITY_MAX, SITEMAP_PRIORITY_HIGH,
     * SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN
     * @param $last_modification_date Date Last modification date of the target page
     */
    function SitemapLink($name = '', $link = null, $change_freq = SITEMAP_FREQ_MONTHLY, $priority = SITEMAP_PRIORITY_AVERAGE, $last_modification_date = null)
    {
        $this->name = $name;
        $this->set_link($link);
        $this->set_change_freq($change_freq);
        $this->set_priority($priority);
        $this->set_last_modification_date($last_modification_date);
    }
    
    /**
     * @desc Return the name of the target page 
     * @return string name
     */
    function get_name()
    {
        return $this->name;
    }

    /**
     * @desc Return the URL of the link
     * @return Url The URL of the link
     */
    function get_link()
    {
        return $this->link;
    }

    /**
     * @desc Get the change frequency (how often the target page is actualized)
     * @return string Frequency taken into the following enum: SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY,
     * SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY, SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER,
     * SITEMAP_FREQ_DEFAULT
     */
    function get_change_freq()
    {
        return $this->change_freq;
    }

    /**
     * @desc Get the priority of the link
     * @return string Priority taken into the following enum: SITEMAP_PRIORITY_MAX, SITEMAP_PRIORITY_HIGH,
     * SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN
     */
    function get_priority()
    {
        return $this->priority;
    }

    /**
     * @desc Returns the last modification date of the target page
     * @return Date the last modification date
     */
    function get_last_modification_date()
    {
        return $this->last_modification_date;
    }

    /**
     * @desc Return the URL of the link 
     * @return string the URL
     */
    function get_url()
    {
        if (is_object($this->link))
        {
            return $this->link->absolute();
        }
        else
        {
            return '';
        }
    }

    /**
     * @desc Set the name of the element
     * @param $name string name of the element
     */
    function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * @desc Set the URL of the link
     * @param $link Url URL
     */
    function set_link($link)
    {
        if (is_object($link))
        {
            $this->link = $link;
        }
    }

    /**
     * @desc Set the change frequency
     * @param $change_freq string Frequency taken into the following enum: SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY,
     * SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY, SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER,
     * SITEMAP_FREQ_DEFAULT
     */
    function set_change_freq($change_freq)
    {
        //If the given frequency is correct
        if (in_array($change_freq, array(SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY, SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY, SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER, SITEMAP_FREQ_DEFAULT)))
        {
            $this->change_freq = $change_freq;
        }
        else
        {
            $this->change_freq = SITEMAP_FREQ_DEFAULT;
        }
    }

    /**
     * @desc Set the priority of the link
     * @param $priority string Priority taken into the following enum: SITEMAP_PRIORITY_MAX, SITEMAP_PRIORITY_HIGH,
     * SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN
     */
    function set_priority($priority)
    {
        if (in_array($priority, array(SITEMAP_PRIORITY_MAX, SITEMAP_PRIORITY_HIGH, SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN)))
        {
            $this->priority = $priority;
        }
        else
        {
            $this->priority = SITEMAP_PRIORITY_AVERAGE;
        }
    }

    /**
     * @desc Set the last modification date of the target page
     * @param $date Date date
     */
    function set_last_modification_date($last_modification_date)
    {
        if (is_object($last_modification_date))
        {
            $this->last_modification_date = $last_modification_date;
        }
    }

    /**
     * @desc Exports the section according to the given configuration
     * @param $export_config SiteMapExportConfig Export configuration
     * @return string the exported link
     */
    function export(&$export_config)
    {
        $display_date = is_object($this->last_modification_date);

        //We get the stream in which we are going to write
        $template = $export_config->get_link_stream();

        $template->assign_vars(array(
			'LOC' => htmlspecialchars($this->get_url(), ENT_QUOTES),
			'TEXT' => htmlspecialchars($this->name, ENT_QUOTES),
			'C_DISPLAY_DATE' => $display_date,
			'DATE' => $display_date ? $this->last_modification_date->To_date() : '',
			'ACTUALIZATION_FREQUENCY' => $this->change_freq,
			'PRIORITY' => $this->priority,
            'C_LINK' => true
        ));

        return $template->parse(TEMPLATE_STRING_MODE);
    }

    ## Private elements ##
    /**
    * @var string Name of the SiteMapElement
    */
    var $name = '';
    /**
     * @var Url Url of the link
     */
    var $link;
    /**
     * @var string Actualization frequency of the target page, must be a member of the following enum:
     * SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY, SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY,
     * SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER, SITEMAP_FREQ_DEFAULT
     */
    var $change_freq = SITEMAP_FREQ_DEFAULT;
    /**
     *
     * @var Date Last modification date of the target page
     */
    var $last_modification_date;
    /**
     * @var string Priority of the target page, must be a member of the following enum: SITEMAP_PRIORITY_MAX,
     *  SITEMAP_PRIORITY_HIGH, SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN
     */
    var $priority = SITEMAP_PRIORITY_AVERAGE;
}

?>