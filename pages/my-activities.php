<?php
	$camperAdmin = false;

	$content .= '<div class="container main">
		<h1 class="page-title">My '. siteVar('act','plural','capital') .'</h1>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	
	$redirect = '/my-activities/'; 

	include($ROOT . '/pages/inc/calendar.php');

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->'; 
?>