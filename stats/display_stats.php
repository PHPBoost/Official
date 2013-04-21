<?php
/*##################################################
 *                            display_stats.php
 *                            -------------------
 *   begin                : August 26, 2007
 *   copyright            : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
/**
* @package ajax
*
*/

define('PATH_TO_ROOT', '..');
define('NO_SESSION_LOCATION', true); //Ne r�actualise pas l'emplacement du visiteur/membre

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

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/stats.php');


$Stats = new ImagesStats();

$array_stats = array('other' => 0);
if ($get_visit_month)
{
    $year = !empty($_GET['year']) ? NumberHelper::numeric($_GET['year']) : date('Y');
    $month = !empty($_GET['month']) ? NumberHelper::numeric($_GET['month']) : '1';

    $array_stats = array();
    $result = $Sql->query_while("SELECT nbr, stats_day
	FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' 
	ORDER BY stats_day", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $array_stats[$row['stats_day']] = $row['nbr'];
    }
    $Sql->query_close($result);

    //Nombre de jours pour chaque mois (gestion des ann�es bissextiles)
    $bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
    //Compl�ment des jours manquant.
    $array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
    for ($i = 1; $i <= $array_month[$month - 1]; $i++)
    {
        if (!isset($array_stats[$i]))
        {
            $array_stats[$i] = 0;
        }
    }
    $Stats->load_data($array_stats, 'histogram', 5);
    //Trac� de l'histogramme.
    $Stats->draw_histogram(440, 250, '', array($LANG['days'], $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_visit_year)
{
    $year = !empty($_GET['year']) ? NumberHelper::numeric($_GET['year']) : '';

    $array_stats = array();
    $result = $Sql->query_while ("SELECT SUM(nbr) as total, stats_month
	FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "'
	GROUP BY stats_month
	ORDER BY stats_month", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $array_stats[$row['stats_month']] = $row['total'];
    }
    $Sql->query_close($result);

    //Compl�ment des mois manquant
    for ($i = 1; $i <= 12; $i++)
    {
        if (!isset($array_stats[$i]))
        {
            $array_stats[$i] = 0;
        }
    }
    $Stats->load_data($array_stats, 'histogram', 5);
    //Trac� de l'histogramme.
    $Stats->draw_histogram(440, 250, '', array($LANG['month'], $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_pages_day)
{
    $year = !empty($_GET['year']) ? NumberHelper::numeric($_GET['year']) : '';
    $month = !empty($_GET['month']) ? NumberHelper::numeric($_GET['month']) : '1';
    $day = !empty($_GET['day']) ? NumberHelper::numeric($_GET['day']) : '1';

    $array_stats = array();
    $pages_details = unserialize((string)$Sql->query("SELECT pages_detail FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' AND stats_day = '" . $day . "'", __LINE__, __FILE__));
    if (is_array($pages_details))
    {
        foreach ($pages_details as $hour => $pages)
        {
            $array_stats[$hour] = $pages;
        }
    }

    //Compl�ment des heures manquantes.
    for ($i = 0; $i <= 23; $i++)
    {
        if (!isset($array_stats[$i]))
        {
            $array_stats[$i] = 0;
        }
    }
    $Stats->load_data($array_stats, 'histogram', 5);
    //Trac� de l'histogramme.
    $Stats->draw_histogram(440, 250, '', array($LANG['hours'], $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_pages_month)
{
    $year = !empty($_GET['year']) ? NumberHelper::numeric($_GET['year']) : date('Y');
    $month = !empty($_GET['month']) ? NumberHelper::numeric($_GET['month']) : '1';

    $array_stats = array();
    $result = $Sql->query_while("SELECT pages, stats_day
	FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' 
	ORDER BY stats_day", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $array_stats[$row['stats_day']] = $row['pages'];
    }
    $Sql->query_close($result);

    //Nombre de jours pour chaque mois (gestion des ann�es bissextiles)
    $bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
    //Compl�ment des jours manquant.
    $array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
    for ($i = 1; $i <= $array_month[$month - 1]; $i++)
    {
        if (!isset($array_stats[$i]))
        {
            $array_stats[$i] = 0;
        }
    }
    $Stats->load_data($array_stats, 'histogram', 5);
    //Trac� de l'histogramme.
    $Stats->draw_histogram(440, 250, '', array($LANG['days'], $LANG['page_s']), NO_DRAW_LEGEND, NO_DRAW_VALUES, 8);
}
elseif ($get_pages_year)
{
    $year = !empty($_GET['year']) ? NumberHelper::numeric($_GET['year']) : '';

    $array_stats = array();
    $result = $Sql->query_while ("SELECT SUM(pages) as total, stats_month
	FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "'
	GROUP BY stats_month
	ORDER BY stats_month", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $array_stats[$row['stats_month']] = $row['total'];
    }
    $Sql->query_close($result);

    //Compl�ment des mois manquant
    for ($i = 1; $i <= 12; $i++)
    {
        if (!isset($array_stats[$i]))
        {
            $array_stats[$i] = 0;
        }
    }
    $Stats->load_data($array_stats, 'histogram', 5);
    //Trac� de l'histogramme.
    $Stats->draw_histogram(440, 250, '', array($LANG['month'], $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_brw) //Navigateurs.
{
    $array_stats = array();
    $percent_other = 0;
    foreach (StatsSaver::retrieve_stats('browsers') as $name => $value)
    {
        if (isset($stats_array_browsers[$name]) && $name != 'other')
        {
            $array_stats[$stats_array_browsers[$name][0]] = $value;
        }
        else
        {
            $percent_other += $value;
        }
    }
    if ($percent_other > 0)
    {
        $array_stats[$stats_array_browsers['other'][0]] = $percent_other;
    }

    $Stats->load_data($array_stats, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/browsers.png');
}
elseif ($get_os)
{
    $array_stats = array();
    $percent_other = 0;
    foreach (StatsSaver::retrieve_stats('os') as $name => $value)
    {
        if (isset($stats_array_os[$name]) && $name != 'other')
        {
            $array_stats[$stats_array_os[$name][0]] = $value;
        }
        else
        {
            $percent_other += $value;
        }
    }
    if ($percent_other > 0)
    {
        $array_stats[$stats_array_os['other'][0]] = $percent_other;
    }

    $Stats->load_data($array_stats, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/os.png');
}
elseif ($get_lang)
{
    $array_stats = array();
    $percent_other = 0;
    foreach (StatsSaver::retrieve_stats('lang') as $name => $value)
    {
        foreach ($stats_array_lang as $regex => $array_country)
        {
            if (preg_match('`' . $regex . '`', $name))
            {
                if ($name != 'other')
                {
                    $array_stats[$array_country[0]] = $value;
                }
                else
                {
                    $percent_other += $value;
                }
                break;
            }
        }
    }
    if ($percent_other > 0)
    {
        $array_stats[$stats_array_lang['other'][0]] = $percent_other;
    }

    $Stats->load_data($array_stats, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/lang.png');
}
elseif ($get_theme)
{
    include_once(PATH_TO_ROOT . '/kernel/begin.php');
    define('TITLE', '');
    include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

    $stats_array = array();
	foreach (ThemeManager::get_activated_themes_map() as $id => $theme)
	{
		$stats_array[$id] = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_theme = '" . $id . "'");
	}

    $Stats->load_data($stats_array, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/theme.png');
}
elseif ($get_sex)
{
    include_once(PATH_TO_ROOT . '/kernel/begin.php');
    define('TITLE', '');
    include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

    $array_stats = array();
    $result = $Sql->query_while ("SELECT member.user_id, count(ext_field.user_sex) as compt, ext_field.user_sex
	FROM " . PREFIX . "member member
	LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = member.user_id
	GROUP BY ext_field.user_sex
	ORDER BY compt", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        switch ($row['user_sex'])
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
    $Sql->query_close($result);

    $Stats->load_data($array_stats, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/sex.png');
}
elseif ($get_bot)
{
	$array_robot = StatsSaver::retrieve_stats('robots');
	$stats_array = array();
	if (is_array($array_robot))
	{
		foreach ($array_robot as $key => $value)
		{
			$array_info = explode('/', $value);
			if (isset($array_info[0]) && isset($array_info[1]))
			{
				$name = ucwords($array_info[0]);
				if (array_key_exists($name, $stats_array))
				{
					$stats_array[$name] = ($stats_array[$name] + $array_info[1]);
				}
				else
				{
					$stats_array[$name] = $array_info[1];
				}
			}
			else if (isset($array_info[0]))
			{
				$name = ucwords($key);
				if (array_key_exists($name, $stats_array))
				{
					$stats_array[$name] = ($stats_array[$name] + $array_info[0]);
				}
				else
				{
					$stats_array[$name] = $array_info[0];
				}
			}
		}
	}
    $Stats->load_data($stats_array, 'ellipse', 5);
    $Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/cache/bot.png');
}

?>