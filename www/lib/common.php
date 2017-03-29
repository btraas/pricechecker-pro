<?php

# Common Useful functions 
# 
# These are all things I want set for every web-based application I write.
#------------------------------------------------------------------------------

# Default Log file settings {{{
# Set default values for any as-yet-undefined logfile settings
# NOTE:  $loggingDefaults is defined in etc/config.php
if( !isset( $logging )  ||  !is_array( $logging ) ) $logging = array();
if( !empty( $loggingDefaults ) ) foreach( $loggingDefaults AS $key => $val ) if( !isset( $logging[$key] ) ) $logging[$key] = $val;
if( !isset( $logging['enabled'] ) ) $logging['enabled'] = false;
# }}}

if(!function_exists('logger'))  { function logger( $msg, $echoToo=null, $asHTML=null ) # {{{
{
	Global $logging, $auth, $profile;
	if( is_object( $profile ) ) $profile->start();

	if( !$logging['enabled'] ) { if( is_object( $profile ) ) $profile->stop(); return false; }

	$logfile = "/tmp/webapp.log";
	if( $logging['logfile'] ) $logfile = $logging['logfile'];

	$append = null;
	if( $logging['append'] ) $append = FILE_APPEND;

	if( is_null( $echoToo ) )
	{
		if( isset( $logging['echoToo'] ) ) $echoToo = $logging['echoToo'];
		else $echoToo = false;
	}

	if( is_null( $asHTML ) )
	{
		if( isset( $logging['asHTML'] ) ) $asHTML = $logging['asHTML'];
		else $asHTML = true;
	}

	if( is_array( $msg ) || is_object( $msg ) ) $msg = print_r( $msg, true );		// Convert arrays to a string
	if( strlen( $msg ) == 0 ) { if( is_object( $profile ) ) $profile->stop(); return false; }

	// Append username if known {{{
	if( !empty( $auth ) )
	{
		$username = $auth->getUsername();
		if( !empty( $username ) )
			$userInfo = ": " . $auth->getUsername();
		else $userInfo = "";
	}
	else
		$userInfo = "";
	// }}}

	// If file does not exist, then create; setting ownership & perms {{{
	if( !file_exists( $logfile ) )
	{
		file_put_contents( $logfile, "", $append );
		@chmod( $logfile, $logging['perms'] );
		@chgrp( $logfile, $logging['group'] );
		@chown( $logfile, $logging['owner'] );
		
	} // }}}

	$trace = debug_backtrace();

	file_put_contents( $logfile, 
			date( 'Y-m-d H:i:s' ) 
			. ": " . getClientIP() 
			. $userInfo
			. ": " . basename( $trace[0]['file'] ) . "[" . $trace[0]['line'] . "]"
			. ": ".trim($msg)."\n", 
			$append );

	if( !$logging['append'] )	// If we just created the file... {{{
	{
		chmod( $logfile, $logging['perms'] );
		chgrp( $logfile, $logging['group'] );
		chown( $logfile, $logging['owner'] );
	} // }}}


	// Dump to screen/console/HTML page too?
	if( $echoToo && $asHTML ) echo "\n<!-- ";
	if( $echoToo ) echo "$msg";							// Echo it out just as it was provided
	if( $echoToo && $asHTML ) echo " -->\n";
# 	if( $echoToo && !$asHTML ) 
# 		if( trim( strlen( $msg ) ) > 0  &&  $msg[strlen($msg)-1] != "\n" ) echo "\n";		// If string doesn't end with a newline, then add it.

	if( is_object( $profile ) ) $profile->stop();
	return true;
} } 
# }}}

// Time/Date Stuff {{{
function getAgeStr( $start, $end=null, $precision=null ) // {{{
{
	// Return a string loosely describing how long ago (or from now) $start is from $end (or now if end is null)
	// i.e. "2.5 days"
	// i.e. "3.6 hrs ago"
	// precision == number of decimal places....

	// Get Start/End/Diff values {{{
	$start = getTimestamp( $start );

	if( is_null( $end ) ) $end = time();
	else $end = getTimestamp( $end );
	if( empty( $end ) ) $end = time();		// Fail-over to 'now'

	$age = $end - $start;
	// }}}

	// Calculate precision {{{
	if( is_null( $precision ) )
	{
		// Don't show partial minutes
		if( $age < ( 2 * 60 * 60 ) ) $precision = 0;
		// But do show partial hours, days, weeks, years....
		else $precision = 1;
	}
	if( $precision < 0 ) $precision = 0;
	elseif( $precision > 5 ) $precision = 5;
	// }}}

#  	logger( __FUNCTION__ . "():  start=$start, end=$end, precision=$precision, age=$age" );
# 	logger( __FUNCTION__ . "():  " . number_format( round( ($age/60/60),3 ), 3 ) . " hrs" );

	if( $age < 0 )	// Future
	{
		$age = $age * -1;		// Convert to "seconds from now"



		if( $age > (2*365*24*60*60) )		return number_format( round( ($age/365/24/60/60),$precision ), $precision ) . " yrs";
		if( $age > (28*24*60*60) )			return number_format( round( ($age/7/24/60/60),$precision ), $precision ) . " wks";
		if( $age > (2*24*60*60) )			return number_format( round( ($age/24/60/60),$precision ), $precision ) . " days";
		if( $age > (2*60*60) )				return number_format( round( ($age/60/60),$precision ), $precision ) . " hrs";
		if( $age > (2*60) )					return number_format( round( ($age/60),$precision ), $precision ) . " mins";
		return number_format( $age ) . " sec";
	}
	else			// Past
	{
		if( $age > (2*365*24*60*60) )		return number_format( round( ($age/365/24/60/60),$precision ), $precision ) . " yrs ago";
		if( $age > (28*24*60*60) )			return number_format( round( ($age/7/24/60/60),$precision ), $precision ) . " wks ago";
		if( $age > (2*24*60*60) )			return number_format( round( ($age/24/60/60),$precision ), $precision ) . " days ago";
		if( $age > (2*60*60) )				return number_format( round( ($age/60/60),$precision ), $precision ) . " hrs ago";
		if( $age > (2*60) )					return number_format( round( ($age/60),$precision ), $precision ) . " mins ago";
		return number_format( $age ) . " sec ago";
	}

} // }}}

function mins2Str( $in, $long=false ) // {{{
{
	// NOTE: only supports "X mins" or "X hrs, Y mins"
	// in	: minutes


# 	logger("mins2Str in: ".$in);

	$in = abs( getInt( $in ) );
	
    if( $in < 1 ) return "Online";
	if( $in == 1 ) return "$in min";
	if( $in < 60 ) return "$in mins";

	$h = floor( $in/60 );
	$m = $in - ( $h * 60 );

	$hs = "s"; $ms = "s";
	if( $h == 1 ) $hs = '';
	if( $m == 1 ) $ms = '';

	if( $h >= 48 )
		return round( $h/24, 1 ) . " days";
	elseif( $h >= 5 )
		return "$h hr$hs";
	else
	{
		if( $long ) return "$h hr$hs, $m min$ms";				#  --> 1 hr, 18 mins
 		return $h . "." . round($m/60*10,0) . " hrs";			#  --> 1.3 hrs
	}

} // }}}

function getFutureDate( $interval, $now=null ) // {{{
{
    # $interval:  number of days into the future
    # returns a date formatted as YYYY-MM-DD $interval days in the future from right $now.

	$now = empty( $now ) ? time() : getDate2( $now );			// NOTE:  Use empty() in case '0' is passed in....

    return date( 'Y-m-d', strtotime( "+$interval days", $now ) );
} // }}}

function getWorkingDays( $dateA, $dateB, $roundUp=false ) // {{{
{
	// roundUp		- include both first and last day between the two timestamps.
	//				- FALSE:   2015-02-10 - 2015-02-12  ===> 2
	//				- TRUE:    2015-02-10 - 2015-02-12  ===> 3

	Global $profile;
	$profile->start();

	// Add 1 day to make dates inclusive.
	$inclusive = ( $roundUp ? 1 : 0 );		// Set to 1 to make inclusive of last day of work

	// Convert to timestamp (if required)
	if( !is_numeric( $dateA ) ) $dateA = strtotime( $dateA );
	if( !is_numeric( $dateB ) ) $dateB = strtotime( $dateB );

	// Use start/end in calculations so we can reference dateA and dateB again at the end....

	$start = $dateA;
	$end = $dateB;

	if( $start > $end ) { $x = $end; $end = $start ; $start = $x; }

	// Calculate total number of days between the two dates.  {{{
	
	//$days = ceil( ( ( $end - $start ) / ( 24 * 60 * 60 ) ) + $inclusive );
	$days = date( 'z', $end ) - date( 'z', $start );
	if( $days < 0 ) $days += 365;
	$days += $inclusive;
# 	logger( "days = $days" );

	$fullWeeks = floor( $days / 7 );
	$remainingDays = fmod( $days, 7 );

	$firstDOW = date( "N", $start );
	$lastDOW = date( "N", $end );

	if( $firstDOW <= $lastDOW )
	{
		if( $firstDOW <= 6  &&  6 <= $lastDOW ) $remainingDays--;
		if( $firstDOW <= 7  &&  7 <= $lastDOW ) $remainingDays--;
	}
	else
	{
		if( $firstDOW == 7 ) 
		{
			$remainingDays--;
			if( $lastDOW == 6 ) $remainingDays--;
		}
		else
		{
			$remainingDays -= 2;
		}
	}
# 	logger( "remainingDays=$remainingDays" );

	$workingDays = $fullWeeks * 5;
	if( $remainingDays > 0 ) $workingDays += $remainingDays;
# 	logger( "workingDays=$workingDays" );
	// }}}

	// Calculate Holiday days {{{

	// If we're only looking for holidays in one year, then reduce data set returned by getHolidayDates()
	$year = ( date( "Y", $start ) == date( "Y", $end ) ? date( "Y", $start ) : null );
	$holidays = getHolidayDates( $year );

	if(		$start  < strtotime( '2011-01-01' )  ||  $start  > strtotime( '2021-12-31' ) 
		||	$end < strtotime( '2011-01-01' )  ||  $end > strtotime( '2021-12-31' )  )
	{
		logger( "*************************************************************************************\n\nWARNING from " . __FUNCTION__ . "()\n\nOne of the dates is out of range of the identified holidays.  start=".date('Y-m-d', $start ).", end=".date('Y-m-d', $end )."\n\n****************************************************************************************" );
	}
	
	// }}}

	// Now, subtract all the holidays between the two dates {{{
	foreach( $holidays AS $holiday )
	{
		$h = strtotime( $holiday );											// Convert to timestamp
		if( $h < $start  ||  $h > $end ) continue;							// Ignore holidays that fall outside our range....
		if( date( "N", $h ) == 6  || date( "N", $h ) == 7 ) continue;		// Ignore holidays that fall on a weekend
		
		// This holiday is within the specified date-range, so subtract it....
		$workingDays--;
	}
# 	logger( "workingDays=$workingDays" );
	// }}}

	if( $dateA > $dateB ) $workingDays = $workingDays * -1;		// Convert to negative value if the specified end date occurs before the start date
	if( $inclusive == 0  && $workingDays < 0 ) $workingDays--;	// Subtract one more day if we're negative - to include the due_date as a working day.
# 	logger( "workingDays=$workingDays" );

# 	logger( __FUNCTION__ . "():  There are $workingDays working days between " . date( "Y-m-d", $dateA ) . " and " . date( "Y-m-d", $dateB ) );
	$profile->stop();
	return $workingDays;

} // }}}
function getWorkingHours( $tsA, $tsB ) // {{{
{
	Global $profile;
	$profile->start();
	// Return how many working hours were accumulated between the two timestamps.
	// Assumes an 8 hour work day, starting at 8:30am and finishing at 5pm, with 30mins for lunch
	// A starting timestamp before 8:30am is added on as extra time.
	// A finishing timestamp after 5pm is added on as extra time.

	$verbose = false;


	// First, calculate how many whole-day working hours betwen the two timestamps
	$days = getWorkingDays( $tsA, $tsB, true ) - 2;  if( $days < 0 ) $days = 0;
	$hours = $days * 8;

	if( $verbose ) logger( "Start Time:  $tsA" );
	if( $verbose ) logger( "End   Time:  $tsB" );
	if( $verbose ) logger( "days=$days, whole_day_hours=$hours" );

	// If start/end times are on the same day....
	if( date( 'Y-m-d', strtotime( $tsA ) ) == date( 'Y-m-d', strtotime( $tsB ) ) )
	{
		$hours = round( ( ( strtotime( $tsB ) - strtotime( $tsA ) ) / 60 / 60 ), 1 );
		if( $verbose ) logger( "DEBUG 1.1: hours=$hours" );


		// If start time is before lunch and end time is after lunch, then subtract 30 minutes for lunch....
		if( date( 'G', strtotime( $tsA ) ) < 12  &&  date( 'G', strtotime( $tsB ) ) > 13 ) $hours -= 0.5;
		if( $verbose ) logger( "DEBUG 1.2: hours=$hours" );
	}
	else
	{
		// Add in the hours worked on the first day
		$x = 0;
		if( date( 'G', strtotime( $tsA ) ) < 17 ) $x = round( ( ( strtotime( '5pm', strtotime( $tsA ) ) - strtotime( $tsA ) ) / 60 / 60 ) , 1 );
		if( $verbose ) logger( "DEBUG 2.1: firstDayHours=$x" );

		// If start time is before lunch, then subtract 30 minutes for lunch....
		if( date( 'G', strtotime( $tsA ) ) < 12 ) $x -= 0.5;
		if( $verbose ) logger( "DEBUG 2.2: firstDayHours=$x" );

		$hours += $x;
		if( $verbose ) logger( "DEBUG 2.3: hours=$hours" );


		// Add in the hours worked on the last day
		$x = 0;
		if( date( 'G', strtotime( $tsB ) ) > 9 ) $x = round( ( ( strtotime( $tsB ) - strtotime( '9am', strtotime( $tsB ) ) ) / 60 / 60 ) , 1 );
		if( $verbose ) logger( "DEBUG 2.4: lastDayHours=$x" );

		// If finish time is after lunch, then subtract 30 minutes for lunch....
		if( date( 'G', strtotime( $tsB ) ) > 12 ) $x -= 0.5;
		if( $verbose ) logger( "DEBUG 2.5: lastDayHours=$x" );

		$hours += $x;
		if( $verbose ) logger( "DEBUG 2.6: hours=$hours" );
	}

# 	logger( __FUNCTION__ . "():  There are $hours working hours betwen $tsA and $tsB...." );
	$profile->stop();
	return $hours;

} // }}}

function getHolidayDates( $year=null, $country='CAN', $prov='BC' ) // {{{
{
	Global $profile;
	$profile->start();

	// This function is fairly "slow", and can sometimes be called thousands of times during a page-load (on large data-sets)
	// This allows holidays to only be calculated once per page-load, and kept in memory for each subsequent call.
	Global $HOLIDAY_DATES;
	if( !empty( $HOLIDAY_DATES[$year][$country][$prov] ) ) { $profile->stop(); return $HOLIDAY_DATES[$year][$country][$prov]; }


	// Return a list of holiday dates (for BC)
	$holidays = array();

	if( $country != 'CAN'  ||  $prov != 'BC' )
	{
		logger( __FUNCTION__ . "( '$year', '$country', '$prov' ):  WARNING!!!!!!!!   Unsupported Country or State/Prov.   Returning empty set." );
		return $holidays;
	}

	// Add all the fixed holidays - New Years, Remembrance, Christmas, Boxing
# 	for( $y=2000;$y<=date('Y')+25;$y++ ) $holidays = array_merge( $holidays, array( "$y-01-01", "$y-11-11", "$y-12-25", "$y-12-26" ) );
	for( $y=2000;$y<=date('Y')+25;$y++ ) { $holidays[] = "$y-01-01"; $holidays[] = "$y-11-11"; $holidays[] = "$y-12-25"; $holidays[] = "$y-12-26"; }


	// The following were derived from http://www.labour.gov.bc.ca/esb/facshts/stats.htm
	// ... and http://www.statutoryholidays.com/bc.php

	// Add Canada-wide holidays {{{
	// Good Friday
# 	$holidays = array_merge( $holidays, array( '2005-03-25', '2006-04-14', '2007-04-06', '2008-03-21', '2009-04-10', '2010-04-02', '2011-04-22', '2012-04-06', '2013-03-29', '2014-04-18', '2015-04-03', '2016-03-25', '2017-04-14', '2018-03-30', '2019-04-19', '2020-04-10', '2021-04-02' ) );
	foreach( array( '2005-03-25', '2006-04-14', '2007-04-06', '2008-03-21', '2009-04-10', '2010-04-02', '2011-04-22', '2012-04-06', '2013-03-29', '2014-04-18', '2015-04-03', '2016-03-25', '2017-04-14', '2018-03-30', '2019-04-19', '2020-04-10', '2021-04-02' ) AS $d ) $holidays[] = $d;

	// Easter Monday
# 	$holidays = array_merge( $holidays, array( '2005-03-28', '2006-04-17', '2007-04-09', '2008-03-24', '2009-04-13', '2010-04-05', '2011-04-25', '2012-04-09', '2013-04-01', '2014-04-21', '2015-04-06', '2016-03-28', '2017-04-17', '2018-04-02', '2019-04-22', '2020-04-13', '2021-04-05' ) );
	foreach( array( '2005-03-28', '2006-04-17', '2007-04-09', '2008-03-24', '2009-04-13', '2010-04-05', '2011-04-25', '2012-04-09', '2013-04-01', '2014-04-21', '2015-04-06', '2016-03-28', '2017-04-17', '2018-04-02', '2019-04-22', '2020-04-13', '2021-04-05' ) AS $d ) $holidays[] = $d;

	// Victoria Day - Monday before May 25
	for( $y=2000;$y<=date('Y')+25;$y++ ) $holidays[] = date( 'Y-m-d', strtotime( 'last monday', strtotime( "$y-05-25" ) ) );


	// Canada Day - July 1 or July 2 (if July 1 is a Sunday)
	for( $y=2000;$y<=date('Y')+25;$y++ ) { $d = "$y-07-01"; if( date( 'D', strtotime( $d ) ) == 'Sun' ) $d = "$y-07-02"; $holidays[] = $d; }

	// Labour Day - First Monday of Sep
	for( $y=2000;$y<=date('Y')+25;$y++ ) $holidays[] = getRelativeDate( $y, 'september', 'first monday' );

	// }}}


	// Add BC-specific holidays {{{

	// BC Family Day - 2nd Monday of Feb
	for( $y=2000;$y<=date('Y')+25;$y++ ) $holidays[] = getRelativeDate( $y, 'february', 'second monday' );

	// BC Day - First Monday of Aug
	for( $y=2000;$y<=date('Y')+25;$y++ ) $holidays[] = getRelativeDate( $y, 'august', 'first monday' );

	// }}}


	// If user only wants holidays for a given year, then strip out everything we don't need {{{
	if( !is_null( $year ) )
	{
		$tmp = array();
		foreach( $holidays AS $day ) if( substr( $day, 0, 4 ) == $year ) $tmp[] = $day;
		$holidays = $tmp;
	} // }}}

	// Cache for future iterations
	$HOLIDAY_DATES[$year][$country][$prov] = $holidays;

	$profile->stop();
	return $holidays;

} // }}}
function getRelativeDate( $year, $month, $offset ) // {{{
{
	// This is used by getHolidayDates() above to calculate things like...
	// Thanksgiving is the 2nd Monday in October, so... to get Thanksgiving for a given year...
	// $date = getRelativeDate( '2025', 'october', 'second monday' );

	return date( 'Y-m-d', strtotime( "$month $year $offset", strtotime( "jan 1 $year" ) ) );
} // }}}

function isWorkingDay( $date, $country='CAN', $prov='BC' ) // {{{
{
	// Return TRUE if the given date falls on a non-Stat-holiday week-day

	$date = date( "Y-m-d", strtotime( $date ) );

	if( date( "D", strtotime( $date ) ) == "Sat" ) return false;
	if( date( "D", strtotime( $date ) ) == "Sun" ) return false;

	$holidays = getHolidayDates( substr( $date, 0, 4 ), $country, $prov );
	if( in_array( $date, $holidays ) ) return false;

	return true;

} // }}}

function getAdjustedETAStr( $in ) // {{{
{
# 	logger( __FUNCTION__ . "( $in ):  datestamp=" . date( "Y-m-d H:i:s", $in ) . " returns " . getAgeStr( $in ) );
	//if( abs( time() - $in ) < 45 ) return "Any moment";
	if( (time()-$in) > 0 ) return "Any moment";
	return getAgeStr( $in );
} // }}}

function getISOWeeksInYear( $year ) // {{{
{
	// Some years have 52 weeks, others have 53.  This functions figures that out and returns 52 or 53
	// Source:  http://stackoverflow.com/questions/3319386/php-get-last-week-number-in-year

	$date = new DateTime;
	$date->setISODate( $year, 53 );
	return ( $date->format( 'W' ) === '53' ? 53 : 52 );
} // }}}

function getWeekStartStop( $year, $week ) // {{{
{
	// Return the first (Sunday) and last (Saturday) date of the given week
	// NOTE:  If this is returning "bad data", it may be because you're getting year and week
	// from PHP's date("Y-W") function for a given date.   However...  2013-12-30 (a Monday) will return
	// W=1, but Y=2013.   Which is as-expected, but... gives the totally wrong dates for below.
	// Instead,  2013-12-30 should input Y=2014 and W=1 for this function....  
	// See my 'hack' in inc/invoices.php for a work-around....   :-/

	$info = array( 'year' => $year, 'week' => $week );

	$o = new DateTime();
	$o->setISODate( $year, $week );
	if( $o->format('D') == 'Mon' ) $o->modify( '-1 days' );		// If setISODate() set to a Monday, then bump back to Sunday....
	$info['start'] = $o->format( 'Y-m-d' );
	$o->modify( '+6 days' );
	$info['end'] = $o->format( 'Y-m-d' );

# 	$week--;
# 	$info['start'] = date( 'Y-m-d', strtotime( 'last sunday',   strtotime( "jan 3, $year + $week week" ) ) );
# 	$info['end']   = date( 'Y-m-d', strtotime( 'next saturday', strtotime( "jan 3, $year + $week week" ) ) );

# 	logger( $info );
	return $info;

} // }}}

function getLastFridayOfMonth( $year, $month, $excludeHolidays=true ) // {{{
{
	// Returns the last Friday of the given month in the format "YYYY-MM-DD"
	// if excludeHolidays==TRUE and last Friday is a holiday, then return the Thursday (or Wed or...)

	// Get "next month"
	$month++; if( $month > 12 ) { $month = 1; $year++; }
	
	// Back off to last day of previous month...
	$date = date( "Y-m-d", strtotime( "yesterday", strtotime( "$year-$month-01" ) ) );

	// While not a Friday...
	while( date( "D", strtotime( $date ) ) != "Fri" ) $date = date( "Y-m-d", strtotime( "yesterday", strtotime( $date ) ) );

	// While a holiday...
	if( $excludeHolidays ) while( !isWorkingDay( $date ) ) $date = date( "Y-m-d", strtotime( "yesterday", strtotime( $date ) ) );

	return $date;
} // }}}

function getMonths( $m=null ) // {{{
{
	// Return an array of Months
	// Allow user to specify a month number, short name, or description, and return details about just that month

	$months = array(
			 0      => array( 'month' =>  0, 'short' => 'NA',  'description' => 'No Selection' ),
			 1      => array( 'month' =>  1, 'short' => 'Jan', 'description' => 'January' ),
			 2      => array( 'month' =>  2, 'short' => 'Feb', 'description' => 'February' ),
			 3      => array( 'month' =>  3, 'short' => 'Mar', 'description' => 'March' ),
			 4      => array( 'month' =>  4, 'short' => 'Apr', 'description' => 'April' ),
			 5      => array( 'month' =>  5, 'short' => 'May', 'description' => 'May' ),
			 6      => array( 'month' =>  6, 'short' => 'Jun', 'description' => 'June' ),
			 7      => array( 'month' =>  7, 'short' => 'Jul', 'description' => 'July' ),
			 8      => array( 'month' =>  8, 'short' => 'Aug', 'description' => 'August' ),
			 9      => array( 'month' =>  9, 'short' => 'Sep', 'description' => 'September' ),
			10      => array( 'month' => 10, 'short' => 'Oct', 'description' => 'October' ),
			11      => array( 'month' => 11, 'short' => 'Nov', 'description' => 'November' ),
			12      => array( 'month' => 12, 'short' => 'Dec', 'description' => 'December' )
		);

	if( is_null( $m ) ) return $months;
	if( array_key_exists( $m, $months ) ) return $months[$m];
	if( ( $x = SubKeySearch( $months, 'short', ( strtoupper( $m ) == 'NA' ? 'NA' : ucwords( $m ) ) ) ) !== FALSE ) return $x;
	if( ( $x = SubKeySearch( $months, 'description', ucwords( $m ) ) ) !== FALSE ) return $x;
	return $months[0];      // No match found

} // }}}
function getMonthFromNumber( $num, $short=true ) // {{{
{
	$m = getMonths( (int)$num );
	if( $short ) return $m['short'];
	return $m['description'];
} // }}}
function getNumberFromMonth( $str="" ) // {{{
{
	$m = getMonths( $str );
	return $m['month'];
} // }}}

function days_diff( $d1, $d2 ) // {{{
{
	// Return the number of days between two dates.
	$d1 = new DateTime( '@' . getDate2( $d1 ) );
	$d2 = new DateTime( '@' . getDate2( $d2 ) );

	$x1 = daysX( $d1 );
	$x2 = daysX( $d2 );
	if( $x1 && $x2 ) return ( $x2 - $x1 );
	return 0;
} // }}}

function daysX( $x ) // {{{
{
	if( get_class( $x ) != 'DateTime' ) return false;

	$y = $x->format( 'Y' ) - 1;
	$days = $y * 365;

	$z = (int)( $y / 4 );
	$days += $z;

	$z = (int)( $y / 100 );
	$days -= $z;
	
	$z = (int)( $y / 400 );
	$days += $z;
	
	$days += $x->format( 'z' );

	return $days;
} // }}}

// }}}

function objectToArray( $d ) // {{{
{
	// Convert an Object (XML, etc.) to an Assoc Array

	if( is_object( $d ) )
	{
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars( $d );
	}

	if( is_array( $d ) )
	{
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map( __FUNCTION__, $d );
	}
	else
	{
		// Return array
		return $d;
	}
} // }}}

// Filters/Validators for common inputs {{{
function getPrintable( $in ) // {{{
{
	// Include tabs, nl, cr, brackets, & most symbols
	return preg_replace( '/[^a-zA-Z0-9_:;#~@!\?$\^&%*<>=+{}\[\]()\.\ \\\\\-\'\"\/,\r\n\t]/si' , '' , $in );
} // }}}
function getAlNum( $in ) // {{{
{
	return preg_replace( '/[^a-zA-Z0-9_]/si', '', $in );
} // }}}
function getAlNumUC( $in ) // {{{
{
	return strtoupper( getAlNum( $in ) );
} // }}}
function getAlNumLC( $in ) // {{{
{
	return strtolower( getAlNum( $in ) );
} // }}}
# function getNum( $in ) // {{{
# {
# 	// Allow signed float
# 	return preg_replace( '/[^0-9\-\.]/si' , '' , $in );
# } // }}}
function getNumeric( $in=null, $min=null, $max=null, $default=null ) // {{{
{
	// Returns a signed, numeric value.
	// If min/max is set and default is null, will return min/max if out-of-bounds
	// If not numeric or min/max is set and default is not null, will return default

# 	logger( __FUNCTION__. "(): in=$in" );
	if( is_null( $in ) ) return $default;
	if( strlen( $in ) == 0 ) return $default;

	// NOTE:  PHP (auto) and floatval() ignore commas (and anything following)
	//        So... remove them first.   This, unfortunately, breaks European-formatted numbers.  FIXME
	//        floatval() removes any non-numeric characters (other than the first decimal)
	$in = floatval( str_replace( ",", "", $in ) );
# 	logger( __FUNCTION__. "(): in=$in" );

	if( !is_numeric( $in ) ) return $default;

	if( !is_null( $min )  &&  $in < $min ) $in = ( is_null( $default ) ? $min : $default );
	if( !is_null( $max )  &&  $in > $max ) $in = ( is_null( $default ) ? $max : $default );

# 	logger( __FUNCTION__. "(): in=$in" );

	return $in;
}
function getNumber( $in, $min=null, $max=null, $default=null ) { return getNumeric( $in, $min, $max, $default ); }
function getNum(    $in, $min=null, $max=null, $default=null ) { return getNumeric( $in, $min, $max, $default ); }
// }}}

function getInt( $in ) // {{{
{
	// NOTE:   Don't simply cast to (int) because any value > 2,147,483,647 is set to 2,147,483,647
	// Allow signed integer

	// "Remember" if this was a negative number or not....
	$neg = substr( $in, 0, 1 ) == '-' ? true : false;

	// Strip off anything after the first decimal...
	if( strpos( $in, "." ) !== FALSE ) 
	{
		$x = explode( ".", $in );
		$in = $x[0];
	}

	// Strip out all non numeric characters
	$in = preg_replace( '/[^0-9]/si' , '' , $in );

	// re-set to negative if needed
	if( $neg ) $in = $in * -1;

	return $in;	
} // }}}
function getDate2( $in, $format="U" ) // {{{
{
	// Return a valid Unix Timestamp (or '$format'ted date/time string) based on the inbound date value
	// format is anything valid for date();  i.e. 'Y-m-d'
	if( empty( $in ) ) return false;

	// If this is already a Unix timestamp....
	if( (string)(int)$in == (string)$in ) return $in;

	// Else try to convert the input into a valid timestamp, or return FALSE on failure
	if( ( $out = strtotime( $in ) ) === FALSE ) return false;
	return date( $format, $out );
} 
function getTimestamp( $in ) { return getDate2( $in ); }		// Alias to getDate2()
// }}}
function getBoolean( $in=null, $default=false ) // {{{
{
	// Analyze $in and return Boolean value.  
	// Return $default if no pattern match

	if( is_bool( $in ) ) return $in;								// if it's already a boolean value
	if( is_null( $in ) || strlen( $in ) == 0 ) return $default;		// if null or empty string, return default

	$in = strtoupper( $in );
	$c = substr( $in, 0, 1 );

# 	logger( __FUNCTION__ . "(): in=$in" );

	if( $in == "ON"  ) return true;
	if( $c  == 'T'   ) return true;
	if( $c  == 'Y'   ) return true;
	if( $in  == 'CHECKED'   ) return true;

	if( $in == "OFF" ) return false;
	if( $c  == 'F'   ) return false;
	if( $c  == 'N'   ) return false;

	//if( $in == "" ) return false;						// empty string == false		(now handled above)
	if( strlen( $in > 0 ) ) return true;				// non-empty string == true

	return $default;									// null ==> default
} // }}}
function getBool( $in, $default=false ) // {{{
{
	return getBoolean( $in, $default );
} // }}}
function getMAC( $in ) // {{{
{
	// Returns a valid MAC Address in format AA:BB:CC:DD:EE:FF or FALSE

	$in = getAlNumUC( $in );		// Convert to upper-case, remove all non-alphanumerics
	if( strlen( $in ) != 12 ) return false;

	return substr($in,0,2) .":". substr($in,2,2) .":". substr($in,4,2) .":". substr($in,6,2) .":". substr($in,8,2) .":". substr($in,10,2);

} // }}}
function getIP( $in ) // {{{
{
	// Returns a valid IP Address in format aaa.bbb.ccc.ddd or FALSE

	if( ip2long( $in ) === false ) return false;
	return long2ip( ip2long( $in ) );

} // }}}
function getEmail( $in, $log=false ) // {{{
{
	if( validEmail( $in, $log ) ) return $in;
	return null;
} // }}}
function getBaseEmailAddr( $in ) // {{{
{
	// Turns (and array of) "Kevin Traas <kevin@tra.as>" into "kevin@tra.as".
	// Returns nothing if email address is invalid.

	// If input is an array, then parse each element of the array (recursively) {{{
	if( is_array( $in ) )
	{
		$out = array();
		foreach( $in AS $x )
		{
			$y = getEmail( getBaseEmailAddr( $x ) );
			if( !empty( $y ) ) $out[] = $y;
		}
		return $out;
	} // }}}

	if( strpos( $in, "<" ) === FALSE ) return $in;

	$in = explode( "<", $in );
	$in = explode( ">", $in[1] );
	return $in[0];

} // }}}
function getPhone( $in, $style=1 ) // {{{
{
	// This only handles 10-digit numbers.  Returns original input (stripped of all non-numerics) otherwise...
	// style		: 1		--> (604) 858-5678	(default, unless cleansed input is not 10 chars)
	//				: 2		--> 604-858-5678
	//				: 3		--> 6048585678

	$val = preg_replace( '/[^0-9]/si' , '' , $in );
	if( strlen( $val ) == 11   &&  substr( $val, 0, 1 ) == "1" ) $val = substr( $val, 1, 10 );		// If starts with 1 (LD prefix), then strip it.

	if( strlen( $val ) != 10 ) return $val;

	switch( $style )
	{
		case 3:		return $val;
		case 2:		return substr( $val, 0, 3 ) . "-" . substr( $val, 3, 3 ) . "-" . substr( $val, 6, 4 );
		case 1:
		default:	return "(" . substr( $val, 0, 3 ) . ") " . substr( $val, 3, 3 ) . "-" . substr( $val, 6, 4 );
	}

	// should never get here
	return $val;

} // }}}

function getGTIN( $in, $asString=true, $type=12 ) // {{{
{
	/* Returns FALSE if $in is not a valid GTIN
		 Supported formats: 
			- GTIN-12 (UPC-A)
			- GTIN-14 (EAN-14, UCC-14, SCC-14)
		If $asString == true, then returns in format x-xxxx-xxxx-x (UPC-A) or x-xx-xxxxx-xxxxx-x (EAN-14)
		else returns as xxxxxxxxxxxx

		NOTES:
			- GTIN-12 can be converted to GTIN-14 by prefixing with 2 zeros
			- First digit of GTIN-12 indicates 'pack-type'
				- 0		= EACH
				- 1		= CASE
				- 2-8	= "other"
				- 9		= quantity of pack varies between containers
				- For more info, see http://www.swinglabels.com/b-gtin14.aspx

			- A Case UPC (GTIN-14) is *usually* the Item UPC (GTIN-12, UPC-A) with a 10 prefix (and new Check Digit suffix)
				- i.e. chars 3-13 of GTIN-14 === chars 1-11 of GTIN-12/UPC-A
	*/

	// Initial validation {{{
	if( $type != 14 ) $type = 12;		// Only GTIN-12 and GTIN-14 are supported right now... Default to GTIN-12

	$in = preg_replace( '/[^0-9]/si' , '' , $in );

	if( strlen( $in ) == 12  &&  $type == 14 ) $in = "00$in";		// Convert GTIN-12 to GTIN-14 (if input was -12 and output is -14)

	if( strlen( $in ) != $type ) return false;
	// }}}

	// Check Digit Validation  {{{
	$even = $odd = 0;
	for( $i=0; $i<($type-1); $i++ ) ( $i % 2 ) ? $even += $in[$i] : $odd += $in[$i];

	$x = $even + ( $odd * 3 );
	$y = $x % 10;
 	$z = $y ? 10 - $y : $y;

	if( $z != $in[($type-1)] ) { logger( "Invalid GTIN-$type ($in): even=$even, odd=$odd, y=$y, z=$z" ); return false; }
	// }}}

	// Return formatted GTIN {{{
	if( !$asString ) return $in;

	switch( $type )
	{
		default:	$out = $in; break;		// Unsupported format.  Leave as a full numeric strin

		case 12:
			$out = $in[0] . "-" . substr( $in, 1, 5 ) . "-" . substr( $in, 6, 5 ) . "-" . substr( $in, 11, 1 );
			break;

		case 14:
			$out = $in[0] . "-" . substr( $in, 1, 2 ) . "-" .substr( $in, 3, 5 ) . "-" . substr( $in, 8, 5 ) . "-" . substr( $in, 13, 1 );
			break;
	}

# 	logger( __FUNCTION__ . "( $in ): $out" );
	return $out;
	// }}}

} // }}}
// }}}

function validEmail( $email, $log=false ) // {{{
{
	/*
		Returns true if the provided email address is in a valid format and the domain exists.
		Courtesy of http://www.linuxjournal.com/article/9585?page=0,3
	*/

	$atIndex = strrpos( $email, "@" );

	if( is_bool( $atIndex )  &&  !$atIndex ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - no @ found" ); return false; }

	$domain = substr( $email, $atIndex + 1 );
	$domainLen = strlen( $domain );

	$local = substr( $email, 0, $atIndex );
	$localLen = strlen( $local );

	// if local part length exceeded
	if( $localLen < 1  ||  $localLen > 64 ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - name.length <1 or >64" ); return false; }

	// if domain part length exceeded
	if( $domainLen < 1  ||  $domainLen > 255 ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - domain.length <1 or >255" ); return false; }

	// if local part starts or ends with '.'
	if( $local[0] == '.'  ||  $local[$localLen-1] == '.' ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - name starts or ends with ." ); return false; }

	// if local part has two consecutive dots
	if( preg_match( '/\\.\\./', $local ) ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - name contains two consecutive dots" ); return false; }

	// if any character not valid in domain part
	if( !preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain ) ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - domain contains invalid character" ); return false; }

	// if domain part has two consecutive dots
	if( preg_match( '/\\.\\./', $domain ) ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - domain contains two consecutive dots" ); return false; }

	// if any character not valid in local part unless local part is quoted
	if( !preg_match( '/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace( "\\\\", "", $local ) ) )
	{
		if( !preg_match( '/^"(\\\\"|[^"])+"$/', str_replace( "\\\\", "", $local ) ) ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - name contains invalid character" ); return false; }
	}

	// if domain not found in DNS
	if( !checkdnsrr( $domain, "MX" )  &&  !checkdnsrr( $domain, "A" ) ) { if( $log ) logger( __FUNCTION__ . "($email):  Failed Test - domain doesn't resove via DNS" ); return false; }

	// if email account exists on remote server
	// TODO

	// No test failed, so... should be good!
	//if( $log ) logger( __FUNCTION__ . "($email):  Appears valid!" );
	return true;
} // }}}

function html2text( $html ) // {{{
{
	logger( __FUNCTION__ . "():   WARNING!!!!   THIS IS BROKEN!    e.g.  'onsite' is returned as 'o ite'           ********************* WARNING *****************" );
	$search = array (
			"'<script[^>]*?>.*?</script>'si",		// Strip out javascript 
			"'<[/!]*?[^<>]*?>'si",					// Strip out HTML tags 
			"'([rn])[s]+'",							// Strip out white space 
			"'&(quot|#34);'i",						// Replace HTML entities 
			"'&(amp|#38);'i", 
			"'&(lt|#60);'i", 
			"'&(gt|#62);'i", 
			"'&(nbsp|#160);'i", 
			"'&(iexcl|#161);'i", 
			"'&(cent|#162);'i", 
			"'&(pound|#163);'i", 
			"'&(copy|#169);'i", 
			"'&#(d+);'e"							// evaluate as php 
		);

	$replace = array (
			"", 
			"", 
			"\1", 
			"\"", 
			"&", 
			"<", 
			">", 
			" ", 
			chr(161), 
			chr(162), 
			chr(163), 
			chr(169), 
			"chr(\1)"
		); 

	return preg_replace( $search, $replace, $html );

} // }}}

function isHTML( $input ) // {{{
{
	// Check for a string containing '/>' with zero or more letters between the two symbols.
	return preg_match( "/\/[a-z]*>/i", $input ) != 0;
} // }}}

// Create GUID {{{
# USE:
#echo "Guid 1 = ".Guid()."\n";
#echo "Guid 2 = ". new Guid()."\n";
function getGuid( $opt=null )
{
	$guid = new Guid( $opt );
	return $guid->getGuid();
}
function Guid( $opt=null ) { return new Guid( $opt ); }
Class Guid
{
	private $guid = null;                   // NOTE:  Some uses (i.e. MS) require this value wrapped in curly braces {...}
	private $alnumOnly = false;             // Default GUID includes braces & dashes.  This returns alphanumeric only.
	public function __construct( $alnum=false )
	{
		$this->alnumOnly = $alnum;

		if( function_exists( 'com_create_guid' ) )
			$this->guid = com_create_guid();
		else
			$this->guid = sprintf( '%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
								mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) );
		return $this->guid;
	}
	public function __toString()
	{
		if( $this->alnumOnly ) return preg_replace( '/[^a-zA-Z0-9_]/si' , '' , $this->guid );
		else return $this->guid;
	}
	public function getGuid() { return $this->__toString(); }
}
// }}}

function getBaseURL() // {{{
{
	if( empty( $_SERVER ) ) return false;
# 	logger( $_SERVER );

	$host		= $_SERVER['SERVER_NAME'];
	$port		= $_SERVER['SERVER_PORT'];
	$protocol	= ( $_SERVER['HTTPS'] == 'on' ? 'https' : 'http' );

	$root		= dirname( $_SERVER['REQUEST_URI'] );

	$url = "$protocol://$host";

	if( ( $protocol == 'https' && $port != 443 )  ||  ( $protocol == 'http' && $port != 80 ) ) $url .= ":$port";

	$url .= $root;

# 	logger( $url );
	return $url;

} // }}}

function getClientIP() // {{{ chriswiegman.com
{
        //Just get the headers if we can or else use the SERVER global
        if ( function_exists( 'apache_request_headers' ) ) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if ( array_key_exists( 'X-Forwarded-For', @$headers ) && filter_var( @$headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', @$headers ) && filter_var( @$headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
        ) {
            $the_ip = @$headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var( @$_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
        }
        return $the_ip;
} // }}}

function getDateTimeInTimezone($tz, $timestamp=null) // {{{
{
	if(empty($timestamp)) $timestamp = time();

	$dt = new DateTime("now",  new DateTimeZone($tz));
	$dt->setTimestamp($timestamp);
	return $dt;

} // }}}

function getDateInTimezone($tz, $timestamp=null) // {{{
{
	return getDateTimeInTimezone($tz, $timestamp)->format('Y-m-d');

} // }}}

function getTimeInTimezone($tz, $timestamp=null) // {{{
{
	return getDateTimeInTimezone($tz, $timestamp)->format('H:i:s');
} // }}}





?>
