<?php
header('Content-type: text/html; charset=iso-8859-15');

include_once('../includes/begin.php');
define('TITLE', 'Ajax faq');
include_once('../includes/header_no_display.php');

if( $session->data['level'] === 2 ) //Admin
{			
	$cache->load_file('faq');

	$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;
	$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
	$get_parent_up = !empty($_GET['g_up']) ? numeric($_GET['g_up']) : 0;
	$get_parent_down = !empty($_GET['g_down']) ? numeric($_GET['g_down']) : 0;

	//R�cup�ration de la cat�gorie d'�change.
	if( !empty($get_parent_up) )
	{
		$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats WHERE '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if( !empty($switch_id_cat) )
			echo $switch_id_cat;
		else
		{	
			// Cat�gories parentes � supprimer.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id 
			FROM ".PREFIX."faq_cats 
			WHERE id_left < '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' AND id_right > '" . $FAQ_CATS[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$sql->close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			if( !empty($list_parent_cats) )
			{
				//Changement de cat�gorie.
				$change_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE id_left < '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' AND level = '" . ($FAQ_CATS[$get_parent_up]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$sql->sql_limit(0, 1), __LINE__, __FILE__);
				if( isset($FAQ_CATS[$change_cat]) )
				{	
					$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats 
					WHERE id_left > '" . $FAQ_CATS[$change_cat]['id_right'] . "'
					ORDER BY id_left" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
				if( !empty($switch_id_cat) )
					echo 's' . $switch_id_cat;
			}
		}	
	}
	elseif( !empty($get_parent_down) )
	{
		$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats WHERE id_left - '" . $FAQ_CATS[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
		if( !empty($switch_id_cat) )
			echo $switch_id_cat;
		else
		{	
			$change_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
			WHERE id_left > '" . $FAQ_CATS[$get_parent_down]['id_left'] . "' AND level = '" . ($FAQ_CATS[$get_parent_down]['level'] - 1) . "'
			ORDER BY id_left" . 
			$sql->sql_limit(0, 1), __LINE__, __FILE__);
			if( isset($FAQ_CATS[$change_cat]) )
			{	
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats 
				WHERE id_left < '" . $FAQ_CATS[$change_cat]['id_right'] . "'
				ORDER BY id_left DESC" . 
				$sql->sql_limit(0, 1), __LINE__, __FILE__);
			}
			if( !empty($switch_id_cat) )
				echo 's' . $switch_id_cat;
		}	
	}

	//D�placement.
	if( !empty($move) && !empty($id) )
	{
		//Si la cat�gorie existe, et d�placement possible
		if( array_key_exists($id, $FAQ_CATS) )
		{
			//Cat�gories parentes de celle � supprimer.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id 
			FROM ".PREFIX."faq_cats 
			WHERE id_left < '" . $FAQ_CATS[$id]['id_left'] . "' AND id_right > '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$sql->close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			$to = 0;
			if( $move == 'up' )
			{	
				//M�me cat�gorie
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE '" . $FAQ_CATS[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
				if( !empty($switch_id_cat) )
				{
					//On monte la cat�gorie � d�placer, on lui assigne des id n�gatifs pour assurer l'unicit�.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left + '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la cat�gorie cible.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$switch_id_cat]['id_left'] . "' AND '" . $FAQ_CATS[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On r�tablit les valeurs absolues.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
					
					$cache->generate_module_file('faq');
				}		
				elseif( !empty($list_parent_cats)  )
				{
					//Changement de cat�gorie.
					$to = $sql->query("SELECT id FROM ".PREFIX."faq_cats
					WHERE id_left < '" . $FAQ_CATS[$id]['id_left'] . "' AND level = '" . ($FAQ_CATS[$id]['level'] - 1) . "' AND
					id NOT IN (" . $list_parent_cats . ")
					ORDER BY id_left DESC" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
			}
			elseif( $move == 'down' )
			{
				//Doit-on changer de cat�gorie parente ou non ?
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE id_left - '" . $FAQ_CATS[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
				if( !empty($switch_id_cat) )
				{
					//On monte la cat�gorie � d�placer, on lui assigne des id n�gatifs pour assurer l'unicit�.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left - '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la cat�gorie cible.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left - '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$switch_id_cat]['id_left'] . "' AND '" . $FAQ_CATS[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On r�tablit les valeurs absolues.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
					
					$cache->generate_module_file('faq');
				}
				elseif( !empty($list_parent_cats)  )
				{
					//Changement de cat�gorie.
					$to = $sql->query("SELECT id FROM ".PREFIX."faq_cats
					WHERE id_left > '" . $FAQ_CATS[$id]['id_left'] . "' AND level = '" . ($FAQ_CATS[$id]['level'] - 1) . "'
					ORDER BY id_left" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
			}

			if( !empty($to) ) //Changement de cat�gorie possible?
			{
				//On v�rifie si la cat�gorie contient des sous cat�gories.
				$nbr_cat = (($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] - 1) / 2) + 1;
			
				//Sous cat�gories de la cat�gorie � supprimer.
				$list_cats = '';
				$result = $sql->query_while("SELECT id
				FROM ".PREFIX."faq_cats 
				WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'
				ORDER BY id_left", __LINE__, __FILE__);
				while( $row = $sql->sql_fetch_assoc($result) )
				{
					$list_cats .= $row['id'] . ', ';
				}
				$sql->close($result);
				$list_cats = trim($list_cats, ', ');
			
				//Pr�caution pour �viter erreur fatale, cas impossible si coh�rence de l'arbre respect�e.
				if( empty($list_cats) )
				{
					header('location:' . HOST . SCRIPT);
					exit;
				}
				
				//cat�gories parentes de la cat�gorie cible.
				$list_parent_cats_to = '';
				$result = $sql->query_while("SELECT id, level 
				FROM ".PREFIX."faq_cats 
				WHERE id_left <= '" . $FAQ_CATS[$to]['id_left'] . "' AND id_right >= '" . $FAQ_CATS[$to]['id_right'] . "'", __LINE__, __FILE__);
				while( $row = $sql->sql_fetch_assoc($result) )
				{
					$list_parent_cats_to .= $row['id'] . ', ';
				}
				$sql->close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
				if( empty($list_parent_cats_to) )
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
		
				########## Suppression ##########
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);					
				

				if( !empty($list_parent_cats) )
				{
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				}
				
				//On r�duit la taille de l'arbre du nombre de cat�gories supprim�es � partir de la position de celui-ci.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);

				########## Ajout ##########
				//On modifie les bornes droites des parents de la cible.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				//On augmente la taille de l'arbre du nombre de cat�gories supprim�es � partir de la position de la cat�gorie cible.
				if( $FAQ_CATS[$id]['id_left'] > $FAQ_CATS[$to]['id_left']  ) //Direction cat�gorie source -> cat�gorie cible.
				{	
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $FAQ_CATS[$to]['id_right'] . "'", __LINE__, __FILE__);						
					$limit = $FAQ_CATS[$to]['id_right'];
					$end = $limit + ($nbr_cat*2) - 1;
				}
				else
				{	
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($FAQ_CATS[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
					$limit = $FAQ_CATS[$to]['id_right'] - ($nbr_cat*2);
					$end = $limit + ($nbr_cat*2) - 1;						
				}	

				//On replace les cat�gories supprim�es virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($FAQ_CATS[$array_sub_cats[$z]]['id_left'] - $FAQ_CATS[$id]['id_left']);
					$id_right = $end - ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$array_sub_cats[$z]]['id_right']);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
				
				$cache->generate_module_file('faq');
			}
			
			//G�n�ration de la liste des cat�gories en cache.
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = $sql->query_while("SELECT id, id_left, id_right
			FROM ".PREFIX."faq_cats 
			ORDER BY id_left", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_cats_js .= $row['id'] . ', ';		
				$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";";
				$i++;
			}
			$sql->close($result);
			echo 'list_cats = new Array(' . trim($list_cats_js, ', ') . ');' . $array_js;
		}	
	}
}

?>