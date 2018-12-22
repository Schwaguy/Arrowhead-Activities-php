<?php

session_destroy();
header("Location: ". $SITEURL);
die();

?>