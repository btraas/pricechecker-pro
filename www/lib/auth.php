<?php

/*
require_once('lib/config.php');
require_once("vendor/google-api/vendor/autoload.php")

$client = new Google_Client();
$client->setAuthConfig('../credentials/client_secret.json');
//$client->addScope("https://www.googleapis.com/auth/fitness.activity.write");
//$client->addScope("https://www.googleapis.com/auth/fitness.body.write");

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri($redirect_uri);
*/

require_once('lib/user.php');

define('GOOGLE_TOKENINFO_URL', 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=');


session_start();


function fail() {
	header('Location: '.BASE_URL);
	exit();
}

function success() {
	header('Location: '.BASE_URL.'/lookup');
	exit();
}



function validate() { // {{{

	if(empty($_REQUEST['meta']) || empty($_REQUEST['value'])) {
		fail();
	}
	
	$type = getAlNumLC($_REQUEST['meta']);
	$token = getPrintable($_REQUEST['value']);
	
	logger("validate: $type");

	switch($type) {
	
		case 'googletoken' : login(validateGoogleToken($token)); break;
		default : fail();
	
	}
} // }}}
function authenticate() { // {{{
	logger('authenticating...');
	return validate();
} // }}}

function getGoogleToken() // {{{
{
	$token = @$_SESSION['googletoken'];
	if(empty($token)) fail();
	return $token;
} // }}}
function validateGoogleToken($token) { // {{{
	
	$response = json_decode(@file_get_contents(GOOGLE_TOKENINFO_URL . $token), true);
	
	// print_r($response);
	
	if(empty($response)) 
	{
		echo "INVALID TOKEN";
		fail();
	}

	$clientIDs = unserialize(GOOGLE_CLIENT_IDS);
	if(!in_array($response['aud'], $clientIDs)) {
		echo "INVAILD CLIENT ID";
		fail();
	}


	$_SESSION['googletoken'] = $token;

	//login($response);
	return $response;

} // }}}

function loginGoogle() // {{{ Shortcut
{
	login(validateGoogleToken(getGoogleToken()));
} // }}}
function getGoogleUser() // {{{
{
	return getUser(getGoogleToken());
} // }}}

function login($userdata, $create=true) { // {{{

	// logger($userdata);

	if(empty($userdata) || empty($userdata['email'])) fail();

	$user = new User($userdata['email']);

	if(empty($user->data)) {

		if(!$create) throw new Exception("Login failed!");

		logger("User $userdata[email] not in db!");

		//echo "User $userdata[email] not in db!<br />";
		if($user->create($userdata) !== false) login($userdata, false);
		else throw new Exception("Unable to add user!");
		return;
	}

	$user->update($userdata);
	
	//print_r($user);


	// If we got to this point, success. Take me in!
	success();
		

} // }}}

function getUser($token) { // {{{
	$userdata = validateGoogleToken($token);

	//print_r($userdata);

	if(empty($userdata) || empty($userdata['email'])) fail();

	$user = new User($userdata['email']);
	//$user->data = runQ($q, array($userdata['email']))[0];

	//print_r($user);

	return $user;
} // }}}





























?>
