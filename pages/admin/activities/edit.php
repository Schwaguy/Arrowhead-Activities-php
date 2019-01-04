<?php

	if (isset($_POST['id'])) {
		// Get Activity Info
		$activity = getActivityinfo ($_POST['id'],$con);
		$monCheck = (($activity['days']['monday']==1) ? 'checked' : ''); 
		$tuesCheck = (($activity['days']['tuesday']==1) ? 'checked' : '');
		$wedCheck = (($activity['days']['wednesday']==1) ? 'checked' : '');
		$thursCheck = (($activity['days']['thursday']==1) ? 'checked' : '');
		$friCheck = (($activity['days']['friday']==1) ? 'checked' : '');
		$prerequisites = getPrerequisites(explode(',',$activity['prerequisites']),$con);

		$content .= '<div class="container main">
			
			<div class="quick-buttons">
				<form method="post" action="/admin/activities/view-signups/">
					<input type="hidden" name="id" value="'. $activity['id'] .'">
					<input type="submit" class="btn btn-primary" value="View Signups">
				</form>
			</div>
			
			<h1 class="page-title">Edit '. siteVar('act','singular','capital') .'</h1>
			
			<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10">';

		// New Activity Form
		$ageGroups = getAgeGroups($activity['groups'],true,true,$con);
		$weeks = getWeeks(true,$activity['week'],false,true,$con);
		$periods = getPeriods($activity['period'],false,false,'select',$con);
		$content .= '<form id="form-edit" class="adminForm">
						<input type="hidden" name="id" value="'. $activity['id'] .'">
						<input type="hidden" name="table" value="activities">
						<input type="hidden" name="updated" value="'. $now .'">
						<input type="hidden" name="updateBy" value="'. $_SESSION['userID'] .'">
						<input type="hidden" name="active" value="1">
						<input type="hidden" name="redirect" value="/admin/activities/overview/">

						<div class="col-12">
							<p><label for="name">Name</label><br><input name="name" class="form-control" value="'. $activity['name'] .'" placeholder="'. siteVar('act','singular','capital') .' Name" data-rule-required="true" data-msg-required="'. siteVar('act','singular','capital') .' Name is Required"></p>
							<p><label for="description">Description</label><br><textarea name="description" class="form-control" placeholder="'. siteVar('act','singular','capital') .' Description">'. $activity['description'] .'</textarea></p>
						</div>

						<div class="row d-flex">
							<div class="col-12 d-lg-flex">
								<div class="col-12 col-sm-12 col-md-12 col-md-12 col-lg-8">
									<div class="row">
										<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
											<p><label for="location">Location</label><br><input type="text" name="location" class="form-control" value="'. $activity['location'] .'" placeholder="Location" data-rule-required="true" data-msg-required="Location is Required"></p>
										</div>
										<div class="col-12 col-sm-12 col-md-6 col-lg-6">
											<p><label for="capacity">Capacity</label><br><input type="number" name="capacity" class="form-control" value="'. $activity['capacity'] .'" placeholder="Capacity" min="0" data-rule-required="true" data-msg-required="Capacity is Required"></p>
										</div>
									</div>
									<div class="row">
										<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
											<p><label for="week">Week</label><br>'. $weeks .'</p>
										</div>
										<div class="col-12 col-sm-12 col-md-6 col-lg-6">
											<p><label for="period">Period</label><br>'. $periods .'</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-4">
									<p><label for="groups">Eligible Age Groups</label><br>'. $ageGroups .'</p>
								</div>
							</div>
							<div class="col-12">
								<div class="col-12">
									<h4>Days Offered</h4>
									<div class="col-12 checkbox-wrap">
										<div class="row d-sm-flex checkbox-group">
											<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
												<p class="checkbox"> 
													<input type="hidden" name="monday" value="0" />
													<input type="checkbox" class="require-one" name="monday" value="1" '. $monCheck .'> <label for "monday"> Monday</label>
												</p>
											</div>
											<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
												<p class="checkbox"> 
													<input type="hidden" name="tuesday" value="0" />
													<input type="checkbox" class="require-one" name="tuesday" value="1" '. $tuesCheck .'> <label for "tuesday"> Tuesday</label>
												</p>
											</div>
											<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
												<p class="checkbox"> 
													<input type="hidden" name="wednesday" value="0" />
													<input type="checkbox" class="require-one" name="wednesday" value="1" '. $wedCheck .'> <label for "wednesday"> Wednesday</label>
												</p>
											</div>
											<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
												<p class="checkbox"> 
													<input type="hidden" name="thursday" value="0" />
													<input type="checkbox" class="require-one" name="thursday" value="1" '. $thursCheck .'> <label for "thursday"> Thursday</label>
												</p>
											</div>
											<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
												<p class="checkbox"> 
													<input type="hidden" name="friday" value="0" />
													<input type="checkbox" class="require-one" name="friday" value="1" '. $friCheck .'> <label for "friday"> Friday</label>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12"><div class="col-12">
								<h4>Prerequisites</h4>
								<div class="col-12 checkbox-wrap">'. $prerequisites .'</div>
							</div></div>
						</div>
						<div class="row">
							<div class="col-12"><div class="col-12 text-center">
								<button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update '. siteVar('act','singular','capital') .'</button>
							</div></div> 
						</div>
					</form>';

			$content .= '</div><!-- /col -->
			</div><!-- /row -->
		</div><!-- /container main -->';
	} else {
		$content .= '<h1 class="page-title text-center">Nothing Selected</h1>';
	}
?>