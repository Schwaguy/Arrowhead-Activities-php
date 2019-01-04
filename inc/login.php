<?php
	$loginError = '';
	if (!empty($_REQUEST['logout'])) {
		session_destroy();
	} elseif (!empty($_POST['uName'])) { // Check PW, Get user information and set Session variables
		$uName = $_POST['uName'];
		$uPass = $_POST['uPass'];
		$pwCheck = checkPassword($uName,$uPass,$con);
		if (logUserIn($uName,$pwCheck,$today,$con)) {
			include('inc/pageContent.php');
		} else { // Invalid Username or Password
			$loginError = '<div class="alert alert-danger" role="alert">Invalid Username or Password</div>';
			include('pages/login.php');
		}
	} elseif (!empty($_SESSION['userName'])) { 
		include('inc/pageContent.php');
	} else {
		include('pages/login.php');
	}
?>