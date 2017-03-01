<?php

define("PAGE_NAME", "Workout Log");

include('inc/header.php');

$q = "SELECT p.*, pe.* FROM program_exercises pe INNER JOIN programs p ON p.program_id = pe.program_id WHERE p.program_id = ?";

$r = runQ($q, array($user->current_program));

$program_id = @$r[0]['program_id'];





?>



<?php include('inc/footer.php'); ?>
