<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_POST) {
	if (!empty($_POST['anti'])) {
		// SPAM!!!!!!!!
		$output = array('update'=>'0','accountCreated'=>'false','mesage'=>'Error Encountered','redirect'=>$redirect);
	} else {
		$redirect = (isset($_POST['redirect']) ? $_POST['redirect'] : '');
		$firstName = mysqli_real_escape_string($con, (trim($_POST['firstName'])));
		$lastName = mysqli_real_escape_string($con, (trim($_POST['lastName'])));
		$username = mysqli_real_escape_string($con, (trim($_POST['username'])));
		$email = mysqli_real_escape_string($con, (trim($_POST['email'])));
		$bunk = (($_POST['bunk']) ? mysqli_real_escape_string($con, (trim($_POST['bunk']))) : 0);
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
			$sql = "INSERT INTO users (id,firstname,lastName,username,email,bunk,password,salt,access_level,lastLogin,active) VALUES ('','". $firstName ."','". $lastName ."','". $username ."','". $email ."','". $bunk ."','". $pass ."','". $salt ."','". $access_level ."','" . $now . "','1')";
			if ($result = $con->query($sql)) {
				$id = mysqli_insert_id($con);
				if ($newRegistration) {
					$login = logUserIn($username,false,$today,$con); 
					$addRow = ''; 
				} else {
					include('inc/addrow-users.php');
				}

				// Update Bunks if this is a counselor
				if (($access_level==3) && ($bunk>0)) {
					// Check to see if a councelor is already assigned to this bunk, if so, update old counselor
					$sql = "SELECT counselor FROM bunks WHERE id=". $bunk ." LIMIT 1" ; 
					if ($result = $con->query($sql)) {
						$row = mysqli_fetch_assoc($result);
						if ($row['counselor'] > 0) {
							$sql = "UPDATE users SET bunk=0 WHERE id=". $row['counselor']; 
							$result = $con->query($sql);
						}
					}
					// Update bunks to set new Counselor
					$sql = "UPDATE bunks SET counselor=". $id ." WHERE id=". $bunk;
					$result = $con->query($sql);
				}
				
				
				// Registration Confirmation Email
				/* Using SendGrid smtp service: https://sendgrid.com/marketing/login */ 
				require('../../inc/sendgrid-php/sendgrid-php.php');
				$toEmail = $email;
				//$toEmail = 'josh@comocreative.com'; // Testing
				$toEmailName = $firstName .' '. $lastName;
				$fromEmail = 'register@arrowheaddaycamp.com';
				$fromName = $formFrom; 
				$replytoEmail = $formFromEmail;
				$mailUN = 'Arrowhead'; 
				$mailPW = '@rrowEmailSend1!';
					
				if($toEmail) {
					// Email Message
					$subject = $companyName .' Registration Confirmation'; 
					$message = '<p>Thank you for registering for your '. $companyName .' '. siteVar('act','singular','lowercase') .' scheduling account.</p><p>Please see below for login information:</p><p><strong>Username: </strong>'. $username .'<br><strong>Password: </strong>'. $password .'</p><p>If you have any questions, please visit the Camp Office to reset your password  or email <a href="mailto:'. $formFromEmail .'">'. $formFromEmail .'</a>.</p>';
					$sendgrid = new SendGrid($mailUN,$mailPW);
					$email = new SendGrid\Email();
					$email
						->setFrom($fromEmail)
						->setFromName($fromName)
						->setReplyTo($replytoEmail)
						->setSubject($subject)
						->setHtml($message)
					;
					$emails = array($toEmail);
					$email->setTos($emails);
					$sendgrid->send($email);
					unset($email);
				}
				
				$output = array('update'=>$id,'accountCreated'=>'true','feedback'=>'User Account Created','redirect'=>$redirect,'updateString'=>$addRow);
			} else {
				$output = array('update'=>'0','accountCreated'=>'false','feedback'=>'We\'ve encountered an error.<br>Please try again.','redirect'=>$redirect);	
			}
		}
	}
} else {
	$output = array('update'=>'0','accountCreated'=>'false','mesage'=>'Error Encountered','redirect'=>$redirect); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));

?>