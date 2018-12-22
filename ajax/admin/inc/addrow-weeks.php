<?php
$startDate = date_format(date_create($fields['startDate']),"m/d/Y");
$endDate = date_format(date_create($fields['endDate']),"m/d/Y");
$addRow = '<form id="form-'. $id .'" class="adminForm list-group-item row align-items-center d-flex">
				<div class="col-8 col-xs-8 col-sm-7 col-md-8">
					<input type="hidden" name="table" value="weeks">
					<input type="hidden" name="id" value="'. $id .'">
					<div class="row flex-row">
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input name="name" class="form-control" value="'. $fields['name'] .'"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center "><input type="text" class="datepicker form-control" name="startDate" value="'. $startDate .'"></div>
						<div class="col-12 col-sm-12 col-md-4 align-self-center"><input type="text" class="datepicker form-control" name="endDate" value="'. $endDate .'"></div>
					</div>
				</div>
				<div class="col-4 col-xs-4 col-sm-5 col-md-4">
					<div class="row flex-row">
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-dark-green adminBtn" data-op="update">Update</button></div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 text-center align-self-center"><button type="button" class="btn btn-danger adminBtn" data-op="delete">Delete</button></div>
					</div>
				</div>
			</form>';
?>