<?php

// Generate Secure Password
function generateHashWithSalt($password) {
	define("MAX_LENGTH", 25);
    $intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
   	$pw = hash("sha256", $password . $salt);
	$secPW = array('salt'=>$salt,'pw'=>$pw);
	return $secPW;
}

// Check Pasword
function checkPassword($uName,$uPass,$con) {
	$pwCheck = '';
	$query = 'SELECT salt FROM users WHERE username="' . $uName . '"';
	if ($result = $con->query($query)) {
		$row = mysqli_fetch_assoc($result);
		$pwCheck =  hash("sha256", $uPass . $row['salt']);
	} 
	return $pwCheck;
}

// Get User Information for Valid Login
function logUserIn($uName,$pwCheck,$today,$con) {
	$loggedin = false;
	$query = 'SELECT u.*, a.super, a.admin, a.manage, a.edit, a.schedule FROM users u LEFT JOIN access_levels a ON (u.access_level = a.id) WHERE u.username="' . $uName . '"';
	if ($pwCheck) { $query .= ' AND u.password="'. $pwCheck .'"'; }
	if ($result = $con->query($query)) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if ($row['active'] == 1)  {
			$_SESSION['userID'] = $row['id'];
			$_SESSION['userAuth'] = $row['access_level'];
			$_SESSION['userPermissions'] = array(
				'super'=>$row['super'],
				'admin'=>$row['admin'],
				'manage'=>$row['manage'],
				'edit'=>$row['edit'],
				'schedule'=>$row['schedule']
			);
			$_SESSION['userName'] = $row['username'];
			$_SESSION['userFirstName'] = $row['firstName'];
			$_SESSION['userLastName'] = $row['lastName'];
			$_SESSION['userEmail'] = $row['email'];
			$_SESSION['userBunk'] = $row['bunk'];
			$_SESSION['bunkInfo'] = (!empty($_SESSION['userBunk']) ? getBunkInfo($_SESSION['userBunk'],'',$con) : getBunkInfo($_SESSION['userBunk'],$_SESSION['userID'],$con));
			$result->free();
			$query = 'UPDATE users SET lastLogin="' . $today . '" WHERE id="' . $_SESSION['userID'] . '"'; 
			$result = $con->query($query);
			//include('inc/pageContent.php');
			$loggedin = true;
		} 
	}
	return $loggedin;
}

// Site-Wide variables
function siteVar($var,$form,$case) {
	switch ($var) {
		case 'act':
			$firstLetter = (($case=='capital') ? 'A' : 'a');
			$output = (($form=='plural') ? $firstLetter .'ctivities' : $firstLetter .'ctivity');
			break;
	}
	return $output;
}

// Check Current Page for Menu Highlighting
function checkPageLink($thisPg,$pageLink) {
	unset($linkStat);
	if (!$thisPg) {
		$linkStat = array(
			'li'=>'active',
			'sr'=>'<span class="sr-only">(current)</span>'
		);
	} elseif ($thisPg == $pageLink) {
		$linkStat = array(
			'li'=>'active',
			'sr'=>'<span class="sr-only">(current)</span>'
		);
	} else {
		$linkStat = array(
			'li'=>'',
			'sr'=>''
		);
	}
	return $linkStat;
}

// Get User Info
function getUserInfo($uID,$con) {
	$user = ''; 
	$query = 'SELECT * FROM users WHERE id='. $uID .' LIMIT 1';  
	if($result = $con->query($query)) {
		$user = mysqli_fetch_array($result,MYSQLI_ASSOC);
	}
	return $user;
}

// Get Auth
function getAuth($selected,$required,$userAuth,$disabled,$con) {
	$req = ($required ? 'true' : 'false');
	$dis = ($disabled ? 'disabled' : '');
	$output = '<select name="access_level" class="form-control browser-default custom-select" data-rule-required="'. $req .'" data-msg-required="Please Select Access Level" '. $dis .'><option value="">&lt; select access level &gt;</option>'; 
	$queryParam = (($userAuth>1) ? 'AND id>1' : '');
	$query = 'SELECT * FROM access_levels WHERE active=1 '. $queryParam .' ORDER BY ID DESC';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['id'] .'"';
		if ($row['id'] == $selected)
			$output .= ' selected';
		$output .= '>'. $row['name'] .'</option>';
	}
	$output .= '</select>';
	return $output;
}

// Get Auth Name
/*function getAuthName($authID,$con) {
	$output = ''; 
	$query = 'SELECT name FROM access_levels WHERE id='. $authID .' LIMIT 1'; 
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output = $row['name'];
	}
	return $output;
}*/

// Get Bunk Name
/*function getBunkName($bunkID,$con) {
	$output = ''; 
	$query = 'SELECT name FROM bunks WHERE id='. $bunkID .' LIMIT 1'; 
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output = $row['name'];
	}
	return $output;
}*/

// Get Item Name
function getName($id,$table,$con) {
	$output = ''; 
	$query = 'SELECT name FROM '. $table .' WHERE id='. $id .' LIMIT 1'; 
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output = $row['name'];
	}
	return $output;
}

// Get Counselors
function getCounselors($selected,$con) {
	$output = '<select name="counselor" class="form-control browser-default custom-select" data-rule-required="false" data-msg-required="Please Select a Counselor"><option value="">&lt; select counselor &gt;</option>'; 
	$query = 'SELECT id, firstName, lastName FROM users WHERE active=1 and access_level=3 ORDER BY lastName, firstName';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['id'] .'"';
		if ($row['id'] == $selected)
			$output .= ' selected';
		$output .= '>'. $row['lastName'] .', '. $row['firstName'] .'</option>';
	}
	$output .= '</select>';
	return $output;
}

// Get Age Groups
function getAgeGroups($selected,$multi,$required,$con) {
	if ($multi) {
		$multiple = 'multiple="multiple"';
		$option1 = '';
		$name = 'groups[]'; 
	} else {
		$multiple = '';
		$option1 = '<option value="">&lt; select group &gt;</option>';
		$name = 'groups';
	}
	$req = ($required ? 'true' : 'false');
	$output = '<select name="'. $name .'" class="form-control browser-default custom-select" data-rule-required="'. $req .'" data-msg-required="Age Group is Required" '. $multiple .'>'. $option1;
	$query = 'SELECT * FROM bunk_age_groups WHERE display=1 ORDER BY age_min ASC, name DESC';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['id'] .'"';
		if (is_array($selected)) {
			if (in_array($row['id'],$selected)) { $output .= ' selected'; }
		} else {
			if ($row['id'] == $selected) { $output .= ' selected'; }
		}
		$output .= '>'. $row['name'] .'</option>';
	}
	$output .= '</select>';
	return $output;
}

// Get Bunks
function getBunks($selected,$con) {
	$output = '<select name="bunk" class="form-control browser-default custom-select" data-rule-required="false" data-msg-required="Please Select Bunk"><option value="">&lt; select bunk &gt;</option>'; 
	$query = 'SELECT b.id as bunkID, b.name AS bunkName, a.age_min, a.name AS groupName FROM bunks b LEFT JOIN bunk_age_groups a ON (b.groups = a.id) WHERE active=1 ORDER BY a.age_min ASC, a.name DESC';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['bunkID'] .'"';
		if ($row['bunkID'] == $selected)
			$output .= ' selected';
		$output .= '>'. $row['bunkName'] .' ('. $row['groupName'] .')</option>';
	}
	$output .= '</select>';
	return $output;
}

// Get Weeks
function getWeeks($admin,$selected,$multi,$required,$con) {
	$query = 'SELECT * FROM weeks WHERE active=1 ORDER BY name ASC, startDate ASC';
	$result = $con->query($query);
	$selected = (!empty($selected) ? explode(',',$selected) : array());
	if ($admin) {
		if ($multi) {
			$multiple = 'multiple="multiple"';
			$multiArr = '[]';
			$option1 = '';
		} else {
			$multiple = '';
			$multiArr = '';
			$option1 = '<option value="">&lt; select week &gt;</option>';
		}
		$req = ($required ? 'true' : 'false');
		$output = '<select name="week'. $multiArr .'" class="form-control browser-default custom-select" data-rule-required="'. $req .'" data-msg-required="Week is Required" '. $multiple .'>'. $option1;

		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<option value="'. $row['id'] .'"';
			if (in_array($row['id'],$selected)) {
			//if ($row['id'] == $selected)
				$output .= ' selected';
			}
			$output .= '>'. $row['name'] .'</option>';
		}
		$output .= '</select>';
	} else {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$week['id'] = $row['id'];
			$week['name'] = $row['name'];
			$week['startDate'] = $row['startDate'];
			$week['endDate'] = $row['endDate'];
			$week['signupStartDate'] = $row['signupStartDate'];
			$week['signupEndDate'] = $row['signupEndDate'];
			$week['days'] = array(
				array(
					'number'=>1,
					'name'=>'monday',
					'date'=>$row['startDate'],
					'nicedate'=>date_format(date_create($row['startDate']),'F jS')
				),
				array(
					'number'=>2,
					'name'=>'tuesday',
					'date'=>date('Y-m-d', strtotime($row['startDate']. ' + 1 days')),
					'nicedate'=>date('F jS', strtotime($row['startDate']. ' + 1 days'))
				),
				array(
					'number'=>3,
					'name'=>'wednesday',
					'date'=>date('Y-m-d', strtotime($row['startDate']. ' + 2 days')),
					'nicedate'=>date('F jS', strtotime($row['startDate']. ' + 2 days'))
				),
				array(
					'number'=>4,
					'name'=>'thursday',
					'date'=>date('Y-m-d', strtotime($row['startDate']. ' + 3 days')),
					'nicedate'=>date('F jS', strtotime($row['startDate']. ' + 3 days'))
				),
				array(
					'number'=>5,
					'name'=>'friday',
					'date'=>date('Y-m-d', strtotime($row['startDate']. ' + 4 days')),
					'nicedate'=>date('F jS', strtotime($row['startDate']. ' + 4 days'))
				)
			);
			$weeks[$row['id']] = $week;
			unset($week);
		}
		$output = $weeks;
	}
	return $output;
}

// Get Periods
function getPeriods($selected,$multi,$required,$return,$con) {
	$query = 'SELECT * FROM periods WHERE active=1 ORDER BY name ASC';
	$result = $con->query($query);
	if ($return == 'select') {
		if ($multi) {
			$multiple = 'multiple="multiple"';
			$option1 = '';
		} else {
			$multiple = '';
			$option1 = '<option value="">&lt; select period &gt;</option>';
		}
		$req = ($required ? 'true' : 'false');
		$output = '<select name="period" class="form-control browser-default custom-select" data-rule-required="'. $req .'" data-msg-required="Period is Required" '. $multiple .'>'. $option1;
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<option value="'. $row['id'] .'"';
			if ($row['id'] == $selected)
				$output .= ' selected';
			$output .= '>'. $row['name'] .'</option>';
		}
		$output .= '</select>';
	} else {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$days[1] = $row['monday'];
			$days[2] = $row['thursday'];
			$days[3] = $row['wednesday'];
			$days[4] = $row['thursday'];
			$days[5] = $row['friday'];
			$output[] = array('id'=>$row['id'],'name'=>$row['name'],'startTime'=>$row['startTime'],'endTime'=>$row['endTime'],'groups'=>explode(',',$row['groups']),'days'=>$days);
		}
	}
	return $output;
}

// Get Prerequisites
function getPrerequisites($selected,$con) {
	$query = 'SELECT * FROM prerequisites WHERE active=1 ORDER BY name ASC';
	$result = $con->query($query);
	$output = '<div class="row d-sm-flex">'; 
	$x = 0;
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox"><input type="hidden" name="prerequisites['. $x .']" value="0"><input type="checkbox" name="prerequisites['. $x .']" value="'. $row['id'] .'"';
		if (is_array($selected)) {
			if (in_array($row['id'],$selected)) { $output .= ' checked'; }
		} else {
			if ($row['id'] == $selected) { $output .= ' checked'; }
		}
		$output .= '> <label>'. $row['name'] .'</label></p></div>';
		$x++;
	}
	$output .= '</div>'; 
	return $output;
}

// Get Bunk Info
function getBunkInfo($bunkID,$counselorID,$con) {
	if ($bunkID==0) {
		$getID = mysqli_fetch_assoc(mysqli_query($con, 'SELECT id FROM bunks WHERE counselor='. $counselorID));
		$bunkID = $getID['id'];
	} 
	unset($outputArray);
	$query = 'SELECT b.*, u.firstName, u.lastName FROM bunks b LEFT JOIN users u ON (b.counselor = u.id) WHERE b.id='. $bunkID .' LIMIT 1'; 
	if($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$outputArray['name'] = $row['name'];
			$outputArray['group'] = $row['groups'];
			$outputArray['counselor'] = $row['firstName'] .' '. $row['lastName'];
		}
	}
	$output = (isset($outputArray) ? $outputArray : '');
	return $output;
}

// Get Activity Info
function getActivityinfo($actID,$con) {
	$query = 'SELECT a.*, w.startDate FROM activities a LEFT JOIN weeks w ON (a.week = w.id) WHERE a.id='. $actID .' LIMIT 1';  
	if($result = $con->query($query)) {
		while ($act=$result->fetch_array(MYSQLI_ASSOC)) {
			$activity = array(
				'id'=>$act['id'],
				'name'=>$act['name'],
				'description'=>$act['description'],
				'location'=>$act['location'],
				'capacity'=>$act['capacity'],
				'groups'=>explode(',',$act['groups']),
				'week'=>$act['week'],
				'startDate'=>$act['startDate'],
				'period'=>$act['period'],
				'days'=>array(
					'monday'=>$act['monday'],
					'tuesday'=>$act['tuesday'],
					'wednesday'=>$act['wednesday'],
					'thursday'=>$act['thursday'],
					'friday'=>$act['friday']
				),
				'prerequisites'=>$act['prerequisites']
			);
		}
	}
	return $activity;
}

// Get Activity Signups
function getActivitySignups($actID,$con) {
	$query = 'SELECT a.id AS sID, a.user, a.day, u.firstName, u.lastName, b.id AS bID, b.name FROM activity_signups a LEFT JOIN users u ON (a.user = u.id) LEFT JOIN bunks b ON (u.bunk = b.id) WHERE a.activity='. $actID .' AND a.active=1 AND u.active=1 ORDER BY a.id';  
	if($result = $con->query($query)) {
		while ($sup=$result->fetch_array(MYSQLI_ASSOC)) {
			$signup = array(
				'id'=>$sup['sID'],
				'user'=>array('id'=>$sup['user'],'firstName'=>$sup['firstName'],'lastName'=>$sup['lastName']),
				'bunk'=>array('id'=>$sup['bID'],'name'=>$sup['name'])
			);
			switch ($sup['day']) {
				case 'Monday':
					$monday[$sup['sID']] = $signup;
					break;
				case 'Tuesday':
					$tuesday[$sup['sID']] = $signup;
					break;
				case 'Wednesday':
					$wednesday[$sup['sID']] = $signup;
					break;
				case 'Thursday':
					$thursday[$sup['sID']] = $signup;
					break;
				case 'Friday':
					$friday[$sup['sID']] = $signup;
					break;
			}
			unset($signup);
		}
		$signups = array(
			1=>(isset($monday) ? $monday : ''),
			2=>(isset($tuesday) ? $tuesday : ''),
			3=>(isset($wednesday) ? $wednesday : ''),
			4=>(isset($thursday) ? $thursday : ''),
			5=>(isset($friday) ? $friday : ''),
		);
	}
	return $signups;
}

// Get Activites for the week
function getWeekActivities($week,$con) {
	$sql = 'SELECT * FROM activities WHERE week='. $week .' ORDER BY name';  
	$monday = array();
	$tuesday = array();
	$wednesday = array();
	$thursday = array();
	$friday = array();
	if ($res = $con->query($sql)) {
		while ($act=$res->fetch_array(MYSQLI_ASSOC)) {
			$monSpace = (($act['monday']) ? $act['capacity']-$act['regMonday'] : 0);
			$tuesSpace = (($act['tuesday']) ? $act['capacity']-$act['regTuesday'] : 0);
			$wedSpace = (($act['wednesday']) ? $act['capacity']-$act['regWednesday'] : 0);
			$thursSpace = (($act['thursday']) ? $act['capacity']-$act['regThursday'] : 0);
			$friSpace = (($act['friday']) ? $act['capacity']-$act['regFriday'] : 0);
			$activity = array(
				'id'=>$act['id'],
				'name'=>$act['name'],
				'week'=>$act['week'],
				'period'=>$act['period'],
				'space'=>array(
					'monday'=>$monSpace,
					'tuesday'=>$tuesSpace,
					'wednesday'=>$wedSpace,
					'thursday'=>$thursSpace,
					'friday'=>$friSpace,
				)
			);
			if ($act['monday']) { $monday[] = $activity; }
			if ($act['tuesday']) { $tuesday[] = $activity; }
			if ($act['wednesday']) { $wednesday[] = $activity; }
			if ($act['thursday']) { $thursday[] = $activity; }
			if ($act['friday']) { $friday[] = $activity; }
		}
	}
	$weekActivities = array(
		'monday'=>$monday,
		'tuesday'=>$tuesday,
		'wednesday'=>$wednesday,
		'thursday'=>$thursday,
		'friday'=>$friday,
	);
	return $weekActivities;
}

// Get Scheduled Activities
function getScheduledActivities($week,$user,$con) {
	$query = 'SELECT * FROM activity_signups WHERE week='. $week .' AND user='. $user .' AND active=1'; 
	if ($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			switch ($row['day']) {
				case 'Monday':
					$mon[$row['period']] = $row['activity'];
					break;
				case 'Tuesday':
					$tues[$row['period']] = $row['activity'];
					break;
				case 'Wednesday':
					$wed[$row['period']] = $row['activity'];
					break;
				case 'Thursday':
					$thurs[$row['period']] = $row['activity'];
					break;
				case 'Friday':
					$fri[$row['period']] = $row['activity'];
					break;
			}
		}
		$scheduledActivities = array(
			1=>(isset($mon) && count($mon>0)) ? $mon : '',
			2=>(isset($tues) && count($tues>0)) ? $tues : '',
			3=>(isset($wed) && count($wed>0)) ? $wed : '',
			4=>(isset($thurs) && count($thurs>0)) ? $thurs : '',
			5=>(isset($fri) && count($fri>0)) ? $fri : ''
		);
		unset($mon);
		unset($tues);
		unset($wed);
		unset($thurs);
		unset($fri);
	} else {
		$scheduledActivities = array(1=>'',2=>'',3=>'',4=>'',5=>'',); 
	}
	return $scheduledActivities;
}

// Show Agenda Activities
function showAgendaActivities($week,$day,$actArray,$period,$admin,$actScheduled) {
	$activities = ''; 
	if (count($actArray)>0) {
		foreach ($actArray as $activity) {
			if ($activity['week'] == $week) {
				if ($activity['period'] == $period) {
					if ($admin) {
						$activities .= '<form id="edit-activity-'. $activity['id'] .'" class="agenda-form button-form" method="post" action="/admin/activities/edit/">
						<input type="hidden" name="id" value="'. $activity['id'] .'">'; 
						$activities .= '<input type="submit" type="button" class="btn btn-light agenda-event-button" value="'. $activity['name'] .'" title="'. $activity['space'][strtolower($day)] .' spots left" data-toggle="tooltip" data-placement="top" title="'. $activity['space'][strtolower($day)] .' spots left">';
						$activities .= '</form>';
					} else {
						// Mark as active if scheduling is complete
						$active = '';
						if (is_array($actScheduled)) {
							if(isset($actScheduled[$period])) {
								if ($activity['id'] == $actScheduled[$period]) {
									$active = 'active';
								}
							} 
						} 
						
						// Disable if scheduling is complete and user cannot edit
						$disable = ((!empty($actScheduled)) ? ($_SESSION['userPermissions']['edit'] ? '' : 'disabled="disabled"') : '');
						
						// Disable for Campers if Full
						$disable = (($activity['space'][strtolower($day)]<=0) ? 'disabled="disabled"' : $disable);
						
						$activities .= '<li><input type="radio" class="schedule-radio '. $active .'" id="activity-'. $week .'-'. $day .'-'. $period .'-'. $activity['id'] .'" name="activity-'. $week .'-'. $day .'-'. $period .'" value="'. $activity['id'] .'" data-rule-required="false" data-msg-required="Please Select an Activity" '. $disable .'><label for="activity-'. $week .'-'. $day .'-'. $period .'-'. $activity['id'] .'" class="btn btn-light schedule-button '. $active .'">'. $activity['name'] .'<div class="small">'. $activity['space'][strtolower($day)] .' spots left</div></label></li>';
					}
				}
			}
		}
		if (!$admin && !empty($activities)) { $activities = '<ul class="activity-signup-buttons">'. $activities .'</ul>'; }
	} 
	$output = ((!empty($activities)) ? $activities : '');
	return $output;
}

// Show Scheduled Activities
function showScheduledActivities($week,$user,$con) {
	$sql= 'SELECT s.id, s.week, s.day, s.period, s.activity, a.name FROM activity_signups s LEFT JOIN activities a ON (s.activity = a.id) WHERE s.week='. $week .' AND s.user='. $user .' AND s.active=1 AND a.active=1';
	if ($res = $con->query($sql)) {
		while ($r=$res->fetch_array(MYSQLI_ASSOC)) {
			switch ($r['day']) {
				case 'Monday':
					$mon[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name']);
					break;
				case 'Tuesday':
					$tues[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name']);
					break;
				case 'Wednesday':
					$wed[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name']);
					break;
				case 'Thursday':
					$thurs[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name']);
					break;
				case 'Friday':
					$fri[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name']);
					break;
			}
		}
		$scheduledActivities = array(
			1=>(isset($mon) && count($mon>0)) ? $mon : '',
			2=>(isset($tues) && count($tues>0)) ? $tues : '',
			3=>(isset($wed) && count($wed>0)) ? $wed : '',
			4=>(isset($thurs) && count($thurs>0)) ? $thurs : '',
			5=>(isset($fri) && count($fri>0)) ? $fri : ''
		);
		unset($mon);
		unset($tues);
		unset($wed);
		unset($thurs);
		unset($fri);
	} else {
		$scheduledActivities = array(1=>'',2=>'',3=>'',4=>'',5=>'',); 
	}
	return $scheduledActivities;
}

// Get Bunk Roster
function getBunkRoster($bunkID,$counselorID,$con) {
	if ($bunkID==0) {
		$getID = mysqli_fetch_assoc(mysqli_query($con, 'SELECT id FROM bunks WHERE counselor='. $counselorID));
		$bunkID = $getID['id'];
	} 
	$query = 'SELECT id, firstName, lastName, email, lastLogin, week, bunk FROM users WHERE bunk='. $bunkID .' AND active=1 ORDER by lastName ASC, firstName ASC';
	if ($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$user = array(
				'id'=>$row['id'],
				'firstName'=>$row['firstName'],
				'lastName'=>$row['lastName'],
				'email'=>$row['email'],
				'lastLogin'=>$row['lastLogin'],
				'week'=>$row['week'],
				'bunk'=>$row['bunk']
			);
			$users[$row['id']] = $user;
			unset($user);
		}
	}
	$output = (isset($users) ? $users : '');
	return $output;
}


// Update scheduling start date when week is edited
function scheduleCheck($table,$field,$date) {
	if (($table=='weeks') && ($field=='startDate')) {
		$date = date_format(date_create($date),'Y-m-d 00:00:00');
		$lastTuesday = date('Y-m-d', strtotime('previous tuesday', strtotime($date)));
		$scheduleDate = date_format(date_create($lastTuesday),'Y-m-d 19:00:00');
	} else {
		$scheduleDate = ''; 
	}
	return $scheduleDate;
}

// Define Column Sort Variables for Sortable Tables
function defineSorts($num,$orderBy) {
	for ($i=1;$i<=$num;$i++) {
		$sortVar = 'sort'. $i;
		$orderVar = 'order'. $i;
		global $$sortVar, $$orderVar;
			
		$sortDir = substr($orderBy, 1);
		if ($sortDir == 'up')
			$sortRev = 'down';
		else
			$sortRev = 'up';
		$colTest = $i . $sortDir;
		
		if ($colTest == $orderBy) {
			$$sortVar = ' id="headerSel_'. $sortDir .'"';
			$$orderVar = 'orderBy='. $i . $sortRev; 
		}
		else {
			$$sortVar = 'class="tableHeader"';
			$$orderVar = 'orderBy='. $i .'down'; 
		}
	}
}	



// Get File Extension
function getFileExtension($str) {
	$i = strrpos($str,".");
    if (!$i) { return ""; }

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);

    return $ext;
}

/**
 * Function to calculate date or time difference.
 * 
 * Function to calculate date or time difference. Returns an array or
 * false on error.
 *
 * @author       J de Silva                             <giddomains@gmail.com>
 * @copyright    Copyright &copy; 2005, J de Silva
 * @link         http://www.gidnetwork.com/b-16.html    Get the date / time difference with PHP
 * @param        string                                 $start
 * @param        string                                 $end
 * @return       array
 */
function get_time_difference( $start, $end )
{
    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
            trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
        }
    }
    else
    {
        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}

if (!function_exists('isDate')) {
	function isDate($value) {
		if (!$value) {
			return false;
		}
		try {
			new \DateTime($value);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}

// Get the last Day of a month
function lastday($month, $year) {
   if (empty($month))
      $month = date('m');
   if (empty($year))
      $year = date('Y');
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));
   return date('Y-m-d', $result);
}

// Add X Months to Date
function addMonths($today, $addAmt) {
	$updateMonth = strtotime(date('Y-m-d', strtotime($today)) . ' +'. $addAmt .' month');
	$updateMonth = date('Y-m-d', $updateMonth);
	return $updateMonth;
}

// Add X Daye to Date
function addDays($today, $addAmt) {
	$updateDay = strtotime(date('Y-m-d', strtotime($today)) . ' +'. $addAmt .' day');
	$updateDay = date('Y-m-d', $updateDay);
	return $updateDay;
}

// Remove a value from an array
function remove_element($arr, $val){
	foreach ($arr as $key => $value){
		if ($arr[$key] == $val){
			unset($arr[$key]);
		}
	}
	return $arr = array_values($arr);
}

/**
* word-sensitive substring function with html tags awareness
* @param text The text to cut
* @param len The maximum length of the cut string
* @returns string
**/
function substrws( $text, $len=180 ) {
    if( (strlen($text) > $len) ) {
        $whitespaceposition = strpos($text," ",$len)-1;
        if( $whitespaceposition > 0 )
            $text = substr($text, 0, ($whitespaceposition+1));

        // close unclosed html tags
        if( preg_match_all("|<([a-zA-Z]+)>|",$text,$aBuffer) ) {
            if( !empty($aBuffer[1]) ) {
                preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
                if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
                    foreach( $aBuffer[1] as $index => $tag ) {
                        if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
                            $text .= '</'.$tag.'>';
                    }
                }
            }
        }
    }
    return $text;
} 

if (!function_exists('logConsole')) {
 function logConsole($name, $data = NULL, $jsEval = FALSE) {
      if (! $name) return false;
      $isevaled = false;
      $type = ($data || gettype($data)) ? 'Type: ' . gettype($data) : '';
      if ($jsEval && (is_array($data) || is_object($data))) {
           $data = 'eval(' . preg_replace('#[\s\r\n\t\0\x0B]+#', '', json_encode($data)) . ')';
           $isevaled = true;
      } else {
           $data = json_encode($data);
      }
      # sanitalize
      $data = $data ? $data : '';
      $search_array = array("#'#", '#""#', "#''#", "#\n#", "#\r\n#");
      $replace_array = array('"', '', '', '\\n', '\\n');
      $data = preg_replace($search_array,  $replace_array, $data);
      $data = ltrim(rtrim($data, '"'), '"');
      //$data = $isevaled ? $data : ($data[0] === "'") ? $data : "'" . $data . "'";
$log = $name .' : '. $data .' : '. $type;	 
	 
$js = <<<JSCODE
\n<script>
 // fallback - to deal with IE (or browsers that don't have console)
 if (! window.console) console = {};
 console.log = console.log || function(name, data){};
 console.log('$log');
 //console.log('\\n');
</script>
JSCODE;
      echo $js;
 } # end logConsole
}