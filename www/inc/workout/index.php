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

//$exercises = $user->getExercises($date);

// }}}

?>

<script>

var date	= '<?php echo $date; ?>';
var week	= '<?php echo $week; ?>';
var session	= '<?php echo $session; ?>';

</script>

<h1>Date picker / New workout here... </h1>

<?php

$btn = new MDL\Button('new-workout-today');
$btn->text = "Today";
$btn->onclick = "window.location = 'workout/new'";
echo $btn->html;
?>

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
