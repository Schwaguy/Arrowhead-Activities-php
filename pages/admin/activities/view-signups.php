<?php

	if (isset($_POST['id'])) {
		$activity = getActivityinfo($_POST['id'],$con);
		$signups = getActivitySignups($_POST['id'],$con);
	}

	$content .= '<div class="container main">
		<h1 class="page-title">'. siteVar('act','singular','capital') .' Signups</h1>
		
		<h2>'. getName($activity['week'],'weeks',$con) .' - '. $activity['name'] .'</h2>
		
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$weekID = $_POST['id'];
		include($ROOT .'/pages/inc/agenda-signups.php');
		$content .= '</div><!-- /list-group -->';
	
$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>