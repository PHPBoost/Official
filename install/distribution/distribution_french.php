<?php
/*##################################################
*                          distribution_french.php
*                            -------------------
*   begin                : October 12, 2008
*   copyright            :(C) 2008 Benoit Sautel
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

//Nom de la distribution
define('DISTRIBUTION_NAME', 'Publication');

//Description de la distribution
define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/publication.png" alt="" style="float:right;"/>
<p>Vous allez installer la distribution <strong>Publication</strong> de PHPBoost.</p>
<p>Cette distribution est id�ale pour cr�er un site qui servira � proposer aux visiteurs du contenu, que ce soit sous forme de texte, d\'images ou autres.</p>');

//Th�me de la distribution
define('DISTRIBUTION_THEME', 'phpboost');

//Page de d�marrage de la distribution (commencer � la racine du site avec /)
define('DISTRIBUTION_START_PAGE', '/news/news.php');

//Espace membre activ� ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
define('DISTRIBUTION_ENABLE_MEMBER', true);

//Liste des modules
$DISTRIBUTION_MODULES = array('articles', 'contact', 'news', 'pages', 'search', 'web');

?>