<header class="mdl-layout__header mdl-color--grey-100 mdl-layout__header-row" style="z-index: 1000; position: absolute; left: 0; width: 100%">
<?php
/**
 * Created by PhpStorm.
 * User: Brayd
 * Date: 4/13/2017
 * Time: 4:25 AM
 */

    echo @$pre;

    $input = new MDL\Input('lookup-number');
    $input->placeholder = "UPC or name";
    $input->value = @$_GET['value'];
    $input->raw_html = true;
    $input->style = "z-index: 1000; color: black; width: auto";
    $input->onkeyup		= "if (event.keyCode == 13) $('#go').click()";
    echo $input->html;

    $btn = new MDL\Button('go');
    $btn->text = "Go";
    $btn->style = "margin-left: 50px; z-index: 1000;";
    $btn->onclick = "location.href = 'lookup/upc/' + $('#lookup-number').val()";
    echo $btn->html;

    echo @$post;
?>
</header>
