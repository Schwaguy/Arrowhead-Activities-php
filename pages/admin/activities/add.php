<?php

	$weekSel = ((isset($_POST['week'])) ? $_POST['week'] : '');
	$periodSel = ((isset($_POST['period'])) ? $_POST['period'] : '');
	$day = ((isset($_POST['day'])) ? $_POST['day'] : '');
	$monCheck = ''; 
	$tuesCheck = ''; 
	$wedCheck = ''; 
	$thursCheck = ''; 
	$friCheck = ''; 
	switch ($day) {
		case 1:
			$monCheck = 'checked';
			break;
		case 2:
			$tuesCheck = 'checked';
			break;
		case 3:
			$wedCheck = 'checked';
			break;
		case 3:
			$thursCheck = 'checked';
			break;
		case 3:
			$friCheck = 'checked';
			break;
	}

	$content .= '<div class="container main">
		<h1 class="page-title">Add New Activity</h1>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10">';

	// New Activity Form
	$content .= '<form id="form-add" class="adminForm activity-admin">
					
					<input type="hidden" name="id" value="">
					<input type="hidden" name="table" value="activities">
					<input type="hidden" name="added" value="'. $now .'">
					<input type="hidden" name="addedBy" value="'. $_SESSION['userID'] .'">
					<input type="hidden" name="updated" value="'. $now .'">
					<input type="hidden" name="updateBy" value="'. $_SESSION['userID'] .'">
					<input type="hidden" name="active" value="1">
					<input type="hidden" name="redirect" value="/admin/activities/overview/">
					
					<div class="col-12">
						<p><label for="type">Activity Type</label><br>
						<input type="text" list="typeList" id="typeInput" class="form-control combobox typeInput" placeholder="'. siteVar('act','singular','capital') .' Type" data-rule-required="true" data-msg-required="'. siteVar('act','singular','capital') .' Type is Required">
						<datalist id="typeList">'. getActivityTypes($con) .'</datalist>
						<input type="hidden" name="type" id="typeInput-hidden" class="hiddenInput"></p>
						
						<p class="oneTime hide">
							<input type="hidden" name="oneTime" value="0">
							<input type="checkbox" name="oneTime" value="1"> <label for="oneTime">Check if this is a One-Time-Only Activity
						</p>
						
						<p><label for="description">Description</label><br><textarea name="description" class="form-control" placeholder="'. siteVar('act','singular','capital') .' Description"></textarea></p>
					</div>
					
					<div class="row align-items-center d-flex">
						<div class="col-12 d-lg-flex">
							<div class="col-12 col-sm-12 col-md-12 col-md-12 col-lg-8">
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
										<p><label for="location">Location</label><br><input type="text" name="location" class="form-control" value="" placeholder="Location" data-rule-required="false" data-msg-required="Location is Required"></p>
									</div>
									<div class="col-12 col-sm-12 col-md-6 col-lg-6">
										<p><label for="capacity">Capacity</label><br><input type="number" name="capacity" class="form-control" value="" placeholder="Capacity" min="0" data-rule-required="true" data-msg-required="Capacity is Required"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6 col-md-6 col-lg-6">
										<p><label for="week">Week</label><br>'. getWeeks('select',$weekSel,false,true,$con) .'</p>
									</div>
									<div class="col-12 col-sm-12 col-md-6 col-lg-6">
										<p><label for="period">Period</label><br>'. getPeriods($periodSel,false,true,'select',$con) .'</p>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-4">
								<p><label for="groups">Eligible Age Groups</label><br>'. getAgeGroups('',true,true,$con) .'</p>
							</div>
						</div>
						<div class="col-12">
							<div class="col-12">
								<h4>Days Offered</h4>
								<div class="col-12 checkbox-wrap">
									<div class="row d-sm-flex checkbox-group">
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox monday"> <input type="checkbox" class="require-one" name="monday" value="1" '. $monCheck .'> <label for "monday"> Monday</label></p></div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox tuesday"> <input type="checkbox" class="require-one" name="tuesday" value="1" '. $tuesCheck .'> <label for "tuesday"> Tuesday</label></p></div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox wednesday"> <input type="checkbox" class="require-one" name="wednesday" value="1" '. $wedCheck .'> <label for "wednesday"> Wednesday</label></p></div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox thursday"> <input type="checkbox" class="require-one" name="thursday" value="1" '. $thursCheck .'> <label for "thursday"> Thursday</label></p></div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox friday"> <input type="checkbox" class="require-one" name="friday" value="1" '. $friCheck .'> <label for "friday"> Friday</label></p></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6"><div class="col-12">
							<h4>Prerequisites</h4>
							<div class="col-12 checkbox-wrap">'. getPrerequisites('',$con) .'</div>
						</div></div>
						<div class="col-12 col-sm-12 col-md-6"><div class="col-12">
							<h4>Restrictions</h4>
							<div class="col-12 checkbox-wrap">'. getRestrictions('','checkboxes',$con) .'</div>
						</div></div>
					</div>
					<div class="row">
						<div class="col-12"><div class="col-12 text-center">
							<button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add '. siteVar('act','singular','capital') .'</button>
						</div></div>
					</div>
				</form>';

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>