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


<table name="resultsTable" id="resultTable" class='competitors-table mdl-data-table mdl-js-data-table mdl-shadow--2dp'>
	<select id="resultSorting" onchange="sortResults()" name="resultSorting">
        <option value="0">Price:Low to High</option>
        <option value="1">Price:High to Low</option>
    	
    </select>
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

	/*presort the result array--ascending*/
    function priceCmp($a, $b) {
        return $a["price"] - $b["price"];
    }
    usort($offers, "priceCmp");
    /*----------------------------------*/

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
                  rows = table.getElementsByTagName("TR");
                  /*Loop through all table rows (except the
                   first, which contains table headers):*/
                  for (i = 1; i < (rows.length - 1); i++) {
                      //start by saying there should be no switching:
                      shouldSwitch = false;
                      /*Get the two elements you want to compare,
                       one from current row and one from the next:*/
                      x = rows[i].getElementsByTagName("TD")[2];
                      y = rows[i + 1].getElementsByTagName("TD")[2];
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


<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'></div>


<?php include_once('inc/footer.php'); ?>




