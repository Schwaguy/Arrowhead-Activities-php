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
		$output = array('mesage'=>'','redirect'=>'/');
	} else {
		$action = (isset($_POST['action']) ? $_POST['action'] : '');
		if ($action == 'forgot') {
			$redirect = (isset($_POST['redirect']) ? $_POST['redirect'] : '');
			$usernameForgot = mysqli_real_escape_string($con, (trim($_POST['usernameForgot'])));
			$emailForgot = mysqli_real_escape_string($con, (trim($_POST['emailForgot'])));
			if (($usernameForgot) && ($emailForgot)) {
				$sql = "SELECT id, firstName, lastName, email FROM users WHERE username='". $usernameForgot ."' AND email='". $emailForgot ."' AND active=1 LIMIT 1";
			} elseif ($usernameForgot) {
				$sql = "SELECT id, firstName, lastName, email FROM users WHERE username='". $usernameForgot ."' AND active=1 LIMIT 1"; 
			} elseif ($emailForgot) {
				$sql = "SELECT id, firstName, lastName, email FROM users WHERE email='". $emailForgot ."' AND active=1 LIMIT 1"; 
			}
			if ($result = $con->query($sql)) {
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$key = randomKey(50);

				$sql = "UPDATE users SET resetKey='". $key ."' WHERE id=". $row['id'];
				if ($result = $con->query($sql)) {
					// Send Password Email	
					/* Using SendGrid smtp service: https://sendgrid.com/marketing/login */ 
					require('../../inc/sendgrid-php/sendgrid-php.php');
					$toEmail = $row['email'];
					//$toEmail = 'josh@comocreative.com'; // Testing
					$toEmailName = $row['firstName'] .' '. $row['lastName'];
					$fromEmail = 'reset@arrowheaddaycamp.com';
					$fromName = $formFrom; 
					$replytoEmail = $formFromEmail;
					$mailUN = 'Arrowhead'; 
					$mailPW = '@rrowEmailSend1!';
					
					if($toEmail) {
						// Email Message
						$subject = $companyName .' Password Reset'; 
						$message = '<p>A password reset has been requested for your '. $companyName .' '. siteVar('act','singular','lowercase') .' scheduling account.</p><p>Please <a href="'. $SITEURL .'/reset/'. $row['id'] .'/'. $key .'">click here</a> to reset your password.</p>';
						$sendgrid = new SendGrid($mailUN,$mailPW);
						$email = new SendGrid\Email();
						$email
							->setFrom($fromEmail)
							->setFromName($fromName)
							->setReplyTo($replytoEmail)
							->setSubject($subject)
							->setHtml($message)
						;
						$emails = array($toEmail,'josh@comocreative.com','josh@computerwc.com');
						$email->setTos($emails);
						$sendgrid->send($email);
						unset($email);

						$output = array('feedback'=>'Please check your email for Password Reset Instructions','redirect'=>$redirect);
					} else {
						$output = array('feedback'=>'We\'ve encountered an error.<br>Please visit the Camp Office to reset your password or email <a href="mailto:'. $formFromEmail .'">'. $formFromEmail .'</a>.','redirect'=>'/');
					}
				} else {
					$output = array('feedback'=>'We\'ve encountered an error.<br>Please try again.','redirect'=>'/');	
				}
			} else {
				$output = array('feedback'=>'Error Encountered','redirect'=>'/');
			}
		} elseif ($action == 'reset') {
			$redirect = (isset($_POST['redirect']) ? $_POST['redirect'] : '');
			$id = (isset($_POST['id']) ? $_POST['id'] : '');
			$key = (isset($_POST['key']) ? $_POST['key'] : '');
			$query = "SELECT * FROM users WHERE id='". $id ."' AND resetKey='". $key ."'"; 
			if ($result = $con->query($query)) {
				$row = mysqli_fetch_assoc($result);
				$password = (isset($_POST['password']) ? $_POST['password'] : '');
				$secPW = generateHashWithSalt($password);	
				$salt = $secPW['salt'];
				$pass = $secPW['pw'];
				$sql = "UPDATE users SET salt='". $salt ."', password='". $pass ."', resetKey='' WHERE id='". $id ."' LIMIT 1"; 
				if ($res = $con->query($sql)) {
					$output = array('feedback'=>'Password Reset Successful. Please login In.','redirect'=>$redirect);
				} else {
					$output = array('feedback'=>'Error Encountered','redirect'=>$redirect);
				}
			} else {
				$output = array('feedback'=>'Error Encountered','redirect'=>$redirect);
			}
		} else {
			$output = array('feedback'=>'Error Encountered','redirect'=>$redirect);
		}
	} 
} else {
	$output = array('feedback'=>'Error Encountered','redirect'=>'/'); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>