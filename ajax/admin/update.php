<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$redirect = ''; 
if ($_POST) {
	foreach($_POST as $key => $value) {
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
			if (($key == 'startTime') || ($key == 'endTime')) {
				$value = date('H:i:s', strtotime($today .' '. $value));
			} elseif (isDate($value)) {
				$scheduleDate = scheduleCheck($_POST['table'],$key,$value);
				if (!empty($scheduleDate)) {
					$updates[] = "signupStartDate='". $scheduleDate ."'";
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