<?php
/*##################################################
*                          distribution_french.php
*                            -------------------
*   begin                : June 6, 2009
*   copyright            :(C) 2009 Benoit Sautel
*   email                : ben.popeye@phpboost.com
*
*
###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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
define('DISTRIBUTION_NAME', 'PDK');

//Description de la distribution
define('DISTRIBUTION_DESCRIPTION', '<p>Vous allez installer la distribution <strong><acronym title="PHPBoost Development Kit">PDK</acronym></strong> de PHPBoost.</p>
<p>Cette distribution est parfaitement adapt�e aux d�veloppeurs qui souhaitent d�velopper un module afin de l\'int�grer � PHPBoost. Elle contient un outil de gestion de la base de donn�es ainsi que la documentation du framework de PHPBoost.</p>');

//Th�me de la distribution
define('DISTRIBUTION_THEME', 'extends');

//Page de d�marrage de la distribution (commencer � la racine du site avec /)
define('DISTRIBUTION_START_PAGE', '/doc/3.0/index.php');

//Espace membre activ� ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
define('DISTRIBUTION_ENABLE_USER', true);

//Liste des modules
$DISTRIBUTION_MODULES = array('connect', 'database', 'doc');

?>