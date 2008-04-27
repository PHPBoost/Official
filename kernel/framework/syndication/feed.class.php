<?php
/*##################################################
 *                         feed.class.php
 *                            -------------------
 *   begin                : April 21, 2008
 *   copyright            : (C) 2005 Lo�c Rouchon
 *   email                : horn@phpboost.com
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

define('FEED_PATH', '../cache/syndication');

define('USE_RSS', 0x01);
define('USE_ATOM', 0x02);
define('ALL_FEEDS', USE_RSS|USE_ATOM);

class Feed
{
    ## Public Methods #
    function Feed($feedName, $type = ALL_FEEDS, $feedPath = FEED_PATH)
    /**
     * Constructor
     */
    {
		$this->name = $feedName;
		$this->path = trim($feedPath, '/') . '/';
		
        if ( $type & USE_RSS )
		{
			require_once('../kernel/framework/syndication/rss.work.class.php');
			$this->feeds[USE_RSS] = new RSS($this->name, $this->path);
		}
		if ( $type & USE_ATOM )
		{
			require_once('../kernel/framework/syndication/atom.class.php');
			$this->feeds[USE_ATOM] = new ATOM($this->name, $this->path);
        }
    }

    function Get($nbItems = 5, $tpl = 'syndication/feed.tpl')
    /**
     * Return the results of the feed generated as a string
     */
    {
        if ( ($nbItems == 5) && ($tpl == 'syndication/feed.tpl') )
        {
            if ( ($HTMLfeed = @file_get_contents($this->path . $this->name . '.html')) !== false )
                return $HTMLfeed;
            else
                return '';
        }
        else
            return $this->getHTMLFeed($this->Parse($nbItems), $tpl);
    }
    
    function Parse($nbItem = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array.
     */
    {
        foreach ( $this->feeds as $feed )
        {
            if ( ($parsed = $feed->Parse($nbItem)) !== false )
                return $parsed;
        }
        return array();
    }

    function Generate(&$feedInformations, $tpl = 'syndication/feed.tpl')
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
     * and also the HTML cache for direct includes.
     */
    {
        foreach ( $this->feeds as $feed )
        {
            $feed->Generate($feedInformations);
        }
        $this->generateCache($feedInformations, $tpl);
    }
    
    ## Private Methods ##
    function getHTMLFeed(&$feedInformations, $tpl)
    /**
     * Return a HTML String of a parsed feed.
     */
    {
        require_once('../kernel/framework/template.class.php');
        $Template = new Templates($tpl);
        
        $Template->Assign_vars(array(
            'DATE' => isset($feedInformations['date']) ? $feedInformations['date'] : '',
            'TITLE' => isset($feedInformations['title']) ? $feedInformations['title'] : '',
            'HOST' => HOST,
            'DESC' => isset($feedInformations['desc']) ? $feedInformations['desc'] : '',
            'LANG' => isset($feedInformations['lang']) ? $feedInformations['lang'] : ''
        ));
        
        if ( isset($feedInformations['items']) )
        {
            foreach ( $feedInformations['items'] as $item )
            {
                $Template->Assign_block_vars('item', array(
                    'DATE' => $item['date'],
                    'U_LINK' => $item['link'],
                    'TITLE' => $item['title']
                ));
            }
        }
        return $Template->Tparse(TEMPLATE_STRING_MODE);
    }
    
    function generateCache(&$feedInformations, $tpl)
    /**
     * Generate the HTML cache for direct includes.
     */
    {
        $file = fopen($this->path . $this->name . '.html', 'w+');
        fputs($file, $this->getHTMLFeed($feedInformations, $tpl));
        fclose($file);
    }

    ## Private attributes ##
	var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
    var $feeds = array();
}

?>