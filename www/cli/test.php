<?php 



require_once('/var/www/super-simple-lifts/www/defines.php');
require_once('cli/cli-config.php');
require_once('lib/common.php');
require_once('lib/db.php');


$r = runQ("SELECT * FROM program_session", array());

echo "Naming sessions...\n";
foreach($r AS $e)
{

	$p = "SELECT pe.*, p.name_short AS program_name, e.name AS exercise_name FROM program_exercises pe
			INNER JOIN programs p ON p.program_id = pe.program_id
			INNER JOIN exercises e ON e.exercise_id = pe.exercise_id
			WHERE pe.program_id = ?
			AND pe.session = ?
			AND pe.week = ?
			ORDER BY pe.program_exercise_id";

	$r2 = runQ($p, array($e['program_id'], $e['session'], $e['week']));

	//$name = //$r2[0]['program_name'] . " " . 
	$name = $r2[0]['exercise_name'] . " & " . $r2[1]['exercise_name'] . "";

	
	echo "session name for program $e[program_id]: $name\n";


	$q = "UPDATE program_session SET name = ?
			WHERE program_id = ?
			AND session = ?
			AND week = ?";
	echo " $q\n";
	runQ($q, array($name, $e['program_id'], $e['session'], $e['week']));
}

echo "done.\n"

?>
