<?php

function logger($msg) {
	if(gettype($msg) == 'array') logger(print_r($msg, true));
	elseif(gettype($msg) == 'object') logger(get_object_vars($msg));
	else echo $msg;
}

require_once('etc/config.php');
?>
