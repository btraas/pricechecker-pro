<?php

define("PAGE_NAME", "Progress");

include('inc/header.php');

$q = "SELECT * FROM user_progress_view WHERE user_id = ?"; //$user[user_id]";
$r = runQ($q, array($user->user_id));
//print_r($r);

//$json = json_encode($r);

$all_exercises = array();

foreach($r AS $datapoint) {
	$all_exercises[$datapoint['exercise']][$datapoint['date']] = $datapoint;
}

$json = json_encode($all_exercises);
//print_r($all_exercises);

?>

<div id='chart_div_combined'></div>
<?php foreach($all_exercises AS $id => $ex) { ?>
<div id="chart_div_<?php echo $id; ?>"></div>
<?php } ?>



<script type='text/javascript'>

var units = '<?php echo $user->weight_unit_id; ?>';
var all_exercises = <?php echo json_encode($all_exercises); ?>;

$(document).ready(function() 
{
	google.charts.load('current', {packages: ['corechart', 'line']});
	google.charts.setOnLoadCallback(drawExercises);


	function drawExercises() 
	{

		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');

		// for each exercise
		$.each(all_exercises, function(exercise_name, exercise_day) {

			data.addColumn('number', exercise_name);
			$.each(exercise_day, function(exercise_date, log) {
				data.addRow([new Date(exercise_date), Number(log.weight)]);

			});

		});


		var options = {
                title: "Combined Lifts",
                hAxis: {
                    title: 'Date'
                },
                vAxis: {
                    title: 'Weight (' + units + ')'
                },
                legend: { position: 'bottom' }
            };


		var elem = document.getElementById('chart_div_combined'); //+exercise_name);
      	var chart = new google.visualization.LineChart(elem);
      		chart.draw(data, options);

	}

});

</script>

<?php include('inc/footer.php'); ?>
