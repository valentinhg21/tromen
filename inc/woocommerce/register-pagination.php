<?php 


function custom_pagination_rewrite() {
    add_rewrite_rule('^page=([0-9]+)/?', 'index.php?paged=$matches[1]', 'top');
}
add_action('init', 'custom_pagination_rewrite');
function custom_query_vars($vars) {
    $vars[] = 'page';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

function modify_main_query($query) {
    if (!is_admin() && $query->is_main_query() && get_query_var('page')) {
        $query->set('paged', get_query_var('page'));
    }
}
add_action('pre_get_posts', 'modify_main_query');


// function custom_pagination_links($link) {
//     return str_replace('/page/', '/page=', $link);
// }
// add_filter('wpseo_next_rel_link', 'custom_pagination_links');
// add_filter('wpseo_prev_rel_link', 'custom_pagination_links');

remove_action('template_redirect', 'redirect_canonical');

function disable_wp_pagination($query) {
    if (!is_admin() && $query->is_main_query() && $query->get('paged') > 1) {
        $query->set('paged', 1); // Forzar página 1 en la consulta
    }
}
add_action('pre_get_posts', 'disable_wp_pagination');

add_filter('woocommerce_get_breadcrumb', function ($crumbs) {
    foreach ($crumbs as $key => $crumb) {
        if (strpos($crumb[0], 'Página') !== false) {
            unset($crumbs[$key]); // Eliminar el breadcrumb de la página
        }
    }
    return array_values($crumbs); // Reindexar el array
});