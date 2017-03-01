<?php

define("PAGE_NAME", "Program");

include('inc/header.php');

$q = "SELECT p.*, pe.* FROM program_exercises pe INNER JOIN programs p ON p.program_id = pe.program_id WHERE p.program_id = ?";//$user[current_program]";

$r = runQ($q, $user->current_program);

$program_id = @$r[0]['program_id'];

$programsQ = "SELECT * FROM programs_view WHERE private_user_id IS NULL OR private_user_id = ?";//$user[user_id]";


$programs = runQ($programsQ, array($user->user_id));




?>


<script>

var programs = <?php echo json_encode($programs); ?>;


function drawVisualization() {
  // Create and populate the data table.

  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Name');
  data.addColumn('string', 'Exercises');

  for(i = 0; i < programs.length; i++)
    data.addRow([programs[i].name, programs[i].exercises]);


  var view = new google.visualization.DataView(data);
  //view.setRows(view.getFilteredRows([{column: 1, minValue: new Date(2007, 0, 1)}]));
  var table = new google.visualization.Table($('#programs')[0]);
  table.draw(view, {});

}


$(document).ready(function() 
{
	google.charts.load('current', {packages: ['corechart', 'table'], 'callback': drawVisualization});
	// google.charts.setOnLoadCallback(drawVisualization);
	
});

</script>


<div id='programs'></div>


<?php include('inc/footer.php'); ?>
