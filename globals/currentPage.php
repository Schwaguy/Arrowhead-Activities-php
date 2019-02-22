<?php
	global $thisPg, $pg, $sp, $tp;
	$thisPg = '';
	$pg = ''; 
	$sp = '';
	$tp = '';  
	if (!empty($_REQUEST['pg'])) {
		$thisPg = $_REQUEST['pg'];
		$pg = $_REQUEST['pg'];
	}
	if (!empty($_REQUEST['sp'])) {
		$thisPg = $_REQUEST['sp'];
		$sp = $_REQUEST['sp'];
	}
	if (!empty($_REQUEST['tp'])) {
		$thisPg = $_REQUEST['tp'];
		$tp = $_REQUEST['tp'];
	} 
?>