<?php  
add_action('wp_ajax_send_form_contact', 'send_form_contact');
add_action('wp_ajax_nopriv_send_form_contact', 'send_form_contact');
function send_form_contact() {
    while (ob_get_level()) ob_end_clean(); // limpia todos los buffers
    // Incluir PHPMailer si no está cargado
    if (!class_exists('PHPMailer')) {
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $mail = new PHPMailer();
        $mail->isMail();
        $mail->setFrom('no-reply@tromen.com', 'Tromen');

        $dataObject = $_POST;
        $dataObjectFiles = $_FILES;

        $messageHTML = "<html><head><title>Tromen</title></head><body>";
        $subject = isset($dataObject['subject']) ? htmlspecialchars($dataObject['subject']) : 'Sin Asunto';

        $destinatarios = isset($dataObject['destinatario']) ? explode(',', $dataObject['destinatario']) : [];
        $counter = 0;

        foreach ($destinatarios as $destinatario) {
            $destinatario = trim($destinatario);
            if (filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
                if ($counter === 0) {
                    $mail->addAddress($destinatario);
                } else {
                    $mail->addBCC($destinatario);
                }
                $counter++;
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => "Dirección de correo inválida: $destinatario"
                ]);
                exit;
            }
        }

        foreach ($dataObject as $key => $value) {
            if (!in_array($key, ['subject', 'action', 'destinatario', 'activegs', 'gsid'])) {
                $formattedValue = htmlspecialchars(str_replace(',', ', ', $value));
                $messageHTML .= "<p><strong>" . ucfirst(strtolower(str_replace('-', ' ', $key))) . ":</strong> $formattedValue</p>";
            }
        }

        $messageHTML .= "</body></html>";

        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subject;
        $mail->Body = $messageHTML;

        $uploadPath = get_template_directory() . '/archivo/';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($dataObjectFiles as $file) {
            $file_tmp = $file['tmp_name'];
            $file_name = sanitize_file_name($file['name']);
            $file_dest = $uploadPath . $file_name;

            if (move_uploaded_file($file_tmp, $file_dest)) {
                $mail->addAttachment($file_dest);
            } else {
                error_log("Error al mover el archivo: $file_name a $file_dest");
            }
        }

        if (!$mail->send()) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode([
                'success' => false,
                'error' => $mail->ErrorInfo
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'ok'
            ]);
        }

        exit; // 👈 Muy importante: evita que WordPress imprima 0 u otro contenido
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Método no permitido'
        ]);
        exit;
    }
}

add_action('wp_ajax_send_form_newsletter', 'send_form_newsletter');
add_action('wp_ajax_nopriv_send_form_newsletter', 'send_form_newsletter');
function send_form_newsletter(){
    global $mail; // define the global variable
    if (!is_object($mail) || !is_a($mail, 'PHPMailer')) { // check if $phpmailer object of class PHPMailer exists
        // if not - include the necessary files
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $mail = new PHPMailer(true);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // $messageHTML = "<html><head><title>Tromen</title></head><body>";
        // $destinatario = $_POST['destinatario'];
        // $email = $_POST['email'];
        // $messageHTML .= "<h2><strong>Tromen Newsletter</strong></h2>";
        // $messageHTML .= "<p>Email: $email</p>";
        // $subject = 'Tromen - Newsletter';
        // $mail = new PHPMailer();
        // $mail->isMail();
        // $mail->SetFrom('no-reply@tromen.com', 'Tromen'); // Remitente, cambiar según sea necesario
        // $mail->AddAddress($destinatario, $subject);
        // $mail->isHTML(true);
        // $mail->CharSet = "UTF-8";
        // $mail->Subject = $subject;
        // $mail->Body= $messageHTML;
        echo 'ok';
        // if (!$mail->Send()) {
        //     echo "Mailer Error: " . $mail->ErrorInfo;
        // } else {
        //     echo 'ok';
        // }
    }
}

add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');
function search_products() {
    // Asegurarse de que la solicitud sea AJAX y obtener el término
    if (defined('DOING_AJAX') && DOING_AJAX && isset($_GET['term'])) {
        $term = sanitize_text_field($_GET['term']);
        // Configurar los argumentos para WP_Query
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            's'              => $term, // Término de búsqueda
        );


        // Realizar la consulta
        $query = new WP_Query($args);
        $results = array();

        // Iterar sobre los productos encontrados
        if ($query->have_posts()) {
            while ($query->have_posts()) {
           
                $query->the_post();
                $product_id = wc_get_product(get_the_ID());
                $product = product_details($product_id);
                $name = $product['product_name'];
                $permalink = $product['product_permalink'];
                $image = $product['product_image'] ? $product['product_image'] : insert_default_image();
                $main_category = $product['category_name'];
                $category_name = $product['sub_category_name'];
                $product_price = $product['product_price'];
                $tags = $product['tags_html'];


                $results[] = array(
                    'name' => $name,
                    'url' => $permalink,
                    'image' => $image,
                    'category' => $category_name,
                    'main_category' => $main_category,
                    'price' => $product_price,
                    'fee' => $product['fee'],
                    'discount' => $product['discount'],
                    'lang-current' => function_exists('weglot_get_current_language') ? weglot_get_current_language() : 'es',
                    'tags' => minify_html_inline($tags)
                );
            }
            wp_reset_postdata();
        }

        if (function_exists('weglot_ajax_replace_urls')) {
            $results = weglot_ajax_replace_urls($results);
        }

        // Enviar la respuesta en formato JSON
        wp_send_json_success($results);
    } else {
        wp_send_json_error('Invalid request method or missing term parameter');
    }

    wp_die(); // Termina correctamente la ejecución del script en WordPress
}

add_action('wp_ajax_get_product_data', 'get_product_data');
add_action('wp_ajax_nopriv_get_product_data', 'get_product_data');
// Función para obtener datos de productos filtrados y devolver en formato JSON
function get_product_data() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Recibir y sanear los parámetros de la solicitud GET
        $featured = isset($_GET['featured']) && $_GET['featured'] === 'true';
        $categories = isset($_GET['categories']) ? sanitize_text_field($_GET['categories']) : '';
        $price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : '';
        $price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : '';
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 9;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'ASC';
        $price_order = isset($_GET['price']) && in_array(strtoupper($_GET['price']), ['ASC', 'DESC']) ? strtoupper($_GET['price']) : '';

        $args = [
            'post_type' => 'product',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'meta_query' => [],
            'tax_query' => [],
            'orderby' => 'menu_order',
            'order' => $order,
        ];

        if ($featured) {
            // Uso de tax_query para filtrar productos destacados
            $args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ];

            // Esto es necesario para que la consulta no ignore la taxonomía de visibilidad de productos
            $args['tax_query']['relation'] = 'AND';
        }

        if (!empty($categories)) {
            $categories_array = explode(',', $categories);
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $categories_array,
            ];
        }

        if ($price_min !== '') {
            $args['meta_query'][] = [
                'key' => '_price',
                'value' => $price_min,
                'type' => 'NUMERIC',
                'compare' => '>=',
            ];
        }

        if ($price_max !== '') {
            $args['meta_query'][] = [
                'key' => '_price',
                'value' => $price_max,
                'type' => 'NUMERIC',
                'compare' => '<=',
            ];
        }

        // Agregar la lógica para ordenar por precio si se pasa el filtro price_order
        if (!empty($price_order)) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
            $args['order'] = $price_order;
        }

        $query = new WP_Query($args);
        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());
                $details = product_details($product);
                $category = $details['category_name'];
                $url = $details['permalink'];
                $product_url = $details['product_permalink'];
              
           



                $results[] = [
                    'name' => $details['product_name'],
                    'url' => $url,
                    'image' => $details['product_image'] ?: insert_default_image(),
                    'category' => $category,
                    'main_category' => $category,
                    'price' => $details['product_price'],
                    'discount' => $details['discount'],
                ];
            }
            wp_reset_postdata();
        }
        if (function_exists('weglot_ajax_replace_urls')) {
            $results = weglot_ajax_replace_urls($results);
        }

        wp_send_json_success($results);
    } else {
        wp_send_json_error('Invalid request method');
    }

    wp_die();
}

add_action('wp_ajax_get_article_data', 'get_article_data');
add_action('wp_ajax_nopriv_get_article_data', 'get_article_data');
// Función para obtener todos los articulos
function get_article_data() {
    // Verifica si la solicitud es GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Valida y sanitiza los datos de entrada
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 9;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Argumentos para WP_Query
        $args = [
            'post_type' => 'blog',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        // Realiza la consulta
        $query = new WP_Query($args);
        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $permalink = get_the_permalink();
                $title = get_the_title();
                $imagen_articulo = get_field('imagen_articulo');

                // Verifica si hay una imagen del artículo, de lo contrario usa una imagen por defecto
                $blocks = parse_blocks(get_the_content());
                $imagen_articulo = '';
                foreach ($blocks as $block) {
                    if ($block['blockName'] === 'acf/portada-articulo' && !empty($block['attrs']['data']['imagen_articulo'])) {
                        $imagen_articulo = $block['attrs']['data']['imagen_articulo'];
                        break;
                    }
                }
       
    
                if($imagen_articulo){
                    $image_data = wp_get_attachment_image_src($imagen_articulo, 'large');
                    $image_url = $image_data ? $image_data[0] : '';
                    $image_w = $image_data ? $image_data[1] : '';
                    $image_h = $image_data ? $image_data[2] : '';
                    $alt_text = get_post_meta($imagen_articulo, '_wp_attachment_image_alt', true);
                    $image = insert_custom_image_json($image_url, $image_w, $image_h, $alt_text);
                }
                
                $results[] = [
                    'id' => $id,
                    'name' => $title,
                    'url' => $permalink,
                    'image' => $image ? $image : insert_default_image(),
                ];
            }
            // Restablece los datos del post global
            wp_reset_postdata();
        } else {
            // Manejo de error si no hay posts encontrados
            wp_send_json_error('No posts found');
            wp_die();
        }

        // Obtén el total de posts
        $total_posts = $query->found_posts;

        // Envía la respuesta en formato JSON
        wp_send_json_success([
            'articles' => $results,
            'total_posts' => $total_posts,
        ]);
        
    } else {
        // Envía un error si el método de solicitud no es GET
        wp_send_json_error('Invalid request method');
    }
    // Termina la ejecución del script
    wp_die();
}


add_action('wp_ajax_get_pdv', 'get_pdv');
add_action('wp_ajax_nopriv_get_pdv', 'get_pdv');
// Función para obtener todos los articulos
function get_pdv() {
    // Verifica si la solicitud es GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Valida y sanitiza los datos de entrada
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 9;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Argumentos para WP_Query
        $args = [
            'post_type' => 'pdv',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status'    => 'publish',  // Solo posts publicados
        ];

        // Realiza la consulta
        $query = new WP_Query($args);
        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID(); 
                $nombre = get_field('nombre');
                $direccion = get_field('direccion');
                $ciudad = get_field('ciudad');
                $provincia = get_field('provincia');
                $latitud = get_field('lat');
                $longitud = get_field('long');
                $web = get_field('web');
                $telefono = get_field('telefono');
                $mail = get_field('mail');
                // Obtener las fechas de inicio y fin
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $inicia = get_field('inicia', 'options');
                $finaliza = get_field('finaliza', 'options');
                $is_adherido = get_field('adherido_a_la_oferta');
                // Inicializar la variable de promoción
                $promo = false;

                // Comprobar si las fechas de inicio y finalización tienen valor

                if ($inicia) {
                    // Convertir la fecha de inicio de formato d/m/Y a objeto DateTime con la zona horaria correcta
                    $zona_horaria = new DateTimeZone('America/Argentina/Buenos_Aires');
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $inicia, $zona_horaria);
                
                    // Obtener la fecha actual en la zona horaria correcta
                    $fecha_actual = new DateTime('now', $zona_horaria);
                
                    // Convertir las fechas a formato 'Y-m-d' para que solo importe la fecha sin la hora
                    $fecha_inicio_formateada = $fecha_inicio->format('Y-m-d');
                    $fecha_actual_formateada = $fecha_actual->format('Y-m-d');
                
                    // Si hay una fecha de finalización, también la convertimos
                    if ($finaliza) {
                        $fecha_finaliza = DateTime::createFromFormat('d/m/Y', $finaliza, $zona_horaria);
                        $fecha_finaliza_formateada = $fecha_finaliza->format('Y-m-d');
                    }
                
                    // Verificar si la fecha actual está dentro del rango de la promoción
                    if ($fecha_actual_formateada >= $fecha_inicio_formateada) {
                        // Si hay una fecha de finalización, también comprobamos que la fecha actual no la exceda
                        if (!$finaliza || ($finaliza && $fecha_actual_formateada <= $fecha_finaliza_formateada)) {
                            $promo = true; // La promoción está activa si la fecha actual está dentro del rango o si no hay fecha de finalización
                        }
                    }
                }


                $results[] = [
                    'id' => $id,
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'ciudad' => $ciudad,
                    'provincia' => $provincia,
                    'lat' => $latitud,
                    'long' => $longitud,
                    'web' => $web,
                    'telefono' => $telefono,
                    'mail' => $mail,
                    'promo' => $promo,
                    'is_adherido' => $is_adherido
                ];
            }
            // Restablece los datos del post global
            wp_reset_postdata();
        } else {
            // Manejo de error si no hay posts encontrados
            wp_send_json_error('No posts found');
            wp_die();
        }

        // Obtén el total de posts
        $total_posts = $query->found_posts;

        // Envía la respuesta en formato JSON
        wp_send_json_success([
            'pdv' => $results,
            'total_posts' => $total_posts,
        ]);
        
    } else {
        // Envía un error si el método de solicitud no es GET
        wp_send_json_error('Invalid request method');
    }
    // Termina la ejecución del script
    wp_die();
}

add_action('wp_ajax_get_pdv_ofertas', 'get_pdv_ofertas');
add_action('wp_ajax_nopriv_get_pdv_ofertas', 'get_pdv_ofertas');
function get_pdv_ofertas(){
    // Verifica si la solicitud es GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Valida y sanitiza los datos de entrada
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 9;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Argumentos para WP_Query
        $args = [
            'post_type' => 'pdv',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        // Realiza la consulta
        $query = new WP_Query($args);
        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID(); 
                $nombre = get_field('nombre');
                $direccion = get_field('direccion');
                $ciudad = get_field('ciudad');
                $provincia = get_field('provincia');
                $latitud = get_field('lat');
                $longitud = get_field('long');
                $web = get_field('web');
                $telefono = get_field('telefono');
                $mail = get_field('mail');
                // Obtener las fechas de inicio y fin
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $inicia = get_field('inicia', 'options');
                $finaliza = get_field('finaliza', 'options');
                $is_adherido = get_field('adherido_a_la_oferta');
                // Inicializar la variable de promoción
                $promo = false;

                // Comprobar si las fechas de inicio y finalización tienen valor

                if ($inicia) {
                    // Convertir la fecha de inicio de formato d/m/Y a objeto DateTime con la zona horaria correcta
                    $zona_horaria = new DateTimeZone('America/Argentina/Buenos_Aires');
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $inicia, $zona_horaria);
                
                    // Obtener la fecha actual en la zona horaria correcta
                    $fecha_actual = new DateTime('now', $zona_horaria);
                
                    // Convertir las fechas a formato 'Y-m-d' para que solo importe la fecha sin la hora
                    $fecha_inicio_formateada = $fecha_inicio->format('Y-m-d');
                    $fecha_actual_formateada = $fecha_actual->format('Y-m-d');
                
                    // Si hay una fecha de finalización, también la convertimos
                    if ($finaliza) {
                        $fecha_finaliza = DateTime::createFromFormat('d/m/Y', $finaliza, $zona_horaria);
                        $fecha_finaliza_formateada = $fecha_finaliza->format('Y-m-d');
                    }
                
                    // Verificar si la fecha actual está dentro del rango de la promoción
                    if ($fecha_actual_formateada >= $fecha_inicio_formateada) {
                        // Si hay una fecha de finalización, también comprobamos que la fecha actual no la exceda
                        if (!$finaliza || ($finaliza && $fecha_actual_formateada <= $fecha_finaliza_formateada)) {
                            $promo = true; // La promoción está activa si la fecha actual está dentro del rango o si no hay fecha de finalización
                        }
                    }
                }

                if ($is_adherido) {
                    $results[] = [
                        'id' => $id,
                        'nombre' => $nombre,
                        'direccion' => $direccion,
                        'ciudad' => $ciudad,
                        'provincia' => $provincia,
                        'lat' => $latitud,
                        'long' => $longitud,
                        'web' => $web,
                        'telefono' => $telefono,
                        'mail' => $mail,
                        'promo' => $promo,
                        'is_adherido' => $is_adherido
                    ];
                }
            }
            // Restablece los datos del post global
            wp_reset_postdata();
        } else {
            // Manejo de error si no hay posts encontrados
            wp_send_json_error('No posts found');
            wp_die();
        }

        // Obtén el total de posts
        $total_posts = $query->found_posts;

        // Envía la respuesta en formato JSON
        wp_send_json_success([
            'pdv' => $results,
            'total_posts' => $total_posts,
        ]);
        
    } else {
        // Envía un error si el método de solicitud no es GET
        wp_send_json_error('Invalid request method');
    }
    // Termina la ejecución del script
    wp_die();
}


add_filter('weglot_add_json_keys', 'custom_weglot_add_json_keys');
function custom_weglot_add_json_keys($keys) {
    // Agregar las claves que quieres traducir
    $keys[] = 'name';            // Traducción de 'name'
    $keys[] = 'permalink';
    $keys[] = 'category';        // Traducción de 'category'
    $keys[] = 'main_category';   // Traducción de 'main_category'
    $keys[] = 'fee';
    var_dump($keys);   
    return $keys;
}


add_action('wp_ajax_ajax_mini_cart_product', 'ajax_mini_cart_product');
add_action('wp_ajax_nopriv_ajax_mini_cart_product', 'ajax_mini_cart_product');
function ajax_mini_cart_product() {
    // Limpia cualquier salida previa
    if (ob_get_length()) {
        ob_end_clean();
    }

    $product_id = intval($_GET['product_id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    if ($product_id <= 0 || $quantity <= 0) {
        wp_send_json_error(['error' => 'ID de producto o cantidad inválida.']);
        return;
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['error' => 'El producto no existe.']);
        return;
    }

    // Agregar el producto al carrito
    $added = WC()->cart->add_to_cart($product_id, $quantity);
    if (!$added) {
        wp_send_json_error(['error' => 'No se pudo agregar el producto al carrito.']);
        return;
    }

    // Captura el HTML del mini carrito
    ob_start();
    woocommerce_mini_cart();
    $mini_cart_html = ob_get_clean();

    // Limpieza adicional para garantizar respuesta JSON correcta
    header('Content-Type: application/json; charset=UTF-8');
    if (ob_get_length()) {
        ob_clean();
    }
    header('Content-Type: application/json; charset=UTF-8');
    // Respuesta JSON
    wp_send_json_success([
        'mini_cart' => $mini_cart_html,
        'cart_total_items' => WC()->cart->get_cart_contents_count(),
        'cart_total_price' => WC()->cart->get_cart_total(),
    ]);
}

add_action('wp_ajax_ajax_update_cart_quantity', 'ajax_update_cart_quantity');
add_action('wp_ajax_nopriv_ajax_update_cart_quantity', 'ajax_update_cart_quantity');
function ajax_update_cart_quantity() {
    $product_id = intval($_GET['product_id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    if ($product_id <= 0 || $quantity <= 0) {
        wp_send_json_error(array('error' => 'ID de producto o cantidad inválida.'));
        return;
    }

    // Verificar si el producto existe en el carrito
    $cart_item_key = null;
    foreach (WC()->cart->get_cart() as $cart_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $cart_item_key = $cart_key;
            break;
        }
    }

    if (!$cart_item_key) {
        wp_send_json_error(array('error' => 'El producto no está en el carrito.'));
        return;
    }

    // Obtener el producto para verificar stock
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(array('error' => 'El producto no existe.'));
        return;
    }

    // Verificar stock
    if ($product->managing_stock() && $quantity > $product->get_stock_quantity()) {
        wp_send_json_error(array('error' => 'La cantidad solicitada excede el stock disponible.'));
        return;
    }

    // Actualizar la cantidad exacta en el carrito
    WC()->cart->set_quantity($cart_item_key, $quantity);

    // Actualizar el mini carrito
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    // Obtener los totales del carrito
    $cart_total_items = WC()->cart->get_cart_contents_count();
    $cart_total = WC()->cart->get_cart_total();

    // Respuesta JSON
    wp_send_json_success(array(
        'message' => 'Cantidad actualizada correctamente.',
        'mini_cart' => $mini_cart,
        'cart_total_items' => $cart_total_items,
        'cart_total_price' => $cart_total
    ));
}

add_action('wp_ajax_ajax_remove_cart_product', 'ajax_remove_cart_product');
add_action('wp_ajax_nopriv_ajax_remove_cart_product', 'ajax_remove_cart_product');
function ajax_remove_cart_product() {
    // Verificar que se haya enviado el ID del producto
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('error' => 'ID de producto no proporcionado.'));
        return;
    }

    $product_id = intval($_POST['product_id']);
    $removed = false;

    // Recorrer los productos en el carrito y buscar el que se desea eliminar
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            // Eliminar el producto del carrito
            WC()->cart->remove_cart_item($cart_item_key);
            $removed = true;
            break;
        }
    }

    if ($removed) {
        // Actualizar el mini carrito
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        // Obtener totales actualizados
        $cart_total_items = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_cart_total();

        wp_send_json_success(array(
            'message' => 'Producto eliminado del carrito con éxito.',
            'mini_cart' => $mini_cart,
            'cart_total_items' => $cart_total_items,
            'cart_total_price' => $cart_total
        ));
    } else {
        wp_send_json_error(array('error' => 'Producto no encontrado en el carrito.'));
    }
}

// Registrar el endpoint AJAX para validar el cupón
add_action('wp_ajax_validar_cupon_ajax', 'validar_cupon_ajax');
add_action('wp_ajax_nopriv_validar_cupon_ajax', 'validar_cupon_ajax');
function validar_cupon_ajax() {
    // Obtener el código del cupón enviado por AJAX
    $coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';

    if (!$coupon_code) {
        wp_send_json_error(['message' => 'Por favor, ingresa un código de cupón.']);
    }

    // Verificar si el cupón existe y es válido
    $coupon = new WC_Coupon($coupon_code);

    if ($coupon->get_id()) {
        if ($coupon->is_valid()) {
            // Aplicar el cupón al carrito
            WC()->cart->apply_coupon($coupon_code);

            // Generar el HTML de los cupones
            ob_start();
            foreach (WC()->cart->get_coupons() as $code => $coupon) {
                ?>
                    <p><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
                    <p><?php wc_cart_totals_coupon_html( $coupon ); ?></p>
                <?php
            }
            $coupons_html = ob_get_clean();

            // Respuesta con éxito
            wp_send_json_success([
                'message' => 'El cupón es válido y ha sido aplicado.',
                'cart_total' => wc_price(WC()->cart->get_total('edit')),
                'coupons_html' => $coupons_html, // HTML de los cupones
            ]);
        } else {
            wp_send_json_error(['message' => 'El cupón no se puede aplicar.']);
        }
    } else {
        wp_send_json_error(['message' => 'El cupón no es válido.']);
    }
}



