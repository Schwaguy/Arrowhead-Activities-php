<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_POST) {
	$redirect = (isset($_POST['redirect']) ? $_POST['redirect'] : '');
	$firstName = mysqli_real_escape_string($con, (trim($_POST['firstName'])));
  	$lastName = mysqli_real_escape_string($con, (trim($_POST['lastName'])));
	$username = mysqli_real_escape_string($con, (trim($_POST['username'])));
	$email = mysqli_real_escape_string($con, (trim($_POST['email'])));
	$bunk = mysqli_real_escape_string($con, (trim($_POST['bunk'])));
	$password = mysqli_real_escape_string($con, (trim($_POST['password'])));
	$access_level = mysqli_real_escape_string($con, (trim($_POST['access_level'])));
	$secPW = generateHashWithSalt($password);	
	$salt = $secPW['salt'];
	$pass = $secPW['pw'];
	$newRegistration = (isset($_POST['new-registration']) ? $_POST['new-registration'] : false);
	
	$sql = "SELECT id FROM users WHERE username='". $username ."' AND active=1 LIMIT 1"; 
	$result = $con->query($sql);
	if (mysqli_num_rows($result)>0) {
		$output = array('update'=>'0','accountCreated'=>'false','feedback'=>'This username already exists.<br>Please try another username.','redirect'=>$redirect);
	} else {
		$sql = "INSERT INTO users (id,firstname,lastName,username,bunk,password,salt,access_level,lastLogin,active) VALUES ('','". $firstName ."','". $lastName ."','". $username ."','". $bunk ."','". $pass ."','". $salt ."','". $access_level ."','" . $now . "','1')";
		if ($result = $con->query($sql)) {
			$id = mysqli_insert_id($con);
			if ($newRegistration) {
				$login = logUserIn($username,false,$today,$con); 
				$addRow = ''; 
			} else {
				include('inc/addrow-users.php');
			}
			$output = array('update'=>$id,'accountCreated'=>'true','feedback'=>'User Account Created','redirect'=>$redirect,'updateString'=>$addRow);
		} else {
			$output = array('update'=>'0','accountCreated'=>'false','feedback'=>'We\'ve encountered an error.<br>Please try again.','redirect'=>$redirect);	
		}
	}
} else {
	$output = array('update'=>'0','accountCreated'=>'false','mesage'=>'Error Encountered','redirect'=>$redirect); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>