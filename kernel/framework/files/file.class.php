<?php
/*##################################################
 *                             file.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright          : (C) 2008 Nicolas Duhamel
 *   email                : akhenathon2@gmail.com
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

// fonction de gestion des fichiers
class File
{
	var $path;
	var $lines = array();
	var $contents;
	var $is_read = false;
	
	// constructeur
	function File($path, $readnow = false)
	{
		if( is_file($path) )
		{
			$this->path = $path;
			if( $readnow )
				$this->readfile();
		}
	}
	
	// fonction priv�e qui lit le fichier
	/* private */
	function readfile()
	{
		if( !$this->is_read )
		{
			$this->lines[] = file($path);
			$this->contents = implode("\n", $this->lines);
			$read = true;
		}
	}
	
	// renvoie le contenu du fichier en commen�ant � l'octet $start
	function read($start = 0, $len = -1)
	{
		// si le fichier n'a pas encore �t� lu alors on le lit maintenant
		$this->readfile();
		
		if( !$start && $len == -1 )
			return $this->contents;
		else if( $len == -1 )
			return substr($this->contents, $start);
		else
			return substr($this->contents, $start, $len);
	}
	
	// renvoie le contenu du fichier sous forme de tableau
	function readlines($start = 0, $n = -1)
	{
		// si le fichier n'a pas encore �t� lu alors on le lit maintenant
		$this->readfile();
		
		if( !$start && $n == -1 )
			return $this->lines;
		else if( $n == -1 )
			return array_slice($this->lines, $start);
		else
			return array_slice($this->lines, $start, $n);
	}
	
	// �crit $data dans le fichier, soit en �crasant les donn�es ( par d�faut ), soit en ajoutant � la suite ( envoyer true en deuxi�me param�tre )
	function write($data, $add = false)
	{
		$mode = $add ? 'a' : 'w';
		
		if( !($fp = @fopen($file, $mode)) )
			return false;
		
		fwrite($data, $fp);
		fclose($fp);
		
		// on force la relecture du fichier
		$this->is_read = false;
		$this->readfile();
		
		return true;
	}

}

?>