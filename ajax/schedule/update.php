<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$logfile = '../ajax-log.txt';
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('PHPSESSID','userAuth');
$success = false;
$feedback = 'Update Complete'; 
$redirect = ''; 
$activityFull = false;
if ($_REQUEST) {
	
	foreach($_REQUEST as $key => $value) {
		
		if (!in_array($key,$ignore)) {
			
			if (!$_POST) { echo $key .': '. $value .'<br>'; } 
			
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
					
					if (!$_POST) { echo '<p>ACTIVITY: '. $key .'</p>'; }
					
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
					
					if (!$_POST) { echo '<p>$sql GET ACTIVITY TYPEs: '. $sql .'</p>'; }
					
					
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
	
	if (!$_POST) { echo '<p>------------------- Activity Count: '. count($activities) .' -------------------------------</p>'; } 
	
	if (count($activities)>0) {
		
		foreach ($activities as $k => $act) {
			
			if (!$_POST) { echo '<p>----------<p>'; }
			
			if (!$_POST) { echo '<p>'; }
			if (!$_POST) { print_r($schedules); }
			if (!$_POST) { echo '</p>'; }
			
			if (!$_POST) { echo '<p>'; }
			$schID = $schedules[$k];
			$newActID = $act['id'];
			if (!$_POST) { echo '$schID: '. $schID .'<br>'; }
			$udKeys = $keys;
			$udValues = $values;
			if ($schID) {
				$udKeys[] = 'id';
				$udValues[] = "'". $schID ."'";
			}
			$udKeys[] = 'activity';
			$udValues[] = "'". $newActID ."'";
			$udKeys[] = 'week';
			$udValues[] = "'". $act['week'] ."'";
			$udKeys[] = 'day';
			$udValues[] = "'". $act['day'] ."'";
			$udKeys[] = 'period';
			$udValues[] = "'". $act['period'] ."'";
			$schedulingWeek = 'week-'. $act['week'];
			
			// Check Activity Signups
			
			if (!$_POST) { echo 'checkSignups('. $newActID .', '. $act['day'] .', '. $_REQUEST['userAuth'] .', '. implode('-',$camperAccessLevels) .',$con)<br>'; 	}
			
			$activityFull = checkSignups($newActID,$act['day'],$_REQUEST['userAuth'],$camperAccessLevels,$con);
			//if (!$_POST) { echo 'activityFull: '. $activityFull .'<br>'; }
			if ($activityFull) {
				if (!$_POST) { echo '- full - <br>'; }
				$feedback = 'Some of the activities you selected are now full. Please try again.';
				//$redirectHash = $schedulingWeek;
				$success = true;
			} else {
				// Get existing activity ID
				if ($schID) {
					$sql = 'SELECT activity, registered FROM activity_signups WHERE id='. $schID .' LIMIT 1'; 
					if (!$_POST) { echo 'SCH QUERY: '. $sql . '<br>'; }

					if ($getAct = mysqli_fetch_assoc(mysqli_query($con, $sql))) {
						$oldActID = $getAct['activity'];
						$registered = $getAct['registered'];
						if (!$_POST) { echo 'OLD: '. $oldActID .'<br>NEW: '. $newActID .'<br>'; }
						if ($oldActID != $newActID) {
							// Get Old Activity Type
							$sql = "SELECT type FROM activities WHERE id=". $oldActID ." LIMIT 1"; 
							if (!$_POST) { echo $sql . '<br>'; }
							$getType = mysqli_fetch_assoc(mysqli_query($con, $sql));
							$oldType = $getType['type'];

							// if old act is one time
							if (checkOneTimeAct($oldType,$con)) {
								$column = date('Y'); // Camp Year Column
								$sql = "SELECT `". $column ."` FROM user_activities WHERE user=". $uID; 
								if (!$_POST) { echo $sql . '<br>'; } 

								if ($result = $con->query($sql)) {
									$userActAll = $result->fetch_array(MYSQLI_ASSOC);
									$userActCurrent = explode(',',$userActAll[$column]);

									// Search and remove old Activity if one time activity
									if (in_array($oldType,$userActCurrent)) {	
										$pos = array_search($oldType, $userActCurrent);
										unset($userActCurrent[$pos]);
										$sql = "UPDATE user_activities SET `". $column ."`='". implode(',',$userActCurrent) ."' WHERE user=". $uID;
										$result = $con->query($sql);
									}
								}
							}
							
							$udKeys[] = 'registered';
							$udValues[] = "'". $registered ."'";
							$udKeys[] = 'active';
							$udValues[] = "'1'";

							$sql = "REPLACE INTO activity_signups (". implode(',',$udKeys) .") VALUES (". implode(',',$udValues) .")";

							if (!$_POST) { echo '<h2>UPDATE: '. $sql .'</h2>'; } 

							if ($result = $con->query($sql)) {
								$var = 'reg'. $act['day']; 

								// Adjust Old Activity Attendance
								$sql = "UPDATE activities SET ". $var ."=". $var ."-1 WHERE id=". $oldActID;
								if (!$_POST) { echo '<p>Old Activity Update: '. $sql .'</p>'; } 
								$result = $con->query($sql);

								// Adjust New Activity Attendance
								$sql = "UPDATE activities SET ". $var ."=". $var ."+1 WHERE id=". $newActID;
								if (!$_POST) { echo '<p>New Activity Update: '. $sql .'</p>'; } 
								$result = $con->query($sql);
								$success = true;
							} else {
								$success = false;
							}
							
							unset($udKeys);
							unset($udValues);
							
						}
					}
					//$sql = "UPDATE activity_signups SET activity=". $newActID .", updated='". $now ."', updatedBy='". $_SESSION['userID'] ."' WHERE id=". $schID;	
				} else {
					if (!$_POST) { echo '<p>NO SID</p>'; } 	
					$registered = $now;
					$udKeys[] = 'registered';
					$udValues[] = "'". $registered ."'";
					$udKeys[] = 'active';
					$udValues[] = "'1'";
					
					$sql = "INSERT INTO activity_signups (". implode(',',$udKeys) .") VALUES (". implode(',',$udValues) .")";
					if (!$_POST) { echo 'NEW Activity Signup: '. $sql; }
					if ($result = $con->query($sql)) {
						$var = 'reg'. $act['day']; 

						// Adjust New Activity Attendance
						$sql = "UPDATE activities SET ". $var ."=". $var ."+1 WHERE id=". $newActID;
						if (!$_POST) { echo 'New Activity Update: '. $sql .'<br>'; } 
						$result = $con->query($sql);
						$success = true;
					} else {
						$success = false;
					}
				}
				if (!$_POST) { echo '</p>'; }
			}
			if (!$_POST) { echo '<p>SUCCESS: '. (($success) ? 'TURE' : 'FALSE') .'</p>'; }
		}
		
		if ($success) {
			// Check and Adjust User Activity Record
			$column = date('Y'); // Camp Year Column
			$sql = "SELECT `". $column ."` FROM user_activities WHERE user=". $uID; 
			if ($result = $con->query($sql)) {
				$userActAll = $result->fetch_array(MYSQLI_ASSOC);
				$userActCurrent = explode(',',$userActAll[$column]);
				$userActivities = array_unique(array_merge($uActNew,$userActCurrent));
				$sql = "UPDATE user_activities SET '". $column ."'='". implode(',',$userActivities) ."' WHERE user=". $fields['user'];
				$result = $con->query($sql);
			} else {
				$userActCurrent = array();	
			}
		}
	}
	$redirect = (($schedulingWeek) ? $redirect .'#'. $schedulingWeek : $redirect);
	
	if ($_SESSION['userID'] != $uID) { $redirect = ''; }
	$output = (($success) ? array('op'=>'update','feedback'=>$feedback,'redirect'=>$redirect) : array('op'=>'update','feedback'=>'UPDATE ERROR','redirect'=>''));
} else {
	$output = array('op'=>'update','feedback'=>'ERROR','redirect'=>''); 	
}

//logToFile($logfile, 'Output: '. implode(' | ', $output));

if ($con) $con->close(); 
if (!$_POST) {
	print_r($output); 
	echo '<p>'. ($success ? 'SUCCESS!' : 'We got issues') .'</p>';
} else {
	header('Content-Type: application/json; charset=utf-8', true); 
	echo json_encode(array("output"=>$output));
}
?>