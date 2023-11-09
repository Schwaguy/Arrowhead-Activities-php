<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 0);
//error_reporting(0);

date_default_timezone_set('America/New_York');
global $today, $now, $todayNice, $startTimeInt, $startTimeSenior;
$today = date("m/d/Y");
$now = date('Y-m-d H:i:s');
$todayNice = date("l, F jS, Y");

//$today = date("6/21/2023");
//$now = '2023-06-21 19:01:00'; 

global $host, $un, $pw, $db;
$host = 'localhost';

// inMotion
$un = 'n7c3f85_arrowClub';
$pw = 'kidsH@veFun!active';
$db = 'n7c3f85_arrowClub'; // LIVE

// HostGator
//$un = 'atoo_arrowhead_a';
//$pw = 'kidsH@veFun!active';
//$db = 'atoo_arrowhead_activities'; // LIVE
//$db = 'atoo_arrowhead_activities_test'; // DEV

// Global Element File
global $URL, $ROOTPATH, $SITEURL;
$URL = 'https://' .$_SERVER['SERVER_NAME'];
$ROOTPATH = '.'; 
$SITEURL = 'https://arrowheadclubday.com/'; // LIVE 
//$SITEURL = 'https://arrowheadclubday.computerwc.info/'; // DEV

global $comanyName, $companyEmail, $formFrom, $formFromEmail;
$companyName = 'Arrowhead Day Camp';
$formFrom = $companyName;
$companyEmail = 'chiefarrowhead@comcast.net'; 
$formFromEmail = 'noreply@arrowheadclubday.com'; 

global $smpthost, $smtpSecurity, $smtpPort, $smtpUN, $smtpPW, $replytoEmail;
//$smtpHost = 'ssl://mail.arrowheadclubday.com'; 
//$smtpSecurity = 'tls'; 
//$smtpPort = 465;
//$smtpUN = 'noreply@arrowheadclubday.com'; 
//$smtpPW = '@rrowEmailSend1!';
//$replytoEmail = 'chiefarrowhead@comcast.net';

$smtpHost = 'smtp.sendgrid.net'; 
$smtpSecurity = 'tls'; 
$smtpPort = 587;
$smtpUN = 'apikey'; 
$sendGrindUN = 'Arrowhead'; 
$smtpPW = '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';

//$smtpHost = 'https://api.sendgrid.com/';
//$smtpUN = 'Arrowhead'; 
//$smtpPW = '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';

$replytoEmail = 'chiefarrowhead@comcast.net';

?>