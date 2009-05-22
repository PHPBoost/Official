<?php
/*##################################################
 *                             url.class.php
 *                            -------------------
 *   begin                : January 14, 2009
 *   copyright            : (C) 2009 Lo�c Rouchon
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

define('URL__CLASS','url');
define('SERVER_URL', $_SERVER['PHP_SELF']);
define('URL_TAGS', 'src|data|value|href|son|flv');

/**
 * @author Lo�c Rouchon <horn@phpboost.com>
 * @desc This class offers a simple way to transform an absolute or relative link
 * to a relative one to the website root.
 * It can also deals with absolute url and will convert only those from this
 * site into relatives ones.
 * Usage :
 * <ul>
 *   <li>In content, get the url with the absolute() method. It will allow content include at multiple level</li>
 *   <li>In forms, get the url with the relative() method. It's a faster way to display url</li>
 * </ul>
 * @package util
 */
class Url
{

    /**
     * @desc Build a Url object
     * @param string $url the url string relative to the current path,
     * to the website root if beginning with a "/" or an absolute url
     * @param string $path_to_root url context. default is PATH_TO_ROOT
     */
    function Url($url = '', $path_to_root = null, $server_url = null)
    {
        if ($path_to_root !== null)
        {
            $this->path_to_root = $path_to_root;
        }
        else
        {
            $this->path_to_root = Url::path_to_root();
        }

        if ($server_url !== null)
        {
            $this->server_url = $server_url;
        }
        else
        {
            $this->server_url = Url::server_url();
        }

        $this->set_url($url);
    }

    /**
     * @desc Set the url
     * @param string $url the url string relative to the current path,
     * to the website root if beginning with a "/" or an absolute url
     */
    function set_url($url)
    {
        if (strpos($url, 'www.') === 0)
        {   // If the url begins with 'www.', it's an absolute one
            $url = 'http://' . $url;
        }
        $url = str_replace(HOST . DIR . '/', '/', Url::compress($url));
        if (!strpos($url, '://'))
        {
            $this->is_relative = true;
            if (substr($url, 0, 1) == '/')
            {   // Relative url from the website root (good form)
                $this->url = $url;
            }
            else
            {   // The url is relative to the current foler
                $this->url = $this->root_to_local() . $url;
            }
        }
        else
        {
            $this->is_relative = false;
            $this->url = $url;
        }
        $this->url = Url::compress($this->url);
    }

    /**
     * @return bool true if the url is a relative one
     */
    function is_relative()
    {
        return $this->is_relative;
    }

    /**
     * @desc Returns the relative url if defined, else the absolute one
     * @return string the relative url if defined, else the absolute one
     */
    function relative()
    {
        if ($this->is_relative())
        {
            return $this->url;
        }
        else
        {
            return $this->absolute();
        }
    }

    /**
     * @desc Returns the absolute url
     * @return string the absolute url
     */
    function absolute()
    {
        if ($this->is_relative())
        {
            return Url::compress($this->get_absolute_root() . $this->url);
        }
        else
        {
            return $this->url;
        }
    }


    /**
     * @static
     * @desc Compress a url by removing all "folder/.." occurrences
     * @param string $url the url to compress
     * @return string the compressed url
     */
    /* static */ function compress($url)
    {
        $url = preg_replace(array('`([^:]|^)/+`', '`(?<!\.)\./`'), array('$1/', ''), $url);

        do
        {
            $url = preg_replace('`/?[^/]+/\.\.`', '', $url);

        }
        while (preg_match('`/?[^/]+/\.\.`', $url) > 0);
        return preg_replace('`^//`', '/', $url);
    }

    /**
     * @desc Returns the relative path from the website root to the current path if working on a relative url
     * @return string the relative path from the website root to the current path if working on a relative url
     */function root_to_local()
    {
        global $CONFIG;

        $local_path = $this->server_url;
        $local_path = substr(trim($local_path, '/'), strlen(trim($CONFIG['server_path'], '/')));
        $file_begun = strrpos($local_path, '/');
        if ($file_begun >= 0)
        {
            $local_path = substr($local_path, 0, $file_begun) . '/';
        }

        return $local_path;
    }

    /**
     * @static
     * @desc Returns the absolute website root Url
     * @return string the absolute website root Url
     */
    /* static */ function get_absolute_root()
    {
        global $CONFIG;
        return trim(trim($CONFIG['server_name'], '/') . '/' . trim($CONFIG['server_path'], '/'), '/');
    }

    /**
     * @static
     * @desc Returns the HTML text with only absolutes urls
     * @param string $html_text The HTML text in which we gonna search for
     * root relatives urls (only those beginning by '/') to convert into absolutes ones.
     * @param string $path_to_root Path to root of the page to which you want to fit the URL.
     * @param string $server_url Path from the site root of the page to which you want to fit the URL.
     * @return string The HTML text with only absolutes urls
     */
    /* static */ function html_convert_root_relative2absolute($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
    {
        Url::path_to_root($path_to_root);
        Url::server_url($server_url);
        return preg_replace_callback(
            '`(<script type="text/javascript">.*insert(?:Sound|Movie|Swf)Player\(")(/[^"]+)(".*</script>)`sU',
        array('Url', '_convert_url_to_absolute'),
        preg_replace_callback(
		        '`((?:<[^>]+) (?:' . URL_TAGS . ')=")(/[^"]+)("(?:[^<]*>))`',
        array('Url', '_convert_url_to_absolute'),
        $html_text
        )
        );
    }

    /**
     * @static
     * @desc Returns the HTML text with only relatives urls
     * @param string $html_text The HTML text in which we gonna search for absolutes urls to convert into relatives ones.
     * @param string $path_to_root Path to root of the page to which you want to fit the URL.
     * @param string $server_url Path from the site root of the page to which you want to fit the URL.
     * @return string The HTML text with only absolutes urls
     */
    /* static */ function html_convert_absolute2relative($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
    {
        Url::path_to_root($path_to_root);
        Url::server_url($server_url);
        return preg_replace_callback(
            '`(<script type="text/javascript">.*insert(?:Sound|Movie|Swf)Player\(")([^"]+)(".*</script>)`sU',
        array('Url', '_convert_url_to_relative'),
        preg_replace_callback(
	            '`((?:<[^>]+) (?:' . URL_TAGS . ')(?:="))([^"]+)("(?:[^<]*>))`',
        array('Url', '_convert_url_to_relative'),
        $html_text
        )
        );
    }

    /**
     * @static
     * @desc Transforms the relative URL whose base is the site root (for instance /images/mypic.png) to the real relative path fited to the current page.
     * @param string $html_text The HTML text in which you want to replace the paths
     * @param string $path_to_root Path to root of the page to which you want to fit the URL.
     * @return string The transformed string
     */
    /* static */ function html_convert_root_relative2relative($html_text, $path_to_root = PATH_TO_ROOT)
    {
        return  preg_replace(
	            '`((?:<[^>]+) (?:' . URL_TAGS . ')(?:="))(/[^"]+)("(?:[^<]*>))`',
                '$1' . $path_to_root . '$2$3',
        $html_text);
    }

    /**
     * @static
     * @desc replace a relative url by the corresponding absolute one
     * @param string[] $url_params Array containing the attributes containing the url and the url
     * @return string the replaced url
     */
    /* static */ function _convert_url_to_absolute($url_params)
    {
        $url = new Url($url_params[2]);
        $url_params[2] = $url->absolute();
        return $url_params[1] . $url_params[2] . $url_params[3];
    }

    /**
     * @static
     * @desc replace an absolute url by the corresponding relative one if possible
     * @param string[] $url_params Array containing the attributes containing the url and the url
     * @return string the replaced url
     */
    /* static */ function _convert_url_to_relative($url_params)
    {
        $url = new Url($url_params[2]);
        $url_params[2] = $url->relative();
        return $url_params[1] . $url_params[2] . $url_params[3];
    }

    /**
     * @static
     * @param string $url the url to "relativize"
     * @return string the relative url of the $url parameter
     */
    /* static */ function get_relative($url, $path_to_root = null, $server_url = null)
    {
        $o_url = new Url($url, $path_to_root, $server_url);
        return $o_url->relative();
    }

    /**
     * @static
     * @desc Overrides the used PATH_TO_ROOT. if the argument is null, the value is only returned.
     * Please note this is a PHP4 hack to allow a Class variable.
     * @param string $path the new PATH_TO_ROOT to use
     * @return string the used PATH_TO_ROOT
     */
    function path_to_root($path = null)
    {
        static $path_to_root = PATH_TO_ROOT;
        if ($path != null)
        {
            $path_to_root = $path;
        }
        return $path_to_root;
    }

    /**
     * @static
     * @desc Overrides the used SERVER URL. if the argument is null, the value is only returned.
     * Please note this is a PHP4 hack to allow a Class variable.
     * @param string $path the new SERVER URL to use
     * @return string the used SERVER URL
     */
    function server_url($url = null)
    {
        static $server_url = SERVER_URL;
        if ($url !== null)
        {
            $server_url = $url;
        }
        return $server_url;
    }

    var $url = '';
    var $is_relative = false;
    var $path_to_root = '';
    var $server_url = '';
}
?>