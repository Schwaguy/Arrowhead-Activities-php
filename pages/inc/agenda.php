<?php

$periods = getPeriods('',false,false,'array',$con);
$weekActivities = getWeekActivities($weekID,$con);

$content .= '<div class="agenda">
        <div class="table-responsive">
            <table class="table table-condensed table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Period</th>
                        <th>Activities</th>
                    </tr>
                </thead>
                <tbody>';

for ($d=1;$d<=5;$d++) {
	$agenda = '';
	$date = date_create($row['startDate']);
	date_add($date, date_interval_create_from_date_string(($d-1) .' days'));
	$dayOfMonth = date_format($date,'d');
	$dayOfWeek = date_format($date,'l');
	$monthYear = date_format($date,'F, Y');
	//$dayArray = strtolower($dayOfWeek);
	$actArray = $weekActivities[strtolower($dayOfWeek)];
	
	// Show first Activity Period
	$count = 1;
	$p = (($periods[0]['days'][$d] == 1) ? 0 : 1);
	$agenda .= '<td class="agenda-time period">
                	'. $periods[$p]['name'] .'
					<div class="text-muted">'. date_format((date_create($row['startDate'] .' '. $periods[$p]['startTime'])),'g:iA')  .' - '. date_format((date_create($row['startDate'] .' '. $periods[$p]['endTime'])),'g:iA')  .'</div>
             	</td>
                <td class="agenda-events">';
	$agenda .= showAgendaActivities($weekID,$dayOfWeek,$actArray,$periods[$p]['id'],true,'');
	$agenda .= '<form class="agenda-form" method="post" action="/admin/activities/add/">
				<input type="hidden" name="week" value="'. $weekID .'"><input type="hidden" name="day" value="'. $d .'"><input type="hidden" name="period" value="'. $periods[$p]['id'] .'"><input type="submit" class="btn btn-light-green agenda-event-button" value="Add New Activity"></form>'; 
	$agenda .= '</td></tr>';
	
	// Show additional Activity Periods
	for ($r=$p+1;$r<=2;$r++) {
		if ($periods[$r]['days'][$d] == 1) {
			$agenda .= '<tr>
					<td class="agenda-time">
						'. $periods[$r]['name'] .'
						<div class="text-muted">'. date_format((date_create($row['startDate'] .' '. $periods[$r]['startTime'])),'g:iA')  .' - '. date_format((date_create($row['startDate'] .' '. $periods[$r]['endTime'])),'g:iA')  .'</div>
					</td>
					<td class="agenda-events">';
			$agenda .= showAgendaActivities($weekID,$dayOfWeek,$actArray,$periods[$r]['id'],true,'');
			$agenda .= '<form class="agenda-form" method="post" action="/admin/activities/add/">
				<input type="hidden" name="week" value="'. $weekID .'"><input type="hidden" name="day" value="'. $d .'"><input type="hidden" name="period" value="'. $periods[$r]['id'] .'"><input type="submit" class="btn btn-light-green agenda-event-button" value="Add New Activity"></form>'; 
			$agenda .= '</td></tr>';
			$count++;
		}
	}
	$content .= '<tr>
				<td class="agenda-date" class="active" rowspan="'. $count .'">
                	<div class="dayofmonth">'. $dayOfMonth .'</div>
                  	<div class="dayofweek">'. $dayOfWeek .'</div>
                  	<div class="shortdate text-muted">'. $monthYear .'</div>
              	</td>'. $agenda;
}
$content .= '</tbody>
            </table>
        </div>
    </div>';
?>