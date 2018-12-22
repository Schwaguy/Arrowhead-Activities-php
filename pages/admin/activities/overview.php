<?php
	$content .= '<div class="container main">
		
		<div class="quick-buttons"><a href="/admin/activities/add/" class="btn btn-primary" type="button" aria-expanded="false" aria-controls="addActivity">Add New Activity</a></div>
		
		<h1 class="page-title">Arrowhead Club Activities</h1>
		
		<div class="row justify-content-md-center clearfix">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$query = "SELECT * FROM weeks WHERE active=1 ORDER BY name ASC";
	if($result = $con->query($query)) {
		$content .= '<div id="response"></div>'; 
		
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$weekID = $row['id'];
			$content .= '<div id="week-'. $weekID .'" class="list-group-item"><a href="#panel-'. $weekID .'" title="'. $row['name'] .'" data-toggle="collapse" data-target="#panel-'. $weekID .'" aria-expanded="false" aria-controls="panel-'. $weekID .'"><h3>'. $row['name'] .' Activities</h3></a></div>';
			$content .= '<div class="collapse" id="panel-'. $weekID .'">';
			include($ROOT .'/pages/inc/agenda.php');
			$content .= '</div>'; 
		}
		$content .= '</div><!-- /list-group -->';
	} 

$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>