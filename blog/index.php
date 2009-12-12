<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright            : (C) 2009 Lo�c Rouchon
 *   email                : loic.rouchon@phpboost.com
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';

$url_controller_mappers = array(
new UrlControllerMapper('BlogController', '`^/test/?$`'),
new UrlControllerMapper('BlogControllerBlogList', '`^/?$`'),
new UrlControllerMapper('blog_controller', '`^/([0-9]+)/?$`', array('blog_id')),
new UrlControllerMapper('blog_controller', '`^/create/?$`'),
new UrlControllerMapper('blog_controller', '`^/create/valid/?$`'),
new UrlControllerMapper('blog_controller', '`^/([0-9]+)/edit/?$`', array('blog_id')),
new UrlControllerMapper('blog_controller', '`^/([0-9]+)/edit/valid/?$`', array('blog_id')),
new UrlControllerMapper('blog_controller', '`^/([0-9]+)/delete/?$`', array('blog_id')),
new UrlControllerMapper('blog_post_controller', '`^/([0-9]+)/posts/?$`', array('blog_id')),
new UrlControllerMapper('blog_post_controller', '`^/([0-9]+)/posts/([0-9]+)/?$`', array('blog_id', 'post_id')),
new UrlControllerMapper('blog_post_controller', '`^/([0-9]+)/post/add/?$`', array('blog_id')),
new UrlControllerMapper('blog_post_controller', '`^/([0-9]+)/post/add/valid/?$`', array('blog_id')),
new UrlControllerMapper('blog_post_controller', '`^/[0-9]+/post/([0-9]+)/edit/?$`', array('blog_id', 'post_id')),
new UrlControllerMapper('blog_post_controller', '`^/[0-9]+/post/([0-9]+)/edit/valid/?$`', array('blog_id', 'post_id')),
new UrlControllerMapper('blog_post_controller', '`^/[0-9]+/post/delete/([0-9]+)/?$`', array('blog_id', 'post_id'))
);
DispatchManager::dispatch($url_controller_mappers);

?>