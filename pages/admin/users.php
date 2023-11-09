<?php
	$content .= '<div class="container main">
		<div class="clearfix">
			
			<div class="quick-buttons"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addUser" aria-expanded="false" aria-controls="addUser">Add New User</button></div>
		
			<h1 class="page-title">Users</h1>
			
		</div>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	// New User Form
	$content .= '
	<div class="collapse add-item-box" id="addUser">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				<form id="registerForm" class="list-group-item registerForm" name="registerForm" method="post">
					<input type="hidden" name="redirect" value="">
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="firstName">First Name</label>
							<input type="text" class="form-control" name="firstName" placeholder="First Name" data-rule-required="true" data-msg-required="First Name is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="lastName">Last Name</label>
							<input type="text" class="form-control" name="lastName" placeholder="Last Name" data-rule-required="true" data-msg-required="Last Name is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="username">Username</label>
							<input type="text" class="form-control input-username" name="username" placeholder="Username" data-rule-required="true" data-msg-required="Username is Required">
							<div class="unExist error"></div>
						</div>
					</div>
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" placeholder="Email" data-rule-required="true" data-msg-required="Email is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="bunk">Bunk</label>
							'. getBunks('','select',$con) .'
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="access_level">User Type</label>
							'. getAuth('',true,$_SESSION['userID'],false,$con) .'
						</div>
					</div>
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" data-rule-required="false" data-msg-required="Password is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="password_repeat">Repeat Password</label>
							<input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Enter Password Again" data-rule-required="false" data-msg-required="Password Confirmation is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle"></div>
					</div>
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 align-middle">
							<label for="prerequisites">Prerequisites</label>
							<div class="col-12 checkbox-wrap">'. getPrerequisites('',$con) .'</div>
						</div>
					</div>
					<div class="col-12 text-center">
						<input type="submit" class="btn btn-dark-green" value="Add User">
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$users = getUsers('','array',$con);
	if ($users) {
  		$content .= '<div class="table-responsive-sm"><table class="table table-sm table-sortable table-data-table table-striped">
			<thead>
      			<tr>
        			<th>Name</th>
        			<th>Username</th>
        			<th>Email</th>
					<th>User Type</th>
					<th>Bunk</th>
					<th>Action</th>
      			</tr>
    		</thead>
    		<tbody id="searchTable">';
      
		foreach ($users as $user) {
			$content .= '<tr id="form-'. $user['id'] .'">
				<td>'. $user['lastName'] .', '. $user['firstName'] .'</td>
				<td>'. $user['username'] .'</td>
				<td>'. $user['email'] .'</td>
				<td>'. $user['access'] .'</td>
				<td>'. $user['bunkName'] .'</td>
				<td>
					<div class="row row-flex d-flex">
						<div col-12 div-xs-col-12 div-sm-col-12 div-md-col-6 text-center">
							<form method="post" action="/admin/users/edit/">
								<input type="hidden" name="id" value="'. $user['id'] .'">
								<input type="submit" class="btn btn-dark-green btn-sm adminBtn" value="Edit">
							</form>
						</div>
						<div col-12 div-xs-col-12 div-sm-col-12 div-md-col-6 text-center">
							<form method="post" class="adminForm">
								<input type="hidden" name="table" value="users">
								<input type="hidden" name="id" value="'. $user['id'] .'">
								<button class="btn btn-danger btn-sm adminBtn" data-op="delete">Delete</button>
							</form>
						</div>
						
						<div col-12 div-xs-col-12 div-sm-col-12 div-md-col-12 text-center">
							<form method="post" class="" action="/reports/camper-activities/">
								<input type="hidden" name="uID" value="'. $user['id'] .'">
								<input type="hidden" name="thisUserName" value="'. $user['firstName'] .' '. $user['lastName'] .'">
								<input type="hidden" name="bunkID" value="'. $user['bunk'] .'">
								'. ((in_array($user['access_level'],$camperAccessLevels)) ? (($user['bunk'] > 0) ? '<button class="btn btn-mdb-color btn-sm adminBtn">Activities</button>' : '<button class="btn btn-mdb-color btn-sm adminBtn disabled" data-toggle="tooltip" data-placement="left" title="Camper must be assigned to a bunk before activites can be scheduled.">Activities</button>') : '') .'
							</form>
						</div>
						
					</div>
				</td>
			</tr>';
		}
		$content .= '</tbody></table></div></div>';
	} 

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>