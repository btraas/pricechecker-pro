<?php 

	//$user = getUser(getGoogleToken())->data;
	//logger($user);
?>
	  
<script>

var user = <?php echo json_encode(@$user); ?>

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
			<img id='user-image' src="<?php echo @$user->picture; ?>" class="avatar">
            <span id='user-email' style='color: white'><?php echo @$user->email; ?></span>
		</div>
		<div class='mdl-card__actions mdl-card--border'>
			<a id='auth-btn' href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--<?php echo $theme->color_main->base; ?>-50">Sign Out</a>
		</div>

        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--<?php echo $theme->color_main->base; ?>-800">

          <a class="mdl-navigation__link" href="/lookup">
			<i class="mdl-color-text--<?php echo $theme->color_main->base; ?>-400 material-icons" 
				role="presentation">home</i>Lookup</a>
        </nav>
      </div>
	  <script>

		var auth2;

		firebase.auth().onAuthStateChanged(init);


		$(document).ready(function()
		{
			/*
			gapi.load('auth2', function() {

				gapi.auth2.init();

				auth2 = gapi.auth2.getAuthInstance();

			});
			*/

			init(firebase.auth().currentUser);
		});
	
		function init(user) {
			
			$('#auth-btn').text("Sign Out").prop('onclick', null).off('click');

			if(user != null) {
				//login(user);
				record(user);

				$('#auth-btn').text("Sign Out").on('click', signOut);
				$('#user-image').attr('src', user.photoURL);
				$('#user-email').text(user.email);
			} else {
				$('#auth-btn').text("Sign In").on('click', signIn);
				$('#user-image').attr('src', 'images/user.jpg');
				$('#user-email').text("");
			}
			

		}

		function record(user) {
			// TODO save to our db
			
		}



		function signOut() {

			//if(typeof gapi != Object) location.href = '/';

			/*

	//		var auth2 = gapi.auth2.getAuthInstance();
			    auth2.signOut().then(function () {
					location.href = '/';
			});

			*/

			firebase.auth().signOut().then(function() {

  			// Sign-out successful.
			//	init();

			}).catch(function(error) {
			  // An error happened.
			});

		}
	

		function signIn()
		{

			/*
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
			*/
			var provider = new firebase.auth.GoogleAuthProvider();
			firebase.auth().signInWithPopup(provider).then(function(result) {
		  		// This gives you a Google Access Token. You can use it to access the Google API.
		  		var token = result.credential.accessToken;
		  		// The signed-in user info.
		  		var user = result.user;
		  		
				console.log(user);
			//	init(user); 		// init in UI
			

				}).catch(function(error) {
				  // Handle Errors here.
				  var errorCode = error.code;
				  var errorMessage = error.message;
				  // The email of the user's account used.
				  var email = error.email;
				  // The firebase.auth.AuthCredential type that was used.
				  var credential = error.credential;
				  // ...
				});


		}
	



	  </script>

