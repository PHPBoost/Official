<?php
/*##################################################
 *                           AdminMembersDisplayResponse.class.php
 *                            -------------------
 *   begin                : April 18, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminMembersDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
        parent::__construct($view);

		$lang = LangLoader::get('admin-members-common');
		$picture = '/templates/' . get_utheme() . '/images/admin/members.png';
		$this->set_title($lang['members.members-management']);
		$this->add_link($lang['members.members-management'], PATH_TO_ROOT . '/admin/admin_members.php', $picture);
		$this->add_link($lang['members.add-member'], AdminMembersUrlBuilder::add(), $picture);
		$this->add_link($lang['members.config-members'], AdminMembersUrlBuilder::configuration(), $picture);
		$this->add_link($lang['members.members-punishment'], PATH_TO_ROOT . '/admin/admin_members_punishment.php', $picture);

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>