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

require_once('fse.class.php');

define('ERASE', false);
define('ADD', true);

// fonction de gestion des fichiers
class File extends FileSystemElement
{
	## Public Attributes ##
	var $lines = array();
	var $contents;
	
	## Public Methods ##	
	// Constructeur
	function File($path, $whenopen = OPEN_AFTER)
	{
		parent::FileSystemElement($path);
		
		if( @file_exists($this->path) )
		{
			if( !@is_file($this->path) )
				return false;
			
			if( $whenopen == OPEN_NOW )
				$this->open();
		}
		else if( !@touch($this->path) )
			return false;
			
		return true;
	}
	
	// lit le fichier et initialise les attributs
	function open()
	{
		parent::open();
		
		$this->lines = file($this->path);
		$this->contents = implode("\n", $this->lines);
	}
	
	// renvoie le contenu du fichier en commen�ant � l'octet $start
	function get_contents($start = 0, $len = -1)
	{
		parent::get();
		
		if( !$start && $len == -1 )
			return $this->contents;
		else if( $len == -1 )
			return substr($this->contents, $start);
		else
			return substr($this->contents, $start, $len);
	}
	
	// renvoie le contenu du fichier sous forme de tableau
	function get_lines($start = 0, $n = -1)
	{
		parent::get();
		
		if( !$start && $n == -1 )
			return $this->lines;
		else if( $n == -1 )
			return array_slice($this->lines, $start);
		else
			return array_slice($this->lines, $start, $n);
	}
	
	// �crit $data dans le fichier, soit en �crasant les donn�es ( par d�faut ), soit passant en troisi�me param�tre la constante ADD
	function write($data, $what = ERASE)
	{
		$mode = ( $what == ADD ) ? 'a' : 'w';
		
		if( !($fp = @fopen($this->path, $mode)) )
			return false;
		
		fwrite($fp, $data);
		fclose($fp);
		
		parent::write();
		
		return true;
	}
	
	// supprime le fichier
	function delete()
	{
		@unlink($this->path);
	}
	
	## Private Methods ##	
}

?>