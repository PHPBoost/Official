<?php
/*##################################################
 *		                   GeneralConfig.class.php
 *                            -------------------
 *   begin                : July 5, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class GeneralConfig extends AbstractConfigData
{
	const SITE_URL = 'site_url';
	const SITE_PATH = 'site_path';
	const SITE_NAME = 'site_name';
	const SITE_DESCRIPTION = 'site_description';

	public function get_site_url()
	{
		return $this->get_property(self::SITE_URL);
	}

	/**
	 * @param string $url The URL must begin with a protocol (for instance http://) and must not end with a slash.
	 */
	public function set_site_url($url)
	{
		$this->set_property(self::SITE_URL, $url);
	}

	public function get_site_path()
	{
		return $this->get_property(self::SITE_PATH);
	}

	/**
	 * @param string $url The URL must begin with a slash but must not end with a slash.
	 */
	public function set_site_path($path)
	{
		$this->set_property(self::SITE_PATH, $path);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
		self::SITE_URL => self::get_default_site_url(),
		self::SITE_PATH => '/',
		self::SITE_NAME => '',
		self::SITE_DESCRIPTION => ''
		);
	}

	public static function get_default_site_url()
	{
		return 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
	}

	public static function get_default_site_path($page_path)
	{
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if (!$server_path)
		{
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		}
		$server_path = trim(str_replace($page_path, '', dirname($server_path)));
		return $server_path;
	}
	
	public function get_site_name()
	{
		return $this->get_property(self::SITE_NAME);
	}
	
	public function set_site_name($site_name)
	{
		$this->set_property(self::SITE_NAME, $site_name);
	}
	
	public function get_site_description()
	{
		return $this->get_property(self::SITE_DESCRIPTION);
	}
	
	public function set_site_description($site_description)
	{
		$this->set_property(self::SITE_DESCRIPTION, $site_description);
	}

	/**
	 * Returns the configuration.
	 * @return GeneralConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'general-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'general-config');
	}
}
?>