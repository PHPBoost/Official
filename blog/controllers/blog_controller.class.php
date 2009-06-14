<?php
/*##################################################
 *                           controller.class.php
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

import('io/template');
import('modules/modules_discovery_service');

mimport('blog/controllers/abstract_blog_controller');
mimport('blog/models/blog');


class BlogController extends AbstractBlogController
{
	public function blogs()
	{
		$this->set_bread_crumb();
		$blogs = BlogDAO::instance()->find_all(0, 20, 'creation_date', ICriteria::DESC);
		$tpl = new Template('blog/list.tpl');
		$tpl->assign_vars(array(
            'U_CREATE' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE)->absolute(),
            'U_LIST' => Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute(),
            'L_BLOGS_LIST' => $this->lang['blogs_list'],
            'L_EDIT' => $this->lang['edit'],
            'L_DELETE' => $this->lang['delete'],
            'L_CREATE_NEW_BLOG' => $this->lang['create_new_blog'],
            'EL_BLOGS_LIST' => htmlspecialchars($this->lang['blogs_list']),
            'EL_EDIT' => htmlspecialchars($this->lang['edit']),
            'EL_DELETE' => htmlspecialchars($this->lang['delete']),
            'EL_CREATE_NEW_BLOG' => htmlspecialchars($this->lang['create_new_blog']),
		    'JL_CONFIRM_DELETE' => to_js_string($this->lang['confirm_delete_blog'])
		));

		foreach ($blogs as $blog)
		{
			$tpl->assign_block_vars('blogs', array(
               'TITLE' => $blog->get_title(),
               'DESCRIPTION' => second_parse($blog->get_description()),
        	   'E_TITLE' => htmlspecialchars($blog->get_title()),
               'U_DETAILS' => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
               'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
               'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute()
			));
		}
		$tpl->parse();
	}

	public function create($blog = null, $error_message = null, $blog_id = -1)
	{
		$tpl = new Template('blog/save.tpl');
		if ($blog_id >= 0)
		{
			$this->set_bread_crumb(array(
			$blog->get_title() => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
			$this->lang['edit'] => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
			));
			$tpl->assign_vars(array(
                'U_FORM_VALID' => $blog->action_url(Blog::ACTION_EDIT_VALID)->absolute(),
                'L_SAVE_BLOG' => sprintf($this->lang['edit_blog'], $blog->get_title()),
                'EL_SAVE' => $this->lang['edit']
			));
		}
		else
		{
			$this->set_bread_crumb(array($this->lang['create_new_blog'] => ''));
			$tpl->assign_vars(array(
	            'U_FORM_VALID' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE_VALID)->absolute(),
	            'L_SAVE_BLOG' => $this->lang['create_new_blog'],
	            'EL_SAVE' => $this->lang['create']
			));
		}

		$tpl->assign_vars(array(
            'L_TITLE' => $this->lang['title'],
            'L_DESCRIPTION' => $this->lang['description'],
            'TITLE_MAX_LENGTH' => BlogDAO::instance()->get_model()->field('title')->length(),
		    'KERNEL_EDITOR' => display_editor()
		));
		if (!empty($error_message))
		{
			$tpl->assign_vars(array('L_ERROR_MESSAGE' => $error_message));
		}
		if ($blog instanceof Blog)
		{
			$blog->set_description(unparse($blog->get_description()));
			$tpl->assign_vars(array(
	            'E_TITLE' => htmlspecialchars($blog->get_title()),
	            'DESCRIPTION' => $blog->get_description()
			));
		}
		$tpl->parse();
	}

	public function create_valid($blog_id = -1)
	{
		$this->check_token();
		$blog = new Blog(retrieve(POST, 'title', ''), strparse(retrieve(POST, 'description', '', TSTRING_AS_RECEIVED), array(), false));
		if ($blog_id >= 0)
		{
			$blog->set_id($blog_id);
		}
		try
		{
			BlogDAO::instance()->save($blog);
			redirect($blog->action_url(Blog::ACTION_DETAILS)->absolute());
		}
		catch (BlogValidationExceptionMissingFields $ex)
		{
			$this->create($blog, $this->lang['missing_fields']);
		}
		catch (ValidationException $ex)
		{
			// TODO process exception here thrown by
			// before_save method. You could implement this method
			// the way you want in order to check object completeness
			// or authorizations
		}
	}

	public function view($blog_id, $page = 1)
	{
		$tpl = new Template('blog/blog.tpl');
		$blog = BlogDAO::instance()->find_by_id($blog_id);
		if ($blog === null)
		{
			// TODO error message here
			$tpl->assign_vars(array('L_ERROR_MESSAGE' => 'ERROR_MESSAGE'));
			return;
		}

		$this->set_bread_crumb(array( $blog->get_title() => ''));
		$posts = BlogPostDAO::instance()->find_by_blog_id($blog->get_id(), ($page - 1) * self::POSTS_PER_PAGE, $page * self::POSTS_PER_PAGE);

		$tpl->assign_vars(array(
            'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
            'EL_EDIT' => htmlspecialchars($this->lang['edit']),
            'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute(),
            'EL_DELETE' => htmlspecialchars($this->lang['delete']),
            'TITLE' => $blog->get_title(),
            'DESCRIPTION' => second_parse($blog->get_description())
		));

		foreach ($posts as $post)
		{
			$tpl->assign_block_vars('posts', array(
	            'TITLE' => $post->get_title(),
                'CONTENT' => second_parse($post->get_content()),
                'CREATION_DATE' => $post->get_date()
			));
		}

		$tpl->parse();
	}
	public function edit($blog_id)
	{
		$blog = BlogDAO::instance()->find_by_id($blog_id);
		$this->create($blog, null, $blog_id);
	}
	public function edit_valid($blog_id)
	{
		$this->create_valid($blog_id);
	}
	public function delete($blog_id)
	{
		$this->check_token();
		BlogDAO::instance()->delete($blog_id);
		redirect(Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute());
	}
	
	const POSTS_PER_PAGE = 3;
}
?>