<?php

include_once('../includes/begin.php'); 

define('TITLE', 'Importation de fichiers texte');

include_once('wiki_functions.php');
include_once('../includes/header.php');

define('CATEGORIE', 0);

$dir = 'fichiers';
$i = 0;
$array_title = array();
if( is_dir($dir) )
{
   if( $dh = opendir($dir) )
	{
		while(($file = readdir($dh)) !== false)
		{
			if( preg_match('`(.+)\.(?:txt|htm|html)$`', $file, $array_title) )					
			{
				$contents = wiki_parse(@file_get_contents($dir . '/' . $file));
				if( !empty($contents) )
				{
					$title = securit($array_title[1]);
					$sql->query_inject("INSERT INTO ".PREFIX."wiki_articles (title, encoded_title, id_cat, is_cat) VALUES ('" . $title . "', '" . url_encode_rewrite($title) . "', '" . CATEGORIE . "', '0')", __LINE__, __FILE__);
					//On r�cup�re le num�ro de l'article cr��
					$id_article = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."wiki_articles");
					//On ins�re le contenu
					$sql->query_inject("INSERT INTO ".PREFIX."wiki_contents (id_article, menu, content, activ, user_id, user_ip, timestamp) VALUES ('" . $id_article . "', '', '" . $contents . "', 1, " . $session->data['user_id'] . ", '" . USER_IP . "', " . time() . ")", __LINE__, __FILE__);
					//On met � jour le num�ro du contenu dans la table articles
					$id_contents = $sql->sql_insert_id("SELECT MAX(id_contents) FROM ".PREFIX."wiki_contents");
					$sql->query_inject("UPDATE ".PREFIX."wiki_articles SET id_contents = '" . $id_contents . "' WHERE id = " . $id_article, __LINE__, __FILE__);
					$i++;
				}
				else
					echo 'Le fichier ' . $file . ' est vide <br />';
			}
		}
	   closedir($dh);
		echo $i . ' fichiers ont �t� enregistr�s avec succ�s';
	}
}
else die('le dossier fichier n\'existe pas');

include_once('../includes/footer.php');

?>