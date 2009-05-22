<?php
/*##################################################
*                          bbcode_parser.class.php
*                            -------------------
*   begin                : July 3 2008
*   copyright            : (C) 2008 Benoit Sautel
*   email                :  ben.popeye@phpboost.com
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

import('content/parser/content_parser');

/**
 * @package content
 * @subpackage parser
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc Converts the PHPBoost BBCode language to the XHTML language which is stocked in
 * the database and can be displayed nearly directly.
 * It parses only the authorized tags (defined in the parent class which is ContentParser).
 */
class BBCodeParser extends ContentParser
{
	/**
	 * @desc Builds a BBCodeParser object
	 */
	function BBCodeParser()
	{
		parent::ContentParser();
	}
	
	/**
	 * @desc Parses the parser content from BBCode to XHTML.
	 * @return void You will find the result by using the get_content method
	 */
	function parse()
	{
		global $User;
		
		//On supprime d'abord toutes les occurences de balises CODE que nous r�injecterons � la fin pour ne pas y toucher
		if (!in_array('code', $this->forbidden_tags))
		{
			$this->_pick_up_tag('code', '=[A-Za-z0-9#+-]+(?:,[01]){0,2}');
		}
		
		//On pr�l�ve tout le code HTML afin de ne pas l'alt�rer
		if (!in_array('html', $this->forbidden_tags) && $User->check_auth($this->html_auth, 1))
		{
			$this->_pick_up_tag('html');
		}
		
		//Ajout des espaces pour �viter l'absence de parsage lorsqu'un s�parateur de mot est �xig�
		$this->content = ' ' . $this->content . ' ';
		
		//Traitement du code HTML
		$this->_protect_content();
		
		//Traitement des smilies
		$this->_parse_smilies();
		
		//Interpr�tation des sauts de ligne
		$this->content = nl2br($this->content);
		
		// BBCode simple tags
		$this->_parse_simple_tags();
		
		//Tableaux
		if (!in_array('table', $this->forbidden_tags) && strpos($this->content, '[table') !== false)
		{
			$this->_parse_table();
		}
		
		//Listes
		if (!in_array('list', $this->forbidden_tags)&& strpos($this->content, '[list') !== false)
		{
			$this->_parse_list();
		}
		

		//On remet le code HTML mis de c�t�
		if (!empty($this->array_tags['html']))
		{
			$this->array_tags['html'] = array_map(create_function('$string', 'return str_replace("[html]", "<!-- START HTML -->\n", str_replace("[/html]", "\n<!-- END HTML -->", $string));'), $this->array_tags['html']);
			$this->_reimplant_tag('html');
		}
		
		//On r�ins�re les fragments de code qui ont �t� pr�velev�s pour ne pas les consid�rer
		if (!empty($this->array_tags['code']))
		{
			$this->array_tags['code'] = array_map(create_function('$string', 'return preg_replace(\'`^\[code(=.+)?\](.+)\[/code\]$`isU\', \'[[CODE$1]]$2[[/CODE]]\', $string);'), $this->array_tags['code']);
			$this->_reimplant_tag('code');
		}
		
		parent::parse();
	}
	
	## Private ##
	/**
	 * @desc Protects the incoming content:
	 * <ul>
	 * 	<li>Breaks all HTML tags and javascript code</li>
	 * 	<li>Accepts only the special character's entitites</li>
	 * 	<li>Treats the Word pasted characters</li>
	 * </ul>
	 */
	function _protect_content()
	{
		//Breaking the HTML code
		$this->content = htmlspecialchars($this->content, ENT_NOQUOTES);
		$this->content = strip_tags($this->content);
		
		//While we aren't in UTF8 encoding, we have to use HTML entities to display some special chars, we accept them.
		$this->content = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $this->content);
		
		//Treatment of the Word pasted characters
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
	}
	
	/**
	 * @desc Replaces the smiley's code by the corresponding HTML image tag
	 */
	function _parse_smilies()
	{
		@include(PATH_TO_ROOT . '/cache/smileys.php');
		if (!empty($_array_smiley_code))
		{
			//Cr�ation du tableau de remplacement.
			foreach ($_array_smiley_code as $code => $img)
			{
				$smiley_code[] = '`(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . preg_quote($code) . ')`';
				$smiley_img_url[] = '<img src="/images/smileys/' . $img . '" alt="' . addslashes($code) . '" class="smiley" />';
			}
			$this->content = preg_replace($smiley_code, $smiley_img_url, $this->content);
		}
	}
	
	/**
	 * @desc Parses all BBCode simple tags.
	 * The simple tags are those which can be treated enough requiring many different treatments.
	 * The not simple tags are [code], [html], [table] and its content [row] [col] [head], [list].
	 */
	function _parse_simple_tags()
	{
		global $LANG;
		$array_preg = array(
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'sup' => '`\[sup\](.+)\[/sup\]`isU',
			'sub' => '`\[sub\](.+)\[/sub\]`isU',
			'img' => '`\[img(?:=(top|middle|bottom))?\]((?:[./]+|(?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
			'color' => '`\[color=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/color\]`isU',
			'bgcolor' => '`\[bgcolor=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/bgcolor\]`isU',
			'size' => '`\[size=([1-9]|(?:[1-4][0-9]))\](.+)\[/size\]`isU',
			'font' => '`\[font=(arial|times|courier(?: new)?|impact|geneva|optima)\](.+)\[/font\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'align' => '`\[align=(left|center|right|justify)\](.+)\[/align\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'anchor' => '`\[anchor=([a-z_][a-z0-9_-]*)\](.*)\[/anchor\]`isU',
			'acronym' => '`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-9]{1,3}),([0-9]{1,3})\](((?:[./]+|(?:https?|ftps?)://([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4})+(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*))\[/swf\]`iU',
			'movie' => '`\[movie=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/movie\]`iU',
            'sound' => '`\[sound\]([a-z0-9_+.:?/=#%@&;,-]*)\[/sound\]`iU',
			'math' => '`\[math\](.+)\[/math\]`iU',
			'url' => '`\[url\]((?:[./]+|(?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)?(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,() -]*)\[/url\]`isU',
			'url2' => '`\[url\]((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,() -]*)\[/url\]`isU',
			'url3' => '`\[url=((?:[./]+|(?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)?(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,() -]*)\]([^\n\r\t\f]+)\[/url\]`isU',
			'url4' => '`\[url=((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,() -]*)\]([^\n\r\t\f]+)\[/url\]`iU',
			'url5' => '`(\s+)((?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)(\s|<+)`isU',
			'url6' => '`(\s+)((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)(\s|<+)`i',
			'mail' => '`(\s+)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(\s+)`i',
			'mail2' => '`\[mail=([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})\]([^\n\r\t\f]+)\[/mail\]`i'
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
			'style' => "<span class=\"$1\">$2</span>",
			'swf' => "<script type=\"text/javascript\"><!-- \n insertSwfPlayer(\"$3\", $1, $2); \n --></script>",
			'movie' => "<script type=\"text/javascript\"><!-- \n insertMoviePlayer(\"$3\", $1, $2); \n --></script>",
			'sound' => "<script type=\"text/javascript\"><!-- \n insertSoundPlayer(\"$1\"); \n --></script>",
			'math' => '[[MATH]]$1[[/MATH]]',
			'url' => "<a href=\"$1\">$1</a>",
			'url2' => "<a href=\"http://$1\">$1</a>",
			'url3' => "<a href=\"$1\">$2</a>",
			'url4' => "<a href=\"http://$1\">$2</a>",
			'url5' => "$1<a href=\"$2\">$2</a>$3",
			'url6' => "$1<a href=\"http://$2\">$2</a>$3",
			'mail' => "$1<a href=\"mailto:$2\">$2</a>$3",
			'mail2' => "<a href=\"mailto:$1\">$2</a>"
		);

		$parse_line = true;
		
		//Suppression des remplacements des balises interdites.
		if (!empty($this->forbidden_tags))
		{
			//Si on interdit les liens, on ajoute toutes les mani�res par lesquelles elles peuvent passer
			if (in_array('url', $this->forbidden_tags))
			{
				$this->forbidden_tags[] = 'url2';
				$this->forbidden_tags[] = 'url3';
				$this->forbidden_tags[] = 'url4';
				$this->forbidden_tags[] = 'url5';
				$this->forbidden_tags[] = 'url6';
			}
			if (in_array('mail', $this->forbidden_tags))
			{
				$this->forbidden_tags[] = 'mail2';
			}
			
			foreach ($this->forbidden_tags as $key => $tag)
			{
				if ($tag == 'line')
				{
					$parse_line = false;
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
		
		//Line tag
		if ($parse_line)
			$this->content = str_replace('[line]', '<hr class="bb_hr" />', $this->content);
			
		//Title tag
		if (!in_array('title', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`\[title=([1-4])\](.+)\[/title\]`iU', array(&$this, '_parse_title'), $this->content);
		}
		
		//Wikipedia tag
		if (!in_array('wikipedia', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`\[wikipedia(?: page="([^"]+)")?(?: lang="([a-z]+)")?\](.+)\[/wikipedia\]`isU', array(&$this, '_parse_wikipedia_links'), $this->content);
		}
		
		##Parsage des balises imbriqu�es.
		//Quote tag
		if (!in_array('quote', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $this->content);
			$this->_parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $this->content);
		}
		
		//Hide tag
		if (!in_array('hide', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $this->content);
		}
		
		//Indent tag
		if (!in_array('indent', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $this->content);
		}
		
		//Block tag
		if (!in_array('block', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[block]', '`\[block\](.+)\[/block\]`sU', '<div class="bb_block">$1</div>', $this->content);
			$this->_parse_imbricated('[block style=', '`\[block style="([^"]+)"\](.+)\[/block\]`sU', '<div class="bb_block" style="$1">$2</div>', $this->content);
		}
		
		//Fieldset tag
		if (!in_array('fieldset', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[fieldset', '`\[fieldset(?: legend="(.*)")?(?: style="([^"]*)")?\](.+)\[/fieldset\]`sU', '<fieldset class="bb_fieldset" style="$2"><legend>$1</legend>$3</fieldset>', $this->content);
		}
	}
	
	/**
	 * @desc Serializes a split content according to the table tag and generates the complete HTML code.
	 * @param string[] $content Content of the parser split according to the table tag
	 */
	function _parse_imbricated_table(&$content)
	{
		if (is_array($content))
		{
			$string_content = '';
			$nbr_occur = count($content);
			for ($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'un tableau on le parse
				if ($i % 3 === 2)
				{
					//On parse d'abord les sous tableaux �ventuels
					$this->_parse_imbricated_table($content[$i]);
					//On parse le tableau concern� (il doit commencer par [row] puis [col] ou [head] et se fermer pareil moyennant espaces et retours � la ligne sinon il n'est pas valide)
					if (preg_match('`^(?:\s|<br />)*\[row(?: style="[^"]+")?\](?:\s|<br />)*\[(?:col|head)(?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?\].*\[/(?:col|head)\](?:\s|<br />)*\[/row\](?:\s|<br />)*$`sU', $content[$i]))
					{
						//On nettoie les caract�res �ventuels (espaces ou retours � la ligne) entre les diff�rentes cellules du tableau pour �viter les erreurs xhtml
						$content[$i] = preg_replace_callback('`^(\s|<br />)+\[row.*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+$`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/row\](\s|<br />)+\[row.*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[col.*\]`Us', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[head[^]]*\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[/row\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[/row\]`U', array(&$this, 'clear_html_br'), $content[$i]);
						//Parsage de row, col et head
						$content[$i] = preg_replace('`\[row( style="[^"]+")?\](.*)\[/row\]`sU', '<tr class="bb_table_row"$1>$2</tr>', $content[$i]);
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
				if ($i % 3 !== 1)
					$string_content .= $content[$i];
			}
			$content = $string_content;
		}
	}
	
	/**
	 * @desc Parses the table tag in the content of the parser
	 */
	function _parse_table()
	{
		//On supprime les �ventuels quote qui ont �t� transform�s en leur entit� html
		$this->_split_imbricated_tag($this->content, 'table', ' style="[^"]+"');
		$this->_parse_imbricated_table($this->content);
		//On remet les tableaux invalides tels qu'ils �taient avant
		$this->content = str_replace(array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), array('[col', '[row', '[/col', '[/row', '[head', '[/head'), $this->content);
	}
	
	/**
	 * @descSerializes a split content according to the list tag
	 * Generates the HTML code
	 * @param string[] $content Content split according to the list tag
	 */
	function _parse_imbricated_list(&$content)
	{
		if (is_array($content))
		{
			$string_content = '';
			$nbr_occur = count($content);
			for ($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'une liste on le parse
				if ($i % 3 === 2)
				{
					//On parse d'abord les sous listes �ventuelles
					if (is_array($content[$i]))
						$this->_parse_imbricated_list($content[$i]);
					
					if (strpos($content[$i], '[*]') !== false) //Si il contient au moins deux �l�ments
					{
						//Nettoyage des listes (retours � la ligne)
						$content[$i] = preg_replace_callback('`\[\*\]((?:\s|<br />)+)`', array(&$this, 'clear_html_br'), $content[$i]);
						$content[$i] = preg_replace_callback('`((?:\s|<br />)+)\[\*\]`', array(&$this, 'clear_html_br'), $content[$i]);
						if (substr($content[$i - 1], 0, 8) == '=ordered')
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
				if ($i % 3 !== 1)
					$string_content .= $content[$i];
			}
			$content = $string_content;
		}
	}
	
	/**
	 * @desc Parses the list tag of the content of the parser.
	 */
	function _parse_list()
	{
		//On nettoie les guillemets �chapp�s
		//on travaille dessus
		if (preg_match('`\[list(=(?:un)?ordered)?( style="[^"]+")?\](\s|<br />)*\[\*\].*\[/list\]`s', $this->content))
		{
			$this->_split_imbricated_tag($this->content, 'list', '(?:=ordered)?(?: style="[^"]+")?');
			$this->_parse_imbricated_list($this->content);
		}
	}
	
	/**
	 * @desc Callback treating the title tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the title tag are parsed
	 */
	function _parse_title($matches)
	{
		$level = (int)$matches[1];
		if ($level <= 2)
			return '<h3 class="title' . $level . '">' . $matches[2] . '</h3>';
		else
			return '<br /><h4 class="stitle' . ($level - 2) . '">' . $matches[2] . '</h4><br />';
	}
	
	/**
	 * @desc Callback which parses the wikipedia tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the wikipedia tag are parsed
	 */
	function _parse_wikipedia_links($matches)
	{
		global $LANG;
		
		//Langue
		$lang = $LANG['wikipedia_subdomain'];
		if (!empty($matches[2]))
			$lang = $matches[2];
		
		$page_url = !empty($matches[1]) ? $matches[1] : $matches[3];
		
		return '<a href="http://' . $lang . '.wikipedia.org/wiki/' . $page_url . '" class="wikipedia_link">' . $matches[3] . '</a>';
	}
	
	/**
	 * @desc Callback which clears the new line tag in the HTML generated code
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the new line tag are cleared
	 */
	function clear_html_br($matches)
	{
		return str_replace("<br />", "", $matches[0]);
	}
}

?>