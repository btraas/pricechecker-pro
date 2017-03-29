<?php

// not really a page - just an endpoint

require_once('lib/user.php');



logger($_REQUEST);

$mode = getAlNumUC(@$_REQUEST['meta']);

switch($mode) // {{{
{

    // Choose day
    case 'UPDATEUSER'         : updateUser(@$_REQUEST['value']);     exit();

    default         : $_404_msg = "Unable to handle mode: $mode"; include('pages/404.php');

} // }}}


function updateUser($user) {

	$dbUser = new User($user['email']);
	if(empty($dbUser->data)) {

		$dbUser->create($user);
	} else {
		$dbUser->update($user);
	}

}
