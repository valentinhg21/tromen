<?php 
add_action( 'after_setup_theme', 'theme_add_woocommerce_support' );
add_filter( 'woocommerce_enqueue_styles', function( $styles ) {
    if ( is_product() ) {
        return [];
    }
    return $styles;
} );

function theme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
// Define una función para cambiar el separador de las migas de pan
function woocommerce_breadcrumb_separator($defaults) {
    // Cambia el separador por ">"
    $defaults['delimiter'] = ' > ';
    return $defaults;
}
// Aplica el filtro
add_filter('woocommerce_breadcrumb_defaults', 'woocommerce_breadcrumb_separator');

function maximum_api_filter($query_params) {
    $query_params['per_page']['maximum'] = 10000;
    $query_params['per_page']['default'] = 500;
    return $query_params;
}
 add_filter('rest_product_collection_params', 'maximum_api_filter', 10, 1 );


add_filter('woocommerce_product_categories_widget_args', 'custom_woocommerce_product_categories_widget_args');

function custom_woocommerce_product_categories_widget_args($args) {
    $args['orderby'] = 'id'; // Cambia 'name' por 'slug', 'id', 'count', etc. según el criterio de orden que prefieras
    $args['order'] = 'ASC'; // Cambia 'ASC' por 'DESC' si prefieres un orden descendente
    return $args;
}


// Definir la función antes de usarla en el filtro
function customize_woocommerce_product_list_api_response($response, $object, $request) {
    // Verificar si es la solicitud de la lista de productos
    if ($request['orderby'] || $request['category'] || $request['per_page']) {
        // Obtener el permalink del producto
        $permalink = get_permalink($object->get_id());

        // Agregar un nuevo campo 'url' o modificar el 'permalink'
        $response->data['url'] = $permalink; // Agrega un campo 'url' adicional con el mismo permalink
        
        // Si deseas cambiar directamente el campo 'permalink' existente, usa esto:
        // $response->data['permalink'] = 'https://tu-nueva-url.com/producto/' . $object->get_slug();
    }

    return $response;
}

// Añadir el filtro después de definir la función
add_filter('woocommerce_rest_prepare_product_object', 'customize_woocommerce_product_list_api_response', 10, 3);


add_filter('woocommerce_rest_prepare_product_object', 'add_discount_html_to_api', 10, 3);

function add_discount_html_to_api($response, $product, $request) {
    // Reutilizar la función get_discount_percentage para obtener el HTML
    $product_data = product_details($product);
    $discount_html = get_discount_percentage($product);
    $tags_html = $product_data['tags_html'];
    $price_fee = price_fee_html($product);
    // Agregar el HTML al campo `discount_html` del JSON de la API
    $response->data['discount_html'] = $discount_html;
    $response->data['price_fee'] = $price_fee;
    $response->data['tags_html'] = $tags_html;
    return $response;
}


add_filter('woocommerce_get_price_html', 'custom_discount_inside_bdi', 10, 2);

function custom_discount_inside_bdi($price_html, $product) {
    // Obtener los precios regular y de oferta
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();

    // Verificar si hay un precio de oferta válido
    if ($regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price) {
        // Calcular el porcentaje de descuento
        $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;

        // Formatear el descuento
        $discount_html = '<span class="discount-label"> ' . number_format($discount_percentage, 0) . '% OFF</span>';

        // Modificar solo el contenido del <ins> para incluir el descuento dentro del <bdi>
        $price_html = preg_replace_callback(
            '/<ins[^>]*>(.*?)<\/ins>/s', // Encuentra el bloque <ins>
            function ($matches) use ($discount_html) {
                // Inserta el descuento después del contenido de precio dentro de <bdi>
                return preg_replace(
                    '/(<bdi>.*?)(<\/bdi>)/',
                    '$1' . $discount_html . '$2',
                    $matches[0]
                );
            },
            $price_html
        );
    }

    // Retornar el HTML modificado
    return $price_html;
}
