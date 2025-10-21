<?php 
function capitalizeWords($string) {
    // Divide la cadena en palabras
    $words = explode(' ', $string);
    
    // Capitaliza la primera letra de la primera palabra y convierte el resto a minúsculas
    if (isset($words[0])) {
        $words[0] = ucfirst(mb_strtolower($words[0]));
    }

    // Convierte las demás palabras a minúsculas
    for ($i = 1; $i < count($words); $i++) {
        $words[$i] = mb_strtolower($words[$i]);
    }

    // Une las palabras de nuevo en una cadena
    return implode(' ', $words);
}
// Función personalizada de breadcrumb con control de niveles
function custom_breadcrumb() {
    $breadcrumb = new WC_Breadcrumb();
    $crumbs = $breadcrumb->generate();
    array_pop($crumbs); // Eliminar el último breadcrumb (el producto actual)

    // Excluir la categoría "Accesorios" del breadcrumb
    $excluded_categories = ['Accesorios', 'accesorios calefactores'];

    // Filtrar las categorías excluidas
    $filtered_crumbs = array_filter($crumbs, function($crumb) use ($excluded_categories) {
        return !in_array($crumb[0], $excluded_categories);
    });

    $total_crumbs = count($filtered_crumbs);
    
    // Limitar la cantidad de niveles de categorías que se mostrarán
    if ($total_crumbs == 1) {
        $filtered_crumbs = array_slice($filtered_crumbs, 0, 0); // Mostrar solo el nivel 1 si hay 2 niveles
    } elseif ($total_crumbs == 2) {
        $category = $filtered_crumbs[0][0];
        if (strpos($category, 'Aire libre') !== false) {
            // Si contiene "Gourmet" o "Aire Libre", eliminar el primer nivel y mostrar los demás
            $filtered_crumbs = array_slice($filtered_crumbs, 1, 1); // Mostrar solo los subniveles
        } else {
            // Si no es "Gourmet" o "Aire Libre", mostrar 2 niveles
            $filtered_crumbs = array_slice($filtered_crumbs, 0, 1);
        }
        // Si no es "Gourmet", mostrar 2 niveles
       
    } elseif ($total_crumbs >= 3) {
        // Comprobar si la última categoría es "Gourmet"
        $category = $filtered_crumbs[0][0];
        if (strpos($category, 'Gourmet') !== false) {
            // Si contiene "Gourmet" o "Aire Libre", eliminar el primer nivel y mostrar los demás
            $filtered_crumbs = array_slice($filtered_crumbs, 1, 2); // Mostrar solo los subniveles
        } else {
            // Si no es "Gourmet" o "Aire Libre", mostrar 2 niveles
            $filtered_crumbs = array_slice($filtered_crumbs, 0, 2);
        }
    }

    $breadcrumb_text = '';
    if (!empty($filtered_crumbs)) {
        foreach ($filtered_crumbs as $key => $crumb) {
            if (strpos($crumb[0], "Parrillas") !== false) {
                $breadcrumb_text .= esc_html(str_replace("Parrillas", "Parrilla", $crumb[0]));

            }elseif(strpos($crumb[0], "Hornos") !== false){
                $breadcrumb_text .= esc_html(str_replace("Hornos", "Hornos", $crumb[0]));
            }else{
                $breadcrumb_text .= esc_html($crumb[0]);
            }

            // Añadir espacio entre los niveles de categoría
            if ($key < count($filtered_crumbs) - 1) {
                
                $breadcrumb_text .= ' ';
            }
        }
    }
    return $breadcrumb_text;
}

function breadcrumb_categories() {
    $breadcrumb = new WC_Breadcrumb();
    $crumbs = $breadcrumb->generate();  // Generar los breadcrumbs completos
    
    $breadcrumb_text = ''; // Variable para almacenar el breadcrumb en texto
    
    if ( ! empty( $crumbs ) ) {
        $total_crumbs = count($crumbs);
        
        foreach ( $crumbs as $key => $crumb ) {
            // Añadir el nombre del breadcrumb actual
            $breadcrumb_text .= esc_html( $crumb[0] );
            
            // Si no es el último elemento, añadir el símbolo ">"
            if ($key < $total_crumbs - 1) {
                $breadcrumb_text .= '  ';
            }
        }
    }
    
    // Retornar el breadcrumb completo como texto
    return $breadcrumb_text;
}

// Filtro para sobrescribir el título de Yoast SEO solo en productos
add_filter( 'wpseo_title', 'custom_seo_title_example' );
add_filter( 'wpseo_opengraph_title', 'custom_seo_title_example' );
add_filter( 'wpseo_twitter_title', 'custom_seo_title_example' );
function custom_seo_title_example( $title ) {
    $yoast_separator = ' - ';

    // --- PRODUCTOS ---
    if ( is_product() ) {
        global $post;

        // Comprobar si Yoast tiene título custom
        $yoast_custom_title = get_post_meta( $post->ID, '_yoast_wpseo_title', true );
        if ( !empty( $yoast_custom_title ) ) {
            return $yoast_custom_title; // priorizar el de Yoast
        }

        $breadcrumb    = custom_breadcrumb();
        $product_name  = get_the_title( $post->ID );
        $brand_name    = "Tromen";

        return capitalizeWords($breadcrumb) . ' ' . capitalizeWords($product_name) . $yoast_separator . $brand_name;
    }

    // --- CATEGORÍAS DE PRODUCTO ---
    if ( is_product_category() ) {
        $term = get_queried_object();

        // Comprobar si Yoast tiene título custom
        $yoast_custom_title = get_term_meta( $term->term_id, '_yoast_wpseo_title', true );
        if ( !empty( $yoast_custom_title ) ) {
            return $yoast_custom_title;
        }

        $breadcrumb  = breadcrumb_categories();
        $brand_name  = "Tromen";

        return capitalizeWords($breadcrumb) . ' ' . $yoast_separator . $brand_name;
    }

    return $title; // default
}



add_action('wp_head', function () {
    if (is_product_category() || is_tax()) {
        global $wp, $wp_query;

        // Cantidad de productos por página según WooCommerce
        $products_per_page = 9;

        // Obtener los parámetros actuales de la URL
        $query_params = $_GET;

        // Traducir parámetros
        if (!empty($query_params['precio'])) {
            $query_params['order'] = $query_params['precio']; // Mantiene 'asc' o 'desc'
        }
        if (!empty($query_params['destacados'])) {
            $query_params['feature'] = $query_params['destacados'];
            unset($query_params['destacados']);
        }

        // Asegurar que 'tipo' sea un array, incluso si solo hay un valor
        $tipo_terms = [];
        if (!empty($query_params['tipo'])) {
            $tipo_terms = is_array($query_params['tipo']) ? $query_params['tipo'] : [$query_params['tipo']];
        }

        // Detectar si hay filtros activos
        $hay_filtros = !empty($query_params['order']) || !empty($query_params['feature']) || !empty($query_params['min_price']) || !empty($query_params['max_price']) || !empty($tipo_terms);

        // Calcular el total de productos según si hay filtros o no
        if ($hay_filtros) {
            // Construir query para contar productos filtrados
            $meta_query = ['relation' => 'AND'];

            if (!empty($query_params['min_price']) && !empty($query_params['max_price'])) {
                $meta_query[] = [
                    'key' => '_price',
                    'value' => [(int) $query_params['min_price'], (int) $query_params['max_price']],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            }

            if (!empty($query_params['feature']) && $query_params['feature'] === 'true') {
                $meta_query[] = [
                    'key' => '_featured',
                    'value' => 'yes',
                    'compare' => '=',
                ];
            }

            // Construcción de la query de productos filtrados
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => -1, // Contamos todos los productos sin paginar
                'fields'         => 'ids', // Solo queremos contar IDs
                'tax_query'      => [],
                'meta_query'     => $meta_query,
                'orderby'        => 'meta_value_num',
                'order'          => !empty($query_params['order']) ? strtoupper($query_params['order']) : 'DESC',
                'meta_key'       => '_price',
            ];

            // Si hay filtro `tipo`, usarlo. Si no, obtener la categoría actual.
            if (!empty($tipo_terms)) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $tipo_terms,
                    'operator' => 'IN',
                ];
            } else {
                if (is_product_category()) {
                    $current_cat = get_queried_object();
                    if ($current_cat && !is_wp_error($current_cat)) {
                        $args['tax_query'][] = [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $current_cat->term_id,
                        ];
                    }
                }
            }

            // Obtener total de productos filtrados
            $filtered_products = new WP_Query($args);
            $total_products = count($filtered_products->posts);
        } else {
            // No hay filtros, usar la cantidad total de productos en la categoría actual
            $total_products = $wp_query->found_posts;
        }

        // Calcular el número total de páginas con los productos obtenidos
        $total_pages = ceil($total_products / $products_per_page);

        // Obtener la página actual desde `?page=`
        $current_page = !empty($query_params['page']) ? (int) $query_params['page'] : 1;

        // Eliminar 'paged' para evitar conflictos con WordPress
        unset($query_params['paged']);

        // Construir la URL base con los filtros actuales
        $base_url = home_url(trailingslashit($wp->request));

        // Generar prev y next con los filtros aplicados
        $prev_url = ($current_page > 1) ? add_query_arg(array_merge($query_params, ['page' => $current_page - 1]), $base_url) : null;
        $next_url = ($current_page < $total_pages) ? add_query_arg(array_merge($query_params, ['page' => $current_page + 1]), $base_url) : null;

        // Imprimir las etiquetas SOLO si existen
        if ($prev_url) {
            echo '<link rel="prev" href="' . esc_url($prev_url) . '" />' . "\n";
        }
        if ($next_url) {
            echo '<link rel="next" href="' . esc_url($next_url) . '" />' . "\n";
        }
    }
}, 1);


add_filter('wpseo_next_rel_link', '__return_false'); // Elimina rel="next"
add_filter('wpseo_prev_rel_link', '__return_false'); // Elimina rel="prev"

function add_prev_next_canonical() {
    if (is_page_template('templates-page/template-blog.php')) {
        global $wp_query;

        // Cantidad total de posts publicados
        $total_posts = wp_count_posts('post')->publish; // Asegurate que sea 'post' o tu CPT correcto
        $posts_per_page = 9;
        $max_pages = ceil($total_posts / $posts_per_page);

        // Página actual (GET), asegurando que sea al menos 1
        $current_page = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;

        $base_url = get_permalink(); // URL base del blog
        if (!$base_url) return;

        if ($max_pages > 1) {
            // Prev
            if ($current_page > 1) {
                $prev_url = esc_url($base_url . '?paged=' . ($current_page - 1));
                echo '<link rel="prev" href="' . $prev_url . '" />' . "\n";
            }

            // Next
            if ($current_page < $max_pages) {
                $next_url = esc_url($base_url . '?paged=' . ($current_page + 1));
                echo '<link rel="next" href="' . $next_url . '" />' . "\n";
            }
        }
    }
}
add_action('wp_head', 'add_prev_next_canonical', 1);