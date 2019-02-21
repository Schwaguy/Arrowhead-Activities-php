<?php

$periods = getPeriods('',false,false,'array',$con);
$weekActivities = getWeekActivities($week['id'],$con);

$content .= '<div class="agenda">
        <div class="table-responsive">
            <table class="table table-condensed table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Period</th>
                        <th>'. siteVar('act','plural','capital') .'</th>
                    </tr>
                </thead>
                <tbody>';

for ($d=1;$d<=5;$d++) {
	$agenda = '';
	$date = date_create($week['startDate']);
	date_add($date, date_interval_create_from_date_string(($d-1) .' days'));
	$dayOfMonth = date_format($date,'d');
	$dayOfWeek = date_format($date,'l');
	$monthYear = date_format($date,'F, Y');
	//$dayArray = strtolower($dayOfWeek);
	$actArray = $weekActivities[strtolower($dayOfWeek)];
	
	// Show first Activity Period
	$count = 1;
	$p = (($periods[1]['days'][$d] == 1) ? 1 : 2);
	$agenda .= '<td class="agenda-time period">
                	'. $periods[$p]['name'] .'
					<div class="text-muted">'. date_format((date_create($week['startDate'] .' '. $periods[$p]['startTime'])),'g:iA')  .' - '. date_format((date_create($week['startDate'] .' '. $periods[$p]['endTime'])),'g:iA')  .'</div>
             	</td>
                <td class="agenda-events">';
	$agenda .= showAgendaActivities($week['id'],$dayOfWeek,$actArray,$periods[$p]['id'],true,'','',$con);
	$agenda .= '<form class="agenda-form" method="post" action="/admin/activities/add/">
				<input type="hidden" name="week" value="'. $week['id'] .'"><input type="hidden" name="day" value="'. $d .'"><input type="hidden" name="period" value="'. $periods[$p]['id'] .'"><input type="submit" class="btn btn-light-green agenda-event-button" value="Add New Activity"></form>'; 
	$agenda .= '</td></tr>';
	
	// Show additional Activity Periods
	for ($r=$p+1;$r<=count($periods);$r++) {
		if ($periods[$r]['days'][$d] == 1) {
			$agenda .= '<tr>
					<td class="agenda-time">
						'. $periods[$r]['name'] .'
						<div class="text-muted">'. date_format((date_create($week['startDate'] .' '. $periods[$r]['startTime'])),'g:iA')  .' - '. date_format((date_create($week['startDate'] .' '. $periods[$r]['endTime'])),'g:iA')  .'</div>
					</td>
					<td class="agenda-events">';
			$agenda .= showAgendaActivities($week['id'],$dayOfWeek,$actArray,$periods[$r]['id'],true,'','',$con);
			$agenda .= '<form class="agenda-form" method="post" action="/admin/activities/add/">
				<input type="hidden" name="week" value="'. $week['id'] .'"><input type="hidden" name="day" value="'. $d .'"><input type="hidden" name="period" value="'. $periods[$r]['id'] .'"><input type="submit" class="btn btn-light-green agenda-event-button" value="Add New '. siteVar('act','singular','capital') .'"></form>'; 
			$agenda .= '</td></tr>';
			$count++;
		}
	}
	$content .= '<tr>
				<td class="agenda-date" class="active" rowspan="'. $count .'">
                	<div class="dayofmonth">'. $dayOfMonth .'</div>
                  	<div class="dayofweek">'. $dayOfWeek .'</div>
                  	<div class="shortdate text-muted">'. $monthYear .'</div>';
				if ($_SESSION['userPermissions']['report'] == 1) {
					$content .= '<a class="btn btn-light printLink" data-camper="" data-week="'. $week['id'] .'" data-activity="" data-date="'. $date->format('Y-m-d') .'">Print Signups</a>';
				}
	$content .= '</td>'. $agenda;
}
$content .= '</tbody>
            </table>
        </div>
    </div>';
?>