<?php
	
	$loginError = '';
	if (!empty($_REQUEST['logout'])) {
		session_destroy();
		//header("Location: ". $SITEURL);
		//die();
	} elseif (!empty($_POST['uName'])) { // Get user information asn set Session variables
		$uName = $_POST['uName'];
		$uPass = $_POST['uPass'];
	
		// Check Pasword
		$query = 'SELECT salt FROM users WHERE username="' . $uName . '"';
		$result = $con->query($query);
		$row = mysqli_fetch_assoc($result);
		$pwCheck =  hash("sha256", $uPass . $row['salt']);
		
		// Get User Information for Valid Login
		$query = 'SELECT u.*, a.super, a.admin, a.manage, a.edit, a.schedule FROM users u LEFT JOIN access_levels a ON (u.access_level = a.id) WHERE u.username="' . $uName . '" AND u.password="'. $pwCheck .'"'; 
		$result = $con->query($query);
		
		if ($result) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row['active'] == 1)  {
				$_SESSION['userID'] = $row['id'];
				$_SESSION['userAuth'] = $row['access_level'];
				$_SESSION['userPermissions'] = array(
					'super'=>$row['super'],
					'admin'=>$row['admin'],
					'manage'=>$row['manage'],
					'edit'=>$row['edit'],
					'schedule'=>$row['schedule']
				);
				$_SESSION['userName'] = $row['username'];
				$_SESSION['userFirstName'] = $row['firstName'];
				$_SESSION['userlastName'] = $row['lastName'];
				$_SESSION['userEmail'] = $row['email'];
				$_SESSION['userBunk'] = $row['bunk'];
				if (!empty($_SESSION['userBunk'])) {
					$_SESSION['bunkInfo'] = getBunkInfo($_SESSION['userBunk'],$con);
				}
				$result->free();
				$query = 'UPDATE users SET lastLogin="' . $today . '" WHERE id="' . $_SESSION['userID'] . '"'; 
				$result = $con->query($query);
				include('inc/pageContent.php');
			} else { // Invalid Username or Password
				$content .= $loginLink;
				$content .= '<h1>Invalid Username or Password</h1>';
			}
		} else { // Invalid Username or Password
			$loginError = '<h2 class="error login-error text-center">Invalid Username or Password</h2>';
			include('pages/login.php');
		}
	}
	elseif (!empty($_SESSION['userName'])) { 
		include('inc/pageContent.php');
	}
	else {// Start Session and Display Login Form
		include('pages/login.php');
	}
?>