<?php
	$content .= '
    <div id="login" class="container main">
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-6 align-middle">
                <div class="login">
					<img class="logo img-responsive img-fluid" src="/img/arrowhead_clubdays_logo-200-min.png" alt="Arrowhead Day Camp Club Day Scheduling">					
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Please Sign In</h3>
						</div>
						<div class="panel-body">
							<form role="form" id="loginForm" name="loginForm" method="post">
								<fieldset>
									<div class="form-group">
										<input class="form-control" placeholder="Username" name="uName" type="text" autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Password" name="uPass" id="loginPass" type="password" value="">
										<input type="checkbox" class="showPW" data-pw-field="#loginPass"> Show Password
									</div>
									<div class="row">
										<div class="col col-xs-12 col-sm-12 col-md-3 text-left">
											<input type="submit" class="btn btn-default btn-lg" value="Login" />
										</div>
										<div class="col col-xs-12 col-sm-12 col-md-3 text-center">
											<a href="#register" class="btn btn-default btn-lg scroll-link btn-register" id="btnRegister" title="Register" data-toggle="collapse" data-target="#register" aria-expanded="false" aria-controls="register">Register</a>
										</div>
										<div class="col col-xs-12 col-sm-12 col-md-6 text-right">
											<a href="#forgot" class="btn btn-light btn-lg scroll-link btn-register" id="btnForgot" title="Forgot Password" data-toggle="collapse" data-target="#forgot" aria-expanded="false" aria-controls="forgot">Forgot Password</a>
										</div>
									</div>
								</fieldset>
							</form>
							'. $loginError .'
						</div>
						
						<div class="collapse" id="register">
  							<div class="card card-body">
								<h3 class="card-title">Create Your Account</h3>
								<form id="registerForm" class="registerForm" name="registerForm" method="post">
									<input type="hidden" name="redirect" value="/welcome/">
									<input type="hidden" name="new-registration" value="true">
									<input type="hidden" name="access_level" value="4">
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="firstName">Camper\'s First Name</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle">
											<div class="feedback error"></div>
											<input type="text" class="form-control first-input input-firstName" name="firstName" placeholder="Camper\'s First Name" required>
										</div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="lastName">Camper\'s Last Name</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="text" class="form-control input-lastName" name="lastName" placeholder="Camper\'s Last Name" required></div>
										<div class="col-12"><div id="name-exists" class="alert alert-warning login-alert"></div></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="username">Camper\'s Username</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="text" class="form-control input-username" name="username" placeholder="Camper\'s Username" required></div>
										<div class="col-12"><div id="username-exists" class="alert alert-danger login-alert"></div></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="email">Email</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="email" class="form-control input-email" name="email" placeholder="example@email.com" required></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="password">Password</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required>
										<input type="checkbox" class="showPW" data-pw-field="#password"> Show Password</div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="password_repeat">Repeat Password</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Enter Your Password Again" required>
										<input type="checkbox" class="showPW" data-pw-field="#password_repeat"> Show Password</div>
									</div>
									<div class="anti"><input type="text" name="anti" value=""></div>
									<div class="text-right"><input type="submit" class="btn btn-default btn-lg" id="register-submit" value="Register" /></div>
								</form>
							</div>
						</div><!-- / register -->
						
						<div class="collapse" id="forgot">
  							<div class="card card-body">
								<h3 class="card-title text-center">Please enter your Username <br>and/or Email Address</h3>
								<form id="forgotForm" class="forgotForm" name="forgotForm" method="post">
									<input type="hidden" name="redirect" value="/reset-message/">
									<input type="hidden" name="action" value="forgot">
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="usernameFrogot">Username</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="text" class="form-control" name="usernameForgot" id="usernameForgot" placeholder="Your Username"></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="emailForgot">Email</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="email" class="form-control" name="emailForgot" id="emailForgot" placeholder="Your Email Address"></div>
									</div>
									<div class="anti"><input type="text" name="anti" value=""></div>
									<div class="text-right"><input type="submit" class="btn btn-default btn-lg" value="Reset Password" /></div>
								</form>
							</div>
						</div><!-- / register -->
						
					</div><!-- /.login-panel --> 
				</div><!-- /.login --> 
            </div>
        </div>
    </div>';