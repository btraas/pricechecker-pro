<script>

$(document).ready(function()
{
	$('#lookup-number').focus().select();
	//openCamera();
});


</script>


<!--<table><tr>-->
<?php

$camera = new MDL\FabButton('cam');
$camera->icon = 'camera_alt';
$camera->onclick = "location.href = 'lookup/scan'";
$camera->addClass('mdl-button--mini-fab');
$camera->style = "position: fixed; left: 10px";
//echo "<td>".$camera->html."</td>";

$pre = $camera->html;
include('upc-textinput.php');


$input = new MDL\NumberInput('lookup-number');
//$input->placeholder = "UPC";
$input->value		= @$_GET['value'];
$input->style		= "width: 100%;";
$input->onkeyup		= "if (event.keyCode == 13) $('#go').click()";
//echo "<td>".$input->html."</td>";

$btn = new MDL\Button('go');
$btn->text = "Go";
$btn->onclick = "location.href = 'lookup/upc/' + $('#lookup-number').val()";
//echo "<td>".$btn->html."</td>";

echo "</tr></table>\n";

//echo "<br>\n";

?>

<div id='result' style="margin-top: 80px"></div>

