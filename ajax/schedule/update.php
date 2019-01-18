<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$success = false;
$redirect = ''; 
if ($_REQUEST) {
	foreach($_REQUEST as $key => $value) {
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
				$arrID = $actInfo[1] .'-'. $actInfo[2] .'-'. $actInfo[3];
				$activities[$arrID] = array(
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
				$value = date_format(date_create($value),'Y-m-d');
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
	$uID = $fields['user'];
	
	if (count($activities)>0) {
		foreach ($activities as $k => $act) {
			$actID = $schedules[$k];
			$udKeys = $keys;
			$udValues = $values;
			$udKeys[] = 'id';
			$udValues[] = $actID;
			$udKeys[] = 'activity';
			$udValues[] = $act['id'];
			$udKeys[] = 'week';
			$udValues[] = $act['week'];
			$udKeys[] = 'day';
			$udValues[] = "'". $act['day'] ."'";
			$udKeys[] = 'period';
			$udValues[] = $act['period'];
			
			// Get existing activity ID
			$sql = 'SELECT activity FROM activity_signups WHERE id='. $actID .' LIMIT 1'; 
			$getAct = mysqli_fetch_assoc(mysqli_query($con, $sql));
			
			if ($getAct) {
				$oldActID = $getAct['activity'];
				if ($oldActID != $act['id']) {
					
					// if old act is one time
					$oneTime = checkOneTimeAct($typeID,$con);
					
					// Update Activity Signup by ID
					$sql = "REPLACE INTO activity_signups (". implode(',',$udKeys) .") VALUES (". implode(',',$udValues) .")";
					
					if ($result = $con->query($sql)) {
						$success = true;
						$var = 'reg'. $act['day']; 
						
						// Adjust Old Activity Attendance
						$sql = "UPDATE activities SET ". $var ."=". $var ."-1 WHERE id=". $oldActID;
						$result = $con->query($sql);

						// Adjust New Activity Attendance
						$sql = "UPDATE activities SET ". $var ."=". $var ."+1 WHERE id=". $act['id'];
						$result = $con->query($sql);
					} else {
						$success = false;
					}
				}
			}
			unset($udKeys);
			unset($udValues);
		}
		
		if ($success) {
			// Check and Adjust User Activity Record
			$column = date('Y'); // Camp Year Column
			$sql = "SELECT `". $column ."` FROM user_activities WHERE user=". $uID; 
			if ($result = $con->query($sql)) {
				$userActAll = $result->fetch_array(MYSQLI_ASSOC);
				$userActCurrent = explode(',',$userActAll[$column]);
			} else {
				$userActCurrent = array();	
			}
		}
		
		
		
		
	}
	$output = (($success) ? array('op'=>'add','feedback'=>'Update Complete','redirect'=>$redirect) : array('op'=>'add','feedback'=>'UPDATE ERROR','redirect'=>''));
} else {
	$output = array('op'=>'add','feedback'=>'ERROR','redirect'=>''); 	
}
exit;
end;

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>