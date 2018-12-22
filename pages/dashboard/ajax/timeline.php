<?php
if (!isset($ROOT)) {
	global $ROOT, $PATH, $phpself;
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	$PATH = '../../../';  
	$phpself = basename(__FILE__);
	require_once($PATH . 'globals/globals.php');
	$ajax = true;
} else {
	$ajax = false;
}
	
$admintype = ($ajax ? ($_POST['admintype'] ? $_POST['admintype'] : '') : '');
$filter = ($ajax ? ($_POST['filter'] ? $_POST['filter'] : '') : '');
$page = ($ajax ? ($_POST['page'] ? $_POST['page'] : '') : '');
$section = ($ajax ? ($_POST['section'] ? $_POST['section'] : '') : '');

$repFilter = ' WHERE p.projectDisp=1 AND pt.timeDisp=1'; 
if ($_SESSION['userAuth'] == 3) 
	$repFilter .= " AND c.subClientOf='" . $_SESSION['clientID'] . "'";
if (!empty($filter)) {
	unset($dates);
	switch ($filter) {
		case 'today':
			$panelTitle = 'Today\'s Hours:';
			$dates = $today;
			break;
		case 'yesterday':
			$panelTitle = 'Yesterday\'s Hours:';
			$date = new DateTime();
			$date->add(DateInterval::createFromDateString('yesterday'));
			$dates = $date->format('Y-m-d');
			break;
		case 'this-week':
			$panelTitle = 'This Week\'s Hours:';
			$begin = date('Y-m-d',strtotime('monday this week'));
			$end = date('Y-m-d',strtotime('sunday this week'));
			$dates = array('begin'=>$begin,'end'=>$end);
			break;
		case 'last-week':
			$panelTitle = 'Last Week\'s Hours:';
			$begin = date('Y-m-d',strtotime('monday last week'));
			$end = date('Y-m-d',strtotime('sunday last week'));
			$dates = array('begin'=>$begin,'end'=>$end);
			break;
		case 'this-month':
			$panelTitle = 'This Month\'s Hours:';
			$begin = date('Y-m-d',strtotime('first day of this month'));
			$end = date('Y-m-d',strtotime('last day of this month'));
			$dates = array('begin'=>$begin,'end'=>$end);
			break;
		case 'last-month':
			$panelTitle = 'Last Month\'s Hours:';
			$begin = date('Y-m-d',strtotime('first day of last month'));
			$end = date('Y-m-d',strtotime('last day of last month'));
			$dates = array('begin'=>$begin,'end'=>$end);
			break;
		default:
			$panelTitle = 'Today\'s Hours:';
			$dates = $today;	
	}
	$repFilter .= (is_array($dates) ? " AND ((pt.timeDate>='". $dates['begin'] ."') AND (pt.timeDate<='". $dates['end'] ."'))" : " AND pt.timeDate='". $dates ."'");
} else {
	$panelTitle = 'Today\'s Hours:';
	$repFilter .= ' AND pt.timeDate="'. $today .'"';
}

$query = "SELECT pt.*, p.projectID, p.projectName, p.projectMaint, p.projectType, c.clientName, t.taskID, t.taskName FROM projectTime pt LEFT JOIN projects p ON (p.projectID = pt.projectID) LEFT JOIN clients c ON (p.clientID = c.clientID) LEFT JOIN tasks t ON (pt.taskID = t.taskID)". $repFilter ." ORDER BY pt.timeDate DESC, pt.updated DESC"; 
//echo $query;
$result = $con->query($query);
	
$timelineItems = ''; 
$totalHours = ''; 
if ($result) {
	$odd = true;
	$totalHours = ''; 
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$rlClass = (($odd)?'':' class="timeline-inverted"');
		
		$badge = ((($row['billable'] == 1) || ($row['projectMaint'] == 1)) ? '<i class="fa fa-usd"></i>' : (($row['projectType'] == 1) ? '<i class="fa fa-calendar-check-o"></i>' : '<i class="fa fa-check"></i>'));
		
		$badgeClass = (($row['billable'] == 1) ? 'success' : (($row['projectMaint'] == 1) || ($row['projectType'] == 1) ? 'info' : ''));
		
		$editLink = ((!empty($row['taskID'])) ? 'data-admintype="task-time" data-adminact="edit" data-editid="' . $row['taskID'] . '" title="Hours"' : 'data-admintype="project-time" data-adminact="" data-editid="' . $row['projectID'] . '" title="Hours"');
			
		$timeHeading = '';
		if (!empty($row['clientName'])) 
			$timeHeading .= $row['clientName'];
		if (!empty($timeHeading)) 
			$timeHeading .= ' - ';
		if (!empty($row['projectName'])) 
			$timeHeading .= $row['projectName'];
		if (!empty($timeHeading)) 
			$timeHeading .= '<br>';
		if (!empty($row['taskName'])) 
			$timeHeading .= '<em>'. $row['taskName'] .'</em>';
					
		$timelineItems .= '<li id="timeline-'. $row['timeID'] .'"'. $rlClass .'>
                       		<div class="timeline-badge '. $badgeClass .'">'. $badge .'</div>
               					<a href="#" class="timeline-panel admin" data-toggle="modal" data-target="#adminModal" '. $editLink .'>
                           			<div class="timeline-heading">
                           				<h4 class="timeline-title">' . $timeHeading . '</h4>
										<p><small class="text-muted"><i class="fa fa-clock-o"></i> ' . $row['timeAmount'] . ' hours</small> | <small class="text-muted"><i class="fa fa-calendar"></i> '. Date::convert($row['timeDate'], 'Y-m-d', 'D, M d, Y') .'</small></p>
                           			</div>
                               		<div class="timeline-body">
                               			<p>' . $row['timeDesc'] . '</p>
                               		</div>
                           		</a>
							</li>';
		$totalHours = $totalHours + $row['timeAmount'];  
		$odd = (($odd)?false:true);			
	}
}

$updateTitle = $panelTitle .' <span class="totalHours">'. $totalHours .'</span>'; 
$output = (($result) ? array('updateOP'=>$admintype,'updateTitle'=>$updateTitle,'updateString'=>$timelineItems,'section'=>$section) : 'UPDATE ERROR');

if ($ajax) {
	if ($con) $con->close();
	header('Content-Type: application/json; charset=utf-8', true); 
	echo json_encode(array("output"=>$output));
}
?>