<?php

	$content .= '<div class="container main">
	
		<div class="quick-buttons"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addWeek" aria-expanded="false" aria-controls="addWeek">Add New Week</button></div>
	
		<h1 class="page-title">Camp Weeks</h1>
		
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$header = '<div class="list-group-item list-group-header row d-flex">
				<div class="col-3 col-header">Week</div>
				<div class="col-3 col-header">Start Date</div>
				<div class="col-3 col-header">End Date</div>
			</div>';

	// New Week Form
	$content .= '
	<div class="collapse collapse-form add-item-box" id="addWeek">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm add-form list-group-item row align-items-center d-flex">
					<div class="col-8 col-xs-8 col-sm-7 col-md-8 d-md-flex">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="weeks">
						<input type="hidden" name="active" value="1">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="" placeholder="Week #"></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center "><input type="text" class="datepicker form-control" name="startDate" value="" placeholder="Start Date"></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><input type="text" class="datepicker form-control" name="endDate" value="" placeholder="End Date"></div>
						</div>
					</div>
					<div class="col-4 col-xs-4 col-sm-5 col-md-4 d-md-flex">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add Week</button></div>
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$query = "SELECT * FROM weeks WHERE active=1 ORDER BY name ASC";
	if($result = $con->query($query)) {
		//$content .= '<div id="response"></div>'; 
		
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$content .= $header;
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$startDate = date_format(date_create($row['startDate']),"m/d/Y");
			$endDate = date_format(date_create($row['endDate']),"m/d/Y");
			
			$content .= '<form id="form-'. $row['id'] .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-7 col-md-8">
					<input type="hidden" name="table" value="weeks">
					<input type="hidden" name="id" value="'. $row['id'] .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="'. $row['name'] .'"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><input type="text" class="datepicker form-control startDate weekDate" name="startDate" value="'. $startDate .'"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input type="text" class="datepicker form-control endDate weekDate" name="endDate" value="'. $endDate .'"></div>
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