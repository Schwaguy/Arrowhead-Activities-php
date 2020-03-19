<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

date_default_timezone_set('America/New_York');
global $today, $now, $todayNice;
$today = date("m/d/Y");
$now = date('Y-m-d H:i:s');
$todayNice = date("l, F jS, Y");
$today = date("6/17/2020");
$now = '2020-06-17 00:00:00'; 

global $host, $un, $pw, $db;
$host = 'localhost';
$un = 'atoo_arrowhead_a';
$pw = 'kidsH@veFun!active';
//$db = 'atoo_arrowhead_activities'; // LIVE
$db = 'atoo_arrowhead_activities_test'; // DEV

// Global Element File
global $URL, $ROOTPATH, $SITEURL;
$URL = 'https://' .$_SERVER['SERVER_NAME'];
$ROOTPATH = '.'; 
//$SITEURL = 'https://arrowheadclubday.com/'; // LIVE 
$SITEURL = 'https://dev.arrowheadclubday.com/'; // DEV

global $comanyName, $formFrom, $formFromEmail;
$companyName = 'Arrowhead Day Camp';
$formFrom = $companyName;
$formFromEmail = 'chiefarrowhead@comcast.net'; 

?>