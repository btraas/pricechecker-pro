<?php

require_once('lib/mdl.php');

$color_main		= new MDL\Color('blue-grey');
$color_accent	= new MDL\Color('teal');

$theme = new MDL\Theme($color_main, $color_accent);
logger('assigned theme for '.$color_main->hex().' '.$color_accent->hex());

define('GOOGLE_CLIENT_ID', '579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe');

define('GOOGLE_CLIENT_IDS', serialize(array(
		'579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe',
		'579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe.apps.googleusercontent.com')));


define('HOMEURL',       '/');

$dsn = array(	'phptype'	=> 'mysql',
				'host'		=> 'localhost',
				'database'	=> 'sslifts',
				'username'  => 'sslifts',
				'password'  => 'WehyiceCr3amVan1lla');
	



?>
