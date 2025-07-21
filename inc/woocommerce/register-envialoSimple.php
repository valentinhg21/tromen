<?php 



add_action('woocommerce_thankyou', 'enviar_cliente_envialosimple', 10, 1);
function enviar_cliente_envialosimple($order_id) {
    if (!$order_id) return;

    $order = wc_get_order($order_id);
    if (!$order) return;

    $email     = $order->get_billing_email();
    $nombre    = $order->get_billing_first_name();
    $apellido  = $order->get_billing_last_name();
    $telefono  = $order->get_billing_phone();

    $provincia_code   = $order->get_meta('_shipping_state') ?: $order->get_meta('_billing_state');
    $provincia_nombre = obtener_nombre_provincia($provincia_code);

    $ciudad       = $order->get_shipping_city() ?: $order->get_billing_city();
    $codigoPostal = $order->get_shipping_postcode() ?: $order->get_billing_postcode();

    // Obtener nombres de productos
    $productos = [];
    foreach ($order->get_items() as $item) {
        $productos[] = $item->get_name();
    }
    $nombres_productos = implode(', ', $productos); // "Producto 1, Producto 2"

    // Asegurate de que los IDs coincidan con tus campos en EnvialoSimple
    $custom_fields = [
        '1' => $nombre,
        '2' => $apellido,
        '3' => $telefono,
        '4' => $provincia_nombre,
        '5' => $ciudad,
        '7' => $nombres_productos,
        '8' => $codigoPostal
    ];

    enviar_a_envialosimple($email, $custom_fields);
}

// Traduce código de provincia a nombre (para Argentina)
function obtener_nombre_provincia($code) {
    $provincias = WC()->countries->get_states('AR');
    return $provincias[$code] ?? $code;
}

function enviar_a_envialosimple($email, $custom_fields = []) {
    $url = get_template_directory_uri() . '/proxy-envialosimple.php';

    $formData = [
        'action' => 'create',
        'email'  => $email
    ];

    foreach ($custom_fields as $key => $value) {
        $formData["customFields[$key]"] = $value;
    }

    $response = enviar_post_envialo($url, $formData);

 
    if ($response['code'] === 'errorMsg_contactAlreadyExist') {
        $contacto = get_contacto_envialo($url, $email);
        if ($contacto && isset($contacto['id'])) {
            editar_contacto_envialo($url, $contacto['id'], $custom_fields);
            suscribir_a_lista_envialo($url, $contacto['id'], 19);
        }
    } elseif ($response['status'] === 'ok') {
        suscribir_a_lista_envialo($url, $response['data']['id'], 19);
    }
}

function enviar_post_envialo($url, $data) {
    $response = wp_remote_post($url, [
        'method'    => 'POST',
        'body'      => $data,
        'timeout'   => 15,
    ]);

    if (is_wp_error($response)) {
        error_log('Error en EnvialoSimple: ' . $response->get_error_message());
        return [];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

function get_contacto_envialo($url, $email) {
    $data = [
        'action' => 'getContact',
        'email'  => $email,
    ];

    $response = enviar_post_envialo($url, $data);

    return !empty($response['data']['data'][0]) ? $response['data']['data'][0] : null;
}

function editar_contacto_envialo($url, $id, $custom_fields) {
    $data = [
        'action' => 'editContact',
        'id'     => $id,
    ];

    foreach ($custom_fields as $key => $value) {
        $data["customFields[$key]"] = $value;
    }

    enviar_post_envialo($url, $data);
}

function suscribir_a_lista_envialo($url, $contact_id, $list_id) {
    $data = [
        'action'        => 'suscribe',
        'contactsIds[]' => $contact_id,
        'listId'        => $list_id,
    ];

    enviar_post_envialo($url, $data);
}

