<?php 
add_action( 'after_setup_theme', 'theme_add_woocommerce_support' );
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