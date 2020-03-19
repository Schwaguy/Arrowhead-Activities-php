<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$success = false;
$redirect = ''; 
$feedback = 'Activity Signups Cleared'; 

if ($_POST) {
	$sql = "UPDATE activity_signups SET active=0, updatedBy=". $_SESSION['userID'] ." WHERE registered<'". $now ."'";
	$success = (($result = $con->query($sql)) ? true : false);

	//echo '<p>'. $sql .'</p>'; 
	
	$sql = "UPDATE activities SET regMonday=0, regTuesday=0, regWednesday=0, regThursday=0, regFriday=0, updated='". $now ."', updateBy=". $_SESSION['userID'] ." WHERE active=1";
	$success = (($result = $con->query($sql)) ? true : false);
	
	//echo '<p>'. $sql .'</p>';
	
	$output = (($success) ? array('op'=>'clear','feedback'=>$feedback,'redirect'=>$redirect,'result'=>'success') : array('op'=>'clear','feedback'=>'UPDATE ERROR','redirect'=>'','result'=>'error'));
} else {
	$output = array('op'=>'clear','feedback'=>'UPDATE ERROR','redirect'=>'','result'=>'error'); 	
}

//exit;
//end;

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>