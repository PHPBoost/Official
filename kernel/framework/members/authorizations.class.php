<?php
/*##################################################
 *                                autorizations.class.php
 *                            -------------------
 *   begin                : July 26 2008
 *   copyright          : (C) 2008 Viarre R�gis / Sautel Benoit
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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

//This class contains only static methods, it souldn't be instantiated.
class Authorizations
{
	## Public methods ##
	//Retourne le tableau avec les droits issus des tableaux pass�s en argument. Tableau destin� � �tre serialis�.
	/*static*/ function Return_array_auth()
	{
		$array_auth_all = array();
		$sum_auth = 0;
		$nbr_arg = func_num_args();
		
		//R�cup�ration du dernier argument, si ce n'est pas un tableau => bool�en demandant la s�lection par d�faut de l'admin.
		$admin_auth_default = true;
		if( $nbr_arg > 1 )
		{
			$admin_auth_default = func_get_arg($nbr_arg - 1);		
			if( !is_bool($admin_auth_default) )
				$admin_auth_default = true;
			else
				$nbr_arg--; //On diminue de 1 le nombre d'argument, car le denier est le flag.
		}
		//On balaye les tableaux pass�s en argument.
		for($i = 0; $i < $nbr_arg; $i++)
			Authorizations::get_array_auth(func_get_arg($i), '', $array_auth_all, $sum_auth);
		
		//Admin tous les droits dans n'importe quel cas.
		if( $admin_auth_default )
			$array_auth_all['r2'] = $sum_auth;
		ksort($array_auth_all); //Tri des cl�es du tableau par ordre alphab�tique, question de lisibilit�.

		return $array_auth_all;
	}
	
	//Retourne le tableau avec les droits issus du tableau pass� en argument. Tableau destin� � �tre serialis�. 
	/*static*/ function Return_array_auth_simple($bit_value, $idselect, $admin_auth_default = true)
	{
		$array_auth_all = array();
		$sum_auth = 0;
		
		//R�cup�ration du tableau des autorisation.
		Authorizations::get_array_auth($bit_value, $idselect, $array_auth_all, $sum_auth);
		
		//Admin tous les droits dans n'importe quel cas.
		if( $admin_auth_default )
			$array_auth_all['r2'] = $sum_auth;
		ksort($array_auth_all); //Tri des cl�es du tableau par ordre alphab�tique, question de lisibilit�.

		return $array_auth_all;
	}	
	
	//G�n�ration d'une liste � s�lection multiple des rangs, groupes et membres
    /*static*/ function Generate_select_auth($auth_bit, $array_auth = array(), $array_ranks_default = array(), $idselect = '', $disabled = '', $disabled_advanced_auth = false)
    {
        global $Sql, $LANG, $CONFIG, $array_ranks, $Group;
		
        //R�cup�ration du tableau des rangs.
		$array_ranks = is_array($array_ranks) ? $array_ranks : array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		//Identifiant du select, par d�faut la valeur du bit de l'autorisation.
		$idselect = ((string)$idselect == '') ? $auth_bit : $idselect; 
		
		$Template = new Template('framework/groups_auth.tpl');
       
		$Template->Assign_vars(array(
			'C_NO_ADVANCED_AUTH' => ($disabled_advanced_auth) ? true : false,
			'C_ADVANCED_AUTH' => ($disabled_advanced_auth) ? false : true,
            'THEME' => $CONFIG['theme'],
            'PATH_TO_ROOT' => PATH_TO_ROOT,
			'IDSELECT' => $idselect,
			'DISABLED_SELECT' => (empty($disabled) ? 'if(disabled == 0)' : ''),
			'L_MEMBERS' => $LANG['member_s'],
			'L_ADD_MEMBER' => $LANG['add_member'],
			'L_REQUIRE_PSEUDO' => addslashes($LANG['require_pseudo']),
			'L_RANKS' => $LANG['ranks'],
            'L_GROUPS' => $LANG['groups'],
            'L_SEARCH' => $LANG['search'],
			'L_ADVANCED_AUTHORIZATION' => $LANG['advanced_authorization'],
			'L_SELECT_ALL' => $LANG['select_all'],
			'L_SELECT_NONE' => $LANG['select_none'],
			'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple']
        ));
	   
		##### G�n�ration d'une liste � s�lection multiple des rangs et membres #####
		//Liste des rangs
        $j = 0;
        foreach($array_ranks as $idrank => $group_name)
        {
            $selected = '';   
            if( array_key_exists('r' . $idrank, $array_auth) && ((int)$array_auth['r' . $idrank] & (int)$auth_bit) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';
            $selected = (isset($array_ranks_default[$idrank]) && $array_ranks_default[$idrank] === true && empty($disabled)) ? 'selected="selected"' : $selected;
            
			$Template->Assign_block_vars('ranks_list', array(
				'ID' => $j++,
				'IDRANK' => $idrank,
				'RANK_NAME' => $group_name,
				'DISABLED' => $disabled,
				'SELECTED' => $selected
			));
        }
       
        //Liste des groupes.
        foreach($Group->get_groups_array() as $idgroup => $group_name)
        {
            $selected = '';       
            if( array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_bit) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';

            $Template->Assign_block_vars('groups_list', array(
				'IDGROUP' => $idgroup,
				'GROUP_NAME' => $group_name,
				'DISABLED' => $disabled,
				'SELECTED' => $selected
			));
        }
		
		##### G�n�ration du formulaire pour les autorisations membre par membre. #####
		//Recherche des membres autoris�.
		$array_auth_members = array();
		foreach($array_auth as $type => $auth)
		{
			if( substr($type, 0, 1) == 'm' )
			{	
				if( array_key_exists($type, $array_auth) && ((int)$array_auth[$type] & (int)$auth_bit) !== 0 )
					$array_auth_members[$type] = $auth;
			}
		}
		$advanced_auth = count($array_auth_members) > 0;

		$Template->Assign_vars(array(
			'ADVANCED_AUTH_STYLE' => ($advanced_auth ? 'display:block;' : 'display:none;')
		));
		
		//Listing des membres autoris�s.
		if( $advanced_auth )
		{
			$result = $Sql->Query_while("SELECT user_id, login 
			FROM ".PREFIX."member
			WHERE user_id IN(" . implode(str_replace('m', '', array_keys($array_auth_members)), ', ') . ")", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				 $Template->Assign_block_vars('members_list', array(
					'USER_ID' => $row['user_id'],
					'LOGIN' => $row['login']
				));
			}
			$Sql->Close($result);
		}

        return $Template->parse(TEMPLATE_STRING_MODE);
    }
	
	//Fonction statique qui regarde les autorisations d'un individu, d'un groupe ou d'un rank
	/*static*/ function check_some_body_auth($type, $value, &$array_auth, $bit)
	{
		if( !is_int($value) )
			return false;
		
		switch($type)
		{
			case RANK_TYPE:
				if( $value <= 2 && $value >= -1 )
					return @$array_auth['r' . $value] & $bit;
				else
					return false;
			case GROUP_TYPE:
				if( $value >= 1 )
					return !empty($array_auth[$value]) ? $array_auth[$value] & $bit : false;
				else
					return false;
			case USER_TYPE:
				if( $value >= 1 )
					return !empty($array_auth['m' . $value]) ? $array_auth['m' . $value] & $bit : false;
				else
					return false;
			default:
				return false;
		}
	}
	
	##  Private methods ##
	//R�cup�ration du tableau des autorisations.
	/*static*/ function get_array_auth($bit_value, $idselect, &$array_auth_all, &$sum_auth)
	{
		$idselect = ($idselect == '') ? $bit_value : $idselect; //Identifiant du formulaire.
		
		##### Niveau et Groupes #####
		$array_auth_groups = !empty($_POST['groups_auth' . $idselect]) ? $_POST['groups_auth' . $idselect] : '';
		if( !empty($array_auth_groups) ) //R�cup�ration du formulaire.
		{
			$sum_auth += $bit_value;
			if( is_array($array_auth_groups) )
			{			
				//Ajout des autorisations sup�rieure si une autorisations inf�rieure est autoris�e. Ex: Membres autoris�s implique, mod�rateurs et administrateurs autoris�s.
				$array_level = array(0 => 'r-1', 1 => 'r0', 2 => 'r1', 3 => 'r2');
				$min_auth = 3;
				foreach($array_level as $level => $key)
				{
					if( in_array($key, $array_auth_groups) )
						$min_auth = $level;
					else
					{
						if( $min_auth < $level )
							$array_auth_groups[] = $key;
					}
				}
				
				//Ajout des autorisations au tableau final.
				foreach($array_auth_groups as $key => $value)
				{
					if( isset($array_auth_all[$value]) )
						$array_auth_all[$value] += $bit_value;
					else
						$array_auth_all[$value] = $bit_value;
				}
			}
		}
		
		##### Membres (autorisations avanc�es) ######
		$array_auth_members = !empty($_POST['members_auth' . $idselect]) ? $_POST['members_auth' . $idselect] : '';
		if( !empty($array_auth_members) ) //R�cup�ration du formulaire.
		{
			if( is_array($array_auth_members) )
			{			
				//Ajout des autorisations au tableau final.
				foreach($array_auth_members as $key => $value)
				{
					if( isset($array_auth_all['m' . $value]) )
						$array_auth_all['m' . $value] += $bit_value;
					else
						$array_auth_all['m' . $value] = $bit_value;
				}
			}
		}
	}
	
	 //Ajoute un droit � l'ensemble des autorisations.
	/*static*/ function add_auth_group($auth_group, $add_auth)
	{
		return ((int)$auth_group | (int)$add_auth);
	}
	
	//Retire un droit � l'ensemble des autorisations
	/*static*/ function remove_auth_group($auth_group, $remove_auth)
	{
		$remove_auth = ~((int)$remove_auth);
		return ((int)$auth_group & $remove_auth);
	}
}

?>
