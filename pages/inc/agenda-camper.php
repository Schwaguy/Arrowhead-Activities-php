<?php

$periods = getPeriods('',false,false,'array',$con);

// Get Activites for the week
/*$sql = 'SELECT * FROM activities WHERE week='. $weekID .' ORDER BY name';  
$monday = array();
$tuesday = array();
$wednesday = array();
$thursday = array();
$friday = array();
if ($res = $con->query($sql)) {
	while ($act=$res->fetch_array(MYSQLI_ASSOC)) {
		$monSpace = (($act['monday']) ? $act['capacity']-$act['regMonday'] : 0);
		$tuesSpace = (($act['tuesday']) ? $act['capacity']-$act['regTuesday'] : 0);
		$wedSpace = (($act['wednesday']) ? $act['capacity']-$act['regWednesday'] : 0);
		$thursSpace = (($act['thursday']) ? $act['capacity']-$act['regThursday'] : 0);
		$friSpace = (($act['friday']) ? $act['capacity']-$act['regFriday'] : 0);
		$activity = array(
			'id'=>$act['id'],
			'name'=>$act['name'],
			'week'=>$act['week'],
			'period'=>$act['period'],
			'space'=>array(
				'Monday'=>$monSpace,
				'Tuesday'=>$tuesSpace,
				'Wednesday'=>$wedSpace,
				'Thursday'=>$thursSpace,
				'Friday'=>$friSpace,
			)
		);
		if ($act['monday']) { $monday[] = $activity; }
		if ($act['tuesday']) { $tuesday[] = $activity; }
		if ($act['wednesday']) { $wednesday[] = $activity; }
		if ($act['thursday']) { $thursday[] = $activity; }
		if ($act['friday']) { $friday[] = $activity; }
	}
}*/
$weekActivities = getWeekActivities($weekID,$con);

$content .='<h2 class="text-center">'. getName($weekID,'weeks',$con) .' Available Activities</h2>'; 
$content .= '<div class="agenda">
        <div class="table-responsive">
			<form id="scheduleForm" class="scheduleForm" name="scheduleForm" method="post">
				<input type="hidden" name="user" value="'. $_SESSION['userID'] .'">
				<input type="hidden" name="registered" value="'. $now .'">
				<input type="hidden" name="updated" value="'. $now .'">
				<input type="hidden" name="updatedBy" value="'. $_SESSION['userID'] .'">
				<input type="hidden" name="redirect" value="/my-activities/">
				<table class="table table-condensed table-bordered">
					<thead class="thead-dark">
						<tr>
							<th>Date</th>
							<th>Period</th>
							<th>Activities</th>
						</tr>
					</thead>
					<tbody>';

$scheduledActivities = getScheduledActivities($weekID,$_SESSION['userID'],$con);
// For Testing
/*if (isset($scheduledActivities)) {
	$i = 1;
	foreach ($scheduledActivities as $scheduled) {
		$content .= '<tr><td colspan="3">$scheduledActivities[Day: '. $i .'] = array(<br>';
		foreach($periods as $period) {
			if (isset($scheduled[$period['id']])) {
				$content .= 'Period: '. $period['id'] .'=> activity '. $scheduled[$period['id']] .',<br>';
			}
		}
		$content .= ')</td></tr>';
		$i++;
	}
}*/

//######################################## HERE  - Now need to highlight seklected activities


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
	
	$schActivity = ((isset($scheduledActivities)) ? $scheduledActivities[$d] : '');
	
	// Show first Activity Period
	$count = 1;
	if (isset($_SESSION['bunkInfo'])) {
		for ($v=0;$v<count($periods);$v++) {
			if ((!isset($p)) && (in_array($_SESSION['bunkInfo']['group'],$periods[$v]['groups']))) {
				$p = $v;
			}
		}
		$agenda .= '<td class="agenda-time period">
						'. $periods[$p]['name'] .'
						<div class="text-muted">'. date_format((date_create($startDate .' '. $periods[$p]['startTime'])),'g:iA')  .' - '. date_format((date_create($startDate .' '. $periods[$p]['endTime'])),'g:iA')  .'</div>
					</td>
					<td class="agenda-events">';
		
		if ($periods[$p]['days'][$d]==1) {
			$available = showAgendaActivities($weekID,$dayOfWeek,$actArray,$periods[$p]['id'],false,$schActivity);
			$agenda .= (!empty($available) ? $available : '<p class="text-muted"><em>No Activities Availabe Yet</em></p>');
		} else {
			$agenda .= '<p class="text-muted"><em>No Activities Availabe</em></p>'; 
		}
			
		$agenda .= '</td></tr>';

		// Show additional Activity Periods
		for ($r=$p+1;$r<=2;$r++) {
			if (in_array($_SESSION['bunkInfo']['group'],$periods[$r]['groups'])) {
				if ($periods[$r]['days'][$d] == 1) {
					$agenda .= '<tr>
							<td class="agenda-time">
								'. $periods[$r]['name'] .'
								<div class="text-muted">'. date_format((date_create($startDate .' '. $periods[$r]['startTime'])),'g:iA')  .' - '. date_format((date_create($startDate .' '. $periods[$r]['endTime'])),'g:iA')  .'</div>
							</td>
							<td class="agenda-events">';
					if ($periods[$p]['days'][$d]==1) {
						$available = showAgendaActivities($weekID,$dayOfWeek,$$dayArray,$periods[$r]['id'],false,$schActivity);
						$agenda .= (!empty($available) ? $available : '<p class="text-muted"><em>No Activities Availabe Yet</em></p>');
					} else {
						$agenda .= '<p class="text-muted"><em>No Activities Availabe</em></p>'; 
					}
					$agenda .= '</td></tr>';
					$count++;
				}
			}
		}
		$content .= '<tr>
					<td class="agenda-date" class="active" rowspan="'. $count .'">
						<div class="dayofmonth">'. $dayOfMonth .'</div>
						<div class="dayofweek">'. $dayOfWeek .'</div>
						<div class="shortdate text-muted">'. $monthYear .'</div>
					</td>'. $agenda;
	}
}
$disable = (($_POST['scheduleOp'] == 'edit') ? ($_SESSION['userPermissions']['edit'] ? '' : 'disabled="disabled"') : '');
$content .= '</tbody>
            	</table>
				<p class="text-center">
					<button type="button" class="btn btn-elegant scheduleBtn" data-op="add" '. $disable .'>Submit Acticvity Selections</button>
				</p>
			</form>
        </div>
    </div>';
?>