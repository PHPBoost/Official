<?php
/*##################################################
*                             parse.class.php
*                            -------------------
*   begin                : November 29, 2007
*   copyright          : (C) 2007 R�gis Viarre, Benoit Sautel
*   email                : crowkait@phpboost.com, ben.popeye@phpboost.com
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

//Classe d'interpr�tation du BBCode
class Parse
{
####### Private #######
	//Editeurs texte support�s.	
	var $editors = array('bbcode', 'tinymce');
	var $user_editor = 'bbcode'; //Editeur texte du membre.
	var $balise = array('b', 'i', 'u', 's',	'title', 'stitle', 'style', 'url', 
	'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
	'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym'); //Balises support�es.
	var $content = '';
	var $array_code = array();
	
	//Pr�paration avant le parsage, avec l'�diteur WYSIWYG.
	function preparse_tinymce()
	{
		$this->content = str_replace(array('&nbsp;&nbsp;&nbsp;', '&gt;', '&lt;', '<br />', '<br>'), array("\t", '&amp;gt;', '&amp;lt;', "\r\n", "\r\n"), $this->content); //Permet de poster de l'html.
		$this->content = html_entity_decode($this->content); //On remplace toutes les entit�es html.

		//Balise size
		$this->content = preg_replace_callback('`<font size="([0-9]+)">(.+)</font>`isU', create_function('$size', 'return \'[size=\' . (6 + (2*$size[1])) . \']\' . $size[2] . \'[/size]\';'), $this->content);
		//Balise image
		$this->content = preg_replace_callback('`<img src="([^"]+)"(?: border="[^"]*")? alt="[^"]*"(?: hspace="[^"]*")?(?: vspace="[^"]*")?(?: width="[^"]*")?(?: height="[^"]*")?(?: align="(top|middle|bottom)")? />`is', create_function('$img', '$align = \'\'; if( !empty($img[2]) ) $align = \'=\' . $img[2]; return \'[img\' . $align . \']\' . $img[1] . \'[/img]\';'), $this->content);

		$array_preg = array(
				'`<strong>(.+)</strong>`isU',
		'`<em>(.+)</em>`isU',
		'`<u>(.+)</u>`isU',
		'`<strike>(.+)</strike>`isU',
		'`<a href="([^"]+)">(.+)</a>`isU',
		'`<sub>(.+)</sub>`isU',
		'`<sup>(.+)</sup>`isU',
		'`<font color="([^"]+)">(.+)</font>`isU',
		'`<font style="background-color: ([^"]+)">(.+)</font>`isU',
		'`<span style="background-color: ([^"]+)">(.+)</span>`isU',
		'`<p style="background-color: ([^"]+)">(.+)</p>`isU',
		'`<font face="([^"]+)">(.+)</font>`isU',
		'`<p align="([a-z]+)">(.+)</p>`isU',
		'`<div style="text-align: ([a-z]+)">(.+)</div>`isU',
		'`<a(?: class="[^"]+")?(?: title="[^"]+" )? name="([^"]+)">(.*)</a>`isU',
		'`<blockquote>(.+)</blockquote>`isU',
		'`<ul>(.+)</ul>`isU',
		'`<ol>(.+)</ol>`isU',
		'`<li>(.+)</li>`isU',
		'`</?font([^>]+)>`i',
		'`<h1>(.+)</h1>`isU',
		'`<h2>(.+)</h2>`isU',
		'`<h3>(.+)</h3>`isU',
		'`<h4>(.+)</h4>`isU',
		'`<h5>(.+)</h5>`isU',
		'`<h6>(.+)</h6>`isU',
		'`<td( colspan="[^"]+")?( rowspan="[^"]+")?>`is',
		'`<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="([^"]+)%?" height="([^"]+)%?"><param name="movie" value="([^"]+)"(.*)</object>`isU',
		'`<span[^>]*>`i',
		'`<p[^r>]*>`i'
						   );
		$array_preg_replace = array(
				'[b]$1[/b]',
		'[i]$1[/i]',
		'[u]$1[/u]',
		'[s]$1[/s]',
		'[url=$1]$2[/url]',
		'[sub]$1[/sub]',
		'[sup]$1[/sup]',
		'[color=$1]$2[/color]',
		'[bgcolor=$1]$2[/bgcolor]',
		'[bgcolor=$1]$2[/bgcolor]',
		'[bgcolor=$1]$2[/bgcolor]',
		'[font=$1]$2[/font]',
		'[align=$1]$2[/align]',
		'[align=$1]$2[/align]',
		'[anchor=$1]$2[/anchor]',
		'[indent]$1[/indent]',
		'[list]$1[/list]',
		'[list=ordered]$1[/list]',
		'[*]$1',
		'',
		'[title=1]$1[/title]',
		'[title=2]$1[/title]',
		'[stitle=1]$1[/stitle]',
		'[stitle=2]$1[/stitle]',
		'[size=10]$1[/size]',
		'[size=8]$1[/size]',
		'[col$1$2]',
		'[swf=$1,$2]$3[/swf]',
		'',
		''
								   );
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);	

		//Pr�parse de la balise table.
		$this->content = preg_replace_callback('`<table(?: border="[^"]+")?(?: cellspacing="[^"]+")?(?: cellpadding="[^"]+")?(?: height="[^"]+")?(?: width="([^"]+)")?(?: align="[^"]+")?(?: summary="[^"]+")?(?: style="([^"]+)")?[^>]*>`i', array('Parse', 'parse_tinymce_table'), $this->content);
		
		$array_str = array( 
				'</span>', '<address>', '</address>', '<pre>', '</pre>', '<blockquote>', '</blockquote>', '</p>',
		'<caption>', '</caption>', '<tbody>', '</tbody>', '<tr>', '</tr>', '</td>', '</table>', '&lt;', '&gt;', 
						  );
		$array_str_replace = array( 
				'', '', '', '[pre]', '[/pre]', '[indent]', '[/indent]', "\r\n\r\n",
		'[row][head]', '[/head][/row]', '', '', '[row]', '[/row]', '[/col]', '[/table]', '<', '>', 
								  );		
		$this->content = str_replace($array_str, $array_str_replace, $this->content);
	}

	//Parse la balise table de tinymce pour le bbcode.
	function parse_tinymce_table($matches)
	{
		$prop = ''; 
		$matches[2] = !empty($matches[2]) ? str_replace('\'', '', $matches[2]) : '';
		if( !empty($matches[1]) && empty($matches[2]) ) 
			$prop .= ' style="width:' . $matches[1] . 'px"';
		if( empty($matches[1]) && !empty($matches[2]) ) 
			$prop .= ' style="' . $matches[2] . '"';
		if( !empty($matches[1]) && !empty($matches[2]) ) 
			$prop .= ' style="width:' . $matches[1] . 'px;' . $matches[2] . '"';
			
		return '[table' . $prop . ']';
	}
	
	//Fonction pour �clater la cha�ne selon les tableaux (gestion de l'imbrication infinie)
	function split_imbricated_tag(&$content, $tag, $attributes)
	{
		$content = $this->preg_split_safe_recurse($content, $tag, $attributes);
		//1 �l�ment repr�sente les inter tag, un les attributs tag et l'autre le contenu
		$nbr_occur = count($content);
		for($i = 0; $i < $nbr_occur; $i++)
		{
			//C'est le contenu d'un tag, il contient un sous tag donc on �clate
			if( ($i % 3) === 2 && preg_match('`\['.$tag.'(?:'.$attributes.')?\].+\[/'.$tag.'\]`s', $content[$i]) ) 
				$this->split_imbricated_tag($content[$i], $tag, $attributes);
		}
	}
	
	//Fonction d'�clatement de cha�ne supportant l'imbrication de tags
	function preg_split_safe_recurse($content, $tag, $attributes)
	{
   		// D�finitions des index de position de d�but des Tags valides
		$indexTags = $this->indexTags($content, $tag, $attributes);
		$size = count($indexTags);
		$parsed = array();
 
   		// Stockage de la cha�ne avant le premier tag dans le cas ou il y a au moins une balise ouvrante
		if ($size >= 1)
			array_push($parsed, substr($content, 0, $indexTags[0]));
		else
			array_push($parsed, $content);
 	
		for ($i = 0; $i < $size; $i++)
		{
			$currentIndex = $indexTags[$i];
			// Calcul de la sous-cha�ne pour l'expression r�guli�re
			if ( $i == ($size - 1))
				$subStr = substr($content, $currentIndex); 
			else
				$subStr = substr($content, $currentIndex, $indexTags[$i + 1] - $currentIndex);
	
			// Mise en place de l'�clatement de la sous-chaine
			$mask = '`\['.$tag.'('.$attributes.')?\](.+)\[/'.$tag.'\](.+)?`s';
			$localParsed = preg_split($mask, $subStr, -1, PREG_SPLIT_DELIM_CAPTURE);
	
			// Remplissage des r�sultats
			array_push($parsed, $localParsed[1]);	// attributs du tag
			array_push($parsed, $localParsed[2]);	// contenu du tag
	
			// Chaine apr�s le tag
			if ( $i < ($size - 1))
			{
				// On prend la chaine apr�s le tag de fermeture courant jusqu'au prochain tag d'ouverture
				$currentTagLen = strlen('['.$tag.$localParsed[1].']'.$localParsed[2].'[/'.$tag.']');
				$endPos = $indexTags[$i + 1] - ($currentIndex + $currentTagLen);
				array_push($parsed, substr($localParsed[3], 0, $endPos ));
			}
			else	// c'est la fin, il n'y a pas d'autre tag ouvrant apr�s
				array_push($parsed, $localParsed[3]); 
		}
		return $parsed;
	}
	
	//Fonction de d�tection du positionnement des balises imbriqu�es
	function indexTags ($content, $tag, $attributes)
	{
		$pos = -1;
		$nbOpenTags = 0;
		$tagsPos = Array();
 
		while( ($pos = strpos($content, '['.$tag, $pos + 1)) !== false )
		{
			// nombre de tag de fermeture d�j� rencontr�
			$nbCloseTags = substr_count(substr($content, 0, ($pos + strlen('['.$tag))), '[/'.$tag.']');
 
			// Si on trouve un tag d'ouverture, on sauvegarde sa position uniquement si
			// il y a autant + 1 de tags ferm�s avant et on it�re sur le suivant
			if ($nbOpenTags == $nbCloseTags)
			{
				$openTag = substr($content, $pos, (strpos($content, ']', $pos + 1) + 1 - $pos));
				$match = preg_match('`\['.$tag.'('.$attributes.')?\]`', $openTag);
				if ($match == 1)
					$tagsPos[count($tagsPos)] = $pos; 
			}
			$nbOpenTags++;
		}
		return $tagsPos;
	}
	
	
	//Remplacement recursif des balises imbriqu�es.
	function parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = substr_count($this->content, $match);
		for($i = 0; $i <= $nbr_match; $i++)
			$this->content = preg_replace($regex, $replace, $this->content); 
	}

	//Fonction qui parse les tableaux dans l'ordre inverse � l'ordre hi�rarchique
	function parse_imbricated_table(&$content)
	{
		if( is_array($content) )
		{
			$string_content = '';
			$nbr_occur = count($content);
			for($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'un tableau on le parse
				if( $i % 3 === 2 )
				{
					//On parse d'abord les sous tableaux �ventuels
					$this->parse_imbricated_table($content[$i]);
					//On parse le tableau concern� (il doit commencer par [row] puis [col] ou [head] et se fermer pareil moyennant espaces et retours � la ligne sinon il n'est pas valide)
					if( preg_match('`^(?:\s|<br />)*\[row\](?:\s|<br />)*\[(?:col|head)(?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?\].*\[/(?:col|head)\](?:\s|<br />)*\[/row\](?:\s|<br />)*$`sU', $content[$i]) )
					{						
						//On nettoie les caract�res �ventuels (espaces ou retours � la ligne) entre les diff�rentes cellules du tableau pour �viter les erreurs xhtml
						$content[$i] = preg_replace_callback('`^(\s|<br />)+\[row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+$`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+\[row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[/row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[/row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						//Parsage de row, col et head
						$content[$i] = preg_replace('`\[row\](.*)\[/row\]`sU', '<tr class="bb_table_row">$1</tr>', $content[$i]);
						$content[$i] = preg_replace('`\[col((?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/col\]`sU', '<td class="bb_table_col"$1>$2</td>', $content[$i]);
						$content[$i] = preg_replace('`\[head((?: colspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/head\]`sU', '<th class="bb_table_head"$1>$2</th>', $content[$i]);
						//parsage r�ussi (tableau valide), on rajoute le tableau devant
						$content[$i] = '<table class="bb_table"' . $content[$i - 1] . '>' . $content[$i] . '</table>';

					}
					else
					{
						//le tableau n'est pas valide, on met des balises temporaires afin qu'elles ne soient pas pars�es au niveau inf�rieur
						$content[$i] = str_replace(array('[col', '[row', '[/col', '[/row', '[head', '[/head'), array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), $content[$i]);
						$content[$i] = '[table' . $content[$i - 1] . ']' . $content[$i] . '[/table]';
					}
				}
				//On concat�ne la cha�ne finale si ce n'est pas le style du tableau
				if( $i % 3 !== 1 )
					$string_content .= $content[$i];
			}
			$content = $string_content;
		}
	}

	function parse_table()
	{
		//On supprime les �ventuels quote qui ont �t� transform�s en leur entit� html
		//$this->content = preg_replace_callback('`\[(?:table|col|row|head)(?: colspan=\\\&quot;[0-9]+\\\&quot;)?(?: rowspan=\\\&quot;[0-9]+\\\&quot;)?( style=\\\&quot;(?:[^&]+)\\\&quot;)?\]`U', create_function('$matches', 'return str_replace(\'\\\&quot;\', \'"\', $matches[0]);'), $this->content);
		$this->split_imbricated_tag($this->content, 'table', ' style="[^"]+"');
		$this->parse_imbricated_table($this->content);
		//On remet les tableaux invalides tels qu'ils �taient avant
		$this->content = str_replace(array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), array('[col', '[row', '[/col', '[/row', '[head', '[/head'), $this->content);
	}
	
	//Fonction qui parse les listes
	function parse_imbricated_list(&$content)
	{
		if( is_array($content) )
		{
			$string_content = '';
			$nbr_occur = count($content);
			for($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'une liste on le parse
				if( $i % 3 === 2 )
				{
					//On parse d'abord les sous listes �ventuelles
					if( is_array($content[$i]) )
						$this->parse_imbricated_list($content[$i]);
					
					if( strpos($content[$i], '[*]') !== false ) //Si il contient au moins deux �l�ments
					{				
						//Nettoyage des listes (retours � la ligne)
						$content[$i] = preg_replace_callback('`\[\*\]((?:\s|<br />)+)`', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						$content[$i] = preg_replace_callback('`((?:\s|<br />)+)\[\*\]`', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $content[$i]);
						if( substr($content[$i - 1], 0, 8) == '=ordered' )
						{
							$list_tag = 'ol';
							$content[$i - 1] = substr($content[$i - 1], 8);
						}
						else
						{
							$list_tag = 'ul';
						}
						$content[$i] = preg_replace_callback('`^((?:\s|<br />)*)\[\*\]`U', create_function('$var', 'return str_replace("<br />", "", str_replace("[*]", "<li class=\"bb_li\">", $var[0]));'), $content[$i]);
						$content[$i] = '<' . $list_tag . $content[$i - 1] . ' class="bb_' . $list_tag . '">' . str_replace('[*]', '</li><li class="bb_li">', $content[$i]) . '</li></' . $list_tag . '>';
					}
				}
				//On concat�ne la cha�ne finale si ce n'est pas le style ou le type de tableau
				if( $i % 3 !== 1 )
					$string_content .= $content[$i];
			}
			$content = $string_content;
		}
	}
	
	//Parse les listes imbriqu�es
	function parse_list()
	{
		//On nettoie les guillemets �chapp�s
		//$this->content = preg_replace_callback('`\[list(?:=(?:un)?ordered)?( style=\\\&quot;[^&]+\\\&quot;)?\]`U', create_function('$matches', 'return str_replace(\'\\\&quot;\', \'"\', $matches[0]);'), $this->content);
		//on travaille dessus
		if( preg_match('`\[list(=(?:un)?ordered)?( style="[^"]+")?\](\s|<br />)*\[\*\].*\[/list\]`s', $this->content) )
		{
			$this->split_imbricated_tag($this->content, 'list', '(?:=ordered)?(?: style="[^"]+")?');
			$this->parse_imbricated_list($this->content);
		}
	}
	
	//Fonction qui retire les portions de code pour ne pas y toucher
	function pick_up_code()
	{
		$split_code = $this->preg_split_safe_recurse($this->content, 'code', '=[a-zA-Z0-9_-]+');
		$num_codes = count($split_code);
		if( $num_codes > 1 )
		{
			$this->content = '';
			$id_code = 0;
			for( $i = 0; $i < $num_codes; $i++ )
			{
				//contenu
				if( $i % 3 == 0 )
				{
					$this->content .= $split_code[$i];
					if( $i < $num_codes - 1 )
						$this->content .= '[CODE_TAG_' . $id_code++ . ']';
				}
				elseif( $i % 3 == 2 )
					$this->array_code[] = '[code' . $split_code[$i - 1] . ']' . strip_tags(htmlspecialchars($split_code[$i], ENT_NOQUOTES)) . '[/code]';
			}
		}
	}
		
	//Fonction qui r�implante les portions de code
	function reimplant_code()
	{
		$num_code = count($this->array_code);

		if( !empty($num_code) )
		{
			for( $i = 0; $i < $num_code; $i++ )
				$this->content = str_replace('[CODE_TAG_' . $i . ']', $this->array_code[$i], $this->content);
			$this->array_code = array();
		}
	}
	
	//Fonction de retour pour les tableaux
	function unparse_table()
	{
		//Preg_replace.
		$array_preg = array( 
			'`<table class="bb_table"([^>]*)>(.*)</table>`sU',
			'`<tr class="bb_table_row">(.*)</tr>`sU',
			'`<th class="bb_table_head"([^>]*)>(.*)</th>`sU',
			'`<td class="bb_table_col"([^>]*)>(.*)</td>`sU'
		);
		$array_preg_replace = array( 
			'[table$1]$2[/table]',
			'[row]$1[/row]',
			'[head$1]$2[/head]',
			'[col$1]$2[/col]'
		);	
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
	}

	//Fonction de retour pour les listes
	function unparse_list(&$content)
	{
		while( preg_match('`<(?:u|o)l( style="[^"]+")? class="bb_(?:u|o)l">(.+)</(?:u|o)l>`sU', $this->content) )
		{
			$this->content = preg_replace('`<ul( style="[^"]+")? class="bb_ul">(.+)</ul>`sU', '[list$1]$2[/list]', $this->content);
			$this->content = preg_replace('`<ol( style="[^"]+")? class="bb_ol">(.+)</ol>`sU', '[list=ordered$1]$2[/list]', $this->content);
			$this->content = preg_replace('`<li class="bb_li">(.+)</li>`isU', '[*]$1', $this->content);
		}
	}
	
######## Public #######
	//On v�rifie que le r�pertoire cache existe et est inscriptible
	function Parse($text, $user_editor = false)
	{
		global $session;
		if( $user_editor !== false )
		{
			$this->user_editor = in_array($user_editor, $this->editors) ? $user_editor : 'bbcode';
		}
		else
		{
			$session->data['user_editor'];
		}
		$this->load_content($text);
	}

	//Fonction qui renvoie le contenu trait�
	function get_content()
	{
		//return addslashes($this->content);
		return $this->content;
	}
	
	//Fonction de chargement de texte
	function load_content($content)
	{
		$this->content = trim((MAGIC_QUOTES == false ? $content : stripslashes($content)));
	}
	
	//On parse le contenu: bbcode => xhtml.
	function parse_content($forbidden_tags = array(), $html_protect = true)
	{
		global $LANG;
		
		//On supprime d'abord toutes les occurences de balises CODE que nous r�injecterons � la fin pour ne pas y toucher
		$this->pick_up_code();

		//Ajout des espaces pour �viter l'absence de parsage lorsqu'un s�parateur de mot est �xig�. Suppression des backslash ajout�s par magic_quotes_gpc.
		$this->content = ' ' . $this->content . ' ';
		
		if( $this->user_editor == 'tinymce' ) //Pr�parse pour tinymce.
		{
			$this->preparse_tinymce($this->content);
		}

		//Protection : suppression du code html
		if( $html_protect )
		{
			$this->content = htmlspecialchars($this->content, ENT_NOQUOTES);
			$this->content = strip_tags($this->content);
		}
		$this->content = preg_replace('`&amp;((?:#[0-9]{2,4})|(?:[a-z0-9]{2,6}));`i', "&$1;", $this->content);
		
		//Smilies
		@include('../cache/smileys.php');
		if( !empty($_array_smiley_code) )
		{	
			//Cr�ation du tableau de remplacement.
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_code[] = '`(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . str_replace('\'', '\\\\\\\'', preg_quote($code)) . ')`';
				$smiley_img_url[] = '<img src="../images/smileys/' . $img . '" alt="' . addslashes($code) . '" class="smiley" />';
			}
			$this->content = preg_replace($smiley_code, $smiley_img_url, $this->content);
		}
		
		//Remplacement des caract�res de word.
		$array_str = array( 
			'�', '�', '�', '�', '�', '�', '�', '�', '�',
			'�', '�', '�', '�', '�', '�', '�', '�', '�',
			'�', '�',  '�', '�', '�', '�', '�', '�', '�'
		);
		$array_str_replace = array( 
			'&#8364;', '&#8218;', '&#402;', '&#8222;', '&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;',
			'&#352;', '&#8249;', '&#338;', '&#381;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;',
			'&#8211;', '&#8212;', '&#732;', '&#8482;', '&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
		);
		
		$this->content = str_replace($array_str, $array_str_replace, $this->content);
		
		//Preg_replace.
		$array_preg = array( 
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'sup' => '`\[sup\](.+)\[/sup\]`isU',
			'sub' => '`\[sub\](.+)\[/sub\]`isU',
			'img' => '`\[img(?:=(top|middle|bottom))?\]((?:(?:\.?\./)+|(?:https?|ftps?)+://([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_-]+\.(?:jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
			'color' => '`\[color=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/color\]`isU',
			'bgcolor' => '`\[bgcolor=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/bgcolor\]`isU',
			'size' => '`\[size=([1-9]|(?:[1-4][0-9]))\](.+)\[/size\]`isU',
			'font' => '`\[font=(arial|times|courier(?: new)?|impact|geneva|optima)\](.+)\[/font\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'align' => '`\[align=(left|center|right|justify)\](.+)\[/align\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'anchor' => '`\[anchor=([a-z_][a-z0-9_]*)\](.*)\[/anchor\]`isU',
			'acronym' => '`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
			'title' => '`\[title=([1-2])\](.+)\[/title\]`iU',
			'stitle' => '`\[stitle=([1-2])\](.+)\[/stitle\]`iU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]((?:(\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+)\[/swf\]`iU',
			'movie' => '`\[movie=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]([^\n\r\t\f]+)\[/movie\]`iU',
			'sound' => '`\[sound\]((?:(?:\.?\./)+|(?:https?|ftps?)+://([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4})+(?:[a-z0-9~_-]+/)*[a-z0-9_-]+\.mp3)\[/sound\]`iU',
			'url' => '`\[url\]((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)\[/url\]`isU',
			'url2' => '`\[url\]((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)\[/url\]`isU',
			'url3' => '`\[url=((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)\]([^\n\r\t\f]+)\[/url\]`isU',
			'url4' => '`\[url=((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)\]([^\n\r\t\f]+)\[/url\]`iU',
			'url5' => '`(\s)+((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)(\s)+`isU', 
			'url6' => '`(\s)+((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4}/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=%@&;,-]*)(\s)+`i',
			'mail' => '`(\s)+([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(\s)+`i',
			'mail2' => '`\url=mailto:([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})\]([^\n\r\t\f]+)\[\url\]`i'
		);
		$array_preg_replace = array( 
			'b' => "<strong>$1</strong>",
			'i' => "<em>$1</em>",
			'u' => "<span style=\"text-decoration: underline;\">$1</span>",
			's' => "<strike>$1</strike>",		
			'sup' => '<sup>$1</sup>',
			'sub' => '<sub>$1</sub>',
			'img' => "<img src=\"$2\" alt=\"\" class=\"valign_$1\" />",
			'color' => "<span style=\"color:$1;\">$2</span>",
			'bgcolor' => "<span style=\"background-color:$1;\">$2</span>",
			'size' => "<span style=\"font-size: $1px;\">$2</span>",
			'font' => "<span style=\"font-family: $1;\">$2</span>",
			'pre' => "<pre>$1</pre>",
			'align' => "<p style=\"text-align:$1\">$2</p>",
			'float' => "<p class=\"float_$1\">$2</p>",	
			'anchor' => "<span id=\"$1\">$2</span>",
			'acronym' => "<acronym title=\"$1\" class=\"bb_acronym\">$2</acronym>",
			'title' => "<h3 class=\"title$1\">$2</h3>",
			'stitle' => "<br /><h4 class=\"stitle$1\">$2</h4><br />",
			'style' => "<span class=\"$1\">$2</span>",
			'swf' => "<object type=\"application/x-shockwave-flash\" data=\"$3\" width=\"$1\" height=\"$2\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"$3\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#000000\" />
		</object>",
			'movie' => "<object type=\"application/x-shockwave-flash\" data=\"../includes/data/movieplayer.swf?movie=$3\" width=\"$1\" height=\"$2\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"$1\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#FFFFFF\" />
		</object>",
			'sound' => "<object type=\"application/x-shockwave-flash\" data=\"../includes/data/dewplayer.swf?son=$1\" width=\"200\" height=\"20\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"../includes/data/dewplayer.swf?son=$1\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#FFFFFF\" />
		</object>",
			'url' => "<a href=\"$1\">$1</a>",
			'url2' => "<a href=\"http://$1\">$1</a>",
			'url3' => "<a href=\"$1\">$2</a>",
			'url4' => "<a href=\"http://$1\">$2</a>",
			'url5' => "$1<a href=\"$2\">$2</a>$3", 
			'url6' => "$1<a href=\"http://$2\">$2</a>$3",
			'mail' => "$1<a href=\"mailto:$2\">$2</a>$3"
		);

		//Suppression des remplacements des balises interdites.
		if( !empty($forbidden_tags) )
		{
			//Si on interdit les liens, on ajoute toutes les mani�res par lesquelles elles peuvent passer
			if( in_array('url', $forbidden_tags) )
			{
				$forbidden_tags[] = 'url2';
				$forbidden_tags[] = 'url3';
				$forbidden_tags[] = 'url4';
				$forbidden_tags[] = 'url5';
				$forbidden_tags[] = 'url6';
			}
			if( in_array('mail', $forbidden_tags) )
			{
				$forbidden_tags[] = 'mail2';
			}
			
			$other_tags = array('table', 'code', 'math', 'quote', 'hide', 'indent', 'list'); 
			foreach($forbidden_tags as $key => $tag)
			{	
				if( in_array($tag, $other_tags) )
				{
					$array_preg[$tag] = '`\[' . $tag . '.*\](.+)\[/' . $tag . '\]`isU';
					$array_preg_replace[$tag] = "$1";
				}
				else
				{	
					unset($array_preg[$tag]);
					unset($array_preg_replace[$tag]);
				}
			}	
		}
		
		//Remplacement : on parse les balises classiques
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
		
		//Interpr�tation des sauts de ligne
		$this->content = nl2br($this->content);
		
		//Tableaux
		if( strpos($this->content, '[table') !== false )
		{
			$this->parse_table();
		}
		
		//Listes
		if( strpos($this->content, '[list') !== false )
		{
			$this->parse_list();
		}
		##### //Fonction de parsage des balises imbriqu�es g�n�rique � faire #####
		//Parsage des balises imbriqu�es.	
		$this->parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $this->content);
		$this->parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $this->content);
		$this->parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $this->content);
		$this->parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $this->content);
		
		//On r�ins�re les fragments de code qui ont �t� pr�velev�s pour ne pas les consid�rer
		$this->reimplant_code();
	}

	//On unparse le contenu xHTML => BBCode
	function unparse_content()
	{
		//Smiley.
		@include('../cache/smileys.php');
		if(!empty($_array_smiley_code) )
		{
			//Cr�ation du tableau de remplacement
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_img_url[] = '`<img src="../images/smileys/' . preg_quote($img) . '(.*) />`sU';
				$smiley_code[] = $code;
			}	
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
		}
			
		if( $this->user_editor == 'tinymce' && $editor_unparse ) //Pr�parse pour tinymce.
		{
			//Remplacement des caract�res de word
			$array_str = array( 
				"\t", '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '�', '�', '�',
				'�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
				'�', '�', '�', '�', '�', '�', '�',  '�', '�', '�',
				'�', '�', '�', '�', '<li class="bb_li">', '</table>', '<tr class="bb_table_row">', '</th>'
			);
			$array_str_replace = array( 
				'&nbsp;&nbsp;&nbsp;', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
				'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
				'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
				'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;', '<li>', '</tbody></table>', '<tr>', '</caption>'
			);	
			$this->content = str_replace($array_str, $array_str_replace, $this->content);
			
			//Remplacement des balises imbriqu�es.	
			$this->parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $this->content);
			$this->parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->content);
			$this->parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '<blockquote>$1</blockquote>', $this->content);
			
			//Balise size
			$this->content = preg_replace_callback('`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU', create_function('$size', 'if( $size[1] >= 36 ) $fontsize = 7;	elseif( $size[1] <= 12 ) $fontsize = 1;	else $fontsize = min(($size[1] - 6)/2, 7); return \'<font size="\' . $fontsize . \'">\' . $size[2] . \'</font>\';'), $this->content);
		
			//Preg_replace.
			$array_preg = array( 
				'`<img src="[^"]+" alt="[^"]*" class="smiley" />`i',
				'`<img src="([^"]+)" alt="" class="valign_([^"]+)?" />`i',
				'`<table class="bb_table"( style="([^"]+)")?>`i', 
				'`<td class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',
				'`<th class="bb_table_head"[^>]?>`i',
				'`<span style="color:(.*);">(.*)</span>`isU',
				'`<span style="background-color:(.*);">(.*)</span>`isU',
				'`<span style="text-decoration: underline;">(.*)</span>`isU',
				'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
				'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
				'`<p class="float_(left|right)">(.*)</p>`isU',
				'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
				'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
				'`<ul( style="[^"]+")? class="bb_ul">`i',
				'`<ol( style="[^"]+")? class="bb_ol">`i',
				'`<h3 class="title1">(.*)</h3>`isU',
				'`<h3 class="title2">(.*)</h3>`isU',
				'`<h4 class="stitle1">(.*)</h4>`isU',
				'`<h4 class="stitle2">(.*)</h4>`isU',
				'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
				'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
			);
			$array_preg_replace = array( 
				"$1",
				"<img src=\"$1\" alt=\"\" align=\"$2\" />",
				"<table border=\"0\"$1><tbody>",
				"<td$1$2$3>", 
				"<caption>", 
				"<font color=\"$1\">$2</font>",
				"<span style=\"background-color: $1\">$2</font>",
				"<u>$1</u>",	
				"<font color=\"$1\">$2</font>",
				"<p align=\"$1\">$2</p>",
				"[float=$1]$2[/float]",
				"<a title=\"$1\" name=\"$1\">$2</a>",
				"[acronym=$1]$2[/acronym]",
				"<ul>",
				"<ol>",
				"<h1>$1</h1>",
				"<h2>$1</h2>",
				"<h3>$1</h3>",
				"<h4>$1</h4>",
				"[style=$1]$2[/style]",
				"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>",
				"[sound]$1[/sound]",
				"[movie=$2,$3]$1[/movie]"
			);	
			$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
			
			$this->content = htmlentities($this->content);
		}
		else
		{		
			//Remplacement des balises imbriqu�es.	
			$this->parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $this->content);
			$this->parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->content);
			$this->parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '[indent]$1[/indent]', $this->content);
				
			//Str_replace.
			$array_str = array( 
				'<br />', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
				'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
				'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
				'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
			);	
			$array_str_replace = array( 
				'', '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '�', '�', '�',
				'�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
				'�', '�', '�', '�', '�', '�', '�',  '�', '�', '�',
				'�', '�', '�', '�'
			);	
			$this->content = str_replace($array_str, $array_str_replace, $this->content);

			//Preg_replace.
			$array_preg = array( 
				'`<img src="([^?\n\r\t].*)" alt="[^"]*"(?: class="[^"]+")? />`iU',
				'`<span style="color:([^;]+);">(.*)</span>`isU',
				'`<span style="background-color:([^;]+);">(.*)</span>`isU',
				'`<span style="text-decoration: underline;">(.*)</span>`isU',
				'`<sup>(.+)</sup>`isU',
				'`<sub>(.+)</sub>`isU',
				'`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU',
				'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
				'`<pre>(.*)</pre>`isU',
				'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
				'`<p class="float_(left|right)">(.*)</p>`isU',
				'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
				'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
				'`<a href="mailto:(.*)">(.*)</a>`isU',
				'`<a href="([^"]+)">(.*)</a>`isU',
				'`<h3 class="title([1-2]+)">(.*)</h3>`isU',
				'`<h4 class="stitle([1-2]+)">(.*)</h4>`isU',
				'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
			);
			$array_preg_replace = array( 
				"[img]$1[/img]",
				"[color=$1]$2[/color]",
				"[bgcolor=$1]$2[/bgcolor]",
				"[u]$1[/u]",	
				"[sup]$1[/sup]",
				"[sub]$1[/sub]",
				"[size=$1]$2[/size]",
				"[font=$1]$2[/font]",
				"[pre]$1[/pre]",
				"[align=$1]$2[/align]",
				"[float=$1]$2[/float]",
				"[anchor=$1]$2[/anchor]",
				"[acronym=$1]$2[/acronym]",
				"$1",
				"[url=$1]$2[/url]",
				"[title=$1]$2[/title]",
				"[stitle=$1]$2[/stitle]",
				"[style=$1]$2[/style]",
				"[sound]$1[/sound]",
				"[movie=$2,$3]$1[/movie]",
				"[swf=$2,$3]$1[/swf]"
			);	
			$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

			//Unparsage de la balise table.
			if( strpos($this->content, '<table') !== false )
				$this->unparse_table($this->content);

			//Unparsage de la balise table.
			if( strpos($this->content, '<li') !== false )
				$this->unparse_list($this->content);
		}
		$this->contents = addslashes($contents);
	}
}

?>