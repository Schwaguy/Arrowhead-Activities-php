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
										<input class="form-control" placeholder="Password" name="uPass" type="password" value="">
									</div>
									<div class="row">
										<div class="col col-xs-12 col-sm-12 col-md-6 text-left">
											<input type="submit" class="btn btn-default btn-lg" value="Login" />
										</div>
										<div class="col col-xs-12 col-sm-12 col-md-6 text-right">
											<a href="#register" class="btn btn-default btn-lg scroll-link btn-register" title="Register" data-toggle="collapse" data-target="#register" aria-expanded="false" aria-controls="register">Register</a>
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
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">First Name</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle">
											<input type="text" class="form-control first-input" name="firstName" placeholder="Your First Name" required>
										</div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Last Name</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="text" class="form-control" name="lastName" placeholder="Your Last Name" required></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Your Bunk</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle">'. getBunks('',$con) .'</div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Username</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="text" class="form-control" name="username" placeholder="Your Username" required></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Email</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="email" class="form-control" name="email" placeholder="Your Email Address"></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Password</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required></div>
									</div>
									<div class="row form-row align-middle">
										<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for"">Repeat Password</label></div>
										<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Enter Your Password Again" required></div>
									</div>
									<div class="text-right"><input type="submit" class="btn btn-default btn-lg" value="Register" /></div>
								</form>
							</div>
						</div>
					</div><!-- /.login-panel --> 
				</div><!-- /.login --> 
            </div>
        </div>
    </div>';