<?php

	if (isset($_POST['id'])) {
		$activity = getActivityinfo($_POST['id'],$con);
		$signups = getActivitySignups($_POST['id'],$con);
	}

	$clearBtn = (($_SESSION['userPermissions']['admin'] == 1) ? ' <a class="btn btn-danger btn-clear btn-clear-weeks" data-toggle="tooltip" data-placement="top" title="This will clear all '. $activity['name'] .' signups" data-op="clear" data-user="" data-week="" data-activity="'. $activity['id'] .'">Clear Signups</a>' : ''); 

	$content .= '<div class="container main">
		<h1 class="page-title">'. siteVar('act','singular','capital') .' Signups</h1>
		
		<div class="row">
			<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<h2>'. getName($activity['week'],'weeks',$con) .' - '. $activity['name'] .'</h2>
			</div>';

		if ($_SESSION['userPermissions']['report'] == 1) {
			$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"><a class="btn btn-primary printLink" data-camper="" data-week="" data-bunk="" data-activity="'. $activity['id'] .'">Print Signups</a>'. $clearBtn .'</div>';
		}
	$content .= '</div>';

	$content .= '<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$weekID = $_POST['id'];
		include($ROOT .'/pages/inc/agenda-signups.php');
		$content .= '</div><!-- /list-group -->';
	
$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>