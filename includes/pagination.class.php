<?php
/*##################################################
 *                             pagination.class.php
 *                            -------------------
 *   begin                : June 30, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
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

define('NO_PREVIOUS_NEXT_LINKS', false);
define('LINK_START_PAGE', false);

class Pagination
{
	## Public Methods ##
	//Renvoi la cha�ne de liens format�e.
	function Display_pagination($path, $total_msg, $var_page, $nbr_msg_page, $nbr_max_link, $font_size = 11, $previous_next = true, $link_start_page = true)
	{
		if( $total_msg > $nbr_msg_page )
		{
			//Initialisations.
			$links = ''; //Cha�ne � retourner.
			$nbr_max_link = $nbr_max_link;

			$this->page = $this->get_var_page($var_page);
			$nbr_page = ceil($total_msg / $nbr_msg_page); //Calcul du nombre page.
			if( $nbr_page == 1 )
				return '';
				
			$this->page = $this->check_page($nbr_page); //Page valide.
			
			//Affichage lien suivant � (si activ� !)	
			if( $this->page != 1 && $nbr_page > 1 && $previous_next === true) //Plus qu'une page, et page diff�rente de celle par d�faut => affichage du lien.
				$links .= '&nbsp<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $this->page - 1) . '">&laquo;</a>&nbsp;';
			
			$page_max_end = $nbr_page - $this->nbr_end_links; //Num�ro de la page $this->nbr_end_links avant la derni�re page.
			$page_current_max = $this->page + $nbr_max_link; //Num�ro de la page $nbr_max_link apr�s la page courante.
			$page_current_min = $this->page - $nbr_max_link; //Num�ro de la page $nbr_max_link avant la page courante.
			
			for($i = 1; $i <= $nbr_page; $i++)
			{
				if( $i == $this->page && $link_start_page ) //Page courante.
					$links .= '&nbsp;<span class="text_strong" style="font-size:' . $font_size . 'px;text-decoration: underline;">' . $this->page . '</span>&nbsp;';					
				elseif( $i <= $this->nbr_start_links || $i > $page_max_end || ($i <= $page_current_max && $i >= $page_current_min) )
					$links .= '&nbsp;<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $i) . '">' . $i . '</a>&nbsp;';			
				else //Affiche les $nbr_max_link liens pr�c�dents/suivant la page courante, si d�passe le $this->nbr_start_links ou le $this->nbr_end_links
				{
					if( $i >= $this->nbr_start_links && $i <= $page_current_min )
					{
						$i = $page_current_min - 1; //Saut conditionnel d'it�ration, envoi � la page: $this->page - $nbr_max_link.
						$links .= '...';
					}                                         
					elseif( $i >= $page_current_max && $i <= $page_max_end )
					{
						$i = $page_max_end; //Saut conditionnel d'it�ration, envoi � la page: $this->page + $nbr_max_link.
						$links .= '...';
					}
				}
			}			

			//Affichage lien pr�c�dent � (si activ� !)	
			if( $this->page != $nbr_page && $nbr_page > 1 && $previous_next === true ) //Plusieurs page et page courante diff�rente de la derni�re => affichage du lien.
				$links .= '&nbsp;<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $this->page + 1) . '">&raquo;</a>';
			
			return $links; //On retourne la cha�ne format�e.
		}
		else 
			return '';		
	}
	
	//Calcule le num�ro du premier message de la page actuelle.
	function First_msg($nbr_msg_page, $var_page)
	{
		$page = !empty($_GET[$var_page]) ? numeric($_GET[$var_page]) : 1;	
		$page = $page > 0 ? $page : 1;	
		return (($page - 1) * $nbr_msg_page); 
	}
	
	
	## Private Methods ##
	//R�cup�re la valeur de la page courante.
	function get_var_page($var_page)
	{
		$_GET[$var_page] = isset($_GET[$var_page]) ? numeric($_GET[$var_page]) : 0;
		if( !empty($_GET[$var_page]) )
			return $_GET[$var_page];
		else 
			return 1;
	}
		
	//V�rifie si la page sur laquelle on se trouve est valide, sinon renvoi sur une page d'erreur.
	function check_page($nbr_page)
	{		
		global $Errorh;
		
		if( $this->page < 0 ) //Erreur => redirection! 
			$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT);
		elseif( $this->page > $nbr_page ) //Erreur => redirection! 
			$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT); 

		return $this->page;
	}
	
	## Private Attribute ##
	var $page; //Valeur courante de la page.
	var $nbr_start_links = 3; //Nombre de liens affich�s en d�but de cha�ne.
	var $nbr_end_links = 3; //Nombre de liens affich�s en fin de cha�ne.	
	//$nbr_max_link => Nombre de lien max avant et apr�s la page courante. 
}

?>