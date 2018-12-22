<?php

// Admin Buttons			
$projectMgmt = ''; 
if ($projectType == 1)
	$projectMgmt .= ' <a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project-payment" data-adminact="" data-editid="' . $projectID . '" title="Payments" data-page="dash" data-section="projects-current"><i class="fa fa-money"></i></a> '; 

$projectMgmt .= ' <a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project-task" data-adminact="" data-editid="' . $projectID . '"  title="Tasks" data-page="dash" data-section="projects-current"><i class="fa fa-check-square-o"></i></a>';

$projectMgmt .= ' <a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project-time" data-adminact="" data-editid="' . $projectID . '"  title="Hours" data-page="dash" data-section="projects-current"><i class="fa fa-clock-o"></i></a> ';
			
$projectMgmt .= ' <a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project" data-adminact="edit" data-editid="' . $projectID . '"  title="Edit Project" data-page="dash" data-section="projects-current"><i class="fa fa-pencil-square-o"></i></a> '; 
			
$projectsCurrent .= '<li class="list-group-item" id="project-'. $projectID .'">';
$projectsCurrent .= '<strong class="projectDue">'. $projDue .'</strong> &nbsp; <span class="client">'. $client .'</span> - <a href="#" class="admin" data-toggle="modal" data-target="#adminModal" data-admintype="project" data-adminact="edit" data-editid="' . $projectID . '" data-page="dash" data-section="projects-current"><span class="projectName">' . $projectName .'</span></a>';
$projectsCurrent .= '<span class="pull-right text-muted adminActions"><em>'. $projectMgmt .'</em></span>';
$projectsCurrent .= '</li>';

?>