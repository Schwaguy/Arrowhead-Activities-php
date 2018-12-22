<?php
	session_start();
	include('globals/error-reporting.php');
	global $ROOT, $PATH, $phpself;
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$PATH = ''; 
	$phpself = basename(__FILE__);
	require_once('globals/globals.php');
	require_once('globals/currentPage.php');
	include('inc/login.php');
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
	<div id="feedback"><div id="processing"><i class="fas fa-spinner fa-pulse fa-spin"></i></div><div id="response" class="container">Feedback Goes Here</div></div>
</body>
</html>