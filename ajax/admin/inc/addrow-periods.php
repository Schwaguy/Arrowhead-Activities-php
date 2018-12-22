<?php
	$ageGroups = getAgeGroups(explode(',',$fields['groups']),true,true,$con);
	$days = array(
		'monday'=>$fields['monday'],
		'tuesday'=>$fields['tuesday'],
		'wednesday'=>$fields['wednesday'],
		'thursday'=>$fields['thursday'],
		'friday'=>$fields['friday']
	);
	$monCheck = (($days['monday']==1) ? 'checked' : ''); 
	$tuesCheck = (($days['tuesday']==1) ? 'checked' : '');
	$wedCheck = (($days['wednesday']==1) ? 'checked' : '');
	$thursCheck = (($days['thursday']==1) ? 'checked' : '');
	$friCheck = (($days['friday']==1) ? 'checked' : '');
	$addRow = '<form id="form-'. $id .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-7 col-md-8">
					<input type="hidden" name="table" value="periods">
					<input type="hidden" name="id" value="'. $id .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="'. $fields['name'] .'" data-rule-required="true" data-msg-required="Bunk Name is Required"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><p>'. $ageGroups .'</p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center">
							<p><input type="text" name="startTime" class="form-control" value="'. $fields['startTime'] .'" placeholder="Start Time (hh:mm:ss)" data-rule-required="true" data-msg-required="Start Time is Required"></p>
							<p><input type="text" name="endTime" class="form-control" value="'. $fields['endTime'] .'" placeholder="End Time (hh:mm:ss)" data-rule-required="true" data-msg-required="EndTime is Required"></p>
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

?>