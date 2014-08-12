<?php
/*##################################################
 *                            PHPBoostAuthenticationMethod.class.php
 *                            -------------------
 *   begin                : November 28, 2010
 *   copyright            : (C) 2010 loic rouchon
 *   email                : horn@phpboost.com
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

/**
 * @author Loic Rouchon <horn@phpboost.com>
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */
class PHPBoostAuthenticationMethod implements AuthenticationMethod
{
	private static $MAX_AUTHORIZED_ATTEMPTS = 5;
	private static $MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY = 600;
	private static $MAX_AUTHORIZED_ATTEMPTS_RESET_ATTEMPS = 0;
	private static $MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_DELAY = 300;
	private static $MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_ATTEMPS = 3;

	/**
	 * @var DBQuerier
	 */
	private $querier;

	private $username;
	private $password;

	private $user_id = null;
	private $connection_attempts = 0;
	private $last_connection_date;

	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = KeyGenerator::string_hash($password);
		$this->querier = PersistenceContext::get_querier();
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function has_user_been_found()
	{
		return $this->user_id != null;
	}

	public function get_remaining_attemps()
	{
		return self::$MAX_AUTHORIZED_ATTEMPTS - $this->connection_attempts;
	}

	/**
	 * {@inheritDoc}
	 */
	public function associate($user_id, $approved = true, $registration_pass = '')
	{
		$internal_authentication_columns = array(
			'user_id' => $user_id,
			'username' => $this->username,
            'password' => $this->password,
			'registration_pass' => $registration_pass,
			'approved' => $approved
		);
		$authentication_method_columns = array(
			'user_id' => $user_id,
            'method' => 'internal',
			'identifier' => $user_id
		);
		try {
            $this->querier->insert(DB_TABLE_INTERNAL_AUTHENTICATION, $internal_authentication_columns);
            $this->querier->insert(DB_TABLE_AUTHENTICATION_METHOD, $authentication_method_columns);
		} catch (SQLQuerierException $ex) {
			throw new IllegalArgumentException('User Id ' . $user_id .
				' is already associated with an authentication method [' . $ex->getMessage() . ']');
		}
	}


	/**
	 * {@inheritDoc}
	 */
	public function authenticate()
	{
        $this->user_id = null;
		try
		{
			return $this->try2authenticate();
		}
		catch (RowNotFoundException $ex) { }
		catch (NotASingleRowFoundException $ex) { }

        $this->user_id = null;
		return false;
	}

	private function try2authenticate()
	{
		$this->find_user_id_by_username();
		$this->check_max_authorized_attempts();
		$match = $this->check_user_password();
		$this->update_user_info();
		return $match;
	}

	private function find_user_id_by_username()
	{
		$columns = array('user_id', 'last_connection', 'connection_attemps');
		$condition = 'WHERE username=:username AND approved=1';
		$parameters = array('username' => $this->username);
		$row = $this->querier->select_single_row(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
		$this->user_id = $row['user_id'];
		$this->connection_attempts = $row['connection_attemps'];
		$this->last_connection_date = $row['last_connection'];
	}

	private function check_max_authorized_attempts()
	{
		$delay_since_last_attempt = time() - $this->last_connection_date;
		if ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY)
		{
			$this->connection_attempts = self::$MAX_AUTHORIZED_ATTEMPTS_RESET_ATTEMPS;
		}
		elseif ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_DELAY)
		{
			$this->connection_attempts = min($this->connection_attempts, self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_ATTEMPS);
		}
		elseif ($this->connection_attempts >= self::$MAX_AUTHORIZED_ATTEMPTS)
		{
			AppContext::get_response()->redirect('/member/error.php?e=e_member_flood#errorh');
		}
	}

	private function check_user_password()
	{
		$condition = 'WHERE user_id=:user_id and password=:password';
		$parameters = array('user_id' => $this->user_id, 'password' => $this->password);
		$match = $this->querier->row_exists(DB_TABLE_INTERNAL_AUTHENTICATION, $condition, $parameters, '*');
		if ($match)
		{
			$this->connection_attempts = 0;
		}
		else
		{
			$this->connection_attempts++;
		}
		return $match;
	}

	private function update_user_info()
	{
		$this->last_connection_date = time();
		$columns = array(
			'last_connection' => $this->last_connection_date,
			'connection_attemps' => $this->connection_attempts,
		);
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $this->user_id);
		$this->querier->update(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
		$this->querier->update(DB_TABLE_MEMBER, array('last_connection_date' => time()), $condition, $parameters);
	}
}

?>
