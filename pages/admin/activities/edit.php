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
		
		$content .= '<div class="container main">';
		/*	
		$content .= '<div class="quick-buttons">
				<form method="post" action="/admin/activities/view-signups/" class="inline">
					<input type="hidden" name="id" value="'. $activity['id'] .'">
					<input type="submit" class="btn btn-primary" value="View Signups">
				</form>';
		
		if ($_SESSION['userPermissions']['report'] == 1) {
			$content .= ' <a class="btn btn-primary printLink inline" data-camper="" data-week="" data-bunk="" data-activity="'. $activity['id'] .'">Print Signups</a>';
		}
		
		$content .= '</div>';*/
		
		$content .= '<div class="row">
			<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<h1 class="page-title">Edit '. siteVar('act','singular','capital') .'</h1>
			</div>
			<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
				<form method="post" action="/admin/activities/view-signups/" class="inline">
					<input type="hidden" name="id" value="'. $activity['id'] .'">
					<input type="submit" class="btn btn-primary" value="View Signups">
				</form>';
		if ($_SESSION['userPermissions']['report'] == 1) {
			$content .= ' <a class="btn btn-primary printLink inline" data-camper="" data-week="" data-bunk="" data-activity="'. $activity['id'] .'">Print Signups</a>';
		}
		$content .= '</div></div>';
			
		$content .= '<div class="row justify-content-md-center">
				<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10">';

		// New Activity Form
		$periodsArr = getPeriods('',false,false,'array',$con);
		$content .= '<form id="form-edit" class="adminForm activity-admin">
						<input type="hidden" name="id" value="'. $activity['id'] .'">
						<input type="hidden" name="table" value="activities">
						<input type="hidden" name="updated" value="'. $now .'">
						<input type="hidden" name="updateBy" value="'. $_SESSION['userID'] .'">
						<input type="hidden" name="active" value="1">
						<input type="hidden" name="redirect" value="/admin/activities/overview/">

						<div class="col-12">
							<p><label for="type">Activity Type</label><br>
							<input type="text" list="typeList" id="typeInput" class="form-control combobox typeInput" value="'. $activity['name'] .'" placeholder="'. siteVar('act','singular','capital') .' Type" data-rule-required="true" data-msg-required="'. siteVar('act','singular','capital') .' Type is Required">
							<datalist id="typeList">'. getActivityTypes($con) .'</datalist>
							<input type="hidden" name="type" id="typeInput-hidden" class="hiddenInput" value="'. $activity['type'] .'"></p>

							<p class="oneTime">
								<input type="hidden" name="oneTime" value="0">
								<input type="checkbox" name="oneTime" class="oneTimeCheck" value="1"> <label for="oneTime">Check if this is a One-Time-Only Activity
							</p>
							
							
							<p><label for="description">Description</label><br><textarea name="description" class="form-control" placeholder="'. siteVar('act','singular','capital') .' Description">'. $activity['description'] .'</textarea></p>
						</div>

						<div class="row d-flex">
							<div class="col-12 d-lg-flex">
								<div class="col-12 col-sm-12 col-md-12 col-md-12 col-lg-8">
									<div class="row">
										<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
											<p><label for="location">Location</label><br><input type="text" name="location" class="form-control" value="'. $activity['location'] .'" placeholder="Location" data-rule-required="false" data-msg-required="Location is Required"></p>
										</div>
										<div class="col-12 col-sm-12 col-md-6 col-lg-6">
											<p><label for="capacity">Capacity</label><br><input type="number" name="capacity" class="form-control" value="'. $activity['capacity'] .'" placeholder="Capacity" min="0" data-rule-required="true" data-msg-required="Capacity is Required"></p>
										</div>
									</div>
									<div class="row">
										<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
											<p><label for="week">Week</label><br>'. getWeeks('select',$activity['week'],false,true,$con) .'</p>
										</div>
										<div class="col-12 col-sm-12 col-md-6 col-lg-6">
											<p><label for="period">Period</label><br>'. getPeriods($activity['period'],false,false,'select',$con) .'</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-4">
									<p><label for="groups">Eligible Age Groups</label><br>'. getAgeGroups($activity['groups'],true,true,$con) .'</p>
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
								if ($periodsArr[$activity['period']]['days'][$key] == 0) {
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
								<div class="col-12 checkbox-wrap">'. getPrerequisites(explode(',',$activity['prerequisites']),$con) .'</div>
							</div></div>
							<div class="col-12 col-sm-12 col-md-6"><div class="col-12">
								<h4>Restrictions</h4>
								<div class="col-12 checkbox-wrap">'. getRestrictions(explode(',',$activity['restrictions']),'checkboxes',$con) .'</div>
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