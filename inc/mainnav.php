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
$content .= '<!-- Links -->
		<ul class="navbar-nav mr-auto">
		  <li class="nav-item active">
			<a class="nav-link" href="/">Home
			  <span class="sr-only">(current)</span>
			</a>
		  </li>
		  <li>
			<a class="nav-link" href="/my-activities/">My Activities</a>
		  </li>';

if ($_SESSION['userAuth']<3) {
	$content .= '<!-- Admin Dropdown -->
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
			<div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
			  	<a class="dropdown-item" href="/admin/activities/overview/">Activities</a>
				<a class="dropdown-item" href="/admin/users/">Users</a>
			  	<a class="dropdown-item" href="/admin/bunks/">Bunks</a>
			  	<a class="dropdown-item" href="/admin/weeks/">Weeks</a>
				<a class="dropdown-item" href="/admin/periods/">Periods</a>
			</div>
		  </li>';
}
$content .= '</ul><!-- Links -->';

// Right Links
$content .= '<ul class="navbar-nav ml-auto nav-flex-icons">
			  <li class="nav-item">
				<a class="nav-link" href="#">
				  <i class="fa fa-gear"></i> Settings</a>
			  </li>
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