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
	if ($pg == 'reset') {
		include($ROOT .'/pages/reset.php');
	} elseif (!empty($tp)) {
		include('pages/'. $pg .'/'. $sp .'/'. $tp .'.php');
	} elseif (!empty($sp)) {
		include('pages/'. $pg .'/'. $sp .'.php');
	} elseif (!empty($pg)) {
		include('pages/'. $pg .'.php');
	} else {
		include($ROOT .'/pages/dashboard.php');
	}
}

$content .= '</div><!-- /#page-wrapper -->';
$content .= '</div><!-- /#wrapper -->';

?>