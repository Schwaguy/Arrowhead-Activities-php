<?php
	//$bunkInfo = (isset($_SESSION['bunkInfo']) ? $_SESSION['bunkInfo'] : getBunkInfo($_SESSION['userBunk'],$_SESSION['userID'],$con));
	$content .= '<div class="container main">
		<h1 class="page-title">Bunk '. siteVar('act','plural','capital') .': '. $_SESSION['bunkInfo']['name'] .'</h1>
		<h4>Counselor: '. $_SESSION['bunkInfo']['counselor'] .'</h4>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	$content .= '<div class="alert alert-success" role="alert">Click on the weeks below to view camper schedules.</div>';

	include($ROOT . '/pages/inc/calendar-bunk.php');

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->'; 
?>