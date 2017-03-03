<?php


// Simplified SQL with runQ()


$write_commands = array('UPDATE', 'DELETE', 'INSERT', 'CREATE', 'ALTER');

//Function to sanitize values received from the form. Prevents SQL injection
/*
function clean($str) {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysqli_real_escape_string($str);
}
*/


class MySQL  // {{{
{
    private static $db = null;          // a PDO DB object.  The connection.

    // Create private methods to ensure only one instance of this object ever exists.
    private function __construct() {}
    private function __clone() {}

    public static function call( $dsn=null, $user=null, $password=null, $options=null ) // {{{
    {
        if( !self::$db )
        {
            self::$db = new PDO( $dsn, $user, $password, $options );
            self::$db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );                       // Enforce prepared statements
            self::$db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );          // Return data as an associative array
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );               // Case an Exception on error (force full handling)
#           self::$db->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, true );
        }

        return self::$db;

    } // }}}

	public static function fail($msg) {
		runQ_new("ROLLBACK");
		die($msg);
	}

} // }}}



// An initial connection & test to ensure things are working {{{ 
try
{
    $options = array();
    MySQL::call( "$dsn[phptype]:dbname=$dsn[database];host=$dsn[host]", $dsn['username'], $dsn['password'], $options )->query( 'SELECT 1' );
}
catch( PDOException $e )
{
    logger( "\n\nOOPS!!! " . $e->getMessage() . "\n\n" );
	echo "$dsn[phptype] DSN error...";
    exit();
} // }}}



function runQ($query, $params=array()) // {{{
{

	//logger("runQ($statement, ".serialize($params).")");

	// No longer allow legacy runQ
	if(null === $params || !is_array($params)) echo "ERROR: Legacy runQ(\$q) unsupported.\n";
	else return runQ_new($query, $params);

	//Global $write_commands;

	//if(!empty($param) && is_array($param))  return runQ_new($query, $param);
	//if(!empty($param) && !is_array($param)) return runQ_old($query, $param);

	//$command = getAlNumUC(substr(trim($query), 0, 6));

	//if(in_array($command, $write_commands) ) return runQ_old($query, $param);

	//return runQ_new($query, $param);
} // }}}

function runQ_new($statement, $params=array()) // {{{
{
	//logger("runQ_new($statement, ".serialize($params).")");

	try
    {
        $stmt = MySQL::call()->prepare( $statement );
    }
    catch( PDOException $e )
    {
        logger( "PDO Error " . $e->getMessage() . " while trying to prepare for: $statement" );
        return false;
    }
    if( !$stmt )
    {
        logger( "PDO Error while trying to prepare for: $statement" . print_r( MySQL::call()->errorInfo(), true ) );
        return false;
    }


    try
    {
        $exec = $stmt->execute( $params );
    }
    catch( PDOException $e )
    {
        logger( "PDO Error " . $e->getMessage() . " while executing: $statement" );
        return false;
    }


    if( $exec ) return $stmt->fetchAll();
	return false;

} // }}}
function runQ_old($query, $lines=-1) // {{{
{

	logger("Use of deprecated runQ()");
    logger(debug_backtrace());

	Global $dsn;

    //Connect to mysql server
    $con = mysqli_connect($dsn['host'], $dsn['username'], $dsn['password'], $dsn['database']);
    if(!$con) {
        die('Failed to connect to server: ' . mysqli_error());
    }

    //Select database
    //$db = mysqli_select_db(DB_DATABASE);
    //if(!$db) {
    //    die("Unable to select database");
    //}

    $result=mysqli_query($con, $query);
    if(!$result) {
		logger("Query failed: $query");
		return false;
	}
	if($result === true) return true;


	$data = array();
	$i = 0;
	

	while(($lines < 0 || $i < $lines) && ($row = mysqli_fetch_assoc($result)))
	{
		$data[] = $row;
		$i++;
	}
    mysqli_close($con);

	if ($lines == 1) return @$data[0];

    return $data;


} // }}}


?>

