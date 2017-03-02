<script>

$(document).ready(function()
{
	$('#lookup-number').focus().select();
	//openCamera();
});


</script>

<h2>Enter UPC</h2>


<table><tr>
<?php



$input = new MDL\NumberInput('lookup-number');
//$input->placeholder = "UPC";
$input->value		= @$_GET['value'];
$input->style		= "width: 100%;";
$input->onkeyup		= "if (event.keyCode == 13) $('#go').click()";
echo "<td>".$input->html."</td>";

$camera = new MDL\FabButton('cam');
$camera->icon = 'camera_alt';
$camera->onclick = "location.href = 'lookup/scan'";
echo "<td>".$camera->html."</td>";

echo "</tr></table>\n";

$btn = new MDL\Button('go');
$btn->text = "Go";
$btn->onclick = "location.href = 'lookup/upc/' + $('#lookup-number').val()";

echo $btn->html;

echo "<br>\n";

?>

<div id='result'></div>

