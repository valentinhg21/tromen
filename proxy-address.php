<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $apiKey = 'AIzaSyBcsj601U1J3rfq9gTAPQmkrpDIc6NFiIs';
    $input = isset($_GET['input']) ? urlencode($_GET['input']) : '';
    $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$input&key=$apiKey&components=country:ar";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
?>