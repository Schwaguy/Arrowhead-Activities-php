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
			if (strpos($key,'activity-') !== false) {
				$actInfo = explode('-',$key);
				$activities[] = array(
					'id'=>$value,
					'week'=>$actInfo[1],
					'day'=>$actInfo[2],
					'period'=>$actInfo[3]
				);
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
			if ($result = $con->query($sql)) {
				$success = true;
			} else {
				$success = false;
			}
			$var = 'reg'. $act['day']; 
			//$sql = "SELECT ". $var ." AS signup FROM activities WHERE id=". $act['id'];
			$sql = "UPDATE activities SET ". $var ."=". $var ."+1 WHERE id=". $act['id']; 
			$result = $con->query($sql);
			
			unset($udKeys);
			unset($udValues);
		}
	}
	$output = (($success) ? array('op'=>'add','feedback'=>'Addition Complete','redirect'=>$redirect) : array('op'=>'add','feedback'=>'ADDITION ERROR','redirect'=>''));
} else {
	$output = array('op'=>'add','feedback'=>'ERROR','redirect'=>''); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>