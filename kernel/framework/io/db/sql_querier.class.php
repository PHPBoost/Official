<?php
/*##################################################
 *                           sql_querier.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

import('io/db/common_query');
import('io/db/sql_querier_exception');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package io
 * @subpackage sql
 * @desc
 *
 */
interface SQLQuerier
{
	/**
	 * @desc the query var prefix
	 * @var string
	 */
	const QUERY_VAR_PREFIX = ':';
	
    /**
     * @desc executes the <code>$query</code> sql request and returns the query result.
     * <p>Query will first be converted into the specific sgbd dialect.</p>
     * <p>Next query functions will be converted into the specific sgbd dialect.</p>
     * <p>Then query vars ":sample_query_var" will be replaced by the value of
     * the <code>$parameters['sample_query_var']</code> variable if existing.
     * If not (there's a lot of chance that you have forgotten to register this
     * query var in the <code>$parameters</code> map), the query var won't be replaced</p> 
     * @param string $query the query to execute
     * @param string[string] $parameters the query_var map
     * @return QueryResult the query result set
     */
    function select($query, $parameters = array());
    
    /**
     * @desc executes the <code>$query</code> sql request.
     * <p>Query will first be converted into the specific sgbd dialect.</p>
     * <p>Next query functions will be converted into the specific sgbd dialect.</p>
     * <p>Then query vars ":sample_query_var" will be replaced by the value of
     * the <code>$parameters['sample_query_var']</code> variable if existing.
     * If not (there's a lot of chance that you have forgotten to register this
     * query var in the <code>$parameters</code> map), the query var won't be replaced</p> 
     * @param string $query the query to execute
     * @param string[string] $parameters the query_var map
     */
    function inject($query, $parameters = array());
    
    /**
     * @desc returns the last executed query converted to the mysql dialect
     * with query vars replaced by their values
     * @debug this method is only there to make unit-tests easier
     * @return string the last executed query
     */
    function get_last_executed_query_string();
    
    /**
     * @desc start a new transaction. If a transaction has already been started,
     * no new transaction will be created, but the existing one will be used
     * (does not count in the requests count)
     */
    function start_transaction();
    
    /**
     * @desc commit the current transaction (does not count in the requests count)
     */
    function commit();
    
    /**
     * @desc rollback the current transaction (does not count in the requests count)
     */
    function rollback();
    
    /**
     * @desc returns the number of executed requests by this querier
     * @return int the number of executed requests by this querier
     */
    function get_executed_requests_count();
    
    /**
     * @desc returns the primary key value generated by the last insert query
     * @return int the primary key value generated by the last insert query
     */
    function get_last_inserted_id();
}

?>