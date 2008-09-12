<?php
/*##################################################
 *                              admin_alerts.php
 *                            -------------------
 *   begin                : August 30, 2008
 *   copyright            : (C) 2008 Sautel Benoit
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', '../../..');

require_once(PATH_TO_ROOT . '/kernel/begin.php');

define('NO_SESSION_LOCATION', true);

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

if( !$Member->Check_level(ADMIN_LEVEL) )
	die('');

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/administrator_alert_service.class.php');

$change_status = retrieve(GET, 'change_status', 0);
$delete = retrieve(GET, 'delete', 0);

if( $change_status > 0 )
{
	$alert = new AdministratorAlert();
	$alert->load_from_db($change_status);
    
	$new_status = $alert->get_status() != CONTRIBUTION_STATUS_PROCESSED ? CONTRIBUTION_STATUS_PROCESSED : CONTRIBUTION_STATUS_UNREAD;
	
	$alert->set_status($new_status);
	
	$alert->save();
	
	echo '1';
}
elseif( $delete > 0 )
{
	$alert = new AdministratorAlert();
	$alert->load_from_db($delete);
	
	$alert->delete();
	
	echo '1';
}

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');

?>