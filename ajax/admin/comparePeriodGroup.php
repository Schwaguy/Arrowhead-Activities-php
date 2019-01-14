<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_POST) {
	if ($_POST['period']) {
		$groups = 'no '; 
		$query = 'SELECT * FROM periods WHERE id='. $_POST['period'] .' LIMIT 1';
		if($result = $con->query($query)) {
			$row = mysqli_fetch_assoc($result);
			$groups = $row['groups'];
			$days = array(
				'monday'=>$row['monday'],
				'tuesday'=>$row['tuesday'],
				'wednesday'=>$row['wednesday'],
				'thursday'=>$row['thursday'],
				'friday'=>$row['friday'],
			);
		}
		$output = (($result) ? array('groups'=>$groups,'days'=>$days) : '');
	} 
} else {
	$output = ''; 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));
?>