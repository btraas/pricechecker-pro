<?php include_once('inc/header.php'); ?>

<?php include('inc/lookup/upc-input.php'); ?>
            

<style>

.competitors-table {
	margin-top: 20px;
	max-width: 100%;
}

.competitors-table td:not(:first-child) {
	padding: 00px;
	white-space: normal;
}

.competitors-table img {
	max-width: 100px;
}



</style>


<table class='competitors-table mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
    	<thead>
            <tr>
                <th class='mdl-data-table__cell--non-numeric'>Retailer</th>
                <th class='mdl-data-table__cell--non-numeric'>Name</th>
                <th>Unit price</th>
            </tr>
        </thead>

	<?php

	$walmart_item = @$results['walmart']['items'][0];
	$bestbuy_item = @$results['bestbuy']['products'][0];
	if(empty($bestbuy_item)) $bestbuy_item = array('name'=>'<i>Not found!</i>');



	$price = money_format('%i', $walmart_item['salePrice']);

	foreach($offers AS $offer) {
		//print_r($offer);

		if(empty($offer['price']) || empty($offer['title'])) continue;
		if(empty($offer['logo'])) $offer['logo'] = "//logo.clearbit.com/$offer[domain]?size=64";

		echo "<tr>
	<td><a href='$offer[link]'><img src='".@$offer['logo']."'
			alt='$offer[merchant]'
			></a>
	
	<td class='mdl-data-table__cell--non-numeric'><b>".$offer['title'] ."</b></td>
	
	<td style='font-size: 150%; padding: 20px'>$$offer[price]</td>
	</tr>
	";
	}

	if(empty($offers)) echo "<tr><td><i>UPC $upc not found at any retailer!</i></td></tr>";


	//print_r($results);
	?>
</table>


<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'></div>
<?php include_once('inc/footer.php'); ?>
