<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('id','oneTime', 'typeInput');
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
				if (($_POST['table']=='activities') && ($key == 'type') && !is_numeric($value)) {
					$oneTime = ($_POST['oneTime'] ? $_POST['oneTime'] : 0);
					$sql = "INSERT INTO activity_types (`name`,`oneTime`,`active`) VALUES ('". $value ."','". $oneTime ."',1)"; 
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
								//$updates[] = "`signupStartDate`='". $scheduleDates['start'] ."'";
								$keys[] = "`signupStartDate`";
								$values[] = "'". $scheduleDates['start'] ."'";
								
							}
							if (!empty($scheduleDates['end'])) {
								//$updates[] = "`signupEndDate`='". $scheduleDates['end'] ."'";
								$keys[] = "`signupEndDate`";
								$values[] = "'". $scheduleDates['end'] ."'";
							}
							if (!empty($scheduleDates['signupStartDateGrp1'])) {
								//$updates[] = "`signupStartDate-int`='". $scheduleDates['startInt'] ."'";
								$keys[] = "`signupStartDateGrp1`";
								$values[] = "'". $scheduleDates['signupStartDateGrp1'] ."'";
							}
							if (!empty($scheduleDates['signupStartDateGrp2'])) {
								//$updates[] = "`signupStartDate-senior`='". $scheduleDates['startSenior'] ."'";
								$keys[] = "`signupStartDateGrp2`";
								$values[] = "'". $scheduleDates['signupStartDateGrp2'] ."'";
							}
						}
					}
					$value = date_format(date_create($value),'Y-m-d');
				}  elseif (is_array($value)) {
					$value = implode(',',$value);
				} else {
					$value = mysqli_real_escape_string($con, $value);
				}
				$fields[$key] = $value; 
				$keys[] = "`". $key ."`";
				$values[] = "'". $value ."'";	
			}
		}
	}
	$keys = implode(',',$keys);
	$values = implode(',',$values);
	$sql = "INSERT INTO ". $table ." (". $keys .") VALUES (". $values .")";
	$result = $con->query($sql);
	$id = mysqli_insert_id($con);
	
	$rowUpdates = array('bunks','weeks','periods','users');
	
	if (in_array($table,$rowUpdates)) {
		include('inc/addrow-'. $table .'.php');
	} else {
		$addRow = ''; 
	}
	$output = (($result) ? array('update'=>$id,'op'=>'add','table'=>$table,'updateString'=>$addRow,'feedback'=>'Addition Complete','redirect'=>$redirect) : array('update'=>'0','op'=>'add','table'=>$table,'updateString'=>'','feedback'=>strtoupper($table) .' ADDITION ERROR : '. $sql,'redirect'=>''));
} else {
	$output = array('update'=>'0','op'=>'add','table'=>'','updateString'=>'NO INFO','feedback'=>'','redirect'=>''); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>