
<?php 

if(!empty($user->getLog($date))) header('location:/workout/session');

//echo $date . ":<br>";
//print_r($user->getExercises($date));

?>

<?php include('inc/header.php'); ?>

<style>
.add-button {
	font-size: 200%;
}

.session-name {
	line-height: 50px;
	width: 100%;
	padding-left: 20px;
}

.new-session {
	height: 50px;
	line-height: 50px;
	padding: 0px;
}

</style>

<script>

var date = '<?php echo $date; ?>';

$(document).ready(function()
{
	$('.new-session').click(function()
	{
		var url = 'workout/session?date='+date+'&program=';
			url += user.current_program;
			url += '&week='+$(this).data('week');
			url += '&session='+$(this).data('session');

		window.location = url;
	});
});

</script>

<?php 

$q = "SELECT ps.*, p.name AS program_name
		FROM program_session ps 
		INNER JOIN programs p ON p.program_id = ps.program_id
		WHERE ps.program_id = ?"; //$user[current_program]";

$r = runQ($q, array($user->current_program));
	
//$weeks = $r[0]['weeks'];
$program_name = $r[0]['program_name'];

echo "<h3>$program_name</h3>\n";

echo "<ul class='mdl-list'>\n";
foreach($r AS $w) {

?>

	<li class='mdl-list__item '>
		<span class='mdl-list__item-primary-content mdl-shadow--2dp'>
			<button class='new-session mdl-button mdl-js-button' 
				data-week='<?php echo $w['week'] ?>'
				data-session='<?php echo $w['session']; ?>'
				>
				<i class="material-icons">add</i>
			</button>

		<?php
			// if($weeks > 1) echo "Week $w[week] of $weeks ";
			echo "Day $w[session]: ";
			echo $w['name']; 
		?>
		</span>
	</li>


<?php 
}
?>

<?php include('inc/footer.php'); ?>
