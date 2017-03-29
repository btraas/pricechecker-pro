<?php define("PAGE_NAME", "Lookup"); ?>

<?php 

define('USE_UPC_ITEM_DB', 1);

//print_r($_REQUEST); 


$date = isset($_REQUEST['date']) ?
	date('Y-m-d', strtotime($_REQUEST['date'])) :
	date('Y-m-d');


$product_name = "";
$mode = getAlNumUC(@$_REQUEST['meta']);

switch($mode) // {{{
{

	// Choose day
    case ''         : include('inc/lookup/index.php');     exit();

	// Choose session (unless already set for this day, then skip)
	//case 'NEW'		: include('inc/workout/new.php');		exit();

	// Choose Exercise
	//case 'SESSION'  : include('inc/workout/session.php');   exit();

	case 'UPC'		: upc($_REQUEST['value']); exit();

	case 'SCAN'		: include('inc/lookup/upc-scan.php'); exit();

	case 'SAVE'     : save(); exit();
	default			: $_404_msg = "Unable to handle mode: $mode"; include('pages/404.php');

} // }}}

function save() // {{{
{
	Global $user;

	$date = date('Y-m-d', (strtotime($_REQUEST['date'])));
	$exercises = $_REQUEST['exercises'];
	$week = getNumeric($_REQUEST['week']);
	$session = getNumeric($_REQUEST['session']);

	if(!empty($user)) $user->log($date, $week, $session, $exercises);

} // }}}

function upc($upc) // {{{
{

	Global $user;
	if(!empty($user)) $user->trackUPC($upc);

	Global $product_name;
	$NOT_FOUND = '<i>Not found!</i>';

	$offers = array();
	

	$walmart_apikey = "w2qjxh4gg3szxqpnuz88bf4r";
	$walmart = "http://api.walmartlabs.com/v1/items?apiKey=$walmart_apikey&upc=$upc";

	$upcitemdb = "https://api.upcitemdb.com/prod/trial/lookup?upc=$upc";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $walmart);
	$results['walmart'] = json_decode(curl_exec($ch), true);
	curl_close($ch);

	$title = @$results['walmart']['items'][0]['name'];
	//if(empty($title)) $title = $NOT_FOUND;
	if(!empty($title)) $offers[] = array(	'merchant'	=> 'Walmart',
						'title'		=> $title,
						'price'		=> money_format('%i', @$results['walmart']['items'][0]['salePrice']),
						'logo'		=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/76/New_Walmart_Logo.svg/2000px-New_Walmart_Logo.svg.png',
						'link'		=> @$results['walmart']['items'][0]['productUrl'] );


	if(!empty($title)) $product_name = $title;

	$bestbuy_apikey = "1Zme8vP4bq4oc4HfXgqB3m2x";
	$bestbuy = "https://api.bestbuy.com/v1/products(upc=$upc)?format=json&show=sku,name,salePrice&apiKey=$bestbuy_apikey";


 	$ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $bestbuy);
    $results['bestbuy'] = json_decode(curl_exec($ch), true);
    curl_close($ch);

	$bestbuy_item = @$results['bestbuy']['products'][0];

    //if(empty($bestbuy_item)) $bestbuy_item = $NOT_FOUND;

	if(!empty($bestbuy_item)) $offers[] = array(  'merchant'  => 'Best Buy',
                        'title'     => $bestbuy_item,
                        'price'     => @$results['bestbuy']['items'][0]['salePrice'],
                        'logo'      => 'https://developer.bestbuy.com/images/bestbuy-logo.png',
                        'link'      => 'https://developer.bestbuy.com' );




	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $upcitemdb);

	$brand_name = null;
	$product_name = null;

	if(USE_UPC_ITEM_DB) {
		$combined = json_decode(curl_exec($ch), true);

		$tmpOffers = @$combined['items'][0]['offers'];
		$brand_name = @$combined['items'][0]['brand'];

		if(empty($tmpOffers)) $tmpOffers = array();

		foreach($tmpOffers AS $offer) {
			$offer['price'] = money_format('%i', $offer['price']);
			if($offer['domain'] != 'walmart.com') $offers[] = $offer;

		}

	}

	// Tracking
	require_once('lib/brand.php');
	require_once('lib/product.php');
	$brand = new Brand($brand_name);

	if(!empty($brand_name) && empty(@$brand->brand_id)) 
	{

		$url = "https://company.clearbit.com/v1/companies/search?query=name:$brand_name";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "sk_49ccb54bbd533c423c436dfcd5415460:");
		curl_setopt($ch, CURLOPT_URL, $url);

		$raw_brand_data = json_decode(curl_exec($ch), true);
		//logger($brand_data);

		//echo curl_exec($ch);

		$brand_data = $raw_brand_data['results'][0];
		$domain = $brand_data['domain'];

		$data = array();
		$data['name'] = $brand_name;
		$data['logo_url'] = "//logo.clearbit.com/$domain?size=64";

		$brand->create($data);

	}

	//$product = new Product($)


	//ini_set("allow_url_fopen", 1);
	//$json = file_get_contents($walmart);
	//echo $json;

	//echo $result;


	include('inc/lookup/upc.php');


} // }}}}

?>


