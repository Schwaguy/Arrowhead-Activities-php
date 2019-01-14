<?php

	$content .= '<div class="container main">
		
		<div class="quick-buttons"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addPeriod" aria-expanded="false" aria-controls="addPeriod">Add New Period</button></div>
		
		<h1 class="page-title">'. siteVar('act','singular','capital') .' Periods</h1>
		
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$header = '<div class="w-100 d-none d-md-block"><div class="list-group-item list-group-header row d-flex">
				<div class="col-3 col-header">Period Name</div>
				<div class="col-3 col-header">Age Group</div>
				<div class="col-3 col-header">Times</div>
			</div></div>';

	// New Period Form
	$ageGroups = getAgeGroups('',true,true,$con);
	$content .= '<div class="collapse collapse-form add-item-box" id="addPeriod">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm add-form list-group-item row align-items-center d-flex">
					<div class="col-12 col-xs-12 col-sm-12 col-md-9">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="periods">
						<input type="hidden" name="active" value="1">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Period Name</label><input name="name" class="form-control" value="" placeholder="Period Name" data-rule-required="true" data-msg-required="Period Name is Required"></p></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center "><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Age Groups</label>'. $ageGroups .'</p></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center">
								<p><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Start Time</label><input type="text" name="startTime" class="form-control" value="" placeholder="Start Time (hh:mm:ss)" data-rule-required="true" data-msg-required="Start Time is Required"></p>
								<p><label class="w-100 d-block d-xs-block d-sm-block d-md-none">End Time</label><input type="text" name="endTime" class="form-control" value="" placeholder="End Time (hh:mm:ss)" data-rule-required="true" data-msg-required="EndTime is Required"></p>
							</div>
							<div class="col-12">
								<div class="checkbox-wrap">
									<div class="row d-sm-flex checkbox-group">
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"><input type="checkbox" class="require-one" name="monday" value="1"> <label for "monday"> Monday</label></p>
										</div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"><input type="checkbox" class="require-one" name="tuesday" value="1"> <label for "tuesday"> Tuesday</label></p>
										</div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"><input type="checkbox" class="require-one" name="wednesday" value="1"> <label for "wednesday"> Wednesday</label></p>
										</div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"><input type="checkbox" class="require-one" name="thursday" value="1"> <label for "thursday"> Thursday</label></p>
										</div>
										<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"><input type="checkbox" class="require-one" name="friday" value="1"> <label for "friday"> Friday</label></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-xs-12 col-sm-12 col-md-3 d-md-flex">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add Period</button></div>
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$periods = getPeriods('',false,false,'array',$con);
	if($periods) {
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$content .= $header;

		foreach($periods as $period) {
			$dayChecks = array(
				1=>(($period['days']['1']==1) ? 'checked' : ''), 
				2=>(($period['days']['2']==1) ? 'checked' : ''),
				3=>(($period['days']['3']==1) ? 'checked' : ''),
				4=>(($period['days']['4']==1) ? 'checked' : ''),
				5=>(($period['days']['5']==1) ? 'checked' : '')
			);
			
			$ageGroups = getAgeGroups($period['groups'],true,true,$con);
			$content .= '<form id="form-'. $period['id'] .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-8">
					<input type="hidden" name="table" value="periods">
					<input type="hidden" name="id" value="'. $period['id'] .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Period Name</label><input name="name" class="form-control" value="'. $period['name'] .'" data-rule-required="true" data-msg-required="Bunk Name is Required"></p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Age Groups</label>'. $ageGroups .'</p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center">
							<p><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Start Time</label><input type="text" name="startTime" class="form-control" value="'. $period['startTime'] .'" placeholder="Start Time (hh:mm:ss)" data-rule-required="true" data-msg-required="Start Time is Required"></p>
							<p><label class="w-100 d-block d-xs-block d-sm-block d-md-none">End Time</label><input type="text" name="endTime" class="form-control" value="'. $period['endTime'] .'" placeholder="End Time (hh:mm:ss)" data-rule-required="true" data-msg-required="EndTime is Required"></p>
						</div>
						<div class="col-12">
							<div class="checkbox-wrap">
								<div class="row d-sm-flex checkbox-group">';
									foreach ($weekdays as $key=>$val) {
										$content .= '<div class="col-12 col-xs-12 col-sm-6 col-md-auto">
											<p class="checkbox"> 
												<input type="hidden" name="'. $val .'" value="0" />
												<input type="checkbox" class="require-one" name="'. $val .'" value="1" '. $dayChecks[$key] .'> <label for "'. $val .'"> '. ucfirst($val) .'</label>
											</p>
										</div>';
									}
			$content .= '		</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-xs-12 col-sm-12 col-md-2 col-lg-4">
					<div class="row flex-row">
						<div class="col-6 col-xs-6 col-sm-6 col-md-12 col-lg-6 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update</button></div>
						<div class="col-6 col-xs-6 col-sm-6 col-md-12 col-lg-6 text-center align-self-center"><button type="button" class="btn btn-danger adminBtn" data-op="delete">Delete</button></div>
					</div>
				</div>
			</form>';
		}
		$content .= '</div><!-- /list-group -->';
	} 

	$content .= '</div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container main -->';
?>