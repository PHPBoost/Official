<?php
/*##################################################
 *                          contribution.class.php
 *                            -------------------
 *   begin                : July 21, 2008
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

import('util/date');
import('events/event');

## Constants ##

//Bit on which are checked the authorizations
define('CONTRIBUTION_AUTH_BIT', 1);

//Contribution status
define('CONTRIBUTION_STATUS_UNREAD', EVENT_STATUS_UNREAD);
define('CONTRIBUTION_STATUS_BEING_PROCESSED', EVENT_STATUS_BEING_PROCESSED);
define('CONTRIBUTION_STATUS_PROCESSED', EVENT_STATUS_PROCESSED);

//Contribution class
class Contribution extends Event
{
	## Public ##
	function Contribution()
	{
		$this->current_status = CONTRIBUTION_STATUS_UNREAD;
		$this->creation_date = new Date();
		$this->fixing_date = new Date();
		if( defined(MODULE_NAME) )
			$this->module = MODULE_NAME;
	}
	
	//Constructor with all parameters of a contribution
	function build($id, $entitled, $description, $fixing_url, $module, $status, $creation_date, $fixing_date, $auth, $poster_id, $fixer_id, $id_in_module, $identifier, $type, $poster_login = '', $fixer_login = '')
	{
		//Building parent class
		parent::build($id, $entitled, $fixing_url, $status, $creation_date, $id_in_module, $identifier, $type);
		
		//Setting its whole parameters
		$this->description = $description;
		$this->module = $module;
		$this->fixing_date = $fixing_date;
		$this->auth = $auth;
		$this->poster_id = $poster_id;
		$this->fixer_id = $fixer_id;
		$this->poster_login = $poster_login;
		$this->fixer_login = $fixer_login;
		
		//Setting the modification flag to false, it just comes to be loaded
		$this->must_regenerate_cache = false;
	}
	
	//Module setter
	function set_module($module)
	{
		$this->module = $module;
	}
	
	//Fixing date setter
	function set_fixing_date($date)
	{
		if( is_object($date) && strtolower(get_class($date)) == 'date' )
			$this->fixing_date = $date;
	}
	
	// current_status setter
	function set_status($new_current_status)
	{
		if( in_array($new_current_status, array(EVENT_STATUS_UNREAD, EVENT_STATUS_BEING_PROCESSED, EVENT_STATUS_PROCESSED)) )
		{
			//If it just comes to be processed, we automatically consider it as processed
			if( $this->current_status != EVENT_STATUS_PROCESSED && $new_current_status == EVENT_STATUS_PROCESSED )
				$this->fixing_date = new Date();
			
			$this->current_status = $new_current_status;
		}
		//Default
		else
			$this->current_status = EVENT_STATUS_UNREAD;
		
		$this->must_regenerate_cache = true;
	}
	
	//Authorization array setter
	function set_auth($auth)
	{
		if( is_array($auth) )
			$this->auth = $auth;
	}
	
	//Poster id setter
	function set_poster_id($poster_id)
	{
		global $Sql;
		if( $poster_id  > 0)
		{
			$this->poster_id = $poster_id;
			//Assigning also the associated login
			$this->poster_login = $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $poster_id . "'", __LINE__, __FILE__);
		}
	}

	//Fixer id setter
	function set_fixer_id($fixer_id)
	{
		if( $fixer_id  > 0)
		{
			$this->fixer_id = $fixer_id;
			//Assigning also the associated login
			$this->fixer_login = $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $fixer_id . "'", __LINE__, __FILE__);
		}
	}	
	
	//Description setter
	function set_description($description)
	{
		if( is_string($description) )
			$this->description = $description;
	}
	
	// Getters
	function get_description() { return $this->description; }
	function get_module() { return $this->module; }
	function get_fixing_date() { return $this->fixing_date; }
	function get_auth() { return $this->auth; }
	function get_poster_id() { return $this->poster_id; }
	function get_fixer_id() { return $this->fixer_id; }
	function get_poster_login() { return $this->poster_login; }
	function get_fixer_login() { return $this->fixer_login; }
	
	//Status name getter
	function get_status_name()
	{
		global $LANG;
		
		switch($this->current_status)
		{
			case CONTRIBUTION_STATUS_UNREAD:
				return $LANG['contribution_status_unread'];
			case CONTRIBUTION_STATUS_BEING_PROCESSED:
				return $LANG['contribution_status_being_processed'];
			case CONTRIBUTION_STATUS_PROCESSED:
				return $LANG['contribution_status_processed'];
		}
	}
	
	//Module name getter
	function get_module_name()
	{
		global $CONFIG;
		if( !empty($this->module) )
		{
			$module_ini = load_ini_file(PATH_TO_ROOT . '/' . $this->module . '/lang/', $CONFIG['lang']);
			
			return $module_ini['name'];
		}
		else
			return '';
	}
	
	## Protected ##
	//Description of the contribution (for instance to justify a contribution)
	var $description;
	//String containing the identifier of the module corresponding to the contribution (ex: forum)
	var $module = '';
	//Date at which the contribution has been processed (if it is obviously). Default value: date at which is created the contribution
	var $fixing_date;
	//Authorization array containing the people who can treat the contribution
	var $auth = array();
	//Identifier of the member who has posted the contribution
	var $poster_id = 0;
	//Identifier of the member who has fixed the contribution
	var $fixer_id = 0;
	//Login of the member who has posted the contribution
	var $poster_login = '';
	//Login of the member who has fixed the contribution
	var $fixer_login = '';
}

?>