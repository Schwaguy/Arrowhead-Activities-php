<?php

// If this script is being called via Ajax
if ($ajaxUpdate) {
	$admin = 'todo';
	$page = (isset($_POST['page']) ? $_POST['page'] : $admin);
	//$parentClients = getPclientArray($con);
	$variables = getVariables($admin,'dash');
	$sql = "SELECT todo.*, c.clientName, c.clientAbbr, p.projectName FROM todo_list todo LEFT JOIN clients c ON (todo.clientID = c.clientID) LEFT JOIN projects p ON (todo.projectID = p.projectID) WHERE todo.todoID=". $editID; 
	$res = $con->query($sql);
	$r = $res->fetch_array(MYSQLI_ASSOC);
	foreach($variables['displayArray'] as $value) 
		$$value =  $r[$value];
}

if(!empty($clientID)) {
	$client = getClientName($clientID,$con);
	if (!empty($clientAbbr)) 
		$client .= ' ('. $clientAbbr . ')';
} else {
	$client = ''; 
}
$dueDate = (((!empty($dueDate))||($dueDate != '0000-00-00')) ? Date::convert($dueDate,'Y-m-d','n/d/Y') : '--/--/----');	
			
$listItem = '<li class="list-group-item clearfix" id="todo-'. $todoID .'">';
$listItem .= '<input type="checkbox" name="completeToDos[]" value="'. $todoID .'" class="todoCheck" /> ';
$listItem .= '<a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="todo" data-adminact="edit" data-editid="' . $todoID . '" data-page="dash" data-section="todo-current" title="Edit To-Do">' . $todoName;
$listItem .= '<span class="pull-right text-muted small"><em>'. $dueDate .'</em></span>';
if (!empty($client))
	$listItem .= '<br><span class="clientName"><em>'. $client .'</em></span>';
$listItem .= '</a>';
$listItem .= '</li>';
	
?>