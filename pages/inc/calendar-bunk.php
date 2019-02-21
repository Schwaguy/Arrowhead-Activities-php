<?php

$periods = getPeriods('',false,false,'array',$con);
$weeks = getWeeks('array','',false,false,$con);
$bunk = (isset($_POST['bunk']) ? $_POST['bunk'] : (!empty($_SESSION['userBunk']) ? $_SESSION['userBunk'] : 0));
$counselor = (isset($_POST['counselor']) ? $_POST['counselor'] : (!empty($_SESSION['userID']) ? $_SESSION['userID'] : 0));
$campers = getBunkRoster($bunk,$counselor,$con);
$bunkInfo = (isset($bunkInfo) ? $bunkInfo : (is_array($_SESSION['bunkInfo']) ? $_SESSION['bunkInfo'] : array()));

$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">';
foreach ($weeks as $week) {
	$header = '<div id="week-'. $week['id'] .'" class="list-group-item"><div class="row">';
	$header .= '<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6"><a href="#panel-'. $week['id'] .'" title="'. $week['name'] .'" data-toggle="collapse" data-target="#panel-'. $week['id'] .'" aria-expanded="false" aria-controls="panel-'. $week['id'] .'"><h2>'. $week['name'] .'</h2></a></div>';
	if ($_SESSION['userPermissions']['report'] == 1) {
		$header .= '<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"><a class="btn btn-light printLink" data-week="'. $week['id'] .'" data-bunk="'. $bunk .'">Print Bunk Schedule</a></div>'; 
	}
	$header .= '</div></div>';
	
	$header .= '<div class="collapse" id="panel-'. $week['id'] .'">
		<div class="row d-none d-sm-flex p-1 bg-dark text-white">
			<h5 class="col-sm p-1 text-center camper">Camper</h5>';
	
	foreach ($week['days'] as $day) {
		$header .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<div class="nicedate">'. $day['nicedate'] .'</h5></a>';
	}
	
	$header .= '</div>'; 
	
	$weekdays = '';
	if (is_array($campers)) {
		foreach ($campers as $camper) {
			//if (in_array($week['id'],(explode(',',$camper['week'])))) {
				$weekdays .= '<div class="row border border-right-0 border-bottom-0">';
				$weekdays .= '<div class="day col-sm p-2 border border-left-0 border-top-0">
								<h5 class="camper-name">'. $camper['lastName'] .', '. $camper['firstName'] .'</h5>';
				if ($_SESSION['userPermissions']['report'] == 1) {
					$weekdays .= '<a class="btn btn-light printLink" data-camper="'. $camper['id'] .'" data-week="'. $week['id'] .'" data-bunk="'. $camper['bunk'] .'">Print Schedule</a>';
				}
			
				$weekdays .= '</div>';

				$scheduledActivities = showScheduledActivities($week['id'],$camper['id'],$camper['prerequisites'],$con);

				$d = 1;
				foreach ($week['days'] as $day) {
					$schActivity = ((isset($scheduledActivities)) ? $scheduledActivities[$d] : '');

					$weekdays .= '<div class="day col-sm p-2 border border-left-0 border-top-0 text-truncate">
									<h5 class="row align-items-center">
										<span class="date col-1 d-sm-none nicedate">'. $day['nicedate'] .'</span>
										<small class="col d-sm-none text-center text-muted dayname">'. $day['name'] .'</small>
										<span class="col-1"></span>
									</h5>';

					foreach ($periods as $period) {
						if (in_array($bunkInfo['group'],$period['groups'])) {
							if ($period['days'][$day['number']]==1) {
								$weekdays .= '<div class="period"><h6>'. $period['name'] .'</h6>';
								if (!empty($schActivity[$period['id']])) {
									$disable = ($_SESSION['userPermissions']['edit'] ? '' : 'disabled="disabled"');
									$disable = (($_SESSION['userPermissions']['schedule'] != 1) ? 'disabled="disabled"' : $disable); 
									$weekdays .= '<form method="post" class="scheduled-activity" action="/schedule-activities/">
										<input type="hidden" name="uID" value="'. $camper['id'] .'">
										<input type="hidden" name="thisUserName" value="'. $camper['firstName'] .' '. $camper['lastName'] .'">
										<input type="hidden" name="bunkID" value="'. $camper['bunk'] .'">
										<input type="hidden" name="weekID" value="'. $week['id'] .'">
										<input type="hidden" name="redirect" value="'. $redirect .'">
										<input type="hidden" name="scheduleOp" value="edit">
										<input type="hidden" name="day" value="'. $d .'">
										<input type="hidden" name="period" value="'. $period['id'] .'">
										<input type="hidden" name="startDate" value="'. $week['days'][0]['date'] .'">
										<input type="submit" class="event btn btn-block btn-light-green agenda-event-button d-block" value="'. $schActivity[$period['id']]['name'] .'" '. $disable .'>
									</form>';
								} else {
									if ($_SESSION['userPermissions']['schedule'] == 1) {
										$weekdays .= '<form method="post" action="/schedule-activities/">
											<input type="hidden" name="uID" value="'. $camper['id'] .'">
											<input type="hidden" name="thisUserName" value="'. $camper['firstName'] .' '. $camper['lastName'] .'">
											<input type="hidden" name="bunkID" value="'. $camper['bunk'] .'">
											<input type="hidden" name="weekID" value="'. $week['id'] .'">
											<input type="hidden" name="redirect" value="'. $redirect .'">
											<input type="hidden" name="scheduleOp" value="add">
											<input type="hidden" name="day" value="'. $d .'"><input type="hidden" name="period" value="'. $period['id'] .'">
											<input type="hidden" name="startDate" value="'. $week['days'][0]['date'] .'">
											<input type="submit" class="btn btn-light agenda-event-button" value="Click to Schedule">
										</form>';
									} else {
										$weekdays .= '<span class="btn btn-light agenda-event-button disabled">Not Scheduled</span>';
									}
								}
								$weekdays .= '</div><!-- /period -->';
							}
						}
					} // foreach period
					$weekdays .= '</div><!-- /day -->';
					$d++;
				} // foreach day
				$weekdays .= '</div><!-- /camper row -->';
			//}
		} // foreach camper
	} else {
		$weekdays .= '<div class="row border border-right-0 border-bottom-0">';
		$weekdays .= '<div class="col-xl p-2 border border-left-0 border-top-0 text-truncate"><p class="text-muted"><em>No Campers Yet</em></p></div>';	
		$weekdays .= '</div><!-- /camper row -->';
	}
	$weekdays .= '</div><!-- /collapse panel-'. $week['id'] .' -->';
	$content .= '<div class="container-fluid">'. $header . $weekdays .'</div><!-- /week-view -->';
}
$content .= '</div><!-- /list-group-edit -->';

?>