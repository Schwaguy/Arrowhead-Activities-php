<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_REQUEST) {
	$customCSS = '@media print {'; 
	$camperID = ((isset($_REQUEST['camper'])) ? $_REQUEST['camper'] : '');
	$weekID = ((isset($_REQUEST['week'])) ? $_REQUEST['week'] : '');
	$bunkID = ((isset($_REQUEST['bunk'])) ? $_REQUEST['bunk'] : '');
	$activityID = ((isset($_REQUEST['activity'])) ? $_REQUEST['activity'] : '');
	$date = ((isset($_REQUEST['date'])) ? $_REQUEST['date'] : '');
	
	$title = ''; 
	$content = ''; 
	if (!empty($activityID)) {
		// Return Single Activity Signups
		$activity = getActivityinfo($activityID,$con);
		$signups = getActivitySignups($activityID,$con);
		$title = $activity['name'] .' - '. $activity['weekname'];
		$content .= '<div class="schedule-wrap print-page">';
		$content .= '<h2>'. $title .'</h2>';
		$content .= showActivitySignups($activity,'',$signups,$con);
		$content .= '</div>';
		$customCSS .= '@page { size: landscape !important; }';
	} elseif (!empty($date)) {
		// Return All Activity Signups for Specified Day 
		$day = date('l', strtotime($date));
		$activities = getDayActivities($_REQUEST['week'],$day,$con);
		$title = 'Activities for '. date('l, F jS Y', strtotime($date)); 
		foreach($activities as $activity) {
			$signups = getActivitySignups($activity['id'],$con);
			$content .= '<div class="schedule-wrap print-page">';
			$content .= '<h2>'. $activity['name'] .' - '. date('l, F jS Y', strtotime($date)) .'</h2>';
			$content .= showActivitySignups($activity['id'],$day,$signups,$con);
			$content .= '</div>';
		}
	} elseif (!empty($camperID)) {
		$camper = getUserInfo($camperID,$con);
		$periods = getPeriods('',false,false,'array',$con);	
		$bunkInfo = getBunkInfo($camper['bunk'],'',$con);
		
		if (!empty($weekID)) {
			// Return Camper  Activities for Single Week
			$week = getWeekInfo($weekID,$con);
			$title = $camper['firstName'] .' '. $camper['lastName'] .'\'s '. $week['name'] .' Activities'; 
			$content .= '<div class="schedule-wrap print-page">';
			$content .= '<h2>'. $title .'</h2>'; 
			$content .= '<div class="row d-none d-sm-flex p-1 bg-dark text-white">';
			foreach ($week['days'] as $day) {
				$content .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<span class="nicedate">'. $day['nicedate'] .'</span></h5></a>';
			}
			$content .= '</div>'; 
			$content .= showCamperSchedule($weekID,$camper,$week,false,$bunkInfo,$periods,$con);
			$content .= '</div>';
		} else {
			// Return Camper Activities for All Weeks
			$weeks = getWeeks('array','',false,false,$con);
			$title = $camper['firstName'] .' '. $camper['lastName'] .'\'s Activities';
			$content .= '<h1>'. $title .'</h1>';
			foreach ($weeks as $week) {
				$content .= '<div class="schedule-wrap">';
				$content .= '<h2>'. $week['name'] .'</h2>';
				$content .= '<div class="row d-none d-sm-flex p-1 bg-dark text-white">';
				foreach ($week['days'] as $day) {
					$content .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<div class="nicedate">'. $day['nicedate'] .'</h5></a>';
				}
				$content .= '</div>'; 
				$content .= showCamperSchedule($week['id'],$camper,$week,false,$bunkInfo,$periods,$con);
				$content .= '</div>';
			}
		}
	} elseif (!empty($bunkID)) {
		$campers = getBunkRoster($bunkID,'',$con);
		$periods = getPeriods('',false,false,'array',$con);	
		$bunkInfo = getBunkInfo($bunkID,'',$con);
		
		if (!empty($weekID)) {
			// Return Single Week Activities for all Campers in Bunk
			$week = getWeekInfo($weekID,$con);
			$title = $bunkInfo['name'] .' - '. $week ['name'];
			$content .= '<h2>'. $title .'</h2>';
			$content .= '<div class="row d-none d-sm-flex p-1 bg-dark text-white"><h5 class="col-sm p-1 text-center camper">Camper</h5>';
			foreach ($week['days'] as $day) {
				$content .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<div class="nicedate">'. $day['nicedate'] .'</h5></a>';
			}
			$content .= '</div>'; 
			if (is_array($campers)) {
				foreach ($campers as $camper) {
					$content .= '<div class="schedule-wrap">';
					$content .= showCamperSchedule($weekID,$camper,$week,true,$bunkInfo,$periods,$con);
					$content .= '</div>';
				}
			}
		} else {
			// Return Activities for All Weeks for All Campers in Bunk
			$weeks = getWeeks('array','',false,false,$con);
			$title = $bunkInfo['name'] .' Activities'; 
			foreach ($weeks as $week) {
				$content .= '<h2>'. $bunkInfo['name'] .' - '. $week['name'] .'</h2>';
				$content .= '<div class="row d-none d-sm-flex p-1 bg-dark text-white"><h5 class="col-sm p-1 text-center camper">Camper</h5>';
				foreach ($week['days'] as $day) {
					$content .= '<h5 class="col-sm p-1 text-center dayname">'. $day['name'] .'<div class="nicedate">'. $day['nicedate'] .'</h5></a>';
				}
				$content .= '</div>'; 
				if (is_array($campers)) {
					foreach ($campers as $camper) {
						$content .= '<div class="schedule-wrap">';
						$content .= showCamperSchedule($week['id'],$camper,$week,true,$bunkInfo,$periods,$con);
						$content .= '</div>';
					}
				}
			}
		}
	}
	$customCSS .= '}';
	
	$headerContent = '<title>'. $title .'</title>
		<link rel="stylesheet" href="/css/bootstrap.min.css" media="all">
		<link rel="stylesheet" href="/css/style.css" media="all"> 
		<style type="text/css">'. $customCSS .'</style>'; 
	
	$bodyContent = '<div class="container print">'. $content .'</div>'; 
	
	$reportContent = '<!doctype html><html><head><meta charset="utf-8">'. $headerContent .'</head>
	<body>'.$bodyContent .'</body></html>';
	
	$result = array('full'=>$reportContent,'header'=>$headerContent,'body'=>$bodyContent);
	$output = $result;
} else {
	$output = 'NO INFO'; 	
}

if ($con) $con->close(); 
if ($_POST) {
	header('Content-Type: application/json; charset=utf-8', true); 
	echo json_encode(array("output"=>$output));
} else {
	echo $reportContent;
	exit;
	end;
}

?>