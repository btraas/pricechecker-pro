<?php include_once('inc/header.php'); ?>

<script src='js/quagga.min.js'></script>
<script>

function openCamera() {

    if (!navigator.mediaDevices
        || typeof navigator.mediaDevices.getUserMedia !== 'function') {
      // safely access `navigator.mediaDevices.getUserMedia`
      alert('Real-time scanning not supported in your browser!');
      return;
    }

    $('#yourElement, #yourElement div').css('z-index', 500);

    Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      constraints:{  },
      facingMode: "environment",
      debug: {
        drawBoundingBox: true,
        showFrequency: false,
        drawScanline: true,
        showPattern: true
      },
      target: document.querySelector('#yourElement')    // Or '#yourElement' (optional)
    },
    decoder : {
      readers : ["upc_reader"]
    }
  }, function(err) {
      if (err) {
          console.log(err);
          return
      }
      console.log("Initialization finished. Ready to start");
      Quagga.start();
  });


    Quagga.onDetected(function(result)
    {
		Quagga.stop();
	
        var code = result.codeResult.code;
		location.href = 'lookup/upc/'+code;

        //alert(code);
        //$('#result').html(code);

    });

}

$(document).ready(function(){ openCamera(); });

</script>
     

<!--<div id='aboveElement'  style='position: fixed; left: 0; z-index: 50; width: 100%; height: 100%; opacity: 0.5;'
                        class='mdl-color--grey-100'>-->
    <div id='yourElement' style='position: fixed; left: 0; top: 0; z-index: -500; width: 100%; height: 100%;'>
        <div style='top: 50%; left: 30%; width: 40%; height: 2px; background: red; position: fixed;  z-index: -500'></div>
    </div>
<!--</div>-->
     


<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'></div>
<?php include_once('inc/footer.php'); ?>
