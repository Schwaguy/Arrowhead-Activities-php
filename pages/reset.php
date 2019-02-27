<?php
	if (isset($_REQUEST['tp'])) {
		$content .= '
		<div id="login" class="container main">
			<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-6 align-middle">
					<div class="login">
						<img class="logo img-responsive img-fluid" src="/img/arrowhead_clubdays_logo-200-min.png" alt="Arrowhead Day Camp Club Day Scheduling">					
						<div class="login-panel panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Please Enter a New Password</h3>
							</div>
							<div class="panel-body">
								<form role="form" id="resetForm" name="resetForm" method="post">
									<input type="hidden" name="redirect" value="/">
									<input type="hidden" name="action" value="reset">
									<input type="hidden" name="id" value="'. $_REQUEST['sp'] .'">
									<input type="hidden" name="resetKey" value="'. $_REQUEST['tp'] .'">
									<fieldset>
										<div class="row form-row align-middle">
											<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="password">Password</label></div>
											<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required></div>
										</div>
										<div class="row form-row align-middle">
											<div class="col-12 col-xs-12 col-sm-12 col-md-5 align-middle"><label for="password_repeat">Repeat Password</label></div>
											<div class="col-12 col-xs-12 col-sm-12 col-md-7 align-middle"><input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Enter Your Password Again" required></div>
										</div>
										<div class="anti"><input type="text" name="anti" value=""></div>
										<div class="row">
											<div class="col col-12 text-center">
												<input type="submit" class="btn btn-default btn-lg" value="Reset Password" />
											</div>
										</div>
									</fieldset>
								</form>
								'. $loginError .'
							</div>
						</div><!-- /.login-panel --> 
					</div><!-- /.login --> 
				</div>
			</div>
		</div>';
	} else {
		$content .= '
		<div id="login" class="container main">
			<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-6 align-middle">
					<div class="login">
						<img class="logo img-responsive img-fluid" src="/img/arrowhead_clubdays_logo-200-min.png" alt="Arrowhead Day Camp Club Day Scheduling">					
						<div class="login-panel panel panel-default">
							<div class="panel-heading text-center">
								<h3 class="panel-title text-center">Something went wrong, Please try again.</h3>
							</div>
							<div class="panel-body text-center">
								<a href="/login/" class="btn btn-default btn-lg">Back to Login</a>
							</div>
						</div><!-- /.login-panel --> 
					</div><!-- /.login --> 
				</div>
			</div>
		</div>';
	}