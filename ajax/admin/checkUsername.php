<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_POST) {
	$username = mysqli_real_escape_string($con, (trim($_POST['username'])));
	$sql = "SELECT id FROM users WHERE username='". $username ."' AND active=1 LIMIT 1";
	$result = $con->query($sql);
	if (mysqli_num_rows($result)>0) {
		$output = array('usernameExists'=>'1','feedback'=>'This username already exists.<br>Please try another username.');
	} else {
		$output = array('usernameExists'=>'0','feedback'=>'');
	}
} 

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));

?>