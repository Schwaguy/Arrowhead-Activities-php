<?php
	$content .= '<div class="container main">';
	if ($_SESSION['userPermissions']['admin'] == 1) {
		$content .= '<div class="quick-buttons"><a href="/admin/activities/add/" class="btn btn-primary" type="button" aria-expanded="false" aria-controls="addActivity">Add New '. siteVar('act','singular','capital') .'</a></div>';
	}
	$content .= '<h1 class="page-title">Arrowhead Club '. siteVar('act','plural','capital') .'</h1>
		
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	
	$content .= '<div class="alert alert-success" role="alert">Click on the weeks below to add/edit '. siteVar('act','plural','capital') .'</div>';
	
	$weeks = getWeeks('array','',false,false,$con);
	if (is_array($weeks)) {
		foreach($weeks as $week) {
			$date = date_create($week['startDate']);
			
			$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">';
			$content .= '<div id="week-'. $week['id'] .'" class="list-group-item week-expand"><div class="row">';
			$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6"><a href="#panel-'. $week['id'] .'" title="'. $week['name'] .'" data-toggle="collapse" data-target="#panel-'. $week['id'] .'" aria-expanded="false" aria-controls="panel-'. $week['id'] .'"><h3>'. $week['name'] .' '. siteVar('act','plural','capital') .'</h3></a></div>';
			if ($_SESSION['userPermissions']['report'] == 1) {
				$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
					<a class="btn btn-light printLink" data-camper="" data-week="'. $week['id'] .'" data-activity="" data-signup="yes"><i class="fal fa-print btnIcon"></i> <span class="btnText"> Signups</span></a>
					<a class="btn btn-light printLink" data-camper="" data-week="'. $week['id'] .'" data-activity="" data-signup="no"><i class="fal fa-print btnIcon"></i> <span class="btnText"> Not Signed Up</span></a>
				</div>';
			}
			$content .= '</div></div>';
			
			$content .= '<div class="collapse" id="panel-'. $week['id'] .'">';
			include($ROOT .'/pages/inc/agenda.php');
			$content .= '</div>'; 
		}
	}

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>