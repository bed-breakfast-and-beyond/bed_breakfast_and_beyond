<?php


// Define the function
function getYelpData($company) {

	// Check if company is set
	if($company) {

		// Enter the path that the oauth library is in relation to the php file
		require_once ('lib/OAuth.php');

		// For example, request business with id 'the-waterboy-sacramento'
		//$unsigned_url = 'http://api.yelp.com/v2/business/' . $company;

		// For examaple, search for 'tacos' in 'sf'
		$unsigned_url = 'http://api.yelp.com/v2/search?term=Resturant&location=' . $company;
						

		// Set your keys here
		$consumer_key = "a8pplMPvEGZpXUAMLH9aHA";
		$consumer_secret = "XC2UWx7qmt18dS2ldnJkYKmUUu8";
		$token = "6MroxBi7dciW5gkMEBeHqMp-8HhMO3BT";
		$token_secret = "0I44CpGpXtep5m_Jm3zPhZ-uEbg";

		// Token object built using the OAuth library
		$token = new OAuthToken($token, $token_secret);

		// Consumer object built using the OAuth library
		$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

		// Yelp uses HMAC SHA1 encoding
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

		// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
		$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

		// Sign the request
		$oauthrequest->sign_request($signature_method, $consumer, $token);

		// Get the signed URL
		$signed_url = $oauthrequest->to_url();

		// Send Yelp API Call
		$ch = curl_init($signed_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch); // Yelp response
		curl_close($ch);

		// Handle Yelp response data
		//$response = json_decode($data);

		return $data;
	
	} else {
		return 'No company was defined';
	}

}

// Define the company id or name from the url
// OR you can use $_POST['id'] if you are posting to this page from a form
//$company = $_GET['id'];
//$myArray = getYelpData($company);

// echo a specific variable
//echo 'rating: ' .  $myArray->;

// print the whole array
// uncomment the line below to test
//print_r($myArray);

?>
