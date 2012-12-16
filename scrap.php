<?php
    /*
     * Converts currency from one type to another
     * @param $amount The amount of currency to convert
     * @param $from_currency The source currency type
     * @param $to_currrency The converted currency type
     * @return Numerical value of the converted currency type
     */
	function ConvertCurrency($amount,$from_currency,$to_currency)
	{
		$string = $amount.strtolower($from_currency)."=?".strtolower($to_currency);
		$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;
		$result = file_get_contents($google_url);
		$result = explode('"', $result);
		$confrom = explode(' ', $result[1]);
		$conto = explode(' ', $result[3]);
		return $conto[0];
	}
	/*
     * Get local resturants from Yelp using its API. The results get written to yelpResults.json.  Relies on yelp.php.
     * @param $location The location of where to search for restaurants in.
     */
	function getYelpInfo($location){
		$yelpdest = str_replace(array(" ", ","), array( "+", "%2C"), $location);
		$yelpdom = getYelpData($yelpdest);
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/yelpResults.json', 'w');
		fwrite($fp, $yelpdom);
		fclose($fp);
		return $yelpdom;
	}
	
	// Include the library
	include('simple_html_dom.php');
	include('yelp.php');
	
    //Gets parameters from Post request
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$place = $_POST['place'];
		if(array_key_exists('startdate', $_POST))
			$startdate = $_POST['startdate'];
		else
			$startdate = '';
		if(array_key_exists('enddate', $_POST))
			$enddate = $_POST['enddate'];
		else
			$enddate = '';
		
		$guest = $_POST['guest'];
		$room = $_POST['room'];
	}
	else{ //Gets parameters from Get request
		$place = $_GET['place'];
		if(array_key_exists('startdate', $_GET))
			$startdate = $_POST['startdate'];
		else
			$startdate = '';
		if(array_key_exists('enddate', $_GET))
			$enddate = $_POST['enddate'];
		else
			$enddate = '';
		if(array_key_exists('guest', $_GET))
			$guest = $_GET['guest'];
		else
			$guest = '';
		if(array_key_exists('room', $_GET))
			$room = $_GET['room'];
		else
			$room = '';
	}
	
	//Uses Yelp API to get restaurants nearby
	$yelpJSON = getYelpInfo($place);
	
    //Change input to match the 2 aggregated sites format
	$airdest = str_replace(array(" ", ","), array("-", "--"), $place);
	$homedest = str_replace(array(" ", ","), array( "+", "%2C"), $place);
	
    //Gathered user start date and stored as an array
	$startdate = array( 'y' => substr($startdate,0,4),
						 'm' => substr($startdate,5,2),
						 'd' => substr($startdate,8,2)
						 );
						 
	//Gathered user end date and stored as an array
	$enddate = array( 'y' => substr($enddate,0,4),
						 'm' => substr($enddate,5,2),
						 'd' => substr($enddate,8,2)
						 );
						 
    
    // Retrieve the DOM from airbnb
    $airdom = file_get_html("https://www.airbnb.com/s/$airdest?checkin=$startdate[m]%2F$startdate[d]%2F$startdate[y]&checkout=$enddate[m]%2F$enddate[d]%2F$enddate[y]&guests=$guest");
	
	//Retrieve the DOM from homeaway
	$homeawaydom = file_get_html("http://www.homeaway.co.uk/search/refined/keywords:$homedest/Sleeps:$guest/arrival:$startdate[y]-$startdate[m]-$startdate[d]/departure:$startdate[y]-$startdate[m]-$startdate[d]");
    
    
    /*
	 * Scrap data from "www.airbnb.com" to get bed and breakfast locations
	 */
    foreach ($airdom->find('ul li.search_result') as $e){
        $main1=str_replace('&amp;', '&', $e->find('div[class=pop_image_small]',0)->childNodes(0)->getAttribute('title')  );
        $main2='www.airbnb.com' . $e->find('div[class=pop_image_small]',0)->childNodes(0)->getAttribute('href');
        $main3=$e->find('div[class=pop_image_small]',0)->childNodes(0)->childNodes(0)->getAttribute('data-original');
        $main4=$e->find('div[class=price]',0)->find('div[@class=price_data]',0)->text();
        $main5=$e->find('div[@class="buttons_container"]',0)->childNodes(0)->getAttribute('data-address');
		$main6=$e->find('div[@class="descriptor descriptor-gray overflow-ellipsis"]',0)->text();
        
        $arr[] = array(    'url' => $main2,
                           'currency' => trim($main4),
                           'image' => $main3,
                           'name' => $main1,
                           'address' => $main5,
						   'room' => trim($main6),
						   'source' => 'www.airbnb.com'
                     );
	}
		
	/*
	 * Scrap data from "www.homeaway.co.uk" to get bed and breakfast locations
	 */
    foreach ($homeawaydom->find('div[class=content-container]') as $e){
		$main1=$e->find('div[class=main-container]',0)->childNodes(0)->childNodes(0)->childNodes(0)->text();
		$main2='www.homeaway.co.uk' . $e->find('div[class=main-container]',0)->childNodes(0)->childNodes(0)->childNodes(0)->getAttribute('href');
		$main3=$e->childNodes(0)->childNodes(0)->childNodes(0)->getAttribute('ref');
        $main4=$e->find('div[class=hit-rates]',0);
		if($main4->find('div[class=rateType]', 0) != null){
			if(trim($main4->find('div[class=rateType]', 0)->childNodes(0)->text()) === 'Nightly'){
				$dollar = intval(substr($main4->childNodes(0)->childNodes(0)->childNodes(1)->text(),2));
				$main4 = '$' . strval(round(ConvertCurrency($dollar, 'GBP', 'USD'), 0)) . ' USD';
			}
			else{
				$main4 = 'Price not available';
			}
		}
		else{
			$main4 = 'Price not available';
		}
        $main5=$e->find('div[class="breadcrumb"]',0)->childNodes(0)->childNodes(0)->text();
		$main6=$e->find('div[class="listing-description"]',0)->text();
		$main5=explode('&nbsp;',$main5);
        
        $arr[] = array(    'url' => $main2,
                           'currency' => trim($main4),
                           'image' => $main3,
                           'name' => $main1,
                           'address' => trim($main5[0]),
						   'room' => trim($main6),
						   'source' => 'www.homeaway.co.uk'
                     );
	}
	
	/*
	 * Scrap data from "www.travelmath.com" to get nearby cities.  Used for Suggested Location feature
	 */
	$city = explode(" ", $place);
	$city[] ="";

	$content = file_get_html("http://www.travelmath.com/cities-near/$city[0]+$city[1]");

	$sug_response;
	foreach ($content->find('div[class=boxmiddle]') as $e){
			$tr = $e -> childNodes(1) -> childNodes(0);
		for($i=0; $i<2; $i++)	{
			for($j=0; $j<10; $j++)
			{
				if( ($j %2) ===0){
					$nearby = $tr ->childNodes($i) ->childNodes($j) ->text();
					//echo $nearby;
					$sug_response[] = $nearby;
				}
			}	
		}
	}

	
	
	/*
     * Gathered all scrapped data and compiled into a single JSON object
     */
    $response = array(
		'searchresult' => $arr,
		'suggested' => $sug_response,
		'place' => $place,
		'startdate' => $startdate,
		'enddate' => $enddate,
		'guest' => $guest,
		'room' => $room
	);
    
    //Writes out the JSON file to the root folder of the server.
    /* No longer used but will be kept for debugging purposes
    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/results.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
    */

    $airdom->clear();
             
				       
?>

<script language="javascript" type="text/javascript" charset="utf-8">
    //Passes the scrap data in php and converts it to a javascript JSON object
    var resultJSON = <?php print json_encode($response); ?>;
	var yelpJSON = <?php print $yelpJSON; ?>;
</script>

<?php
    $homepage = file_get_contents('./output.html', false);
	echo $homepage;
?>