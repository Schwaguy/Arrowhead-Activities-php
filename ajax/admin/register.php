<?php
session_start();

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require($PATH  .'inc/PHPMailer-5.5/src/Exception.php');
require($PATH  .'inc/PHPMailer-5.5/src/PHPMailer.php');
require($PATH  .'inc//PHPMailer-5.5/src/SMTP.php');
//require($PATH  .'inc/PHPMailer/class.phpmailer.php');

if ($_REQUEST) {
	//echo '<p>Hey now!</p>';
	if (!empty($_REQUEST['anti'])) {
		// SPAM!!!!!!!!
		$output = array('update'=>'0','accountCreated'=>'false','mesage'=>'Error Encountered','redirect'=>$redirect);
	} else {
		//echo '<p>NOT SPAM</p>'; 
		$redirect = (isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : '');
		$firstName = mysqli_real_escape_string($con, (trim($_REQUEST['firstName'])));
		$lastName = mysqli_real_escape_string($con, (trim($_REQUEST['lastName'])));
		$username = mysqli_real_escape_string($con, (trim($_REQUEST['username'])));
		$email = mysqli_real_escape_string($con, (trim($_REQUEST['email'])));
		$bunk = ((isset($_REQUEST['bunk'])) ? mysqli_real_escape_string($con, (trim($_REQUEST['bunk']))) : 0);
		$password = mysqli_real_escape_string($con, (trim($_REQUEST['password'])));
		$access_level = mysqli_real_escape_string($con, (trim($_REQUEST['access_level'])));
		$secPW = generateHashWithSalt($password);	
		$salt = $secPW['salt'];
		$pass = $secPW['pw'];
		$newRegistration = (isset($_REQUEST['new-registration']) ? $_REQUEST['new-registration'] : false);

		$sql = "SELECT id FROM users WHERE username='". $username ."' AND active=1 LIMIT 1"; 
		$result = $con->query($sql);
		if (mysqli_num_rows($result)>0) {
			//echo 'EXISTS!'; 
			$output = array('update'=>'0','accountCreated'=>'false','feedback'=>'This username already exists.<br>Please try another username.','redirect'=>$redirect);
		} else {
			$sql = "INSERT INTO users (`firstname`,`lastName`,`username`,`email`,`bunk`,`password`,`salt`,`access_level`,`lastLogin`,`active`) VALUES ('". $firstName ."','". $lastName ."','". $username ."','". $email ."','". $bunk ."','". $pass ."','". $salt ."','". $access_level ."','" . $now . "','1')";
			
			//echo '<p>SQL: '. $sql .'</p>';
			
			if ($result = $con->query($sql)) {
				$id = mysqli_insert_id($con);
								
				//echo '<p>RESULT</p>'; 
				
				if ($newRegistration) {
					$login = logUserIn($username,false,$today,$now,$con); 
					$addRow = ''; 
				} else {
					include('inc/addrow-users.php');
				}
				
				//echo '<p>Here</p>';

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
				
				//echo '<p>Before email</p>';
				
				// Registration Confirmation Email
				/* Using SendGrid smtp service: https://sendgrid.com/marketing/login */ 
				require('../../inc/sendgrid-php/sendgrid-php.php');
				$toEmail = $email;
				//$toEmail = 'josh@comocreative.com'; // Testing
				$toEmailName = $firstName .' '. $lastName;
				$fromEmail = $formFromEmail;
				$fromName = $formFrom; 
				$replytoEmail = $companyEmail;
				//$mailUN = 'Arrowhead'; 
				//$mailPW = '@rrowEmailSend1!';
					
				if($toEmail) {
					// Email Message
					$subject = $companyName .' Registration Confirmation'; 
					$message = '<p>Thank you for registering for your '. $companyName .' '. siteVar('act','singular','lowercase') .' scheduling account.</p><p>Please see below for login information:</p><p><strong>Username: </strong>'. $username .'<br><strong>Password: </strong>'. $password .'</p><p>If you have any questions, please visit the Camp Office to reset your password  or email <a href="mailto:'. $companyEmail .'">'. $companyEmail .'</a>.</p>';
					
					$mail = new PHPMailer();
					$mail->isSMTP();
					//Enable SMTP debugging
					// 0 = off (for production use)
					// 1 = client messages
					// 2 = client and server messages
					$mail->SMTPDebug = 0;
					//$mail->Debugoutput = 'html';
					$mail->SMTPAuth = true;
					$mail->Host = $smtpHost;
					$mail->SMTPSecure = $smtpSecurity;
					$mail->Port = $smtpPort;
					$mail->Username = $smtpUN;
					$mail->Password = $smtpPW;
					$mail->addAddress($toEmail, $toEmailName);
					$mail->setFrom($fromEmail, $fromName);
					$mail->addReplyTo($fromEmail, $fromName);
					//$mail->AddBCC("josh@comocreative.com", "Josh Comolli");
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					//send the message, check for errors
					if (!$mail->send()) {
						// To send HTML mail, the Content-type header must be set
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=iso-8859-1';

						// Additional headers
						//$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
						$headers[] = 'From: '. $fromName .' <'. $fromEmail .'>';
						//$headers[] = 'Cc: email@example.com';
						//$headers[] = 'Bcc: josh@comocreative.com';

						// Mail it
						if (mail($to, $subject, $message, implode("\r\n", $headers))) {
							//echo '<p>Mail Sent</p>'; 
							$output = array('update'=>$id,'accountCreated'=>'true','feedback'=>'User Account Created','redirect'=>$redirect,'updateString'=>$addRow);
						} else {
							//echo '<p>Mail NOT Sent</p>';
							$output = array('update'=>'0','accountCreated'=>'true','feedback'=>'We\'ve encountered an error.<br>Please try again.','redirect'=>$redirect);
						}
					} else {
						$output = array('update'=>$id,'accountCreated'=>'true','feedback'=>'User Account Created','redirect'=>$redirect,'updateString'=>$addRow);
						//echo "<p>Message sent!</p>";
					}
						
					// Using SendGrid smtp service: https://sendgrid.com/marketing/login  
					/*$sendgrid = new SendGrid($mailUN,$mailPW);
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
					unset($email);*/
				}
				
				//echo '<p>After email</p>';
				
				$output = array('update'=>$id,'accountCreated'=>'true','feedback'=>'User Account Created','redirect'=>$redirect,'updateString'=>$addRow);
			} else {
				$output = array('update'=>'0','accountCreated'=>'false','feedback'=>'We\'ve encountered an error.<br>Please try again.','redirect'=>$redirect);	
			}
		}
	}
} else {
	$output = array('update'=>'0','accountCreated'=>'false','mesage'=>'Error Encountered','redirect'=>$redirect); 	
}

//require_once($PATH  .'globals/phpDebug.php');
//$debug = new PHPDebug();
//exit;
//end;

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));

?>