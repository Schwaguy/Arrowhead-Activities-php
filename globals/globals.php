<?php

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

// Date
global $today, $now; 
date_default_timezone_set("America/New_York");
$today = date('Y-m-d');
$now = date('Y-m-d H:i:s');
$now = date('2019-06-25 19:01:00'); 

global $weekdays;
$weekdays = array(
	1=>'monday',
	2=>'tuesday',
	3=>'wednesday',
	4=>'thursday',
	5=>'friday',
);

// Global Element File
global $URL, $ROOTPATH, $SITEURL;
$URL = 'http://' .$_SERVER['SERVER_NAME'];
$ROOTPATH = '.'; 
$SITEURL = 'http://activities.arrowheaddaycamp.com'; 

global $comanyName, $defaultCompany;
$companyName = 'Arrowhead Club Days';
$defaultCompany = 130;

global $admin, $pageName, $greeting, $customScript, $content, $controls, $filters, $controlsRight, $pageSubTitle;
$admin = ''; 
$pageName = '';
$greeting = ''; 
$customScript = ''; 
$content = '';
$controls = ''; 
$filters = ''; 
$controlsRight = '';
$pageSubTitle = ''; 

global $docReady, $tabTable, $tableID, $footerScripts, $modalScripts, $ajaxUpdate; 
$docReady = '';
$tabTable = false;
$tableID = '';
$footerScripts = ''; 
$modalScripts = '';
$ajaxUpdate = false; 

// Login/Out, Main Admin, Back Links
global $logoutLink, $backtoMain, $backBtn, $adminBtns;
$adminBtns = ''; 
$logoutLink = $phpself . '?logout=logout';
$backtoMain = '<p id="backtoMain"><a href="' . $phpself . '" name="main">Back to Main Admin</a></p>';
//$backBtn = '<p id="backBtn"><a href="javascript:history.back()">Back</a></p>';
$backBtn = '<button id="backBtn" class="btn btn-default" onclick="javascript:history.back()"><i class="fa fa-angle-double-left"></i> Back</button>';

// Report Titles
global $amtInv, $comRate, $comDue;
$amtInv = 'Invoiced'; 
$comishRate = 'Comp %'; 
$comDue = 'Commission';

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

// Email This
$emailThis = '<script type="text/javascript">
				function mailThisPage() {
					var link = window.location;
					var pageTitle = document.title;
					pageTitle = pageTitle.replace(/&/g, "and").replace(/&amp;/g, "and").replace(/#/g, "");
					var emailSubject = "Check this out: "+ pageTitle;
					var emailAddress = prompt("Please enter the recipients email address","");
					window.location  = "mailto:"+emailAddress+"?Subject="+emailSubject+"&body="+link;                    
				};
			</script>';

?>