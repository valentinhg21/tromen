<?php
function saveDataGoogleSheet(array $data, string $url): ?string {
    $postFields = http_build_query($data);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields, // convierte array a form-urlencoded
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($postFields)
        ],
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        error_log('Curl error: ' . curl_error($curl));
        curl_close($curl);
        return null;
    }

    curl_close($curl);
    return $response;
}