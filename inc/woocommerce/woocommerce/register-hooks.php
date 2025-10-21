<?php 
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'show_related_products', 20 );
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
        'discount' => get_discount_percentage($product)
    ];
}

function get_discount_percentage($product){

    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    // Verificar si ambos precios están disponibles
    if ($regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price) {
        // Calcular el porcentaje de descuento
        $discount_percentage = (($regular_price - $sale_price) / $regular_price) * 100;
            
        // Formatear el resultado a dos decimales y agregar el símbolo de porcentaje
  

        return ' -' . number_format($discount_percentage, 0) . '%';
    }else{
        return 0;
    }
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

    // Verificar si se encontraron subcategorías
    echo "<ul class='list-categories'>";
    if ( ! empty( $subcategories ) ) {
        // Iterar sobre cada subcategoría y mostrar su nombre
        echo $todos ? "<li class='subcat'><button type='button' class='active' data-id='$id' data-name='$category_name'>Todos</button></li>" : '';
        foreach ( $subcategories as $subcategory ) {
            $destacar = get_field( 'destacar', 'product_cat_' . $subcategory->term_id );
            $permalink = get_term_link($subcategory->term_id);


            $li = $destacar ? 
            "<li class='premier'><a href='$permalink' class='btn btn-md btn-red-transparent'>Ver $subcategory->name</a></li>" : 
            "<li class='subcat'><button type='button' data-id='$subcategory->term_id' data-name='$subcategory->slug'>$subcategory->name</button></li>";
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