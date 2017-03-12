<?php

if(!defined("PAGE_NAME")) define("PAGE_NAME", "404: Page not found");

?>

<?php include_once('inc/header.php'); ?>


	<style>
		/*
		.mdl-cell > .center > div {
			display: table;
			margin: 0 auto;

		}
		*/
		.info-box {
			max-width: 400px;
			margin: 0 auto;
		}
		.mdl-layout__content > .full {
			height: 100%;
			margin: 0px;
			
		}
		.full {
			position: absolute;
			width: 100%;
			height: 100%;
		}
		.massive-404 {
			position: absolute;
			height: 400px;
			width: 100%;
			top: 25%;
			padding-bottom: 0px;

			color: white;
			text-align: center;
		}
		.massive-404 > h1 {
			margin: 0px;
			font-size: 1000%;
		}
		#maincontent {
			margin: 0px !important;
		}
	</style>


<div class='full mdl-color--<?php echo $theme->color_accent->base; ?>-300 mdl-grid--no-spacing'>

	<div class='massive-404'>
		<h1>404</h1>
		
		
		<div class='info-box'>
			<div class="mdl-shadow--2dp mdl-color--grey-100 mdl-cell mdl-cell--4-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
			      <div class="mdl-card__supporting-text mdl-color-text--grey-600">
					<?php	if(!empty($_404_msg)) echo $_404_msg;
							else echo "The page you are requesting ($page) does not exist.";
					?>
			      </div>
			      <div class="mdl-card__actions mdl-card--border">
			        <a href="/lookup" class="mdl-button mdl-js-button mdl-js-ripple-effect">Take me Home</a>
			      </div>
			</div>
		</div>
	</div>
</div>





<?php include_once('inc/footer.php'); ?>
