<?php
$mainnav = '';

$content .= '<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-fixed-top navbar-dark primary-color navbar-custom">

	<div class="container">

	  <!-- Navbar brand -->
	  <a class="navbar-brand" href="/" title="Arrowhead Club Days"><img src="/img/logo-arrow-activities-50-min.png" class="logo img-responsive img-fluid" atl="Arrohead Day Camp Club Day Scheduling"></a>
  
	  	<button type="button" id="nav-icon" class="navbar-toggle navbar-toggler collapsed" data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" data-target="#navbarCollapse" aria-expanded="false" aria-controls="navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="sr-only">Toggle Navigation</span>
		</button>

	  <!-- Collapsible content -->
	  <div class="collapse side-collapse in navbar-collapse" id="mainNav">';

// Left Links
$linkInfo = checkPageLink($thisPg,'home');
$content .= '<!-- Links -->
		<ul class="navbar-nav mr-auto">
			<li class="nav-item '. $linkInfo['li'] .'"><a class="nav-link" href="/">Home'. $linkInfo['sr'] .'</a></li>';
if ($_SESSION['userAuth']>=3) {
	$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'my-activities') : array('li'=>'','sr'=>''));
	$content .= '<li class="nav-item '. $linkInfo['li'] .'"><a class="nav-link" href="/my-activities/">My Activities'. $linkInfo['sr'] .'</a></li>';
}
if ($_SESSION['userAuth']==3) {
	$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'my-bunk') : array('li'=>'','sr'=>''));
	$content .= '<li class="nav-item '. $linkInfo['li'] .'"><a class="nav-link" href="/my-bunk/">My Bunk'. $linkInfo['sr'] .'</a></li>';
}
if (in_array($_SESSION['userAuth'],$adminAccessLevels)) {
	// Activity Admin
	$linkInfo = (!empty($sp) ? checkPageLink($sp,'activities') : array('li'=>'','sr'=>''));
	$content .= '<li class="nav-item '. $linkInfo['li'] .'"><a class="nav-link" href="/admin/activities/overview/" title="'. siteVar('act','singular','capital') .' Admin">'. siteVar('act','plural','capital') . $linkInfo['sr'] .'</a></li>';
	
	// User Admin
	$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'users') : array('li'=>'','sr'=>''));
	$content .= '<li class="nav-item '. $linkInfo['li'] .'"><a class="nav-link" href="/admin/users/" title="User Admin">Users</a></li>';
	
	
	$content .= '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-admin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a><div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-admin">';
	
		// Bunk Admin
		$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'bunks') : array('li'=>'','sr'=>''));
		$content .= '<a class="dropdown-item '. $linkInfo['li'] .'" href="/admin/bunks/" title="Bunk Admin">Bunks</a>';

		// Week Admin
		$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'weeks') : array('li'=>'','sr'=>''));
		$content .= '<a class="dropdown-item '. $linkInfo['li'] .'" href="/admin/weeks/" title="Week Admin">Weeks</a>';

		// Period Admin
		$linkInfo = (!empty($thisPg) ? checkPageLink($thisPg,'periods') : array('li'=>'','sr'=>''));
		$content .= '<a class="dropdown-item '. $linkInfo['li'] .'" href="/admin/periods/" title="Period Admin">Periods</a>';

	$content .= '</div></li>'; 
	
	
}
$content .= '</ul><!-- Links -->';

// Right Links
$content .= '<ul class="navbar-nav ml-auto nav-flex-icons">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true"
				  aria-expanded="false">
				  <i class="fa fa-user"></i> '. (($_SESSION['userFirstName']) ? $_SESSION['userFirstName'] : 'Profile') .' </a>
				<div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
				  	<form class="dropdown-form" method="post" action="/my-account/">
						<input type="hidden" name="id" value="'. $_SESSION['userID'] .'">
						<input type="submit" class="adminBtn dropdown-item" value="My Account">
					</form>
				  	<a id="logout" class="dropdown-item" href="/logout/">Log out</a>
				</div>
			  </li>
			</ul>
	  </div>
	  <!-- Collapsible content -->

	</div>

</nav>
<!--/.Navbar-->';
$content .= '<div class="side-collapse-container">';

?>