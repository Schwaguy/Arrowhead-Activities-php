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

//$emailForgot = 'josh@comocreative.com';
//echo '$emailForgot: '. $emailForgot .'<br>';

//if ($emailForgot) {
if ($_REQUEST) {
	//if (empty($emailForgot)) {
	if (!empty($_REQUEST['anti'])) {
		// SPAM!!!!!!!!
		$output = array('mesage'=>'','redirect'=>'/');
	} else {
		$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');
		//$action = 'forgot'; 
		if ($action == 'forgot') {
			//echo 'FORGOT<br>'; 
			$redirect = (isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : '');
			$usernameForgot = mysqli_real_escape_string($con, (trim($_REQUEST['usernameForgot'])));
			$emailForgot = mysqli_real_escape_string($con, (trim($_REQUEST['emailForgot'])));
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

				$sql = "UPDATE users SET `resetKey`='". $key ."' WHERE id=". $row['id'];
				if ($result = $con->query($sql)) {
					// Send Password Email	
					$toEmail = $row['email'];
					//$toEmail = 'josh@comocreative.com'; // Testing
					$toEmailName = $row['firstName'] .' '. $row['lastName'];
					$fromEmail = 'noreply@arrowheadclubday.com';
					$fromName = $formFrom; 
					$replytoEmail = $companyEmail;
					//$mailUN = 'Arrowhead'; 
					//$mailPW = '@rrowEmailSend1!';
					if($toEmail) {
						// Email Message
						$subject = $companyName .' Password Reset'; 
						$message = '<p>A password reset has been requested for your '. $companyName .' '. siteVar('act','singular','lowercase') .' scheduling account.</p><p>Please <a href="'. $SITEURL .'/reset/'. $row['id'] .'/'. $key .'">click here</a> to reset your password.</p>';
						
						
						//echo '<p>HOST: '. $smtpHost .'<br>UN: '. $smtpUN .'<br>PW: '. $smtpPW .'<br>FROM: '. $fromEmail .'</p>'; 
						
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
								//echo 'Mail Sent'; 
								$output = array('feedback'=>'Please check your email for Password Reset Instructions.  Make sure you check your Spam folder.','redirect'=>$redirect);
							} else {
								//echo 'Mail NOT Sent';
								$output = array('feedback'=>'We\'ve encountered an error.<br>Please visit the Camp Office to reset your password or email <a href="mailto:'. $formFromEmail .'">'. $formFromEmail .'</a>.','redirect'=>'/');
							}
						} else {
							$output = array('feedback'=>'Please check your email for Password Reset Instructions. Make sure you check your Spam folder.','redirect'=>$redirect);
							//echo "Message sent!<br>";
						}
						
						$output = array('feedback'=>'Please check your email for Password Reset Instructions.  Make sure you check your Spam folder.','redirect'=>$redirect);	
						
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
			$redirect = (isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : '');
			$id = (isset($_REQUEST['id']) ? $_REQUEST['id'] : '');
			$key = (isset($_REQUEST['key']) ? $_REQUEST['key'] : '');
			$query = "SELECT * FROM users WHERE id='". $id ."' AND resetKey='". $key ."'"; 
			if ($result = $con->query($query)) {
				$row = mysqli_fetch_assoc($result);
				$password = (isset($_REQUEST['password']) ? $_REQUEST['password'] : '');
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

//echo '<p>'. $output .'</p>';

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>