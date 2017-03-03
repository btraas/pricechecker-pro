<?php

// True entry point for all web stuff

ini_set("display_startup_errors", 1); 
ini_set("display_errors", 1);
error_reporting(E_ALL);

//phpinfo();


include('loader.php');

logger($_REQUEST);

//logger('well hello there!');

?>
