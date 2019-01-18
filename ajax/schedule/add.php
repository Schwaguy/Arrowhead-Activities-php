<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('PHPSESSID');
$redirect = ''; 
if ($_POST) {
	foreach($_POST as $key => $value) {
		if (!in_array($key,$ignore)) {
			if ($key == 'op') {
				$op = $value;
			} elseif ($key == 'table') {
				$table = $value;
			} elseif ($key == 'redirect') {
				$redirect = $value;
			} else {
				if (strpos($key,'schedule-') !== false) {
					$schInfo = explode('-',$key);
					$arrID = $schInfo[1] .'-'. $schInfo[2] .'-'. $schInfo[3];
					$schedules[$arrID] = $value;
					unset($key);
					unset($value);
				} elseif (strpos($key,'activity-') !== false) {
					$actInfo = explode('-',$key);
					$activities[] = array(
						'id'=>$value,
						'week'=>$actInfo[1],
						'day'=>$actInfo[2],
						'period'=>$actInfo[3]
					);
					
					// Get Activity Type
					$sql = "SELECT type FROM activities WHERE id=". $value ." LIMIT 1"; 
					$getType = mysqli_fetch_assoc(mysqli_query($con, $sql));
					$uActNew[] = $getType['type'];

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
					$fields[$key] = $value; 
					$keys[] = $key;
					$values[] = "'". $value ."'";
				}
			}
		}
	}
	$uID = $fields['user'];
	
	if (count($activities)>0) {
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
		}
	}
	
	if ($success) {
		// Check to see if current Year column exists in user_activities table and create if not
		$table = 'user_activities'; 
		$column = date('Y'); // Camp Year Column
		$sql = 'SHOW COLUMNS FROM '. $table;
		if ($result = $con->query($sql)) {
			while ($values = $result->fetch_array(MYSQLI_ASSOC)) {
				$column_names[] = $values['field'];
			} 
		} else {
			$column_names = array();
		}
		$userActCurrent = array();
		if (in_array($column, $column_names)) {
			$sql = "SELECT * FROM ". $table ." WHERE user=". $fields['user']; 
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
		
		$output = array('op'=>'add','feedback'=>'Addition Complete','redirect'=>$redirect);
	} else {
		$output = array('op'=>'add','feedback'=>'ADDITION ERROR','redirect'=>'');
	}
} else {
	$output = array('op'=>'add','feedback'=>'ERROR','redirect'=>''); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>