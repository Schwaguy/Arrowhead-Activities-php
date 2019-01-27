<?php

$periods = getPeriods('',false,false,'array',$con);
$weeks = getWeeks('array','',false,false,$con);
$activityWeeks = ''; 
if (!isset($userInfo)) { $userInfo = checkUser($con); }

foreach ($weeks as $week) {
	//if (in_array($week['id'],$_SESSION['userWeeks'])) {
	if (($now >= $week['signupStartDate']) || (in_array($_SESSION['userAuth'],$adminAccessLevels))) {
		//$scheduledActivities = showScheduledActivities($week['id'],$userInfo['userID'],$_SESSION['userPrereqs'],$con);
		$scheduledActivities = showScheduledActivities($week['id'],$userInfo['userID'],$userInfo['userInfo']['prerequisites'],$con);		
		$weekdays = '';

		$header = '<header><h4 class="display-4 mb-1 text-center">'. $week['name'] .'</h4><div class="row d-none d-sm-flex p-1 bg-dark text-white">';
		$weekdays .= '<div class="row border border-right-0 border-bottom-0">';
		$d = 1;
		foreach ($week['days'] as $day) {
			$schActivity = ((isset($scheduledActivities)) ? $scheduledActivities[$d] : '');
			$header .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<div class="nicedate">'. $day['nicedate'] .'</div></h5>'; 
			$weekdays .= '<div class="day col-sm p-2 border border-left-0 border-top-0 text-truncate">
							<h5 class="row align-items-center">
								<span class="date col-1 d-sm-none nicedate">'. $day['nicedate'] .'</span>
								<small class="col d-sm-none text-center text-muted dayname">'. $day['name'] .'</small>
								<span class="col-1"></span>
							</h5>';

			foreach ($periods as $period) {
				if ((isset($userInfo['bunkInfo'])) && (in_array($userInfo['bunkInfo']['group'],$period['groups']))) {
					if ($period['days'][$day['number']]==1) {
						$weekdays .= '<div class="period"><h6>'. $period['name'] .'</h6>';
						$activityScheduled = false; 	
							
						$disable = (($_SESSION['userPermissions']['edit'] || ($now <= $week['signupEndDate'])) ? '' : 'disabled="disabled"');
								
						$tooltip = (($now>$week['signupEndDate']) ? 'data-toggle="tooltip" data-placement="top" title="Scheduling for this week is closed"' : '');
							
						if (!empty($schActivity[$period['id']])) {
							$checkSchedule = checkSchedulDate($now,$week['signupStartDate'],'Scheduling for this week is not available yet',$week['signupEndDate'],'Scheduling for this week is closed');
							$disable = $checkSchedule['disable'];
							$tooltip = $checkSchedule['tooltip'];
								
							$weekdays .= '<form method="post" class="scheduled-activity" action="/schedule-activities/">';
							if ($camperAdmin) {
								$weekdays .= '<input type="hidden" name="uID" value="'. $userInfo['userID'] .'">';
								$weekdays .= '<input type="hidden" name="thisUserName" value="'. $userInfo['userInfo']['firstName'] .' '. $userInfo['userInfo']['lastName'] .'">';
								$weekdays .= '<input type="hidden" name="bunkID" value="'. $userInfo['bunkInfo']['id'] .'">';
							} else { 
								$weekdays .='<input type="hidden" name="user" value="'. $userInfo['userID'] .'">';
							}
							$weekdays .= '<input type="hidden" name="weekID" value="'. $week['id'] .'">
								<input type="hidden" name="redirect" value="'. $redirect .'">
								<input type="hidden" name="scheduleOp" value="edit">
								<input type="hidden" name="day" value="'. $d .'">
								<input type="hidden" name="period" value="'. $period['id'] .'">
								<input type="hidden" name="startDate" value="'. $week['days'][0]['date'] .'">
								<input type="submit" class="event btn btn-block btn-light-green agenda-event-button d-block" value="'. $schActivity[$period['id']]['name'] .'" '. $disable .' '. $tooltip .'>
							</form>';
						} else {
							$weekdays .= '<form method="post" action="/schedule-activities/">';
							if ($camperAdmin) {
								$weekdays .= '<input type="hidden" name="uID" value="'. $userInfo['userID'] .'">';
								$weekdays .= '<input type="hidden" name="thisUserName" value="'. $userInfo['userInfo']['firstName'] .' '. $userInfo['userInfo']['lastName'] .'">';
								$weekdays .= '<input type="hidden" name="bunkID" value="'. $userInfo['bunkInfo']['id'] .'">';
							} else { 
								$weekdays .='<input type="hidden" name="user" value="'. $userInfo['userID'] .'">';
							}
							$weekdays .= '<input type="hidden" name="weekID" value="'. $week['id'] .'">
								<input type="hidden" name="redirect" value="'. $redirect .'">
								<input type="hidden" name="scheduleOp" value="add">
								<input type="hidden" name="day" value="'. $d .'">
								<input type="hidden" name="period" value="'. $period['id'] .'">
								<input type="hidden" name="startDate" value="'. $week['days'][0]['date'] .'">
								<input type="submit" class="btn btn-light agenda-event-button" value="Click to Schedule '. siteVar('act','plural','capital') .'" '. $disable .' '. $tooltip .'>
							</form>';
						}
						$weekdays .= '</div>';
					}
				}
			}
			$weekdays .= '</div>';
			$d++;
		}
		$header .= '</div></header>'; 
		$weekdays .= '</div>';
	} else {
		$header = '<header><h4 class="display-4 mb-1 text-center">'. $week['name'] .'</h4></header>';
		$signupDates[$week['id']] = $week['signupStartDate'];
		$startDate =  strtotime($week['signupStartDate']);
		$weekdays = '<h5 class="text-muted text-center"><em>Scheduling for '. $week['name'] .' will be available on '. date('l, F jS',$startDate) .' at '. date('g:ia',$startDate) .'</em></h5>'; 
	}
	$activityWeeks .= '<div class="container-fluid week-view">'. $header . $weekdays .'</div><!-- /week-view -->';
}

$content .= ($activityWeeks ? $activityWeeks : '<h2 class="text-muted"><em>No Scheduling is available yet.  Please check back.</em></h2>'); 

?>