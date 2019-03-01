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

// Check Password
function checkPassword($uName,$uPass,$con) {
	$pwCheck = '';
	$query = 'SELECT salt FROM users WHERE username="' . $uName . '"';
	if ($result = $con->query($query)) {
		$row = mysqli_fetch_assoc($result);
		$pwCheck =  hash("sha256", $uPass . $row['salt']);
	} 
	return $pwCheck;
}

// Generate Random Key
function randomKey($length) {
  	$max = ceil($length / 32);
  	$random = '';
  	for ($i = 0; $i < $max; $i ++) {
    	$random .= md5(microtime(true).mt_rand(10000,90000));
  	}
  	return substr($random, 0, $length);
}

// Get User Information for Valid Login
function logUserIn($uName,$pwCheck,$today,$con) {
	$loggedin = false;
	$query = 'SELECT u.*, a.super, a.admin, a.manage, a.edit, a.schedule, a.report FROM users u LEFT JOIN access_levels a ON (u.access_level = a.id) WHERE u.username="' . $uName . '"';
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
				'report'=>$row['report'],
				'edit'=>$row['edit'],
				'schedule'=>$row['schedule']
			);
			$_SESSION['userName'] = $row['username'];
			$_SESSION['userFirstName'] = $row['firstName'];
			$_SESSION['userLastName'] = $row['lastName'];
			$_SESSION['userEmail'] = $row['email'];
			$_SESSION['userBunk'] = $row['bunk'];
			$_SESSION['userWeeks'] = explode(',',$row['week']);
			$_SESSION['userPrereqs'] = explode(',',$row['prerequisites']);
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

// Check to see if User was specified, if not get logged in user's info
function checkUser($con) {
	if (isset($_POST['uID'])) {
		$userInfo = array(
			'userID'=>$_POST['uID'],
			'userName'=>$_POST['thisUserName'],
			'bunkInfo'=>getBunkInfo($_POST['bunkID'],'',$con),
			'userInfo'=>getUserInfo($_POST['uID'],$con)
		);
	} else {
		$userInfo = array(
			'userID'=>$_SESSION['userID'],
			'userName'=>$_SESSION['userFirstName'] .' '. $_SESSION['userLastName'],
			//'userName'=>$_SESSION['userName'],
			'bunkInfo'=>$_SESSION['bunkInfo'],
			'userInfo'=>getUserInfo($_SESSION['userID'],$con)
		);
	}
	return $userInfo;
}

// Get Auth
function getAuth($selected,$required,$userAuth,$disabled,$con) {
	$req = ($required ? 'true' : 'false');
	$dis= (($disabled || $_SESSION['userPermissions']['edit']!=1) ? 'readonly="readonly"' : '');
	$authSelDisableClass = (($disabled || $_SESSION['userPermissions']['edit']!=1) ? 'readonly' : '');
	$output = '<select name="access_level" class="form-control browser-default custom-select '. $authSelDisableClass .'" data-rule-required="'. $req .'" data-msg-required="Please Select Access Level" '. $dis .'><option value="">&lt; select access level &gt;</option>'; 
	$queryParam = (($userAuth>1) ? 'AND id>1' : '');
	$query = 'SELECT * FROM access_levels WHERE active=1 '. $queryParam .' ORDER BY ID DESC';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['id'] .'"';
		if ($row['id'] == $selected)
			$output .= ' selected="selected"';
		$output .= '>'. $row['name'] .'</option>';
	}
	$output .= '</select>';
	return $output;
}

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

// Get Week Info
function getWeekInfo($weekID,$con) {
	$week = ''; 
	$query = 'SELECT * FROM weeks WHERE id='. $weekID .' LIMIT 1'; 
	if($result = $con->query($query)) {
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
		}
	}
	return $week;
}

// Show Camper Schedule
function showCamperSchedule($weekID,$camper,$week,$showName,$bunkInfo,$periods,$con) {
	$content = '<div class="row border border-right-0 border-bottom-0">';
	if($showName) {
		$content .= '<div class="day col-sm p-2 border border-left-0 border-top-0"><h5 class="camper-name">'. $camper['lastName'] .', '. $camper['firstName'] .'</h5></div>';
	}
	$scheduledActivities = showScheduledActivities($weekID,$camper['id'],$camper['prerequisites'],$con);
			
	$d = 1;
	foreach ($week['days'] as $day) {
		$schActivity = ((isset($scheduledActivities)) ? $scheduledActivities[$d] : '');

		$content .= '<div class="day col-sm p-2 border border-left-0 border-top-0 text-truncate">
									<h5 class="row align-items-center">
										<span class="date col-1 d-sm-none nicedate">'. $day['nicedate'] .'</span>
										<small class="col d-sm-none text-center text-muted dayname">'. $day['name'] .'</small>
										<span class="col-1"></span>
									</h5>';
		foreach ($periods as $period) {
			if (in_array($bunkInfo['group'],$period['groups'])) {
				if ($period['days'][$day['number']]==1) {
					$content .= '<div class="period"><h6>'. $period['name'] .'</h6>';
					if (!empty($schActivity[$period['id']])) {
						$content .= '<div class="event btn btn-block btn-light-green agenda-event-button d-block view-only">'. $schActivity[$period['id']]['name'] .'</div>';
					} else {
						$content .= '<div class="btn btn-block btn-light agenda-event-button d-block view-only disabled">Not Scheduled</div>';
					}
					$content .= '</div><!-- /period -->';
				}
			}
		} // foreach period
		$content .= '</div><!-- /day -->';
		$d++;
	} // foreach day
	$content .= '</div><!-- /camper row -->';
	return $content;
}

// Show Activity Signups
function showActivitySignups($activity,$day,$signups,$con) {
	$periods = getPeriods('',false,false,'array',$con);
	if ($day) {
		switch ($day) {
			case 'Monday':
				$d = 1;
				break;
			case 'Tuesday':
				$d = 2;
				break;
			case 'Wednesday':
				$d = 3;
				break;
			case 'Thursday':
				$d = 4;
				break;
			case 'Friday':
				$d = 5;
				break;
		}
		
		$count = 1;
		$p = (($periods[1]['days'][$d] == 1) ? 1 : 2);
		for ($v=0;$v<count($periods);$v++) {
			if ((!isset($p)) && $v==$activity['period]']){
				$p = $v;
			}
		}

		$actSignups = '';  
		if ($periods[$p]['days'][$d]==1) {
			$campers = (is_array($signups[$d]) ? $signups[$d] : '');
			if (!empty($campers)) {
				$actSignups .= '<p class="text-muted"><em>Listed in signup order</em></p><ol class="signupList">';
				foreach ($campers as $camper) {
					$actSignups .= '<li>'. $camper['user']['lastName'] .', '. $camper['user']['firstName'] . ($camper['bunk']['name'] ? ' ('. $camper['bunk']['name'] .')' : '') .'</li>';
				}
				$actSignups .= '</ol><!-- /signupList -->';
			} else {
				$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>';	
			}
		} else {
			$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>'; 
		}
		
		$content .= '<div class="agenda">'. $actSignups .'</div>';
	} else {
		$tableHead = ''; 
		$tableBody = '';
		for ($d=1;$d<=5;$d++) {
			$agenda = '';
			$startDate = $activity['startDate'];
			$date = date_create($startDate);
			date_add($date, date_interval_create_from_date_string(($d-1) .' days'));
			$dayOfMonth = date_format($date,'d');
			$dayOfWeek = date_format($date,'l');
			$monthYear = date_format($date,'F, Y');

			// Show first Activity Period
			$count = 1;
			$p = (($periods[1]['days'][$d] == 1) ? 1 : 2);

			for ($v=0;$v<count($periods);$v++) {
				if ((!isset($p)) && $v==$activity['period]']){
					$p = $v;
				}
			}

			$actSignups = '';  
			if ($periods[$p]['days'][$d]==1) {
				$campers = (is_array($signups[$d]) ? $signups[$d] : '');
				if (!empty($campers)) {
					$actSignups .= '<p class="text-muted"><em>Listed in signup order</em></p><ol class="signupList">';
					foreach ($campers as $camper) {
						$actSignups .= '<li>'. $camper['user']['lastName'] .', '. $camper['user']['firstName'] . ($camper['bunk']['name'] ? ' ('. $camper['bunk']['name'] .')' : '') .'</li>';
					}
					$actSignups .= '</ol><!-- /signupList -->';
				} else {
					$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>';	
				}
			} else {
				$actSignups .= '<p class="text-muted"><em>No Signups Yet</em></p>'; 
			}
			$tableBody .= '<td>'. $actSignups .'</td>';

			//$tableHead .= '<th class="agenda-date"><div class="dayofmonth">'. $dayOfMonth .'</div><div class="dayofweek">'. $dayOfWeek .'</div><div class="shortdate text-muted">'. $monthYear .'</div></th>';
			
			$tableHead .= '<th class="agenda-date"><table class="no-border"><tr><td class="dayofmonth">'. $dayOfMonth .'</td><td><div class="dayofweek">'. $dayOfWeek .'</div><div class="shortdate text-muted">'. $monthYear .'</div></td></tr></table></th>';
		}
		$content .= '<div class="agenda">
			<div class="table-responsive">
				<table class="table table-condensed table-bordered">
					<thead class="thead-dark">
						<tr>'. $tableHead .'</tr>
					</thead>
					<tbody><tr>'. $tableBody .'</tr></tbody>
				</table>
			</div>
		</div>';
	}
	return $content;
}

// Get Users
function getUsers($selected,$return,$con) {
	$output = ''; 
	if ($return == 'select') {
		$query = "SELECT u.id, u.firstName, u.lastName FROM users WHERE active=1 ORDER BY lastName ASC, firstName ASC";
		if ($result = $con->query($query)) {
			$output = '<select name="counselor" class="form-control browser-default custom-select" data-rule-required="false" data-msg-required="Please Select a Counselor"><option value="">&lt; select counselor &gt;</option>'; 
			while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
				$output .= '<option value="'. $row['id'] .'"';
				if ($row['id'] == $selected)
					$output .= ' selected="selected"';
				$output .= '>'. $row['lastName'] .', '. $row['firstName'] .'</option>';
			}
			$output .= '</select>';
		}
	} else {
		$query = "SELECT u.id, u.firstName, u.lastName, u.username, u.email, u.bunk, u.access_level, a.name AS access, b.name AS bunkName FROM users u LEFT JOIN access_levels a on (u.access_level = a.id) LEFT JOIN bunks b ON (u.bunk = b.id) WHERE u.active=1 ORDER BY u.lastName ASC, u.firstName ASC";
		if ($result = $con->query($query)) {
			while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
				$users[] = array(
					'id'=>$row['id'],
					'firstName'=>$row['firstName'],
					'lastName'=>$row['lastName'],
					'username'=>$row['username'],
					'email'=>$row['email'],
					'access_level'=>$row['access_level'],
					'access'=>$row['access'],
					'bunk'=>$row['bunk'],
					'bunkName'=>$row['bunkName'],
				);
			}
			$output = $users;
		}
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
			$output .= ' selected="selected"';
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
	$output = '<select name="'. $name .'" class="form-control browser-default custom-select group-select" data-rule-required="'. $req .'" data-msg-required="Age Group is Required" '. $multiple .'>'. $option1;
	$query = 'SELECT * FROM bunk_age_groups WHERE display=1 ORDER BY age_min ASC, name DESC';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output .= '<option value="'. $row['id'] .'"';
		if (is_array($selected)) {
			if (in_array($row['id'],$selected)) { $output .= ' selected="selected"'; }
		} else {
			if ($row['id'] == $selected) { $output .= ' selected="selected"'; }
		}
		$output .= '>'. $row['name'] .'</option>';
	}
	$output .= '</select>';
	return $output;
}

// Get Bunks
function getBunks($selected,$return,$con) {
	if ($return == 'select') {
		$query = 'SELECT b.id as bunkID, b.name AS bunkName, a.age_min, a.name AS groupName FROM bunks b LEFT JOIN bunk_age_groups a ON (b.groups = a.id) WHERE active=1 ORDER BY a.age_min ASC, a.name DESC';
		$result = $con->query($query);
		$bunkSelDisable = (($_SESSION['userPermissions']['edit']==1) ? '' : 'readonly="readonly"');
		$bunkSelDisableClass = (($_SESSION['userPermissions']['edit']==1) ? '' : 'readonly');
		$output = '<select name="bunk" class="form-control browser-default custom-select '. $bunkSelDisableClass .'" data-rule-required="false" data-msg-required="Please Select Bunk" '. $bunkSelDisable .'><option value="">&lt; select bunk &gt;</option>'; 
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<option value="'. $row['bunkID'] .'"';
			if ($row['bunkID'] == $selected)
				$output .= ' selected="selected"';
			$output .= '>'. $row['bunkName'] .' ('. $row['groupName'] .')</option>';
		}
		$output .= '</select>';
	} else {
		$query = 'SELECT b.* FROM bunks b LEFT JOIN bunk_age_groups a ON (b.groups = a.id) WHERE active=1 ORDER BY a.age_min ASC, a.name DESC, b.name ASC';
		if ($result = $con->query($query)) {
			while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
				$bunks[$row['id']] = array(
					'id'=>$row['id'],
					'name'=>$row['name'],
					'groups'=>$row['groups'],
					'counselor'=>$row['counselor']
				);
			}
			$output = $bunks;
		}
	}
	return $output;
}

// Get Weeks
function getWeeks($return,$selected,$multi,$required,$con) {
	$query = 'SELECT * FROM weeks WHERE active=1 ORDER BY name ASC, startDate ASC';
	$result = $con->query($query);
	$selected = (!empty($selected) ? explode(',',$selected) : array());
	if ($return == 'select') {
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
				$output .= ' selected="selected"';
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
		$output = '<select name="period" class="form-control browser-default custom-select period-select" data-rule-required="'. $req .'" data-msg-required="Period is Required" '. $multiple .'>'. $option1;
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<option value="'. $row['id'] .'"';
			if ($row['id'] == $selected)
				$output .= ' selected="selected"';
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
			$output[$row['id']] = array('id'=>$row['id'],'name'=>$row['name'],'startTime'=>$row['startTime'],'endTime'=>$row['endTime'],'groups'=>explode(',',$row['groups']),'days'=>$days);
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
			if (in_array($row['id'],$selected)) { $output .= ' checked="checked"'; }
		} else {
			if ($row['id'] == $selected) { $output .= ' checked="checked"'; }
		}
		$output .= '> <label>'. $row['name'] .'</label></p></div>';
		$x++;
	}
	$output .= '</div>'; 
	return $output;
}

// Get Restrictions
function getRestrictions($selected,$return,$con) {
	$query = 'SELECT * FROM restrictions WHERE active=1 ORDER BY name ASC';
	$result = $con->query($query);
	
	if ($return == 'checkboxes') {
		$output = '<div class="row d-sm-flex">'; 
		$x = 0;
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<div class="col-12 col-xs-12 col-sm-6 col-md-auto"><p class="checkbox"><input type="hidden" name="restrictions['. $x .']" value="0"><input type="checkbox" name="restrictions['. $x .']" value="'. $row['id'] .'"';
			if (is_array($selected)) {
				if (in_array($row['id'],$selected)) { $output .= ' checked="checked"'; }
			} else {
				if ($row['id'] == $selected) { $output .= ' checked="checked"'; }
			}
			$output .= '> <label>'. $row['name'] .'</label></p></div>';
			$x++;
		}
		$output .= '</div>'; 
	} else { // Return Array
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output = array(
				'id'=>$row['id'],
				'name'=>$row['name'],
				'eligible'=>explode(',',$row['eligible'])
			);
		}
	}
	return $output;
}

// Get Restriction
function getRestrictionInfo($restID,$con) {
	$query = 'SELECT * FROM restrictions WHERE id='. $restID .' LIMIT 1';
	$result = $con->query($query);
	while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
		$output = array(
			'id'=>$row['id'],
			'name'=>$row['name'],
			'eligible'=>explode(',',$row['eligible'])
		);
	}
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
			$outputArray['id'] = $row['id'];
			$outputArray['name'] = $row['name'];
			$outputArray['group'] = $row['groups'];
			$outputArray['counselor'] = $row['firstName'] .' '. $row['lastName'];
		}
	}
	$output = (isset($outputArray) ? $outputArray : '');
	return $output;
}

// Get Activity Types
function getActivityTypes($con) {
	$output = '';
	$query = 'SELECT * FROM activity_types WHERE active=1 ORDER BY name ASC';
	if ($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$output .= '<option data-value="'. $row['id'] .'" data-onetime="'. $row['oneTime'] .'">'. $row['name'] .'</option>';
		}
	}
	return $output;
}

// Check Scheduling Availability
function checkSchedulDate($now,$signupStart,$tipEarly,$signupEnd,$tipLate) {
	if (!$_SESSION['userPermissions']['edit']) {
		if ($now < $signupStart) {
			$disable = 'disabled="disabled"';	
			$tooltip = 'data-toggle="tooltip" data-placement="top" title="'. $tipEarly .'"';
		} elseif ($now > $signupEnd) {
			$disable = 'disabled="disabled"';	
			$tooltip = 'data-toggle="tooltip" data-placement="top" title="'. $tipLate .'"';
		} else {
			$disable = ''; 
			$tooltip = ''; 
		}
	} else {
		$disable = ''; 
		$tooltip = ''; 
	}
	$output = array(
		'disable'=>$disable,
		'tooltip'=>$tooltip
	);
	return $output;
}

// Get Activity Info
function getActivityinfo($actID,$con) {
	$query = 'SELECT a.*, t.name, t.oneTime, w.startDate, w.name AS weekname FROM activities a LEFT JOIN activity_types t ON (a.type = t.id) LEFT JOIN weeks w ON (a.week = w.id) WHERE a.id='. $actID .' LIMIT 1';  
	if($result = $con->query($query)) {
		while ($act=$result->fetch_array(MYSQLI_ASSOC)) {
			$activity = array(
				'id'=>$act['id'],
				'name'=>$act['name'],
				'type'=>$act['type'],
				'description'=>$act['description'],
				'location'=>$act['location'],
				'capacity'=>$act['capacity'],
				'groups'=>explode(',',$act['groups']),
				'week'=>$act['week'],
				'weekname'=>$act['weekname'],
				'startDate'=>$act['startDate'],
				'period'=>$act['period'],
				'days'=>array(
					'monday'=>$act['monday'],
					'tuesday'=>$act['tuesday'],
					'wednesday'=>$act['wednesday'],
					'thursday'=>$act['thursday'],
					'friday'=>$act['friday']
				),
				'prerequisites'=>$act['prerequisites'],
				'restrictions'=>$act['restrictions']
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
	$sql = 'SELECT a.*, t.id AS type, t.name, t.oneTime FROM activities a LEFT JOIN activity_types t ON (a.type = t.id) WHERE a.week='. $week .' AND a.active=1 ORDER BY t.name';  
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
				'type'=>$act['type'],
				'name'=>$act['name'],
				'week'=>$act['week'],
				'period'=>$act['period'],
				'prerequisites'=>$act['prerequisites'],
				'restrictions'=>$act['restrictions'],
				'oneTime'=>$act['oneTime'],
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

// Get Activites for the Day
function getDayActivities($week,$day,$con) {
	$sql = 'SELECT a.*, t.id AS type, t.name, t.oneTime FROM activities a LEFT JOIN activity_types t ON (a.type = t.id) WHERE a.week='. $week .' AND '. strtolower($day) .'=1 ORDER BY t.name';  
	if ($res = $con->query($sql)) {
		while ($act=$res->fetch_array(MYSQLI_ASSOC)) {
			$activity = array(
				'id'=>$act['id'],
				'type'=>$act['type'],
				'name'=>$act['name'],
				'week'=>$act['week'],
				'period'=>$act['period'],
				'prerequisites'=>$act['prerequisites'],
				'restrictions'=>$act['restrictions'],
				'oneTime'=>$act['oneTime'],
				'space'=>(($act[strtolower($day)]) ? $act['capacity']-$act['reg'. $day] : 0)
			);
			$dayActivities[$act['id']] = $activity;
		}
	}
	return $dayActivities;
}

// Get Scheduled Activities
function getScheduledActivities($week,$user,$con) {
	$query = 'SELECT * FROM activity_signups WHERE week='. $week .' AND user='. $user .' AND active=1';
	if ($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$signupInfo = array('id'=>$row['id'],'activity'=>$row['activity']);
			switch ($row['day']) {
				case 'Monday':
					$mon[$row['period']] = $signupInfo;
					break;
				case 'Tuesday':
					$tues[$row['period']] = $signupInfo;
					break;
				case 'Wednesday':
					$wed[$row['period']] = $signupInfo;
					break;
				case 'Thursday':
					$thurs[$row['period']] = $signupInfo;
					break;
				case 'Friday':
					$fri[$row['period']] = $signupInfo;
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
		$scheduledActivities = ''; 
	}
	return $scheduledActivities;
}

// Show Agenda Activities
function showAgendaActivities($week,$day,$actArray,$period,$admin,$actScheduled,$userID,$con) {
	$activities = '';
	$scheduleID = '';
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
						$checked = ''; 
						$actClass = '';
						$actData = ''; 
						if (is_array($actScheduled)) {
							if(isset($actScheduled[$period])) {
								$scheduleID = $actScheduled[$period]['id'];
								if ($activity['id'] == $actScheduled[$period]['activity']) {
									$active = 'active';
									$checked = 'checked="checked"'; 
								}
							} 
						} 
						$required = (empty($activities) ? 'true' : '');
						
						$disable = '';
						$disableClass = '';
						$tooltip = ''; 
						
						// Admins can override all Scheduling Disables
						// Non Admins will go throught the disable check process
						if (!$_SESSION['userPermissions']['admin']) {
						
							// Disable for campers that do not have necessary Prerequisites
							$actPrereqs = array_filter(explode(',',$activity['prerequisites']));
							if (count($actPrereqs)>0) { 
								$qualified = false;
								$camper = getUserInfo($userID,$con);
								$camperPrereqs = array_filter(explode(',',$camper['prerequisites']));
								if (count($camperPrereqs)>0) {
									foreach ($actPrereqs as $prereq) {
										if (in_array($prereq,$camperPrereqs)) { $qualified = true; }
									}
								}
								if (!$qualified) {
									$disable = 'disabled="disabled"';	
									$disableClass = 'disabled';
									$tooltip = 'data-toggle="tooltip" data-placement="top" title="'. $camper['firstName'] .' is not authorized for '. $activity['name'] .'.  Please contact the Camp Office with any questions."';
								}
							} 
							
							// Disable if Restricted (Staff only, Paying Campers Only)
							$actRestrict = array_filter(explode(',',$activity['restrictions']));
							if (count($actRestrict)>0) { 
								$camper = getUserInfo($userID,$con);
								foreach ($actRestrict as $restID) {
									$rest = getRestrictionInfo($restID,$con);
									if (!in_array($camper['access_level'],$rest['eligible'])) {
										$disable = 'disabled="disabled"';	
										$disableClass = 'disabled';
										$tooltip = 'data-toggle="tooltip" data-placement="top" title="This '. siteVar('act','singular','capital') .' is for '. $rest['name'] .'"';
									}
								}
							}
							
							// Disable if this is a one-time activity and the camper has already done it 
							if ($activity['oneTime']) {
								$tooltip = 'data-toggle="tooltip" data-placement="top" title="'. $activity['name'] .' can only be taken once per summer"';
								$column = date('Y'); // Camp Year Column
								$sql = "SELECT `". $column ."` FROM user_activities WHERE user=". $userID; 
								if ($result = $con->query($sql)) {
									$actClass .= 'onetime onetime-'. $activity['id'];
									$actData .= 'data-onetime="onetime-'. $activity['id'] .'"';
									$userActAll = $result->fetch_array(MYSQLI_ASSOC);
									$userAct = explode(',',$userActAll[$column]);
									if (in_array($activity['type'],$userAct)) {
										$disable = 'disabled="disabled"';	
										$disableClass = 'disabled';
										$actData .= ' data-previous="yes"';
										//$tooltip = 'data-toggle="tooltip" data-placement="top" title="'. $activity['name'] .' can only be taken once per summer"';
									}
								} 
							} 
							
							// Disable for Campers if Full
							if ($activity['space'][strtolower($day)]<=0) { 
								$disable = 'disabled="disabled"';	
								$disableClass = 'disabled';
								$tooltip = 'data-toggle="tooltip" data-placement="top" title="This '. siteVar('act','singular','capital') .' is Full"';
							}
						}
						
						$activities .= '<li '. $tooltip .' class="schedule-item '. $actClass .'">
							<input type="radio" class="schedule-radio '. $active .'" id="activity-'. $week .'-'. $day .'-'. $period .'-'. $activity['id'] .'" name="activity-'. $week .'-'. $day .'-'. $period .'" value="'. $activity['id'] .'" data-rule-required="'. $required .'" data-msg-required="Please Select an Activity" '. $checked .' '. $disable .' data-scheduleID="'. $scheduleID .'">
							<label for="activity-'. $week .'-'. $day .'-'. $period .'-'. $activity['id'] .'" class="btn btn-light schedule-button '. $active .' '. $disableClass .'" '. $actData .'>'. $activity['name'] .'
								<div class="small">'. $activity['space'][strtolower($day)] .' spots left</div>
							</label>
						</li>';
					}
				}
			}
		}
		if (!$admin && !empty($activities)) { 
			$activities = '<input type="hidden" name="schedule-'. $week .'-'. $day .'-'. $period .'" value="'. $scheduleID .'">
			<ul class="activity-signup-buttons" data-scheduleID="'. $scheduleID .'">'. $activities .'</ul>'; 
		}
	} 
	$output = ((!empty($activities)) ? $activities : '');
	return $output;
}

// Show Scheduled Activities
function showScheduledActivities($week,$user,$prereqs,$con) {
	$sql= 'SELECT s.id, s.week, s.day, s.period, s.activity, t.name, a.prerequisites FROM activity_signups s LEFT JOIN activities a ON (s.activity = a.id) LEFT JOIN activity_types t ON (a.type = t.id) WHERE s.week='. $week .' AND s.user='. $user .' AND s.active=1 AND a.active=1';
	if (!is_array($prereqs)) { $prereqs = explode(',',$prereqs); }
	if ($res = $con->query($sql)) {
		while ($r=$res->fetch_array(MYSQLI_ASSOC)) {
			$canSchedule = false;
			if (empty($prerequisites)) {
				$canSchedule = true;	
			} else {
				$prerequisites = (!is_array($r['prerequisites']) ? explode(',',$r['prerequisites']) : $r['prerequisites']);
				if (in_array($r['activity'],$prerequisites)) {
					$canSchedule = true;
				}
			}
			switch ($r['day']) {
				case 'Monday':
					$mon[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name'],$canSchedule);
					break;
				case 'Tuesday':
					$tues[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name'],$canSchedule);
					break;
				case 'Wednesday':
					$wed[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name'],$canSchedule);
					break;
				case 'Thursday':
					$thurs[$r['period']] = array('id'=>$r['activity'],'name'=>$r['name'],$canSchedule);
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
		//$scheduledActivities = array(1=>'',2=>'',3=>'',4=>'',5=>'',);
		$scheduledActivities = '';
	}
	return $scheduledActivities;
}

// Get Bunk Roster
function getBunkRoster($bunkID,$counselorID,$con) {
	if ($bunkID==0) {
		$getID = mysqli_fetch_assoc(mysqli_query($con, 'SELECT id FROM bunks WHERE counselor='. $counselorID));
		$bunkID = $getID['id'];
	} 
	$query = 'SELECT id, firstName, lastName, email, lastLogin, week, prerequisites, bunk FROM users WHERE bunk='. $bunkID .' AND active=1 AND access_level!=3 ORDER by lastName ASC, firstName ASC';
	if ($result = $con->query($query)) {
		while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
			$user = array(
				'id'=>$row['id'],
				'firstName'=>$row['firstName'],
				'lastName'=>$row['lastName'],
				'email'=>$row['email'],
				'lastLogin'=>$row['lastLogin'],
				'week'=>$row['week'],
				'prerequisites'=>$row['prerequisites'],
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
		$scheduleDateStart = date_format(date_create($lastTuesday),'Y-m-d 19:00:00');
		$lastSunday = date('Y-m-d', strtotime('previous sunday', strtotime($date)));
		$scheduleDateEnd = date_format(date_create($lastSunday),'Y-m-d 23:59:59');
		$scheduleDates = array(
			'start'=>$scheduleDateStart,
			'end'=>$scheduleDateEnd
		);
	} else {
		$scheduleDates = ''; 
	}
	return $scheduleDates;
}

// Check One-Time Activities
function checkOneTimeAct($typeID,$con) {
	$query = 'SELECT oneTime FROM activity_types WHERE id='. $typeID; 
	if($result = $con->query($query)) {
		$res = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$output = $res['oneTime'];
	}
	return $output;
}

// Define Column Sort Variables for Sortable Tables
/*function defineSorts($num,$orderBy) {
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
}	*/



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