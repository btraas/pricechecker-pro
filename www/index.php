<?php

// True entry point for all web stuff

ini_set("display_startup_errors", 1); 
ini_set("display_errors", 1);
ini_set("max_execution_time", 5);
error_reporting(E_ALL);

ob_start();

// Entry point for most web calls.


require_once('etc/config.php');
require_once('lib/common.php');

require_once('lib/db.php');
require_once('lib/mdl.php');
require_once('lib/auth.php');
require_once('lib/app.php');



//print_r(@$_REQUEST);

$access_granted = false;

$no_auth = array(
                    "/home",
                    "/authenticate",
                    "/index.php?p=authenticate"
                );


if(isset($_REQUEST['debug'])) print_r($_REQUEST);

$page = getAlNum(@$_REQUEST['p']);
if(empty($page))
{
    logger("No page! loading home");
    include("pages/home.php");
    exit();
}

logger("page: $page");

foreach( $no_auth AS $prefix )
{
    //logger("checking $prefix with $_SERVER[REQUEST_URI]");
    if( strpos( $_SERVER['REQUEST_URI'], $prefix ) !== false ) $access_granted = true;
}


// if not granted so far, user must be logged in.

if(!$access_granted)
{
    // redirect to login at this point.


    // logger("Access Not granted. Getting google user...");
    $user = getGoogleUser();        // Only google so far, so login with google token.


	if(empty($user)) header('/');

}                                   // Will redirect if not logged in.





$file = "pages/$page.php";

if(!file_exists($file)) $file = 'pages/404.php';

include($file);


?>

