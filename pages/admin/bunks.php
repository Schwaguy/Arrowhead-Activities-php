<?php

	$content .= '<div class="container main">
		
		<div class="quick-buttons"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addBunk" aria-expanded="false" aria-controls="addBunk">Add New Bunk</button></div>
		
		<h1 class="page-title">Bunks</h1>
		
		<div class="row justify-content-md-center">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">';

	$header = '<div class="w-100 d-none d-md-block"><div class="list-group-item list-group-header row d-flex">
				<div class="col-3 col-header">Bunk Name</div>
				<div class="col-3 col-header">Age Group</div>
				<div class="col-3 col-header">Counselor</div>
			</div></div>';

	// New Bunk Form
	$content .= '<div class="collapse collapse-form add-item-box" id="addBunk">
  		<div class="card card-body">
    		<div class="list-group list-group-flush list-group-admin">
				'. $header .'
				<form id="form-add" class="adminForm add-form list-group-item row align-items-center d-flex">
					<div class="col-12 col-xs-12 col-sm-7 col-md-9">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="table" value="bunks">
						<input type="hidden" name="active" value="1">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-none d-xs-block d-sm-block d-md-none">Bunk Name</label><input name="name" class="form-control" value="" placeholder="Bunk Name" data-rule-required="true" data-msg-required="Bunk Name is Required"></p></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center "><p class="input-spacer"><label class="w-100 d-none d-xs-block d-sm-block d-md-none">Counselor</label>'. getCounselors('',$con) .'</p></div>
							<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-none d-xs-block d-sm-block d-md-none">Age Group</label>'. getAgeGroups('',false,true,$con) .'</p></div>
						</div>
					</div>
					<div class="col-12 col-xs-12 col-sm-5 col-md-3 d-md-flex">
						<div class="col-12 col-xs-12 col-sm-12 col-md-12 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="add">Add Bunk</button></div>
					</div>
				</form>
			</div>
  		</div>
	</div>';

	$bunks = getBunks('','array',$con);
	if ($bunks) {
		$content .= '<div id="list-group-edit" class="list-group list-group-flush list-group-admin">'; 
		$content .= $header;
		foreach ($bunks as $bunk) {
			$content .= '<form id="form-'. $bunk['id'] .'" class="adminForm list-group-item row flex-row align-items-center d-flex">
				<div class="col-12 col-xs-12 col-sm-9 col-md-8 col-lg-9">
					<input type="hidden" name="table" value="bunks">
					<input type="hidden" name="id" value="'. $bunk['id'] .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Bunk Name</label><input name="name" class="form-control" value="'. $bunk['name'] .'" data-rule-required="true" data-msg-required="Bunk Name is Required"></p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Age Group</label>'. getAgeGroups($bunk['groups'],false,true,$con) .'</p></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><p class="input-spacer"><label class="w-100 d-block d-xs-block d-sm-block d-md-none">Counselor</label>'. getCounselors($bunk['counselor'],$con) .'</p></div>
					</div>
				</div>
				<div class="col-12 col-xs-12 col-sm-3 col-md-4 col-lg-3">
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