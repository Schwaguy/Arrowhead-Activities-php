<?php

	if (isset($_POST['id'])) {
		// Get Activity Info
		$activity = getActivityinfo ($_POST['id'],$con);
		$monCheck = (($activity['days']['monday']==1) ? 'checked' : ''); 
		$tuesCheck = (($activity['days']['tuesday']==1) ? 'checked' : '');
		$wedCheck = (($activity['days']['wednesday']==1) ? 'checked' : '');
		$thursCheck = (($activity['days']['thursday']==1) ? 'checked' : '');
		$friCheck = (($activity['days']['friday']==1) ? 'checked' : '');
		
		$dayChecks = array(
			1=>(($activity['days']['monday']==1) ? 'checked' : ''), 
			2=>(($activity['days']['tuesday']==1) ? 'checked' : ''),
			3=>(($activity['days']['wednesday']==1) ? 'checked' : ''),
			4=>(($activity['days']['thursday']==1) ? 'checked' : ''),
			5=>(($activity['days']['friday']==1) ? 'checked' : '')
		);
		
		$prerequisites = getPrerequisites(explode(',',$activity['prerequisites']),$con);
		$restrictions = getRestrictions(explode(',',$activity['restrictions']),$con);
		
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
		$weeks = getWeeks('select',$activity['week'],false,true,$con);
		$periods = getPeriods($activity['period'],false,false,'select',$con);
		$periods2 = getPeriods('',false,false,'array',$con);
		$content .= '<form id="form-edit" class="adminForm activity-admin">
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
										<div class="row d-sm-flex checkbox-group">';
							foreach ($weekdays as $key=>$val) {
								$cbClass = ''; 
								$cbDisable = ''; 
								if ($periods2[$activity['period']]['days'][$key] == 0) {
									$cbClass = 'text-muted'; 
									$cbDisable = 'disabled="disabled"'; 
								}
								$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
									<p class="checkbox '. $val .' '. $cbClass .'"> 
										<input type="hidden" name="'. $val .'" value="0" />
										<input type="checkbox" class="require-one" name="'. $val .'" value="1" '. $dayChecks[$key] .' '. $cbDisable .'> <label for "'. $val .'"> '. ucfirst($val) .'</label>
									</p>
								</div>';
							}			
							$content .= '</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12 col-md-6"><div class="col-12">
								<h4>Prerequisites</h4>
								<div class="col-12 checkbox-wrap">'. $prerequisites .'</div>
							</div></div>
							<div class="col-12 col-sm-12 col-md-6"><div class="col-12">
								<h4>Restrictions</h4>
								<div class="col-12 checkbox-wrap">'. $restrictions .'</div>
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