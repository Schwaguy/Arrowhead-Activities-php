<?php
	$editAuth = ((($_SESSION['userAuth']>2) || (($_SESSION['userAuth']==2) && ($_SESSION['userAuth']==$user['id']))) ? true : false);

	$unCheck = (($_SESSION['userPermissions']['admin']==1) ? '' : (!empty($user['username']) ? 'disabled' : ''));

	// User Edit Form
	$content .= '<form id="form-edit" class="adminForm">
					<input type="hidden" name="id" value="'. $user['id'] .'">
					<input type="hidden" name="table" value="users">
					<input type="hidden" name="active" value="1">
					<input type="hidden" name="redirect" value="'. $redirect .'">
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="firstName">First Name</label>
							<input type="text" class="form-control" name="firstName" value="'. $user['firstName'] .'" placeholder="First Name" data-rule-required="true" data-msg-required="First Name is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="lastName">Last Name</label>
							<input type="text" class="form-control" name="lastName" value="'. $user['lastName'] .'" placeholder="Last Name" data-rule-required="true" data-msg-required="Last Name is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="username">Username</label>
							<input type="text" class="form-control input-username" name="username" value="'. $user['username'] .'" placeholder="Username" data-rule-required="true" data-msg-required="Username is Required" '. $unCheck .'>
							<div class="unExist error"></div>
						</div>
					</div>
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for"">Email</label>
							<input type="email" class="form-control" name="email" value="'. $user['email'] .'" placeholder="Email" data-rule-required="true" data-msg-required="Email is Required">
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="bunk">Bunk</label>
							'. getBunks($user['bunk'],'select',$con) .'
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="access_level">User Type</label>
							'. getAuth($user['access_level'],true,$_SESSION['userAuth'],$editAuth,$con) .'
						</div>
					</div>
					<div class="row row-flex form-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="password">New Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" data-rule-required="false" data-msg-required="Password is Required">
							<input type="checkbox" class="showPW" data-pw-field="#password" name="showPW"> Show Password
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 align-middle">
							<label for="password_repeat">Repeat New Password</label>
							<input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Enter Password Again" data-rule-required="false" data-msg-required="Password Confirmation is Required">
							<input type="checkbox" class="showPW" data-pw-field="#password_repeat" name="showPW"> Show Password
						</div>
					</div>';
	$prereqDisplay = (($_SESSION['userPermissions']['edit']==1) ? '' : 'style="display: none"');
	//if (in_array($user['access_level'],array(4,5))) {
		$content .= '<div class="row row-flex form-row" '. $prereqDisplay .'>
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 align-middle">
				<label for="prerequisites">Prerequisites</label>
				<div class="col-12 checkbox-wrap">'. getPrerequisites(explode(',',$user['prerequisites']),$con) .'</div>
			</div>
		</div>'; 
	//}
	$content .= '<div class="row row-flex form-row">
					<div class="col-12 text-center">
						<button type="button" class="btn btn-dark-green adminBtn" data-op="update">Save Updates</button>
					</div>
				</div>
						
				</form>';
?>