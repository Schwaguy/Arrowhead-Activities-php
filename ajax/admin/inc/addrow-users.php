<?php
$addRow = '<tr id="form-'. $id .'">
				<td>'. $lastName .', '. $firstName .'</td>
				<td>'. $username .'</td>
				<td>'. $email .'</td>
				<td>'. getAuthName($access_level,$con) .'</td>
				<td>'. getBunkName($bunk,$con) .'</td>
				<td>
					<div class="row row-flex d-flex">
						<div col-12 div-xs-col-12 div-sm-col-12 div-md-col-6 text-center"><button class="btn btn-dark-green  btn-sm adminBtn">Edit</button></div>
						<div col-12 div-xs-col-12 div-sm-col-12 div-md-col-6 text-center">
							<form method="post" class="adminForm">
								<input type="hidden" name="table" value="users">
								<input type="hidden" name="id" value="'. $id  .'">
								<button class="btn btn-danger btn-sm adminBtn" data-op="delete">Delete</button>
							</form>
						</div>
					</div>
				</td>
			</tr>';
?>