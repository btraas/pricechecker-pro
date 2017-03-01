<?php

//logger('yo');

require_once('lib/auth.php');
authenticate();

define("PAGE_NAME", "Authentication");

?>

<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">

<?php include('inc/head.php') ?>

<script>


	$(document).ready(function() 
	{
		animateLogo($('#logo'), 10*1000);
	});



</script>


  <body>
    <div class="mdl-layout mdl-js-layout mdl-layout__container">
      <main class="mdl-color--grey-100">



	<style>
		/*
		.mdl-cell > .center > div {
			display: table;
			margin: 0 auto;

		}
		*/
		.info-box {
			width: 400px;	
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
		.title {
			position: absolute;
			height: 400px;
			width: 100%;
			top: 25%;
			padding-bottom: 0px;

			color: white;
			text-align: center;
		}
		.title > h1 {
			margin: 0px;
			font-size: 500%;
		}
	</style>


<div class='full mdl-color--<?php echo $theme->color_main->base; ?>-300 mdl-grid--no-spacing'>

	<div class='title'>


		<img id='logo' src='images/android-desktop.png' />

		<h1>Logging in...</h1>
		
		

	</div>
</div>





<?php include('inc/footer.php'); ?>
