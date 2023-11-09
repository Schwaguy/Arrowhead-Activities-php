<?php
	$content .= '<div class="container main">
		<h1 class="page-title">Schedule '. siteVar('act','plural','capital') .'</h1>
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';
	
	$redirect = (isset($_POST['redirect']) ? $_POST['redirect'] : '/');
	$weeks = getWeeks('array','',false,false,$con);
	
/*
	foreach ($_POST as $k=>$v) {
		echo $k .': '. $v .'<br>';
	}*/


	if (isset($_POST['weekID'])) {
		$week = $weeks[$_POST['weekID']];
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$weekID = $week['id'];
		include($ROOT .'/pages/inc/agenda-camper.php');
		$content .= '</div><!-- /list-group -->';
	} else {
		foreach ($weeks as $week) {	
			$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
			$weekID = $week['id'];
			include($ROOT .'/pages/inc/agenda-camper.php');
			$content .= '</div><!-- /list-group -->';
		}
	}
		
	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>