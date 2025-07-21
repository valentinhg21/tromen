<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$apiKey = 'AIzaSyBcsj601U1J3rfq9gTAPQmkrpDIc6NFiIs';
$placeId = isset($_GET['place_id']) ? urlencode($_GET['place_id']) : '';
$url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&key=$apiKey";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>