<?php
    // Include the library
    include('simple_html_dom.php');
    $place = $_POST['place'];
	$startdate = $_POST['startdate'];
	$endate = $_POST['enddate'];
	$guest = $_POST['guest'];
	$room = $_POST['room'];
	
	$startdate = array( 'a' => substr($startdate,0,4),
						 'b' => substr($startdate,5,2),
						 'c' => substr($startdate,8,2)
						 );
						 
	
	$endate = array( 'a' => substr($endate,0,4),
						 'b' => substr($endate,5,2),
						 'c' => substr($endate,8,2)
						 );
						 
    $myFile = "File.json";
    
    // Retrieve the DOM from a given URL
    $folder = file_get_html("https://www.airbnb.com/s/$place?checkin=$startdate[b]%2F$startdate[c]%2F$startdate[a]&checkout=$endate[b]%2F$endate[c]%2F$endate[a]&guests=$guest");
    
    
    // Find all TD tags with "align=center"
    $i=0;
    foreach ($folder->find('ul li.search_result') as $e){
        $main1=$e->find('div[class=pop_image_small]',0)->childNodes(0)->getAttribute('title');
        $main2=$e->find('div[class=pop_image_small]',0)->childNodes(0)->getAttribute('href');
        $main3=$e->find('div[class=pop_image_small]',0)->childNodes(0)->childNodes(0)->getAttribute('data-original');
        $main4=$e->find('div[class=price]',0)->find('div[@class=price_data]',0)->text();
        $main5=$e->find('div[@class="buttons_container"]',0)->childNodes(0)->getAttribute('data-address');
		$main6=$e->find('div[@class="descriptor descriptor-gray overflow-ellipsis"]',0)->text();
        
        $arr[] = array(    'url' => $main2,
                           'currency' => trim($main4),
                           'image' => $main3,
                           'name' => $main1,
                           'address' => $main5,
						   'room' => $main6
                     );
       // echo "\n\t".$main5."\n";
      // $i = $i+1;
        }
    
    $response = $arr;
    
    $fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);

        
    $folder->clear();
    $homepage = file_get_contents('./output.html', false);
	echo $homepage;               
				       
?>