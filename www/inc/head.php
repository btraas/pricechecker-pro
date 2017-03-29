  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Check prices - Everywhere.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo PAGE_NAME ?></title>
	<base href="<?php echo BASE_URL ?>" />

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?php echo APP_NAME; ?>">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">




	<meta name="google-signin-client_id" content="<?php echo GOOGLE_CLIENT_ID; ?>.apps.googleusercontent.com">


    <link rel="shortcut icon" href="images/favicon.png">

	<!-- Pace is a simple loading bar for all pages -->
	<link rel="stylesheet" href="css/pace.css">
	<script src='js/pace.min.js'></script>


	<!-- Google and MDL CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?php global $theme; echo $theme->stylesheet(); ?>">
	<link rel="stylesheet" href="css/mtl-styles.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }

	
	.pace .pace-progress {
		background: <?php echo $theme->color_main->shade(500)->hex(); ?>
	}

    </style>



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src='js/functions.js'></script>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<!-- Moment JS for datetime & timezone info -->
	<script type='text/javascript' src='https://momentjs.com/downloads/moment-with-locales.js'></script>
	<script type='text/javascript' src='https://momentjs.com/downloads/moment-timezone-with-data.js'></script>

	<script type='text/javascript' src='js/cookie.js'></script>
	<script type='text/javascript' src='js/mdl.js'></script>
  

	<script src="https://www.gstatic.com/firebasejs/3.7.3/firebase.js"></script>
	<script>
	  // Initialize Firebase
	  var config = {
	    apiKey: "AIzaSyCIS7Bo1V5dzpKVqJwxPi8gDDLbT1mmDIA",
	    authDomain: "price-checker-pro.firebaseapp.com",
	    databaseURL: "https://price-checker-pro.firebaseio.com",
	    storageBucket: "price-checker-pro.appspot.com",
	    messagingSenderId: "579779341532"
	  };
	  firebase.initializeApp(config);
	</script>

	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
  	(adsbygoogle = window.adsbygoogle || []).push({
    	google_ad_client: "ca-pub-2829849745618303",
    	enable_page_level_ads: true
  	});
	</script>
	  
  <script>
    function sortResults() {
    var table, rows, switching, i, x, y, shouldSwitch,tmp;
    var option = document.getElementById('resultSorting').value;
    table = document.getElementById("resultTable");
    console.log(option);
    switching = true;
    /*Make a loop that will continue until
     no switching has been done:*/
    while (switching) {

        //start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("tr");
        /*Loop through all table rows (except the
         first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
             one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("td")[2];
            y = rows[i + 1].getElementsByTagName("td")[2];
            //check if the two rows should switch place:
            if(parseInt(option)) {
                tmp = x;
                x = y;
                y= tmp;
            }

            if (parseFloat(x.innerHTML.toString().replace(/[^0-9\.]/g, '')) > parseFloat(y.innerHTML.toString().replace(/[^0-9\.]/g, ''))) {
                //if so, mark as a switch and break the loop:
                shouldSwitch= true;
                break;
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
             and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}
</script>

  </head>
