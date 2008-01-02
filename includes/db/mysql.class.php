<?php
/*##################################################
 *                                mysql.class.php
 *                            -------------------
 *   begin                : March 13, 2006
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : mickaelhemri@gmail.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('LOW_PRIORITY', 'LOW_PRIORITY');
define('DB_NO_CONNECT', false);

class Sql
{
	var $query = 0; //Compteur des requ�tes sql.
	var $link; //Lien avec la base de donn�e.
	var $result = array(); //Resultat de la requ�te.
	var $req = 0; //Nombre de requ�tes.
	
	function Sql($connect = true) //Constructeur
	{
		global $sql_host, $sql_login, $sql_pass, $sql_base;
		static $connected = false;
		if( $connect == true && !$connected )
		{
			$this->link = @$this->sql_connect($sql_host, $sql_login, $sql_pass) or $this->sql_error('', 'Connexion base de donn�e impossible!', __LINE__, __FILE__);
			@$this->sql_select_db($sql_base, $this->link) or $this->sql_error('', 'Selection de la base de donn�e impossible!', __LINE__, __FILE__);
			$connected = true;			
		}
		return;
	}
	
	//Connexion
	function sql_connect($sql_host, $sql_login, $sql_pass)
	{
		return mysql_connect($sql_host, $sql_login, $sql_pass);
	}
	
	//Connexion
	function sql_select_db($sql_base, $link)
	{
		return mysql_select_db($sql_base, $link);
	}

	//Requ�te simple
	function query($query, $errline, $errfile) 
	{		
		$this->result = mysql_query($query) or $this->sql_error($query, 'Requ�te simple invalide', $errline, $errfile);		
		$this->result = mysql_fetch_row($this->result);
		$this->close($this->result); //D�chargement m�moire.	
		$this->req++;			

		return $this->result[0];
	}

	//Requ�te multiple.
	function query_array()
	{
		$table = func_get_arg(0);
		$nbr_arg = func_num_args();

		if( func_get_arg(1) !== '*' )
		{
			$nbr_arg_field_end = ($nbr_arg - 4);			
			for($i = 1; $i <= $nbr_arg_field_end; $i++)
			{
				if( $i > 1)
					$field .= ', ' . func_get_arg($i);
				else
					$field = func_get_arg($i);
			}
			$end_req = ' ' . func_get_arg($nbr_arg - 3);
		}
		else
		{
			$field = '*';
			$end_req = ($nbr_arg > 4) ? ' ' . func_get_arg($nbr_arg - 3) : '';
		}
		
		$error_line = func_get_arg($nbr_arg - 2);
		$error_file = func_get_arg($nbr_arg - 1);
		$this->result = mysql_query('SELECT ' . $field . ' FROM ' . PREFIX . $table . $end_req) or $this->sql_error('SELECT ' . $field . ' FROM ' . PREFIX . $table . '' . $end_req, 'Requ�te multiple invalide', $error_line, $error_file);
		$this->result = mysql_fetch_assoc($this->result);
		$this->close($this->result); //D�chargement m�moire.
		$this->req++;		
		
		return $this->result;
	}

	//Requete d'injection (insert, update, et requ�tes complexes..)
	function query_inject($query, $errline, $errfile) 
	{
		$resource = mysql_query($query) or $this->sql_error($query, 'Requ�te inject invalide', $errline, $errfile);
		$this->req++;
		
		return $resource;
	}

	//Requ�te de boucle.
	function query_while($query, $errline, $errfile) 
	{
		$this->result = mysql_query($query) or $this->sql_error($query, 'Requ�te while invalide', $errline, $errfile);
		$this->req++;

		return $this->result;
	}
	
	//Nombre d'entr�es dans la table.
	function count_table($table, $errline, $errfile)
	{ 
		$this->result = mysql_query('SELECT COUNT(*) AS total FROM ' . PREFIX . $table) or $this->sql_error('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, 'Requ�te count invalide', $errline, $errfile);
		$this->result = mysql_fetch_assoc($this->result);
		$this->close($this->result); //D�chargement m�moire.		
		$this->req++;
		
		return $this->result['total'];
	}

	//Limite des r�sultats de la requete sql.
	function sql_limit($start, $end = 0)
	{
		return ' LIMIT ' . $start . ', ' .  $end;
	}
		
	//Balayage du retour de la requ�te sous forme de tableau index� par le nom des champs.
	function sql_fetch_assoc($result)
	{	
		return mysql_fetch_assoc($result);
	}
	
	//Balayage du retour de la requ�te sous forme de tableau index� num�riquement.
	function sql_fetch_row($result)
	{	
		return mysql_fetch_row($result);
	}
	
	//Lignes affect�es lors de requ�tes de mise � jour ou d'insertion.
	function sql_affected_rows($ressource, $query)
	{
		return mysql_affected_rows();
	}
	
	//Nombres de lignes retourn�es.
	function sql_num_rows($ressource, $query)
	{
		return mysql_num_rows($ressource);
	}
	
	//Retourne l'id de la derni�re insertion
	function sql_insert_id($query)
	{
		return mysql_insert_id();
	}
	
	//Retourne la fonction de r�cup�ration de la date actuelle.
	function sql_now()
	{
		return 'NOW()';
	}
	
	//Retourne la fonction de r�cup�ration du jour.
	function sql_day($field)
	{
		return 'DAYOFMONTH(' . $field . ')';
	}
	
	//Retourne la fonction de r�cup�ration du mois.
	function sql_month($field)
	{
		return 'MONTH(' . $field . ')';
	}
	
	//Retourne la fonction de r�cup�ration de l'ann�e.
	function sql_year($field)
	{
		return 'YEAR(' . $field . ')';
	}
	
	//Retourne le nombre d'ann�e entre la date et aujourd'hui.
	function sql_date_diff($field)
	{
		return '(YEAR(CURRENT_DATE) - YEAR(' . $field . ')) - (RIGHT(CURRENT_DATE, 5) < RIGHT(' . $field . ', 5))';
	}
	
	//D�chargement m�moire.
	function close($result)
	{
		if( is_resource($result) )
			return mysql_free_result($result);		
	}

	//Fermeture de la connexion mysql ouverte.
	function sql_close()
	{
		if( $this->link ) // si la connexion est �tablie
			return mysql_close($this->link); // on ferme la connexion ouverte.
	}
	
	//Liste les champs d'une table.
	function sql_list_fields($table)
	{
		global $sql_base;
		
		if( !empty($table) )
		{
			$array_fields_name = array();
			$result = $this->query_while("SHOW COLUMNS FROM " . $table . " FROM `" . $sql_base . "`", __LINE__, __FILE__);
			while( $row = mysql_fetch_row($result) ) 
				$array_fields_name[] = $row[0];
			return $array_fields_name;
		}
		else 
			return array();
	}
	
	//Liste les tables + infos.
	function sql_list_tables()
	{
		global $sql_base;
		
		$array_tables = array();
		
		$result = $this->query_while("SHOW TABLE STATUS FROM `" . $sql_base . "` LIKE '" . PREFIX . "%'", __LINE__, __FILE__);
		while( $row = mysql_fetch_row($result) )
		{	
			$array_tables[] = array(
				'name' => $row[0], 
				'engine' => $row[1], 
				'rows' => $row[4], 
				'data_length' => $row[6],
				'index_lenght' => $row[8],
				'data_free' => $row[9],
				'collation' => $row[14]
			);
		}
		return $array_tables;
	}
		
	//Parsage d'un fichier SQL => ex�cution des requ�tes.
	function sql_parse($file_path, $tableprefix = '')
	{
		$handle_sql = @fopen($file_path, 'r');
		if( $handle_sql ) 
		{
			$req = '';
			while( !feof($handle_sql) ) 
			{		
				$sql_line = trim(fgets($handle_sql));
				//Suppression des lignes vides, et des commentaires.
				if( !empty($sql_line) && substr($sql_line, 0, 2) !== '--' )
				{		
					//On v�rifie si la ligne est une commande SQL.
					if( substr($sql_line, -1) == ';' )
					{
						if( empty($req) )
							$req = $sql_line;
						else
							$req .= ' ' . $sql_line;
							
						if( !empty($tableprefix) )
							$this->query_inject(str_replace('phpboost_', $tableprefix, $req), __LINE__, __FILE__);						
						else
							$this->query_inject($req, __LINE__, __FILE__);						
						$req = '';
					}	
					else //Concat�nation de la requ�te qui peut �tre multi ligne.
						$req .= ' ' . $sql_line;					
				}		
			}
			@fclose($handle);
		}
	}	
	
	//Gestion des erreurs.
	function sql_error($query, $errstr, $errline = '', $errfile = '') 
	{
		global $errorh;
		
		//Enregistrement dans le log d'erreur.
		$errorh->error_handler($errstr . '<br /><br />' . $query . '<br /><br />' . mysql_error(), E_USER_ERROR, $errline, $errfile);
	}
}		
?>