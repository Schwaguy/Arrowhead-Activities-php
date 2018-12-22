<?php

//global $phpself;

//Get the name of the file (form.php)
//$phpself = basename(__FILE__);

//Get everything from start of PHP_SELF to where $phpself begins

//Cut that part out, and place $phpself after it
$_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'],
$phpself)) . $phpself;

//You've got a clean PHP_SELF again (y) 

?>