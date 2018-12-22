<?php

// If this script is being called via Ajax
if ($ajaxUpdate) {
	$admin = 'task';
	$page = (isset($_POST['page']) ? $_POST['page'] : $admin);
	//$parentClients = getPclientArray($con);
	$variables = getVariables($admin,$page);
	$sql = "SELECT *, p.clientID FROM tasks t LEFT JOIN projects p ON (t.projectID = p.projectID) LEFT JOIN taskStatus ts ON (t.taskStatus = ts.taskStatID) WHERE t.taskID=". $editID;
	$res = $con->query($sql);
	$r = $res->fetch_array(MYSQLI_ASSOC);
	foreach($variables['displayArray'] as $value) 
		$$value =  $r[$value];
} else {
	$variables = getVariables('task','dash');
	foreach ($variables['displayArray'] as $value)
		$$value = $row[$value];
}

$client = getClientName($clientID,$con);
if ( (!empty($dueDate)) || ($dueDate != '0000-00-00') )	
	$dueDate = Date::convert($dueDate, 'Y-m-d', 'n/d/Y');
else
	$dueDate = 'N/A';

$listItem = '<a href="#" id="task-' . $taskID . '" class="admin list-group-item clearfix" data-toggle="modal" data-target="#adminModal" data-admintype="task-time" data-adminact="" data-page="dash" data-editid="' . $taskID . '" title="Hours"><i class="fa fa-clock-o"></i> '. $taskName;
if (!empty($client))
	$listItem .= '<span class="clientName"> - '. $client .'</span>';
$listItem .= '<span class="pull-right text-muted small"><em>'. $dueDate .'</em></span>';
$listItem .= '</a>';
	
?>