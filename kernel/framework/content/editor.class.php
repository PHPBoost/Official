<?php
/*##################################################
*                             editor.class.php
*                            -------------------
*   begin                : July 5 2008
*   copyright          : (C) 2008 R�gis Viarre
*   email                :  crowkait@phpboost.com
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

class ContentEditor
{
	function ContentEditor($language_type = false)
	{
		if( $language_type !== false )
			$this->set_language($language_type);
	}
	
	//Balises interdites.
	function set_forbidden_tags($forbidden_tags)
	{
		$this->forbidden_tags = $forbidden_tags;
	}
	
	//Identifiant du textarea de destination.
	function set_identifier($identifier)
	{
		$this->identifier = $identifier;
	}
	
	//Template alternatif.
	function set_template($template)
	{
		$this->template = $template;
	}
	
	## Private ##
	//Langage type
	var $language_type = DEFAULT_LANGUAGE;
}

?>