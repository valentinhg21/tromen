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
                $breadcrumb_text .= esc_html(str_replace("Hornos", "PaHrrilla", $crumb[0]));
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

// Función para ajustar el título SEO en productos
function custom_seo_title_example( $title ) {
    if (is_product()) {
        global $post;
        // Obtener el breadcrumb personalizado
        $breadcrumb = custom_breadcrumb();
        // Obtener el nombre del producto
        $product_name = get_the_title($post->ID);
        // Definir la marca (puedes hacerla dinámica si es necesario)
        $brand_name = "Tromen"; // Cambia a la marca que corresponda
        // Formatear el título personalizado
        $custom_title = capitalizeWords($breadcrumb). ' ' . capitalizeWords($product_name) . '  ' . $brand_name;
        // Retornar el título personalizado
        return str_replace( '-', '|', $custom_title );
    }
    // Si no es un producto, devolver el título original
    return $title;
}

// Filtro para sobrescribir el título de Yoast SEO solo en productos
add_filter( 'wpseo_title', 'custom_seo_title_example' );
