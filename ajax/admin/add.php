<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
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
		} elseif ($key == 'redirect') {
			$redirect = $value;
		} else {
			if (($key == 'startTime') || ($key == 'endTime')) {
				$value = date('H:i:s', strtotime($today .' '. $value));
			} elseif (isDate($value)) {
				if ($key == 'startDate') {
					$scheduleDate = scheduleCheck($_POST['table'],$key,$value);
					if (!empty($scheduleDate)) {
						$updates[] = "signupStartDate='". $scheduleDate ."'";
					}
				}
				$value = date_format(date_create($value),'Y-m-d');
			}  elseif (is_array($value)) {
				$value = implode(',',$value);
			} else {
				$value = mysqli_real_escape_string($con, $value);
			}
			$fields[$key] = $value; 
			$keys[] = $key;
			$values[] = "'". $value ."'";	
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