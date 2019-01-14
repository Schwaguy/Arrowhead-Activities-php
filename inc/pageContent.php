<?php

$content .= '<div id="wrapper">';

include($ROOT .'/inc/mainnav.php');

$content .= '<div id="page-wrapper">';

if (!empty($_REQUEST['op'])) {
	
	$adminBtns .= $backBtn;
	
	if ($_REQUEST['op'] == 'admin') {
		if (!empty($_REQUEST['type'])) {
			include($ROOT .'/pages/admin.php');	
		}
		else {
			$content .= '<p class="errorMessage">ERROR - No Admin Type Selected</p>'; 
		}
	}
	elseif ($_REQUEST['op'] == 'report') {
		if (!empty($_REQUEST['type'])) {
			include('pages/reports.php');
		} else {
			$content .= '<p class="errorMessage">ERROR - No Report Type Selected</p>'; 
		}
	}
} else {
	/*if (!empty($thisPg)) {
		include('pages/'. $thisPg .'.php');
	} else {
		if ($_SESSION['userAuth']<=2) { 
			include('pages/dashboard-admin.php');
		} elseif ($_SESSION['userAuth']==3) {
			include('pages/dashboard-counselor.php');
		} else {
			include('pages/dashboard-camper.php');
		}
	}*/
	if (!empty($tp)) {
		include('pages/'. $pg .'/'. $sp .'/'. $tp .'.php');
	} elseif (!empty($sp)) {
		include('pages/'. $pg .'/'. $sp .'.php');
	} elseif (!empty($pg)) {
		include('pages/'. $pg .'.php');
	} else {
		include($ROOT .'/pages/dashboard.php');
		/*if ($_SESSION['userAuth']<=2) { 
			include($ROOT .'/pages/dashboard-admin.php');
		} elseif ($_SESSION['userAuth']==3) {
			//window.location('/my-bunk/');
			include($ROOT .'/pages/dashboard-counselor.php');
		} else {
			include($ROOT .'/pages/dashboard-camper.php');
		}*/
	}
}

$content .= '</div><!-- /#page-wrapper -->';
$content .= '</div><!-- /#wrapper -->';

?>