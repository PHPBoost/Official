<?php
/*##################################################
 *                                header.php
 *                            -------------------
 *   begin                : July 09, 2005
 *   copyright            : (C) 2005 Viarre R�gis
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

if (defined('PHPBOOST') !== true)
{
    exit;
}

if (!defined('TITLE'))
{
    define('TITLE', $LANG['unknow']);
}

$Session->check(TITLE); //V�rification de la session.

$Template->set_filenames(array(
	'header'=> 'header.tpl'
));

//Gestion de la maintenance du site.
if ($CONFIG['maintain'] == -1 || $CONFIG['maintain'] > time())
{
    if (!$User->check_level(ADMIN_LEVEL)) //Non admin.
    {
        if (SCRIPT !== (DIR . '/member/maintain.php')) //Evite de cr�er une boucle infine.
        {
            redirect(HOST . DIR . '/member/maintain.php');
        }
    }
    elseif ($CONFIG['maintain_display_admin']) //Affichage du message d'alerte � l'administrateur.
    {
        //Dur�e de la maintenance.
        $array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600, 86400, 172800, 604800);
        $array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '3 ' . $LANG['hours'], '4 ' . $LANG['hours'], '5 ' . $LANG['hours'], '6 ' . $LANG['hours'], '7 ' . $LANG['hours'], '8 ' . $LANG['hours'], '16 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week']);
        //Retourne le d�lai de maintenance le plus proche.
        if ($CONFIG['maintain'] != -1)
        {
            $key_delay = 0;
            $current_time = time();
            $array_size = count($array_time) - 1;
            for ($i = $array_size; $i >= 1; $i--)
            {
                if (($CONFIG['maintain'] - $current_time) - $array_time[$i] < 0 &&  ($CONFIG['maintain'] - $current_time) - $array_time[$i-1] > 0)
                {
                    $key_delay = $i-1;
                    break;
                }
            }

            //Calcul du format de la date
            $seconds = gmdate_format('s', $CONFIG['maintain'], TIMEZONE_SITE);
            $array_release = array(
            gmdate_format('Y', $CONFIG['maintain'], TIMEZONE_SITE), (gmdate_format('n', $CONFIG['maintain'], TIMEZONE_SITE) - 1), gmdate_format('j', $CONFIG['maintain'], TIMEZONE_SITE),
            gmdate_format('G', $CONFIG['maintain'], TIMEZONE_SITE), gmdate_format('i', $CONFIG['maintain'], TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
        }
        else //D�lai ind�termin�.
        {
            $key_delay = 0;
            $array_release = array('0', '0', '0', '0', '0', '0');
        }

        $timezone_delay = ($CONFIG['timezone'] - number_round(date('Z')/3600, 0) - date('I')) * 3600 * 1000; // D�callage du serveur par rapport au m�ridien de greenwitch et � l'heure d'�t�, En millisecondes
        $Template->assign_vars(array(
			'C_ALERT_MAINTAIN' 		=> true,
			'C_MAINTAIN_DELAY' 		=> true,
			'UNSPECIFIED' 			=> $CONFIG['maintain'] != -1 ? 1 : 0,
			'DELAY' 				=> isset($array_delay[$key_delay]) ? $array_delay[$key_delay] : '0',
			'TIMEZONE_DELAY_NOW' 	=> $timezone_delay >= 0 ? '+ ' . $timezone_delay : $timezone_delay,
			'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
			'L_MAINTAIN_DELAY' 		=> $LANG['maintain_delay'],
			'L_LOADING' 			=> $LANG['loading'],
			'L_DAYS' 				=> $LANG['days'],
			'L_HOURS' 				=> $LANG['hours'],
			'L_MIN' 				=> $LANG['minutes'],
			'L_SEC' 				=> $LANG['seconds'],
        ));
    }
}

//Ajout des �ventuels css alternatifs du module.
$alternative_css = '';
if (defined('ALTERNATIVE_CSS'))
{
    $alternative = null;
    $styles = @unserialize(ALTERNATIVE_CSS);
    if (is_array($styles))
    {
        foreach ($styles as $module => $style) {
            $base 	= PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
            $file = $base . $style . '.css';
            if (file_exists($file))
            {
                $alternative = $file;
            }
            else
            {
                $alternative = PATH_TO_ROOT . '/' . $module . '/templates/' . $style . '.css';
            }
            $alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
        }
    }
    else
    {
        $array_alternative_css = explode(',', str_replace(' ', '', ALTERNATIVE_CSS));
        $module = $array_alternative_css[0];
        $base 	= PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
        foreach ($array_alternative_css as $alternative)
        {
            $file = $base . $alternative . '.css';
            if (file_exists($file))
            {
                $alternative = $file;
            }
            else
            {
                $alternative = PATH_TO_ROOT . '/' . $module . '/templates/' . $alternative . '.css';
            }
            $alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
        }
    }
}

//On ajoute les css associ�s aux mini-modules.
$Cache->load('css');
if (isset($CSS[get_utheme()]))
{
    foreach ($CSS[get_utheme()] as $css_mini_module)
    {
        $alternative_css .= "\t\t" . '<link rel="stylesheet" href="' . PATH_TO_ROOT . $css_mini_module . '" type="text/css" media="screen, handheld" />' . "\n";
    }
}

//On r�cup�re la configuration du th�me actuel, afin de savoir si il faut placer les s�parateurs de colonnes (variable sur chaque th�me).
$THEME = load_ini_file(PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());

$member_connected = $User->check_level(MEMBER_LEVEL);
$Template->assign_vars(array(
	'SID' 						=> SID,
	'SERVER_NAME' 				=> $CONFIG['site_name'],
	'SITE_NAME' 				=> $CONFIG['site_name'],
	'TITLE' 					=> stripslashes(TITLE),
	'SITE_DESCRIPTION' 			=> $CONFIG['site_desc'],
	'SITE_KEYWORD' 				=> $CONFIG['site_keyword'],
	'THEME' 					=> get_utheme(),
	'LANG' 						=> get_ulang(),
	'ALTERNATIVE_CSS' 			=> $alternative_css,
	'C_USER_CONNECTED' 			=> $member_connected,
	'C_USER_NOTCONNECTED' 		=> !$member_connected,
	'C_BBCODE_TINYMCE_MODE' 	=> $User->get_attribute('user_editor') == 'tinymce',
	'L_XML_LANGUAGE' 			=> $LANG['xml_lang'],
	'L_VISIT' 					=> $LANG['guest_s'],
	'L_TODAY' 					=> $LANG['today'],
	'PATH_TO_ROOT' 				=> PATH_TO_ROOT,
	'L_REQUIRE_PSEUDO' 			=> $LANG['require_pseudo'],
	'L_REQUIRE_PASSWORD' 		=> $LANG['require_password'],
    'BASE_URI'					=> HOST . SCRIPT
));

//Inclusion des blocs
import('core/menu_service');
if (!DEBUG)
{
    $result = @include_once(PATH_TO_ROOT . '/cache/menus.php');
}
else
{
    $result = include_once(PATH_TO_ROOT . '/cache/menus.php');
}
if (!$result)
{
    //En cas d'�chec, on r�g�n�re le cache
    $Cache->Generate_file('menus');

    //On inclut une nouvelle fois
    if (!@include_once(PATH_TO_ROOT . '/cache/menus.php'))
    {
        $Errorh->handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
    }
}

$Template->assign_vars(array(
	'C_MENUS_HEADER_CONTENT' 		=> !empty($MENUS[BLOCK_POSITION__HEADER]),
    'MENUS_HEADER_CONTENT' 			=> $MENUS[BLOCK_POSITION__HEADER],
	'C_MENUS_SUB_HEADER_CONTENT' 	=> !empty($MENUS[BLOCK_POSITION__SUB_HEADER]),
	'MENUS_SUB_HEADER_CONTENT' 		=> $MENUS[BLOCK_POSITION__SUB_HEADER]
));

//Si le compteur de visites est activ�, on affiche le tout.
if ($CONFIG['compteur'] == 1)
{
    $compteur 		= $Sql->query_array(DB_TABLE_VISIT_COUNTER, 'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);
    $compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
    $compteur_day 	= !empty($compteur['total']) ? $compteur['total'] : '1';

    $Template->assign_vars(array(
		'C_COMPTEUR' 		=> true,
		'COMPTEUR_TOTAL' 	=> $compteur_total,
		'COMPTEUR_DAY' 		=> $compteur_day
    ));
}

//Gestion de l'affichage des modules.
if (!defined('NO_LEFT_COLUMN'))
{
    define('NO_LEFT_COLUMN', false);
}
if (!defined('NO_RIGHT_COLUMN'))
{
    define('NO_RIGHT_COLUMN', false);
}

$left_column  = ($THEME_CONFIG[get_utheme()]['left_column'] && !NO_LEFT_COLUMN);
$right_column = ($THEME_CONFIG[get_utheme()]['right_column'] && !NO_RIGHT_COLUMN);

//D�but de la colonne de gauche.
if ($left_column) //Gestion des blocs de gauche.
{
    // Affichage des modules droits � gauche sur les th�mes � une colonne (gauche).
    $left_column_content = $MENUS[BLOCK_POSITION__LEFT] . (!$right_column ? $MENUS[BLOCK_POSITION__RIGHT] : '');
    $Template->assign_vars(array(
		'C_MENUS_LEFT_CONTENT' 	=> !empty($left_column_content),
		'MENUS_LEFT_CONTENT'	=> $left_column_content
    ));
}
if ($right_column)  //Gestion des blocs de droite.
{
    // Affichage des modules gauches � droite sur les th�mes � une colonne (droite).
    $right_column_content = $MENUS[BLOCK_POSITION__RIGHT] . (!$left_column ? $MENUS[BLOCK_POSITION__LEFT] : '');
    $Template->assign_vars(array(
		'C_MENUS_RIGHT_CONTENT' => !empty($right_column_content),
		'MENUS_RIGHT_CONTENT' 	=> $right_column_content
    ));
}

//Gestion du fil d'ariane, et des titres des pages dynamiques.
$Bread_crumb->display();

$Template->assign_vars(array(
	'C_MENUS_TOPCENTRAL_CONTENT' => !empty($MENUS[BLOCK_POSITION__TOP_CENTRAL]),
	'MENUS_TOPCENTRAL_CONTENT'   => $MENUS[BLOCK_POSITION__TOP_CENTRAL]
));

$Template->pparse('header');

?>
