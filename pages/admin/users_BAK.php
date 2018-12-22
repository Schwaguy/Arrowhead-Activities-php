<?php

	$content .= '<div class="container main">
		<h1 class="page-title">Users</h1>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$query = "SELECT * FROM users WHERE active=1 ORDER BY lastName ASC, firstName ASC";
	if($result = $con->query($query)) {
		//$content .= '<div id="response"></div>'; 
		
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$header = '<div class="list-group-item list-group-header row d-flex">
				<div class="col-4 col-header">Name</div>
				<div class="col-2 col-header">Bunk</div>
				<div class="col-2 col-header">Auth</div>
			</div>';
		$content .= $header;
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$bunks = getBunks($row['bunk'],$con);
			$auth = getAuth($row['access_level'],true,$_SESSION['userID'],false,$con);
			
			$content .= '<form id="form-'. $row['id'] .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-7 col-md-8">
					<input type="hidden" name="table" value="users">
					<input type="hidden" name="id" value="'. $row['id'] .'">
					<div class="row flex-row d-flex">
						<div class="col-12 col-sm-12 col-md-6 align-self-center">
							<div class="row d-flex">
								<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input name="lastName" class="form-control" value="'. $row['lastName'] .'" data-rule-required="true" data-msg-required="Last name is required"></p></div>
								<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input name="firstName" class="form-control" value="'. $row['firstName'] .'" data-rule-required="true" data-msg-required="First name is required"></p></div>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-3 align-self-center "><p>'. $bunks .'</p></div>
						<div class="col-12 col-sm-12 col-md-3 align-self-center"><p>'. $auth .'</p></div>
						<div class="col-12">
							<div class="row flex-row">
								<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="text" name="username" class="form-control" value="'. $row['username'] .'" placeholder="Username"></p></div>
								<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="email" name="email" class="form-control" value="'. $row['email'] .'" placeholder="Email"></p></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-xs-12 col-sm-5 col-md-4">
					<div class="row flex-row d-flex">
						<div class="col-6 col-xs-6 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update</button></div>
						<div class="col-6 col-xs-6 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-danger adminBtn" data-op="delete">Delete</button></div>
					</div>
				</div>
			</form>';
		}
		$content .= '</div><!-- /list-group -->';
	} 

	// New User Form
	$bunks = getBunks('',$con);
	$auth = getAuth('',true,$_SESSION['userID'],false,$con);
	$content .= '<p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addUser" aria-expanded="false" aria-controls="addUser">Add New User</button></p>
	<div class="collapse" id="addUser">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm list-group-item row align-items-center d-flex">
					<div class="col-8 col-xs-8 col-sm-7 col-md-8">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="users">
						<input type="hidden" name="active" value="1">
						<div class="row row-flex d-flex">
							<div class="col-12 col-sm-12 col-md-6 align-self-center ">
								<div class="row row-flex">
									<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="text" name="lastName" class="form-control" value="" placeholder="last Name"></p></div>
									<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="text" name="firstName" class="form-control" value="" placeholder="First Name"></p></div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-3 align-self-center "><p>'. $bunks .'</p></div>
							<div class="col-12 col-sm-12 col-md-3 align-self-center"><p>'. $auth .'</p></div>
							<div class="col-12">
								<div class="row flex-row">
									<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="text" name="username" class="form-control" value="" placeholder="Username"></p></div>
									<div class="col-12 col-sm-12 col-md-6 align-self-center"><p><input type="email" name="email" class="form-control" value="" placeholder="Email"></p></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-4 col-xs-4 col-sm-5 col-md-2">
						<button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add User</button>
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$content .= '</div>
        </div>
    </div>';
?>