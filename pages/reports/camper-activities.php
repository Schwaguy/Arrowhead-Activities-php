<?php
	$camperAdmin = true; 
	$userInfo = checkUser($con);

	$header = '<div class="row">';
	$header .= '<div class="col-12 col-xs-12 col-sm-6 col-md-8 col-lg-8"><h1 class="page-title">'. $userInfo['userName'] .'\'s '. siteVar('act','plural','capital') .'</h1></div>';
	//if ($_SESSION['userPermissions']['report'] == 1) {
		$header .= '<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-4 text-right"><a class="btn btn-light printLink" data-camper="'. $userInfo['userID'] .'">Print All Signups</a></div>'; 
	//}
	$header .= '</div>';

	$content .= '<div class="container main">
		'. $header .'
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	
	$redirect = '/reports/camper-activities/'; 

	include($ROOT . '/pages/inc/calendar.php');

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->'; 
?>