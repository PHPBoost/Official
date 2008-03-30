<?php
header('Content-type: text/html; charset=iso-8859-15');

require_once('../includes/begin.php');
require_once('../pages/pages_begin.php');
define('TITLE', 'Utilisation d\'AJAX');
require_once('../includes/header_no_display.php');

$id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = !empty($_POST['selected_cat']) ? numeric($_POST['selected_cat']) : 0;
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = !empty($_POST['open_cat']) ? numeric($_POST['open_cat']) : 0;
$root = !empty($_GET['root']) ? 1 : 0;

//Chargement d'un fichier template pour connaître l'emplacement du template
include_once('../includes/template.class.php');
$Template = new Templates();
$Template->Set_filenames(array('pages' => '../templates/' . $CONFIG['theme'] . '/pages/pages.tpl'));

//Listage des répertoires dont le répertoire parent est connu
if( $id_cat != 0 )
{	
	echo '<ul style="margin:0;padding:0;list-style-type:none;padding-left:30px;">';
	//On sélectionne les répetoires dont l'id parent est connu
	$result = $Sql->Query_while("SELECT c.id, p.title, p.encoded_title, p.auth
	FROM ".PREFIX."pages_cats c
	LEFT JOIN ".PREFIX."pages p ON p.id = c.id_page
	WHERE c.id_parent = " . $id_cat . "
	ORDER BY title ASC", __LINE__, __FILE__);
	$nbr_subcats = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."pages_cats WHERE id_parent = '" . $id_cat. "'", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		//Vérification de l'autorisation d'éditer la page
		if( ($special_auth && $Member->Check_auth($row['auth'], READ_PAGE)) || (!$special_auth && $Member->Check_auth($_PAGES_CONFIG['auth'], READ_PAGE)) )
		{
			//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
			$sub_cats_number = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."pages_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			//Si cette catégorie contient des sous catégories, on propose de voir son contenu
			if( $sub_cats_number > 0 )
				echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('pages') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('pages') . '/images/closed_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
			else //Sinon on n'affiche pas le "+"
				echo '<li style="padding-left:17px;"><img src="' . $Template->Module_data_path('pages') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a></span></li>';
		}
	}
	$Sql->Close($result);
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif( $select_cat && empty($open_cat) && $root == 0 )
{
	if( $selected_cat > 0)
	{
		$localisation = array();
		$Cache->Load_file('pages');
		$id = $selected_cat; //Premier id
		do
		{
			$localisation[] = $_PAGES_CATS[$id]['name'];
			$id = (int)$_PAGES_CATS[$id]['id_parent'];
		}	
		while( $id > 0 );
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('pages');
		echo $LANG['pages_no_selected_cat'];
	}
}
elseif( !empty($open_cat) || $root == 1 )
{
	$open_cat = $root == 1 ? 0 : $open_cat;
	$return = '<table style="width:100%;">';
	//Liste des catégories dans cette catégorie
	foreach( $_PAGES_CATS as $key => $value )
	{
		if( $value['id_parent'] == $open_cat )
		{
			//Autorisation particulière ?
			$special_auth = !empty($value['auth']);
			//Vérification de l'autorisation d'éditer la page
			if( ($special_auth && $Member->Check_auth($value['auth'], READ_PAGE)) || (!$special_auth && $Member->Check_auth($_PAGES_CONFIG['auth'], READ_PAGE)) )
			{
				$return .= '<tr><td class="row2"><img src="' . $Template->Module_data_path('pages') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);">' . $value['name'] . '</a></td></tr>';
			}
		}
	}
	$result = $Sql->Query_while("SELECT title, id, encoded_title, auth
	FROM ".PREFIX."pages
	WHERE id_cat = '" . $open_cat . "'
	ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		//Vérification de l'autorisation d'éditer la page
		if( ($special_auth && $Member->Check_auth(unserialize($row['auth']), READ_PAGE)) || (!$special_auth && $Member->Check_auth($_PAGES_CONFIG['auth'], READ_PAGE)) )
		{
			$return .= '<tr><td class="row2"><img src="' . $Template->Module_data_path('pages') . '/images/page.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="' . transid('pages.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a></td></tr>';
		}
	}
	$Sql->Close($result);
	$return .= '</table>';
	echo $return;
}


require_once('../includes/footer_no_display.php');
?>