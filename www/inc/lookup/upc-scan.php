<?php include_once('inc/header.php'); ?>

<script src='js/quagga.min.js'></script>
<script>

mode = "user";
camIds = [];
currentIdIndex = 0;
currentId = ""

function switchMode() {
    if(mode  == "environment") {
        mode = "user";
    }else if(mode == "user") {
        mode = "environment";
    }
    /*console.log(mode);*/
 // currentIdIndex = (currentIdIndex+1)%camIds.length;
}

function openCamera() {
    switchMode();
    //console.log(mode);

    if (!navigator.mediaDevices
        || typeof navigator.mediaDevices.getUserMedia !== 'function') {
      // safely access `navigator.mediaDevices.getUserMedia`
      alert('Real-time scanning not supported in your browser!');
      return;
    }

    //currentId = camIds[currentIdIndex].toString().trim();

    $('#yourElement, #yourElement div').css('z-index', 500);

    Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      constraints:{ 
      facingMode: mode,
      //deviceId: currentId,
      }
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

$(document).ready(function(){ 

              openCamera();

            });
              
              

</script>


    <!--
    <div class="mdl-textfield mdl-js-textfield has-placeholder"  >
        <input class="mdl-textfield--full-width  mdl-textfield__input" id="lookup-number" placeholder="" type="number" pattern="-?[0-9]*(\.[0-9]+)?" style="" value="" onkeyup="if (event.keyCode == 13) $('#go').click()">

        <label class="mdl-textfield__label" for="lookup-number"></label>
        <span class="mdl-textfield__error">Not a number!</span>

    </div>
    -->
    <!--<div class="mdl-textfield mdl-js-textfield">
        <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="sample2">
        <label class="mdl-textfield__label" for="sample2">Number...</label>
        <span class="mdl-textfield__error">Input is not a number!</span>
    </div>-->



    <?php include('upc-textinput.php'); ?>





<!--<div id='aboveElement'  style='position: fixed; left: 0; z-index: 50; width: 100%; height: 100%; opacity: 0.5;'
                        class='mdl-color--grey-100'>-->
    <div id='yourElement' style='position: fixed; left: 0; top: 0; z-index: -500; width: 100%; height: 100%;'>
        <div style='top: 50%; left: 30%; width: 40%; height: 2px; background: red; position: fixed;  z-index: -500'></div>
    </div>
<!--</div>-->

   <!-- <button style="display: block;
        margin-left: auto;
        margin-right: auto;
        z-index:1000;" class="mdl-button--fab mdl-button mdl-js-button  mdl-button--raised mdl-button--accent mdl-button--raised" onclick="openCamera()"  id="camFlip">
        <i class="material-icons">switch_camera</i>
    </button>-->
     


<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'></div>
        <?php
        $btn = new MDL\FabButton('camFlip');
        $btn->text = "<i class=\"material-icons\">switch_camera</i>";
        $btn->style = "     position: fixed;
                            right: 25px;
                            bottom: 85px;
                            z-index: 1000";
        $btn->onclick = "openCamera()";
        //echo "<td>".$btn->html."</td>";

        //echo "</tr></table>\n";
        $FAB = $btn->html;
        ?>
<?php include_once('inc/footer.php'); ?>
