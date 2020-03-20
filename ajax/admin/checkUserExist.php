<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_REQUEST) {
	$firstName = mysqli_real_escape_string($con, (trim($_REQUEST['firstName'])));
	$lastName = mysqli_real_escape_string($con, (trim($_REQUEST['lastName'])));
	$email = mysqli_real_escape_string($con, (trim($_REQUEST['email'])));
	$sql = "SELECT id FROM users WHERE firstName='". $firstName ."' AND lastName='". $lastName ."' AND active=1";
	$result = $con->query($sql);
	if (mysqli_num_rows($result)>0) {
		$output = array('userExists'=>'yes','feedback'=>'An accout with this first and last name already exists.  If you already have an account, you can reset your password by clicking the "Forgot Password" button.');
	} else {
		$output = array('userExists'=>'no','feedback'=>'');
	}
} 
if ($_POST) {
	if ($con) $con->close(); 
	header('Content-Type: application/json; charset=utf-8', true); 
	echo json_encode(array("output"=>$output));
} else {
	if ($con) $con->close();
	echo $output['userExists'] .' : '. $output['feedback']; 
}

?>