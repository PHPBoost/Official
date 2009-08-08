<?php
/*##################################################
 *                           mysql_criteria.class.php
 *                            -------------------
 *   begin                : June 13 2009
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

import('mvc/dao/criteria/sql_criteria');
import('mvc/dao/criteria/restriction/mysql_restriction');

class MySQLCriteria extends SQLCriteria
{
	public function __construct($model)
	{
		parent::__construct($model, MySQLDAO::get_connection());
	}

	public function create_restriction()
	{
		return new MySQLRestriction($this->model);
	}

	public function count()
	{
		$params = array($this->model->table(), 'COUNT(*)');
		$conditions = $this->build_query_conditions();
		if (!empty($conditions))
		{
			$params[] = ' WHERE ' . $conditions;
			$params[] = __LINE__;
			$params[] = __FILE__;
		}
		return (int) call_user_func_array(array($this->connection, 'query_array'), $params);
	}

	public function results_list($max_results = 0, $offset = 0, $order_by = null, $way = ICriteria::ASC)
	{
		$query = 'SELECT ' . $this->fields() . ' FROM ' . implode(', ', $this->tables) . ' ';
		$conditions = $this->build_query_conditions();
		if (!empty($conditions))
		{
			$query .= ' WHERE ' . $conditions;
		}
		if (!empty($order_by))
		{
			$query .= ' ORDER BY ' . $order_by;
			if ($way == ICriteria::ASC)
			{
				$query .= ' ASC ';
			}
			else
			{
				$query .= ' DESC ';
			}
		}
		if ($max_results > 0)
		{
		    $query .= ' LIMIT ' .$max_results . ', ' . $offset;
		}
		$results = array();
		$sql_results = $this->connection->query_while($query, __LINE__, __FILE__);
		while ($row = $this->connection->fetch_assoc($sql_results))
		{
			$results[] = $this->model->build($row);
		}
		return $results;
	}

    public function update(&$fields_and_values)
	{
		$query = 'UPDATE ' . $this->model->table() . ' SET ';
        $fields_update = '';
        $model_fields = array_keys($this->model->fiedls());
		foreach ($fields_and_values as $field => $value)
		{
			if (!in_array($field, $model_fields))
			{   // TODO Special exception here
                throw new Exception('Field "' . $field . '" doesn\'t exist in model "' . $this->model->name() . '"');
			}
			// TODO find a solution for request like:
			// SET myField = myField + 1
			$fields_update .= $field->name() . '=' . MySQLDAO::escape($value);
		}
		$conditions = $this->build_query_conditions();
		if (!empty($conditions))
		{
			$query .= 'WHERE ' . $conditions;
		}
		die($query);
		$this->connection->query_inject($query, __LINE__, __FILE__);
	}

	public function delete()
	{
		$query = 'DELETE FROM ' . $this->model->table();
		$conditions = $this->build_query_conditions();
		if (!empty($conditions))
		{
			$query .= ' WHERE ' . $conditions;
		}
		$this->connection->query_inject($query, __LINE__, __FILE__);
	}

	protected function build_query_conditions()
	{
		$joins = $this->model->joins();
		foreach ($joins as $left_key => $right_key)
		{
			$this->restrictions[] = $left_key . '=' . $right_key;
		}
		if (!empty($this->restrictions))
		{
			return '(' . implode(') AND (', $this->restrictions) . ')';
		}
		return '';
	}

	protected function fields($fields_options = null)
	{
		$requested_fields = $this->model->primary_key()->name() . ' AS ' . $this->model->primary_key()->property();
		$fields = $this->model->fields();
		foreach ($fields as $field)
		{
			$requested_fields .= ', ' . $field->name() . ' AS ' . $field->property();
		}
		$fields = array_merge($this->model->extra_fields(), $this->extra_fields);
		foreach ($fields as $field)
		{
			$requested_fields .= ', ' . $field->name() . ' AS ' . $field->property();
			$this->add_external_table($field->get_table());
		}
		return $requested_fields;
	}
}
?>