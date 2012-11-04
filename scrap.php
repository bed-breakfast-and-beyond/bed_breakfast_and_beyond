<?php
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
	
	function getYelpInfo($location){
		//DO SCRAPPING
	}
	
	// Include the library
	include('simple_html_dom.php');
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$place = $_POST['place'];
		if(array_key_exists('startdate', $_POST))
			$startdate = $_POST['startdate'];
		else
			$startdate = '';
		if(array_key_exists('endate', $_POST))
			$endate = $_POST['endate'];
		else
			$endate = '';
		
		$guest = $_POST['guest'];
		$room = $_POST['room'];
	}
	else{
		$place = $_GET['place'];
		if(array_key_exists('startdate', $_GET))
			$startdate = $_POST['startdate'];
		else
			$startdate = '';
		if(array_key_exists('endate', $_GET))
			$endate = $_POST['endate'];
		else
			$endate = '';
		if(array_key_exists('guest', $_GET))
			$guest = $_GET['guest'];
		else
			$guest = '';
		if(array_key_exists('room', $_GET))
			$room = $_GET['room'];
		else
			$room = '';
	}
	
	getYelpInfo($place);
	
	$airdest = str_replace(array(" ", ","), array("-", "--"), $place);
	$homedest = str_replace(array(" ", ","), array( "+", "%2C"), $place);
	
	$startdate = array( 'y' => substr($startdate,0,4),
						 'm' => substr($startdate,5,2),
						 'd' => substr($startdate,8,2)
						 );
						 
	
	$endate = array( 'y' => substr($endate,0,4),
						 'm' => substr($endate,5,2),
						 'd' => substr($endate,8,2)
						 );
						 
    $myFile = "File.json";
    
    // Retrieve the DOM from airbnb
    $airdom = file_get_html("https://www.airbnb.com/s/$airdest?checkin=$startdate[m]%2F$startdate[d]%2F$startdate[y]&checkout=$endate[m]%2F$endate[d]%2F$endate[y]&guests=$guest");
	
	//Retrieve the DOM from homeaway
	$homeawaydom = file_get_html("http://www.homeaway.co.uk/search/refined/keywords:$homedest/Sleeps:$guest/arrival:$startdate[y]-$startdate[m]-$startdate[d]/departure:$startdate[y]-$startdate[m]-$startdate[d]");
    
    
    // Populate data from Airbnb
	
    $i=0;
	
    foreach ($airdom->find('ul li.search_result') as $e){
        $main1=$e->find('div[class=pop_image_small]',0)->childNodes(0)->getAttribute('title');
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
						   'room' => $main6,
						   'source' => 'www.airbnb.com'
                     );
       // echo "\n\t".$main5."\n";
      // $i = $i+1;
	}
		
	
    foreach ($homeawaydom->find('div[class=content-container]') as $e){
		$main1=$e->find('div[class=main-container]',0)->childNodes(0)->childNodes(0)->childNodes(0)->text();
		$main2='www.homeaway.co.uk' . $e->find('div[class=main-container]',0)->childNodes(0)->childNodes(0)->childNodes(0)->getAttribute('href');
		$main3=$e->childNodes(0)->childNodes(0)->childNodes(0)->getAttribute('ref');
        $main4=$e->find('div[class=hit-rates]',0);
		if($main4->find('div[class=rateType]', 0) != null){
			if(trim($main4->find('div[class=rateType]', 0)->childNodes(0)->text()) === 'Nightly'){
				$dollar = intval(substr($main4->childNodes(0)->childNodes(0)->childNodes(1)->text(),2));
				$main4 = '$' . strval(round(ConvertCurrency($dollar, 'GBP', 'USD'), 0));
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
        
        $arr[] = array(    'url' => $main2,
                           'currency' => trim($main4),
                           'image' => $main3,
                           'name' => $main1,
                           'address' => $main5,
						   'room' => $main6,
						   'source' => 'www.homeaway.co.uk'
                     );
	}
    $response = array(
		'searchresult' => $arr,
		'place' => $place,
		'startdate' => $startdate,
		'endate' => $endate,
		'guest' => $guest,
		'room' => $room
	);
    
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);

    $airdom->clear();
    $homepage = file_get_contents('./output.html', false);
	echo $homepage;               
				       
?>