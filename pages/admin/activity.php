<?php

	$content .= '<div class="container main">
		<h1>Bunks</h1>
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$query = "SELECT b.*, g.age_min AS agestart, g.name AS gname FROM bunks b LEFT JOIN bunk_age_groups g ON (b.groupID = g.id) WHERE b.active=1 ORDER BY agestart ASC, gname DESC";
	if($result = $con->query($query)) {
		$content .= '<div id="response"></div>'; 
		
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$header = '<div class="list-group-item list-group-header row d-flex">
				<div class="col-3 col-header">Bunk Name</div>
				<div class="col-3 col-header">Age Group</div>
				<div class="col-3 col-header">Counselor</div>
			</div>';
		$content .= $header;
		
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$counselors = getCounselors($row['counselor'],$con);
			$ageGroups = getAgeGroups($row['groupID'],$con);
			$content .= '<form id="form-'. $row['id'] .'" class="adminForm list-group-item row align-items-center d-flex">
				<div class="col-8 col-xs-8 col-sm-7 col-md-8 d-md-flex">
					<input type="hidden" name="table" value="bunks">
					<input type="hidden" name="id" value="'. $row['id'] .'">
					<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="'. $row['name'] .'"></div>
					<div class="col-12 col-sm-12 col-md-4 align-self-center ">'. $ageGroups .'</div>
					<div class="col-12 col-sm-12 col-md-4 align-self-center">'. $counselors .'</div>
				</div>
				<div class="col-4 col-xs-4 col-sm-5 col-md-4 d-md-flex">
					<div class="col-12 col-xs-12 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update</button></div>
					<div class="col-12 col-xs-12 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-danger adminBtn" data-op="delete">Delete</button></div>
				</div>
			</form>';
		}
		$content .= '</div><!-- /list-group -->';
	} 

	// New Bunk Form
	$counselors = getCounselors('',$con);
	$ageGroups = getAgeGroups('',$con);
	$content .= '<p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addBunk" aria-expanded="false" aria-controls="addBunk">Add New Bunk</button></p>
	<div class="collapse" id="addBunk">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm list-group-item row align-items-center d-flex">
					<div class="col-8 col-xs-8 col-sm-7 col-md-9 d-md-flex">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="bunks">
						<input type="hidden" name="active" value="1">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="" placeholder="Bunk Name"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center ">'. $ageGroups .'</div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center">'. $counselors .'</div>
					</div>
					<div class="col-4 col-xs-4 col-sm-5 col-md-3 d-md-flex">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add Bunk</button></div>
					</div>
				</form>
			</div>
  		</div>
	</div>';


	$content .= '</div>
        </div>
    </div>';
?>