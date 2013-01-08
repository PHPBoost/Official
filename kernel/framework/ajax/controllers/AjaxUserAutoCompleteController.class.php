<?php
/*##################################################
 *                          AjaxUserAutoCompleteController.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AjaxUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$tpl = new StringTemplate('<ul> # START results # <li>{results.NAME}</li> # END results # </ul>');
 
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '" . $request->get_value('value', '') . "%'",
			array(), SelectQueryResult::FETCH_ASSOC);
 
		while($row = $result->fetch())
		{
			$tpl->assign_block_vars('results', array('NAME' => $row['login']));
		}
 
		return new SiteNodisplayResponse($tpl);
	}
}
?>