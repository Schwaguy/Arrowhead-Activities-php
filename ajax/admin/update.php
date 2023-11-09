<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

//echo 'TEST'; 

$ignore = array('oneTime', 'typeInput');
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
				} elseif ($key == 'id') {
					$value = $value;
				} elseif (($key == 'startTime') || ($key == 'endTime')) {
					$value = date('H:i:s', strtotime($today .' '. $value));
				} elseif (isDate($value)) {
					if ($key == 'startDate') {
						$scheduleDates = scheduleCheck($_POST['table'],$key,$value,$con);
						if (is_array($scheduleDates)) {
							if (!empty($scheduleDates['start'])) {
								$updates[] = "`signupStartDate`='". $scheduleDates['start'] ."'";
							}
							if (!empty($scheduleDates['end'])) {
								$updates[] = "`signupEndDate`='". $scheduleDates['end'] ."'";
							}
							if (!empty($scheduleDates['signupStartDateGrp1'])) {
								$updates[] = "`signupStartDateGrp1`='". $scheduleDates['signupStartDateGrp1'] ."'";
							}
							if (!empty($scheduleDates['signupStartDateGrp2'])) {
								$updates[] = "`signupStartDateGrp2`='". $scheduleDates['signupStartDateGrp2'] ."'";
							}
						}
					}
					$value = date_format(date_create($value),'Y-m-d');
				} elseif (is_array($value)) {
					$value = implode(',',$value);
				} else {
					$value = mysqli_real_escape_string($con, $value);
				}
				$updates[] = "`". $key ."`='". $value ."'";		
			}
		}
	}
	
	// Update Bunks if this is a counselor
	if (($table=='users') && ($_POST['access_level']==3)) { 
		if ($_POST['bunk']>0) {
			// Check to see if a councelor is already assigned to this bunk, if so, update old counselor
			$sql = "SELECT counselor FROM bunks WHERE id=". $_POST['bunk'] ." LIMIT 1"; 
			if ($result = $con->query($sql)) {
				$row = mysqli_fetch_assoc($result);
				if ($row['counselor']>0) {
					$sql = "UPDATE users SET bunk=0 WHERE id=". $row['counselor']; 
					$result = $con->query($sql);
				}
			}
			// Update bunks to set new Counselor
			$sql = "UPDATE bunks SET counselor=". $id ." WHERE id=". $_POST['bunk'];	
			$result = $con->query($sql);
		}
	} 
	
	//echo 'HERE'; 
	// Update Counselor's user record if this is a Bunk edit
	if (($table=='bunks') && !empty($_POST['counselor'])) {
		$sql = "SELECT counselor FROM bunks WHERE id=". $id ." LIMIT 1";
		//echo '<p>'. $sql .'</p>'; 
		if ($result = $con->query($sql)) {
			$row = mysqli_fetch_assoc($result);
			if ($row['counselor']>0) {
				$sql = "UPDATE users SET bunk=0 WHERE id=". $row['counselor']; 
				//echo '<p>'. $sql .'</p>';
				$result = $con->query($sql);
			}
		}
		// Update bunks to set new Counselor
		$sql = "UPDATE users SET bunk=". $id ." WHERE id=". $_POST['counselor'];
		//cho '<p>'. $sql .'</p>';
		$result = $con->query($sql);
	}
	
	$updates = implode(', ',$updates);
	$sql = "UPDATE ". $table ." SET ". $updates ." WHERE id=". $id ." LIMIT 1";
	//echo '<p>'. $sql .'</p>';
	$result = $con->query($sql);
	
	$output = (($result) ? array('update'=>$id,'op'=>'update','table'=>$table,'updateString'=>'update successful','feedback'=>'UPDATE COMPLETE','redirect'=>$redirect) : array('update'=>'0','op'=>'update','table'=>$table,'updateString'=>strtoupper($table) .' UPDATE ERROR : '. $sql,'feedback'=>strtoupper($table) .' UPDATE ERROR : '. $sql,'redirect'=>''));
} else {
	$output = array('update'=>'0','op'=>'update','table'=>'','updateString'=>'NO INFO','feedback'=>'','redirect'=>''); 	
}

/*foreach ($output as $k=>$v) {
	echo $k .' : '. $v .'<br>'; 
}*/
//exit;
//end;

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>