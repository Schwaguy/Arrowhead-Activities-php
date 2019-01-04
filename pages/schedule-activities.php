<?php
	$content .= '<div class="container main">
		<h1 class="page-title">Schedule '. siteVar('act','plural','capital') .'</h1>
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$weeks =  getWeeks(false,'',false,false,$con);

	//$query = "SELECT * FROM weeks WHERE active=1 ORDER BY name ASC";
	//if($result = $con->query($query)) {
	foreach ($weeks as $week) {	
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		//$weekID = $_POST['weekID'];
		$weekID = $week['id'];
		include($ROOT .'/pages/inc/agenda-camper.php');
		$content .= '</div><!-- /list-group -->';
	} 

$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>