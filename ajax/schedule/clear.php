<?php
session_start();
global $ROOT, $PATH, $phpself, $id, $addRow;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$PATH = '../../';  
$phpself = basename(__FILE__);
require_once($PATH  .'globals/globals.php');

$ignore = array('PHPSESSID');
$success = false;
$redirect = ''; 
$test = ''; 
if ($_REQUEST) {
	$redirect = $_REQUEST['redirect'];
	$sql = "SELECT s.id AS signupID, s.user, s.activity, s.week, s.day, t.id AS typeID, t.oneTime FROM activity_signups s LEFT JOIN activities a ON (a.id = s.activity) LEFT JOIN activity_types t ON (t.id = a.type) WHERE s.active=1";
	if (!empty($_REQUEST['user'])) {
		$sql .= " AND s.user='". $_REQUEST['user'] ."'"; 
		if (!empty($_REQUEST['week'])) {
			$sql .= " AND s.week='". $_REQUEST['week'] ."'";
		}
	} elseif (!empty($_REQUEST['activity'])) {
		$sql .= " AND s.activity='". $_REQUEST['activity'] ."'";
		if (!empty($_REQUEST['week'])) {
			$sql .= " AND s.week='". $_REQUEST['week'] ."'";
			if (!empty($_REQUEST['day'])) {
				$sql .= " AND s.day='". $_REQUEST['day'] ."'";
			}
		}
	} elseif (!empty($_REQUEST['week'])) {
		$sql .= " AND s.week='". $_REQUEST['week'] ."'";	
	} 
	
	$test .= '<p>SQL: '. $sql .'</p>'; 
	
	if($result = $con->query($sql)) {
		while ($sup=$result->fetch_array(MYSQLI_ASSOC)) {
			// Update user_activities table IF this is a once-a-summer activity being removed
			if ($sup['oneTime'] > 0) {
				$column = date('Y'); // Camp Year Column
				$query = "SELECT `". $column ."` FROM user_activities WHERE user=". $sup['user'];
				
				$test .= '<p>QUERY: '. $query .'</p>';
				
				if ($res = $con->query($query)) {
					$userActAll = $res->fetch_array(MYSQLI_ASSOC);
					$userActCurrent = explode(',',$userActAll[$column]);
					if (in_array($sup['typeID'],$userActCurrent)) {	
						$pos = array_search($sup['typeID'], $userActCurrent);
						unset($userActCurrent[$pos]);
						$sql = "UPDATE user_activities SET `". $column ."`='". implode(',',$userActCurrent) ."' WHERE user=". $sup['user'];
						
						$test .= '<p>SQL: '. $sql .'</p>';

						$update = $con->query($sql);
					}
				}
			}
			
			// Update signup status in activity_signups table
			$sql = "UPDATE activity_signups SET active=0 WHERE id=". $sup['signupID'] ." LIMIT 1"; 
			$test .= '<p>SQL: '. $sql .'</p>';
			$update = $con->query($sql);
			
			// Update registration number in activities table
			$var = 'reg'. $sup['day'];
			$sql = "UPDATE activities SET ". $var ."=". $var ."-1 WHERE id=". $sup['activity'];
			$test .= '<p>SQL: '. $sql .'</p>';
			$update = $con->query($sql);
			$test .= '<hr />';
		} 
		$success = true;
	} else {
		$success = false;
	}
	$output = (($success) ? array('op'=>'clear','feedback'=>'Update Complete','redirect'=>$redirect,'result'=>'success') : array('op'=>'clear','feedback'=>'UPDATE ERROR','redirect'=>'','result'=>'error'));
} else {
	$output = array('op'=>'clear','feedback'=>'ERROR','redirect'=>'','result'=>'error'); 	
}

if ($con) $con->close(); 

if ($_POST) {
	header('Content-Type: application/json; charset=utf-8', true); 
	echo json_encode(array("output"=>$output));
} else {
	echo $test;
}
?>