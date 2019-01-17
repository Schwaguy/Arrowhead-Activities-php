<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('oneTime');
$redirect = ''; 
if ($_POST) {
	foreach($_POST as $key => $value) {
		if (!in_array($key,$ignore)) {
			if ($key == 'op') {
				$op = $value;
			} elseif ($key == 'table') {
				$table = $value;
			} elseif ($key == 'id') {
				$id = $value;
			} elseif ($key == 'redirect') {
				$redirect = $value;
			} elseif ($key == 'password') {
				if (!empty($value)) {
					$secPW = generateHashWithSalt($value);	
					$salt = $secPW['salt'];
					$pass = $secPW['pw'];
					$updates[] = "password='". $pass  ."'";
					$updates[] = "salt='". $salt ."'";
				}
			} elseif ($key == 'password_repeat') {
				// Skip this
			} else {
				if (($_POST['table']=='activities') && ($key == 'type') && !is_numeric($value)) {
					$oneTime = ($_POST['oneTime'] ? $_POST['oneTime'] : 0);
					$sql = "INSERT INTO activity_types (id,name,oneTime,active) VALUES ('','". $value ."','". $oneTime ."',1)"; 
					$result = $con->query($sql);
					$value = mysqli_insert_id($con);
				} elseif (($key == 'startTime') || ($key == 'endTime')) {
					$value = date('H:i:s', strtotime($today .' '. $value));
				} elseif (isDate($value)) {
					if ($key == 'startDate') {
						$scheduleDates = scheduleCheck($_POST['table'],$key,$value);
						if (is_array($scheduleDates)) {
							if (!empty($scheduleDates['start'])) {
								$updates[] = "signupStartDate='". $scheduleDates['start'] ."'";
							}
							if (!empty($scheduleDates['end'])) {
								$updates[] = "signupEndDate='". $scheduleDates['end'] ."'";
							}
						}
					}
					$value = date_format(date_create($value),'Y-m-d');
				} elseif (is_array($value)) {
					$value = implode(',',$value);
				} else {
					$value = mysqli_real_escape_string($con, $value);
				}
				$updates[] = $key ."='". $value ."'";		
			}
		}
	}
	$updates = implode(', ',$updates);
	$sql = "UPDATE ". $table ." SET ". $updates ." WHERE id=". $id ." LIMIT 1";
	$result = $con->query($sql);
	
	$output = (($result) ? array('update'=>$id,'op'=>'update','table'=>$table,'updateString'=>'update successful','feedback'=>'UPDATE COMPLETE','redirect'=>$redirect) : array('update'=>'0','op'=>'update','table'=>$table,'updateString'=>strtoupper($table) .' UPDATE ERROR : '. $sql,'feedback'=>strtoupper($table) .' UPDATE ERROR : '. $sql,'redirect'=>''));
} else {
	$output = array('update'=>'0','op'=>'update','table'=>'','updateString'=>'NO INFO','feedback'=>'','redirect'=>''); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>