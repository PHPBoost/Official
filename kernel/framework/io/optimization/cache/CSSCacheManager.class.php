<?php
/*##################################################
 *                               CSSCacheManager.class.php
 *                            -------------------
 *   begin                : March 29, 2011
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
*/
class CSSCacheManager
{
	private $css_optimizer;
	private $files = array();
	private $cache_file_location = '';

	public function __construct()
	{
		$this->css_optimizer = new CSSFileOptimizer();
	}
	
	public function set_files(Array $files)
	{
		foreach ($files as $file)
		{
			$this->css_optimizer->add_file(PATH_TO_ROOT . $file);
		}
		$this->files = $files;
	}
	
	public function set_cache_file_location($location)
	{
		$this->cache_file_location = $location;
	}
	
	public function execute($intensity = CSSFileOptimizer::LOW_OPTIMIZATION)
	{
		if (!file_exists($this->cache_file_location))
		{
			$this->force_regenerate_cache($intensity);
		}
		else
		{
			foreach ($this->css_optimizer->get_files() as $file)
			{
				if(filemtime($file) > filemtime($this->cache_file_location))
				{
					$this->force_regenerate_cache($intensity);
					break;
				}
			}
		}
	}
	
	public function get_cache_file_location()
	{
		return $this->cache_file_location;
	}
	
	public function force_regenerate_cache($intensity)
	{
		$this->css_optimizer->optimize($intensity);
		$this->css_optimizer->export_to_file($this->cache_file_location);
	}	
}
?>