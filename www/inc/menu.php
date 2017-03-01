<?php 

	//$user = getUser(getGoogleToken())->data;
	//logger($user);
?>
	  
<script>

var user = <?php echo json_encode($user); ?>

$.cookie('timezone', moment.tz.guess(), { expires: 7 });

</script>
	  
	  <style>
		.avatar {
		/*	width: 48px; */
			height: 48px;
			border-radius: 24px;
			margin-right: 5px;
		}

		.demo-navigation.mdl-navigation  .mdl-navigation__link:hover {
			background-color: <?php echo $theme->color_main->shade(300)->hex(); ?>;
			color: #37474F;

		}

	  </style>

	  
	  <div class="	demo-drawer mdl-layout__drawer	mdl-color--<?php echo $theme->color_main->base; ?>-900 
													mdl-color-text--<?php echo $theme->color_main->base; ?>-50">
        <header>

		  <div class='mdl-layout-spacer'></div>

          <div class="mdl-card__title demo-avatar-dropdown">
			<img id='user-image' src="<?php echo $user->picture; ?>" class="avatar">
            <span id='user-email' style='color: white'><?php echo $user->email; ?></span>
		</div>
		<div class='mdl-card__actions mdl-card--border'>
			<a href="#" onClick='signOut()' class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--<?php echo $theme->color_main->base; ?>-50">Sign Out</a>
		</div>

        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--<?php echo $theme->color_main->base; ?>-800">

          <a class="mdl-navigation__link" href="/workout">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">home</i>Workout</a>

          <a class="mdl-navigation__link" href="/progress">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">trending_up</i>Progress</a>

          <a class="mdl-navigation__link" href="/programs">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">fitness_center</i>Programs</a>

          <a class="mdl-navigation__link" href="/spam">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">report</i>Spam</a>

          <a class="mdl-navigation__link" href="/forums">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">forum</i>Forums</a>

          <a class="mdl-navigation__link" href="/updates">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">flag</i>Updates</a>

          <a class="mdl-navigation__link" href="/promos">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">local_offer</i>Promos</a>

          <a class="mdl-navigation__link" href="/purchases">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">shopping_cart</i>Purchases</a>

          <a class="mdl-navigation__link" href="/demo">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">picture_in_picture</i>Demo</a>

          <div class="mdl-layout-spacer"></div>
          <a class="mdl-navigation__link" href=""><i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>
      </div>
	  <script>

		var auth2;


		$(document).ready(function()
		{
			gapi.load('auth2', function() {

				gapi.auth2.init();

				auth2 = gapi.auth2.getAuthInstance();

			});
		});


		function signOut() {

			//if(typeof gapi != Object) location.href = '/';

	//		var auth2 = gapi.auth2.getAuthInstance();
			    auth2.signOut().then(function () {
					location.href = '/';
			});
		}


/*
		function signIn()
		{

			gapi.load('auth2', function() {
 				auth2 = gapi.auth2.init({
    				client_id: '<?php echo GOOGLE_CLIENT_ID; ?>.apps.googleusercontent.com',
    				fetch_basic_profile: true,
    				scope: 'profile'
  				});

  				// Sign the user in, and then retrieve their ID.
  					auth2.signIn().then(function() {
						console.log(auth2.currentUser.get().getId());

						var profile = auth2.currentUser.get().getBasicProfile();
						console.log('ID: ' + profile.getId());
						console.log('Full Name: ' + profile.getName());
						console.log('Given Name: ' + profile.getGivenName());
						console.log('Family Name: ' + profile.getFamilyName());
						console.log('Image URL: ' + profile.getImageUrl());
						console.log('Email: ' + profile.getEmail());

						$('#user-email').text(profile.getEmail());
						$('#user-image').attr('src', profile.getImageUrl());

					});
			});	


		}
	
*/



	  </script>

