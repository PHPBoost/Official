<?php

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../pages/pages_begin.php');
require_once('../kernel/header_no_display.php');

$id_cat = retrieve(POST, 'id_cat', 0);
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = retrieve(POST, 'selected_cat', 0);
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = retrieve(POST, 'open_cat', 0);
$root = !empty($_GET['root']) ? 1 : 0;

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

//Chargement d'un fichier template pour conna�tre l'emplacement du template
$Template = new FileTemplate('pages/page.tpl');
$pages_data_path = $Template->get_pictures_data_path();

//Listage des r�pertoires dont le r�pertoire parent est connu
if ($id_cat != 0)
{	
	echo '<ul>';
	//On s�lectionne les r�petoires dont l'id parent est connu
	$result = $Sql->query_while("SELECT c.id, p.title, p.encoded_title, p.auth
	FROM " . PREFIX . "pages_cats c
	LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
	WHERE c.id_parent = " . $id_cat . "
	ORDER BY title ASC", __LINE__, __FILE__);
	$nbr_subcats = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $id_cat. "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Autorisation particuli�re ?
		$special_auth = !empty($row['auth']);
		//V�rification de l'autorisation d'�diter la page
		if (($special_auth && $User->check_auth($row['auth'], READ_PAGE)) || (!$special_auth && $User->check_auth($config_authorizations, READ_PAGE)))
		{
			//On compte le nombre de cat�gories pr�sentes pour savoir si on donne la possibilit� de faire un sous dossier
			$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			//Si cette cat�gorie contient des sous cat�gories, on propose de voir son contenu
			if ($sub_cats_number > 0)
				echo '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $pages_data_path . '/images/plus.png" alt="" id="img2_' . $row['id'] . '" /><span class="icon-folder-close" id="img_' . $row['id'] . '"></span></a><a id="class_' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></span></li>';
			else //Sinon on n'affiche pas le "+"
				echo '<li class="sub"><a id="class_' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');"><span class="icon-folder-close"></span>' . $row['title'] . '</a></li>';
		}
	}
	$Sql->query_close($result);
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif ($select_cat && empty($open_cat) && $root == 0)
{
	if ($selected_cat > 0)
	{
		$localisation = array();
		$Cache->load('pages');
		$id = $selected_cat; //Premier id
		do
		{
			$localisation[] = $_PAGES_CATS[$id]['name'];
			$id = (int)$_PAGES_CATS[$id]['id_parent'];
		}	
		while ($id > 0);
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('pages');
		echo $LANG['pages_no_selected_cat'];
	}
}
elseif (!empty($open_cat) || $root == 1)
{
	$open_cat = $root == 1 ? 0 : $open_cat;
	$return = '<ul>';
	//Liste des cat�gories dans cette cat�gorie
	foreach ($_PAGES_CATS as $key => $value)
	{
		if ($value['id_parent'] == $open_cat)
		{
			//Autorisation particuli�re ?
			$special_auth = !empty($value['auth']);
			//V�rification de l'autorisation d'�diter la page
			if (($special_auth && $User->check_auth($value['auth'], READ_PAGE)) || (!$special_auth && $User->check_auth($config_authorizations, READ_PAGE)))
			{
				$return .= '<li><a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);"><span class="icon-folder-close"></span>' . $value['name'] . '</a></li>';
			}
		}
	}
	$result = $Sql->query_while("SELECT title, id, encoded_title, auth
	FROM " . PREFIX . "pages
	WHERE id_cat = '" . $open_cat . "'
	ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Autorisation particuli�re ?
		$special_auth = !empty($row['auth']);
		//V�rification de l'autorisation d'�diter la page
		if (($special_auth && $User->check_auth(unserialize($row['auth']), READ_PAGE)) || (!$special_auth && $User->check_auth($config_authorizations, READ_PAGE)))
		{
			$return .= '<li><a href="' . PATH_TO_ROOT . url('/pages/pages.php?title=' . $row['encoded_title'], '/pages/' . $row['encoded_title']) . '"><span class="icon-file"></span>' . $row['title'] . '</a></li>';
		}
	}
	$Sql->query_close($result);
	$return .= '</ul>';
	echo $return;
}


require_once('../kernel/footer_no_display.php');
?>