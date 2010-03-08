<?php
/**
 * xmlhttprequest.php
 *
 */

require_once('../kernel/begin.php');
require_once('../online/online_begin.php');
require_once('../kernel/header_no_display.php');
require_once('../online/online_mini.php');

$tmp = @file_get_contents('../cache/menus.php');
if ($tmp === FALSE)
	die ('Erreur');
	
$matches = array();
$i = @preg_match('/online_mini\((\d+),(\d+)\)/', $tmp, $matches);
if ($i === FALSE || $i == 0)
	die ('Erreur');

echo( online_mini($matches[1], $matches[2], TRUE) );
	
?>