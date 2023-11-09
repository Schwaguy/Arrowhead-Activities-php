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
	require_once('globals/currentPage.php');
	include('inc/login.php');
	//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
	if ($con) $con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('inc/doc-head.php'); ?>
</head>
<body>
	<?=$content?>
	<?php include('inc/doc-foot.php'); ?>
</body>
</html>