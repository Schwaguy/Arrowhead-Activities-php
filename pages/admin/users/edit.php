<?php

	if (isset($_POST['id'])) {
		// Get User Info
		$sql = 'SELECT * FROM users WHERE id='. $_POST['id'] .' LIMIT 1';  
		if($res = $con->query($sql)) {
			while ($usr=$res->fetch_array(MYSQLI_ASSOC)) {
				$user = array(
					'id'=>$usr['id'],
					'firstName'=>$usr['firstName'],
					'lastName'=>$usr['lastName'],
					'email'=>$usr['email'],
					'username'=>$usr['username'],
					'access_level'=>$usr['access_level'],
					'bunk'=>$usr['bunk'],
					'lastLogin'=>$usr['lastLogin']
				);
			}
		}
		$redirect = '/admin/users/'; 
		
		$content .= '<div class="container main">
			<h1 class="page-title">Edit User</h1>
			<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10">';
		
		include($ROOT .'/pages/admin/users/edit-user-form.php');

		$content .= '</div>
			</div>
		</div>';
	} else {
		$content .= '<h1 class="page-title text-center">Nothing Selected</h1>';
	}
?>