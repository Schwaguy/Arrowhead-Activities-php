<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('PHPSESSID','showPW');
$success = false;
$redirect = ''; 
$feedback = 'Bunk Assignments Cleared'; 

if ($_POST) {
	$sql = "UPDATE users SET bunk=0, updatedBy=". $_SESSION['userID'];
	$result = $con->query($sql);
	
	$output = (($result) ? array('op'=>'clear','feedback'=>$feedback,'redirect'=>$redirect,'result'=>'success') : array('op'=>'clear','feedback'=>'UPDATE ERROR','redirect'=>'','result'=>'error'));
} else {
	$output = array('op'=>'clear','feedback'=>'UPDATE ERROR - No POST','redirect'=>'','result'=>'error'); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>