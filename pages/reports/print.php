<?php
	ini_set('session.cache_limiter','public');
	session_cache_limiter(false);
	session_start();
	include('globals/error-reporting.php');
	global $ROOT, $PATH, $phpself;
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$PATH = ''; 
	$phpself = basename(__FILE__);
	require_once('globals/globals.php');

	$camper = ((isset($_REQUEST['camper'])) ? $_REQUEST['camper'] : '');
	$bunk = ((isset($_REQUEST['bunk'])) ? $_REQUEST['bunk'] : '');
	$week = ((isset($_REQUEST['week'])) ? $_REQUEST['week'] : '');



	if ($con) $con->close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Print Report</title>
</head>

<body>
	
	<p>Camper: <?=$camper?></p>
	<p>Bunk: <?=$bunk?></p>
	<p>Week: <?=$week?></p>
	
</body>
</html>