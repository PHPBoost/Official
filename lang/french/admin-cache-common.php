<?php
/*##################################################
 *                           admin-cache-common.php
 *                            -------------------
 *   begin                : August 7, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

 ####################################################
#                     French                       #
 ####################################################
 
$lang = array();
$lang['cache'] = 'Cache';
$lang['cache_cleared_successfully'] = 'Le cache a �t� vid� avec succ�s !';
$lang['clear_cache'] = 'Vider';
$lang['explain_data_cache'] = '<p>PHPBoost met en cache un certain nombre d\'informations, ce qui permet d\'am�liorer consid�rablement ses performances.
Toutes les donn�es manipul�es par PHPBoost sont stock�es en base de donn�es mais chaque acc�s � la base de donn�es co�te cher en temps. Les donn�es qui sont acc�d�es de fa�on r�guli�re (notamment la configuration) sont ainsi conserv�es par le serveur
de fa�on � ne pas avoir � les demander � la base de donn�es.</p>
<p>En contre partie, cela signifie que certaines donn�es sont pr�sentes � deux endroits : dans la base de donn�es et sur le serveur web. Si vous modifiez des donn�es dans la base de donn�es, la modification ne se fera peut-�tre pas imm�diatement car le fichier de cache contient encore les anciennes donn�es.
Dans ce cas, il faut vider le cache � la main via cette page de configuration de fa�on � ce que PHPBoost soit oblig� de g�n�rer de nouveaux fichiers de cache contenant les donn�es � jour.
L\'emplacement de r�f�rence des donn�es est la base de donn�es. Si vous modifiez un fichier cache, d�s qu\'il sera invalid� car la base de donn�es aura chang�, les modifications seront perdues.</p>';
$lang['syndication_cache'] = 'Syndication';
$lang['explain_syndication_cache'] = '<p>PHPBoost met en cache l\'ensemble des flux de donn�es (RSS ou ATOM) qui lui sont demand�s. En pratique, la premi�re fois qu\'on lui demande un flux, il va le chercher en base de donn�es et il l\'enregistre sur le serveur web et n\'acc�de plus � la base de donn�es les fois suivantes pour
�viter des requ�tes dans la base de donn�es qui ralentissent consid�rablement l\'affichage des pages.</p>
<p>Via cette page de l\'administration de PHPBoost, vous pouvez vider le cache de fa�on � forcer PHPBoost � rechercher les donn�es dans la base de donn�es. C\'est particuli�rement utile si vous avez modifi� certaines choses manuellement dans la base de donn�es. En effet, elles ne seront pas prises en compte car le cache aura toujours les valeurs pr�c�dentes.</p>';

?>