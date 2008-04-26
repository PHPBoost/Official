<?php
/*##################################################
 *                            display_stats.php
 *                            -------------------
 *   begin                : August 26, 2007
 *   copyright          : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
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

$get_brw = !empty($_GET['browsers']) ? true : false;
$get_os = !empty($_GET['os']) ? true : false;
$get_lang = !empty($_GET['lang']) ? true : false;
$get_bot = !empty($_GET['bot']) ? true : false;
$get_theme = !empty($_GET['theme']) ? true : false;
$get_sex = !empty($_GET['sex']) ? true : false;
$get_visit_month = !empty($_GET['visit_month']) ? true : false;
$get_visit_year = !empty($_GET['visit_year']) ? true : false;
$get_pages_day = !empty($_GET['pages_day']) ? true : false;
$get_pages_month = !empty($_GET['pages_month']) ? true : false;
$get_pages_year = !empty($_GET['pages_year']) ? true : false;

include_once('../includes/begin.php');

include_once('../lang/' . $CONFIG['lang'] . '/stats.php');
include_once('../includes/framework/stats.class.php');
$Stats = new Stats();

$array_stats = array('other' => 0);
if( $get_visit_month )
{
	$year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
	$month = !empty($_GET['month']) ? numeric($_GET['month']) : '1';
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT nbr, stats_day 
	FROM ".PREFIX."stats WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' 
	ORDER BY stats_day", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		$array_stats[$row['stats_day']] = $row['nbr'];
	}
	$Sql->Close($result);
	
	//Nombre de jours pour chaque mois (gestion des ann�es bissextiles)
	$bissextile = (($year % 4) == 0) ? 29 : 28;
	//Compl�ment des jours manquant.
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	for($i = 1; $i <= $array_month[$month - 1]; $i++)
	{
		if( !isset($array_stats[$i]) )
			$array_stats[$i] = 0;
	}
	$Stats->Load_statsdata($array_stats, 'histogram', 5);
	//Trac� de l'histogramme.
	$Stats->Draw_histogram(440, 250, '', array($LANG['days'], $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif( $get_visit_year )
{
	$year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT SUM(nbr) as total, stats_month
	FROM ".PREFIX."stats WHERE stats_year = '" . $year . "'
	GROUP BY stats_month
	ORDER BY stats_month", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		$array_stats[$row['stats_month']] = $row['total'];
	}
	$Sql->Close($result);
	
	//Compl�ment des mois manquant
	for($i = 1; $i <= 12; $i++)
	{
		if( !isset($array_stats[$i]) )
			$array_stats[$i] = 0;
	}
	$Stats->Load_statsdata($array_stats, 'histogram', 5);
	//Trac� de l'histogramme.
	$Stats->Draw_histogram(440, 250, '', array($LANG['month'], $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif( $get_pages_day )
{
	$year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
	$month = !empty($_GET['month']) ? numeric($_GET['month']) : '1';
	$day = !empty($_GET['day']) ? numeric($_GET['day']) : '1';
	
	$array_stats = array();
	$pages_details = unserialize((string)$Sql->Query("SELECT pages_detail FROM ".PREFIX."stats WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' AND stats_day = '" . $day . "'", __LINE__, __FILE__));
	if( is_array($pages_details) )
		foreach($pages_details as $hour => $pages)
			$array_stats[$hour] = $pages;
	
	//Compl�ment des heures manquantes.
	for($i = 0; $i <= 23; $i++)
	{
		if( !isset($array_stats[$i]) )
			$array_stats[$i] = 0;
	}
	$Stats->Load_statsdata($array_stats, 'histogram', 5);
	//Trac� de l'histogramme.
	$Stats->Draw_histogram(440, 250, '', array($LANG['hours'], $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif( $get_pages_month )
{
	$year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
	$month = !empty($_GET['month']) ? numeric($_GET['month']) : '1';
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT pages, stats_day 
	FROM ".PREFIX."stats WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' 
	ORDER BY stats_day", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		$array_stats[$row['stats_day']] = $row['pages'];
	}
	$Sql->Close($result);
	
	//Nombre de jours pour chaque mois (gestion des ann�es bissextiles)
	$bissextile = (($year % 4) == 0) ? 29 : 28;
	//Compl�ment des jours manquant.
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	for($i = 1; $i <= $array_month[$month - 1]; $i++)
	{
		if( !isset($array_stats[$i]) )
			$array_stats[$i] = 0;
	}
	$Stats->Load_statsdata($array_stats, 'histogram', 5);
	//Trac� de l'histogramme.
	$Stats->Draw_histogram(440, 250, '', array($LANG['days'], $LANG['page_s']), NO_DRAW_LEGEND, NO_DRAW_VALUES, 8);
}
elseif( $get_pages_year )
{
	$year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT SUM(pages) as total, stats_month
	FROM ".PREFIX."stats WHERE stats_year = '" . $year . "'
	GROUP BY stats_month
	ORDER BY stats_month", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		$array_stats[$row['stats_month']] = $row['total'];
	}
	$Sql->Close($result);
	
	//Compl�ment des mois manquant
	for($i = 1; $i <= 12; $i++)
	{
		if( !isset($array_stats[$i]) )
			$array_stats[$i] = 0;
	}
	$Stats->Load_statsdata($array_stats, 'histogram', 5);
	//Trac� de l'histogramme.
	$Stats->Draw_histogram(440, 250, '', array($LANG['month'], $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif( $get_brw ) //Navigateurs.
{
	//On lit le fichier
	$file = @fopen('../cache/browsers.txt', 'r');
	$browsers_serial = @fgets($file);
	$array_browsers = !empty($browsers_serial) ? unserialize($browsers_serial) : array();
	$array_stats = array();
	$percent_other = 0;
	foreach($array_browsers as $name => $value)
	{
		if( isset($stats_array_browsers[$name]) && $name != 'other' )
			$array_stats[$stats_array_browsers[$name][0]] = $value;
		else
			$percent_other += $value;
	}
	if( $percent_other > 0 )
		$array_stats[$stats_array_browsers['other'][0]] = $percent_other;
		
	@fclose($file);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/browsers.png');
}
elseif( $get_os )
{
	//On lit le fichier
	$file = @fopen('../cache/os.txt', 'r');
	$os_serial = @fgets($file);
	$array_os = !empty($os_serial) ? unserialize($os_serial) : array();
	$array_stats = array();
	$percent_other = 0;
	foreach($array_os as $name => $value)
	{
		if( isset($stats_array_os[$name]) && $name != 'other' )
			$array_stats[$stats_array_os[$name][0]] = $value;
		else
			$percent_other += $value;
	}
	if( $percent_other > 0 )
		$array_stats[$stats_array_os['other'][0]] = $percent_other;
	@fclose($file);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/os.png');
}	
elseif( $get_lang )
{
	//On lit le fichier
	$file = @fopen('../cache/lang.txt', 'r');
	$lang_serial = @fgets($file);
	$array_lang = !empty($lang_serial) ? unserialize($lang_serial) : array();
	$array_stats = array();
	$percent_other = 0;
	foreach($array_lang as $name => $value)
	{
		foreach($stats_array_lang as $regex => $array_country)
		{
			if( preg_match('`' . $regex . '`', $name) )
			{	
				if( $name != 'other' )
					$array_stats[$array_country[0]] = $value;
				else
					$percent_other += $value;
				break;
			}
		}
	}
	if( $percent_other > 0 )
		$array_stats[$stats_array_lang['other'][0]] = $percent_other;

	@fclose($file);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/lang.png');
}
elseif( $get_theme )
{
	include_once('../includes/begin.php');
	define('TITLE', '');
	include_once('../includes/header_no_display.php');
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT at.theme, COUNT(m.user_theme) AS compt
	FROM ".PREFIX."themes at
	LEFT JOIN ".PREFIX."member m ON m.user_theme = at.theme
	GROUP BY at.theme", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		$name = isset($info_theme['name']) ? $info_theme['name'] : $row['theme'];
		$array_stats[$name] = $row['compt'];
	}	
	$Sql->Close($result);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/theme.png');
}
elseif( $get_sex )
{
	include_once('../includes/begin.php');
	define('TITLE', '');
	include_once('../includes/header_no_display.php');
	
	$array_stats = array();
	$result = $Sql->Query_while("SELECT count(user_sex) as compt, user_sex
	FROM ".PREFIX."member
	GROUP BY user_sex
	ORDER BY compt", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{
		switch($row['user_sex'])
		{
			case 0:
			$name = $LANG['unknow'];
			break;
			case 1:
			$name = $LANG['male'];
			break;
			case 2:
			$name = $LANG['female'];
			break;
		}
		$array_stats[$name] = $row['compt'];
	}	
	$Sql->Close($result);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/sex.png');
}
elseif( $get_bot )
{
	//On lit le fichier
	$file = @fopen('../cache/robots.txt', 'r');
	$robot_serial = @fgets($file);	
	$array_robot = !empty($robot_serial) ? unserialize($robot_serial) : array('other' => 0);
	$array_stats = array();
	if( is_array($array_robot) )
	{
		foreach($array_robot as $key => $value)
		{
			$array_info = explode('/', $value);			
			if( isset($array_info[0]) && isset($array_info[1]) )
				$array_stats[$array_info[0]] = $array_info[1];
		}
	}
	@fclose($file);
	
	$Stats->Load_statsdata($array_stats, 'ellipse', 5);
	//Trac� de l'ellipse.
	$Stats->Draw_ellipse(210, 100, '../cache/bot.png');
}

?>