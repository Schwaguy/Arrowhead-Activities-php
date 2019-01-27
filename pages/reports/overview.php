<?php
	$content .= '<div class="container main">
		
		<div class="quick-buttons"><a href="/admin/activities/add/" class="btn btn-primary" type="button" aria-expanded="false" aria-controls="addActivity">Add New '. siteVar('act','singular','capital') .'</a></div>
		
		<h1 class="page-title">Arrowhead Club '. siteVar('act','plural','capital') .'</h1>
		
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	
	$content .= '<div class="alert alert-success" role="alert">Click on the weeks below to add/edit '. siteVar('act','plural','capital') .'</div>';
	
	$weeks = getWeeks('array','',false,false,$con);
	if (is_array($weeks)) {
		foreach($weeks as $week) {
			$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">';
			$content .= '<div id="week-'. $week['id'] .'" class="list-group-item"><a href="#panel-'. $week['id'] .'" title="'. $week['name'] .'" data-toggle="collapse" data-target="#panel-'. $week['id'] .'" aria-expanded="false" aria-controls="panel-'. $week['id'] .'"><h3>'. $week['name'] .' '. siteVar('act','plural','capital') .'</h3></a></div>';
			$content .= '<div class="collapse" id="panel-'. $week['id'] .'">';
			include($ROOT .'/pages/inc/agenda.php');
			$content .= '</div>'; 
		}
	}

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>