<?php

define("PAGE_NAME", "Price Checker Pro");

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
function onSignIn(googleUser) {
	//location.href = 'dashboard';




	var id_token = googleUser.getAuthResponse().id_token;


	var url = '/index.php?p=authenticate&meta=googletoken&value='+id_token;
	//alert(url);
	location.href = url;

}


function handleAuthClick(event) {
    gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
    return false;
}



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
			height: 200px;
			width: 100%;
			top: 15%;
			padding-bottom: 0px;

			color: white;
			text-align: center;
		}
		.title > h1 {
			margin: 0px;
			font-size: 500%;
		}
		.g-signin2 > .abcRioButton {
			margin: 0 auto;
		}
		.title > img {
			max-width: 30%;
		}
	</style>


<div class='full mdl-color--<?php echo $theme->color_main->base; ?>-300 mdl-grid--no-spacing'>

	<div class='title'>


		<img src='images/android-desktop.png' />

		<h2>Price Checker Pro</h2>
		<h3>Compare prices easily</h3>
		
		

		<div class='info-box'>
			<div class="g-signin2" data-onsuccess="onSignIn"></div>
		</div>
	</div>
</div>





<?php include('inc/footer.php'); ?>
