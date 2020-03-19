<?php

	$content .= '<div class="container main">';

	$clearBunksBtn = (($_SESSION['userPermissions']['admin'] == 1) ? ' <a class="btn btn-danger btn-clear btn-clear-bunks" data-toggle="tooltip" data-placement="top" title="This will clear all Bunk Assignments" data-op="clear" data-funct="clearBunks" data-alert="Are you sure you want to clear all Bunk Assignments? This cannot be undone!" data-bunk="all"><p><i class="far fa-store-alt fa-5x"></i></p>Clear Bunks</a>' : '');

	$clearSignupsBtn = (($_SESSION['userPermissions']['admin'] == 1) ? ' <a class="btn btn-danger btn-clear btn-clear-signups" data-toggle="tooltip" data-placement="top" title="This will clear all Activity Signups before Today ('. $todayNice .')" data-op="clear-all" data-funct="clearSignups" data-alert="Are you sure you want to clear all Activity Signups before Today ('. $todayNice .')? This cannot be undone!"><p><i class="far fa-calendar-times fa-5x"></i></p>Clear Signups</a>' : '');
		
	$content .= '<h1 class="page-title">Annual Maintenance</h1>
		<div class="row justify-content-center">';

	$content .= '<div class="col-12 col-xl-10"><div class="annual-warning d-flex align-content-center"><div class="flex-fill alert-icon"><i class="far fa-exclamation-triangle fa-4x"></i></div><div class="flex-fill d-flex align-content-center flex-wrap"><div class="alert-message">CAUTION: These are very powerful tools that will remove all data prior to today ('. $todayNice .'). Use with extreme caution.</div></div></div></div>';
	

	$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-3 text-center">'. $clearBunksBtn .'</div>';
	$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-3 text-center">'. $clearSignupsBtn .'</div>';

	$content .= '<div class="col-12 col-xl-10 text-center"><p class="pt-5"><strong>Remember to update the week dates for the upcoming summer!</strong></p><p><a class="btn btn-primary" href="/admin/weeks/" title="Week Admin">Click Here to update Weeks</a></p></div>';

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>