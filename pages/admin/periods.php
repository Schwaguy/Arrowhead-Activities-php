<?php

	$content .= '<div class="container main">
		
		<div class="quick-buttons"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addBunk" aria-expanded="false" aria-controls="addBunk">Add New Period</button></div>
		
		<h1 class="page-title">Activity Periods</h1>
		
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$header = '<div class="list-group-item list-group-header row d-flex">
				<div class="col-3 col-header">Period Name</div>
				<div class="col-3 col-header">Age Group</div>
				<div class="col-3 col-header">Times</div>
			</div>';

	// New Bunk Form
	$ageGroups = getAgeGroups('',true,true,$con);
	$content .= '<div class="collapse collapse-form add-item-box" id="addBunk">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm add-form list-group-item row align-items-center d-flex">
					<div class="col-8 col-xs-8 col-sm-7 col-md-9">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="periods">
						<input type="hidden" name="active" value="1">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="" placeholder="Period Name" data-rule-required="true" data-msg-required="Period Name is Required"></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center ">'. $ageGroups .'</div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center">
								<p><input type="text" name="startTime" class="form-control" value="" placeholder="Start Time (hh:mm:ss)" data-rule-required="true" data-msg-required="Start Time is Required"></p>
								<p><input type="text" name="endTime" class="form-control" value="" placeholder="End Time (hh:mm:ss)" data-rule-required="true" data-msg-required="EndTime is Required"></p>
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
					<div class="col-4 col-xs-4 col-sm-5 col-md-3 d-md-flex">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add Period</button></div>
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$query = "SELECT * FROM periods WHERE active=1 ORDER BY startTime ASC";
	if($result = $con->query($query)) {
		//$content .= '<div id="response"></div>'; 
		
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$content .= $header;
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			
			$days = array(
				'monday'=>$row['monday'],
				'tuesday'=>$row['tuesday'],
				'wednesday'=>$row['wednesday'],
				'thursday'=>$row['thursday'],
				'friday'=>$row['friday']
			);
			$monCheck = (($days['monday']==1) ? 'checked' : ''); 
			$tuesCheck = (($days['tuesday']==1) ? 'checked' : '');
			$wedCheck = (($days['wednesday']==1) ? 'checked' : '');
			$thursCheck = (($days['thursday']==1) ? 'checked' : '');
			$friCheck = (($days['friday']==1) ? 'checked' : '');
			
			$ageGroups = getAgeGroups(explode(',',$row['groups']),true,true,$con);
			$content .= '<form id="form-'. $row['id'] .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-7 col-md-8">
					<input type="hidden" name="table" value="periods">
					<input type="hidden" name="id" value="'. $row['id'] .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="'. $row['name'] .'" data-rule-required="true" data-msg-required="Bunk Name is Required"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><p>'. $ageGroups .'</p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center">
							<p><input type="text" name="startTime" class="form-control" value="'. $row['startTime'] .'" placeholder="Start Time (hh:mm:ss)" data-rule-required="true" data-msg-required="Start Time is Required"></p>
							<p><input type="text" name="endTime" class="form-control" value="'. $row['endTime'] .'" placeholder="End Time (hh:mm:ss)" data-rule-required="true" data-msg-required="EndTime is Required"></p>
						</div>
						<div class="col-12">
							<div class="checkbox-wrap">
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
				<div class="col-12 col-xs-12 col-sm-5 col-md-4">
					<div class="row flex-row">
						<div class="col-6 col-xs-6 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update</button></div>
						<div class="col-6 col-xs-6 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-danger adminBtn" data-op="delete">Delete</button></div>
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