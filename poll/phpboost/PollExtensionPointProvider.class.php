<?php
/*##################################################
 *                              pollExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
 *   email                : crowkait@phpboost.com
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

class PollExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('poll');
    }

    //R�cup�ration du cache.
	function get_cache()
	{
		$poll_config = PollConfig::load();
		
		//Liste des sondages affich�s dans le mini module
		$config_displayed_in_mini_module_list = $poll_config->get_displayed_in_mini_module_list();
		
		$_array_poll = '';
		if (!empty($config_displayed_in_mini_module_list) && is_array($config_displayed_in_mini_module_list))
		{
			foreach ($config_displayed_in_mini_module_list as $key => $idpoll)
			{
				$poll = $this->sql_querier->query_array(PREFIX . 'poll', 'id', 'question', 'votes', 'answers', 'type', "WHERE id = '" . $idpoll . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
				if (!empty($poll['id'])) //Sondage existant.
				{
					$array_answer = explode('|', $poll['answers']);
					$array_vote = explode('|', $poll['votes']);

					$total_vote = array_sum($array_vote);
					$total_vote = ($total_vote == 0) ? 1 : $total_vote; //Emp�che la division par 0.

					$array_votes = array_combine($array_answer, $array_vote);
					foreach ($array_votes as $answer => $nbrvote)
						$array_votes[$answer] = NumberHelper::round(($nbrvote * 100 / $total_vote), 1);

					$_array_poll .= $key . ' => array(\'id\' => ' . var_export($poll['id'], true) . ', \'question\' => ' . var_export($poll['question'], true) . ', \'votes\' => ' . var_export($array_votes, true) . ', \'total\' => ' . var_export($total_vote, true) . ', \'type\' => ' . var_export($poll['type'], true) . '),' . "\n";
				}
			}
		}

		$code = 'global $_array_poll;' . "\n\n" . '$_array_poll = array(' . $_array_poll . ');';

		return $code;
	}

	public function home_page()
	{
		return new PollHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(
			new PollModuleMiniMenu()
		));
	}
}
?>