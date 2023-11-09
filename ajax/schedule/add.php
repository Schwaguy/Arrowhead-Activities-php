<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('PHPSESSID','userAuth');
$redirect = ''; 
if ($_POST) {
	foreach($_POST as $key => $value) {
		if (!in_array($key,$ignore)) {
			//echo $key .': '. $value .'<br>';
			if ($key == 'op') {
				$op = $value;
			} elseif ($key == 'table') {
				$table = $value;
			} elseif ($key == 'redirect') {
				$redirect = $value;
			} else {
				if ($key == 'user') {
					$fields['user'] = $value;
				} elseif ($key == 'updatedBy') {
					$fields['updatedBy'] = $value;
				} elseif (strpos($key,'schedule-') !== false) {
					$schInfo = explode('-',$key);
					$arrID = $schInfo[1] .'-'. $schInfo[2] .'-'. $schInfo[3];
					$schedules[$arrID] = $value;
					unset($key);
					unset($value);
				} elseif (strpos($key,'activity-') !== false) {
					$actInfo = explode('-',$key);
					
					// Get Activity Type
					$sql = "SELECT type, capacity, reg". $actInfo[2] ." AS regNumber FROM activities WHERE id=". $value ." LIMIT 1"; 
					$getType = mysqli_fetch_assoc(mysqli_query($con, $sql));
					
					if (($getType['regNumber'] >= $getType['capacity']) && (in_array($_POST['userAuth'],$camperAccessLevels))) {
						$activitiesFull[] = $value; 	
					} else {
						$activities[] = array(
							'id'=>$value,
							'week'=>$actInfo[1],
							'day'=>$actInfo[2],
							'period'=>$actInfo[3]
						);
						$uActNew[] = $getType['type'];
					}
					unset($key);
					unset($value);
				} elseif (($key == 'startTime') || ($key == 'endTime')) {
					$value = date('H:i:s', strtotime($today .' '. $value));
				} elseif (isDate($value)) {
					$value = date_format(date_create($value),'Y-m-d H:i:s');
				}  elseif (is_array($value)) {
					$value = implode(',',$value);
				} else {
					$value = mysqli_real_escape_string($con, $value);
				}
				if (isset($key)) {
					//echo $key .': '. $value .'<br>';
					$fields[$key] = $value; 
					$keys[] = $key;
					$values[] = "'". $value ."'";
				}
			}
		}
	}
	$uID = $fields['user'];
	
	if (count($activities)>0) {
		unset($schedulingWeek);
		foreach ($activities as $act) {
			$udKeys = $keys;
			$udValues = $values;
			$udKeys[] = 'activity';
			$udValues[] = $act['id'];
			$udKeys[] = 'week';
			$udValues[] = $act['week'];
			$udKeys[] = 'day';
			$udValues[] = "'". $act['day'] ."'";
			$udKeys[] = 'period';
			$udValues[] = $act['period'];
			$sql = "INSERT INTO activity_signups (". implode(',',$udKeys) .") VALUES (". implode(',',$udValues) .")";
			//echo '<p>'. $sql .'</p>';
			if ($result = $con->query($sql)) {
				// Update Activity Attendance
				$var = 'reg'. $act['day']; 
				$sql = "UPDATE activities SET ". $var ."=". $var ."+1 WHERE id=". $act['id']; 
				$result = $con->query($sql);
				$success = true;
			} else {
				$success = false;
			}
			unset($udKeys);
			unset($udValues);
			$schedulingWeek = 'week-'. $act['week'];
		}
	}
	
	if ($success) {
		// Check to see if current Year column exists in user_activities table and create if not
		$table = 'user_activities'; 
		$column = date('Y'); // Camp Year Column
		$sql = 'SHOW COLUMNS FROM '. $table;
		if ($result = $con->query($sql)) {
			while ($values = $result->fetch_array(MYSQLI_ASSOC)) {
				$column_names[] = $values['Field'];
			} 
		} else {
			$column_names = array();
		}
		$userActCurrent = array();
		if (in_array($column, $column_names)) {
			$sql = "SELECT * FROM ". $table ." WHERE user=". $fields['user']; 
			//echo '<p>SQL: '. $sql .'</p>';
			if ($result = $con->query($sql)) {
				$userActAll = $result->fetch_array(MYSQLI_ASSOC);
				$userActCurrent = explode(',',$userActAll[$column]);
			} else {
				$userActCurrent = array();	
			}
		} else {
			$sql = "ALTER TABLE `". $table ."` ADD `". $column ."` VARCHAR(255)";
			mysqli_query($con, $sql);
			$uActivities = array();
		}
		
		// Update Users Activities for year
		$userActivities = array_unique(array_merge($uActNew,$userActCurrent));
		if (isset($userActAll)) {	
			$sql = "UPDATE ". $table ." SET '". $column ."'='". implode(',',$userActivities) ."' WHERE user=". $fields['user'];
		} else {
			$sql = "INSERT INTO ". $table ." (`user`,`". $column ."`) VALUES ('". $fields['user'] ."','". implode(',',$userActivities) ."')";
		}
		$result = $con->query($sql);
		
		if (isset($activitiesFull)) {
			$redirect = $redirect .'#'. $schedulingWeek;
			$output = array('op'=>'add','feedback'=>'Some of the activities you selected are now full. Please try again.','full'=>$activitiesFull,'redirect'=>$redirect);
		} else {
			if ($_SESSION['userID'] != $uID) { $redirect = ''; }
			$output = array('op'=>'add','feedback'=>'Schedule Complete','redirect'=>$redirect);
		}
	} else {
		$output = array('op'=>'add','feedback'=>'SCHEDULE ERROR','redirect'=>'');
	}
} else {
	$output = array('op'=>'add','feedback'=>'ERROR','redirect'=>''); 	
}

//print_r($output);
//exit;

if ($con) $con->close();
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>