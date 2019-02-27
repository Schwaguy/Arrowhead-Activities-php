<?php

$pageTitle = 'Dashboard'; 
$page = 'dash'; 
$content .= '<div class="container main"><h1>Welcome '. $_SESSION['userFirstName'] .'</h1><div id="dashboard">'; 

if (in_array($_SESSION['userAuth'],$adminAccessLevels)) { // Admin/Office
	$content .= '<div class="row d-flex justify-content-around" id="dash-icons">
				<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-primary" href="/admin/activities/overview/" title="'. siteVar('act','singular','capital') .' Admin">
						<div class="panel-heading">
							<div class="row">
								<div class="col-12 text-center">
									<i class="far fa-calendar-edit fa-5x"></i>
								</div>
							</div>
						</div>
						<div class="panel-footer text-center">
							<p class="clearfix"><span class="pull-left">'. siteVar('act','plural','capital') .'</span>
							<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
						</div>
					</a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-primary" href="/admin/users/" title="User Admin">
						<div class="panel-heading">
							<div class="row">
								<div class="col-12 text-center">
									<i class="far fa-users fa-5x"></i>
								</div>
							</div>
						</div>
						<div class="panel-footer text-center">
							<p class="clearfix"><span class="pull-left">Users</span>
							<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
						</div>
					</a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/admin/bunks/" title="Bunk Admin">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
									<i class="far fa-store-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Bunks</span>
                            <span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                        </div>
                    </a>
                </div>
                
            <!--</div>
            <!-- /.row -->';
	$content .= '<!--<div class="row d-flex justify-content-around" id="dash-icons">-->
				<div class="w-100 d-none d-md-block"></div>
				<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/admin/weeks/" title="Week Admin">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
									<i class="far fa-calendar-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Weeks</span>
                        	<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                 		</div>
                    </a>
                </div>
				<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/admin/periods/" title="Period Admin">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
									<i class="far fa-clock fa-5x"></i>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Periods</span>
                          	<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                      	</div>
                    </a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<form class="dropdown-form" method="post" action="/my-account/">
						<input type="hidden" name="id" value="'. $_SESSION['userID'] .'">
						<a class="panel panel-primary submitLink" href="#" title="My Profile">
							<div class="panel-heading">
								<div class="row">
									<div class="col-12 text-center">
										<i class="far fa-user fa-5x"></i>
									</div>
								</div>
							</div>
							<div class="panel-footer text-center">
								<p class="clearfix"><span class="pull-left">My Profile</span>
								<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
							</div>
						</a>
					</form>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/logout/">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <i class="far fa-sign-out-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Sign Out</span>
                       		<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                     	</div>
                    </a>
                </div>
            </div>
            <!-- /.row -->';
} elseif ($_SESSION['userAuth']==6) {  // Directors
	$content .= '<div class="row d-flex justify-content-around" id="dash-icons">
				<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/admin/bunks/" title="Bunk Schedules">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
									<i class="far fa-store-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Bunk Schedules</span>
                            <span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<form class="dropdown-form" method="post" action="/my-account/">
						<input type="hidden" name="id" value="'. $_SESSION['userID'] .'">
						<a class="panel panel-primary submitLink" href="#" title="My Profile">
							<div class="panel-heading">
								<div class="row">
									<div class="col-12 text-center">
										<i class="far fa-user fa-5x"></i>
									</div>
								</div>
							</div>
							<div class="panel-footer text-center">
								<p class="clearfix"><span class="pull-left">My Profile</span>
								<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
							</div>
						</a>
					</form>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-primary" href="/my-activities/" title="My '. siteVar('act','plural','capital') .'">
						<div class="panel-heading">
							<div class="row">
								<div class="col-12 text-center">
									<i class="far fa-calendar fa-5x"></i>
								</div>
							</div>
						</div>
						<div class="panel-footer text-center">
							<p class="clearfix"><span class="pull-left">My '. siteVar('act','plural','capital') .'</span>
							<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
						</div>
					</a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/logout/">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <i class="far fa-sign-out-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer text-center">
                                <p class="clearfix"><span class="pull-left">Sign Out</span>
                                <span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                            </div>
                        </a>
                    </a>
                </div>
            </div>
            <!-- /.row -->';
} elseif ($_SESSION['userAuth']==3) {  // Counselors
	$content .= '<div class="row d-flex justify-content-around" id="dash-icons">
				<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/my-bunk/">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
									<i class="far fa-store-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">My Bunk</span>
                          	<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                     	</div>
                    </a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<form class="dropdown-form" method="post" action="/my-account/">
						<input type="hidden" name="id" value="'. $_SESSION['userID'] .'">
						<a class="panel panel-primary submitLink" href="#" title="My Profile">
							<div class="panel-heading">
								<div class="row">
									<div class="col-12 text-center">
										<i class="far fa-user fa-5x"></i>
									</div>
								</div>
							</div>
							<div class="panel-footer text-center">
								<p class="clearfix"><span class="pull-left">My Profile</span>
								<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
							</div>
						</a>
					</form>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-primary" href="/my-activities/" title="My '. siteVar('act','plural','capital') .'">
						<div class="panel-heading">
							<div class="row">
								<div class="col-12 text-center">
									<i class="far fa-calendar fa-5x"></i>
								</div>
							</div>
						</div>
						<div class="panel-footer text-center">
							<p class="clearfix"><span class="pull-left">My '. siteVar('act','plural','capital') .'</span>
							<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
						</div>
					</a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/logout/">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <i class="far fa-sign-out-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer text-center">
                                <p class="clearfix"><span class="pull-left">Sign Out</span>
                                <span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                            </div>
                        </a>
                    </a>
                </div>
            </div>
            <!-- /.row -->';
} else { // Campers/CITs
	
	// Assigned Bunk & Counselor
	$content .= '<div class="row d-flex justify-content-around" id="dash-icons">
                	<div class="col-lg-4 col-md-4">
						<p><strong>My Bunk:</strong> '. ((isset($_SESSION['bunkInfo']['name'])) ? $_SESSION['bunkInfo']['name'] : 'Not Assigned Yet') .'</p>
					</div>
					<div class="col-lg-4 col-md-4">
						<p><strong>My Counselor:</strong> '. ((isset($_SESSION['bunkInfo']['counselor'])) ? $_SESSION['bunkInfo']['counselor'] : 'Not Assigned Yet') .'</p>
					</div>
				</div>';
	$content .= '<div class="row d-flex justify-content-around" id="dash-icons">
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<form class="dropdown-form" method="post" action="/my-account/">
						<input type="hidden" name="id" value="'. $_SESSION['userID'] .'">
						<a class="panel panel-primary submitLink" href="#" title="My Profile">
							<div class="panel-heading">
								<div class="row">
									<div class="col-12 text-center">
										<i class="far fa-user fa-5x"></i>
									</div>
								</div>
							</div>
							<div class="panel-footer text-center">
								<p class="clearfix"><span class="pull-left">My Profile</span>
								<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
							</div>
						</a>
					</form>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-primary" href="/my-activities/" title="My Activities">
						<div class="panel-heading">
							<div class="row">
								<div class="col-12 text-center">
									<i class="far fa-calendar fa-5x"></i>
								</div>
							</div>
						</div>
						<div class="panel-footer text-center">
							<p class="clearfix"><span class="pull-left">My Activities</span>
							<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
						</div>
					</a>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-2">
					<a class="panel panel-yellow" href="/logout/">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <i class="far fa-sign-out-alt fa-5x"></i>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer text-center">
                        	<p class="clearfix"><span class="pull-left">Sign Out</span>
                         	<span class="pull-right"><i class="far fa-arrow-circle-right"></i></span></p>
                     	</div>
                    </a>
                </div>
            </div>
            <!-- /.row -->';
}

$content .= '</div><!-- /#dashboard --></div><!-- /container-fluid -->'; 
?>