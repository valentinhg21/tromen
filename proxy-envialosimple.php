<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verificar si se envió el email

$api_key = "eyJpdiI6Ik5UYzBNakE0T0RjNU5qY3lOakF3T0E9PSIsInZhbHVlIjoiOFRKQVFsckZLZmVOK0hEaERuMlR1WWdkdnF6VlMvSlAxOEloYjJSVldwVT0iLCJtYWMiOiI2OTA1ZmNiMDVkMTQ1MjZlY2ZhNWZiMmM0NDc1NGVjYjNjY2JiMzJmNzI4MjA0YTNkZGI4MWUwMjkzNGVkNzE5In0=";

// Verificar si el contacto esta creado
// Caso contrario Crear el contacto
// Si No, 

function getContact($data, $api_key){
    $api_url = "https://api.esmsv.com/v1/contacts/getall";
        // Obtener los datos del formulario
        $post_data = http_build_query($_POST);

        // Configurar la solicitud cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: $api_key"
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        echo $response;
}

function createContact($data, $api_key) {
    $api_url = "https://api.esmsv.com/v1/contacts/create";

    // Obtener los datos del formulario
    $post_data = http_build_query($_POST);

    // Configurar la solicitud cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: $api_key"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

function subscribeContact($data, $api_key) {
    $api_url = "https://api.esmsv.com/v1/contacts/suscribe";

    // Obtener los datos del formulario
    $post_data = http_build_query($_POST);

    // Configurar la solicitud cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: $api_key"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

function editContact($data, $api_key){
    $api_url = "https://api.esmsv.com/v1/contacts/edit";

    // Obtener los datos del formulario
    $post_data = http_build_query($_POST);

    // Configurar la solicitud cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: $api_key"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    switch ($action) {
        case "create":
            echo createContact($_POST, $api_key);
            break;
        case "suscribe":
            echo subscribeContact($_POST, $api_key);
            break;
        case "getContact":
            echo getContact($_POST, $api_key);
            break;
        case 'editContact':
            echo editContact($_POST, $api_key);
            break;
        default:
            echo json_encode(["error" => "Acción no válida"]);
            break;
    }
}

?>