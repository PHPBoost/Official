<?php
/*##################################################
 *                             ooftp.class.php
 *                            -------------------
 *   begin                : July 02, 2008
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

// Operation on FTP
class OoFTP
{

	// retourne la liste de tout les dossiers et de tout les fichiers se trouvant dans $dir_path correspondant � la regex $regex si celle ci est renseign�e
	// conseil d'utilisation : list($dir, $files) = $OoFTP->parseFTP($path);
	function parseFTP($path, $regex = '')
	{
		$dir = $files = array();
		
		if( is_dir($path) && $dh = @opendir($path) )
        {       
            while( !is_bool($res = readdir($dh)) )
            {
				
				if($res == '.' || $res == '..' || ( !empty($regex) && !preg_match($regex, $res) ) )
					continue;
				
				if( is_file($res) )
					$files[] = $res;
                else
					$dir[] = $res;
            }
            closedir($dh);
        }
		
		return array($dir, $files);
	}
	
	// renvoie le premier dossier trouv� dans $dir_path si il n'y a pas d'erreur, false sinon
	function first_dir($path)
	{
		if( is_dir($path) && $dh = @opendir($path) )
        {       
            while( !is_bool($dir = readdir($dh)) )
            {   
                if( is_dir($dir) && $dir != '.' && $dir != '..')
				{
					closedir($dh);
                    return $dir;
				}
            }
            closedir($dh);
        }
		
		return false;
	}

	//Fonction r�cursive de suppression de dossier.
	function delete_directory($path)
	{
	    if( !is_dir($path) || !($dh = @opendir($path)) )
			return false;
	        
	    while( $res = readdir($dh) )
	    {
	        if( $res == '.' || $res == '..' )
				continue;
			
	        $path_res = $path . '/' . $res;
	        if( is_file($path_res) )
			{
	            if( !@unlink($path_res) )
				{
					closedir($dh);
	                return false;
				}
			}
			// si c'est pas un fichier alors c'est un dossier
	        else
			{
				// Si il y a une erreur, on stoppe tout
	            if( $this->delete_directory($path_res) === false)
				{
					close($dh);
					return false;
				}
			}
	    }
	    
	    closedir($dh);
	    if( @rmdir($path) )
	        return true;
	    
	    return false;
	}

	// lit le contenu d'un fichier ligne par ligne en commen�ant � la ligne $startline
	function readlines($file, $startline = 0)
	{
		$lines = array();
		
		if( !( $fp = @fopen($file, 'r') ) )
			return $lines;
		
		$currentindex = 0;
		while(!feof($fp))
		{
			$line = fgets($fp);
			
			if($currentindex >= $startline)
				$lines[] = $line;
			
			$currentindex++;
		}
		
		fclose($fp);
		
		return $lines;
	}
	
	// lit le contenu du fichier en comman�ant � $start octect
	function read($file, $start = 0)
	{
		return substr(@file_get_contents_emulate($file), $start);
	}
	
	// �crit $data dans $file, soit en �crasant les donn�es ( par d�faut ), soit en ajoutant � la suite ( envoyer true en troisi�me param�tre )
	function write($file, $data, $add = false)
	{
		$mode = $add ? 'a' : 'w';
		
		if( !( $fp = @fopen($file, $mode) ) )
			return false;
		
		fwrite($data, $fp);
		
		fclose($fp);
		
		return true;
	}
}

?>