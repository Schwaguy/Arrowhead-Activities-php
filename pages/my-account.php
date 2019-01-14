<?php
	
	if (isset($_POST['id'])) {
		// Get User Info
		$user = getUserInfo($_POST['id'],$con);
		$redirect = '';
		
		$content .= '<div class="container main">

			<h1 class="page-title">My Account</h1>

			<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

		include($ROOT .'/pages/admin/users/edit-user-form.php');

		$content .= '</div><!-- /col -->
			</div><!-- /row -->
		</div><!-- /container main -->';
	}
?>