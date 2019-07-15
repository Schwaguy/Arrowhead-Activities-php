<?php

	$logedOut = ((!empty($_REQUEST['logout'])) ? '' : '');

	$content .= '
    <div id="login" class="container main">
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-6 align-middle">
                <div class="login">
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title">Create Your Account</h2>
						</div>
						<div class="panel-body">
							<form role="form" id="loginForm" name="loginForm" action="#" method="post">
								<fieldset>
									<div class="form-group">
										<input class="form-control" placeholder="Username" name="uName" type="text" autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Password" name="uPass" type="password" value="">
									</div>
									<div class="row">
										<div class="col col-xs-12 col-sm-12 col-md-6 text-left">
											<input type="submit" class="btn btn-default btn-lg" value="Submit" />
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div><!-- /.login-panel --> 
				</div><!-- /.login --> 
            </div>
        </div>
    </div>';