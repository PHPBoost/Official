<?php
/*##################################################
 *                             blog_dao.class.php
 *                            -------------------
 *   begin                : June 02, 2009
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

define('BLOG_DAO__CLASS','blog_dao');

mimport('blog/mvc/model');

/**
 * @author Lo�c Rouchon <horn@phpboost.com>
 * @desc
 */
class BlogDAO extends AbstractDAO
{
	public function __construct()
	{
		parent::__construct(
		  new Model('blog', 'id', array(
		      new ModelField('title', 'string', 64),
		      new ModelField('posts', 'integer', 12, false, 'posts', 'id'))));
	}
}
?>