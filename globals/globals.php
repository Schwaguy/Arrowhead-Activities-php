<?php

global $ROOT;
$ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once('config.php');
require_once('functions.php');
require_once('phpself.php');
require_once('sanitize.php');

global $con;
$con = new mysqli($host,$un,$pw,$db);
if ($con->connect_errno) {
    printf("Connect failed: %s\n", $con->connect_error);
    exit();
}

global $weekdays;
$weekdays = array(
	1=>'monday',
	2=>'tuesday',
	3=>'wednesday',
	4=>'thursday',
	5=>'friday',
);

global $adminAccessLevels,$camperAccessLevels;
$adminAccessLevels = array(1,2);
$camperAccessLevels = array(4,5,7);

/*
// Global Element File
global $URL, $ROOTPATH, $SITEURL;
$URL = 'https://' .$_SERVER['SERVER_NAME'];
$ROOTPATH = '.'; 
$SITEURL = 'https://arrowheadclubday.com/'; 

global $comanyName, $formFrom, $formFromEmail;
$companyName = 'Arrowhead Day Camp';
$formFrom = $companyName;
$formFromEmail = 'chiefarrowhead@comcast.net'; */

global $content, $redirect;
$content = '';

// Login/Out, Main Admin, Back Links
global $logoutLink, $backtoMain, $backBtn, $adminBtns;
$adminBtns = ''; 
$logoutLink = $phpself . '?logout=logout';
$backtoMain = '<p id="backtoMain"><a href="' . $phpself . '" name="main">Back to Main Admin</a></p>';
//$backBtn = '<p id="backBtn"><a href="javascript:history.back()">Back</a></p>';
$backBtn = '<button id="backBtn" class="btn btn-default" onclick="javascript:history.back()"><i class="fa fa-angle-double-left"></i> Back</button>';

// Copyright Information
$copyright = 'Copyright &copy; ';
$copyright .= date("Y");
$copyright .= ' '. $companyName . ' LLC. All Rights Reserved.';

// Confirmation PopUp
$areYouSure = '<script language = "javascript">
				<!--
				function sure(ID) {
					y = confirm ("Are you sure you want to DEACTIVATE?"); 
					if (y) 
						return true;
					else 
						return false;
				}
				//-->
				</script>';

?>