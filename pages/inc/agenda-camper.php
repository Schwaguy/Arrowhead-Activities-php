<?php

$allowAdmin = ($_SESSION['userPermissions']['edit'] ? true : false);
$periods = getPeriods('',false,false,'array',$con);
$weekActivities = getWeekActivities($weekID,$con);
$userInfo = checkUser($con);

$content .='<h2 class="text-center">'. getName($weekID,'weeks',$con) .' Available '. siteVar('act','plural','capital') .' for '. $userInfo['userName'] .'</h2>'; 

$scheduledActivities = getScheduledActivities($weekID,$userInfo['userID'],$con);
$formRows = ''; 
/*
foreach ($_POST as $k=>$v) {
	echo $k .': '. $v .'<br>';
}
*/
$actScheduled = 0;
for ($d=1;$d<=5;$d++) {
	$agenda = '';
	$startDate = $_POST['startDate'];
	$date = date_create($startDate);
	date_add($date, date_interval_create_from_date_string(($d-1) .' days'));
	$dayOfMonth = date_format($date,'d');
	$dayOfWeek = date_format($date,'l');
	$monthYear = date_format($date,'F, Y');
	//$dayArray = strtolower($dayOfWeek);
	
	$actArray = $weekActivities[strtolower($dayOfWeek)];
	
	if (!empty($scheduledActivities)) { 
		$schActivity = $scheduledActivities[$d];
		if (is_array( $schActivity)) { $actScheduled = $actScheduled+1; }
	} else {
		$schActivity =''; 
	}
	// Show first Activity Period
	$count = 1;
	if (isset($userInfo['bunkInfo'])) {
		
		$bunkGroup = $userInfo['bunkInfo']['group']; 
		
		for ($v=1;$v<=count($periods);$v++) {
			if ((!isset($p)) && (in_array($bunkGroup,$periods[$v]['groups']))) {
				$p = $v;
			}
		}
		$agenda .= '<td class="agenda-time period">
						'. $periods[$p]['name'] .'
						<div class="text-muted period-times">'. date_format((date_create($startDate .' '. $periods[$p]['startTime'])),'g:iA')  .' - '. date_format((date_create($startDate .' '. $periods[$p]['endTime'])),'g:iA')  .'</div>
					</td>
					<td class="agenda-events">';
		
		if ($periods[$p]['days'][$d]==1) {
			$available = showAgendaActivities($weekID,$dayOfWeek,$actArray,$periods[$p]['id'],false,$schActivity,$userInfo['userID'],$con);
			$agenda .= (!empty($available) ? $available : '<p class="text-muted"><em>No '. siteVar('act','plural','capital') .' Availabe Yet</em></p>');
		} else {
			$agenda .= '<p class="text-muted"><em>No '. siteVar('act','plural','capital') .' Availabe</em></p>'; 
		}
			
		$agenda .= '</td></tr>';

		
		//$agenda .= '<tr><td colspan="3">-</td></tr>';
		
		// Show additional Activity Periods
		for ($r=$p+1;$r<=3;$r++) {
			
			//$agenda .= '<tr><td colspan="3">-'. print_r($periods[$r]['groups']) .'-</td></tr>';
			
			if (in_array($bunkGroup,$periods[$r]['groups'])) {
				
				//$agenda .= '<tr><td colspan="3">---</td></tr>';
				
				if ($periods[$r]['days'][$d] == 1) {
					
					//$agenda .= '<tr><td colspan="3">----</td></tr>';
					
					$agenda .= '<tr>
							<td class="agenda-time">
								'. $periods[$r]['name'] .'
								<div class="text-muted period-times">'. date_format((date_create($startDate .' '. $periods[$r]['startTime'])),'g:iA')  .' - '. date_format((date_create($startDate .' '. $periods[$r]['endTime'])),'g:iA')  .'</div>
							</td>
							<td class="agenda-events">';
					if ($periods[$p]['days'][$d]==1) {
						$available = showAgendaActivities($weekID,$dayOfWeek,$actArray,$periods[$r]['id'],false,$schActivity,$userInfo['userID'],$con);
						$agenda .= (!empty($available) ? $available : '<p class="text-muted"><em>No '. siteVar('act','plural','capital') .' Availabe Yet</em></p>');
					} else {
						$agenda .= '<p class="text-muted"><em>No '. siteVar('act','plural','capital') .' Availabe</em></p>'; 
					}
					$agenda .= '</td></tr>';
					$count++;
				}
			}
		}
		$formRows .= '<tr>
					<td class="agenda-date active" rowspan="'. $count .'">
						<div class="dayofmonth">'. $dayOfMonth .'</div>
						<div class="dayofweek">'. $dayOfWeek .'</div>
						<div class="shortdate text-muted">'. $monthYear .'</div>
					</td>'. $agenda;
	}
}

$checkSchedule = checkSchedulDate($now,$week['signupStartDate'],'Scheduling for this week is not available yet',$week['signupEndDate'],'Scheduling for this week is closed. If you need to make any changes, please contact the camp office.');
$disable = $checkSchedule['disable'];
$tooltip = $checkSchedule['tooltip'];

if ($actScheduled>0) {
	$formName = 'updateScheduleForm';
	$buttonText = 'Update '. siteVar('act','singular','capital') .' Selections';
	$registered = '';
	$op = 'update';
} else {
	$formName = 'scheduleForm';
	$buttonText = 'Submit '. siteVar('act','singular','capital') .' Selections'; 
	$registered = '<input type="hidden" name="registered" value="'. $now .'">';
	$op = 'add';
}

$content .= '<div class="agenda">
        <div class="table-responsive">
			<form id="'. $formName .'" class="scheduleForm" name="'. $formName .'" method="post">
				<input type="hidden" name="user" value="'. $userInfo['userID'] .'">
				'. $registered .'
				<input type="hidden" name="userAuth" value="'. $_SESSION['userAuth'] .'"> 
				<input type="hidden" name="updated" value="'. $now .'">
				<input type="hidden" name="updatedBy" value="'. $_SESSION['userID'] .'">
				<input type="hidden" name="redirect" value="'. $redirect .'">
				<table class="table table-condensed table-bordered">
					<thead class="thead-dark">
						<tr class="no-mobile">
							<th>Date</th>
							<th>Period</th>
							<th>'. siteVar('act','plural','capital') .'</th>
						</tr>
					</thead>
					<tbody>';

$content .= $formRows;

$content .= '</tbody>
            	</table>
				<p class="text-center">
					<button type="button" class="btn btn-elegant scheduleBtn" data-op="'. $op .'" '. $disable .' '. $tooltip .'>'. $buttonText .'</button>
				</p>
			</form>
        </div>
    </div>';
?>