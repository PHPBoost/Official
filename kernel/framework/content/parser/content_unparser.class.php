<?php
/*##################################################
*                             content_unparser.class.php
*                            -------------------
*   begin                : August 10, 2008
*   copyright            : (C) 2008 Benoit Sautel
*   email                : ben.popeye@phpboost.com
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

import('content/parser/parser');

//Classe de gestion du contenu
class ContentUnParser extends Parser
{
	######## Public #######
	//Constructeur
	function ContentUnParser()
	{
		parent::Parser();
	}
	
	/*abstract*/ function unparse() {}
	
	## Private ##
	//Fonction de retour pour le html (pr�l�vement ou r�insertion)
	function _unparse_html($action)
	{
		//Pr�l�vement du HTML
		if( $action == PICK_UP )
		{
			$mask = '`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`is';
			$content_split = preg_split($mask, $this->content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if( $content_length > 1 )
			{
				$this->content = '';
				for($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if( $i % 2 == 0 )
					{
						$this->content .= $content_split[$i];
						//Ajout du tag de remplacement
						if( $i < $content_length - 1 )
							$this->content .= '[HTML_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					else
					{
						$this->array_tags['html_unparse'][] = $content_split[$i];
					}
				}
				
				//On prot�ge le code HTML � l'affichage qui vient non prot�g� de la base de donn�es
				$this->array_tags['html_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['html_unparse']);
			}
			return true;
		}
		//R�insertion du HTML
		else
		{
			if( !array_key_exists('html_unparse', $this->array_tags) )
				return false;
				
			$content_length = count($this->array_tags['html_unparse']);

			if( $content_length > 0 )
			{
				for( $i = 0; $i < $content_length; $i++ )
					$this->content = str_replace('[HTML_UNPARSE_TAG_' . $i . ']', '[html]' . $this->array_tags['html_unparse'][$i] . '[/html]', $this->content);
				$this->array_tags['html_unparse'] = array();
			}
			return true;
		}
	}
	
	//Fonction de retour pour le html (pr�l�vement ou r�insertion)
	function _unparse_code($action)
	{
		//Pr�l�vement du HTML
		if( $action == PICK_UP )
		{
			$mask = '`\[\[CODE(=[a-z0-9-]+(?:,(?:0|1)(?:,1)?)?)?\]\]' . '(.+)' . '\[\[/CODE\]\]`sU';
			$content_split = preg_split($mask, $this->content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if( $content_length > 1 )
			{
				$this->content = '';
				for($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if( $i % 3 == 0 )
					{
						$this->content .= $content_split[$i];
						//Ajout du tag de remplacement
						if( $i < $content_length - 1 )
							$this->content .= '[CODE_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					elseif( $i % 3 == 2 )
					{
						$this->array_tags['code_unparse'][] = '[code' . $content_split[$i - 1] . ']' . $content_split[$i] . '[/code]';
					}
				}
				//On prot�ge le code HTML � l'affichage qui vient non prot�g� de la base de donn�es
				$this->array_tags['code_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['code_unparse']);
			}
			return true;
		}
		//R�insertion du HTML
		else
		{
			if( !array_key_exists('code_unparse', $this->array_tags) )
				return false;
				
			$content_length = count($this->array_tags['code_unparse']);

			if( $content_length > 0 )
			{
				for( $i = 0; $i < $content_length; $i++ )
					$this->content = str_replace('[CODE_UNPARSE_TAG_' . $i . ']', $this->array_tags['code_unparse'][$i], $this->content);
				$this->array_tags['code_unparse'] = array();
			}
			return true;
		}
	}
}
?>