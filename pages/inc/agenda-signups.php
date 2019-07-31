<?php

$periods = getPeriods('',false,false,'array',$con);

$tableHead = ''; 
$tableBody = ''; 

for ($d=1;$d<=5;$d++) {
	$agenda = '';
	$startDate = $activity['startDate'];
	$date = date_create($startDate);
	date_add($date, date_interval_create_from_date_string(($d-1) .' days'));
	$dayOfMonth = date_format($date,'d');
	$dayOfWeek = date_format($date,'l');
	$monthYear = date_format($date,'F, Y');
	
	// Show first Activity Period
	$count = 1;
	$p = (($periods[1]['days'][$d] == 1) ? 1 : 2);
	
	for ($v=0;$v<count($periods);$v++) {
		if ((!isset($p)) && $v==$activity['period]']){
			$p = $v;
		}
	}
	
	$actSignups = '';  
	if ($periods[$p]['days'][$d]==1) {
		$campers = (is_array($signups[$d]) ? $signups[$d] : '');
		if (!empty($campers)) {
			$actSignups .= '<p class="text-muted"><em>Listed in signup order</em></p><ol class="signupList">';
			foreach ($campers as $camper) {
				$actSignups .= '<li><form method="post" action="/schedule-activities/">
					<input type="hidden" name="uID" value="'. $camper['user']['id'] .'">
					<input type="hidden" name="thisUserName" value="'. $camper['user']['username'] .'">
					<input type="hidden" name="bunkID" value="'. $camper['bunk']['id'] .'">
					<input type="hidden" name="weekID" value="'. $activity['week'] .'">
					<input type="hidden" name="startDate" value="'. $startDate .'">
					<input type="hidden" name="scheduleOp" value="edit">
					<a href="#" class="submitLink" title="Edit '. $camper['user']['firstName'] .'\'s Schedule" href="#">'. $camper['user']['id'] .' '. $camper['user']['lastName'] .', '. $camper['user']['firstName'] . ($camper['bunk']['name'] ? ' ('. $camper['bunk']['name'] .')' : '') .'</a>
				</form></li>';
			}
			$actSignups .= '</ol><!-- /signupList -->';
		} else {
			$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>';	
		}
	} else {
		$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>'; 
	}
	$tableBody .= '<td>'. $actSignups .'</td>';

	$tableHead .= '<th class="agenda-date"><div class="dayofmonth">'. $dayOfMonth .'</div><div class="dayofweek">'. $dayOfWeek .'</div><div class="shortdate text-muted">'. $monthYear .'</div></th>';
}
$content .= '<div class="agenda">
        <div class="table-responsive">
            <table class="table table-condensed table-bordered">
                <thead class="thead-dark">
                    <tr>'. $tableHead .'</tr>
                </thead>
                <tbody id="activity-signups"><tr>'. $tableBody .'</tr></tbody>
            </table>
        </div>
    </div>';

?>