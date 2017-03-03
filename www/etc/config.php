<?php

// Various application settings are defined here.


// Which subset of applications to activate
define( 'APP_MODE', 'PRICECHECKERPRO' );
define( 'APP_NAME', 'Price Checker Pro');
define( 'BASE_DOMAINNAME', "pricechecker.pro" );
define( 'BASE_URL', "https://" . BASE_DOMAINNAME );
define( 'HOMEURL',       '/' );


date_default_timezone_set( 'America/Vancouver' );

// Cache various info to prevent re-entrant calls from hammering the DB {{{
// Values are in seconds

// }}}

// Extra Debug info? {{{
define( 'DEBUG_MODE', 'f' );								// 't' || 'f' || undefined.   t == extra debugging info
if( DEBUG_MODE == 't' )
{
	error_reporting( E_ALL );

	// NOTE:  Setting these to On will not display PHP syntax (parsing) errors.  
	//        To see these errors, you'll need to set these in /etc/php5/apache2/php.ini
	ini_set( "display_errors", 'On' );
	ini_set( "display_startup_errors", 'On' );
}
// }}}

// Application Log settings {{{
// NOTE: $loggingDefaults['owner'] must have write-access to dirname( $loggingDefaults['logfile'] )
$loggingDefaults = array(
	"enabled"   => true,
	"logfile"   => "/tmp/pricecheckerpro.log",
	"owner"     => "www-data",
	"group"     => "www-data",
	"perms"     => 0660,
	"echoToo"   => false,
	"asHTML"    => true,
	"append"    => true
);
// }}}

// Smarty template engine settings {{{

define( 'SMARTY_ENABLED', false );

// Folder under docroot where templates are located
define( 'SMARTY_TEMPLATE_DIR', "templates" );

// Smarty's working dirs under docroot.  www-data must have read/write access to these folders
define( 'SMARTY_COMPILE_DIR', "templates/compiled" );
define( 'SMARTY_CACHE_DIR', "templates/cached" );

// }}} 

// Database connection settings {{{
$dsn = array(
	'phptype'   => 'mysql',
	'host'      => 'localhost',
    'database'  => 'pricecheckerpro',
    'username'  => 'pricecheckerpro',
	'password'  => 'API4beastBySuck5'
);

// }}}

// LDAP / AD Authentication settings {{{

define( 'LDAP_ENABLED', true );

define( 'AD_DOMAIN', 'DEVRY' );

$authOptions = array(
	'host'          => 'stewart',
	'port'          => 3268,
	'version'       => 3,
	'referrals'     => false,
	#   'basedn'        => 'OU=SBSUsers, OU=Users, OU=MyBusiness, DC=cheam, DC=devry, DC=local',
	'binddn'        => "www-data@" . AD_DOMAIN,
	'bindpw'        => "Fl0wer-P0wer",
	'userattr'      => 'samAccountName',
	'userfilter'    => '(ObjectClass=user)',
	'attributes'    => array(),
	//  'group'         => 'Users',
	//  'groupattr'     => 'samAccountName',
	'groupfilter'   => '(ObjectClass=group)',
	'memberattr'    => 'member',
	'memberisdn'    => true,
	//  'groupdn'       => 'cn=Users',
	'groupscope'    => 'one',
	'debug'         => true,
	'enableLogging' => false,           // Enable this to dump LOTS of additional stuff to log file.  Including extra LDAP fields we can use.
	'start_tls'     => true,
);

// }}}

// Must site run over SSL, or is regular HTTP okay? {{{
if( defined( 'STDIN' ) ) define( 'HTTPS_REQUIRED', false );		// If runnig from cmdline, HTTPS is NOT required  :)
else define( 'HTTPS_REQUIRED', true ); 

# Pages that don't require HTTPS
$https_exceptions = array( 'barcode.php' );
# barcode.php:   Crystal Reports can't load dynamic images over HTTPS.  HTTP-only.    :-(

// }}}


ini_set( 'memory_limit', '256M' );
set_time_limit( 30 );          // set max_execution_time to 30 seconds



#------------------------------------------------------------------------------

// Various safety checks {{{

// On this file...
if( fileowner(__FILE__) != 0 ) die( basename( __FILE__ ) . ": Error 001\n" );									// Must be owned by root
$g = posix_getgrgid( filegroup(__FILE__) );
if( $g['name'] != $loggingDefaults['group'] ) die( basename( __FILE__ ) . ": Error 002\n" );					// Group must be 'www-data'
if( substr(sprintf('%o', fileperms(__FILE__)), -4) != "0640" ) die( basename( __FILE__ ) . ": Error 003\n" );	// Ownership must be '0640'


// Make sure HTTPS is enabled (if it's required)
$basename = basename( @$_SERVER['SCRIPT_NAME'] );
if( HTTPS_REQUIRED  &&  @$_SERVER['HTTPS'] != "on"  &&  !in_array( $basename, $https_exceptions ) ) die( basename( __FILE__ ) . ": Error 004\n" );


// }}}

?>
