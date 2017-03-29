<?php

// not really a page - just 

//logger('yo');

require_once('lib/track.php');

$mode = getAlNumUC(@$_REQUEST['meta']);

switch($mode) // {{{
{

    // Choose day
    case 'login'         : login($_REQUEST['user']);     exit();

    default         : $_404_msg = "Unable to handle mode: $mode"; include('pages/404.php');

} // }}}

