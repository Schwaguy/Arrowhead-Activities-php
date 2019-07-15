<?php
session_start();
global $ROOT, $PATH, $phpself;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

if ($_POST) {
	foreach($_POST as $key => $value) {
		if ($key == 'op') {
			$op = $value;
		} elseif ($key == 'table') {
			$table = $value;
		} elseif ($key == 'id') {
			$id = $value;
		} 
	}
	$sql = "UPDATE ". $table ." SET active=0 WHERE id=". $id ." LIMIT 1";
	$result = $con->query($sql);
	
	$updateString = $sql;
	
	$output = (($result) ? array('update'=>$id,'op'=>'delete','updateString'=>$updateString) : array('update'=>$id,'op'=>$op,'updateString'=>'UPDATE ERROR'));
} else {
	$output = array('update'=>'00','op'=>'delete','updateString'=>'NO OP'); 	
}

if ($con) $con->close(); 
header('Content-Type: application/json; charset=utf-8', true); 
echo json_encode(array("output"=>$output));