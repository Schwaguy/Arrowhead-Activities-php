<?php

if ($_REQUEST['type'] == 'project') {
	$report = 'project'; 
	$pageTitle = 'Project Billing Report'; 
	include('reports/inc/project.php');
} elseif ($_REQUEST['type'] == 'hourly') { 
	$report = 'hourly';
	$pageTitle = 'Hourly Billing Report';  
	include('reports/inc/hourly.php');
} elseif ($_REQUEST['type'] == 'ongoing') { 
	$report = 'ongoing'; 
	$pageTitle = 'Ongoing Fee Reports';  
	include('reports/inc/ongoing.php');
} elseif ($_REQUEST['type'] == 'summary') {
	$report = 'summary';
	$pageTitle = ''; 
	include('reports/inc/summary.php');
} elseif ($_REQUEST['type'] == 'hours') {
	$report = 'hours';
	$pageTitle = 'Hourly Project Billing Report'; 
	include('reports/inc/project_hours.php');
}

?>