<?php 
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_after_single_product_summary', 'show_related_products', 20 );
// add_filter( 'woocommerce_add_to_cart_validation', '__return_false' );
add_filter('woocommerce_checkout_show_terms', '__return_false');
add_filter('woocommerce_ship_to_different_address_checked', '__return_true');
add_action('wp_enqueue_scripts', 'desactivar_select2_css_woocommerce', 100);
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );


// ZIPPIN METODO DE ENVIO
add_action('woocommerce_checkout_create_order', 'force_shipping_name_from_billing', 20, 2);

function force_shipping_name_from_billing($order, $data) {
    if (empty($data['shipping_first_name'])) {
        $order->set_shipping_first_name($data['billing_first_name']);
    }
    
    if (empty($data['shipping_last_name'])) {
        $order->set_shipping_last_name($data['billing_last_name']);
    }
}
// WOOCOMMERCE ARMADO DE DIRECCION PARA ZIPPIN
add_action('woocommerce_checkout_create_order', 'fusionar_shipping_address_custom', 30, 2);
function fusionar_shipping_address_custom($order, $data) {
    $calle   = $order->get_shipping_address_1();
    $numero  = $order->get_meta('_shipping_numero');
    $piso    = $order->get_shipping_address_2();
    $barrio  = $order->get_meta('_shipping_barrio');

    // Armar dirección fusionada
    $direccion = trim($calle);
    if ($numero) $direccion .= ' ' . $numero;
    if ($piso)   $direccion .= ' - ' . $piso;
    if ($barrio) $direccion .= ' - ' . $barrio;

    // Setear nuevo valor de shipping_address_1
    $order->set_shipping_address_1($direccion);
}
function desactivar_select2_css_woocommerce() {
    // Desactiva los scripts de Select2 en WooCommerce
    wp_dequeue_script('select2');
    wp_dequeue_script('selectWoo'); // WooCommerce usa una versión personalizada llamada selectWoo
    wp_dequeue_script('wc-select2');
    
    // Desactiva los estilos de Select2
    wp_dequeue_style('select2');
    wp_dequeue_style('woocommerce-select2');
}

function show_related_products() {
    // Añadir solo la función para mostrar los productos relacionados
    do_action( 'woocommerce_output_related_products' );
}

function product_details($product) {
    $product_id = $product->get_id();
    $product_name = $product->get_name();
    $product_price = $product->get_price_html();
    $product_price = !empty($product_price) ? $product_price : '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>&nbsp;0</bdi></span>';
    $product_image_id = get_post_thumbnail_id($product_id);
    $product_image = $product_image_id ? 
                     wp_get_attachment_image($product_image_id, 'large') : 
                     insert_custom_image_json(IMAGE_DEFAULT);
    $product_permalink = get_permalink($product_id);
    // Obtener la primera imagen de la galería del producto
    $product_gallery_ids = $product->get_gallery_image_ids();
    $first_gallery_image = !empty($product_gallery_ids) ? wp_get_attachment_image($product_gallery_ids[0], 'large') : '';

    // Obtener las categorías del producto
    $product_categories = get_the_terms($product->get_id(), 'product_cat');
    $primary_category_name = '';
    $primary_category_slug = '';
    $sub_category_name = '';
    $sub_category_slug = '';

    if ($product_categories && !is_wp_error($product_categories)) {
        foreach ($product_categories as $category) {
            if ($category->parent == 0) {
                // Guardar la categoría principal
                $primary_category_name = $category->name;
                $primary_category_slug = $category->slug;
            } else {
                // Guardar la subcategoría
                $sub_category_name = $category->name;
                $sub_category_slug = $category->slug;
            }
        }
    }

    // Decidir cuál mostrar: subcategoría si existe, de lo contrario la categoría principal
    $category_name = $sub_category_name ? $sub_category_name : $primary_category_name;
    $category_name_slug = $sub_category_slug ? $sub_category_slug : $primary_category_slug;

    $imagen_destacada = get_field('imagen_destacada', $product_id);
    $gallery = [];

    if ($imagen_destacada) {
        foreach ($imagen_destacada as $image) {
            $gallery[] = [
                'url' => esc_url($image['sizes']['1536x1536']),
                'width' => $image['sizes']['1536x1536-width'],
                'height' => $image['sizes']['1536x1536-height']
            ];
        }
    } else {
        $gallery[] = [
            'url' => IMAGE_DEFAULT,
            'width' => 750,
            'height' => 500
        ];
    }

    // Obtener los tags del producto con HTML
    $tags_html = '';
    $tags = get_the_terms( $product_id, 'product_tag' );
    if ( $tags && ! is_wp_error( $tags ) ) {
        $tags_html .= '<ul class="product-tags">';
        foreach ( $tags as $tag ) {
            // Obtener el color personalizado de ACF para esta etiqueta
            $bg_color = get_field('color_del_fondo', 'product_tag_' . $tag->term_id);
            $text_color = get_field('color_del_texto', 'product_tag_' . $tag->term_id);
        
            $tags_html .= '
            <li class="tag-svg" style="--bg-color:' . esc_attr($bg_color) . '; --text-color:' . esc_attr($text_color) . '">
                <svg viewBox="0 0 103 26" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M93.047 25.1712H0V0H93.047L103 12.5829L93.047 25.1712Z" fill="var(--bg-color)"/>
                </svg>
                <span class="tag-text">' . esc_html($tag->name) . '</span>
            </li>';
        }
        $tags_html .= '</ul>';
    }

    // Producto Black Imagen
    $imagen_producto_black = get_field( 'imagen_producto_black', $product_id );
    
    return [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'product_image' => $product_image,
        'product_permalink' => $product_permalink,
        'first_gallery_image' => $first_gallery_image,
        'category_name' => $category_name,
        'category_name_slug' => $category_name_slug,
        'gallery' => $gallery,
        'product_price' => $product_price,
        'discount' => get_discount_percentage($product),
        'fee' =>  price_fee_html($product),
        'tags_html' => $tags_html,
        'image_black' => $imagen_producto_black
    ];
}


function get_discount_percentage($product) {
    // Obtener los precios regular y de oferta
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();

    // Verificar si ambos precios están disponibles y válidos
    if ($regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price) {
        // Calcular el porcentaje de descuento
        $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;

        // Formatear el resultado a cero decimales y agregar el símbolo de porcentaje
        return '<div class="discount">' . number_format($discount_percentage, 0) . '% OFF</div>';
    }

    // Retornar una cadena vacía si no hay descuento
    return '';
}


function breadcrumb (){
    $breadcrumb = new WC_Breadcrumb();
    $crumbs = $breadcrumb->generate();  
    array_pop($crumbs);
    if ( ! empty( $crumbs ) ) {
        echo '<ul class="woocommerce-breadcrumb">';
        $total_crumbs = count($crumbs);
        foreach ( $crumbs as $key => $crumb ) {
            echo '<li>' . esc_html( $crumb[0] );
            // Si no es el último elemento, mostramos el signo ">"
            if ($key < $total_crumbs - 1) {
                echo ' >'; // &gt; es el código HTML para ">"
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

function getAllCategoriesById($id, $todos){
    // Obtener todas las subcategorías de la categoría principal
    $subcategories = get_terms( array(
        'taxonomy'   => 'product_cat', // Taxonomía de las categorías de productos en WooCommerce
        'child_of'   => $id, // Obtener las subcategorías de esta categoría principal
        'hide_empty' => false, // Incluir subcategorías sin productos asociados
    ) );

    $subcategorie = get_term($id, 'product_cat');
    $category_name = $subcategorie->name;
    $category_permalink = $subcategorie->permalink;
    // Verificar si se encontraron subcategorías
    echo "<ul class='list-categories'>";
    if ( ! empty( $subcategories ) ) {
        // Iterar sobre cada subcategoría y mostrar su nombre
        echo $todos ? "<li class='subcat'><a href='$category_permalink' class='active'>Todos</a></li>" : '';
        foreach ( $subcategories as $subcategory ) {
            $destacar = get_field( 'destacar', 'product_cat_' . $subcategory->term_id );
            $permalink = get_term_link($subcategory->term_id);

            // Ajustar la condición: $destacar es TRUE solo si tiene un valor que NO es 'Ninguno' y NO está vacío.
            $es_destacado = ( ! empty( $destacar ) && $destacar !== 'Ninguno' );

            $li = $es_destacado ? 
                "<li class='premier'><a href='$permalink' class='btn btn-md btn-red-transparent'>Ver $subcategory->name</a></li>" : 
                "<li class='subcat'><a href='$permalink'>$subcategory->name</a></li>";
                
            echo $li;
        }
    } else {
        // No se encontraron subcategorías
        echo '';
    }
    echo "</ul>";
}

function displayAllProductCategories($parent_id = 0) {
    // Obtener categorías con el ID de padre dado
    $categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => $parent_id,
        'order'      => 'asc'
    ));
    if (!empty($categories) && !is_wp_error($categories)) {
        echo "<ul>";
        foreach ($categories as $category) {
            if ($category->slug === 'uncategorized') {
                continue;
            }

            // Obtener subcategorías inmediatas de la categoría principal
            $subcategories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'parent'     => $category->term_id,
                'order'      => 'asc'
            ));

            // Verificar si la categoría tiene subcategorías
            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                echo "<li class='dropdown-filter dropdown-subfilter'>
                    <button type='button' class='dropdown-title'>
                        {$category->name} <i class='fa-solid fa-chevron-down'></i>
                    </button>";
                
                echo "<ul>";
                foreach ($subcategories as $subcategory) {
                    echo "<li>
                        <button type='button' class='filter-btn-panel filter-tipo tipo' data-group='tipo' data-order='{$subcategory->slug}' 
                        data-id='{$subcategory->term_id}' data-name='{$subcategory->slug}'>
                        <i class='fa-regular fa-circle'></i> {$subcategory->name}
                        </button></li>";
                }
                echo "</ul>";
            } else {
                // Estructura diferente para categorías sin subcategorías
                echo "<li class='simple-category'>
                    <button type='button' class='filter-btn-panel filter-tipo tipo' data-group='tipo' data-order='{$category->slug}' 
                    data-id='{$category->term_id}' data-name='{$category->slug}'>
                    {$category->name}
                    </button></li>";
            }
            echo "</li>";
        }
        echo "</ul>";
    }
}

function getMaxAndMinPrice(){
    $products = wc_get_products(array(
        'status' => 'publish',
        'limit' => -1, // Obtener todos los productos
    ));
    
    // Inicializa variables para el precio mínimo y máximo
    $min_price = PHP_INT_MAX;
    $max_price = 0;
    
    // Itera sobre los productos para encontrar el precio mínimo y máximo
    foreach ($products as $product) {
        $price = $product->get_price();
        if ($price < $min_price) {
            $min_price = $price;
        }
        if ($price > $max_price) {
            $max_price = $price;
        }
    }

    // Retorna un array con los precios mínimo y máximo
    return array(
        'min_price' => $min_price,
        'max_price' => $max_price,
    );


}

function isCatFather($categoria_id) {
        $term = get_term($categoria_id, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $subcategorias = get_term_children($term->term_id, 'product_cat');
            return !empty($subcategorias);
        }
        return false;
}

function cat_details(){
    $category = get_queried_object();
    $category_id = $category->term_id;

    // Obtener el título y la descripción de la categoría
    $title = single_term_title('', false);
    $description = term_description();

    // Obtener la URL de la imagen de la categoría usando ACF o meta de término
    $category_image_url = '';

    // Intenta obtener la URL de la imagen de la categoría usando ACF


    $category_video_url = false;
    if (function_exists('get_field')) {
        $video_opcional = get_field('video_opcional', 'product_cat_' . $category_id);

        // Depuración adicional para ver qué está devolviendo el campo
        error_log(print_r($video_opcional, true));

        // Verifica si es un array y contiene la clave 'url'
        if (is_array($video_opcional) && isset($video_opcional['url'])) {
            $category_video_url = esc_url($video_opcional['url']);
        } elseif (is_string($video_opcional)) {
            // Si el valor es una cadena, asume que es una URL directa
            $category_video_url = esc_url($video_opcional);
        }
    }

    // Si no se pudo obtener la URL de la imagen usando ACF, intenta obtenerla desde el meta de término
    if (empty($category_image_url)) {
        $category_image_id = get_term_meta($category_id, 'thumbnail_id', true);
        if (!empty($category_image_id)) {
            $category_image_url = wp_get_attachment_url($category_image_id);
        }
    }

    // Obtener las subcategorías
    $subcategories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'parent'     => $category_id,
        'hide_empty' => false,
    ));

    // Obtener la categoría padre
    $parent_category = null;
    $parent_category_name = '';
    $parent_category_url = '';
    $anchor = '';
    if ($category->parent != 0) {
        $parent_category = get_term($category->parent, 'product_cat');
        if ($parent_category) {
            $parent_category_name = $parent_category->name;
            $parent_category_url = get_term_link($parent_category);
            // Construcción de la etiqueta de anclaje (link)
            $anchor = '<a class="parent-cat" href="' . esc_url($parent_category_url) . '" aria-label="' . esc_attr($parent_category_name) . '">';
            $anchor .= $parent_category_name;
            $anchor .= '</a>';
        }
    }

    // Devolver la información de la categoría
    return array(
        'id'           => $category_id,
        'title'        => $title,
        'description'  => $description,
        'image_url'    => $category_image_url,
        'subcategories' => $subcategories,
        'parent_cat' => $anchor,
        'video' => $category_video_url
    );
}

function price_fee_html($product) {
    if (!$product || !is_a($product, 'WC_Product')) {
        return '';
    }

    // Precios base
    $precio_regular = (float) $product->get_regular_price();
    $precio_oferta  = (float) $product->get_sale_price();
    $is_on_sale     = $product->is_on_sale();

    // Si está en oferta, usar el precio de oferta; si no, el regular
    $precio_base = $is_on_sale && $precio_oferta > 0 ? $precio_oferta : $precio_regular;

    // Cálculo de cuotas
    $precios_cuotas = [
        '3'  => $precio_base / 3,
        '6'  => ($precio_base * 1.13) / 6,
        '12' => ($precio_base * 1.30) / 12,
    ];

    // Campos ACF
    $acf_cuotas = [
        '3'  => get_field('3_cuotas', $product->get_id()),
        '6'  => get_field('6_cuotas', $product->get_id()),
        '12' => get_field('12_cuotas', $product->get_id()),
    ];

    // Generar HTML de cuotas
    $html_cuotas = '';
    foreach ($precios_cuotas as $cuotas => $valor) {
        if (!empty($acf_cuotas[$cuotas])) {
            $html_cuotas .= sprintf(
                '<p class="cuotas"><span>%s</span> cuotas fijas de %s</p>',
                $cuotas,
                wc_price($valor)
            );
        }
    }

    // Si está en oferta, mostrar precio de un pago
    $html_efectivo = '';
    if ($is_on_sale) {
        $html_efectivo = sprintf(
            '<p class="efectivo">En 1 pago de %s</p>',
            wc_price($precio_base)
        );
    }

    // Clase adicional
    $extra_class = $is_on_sale ? ' on-sale' : ' price-regular';

    // HTML final
    return sprintf(
        '<div class="price-fee%s">%s%s</div>',
        esc_attr($extra_class),
        $html_cuotas,
        $html_efectivo
    );
}

function is_ecommerce_active( $product_id ) {
    // Obtener las categorías del producto
    $categories = wp_get_post_terms( $product_id, 'product_cat' );

    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            // Consultar el valor del campo ACF en la categoría
            $activar_ecommerce = get_field( 'activar_ecommerce', 'product_cat_' . $category->term_id );

            if ( $activar_ecommerce ) {
                // Retornar true si alguna categoría tiene el campo activado
                return true;
            }
        }
    }

    // Retornar false si no se encuentra ninguna categoría con el campo activado
    return false;
}

function show_quantity_mini_cart( $cart_item, $cart_item_key, $product ) {
    // Obtener las cantidades mínimas y máximas del producto.
    $minStock = $product->get_min_purchase_quantity();
    $maxStock = $product->get_max_purchase_quantity();

    // Comprobar que las variables están definidas correctamente.
    if ( ! isset( $minStock ) || ! isset( $maxStock ) ) {
        $minStock = 1; // Valor por defecto para la cantidad mínima.
        $maxStock = 999; // Valor por defecto para la cantidad máxima.
    }

    // Comienza a capturar el contenido HTML.
    ob_start();
    ?>
    <div class="quantity-container">
        <button type="button" id="minus-qty" class="minus-qty">
            <svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 12.1147L5 12.1147" stroke="#222222" stroke-linecap="round"/>
            </svg>
        </button>
        <input 
            type="number" 
            name="cart_item_quantity" 
            class="mini-cart-quantity-input"
            value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" 
            min="<?php echo esc_attr( $minStock ); ?>" 
            max="<?php echo esc_attr( $maxStock ); ?>" 
            step="1"
            data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
            data-max-stock="<?php echo esc_attr( $maxStock ); ?>" 
            data-min-stock="<?php echo esc_attr( $minStock ); ?>"
            data-product="<?php echo esc_attr( $product->get_id() ); ?>" 
        >
        <button type="button" id="plus-qty" class="plus-qty">
            <svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 6.08594L10 18.1432" stroke="#222222" stroke-linecap="round"/>
                <path d="M15 12.1147L5 12.1147" stroke="#222222" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    <?php
    return $html = ob_get_clean(); // Captura el contenido HTML y lo almacena en una variable.

}

function show_input_cupon(){
    // Comienza a capturar el contenido HTML.
    ob_start(); ?>
    <div class="custom-cupon">
        <input type="text" id="coupon-code-input" placeholder="CÓDIGO DE DESCUENTO">
        <button type="button" class="btn btn-black-to-red btn-sm" id="validar-cupon-btn">Validar Cupón</button>
    </div>
    <div class="blockUI-cupon blockOverlay "><span class="loader"></span></div>
    <?php
    return $html = ob_get_clean(); // Captura el contenido HTML y lo almacena en una variable.
}


function mi_traduccion_personalizada($translated_text, $text, $domain) {
    $traducciones = array(
        'Order received' => 'Pedido recibido',
        'Billing details' => 'Datos de facturación',
        'Billing address' => 'Dirección de facturación',
        'Shipping address' => 'Datos de Envio',
        'Order' => 'Pedido',
        'Quantity' => 'Cantidad',
        'Price' => 'Precio',
        'Product' => 'Producto',
        'Cart' => 'Carrito'
    );

    if (isset($traducciones[$text])) {
        return $traducciones[$text];
    }

    return $translated_text;
}
add_filter('gettext', 'mi_traduccion_personalizada', 10, 3);