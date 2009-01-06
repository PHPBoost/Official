<?php
/*##################################################
 *                    administrator_alert_service.class.php
 *                            -------------------
 *   begin                : August 29, 2008
 *   copyright            : (C) 2008 Beno�t Sautel
 *   email                : ben.popeye@phpboost.com
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

import('events/administrator_alert');

//Flag which distinguishes an alert and a contribution in the database
define('ADMINISTRATOR_ALERT_TYPE', 1);

//This is a static class, it must not be instantiated.
class AdministratorAlertService
{
	//Function which builds an alert knowing its id. If it's not found, it returns null
	/*static*/ function find_by_id($alert_id)
	{
		global $Sql;
		
		//Selection query
		$result = $Sql->query_while("SELECT id, entitled, fixing_url, current_status, id_in_module, identifier, type, priority, creation_date, description
		FROM " . DB_TABLE_EVENTS  . "
		WHERE id = '" . $alert_id . "'
		ORDER BY creation_date DESC", __LINE__, __FILE__);
		
		$properties = $Sql->fetch_assoc($result);
		
		if ((int)$properties['id'] > 0)
		{
			//Creation of the object we are going to return
			$alert = new AdministratorAlert();
			$alert->build($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['creation_date']), $properties['id_in_module'], $properties['identifier'], $properties['type'], $properties['priority']);
			return $alert;
		}
		else
			return null;
	}
	
	//Function which builds a list of alerts matching the required criteria(s)
	/*static*/ function find_by_criteria($id_in_module = null, $type = null, $identifier = null)
	{
		global $Sql;
		$criterias = array();
	
		if ($id_in_module != null)
			$criterias[] = "id_in_module = '" . intval($id_in_module) . "'";
		
		if ($type != null)
			$criterias[] = "type = '" . strprotect($type) . "'";
			
		if ($identifier != null)
			$criterias[] = "identifier = '" . strprotect($identifier). "'";
		
		//Restrictive criteria
		if (!empty($criterias))
		{
			$array_result = array();
			$where_clause = "contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "' AND " . implode($criterias, " AND ");
			$result = $Sql->query_while("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
			FROM " . DB_TABLE_EVENTS  . "
			WHERE " . $where_clause, __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
			{
				$alert = new AdministratorAlert();
				$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
				$array_result[] = $alert;
			}
			
			return $array_result;
		}
		//There is no criteria, we return all alerts
		else
			return AdministratorAlertService::get_all_alerts();
	}
	
 	/*static*/ function find_by_identifier($identifier, $type = '')
	{
        global $Sql;
        
        $result = $Sql->query_while(
            "SELECT id, entitled, fixing_url, current_status, creation_date, id_in_module, priority, identifier, type, description
    		FROM " . DB_TABLE_EVENTS  . "
    		WHERE identifier = '" . addslashes($identifier) . "'" . (!empty($type) ? " AND type = '" . addslashes($type) . "'" : '') . " ORDER BY creation_date DESC " . $Sql->limit(0, 1) . ";"
            , __LINE__, __FILE__);
            
		if ($row = $Sql->fetch_assoc($result))
		{
            $alert = new AdministratorAlert();
			$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
            
			return $alert;
        }
        $Sql->query_close($result);
        
        return null;
	}
	
	//Function which returns all the alerts of the table
	/*static*/ function get_all_alerts($criteria = 'creation_date', $order = 'desc', $begin = 0, $number = 20)
	{
		global $Sql;
		
		$array_result = array();
		
		//On liste les alertes
		$result = $Sql->query_while("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
		FROM " . DB_TABLE_EVENTS  . "
		WHERE contribution_type = " . ADMINISTRATOR_ALERT_TYPE . "
		ORDER BY " . $criteria . " " . strtoupper($order) . " " . 
		$Sql->limit($begin, $number), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$alert = new AdministratorAlert();
			$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
			$array_result[] = $alert;
		}
		
		$Sql->query_close($result);
		
		return $array_result;
	}
	
	//Function which saves an alert in the database. It creates it whether it doesn't exist or updates it if it already exists.
	/*static*/ function save_alert(&$alert)
	{
		global $Sql, $Cache;
		
		// If it exists already in the data base
		if ($alert->get_id() > 0)
		{
			//This line exists only to be compatible with PHP 4 (we cannot use $var->get_var()->method(), whe have to use a temp var)
			$creation_date = $alert->get_creation_date();
			
			$Sql->query_inject("UPDATE " . DB_TABLE_EVENTS  . " SET entitled = '" . addslashes($alert->get_entitled()) . "', description = '" . addslashes($alert->get_properties()) . "', fixing_url = '" . addslashes($alert->get_fixing_url()) . "', current_status = '" . $alert->get_status() . "', creation_date = '" . $creation_date->get_timestamp() . "', id_in_module = '" . $alert->get_id_in_module() . "', identifier = '" . addslashes($alert->get_identifier()) . "', type = '" . addslashes($alert->get_type()) . "', priority = '" . $alert->get_priority() . "' WHERE id = '" . $alert->get_id() . "'", __LINE__, __FILE__);
			
			//Regeneration of the member cache file
			if ($alert->get_must_regenerate_cache())
			{
				$Cache->generate_file('member');
				$alert->set_must_regenerate_cache(false);
			}
		}
		else //We create it
		{
			$creation_date = new Date();
			$Sql->query_inject("INSERT INTO " . DB_TABLE_EVENTS  . " (entitled, description, fixing_url, current_status, creation_date, id_in_module, identifier, type, priority) VALUES ('" . addslashes($alert->get_entitled()) . "', '" . addslashes($alert->get_properties()) . "', '" . addslashes($alert->get_fixing_url()) . "', '" . $alert->get_status() . "', '" . $creation_date->get_timestamp() . "', '" . $alert->get_id_in_module() . "', '" . addslashes($alert->get_identifier()) . "', '" . addslashes($alert->get_type()) . "', '" . $alert->get_priority() . "')", __LINE__, __FILE__);
			$alert->set_id($Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_EVENTS ));

			//Cache regeneration
			$Cache->generate_file('member');
		}
	}
	
	//Function which deletes an alert from the database
	/*static*/ function delete_alert(&$alert)
	{
		global $Sql, $Cache;
		
		// If it exists in the data base
		if ($alert->get_id() > 0)
		{			
			$Sql->query_inject("DELETE FROM " . DB_TABLE_EVENTS  . " WHERE id = '" . $alert->get_id() . "'", __LINE__, __FILE__);
			$alert->set_id(0);
		}
		//Else it's not present in the database, we have nothing to delete
	}
	
	//Counts the number of unread alerts
	/*static*/ function compute_number_unread_alerts()
	{
		global $Sql;
		
		return array('unread' => $Sql->query("SELECT count(*) FROM ".DB_TABLE_EVENTS  . " WHERE current_status = '" . ADMIN_ALERT_STATUS_UNREAD . "' AND contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__),
			'all' => $Sql->query("SELECT count(*) FROM " . DB_TABLE_EVENTS . " WHERE contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__)
			);
	}
	
	//Returns the number of unread alerts
	/*static*/ function get_number_unread_alerts()
	{
		global $ADMINISTRATOR_ALERTS;
		return $ADMINISTRATOR_ALERTS['unread'];
	}
	
	//Returns the number of alerts
	/*static*/ function get_number_alerts()
	{
		global $ADMINISTRATOR_ALERTS;
		return $ADMINISTRATOR_ALERTS['all'];
	}
}

?>