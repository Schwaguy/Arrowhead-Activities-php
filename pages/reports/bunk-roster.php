<?php
	$camperAdmin = false;	
	$bunk = (isset($_POST['bunk']) ? (($_POST['bunk']>0) ? $_POST['bunk'] : 0) : 0);
	$counselor = (isset($_POST['counselor']) ? (($_POST['counselor']>0) ? $_POST['counselor'] : 0) : 0);
	$bunkInfo = getBunkInfo($bunk,$counselor,$con);
	$content .= '<div class="container main">
		<h1 class="page-title">Bunk Roster: '. $bunkInfo['name'] .'</h1>
		<h4>Counselor: '. $bunkInfo['counselor'] .'</h4>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	$content .= '<div class="alert alert-success" role="alert">Click on the weeks below to view camper schedules.</div>';

	$redirect = '/bunks/';

	include($ROOT . '/pages/inc/calendar-bunk.php');

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->'; 
?>