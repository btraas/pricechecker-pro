<?php

// App-specific functions

// PHP UI library I built based on MDL css
require_once('lib/mdl.php');

// Defined colors for theme
$color_main     = new MDL\Color('blue-grey');
$color_accent   = new MDL\Color('blue');

// Define theme
$theme = new MDL\Theme($color_main, $color_accent);
logger('assigned theme for '.$color_main->hex().' '.$color_accent->hex());

// For google auth / APIs
define('GOOGLE_CLIENT_ID', '579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe');

define('GOOGLE_CLIENT_IDS', serialize(array(
        '579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe',
        '579779341532-cngl3rr9d0jj7m5p9od5f7216fp04toe.apps.googleusercontent.com')));





function _404() {
	include('pages/404.php');
}
