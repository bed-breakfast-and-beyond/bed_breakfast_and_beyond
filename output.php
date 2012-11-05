<?php

$feed = file_get_contents('yelpResults.json');
$data = json_decode($feed);

print_r($feed);