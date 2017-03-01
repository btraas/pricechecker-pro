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


<?php  


// $user->addExercises($userProgram->getExercises($week, $session));

$week = @$_REQUEST['week'];
$session = @$_REQUEST['session'];

//$exercises = $user->getExercises($date, $week, $session);

$exercises = $user->getLog($date);

if(empty($exercises)) $exercises = $user->newLog($date, $week, $session);



// }}}

?>

<script>

var date	= '<?php echo $date; ?>';
var week	= '<?php echo $week; ?>';
var session	= '<?php echo $session; ?>';

function save() // {{{
{
	var data = {
		date:		date,
		week:		week,
		session:	session,
		exercises:	[]
		};

	$('.exercise-card').each(function()
	{
		var exercise = {};
		exercise.id = $(this).data('exercise_id');
		exercise.name = $(this).data('name');

		exercise.weight = $(this).find('.exercise-weight').val();
		exercise.weight_unit_id = $(this).find('.exercise-weight').data('unit_id');
		exercise.reps_min = $(this).data('reps_min');
		exercise.reps_max = $(this).data('reps_max');
		exercise.sets_goal = $(this).data('sets_goal');
		exercise.last_amrap = $(this).data('last_amrap');

		exercise.sets = [];
		
		$(this).find('.exercise-set').each(function()
		{
			if(!empty($(this).val())) exercise.sets.push($(this).val());
		});

		data.exercises.push(exercise);

	});

	$.post('workout/save', data, function(html) { $('#tmpHead').html(html) });


} // }}}

</script>

<?php

//echo "<h3>$program->program_name</h3>\n";
echo "<ul class='mdl-list'>\n"; 

//if(empty($exercises)) print_r($myProgram->exercises);

foreach($exercises AS $e) {

// print_r($e);

?> 

	<li class='mdl-list__item exercise-card' 
			data-exercise_id='<?php echo $e->exercise_id?>'
			data-name='<?php echo $e->name?>'

			data-reps_min='<?php echo $e->reps_min?>'
			data-reps_max='<?php echo $e->reps_max?>'
			data-sets_goal='<?php echo $e->sets_goal?>' 
			data-last_amrap='<?php echo $e->last_amrap?>'
			data-optional='<?php echo $e->optional?>'
			>


		<span class='mdl-list__item-primary-content mdl-shadow--2dp' style='padding: 10px'>
			<!--<button class='session-exercise mdl-button mdl-js-button'>
				<i class="material-icons">add</i>
			</button>-->

			<table style='width: 100%;'>
				<tr>
					<td><h5><?php echo "$e->name"; ?></h5></td>
					<td style='width: 200px; text-align: right;'>
						<?php 
						
						$reps = $e->reps_max == $e->reps_min ? $e->reps_max : "$e->reps_min-$e->reps_max";


						$weight = new MDL\WeightInput($e->exercise_id."-weight");
						$weight->addClass('exercise-weight');
						$weight->{'data-unit_id'} = $user->weight_unit_id;
						$weight->value = getNumeric($e->weight);
						$weight->before =  $e->sets_goal." x $reps @  ";
						$weight->after = str_replace($weight->value, "", $e->weight);
						
						echo $weight->html;
						
						
						?>
					</td>
				</tr>
				<tr><td colspan=2>
	
				<?php 

					$input = new MDL\NumberInput($e->exercise_id."-set-x"); 
					$input->placeholder = $e->reps_max;
					//$input->value = $e->reps;
					$input->addClass('exercise-set');
					$input->onclick = "if(this.value == '') { 
											this.value = $input->placeholder; 
											$(this).parent().addClass('is-dirty')
										}
										this.select();";
					$sets = explode(',', $e->reps);

				?>
					<form action='#'>
						<?php for($i=0; $i<$e->sets_goal; $i++) 
						{
							$input->id = $e->exercise_id."-set-".($i+1);
							$input->value =	@$sets[$i];

							echo $input->html;
						}
						?>		

					</form>
				</td></tr>
			</table>
		</span>
	</li>


<?php 
}
?>

</ul>

<style>
/* Change the layout__content class to Flex (instead of inline-block) to allow spacer to work. */
.mdl-layout__content {
    display: -webkit-flex;
	display: flex;
    -webkit-flex-direction: column;
	        flex-direction: column;
}
</style>

            
<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'></div>

<footer class="mdl-mini-footer mdl-color--<?php echo $theme->color_main->base; ?>-800 mdl-color-text--grey-100" 
	style='	position: fixed; bottom: 0px; 
			width: 100%; margin-left: -10px; 
			padding: 10px;'>
		<div class='mdl-layout-spacer'></div>
		<?php 
			$button = new MDL\Button('save-button');
			$button->text='save';
			$button->onclick="save()";
			$button->addClass('mdl-button--raised');
			$button->style="margin-right: 30px";
			echo $button->html;
		?>	
</footer>

<?php include('inc/footer.php'); ?>
