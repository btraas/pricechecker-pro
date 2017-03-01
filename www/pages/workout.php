<?php define("PAGE_NAME", "Workout"); ?>

<?php 

require_once('lib/program.php');

//print_r($_REQUEST); 


$date = isset($_REQUEST['date']) ?
	date('Y-m-d', strtotime($_REQUEST['date'])) :
	date('Y-m-d');

$userProgram = $user->getProgram();
//$program = empty($user->current_program) ? null : new PublicProgram($user->current_program);

$week = getNumeric(@$_REQUEST['week']);
$session = getNumeric(@$_REQUEST['session']);

//$exercises = $userProgram->getExercises($week, $session);


$mode = getAlNumUC(@$_REQUEST['meta']);

switch($mode) // {{{
{

	// Choose day
    case ''         : include('inc/workout/index.php');     exit();

	// Choose session (unless already set for this day, then skip)
	case 'NEW'		: include('inc/workout/new.php');		exit();

	// Choose Exercise
	case 'SESSION'  : include('inc/workout/session.php');   exit();


	case 'SAVE'     : save(); exit();
	default			: _404();

} // }}}

function save() // {{{
{
	Global $user;

	$date = date('Y-m-d', (strtotime($_REQUEST['date'])));
	$exercises = $_REQUEST['exercises'];
	$week = getNumeric($_REQUEST['week']);
	$session = getNumeric($_REQUEST['session']);

	$user->log($date, $week, $session, $exercises);

} // }}}



?>


