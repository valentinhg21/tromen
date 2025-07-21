
<?php

// Registrar scripts globales (solo una vez por handle)
function zetenta_register_global_scripts() {
    wp_register_script('validator', LIB . '/validator/validator.js', [], '1.0', true);
    wp_register_script('splide-js', LIB . '/splide/splide.min.js', [], '4.1.3', true);
    wp_register_script('helper-js', LIB . '/helper/helper.js', [], '1.0', true);
    wp_register_script('leaflet-js', LIB . '/leaflet/leaflet.js', [], '1.9.4', true);
    wp_register_script('marker-js', LIB . '/leaflet/leaflet.markercluster.js', [], '1.0', true);
    wp_register_script('plyr-js', LIB . '/plyr/plyr.min.js', [], '1.0', true);
    wp_register_script('noty-js', 'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js', [], '3.0', true);
    wp_register_script( 'list-map-js', WOOCOMMERCE . '/single-map-product.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'zetenta_register_global_scripts', 5);

// Registrar estilos globales
function zetenta_register_global_styles() {
    wp_register_style('font', 'https://use.typekit.net/hfw5hdp.css', [], '1.0', 'all');
    wp_register_style('font-orbital', 'https://use.typekit.net/xkk0lcl.css', ['font'], '1.0', 'all');
    wp_register_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', ['font-orbital'], '6.4.2', 'all');
    wp_register_style('zetenta-styles', get_stylesheet_uri(), ['fontawesome'], '1.0', 'all');
    wp_register_style('plyr-css', LIB . '/plyr/plyr.min.css', [], '3.7.8', 'all');
    wp_register_style('leaflet-cluster', LIB . '/leaflet/MarkerCluster.css', [], '1.9.4', 'all');
    wp_register_style('leaflet-clusterDefault', LIB . '/leaflet/MarkerCluster.Default.css', [], '1.9.4', 'all');
    wp_register_style('leaflet-css-map', LIB . '/leaflet/leaflet.css', ['leaflet-cluster', 'leaflet-clusterDefault'], '1.9.4', 'all');
    
    wp_register_style('noty-css', 'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css', [], '3.0', 'all');
}
add_action('wp_enqueue_scripts', 'zetenta_register_global_styles', 5);

// Enqueue estilo principal
function zetenta_enqueue_main_style() {
    wp_enqueue_style('zetenta-styles');
}
add_action('wp_enqueue_scripts', 'zetenta_enqueue_main_style');

// Scripts principales del sitio
function zetenta_theme_scripts() {
    wp_register_script('sm-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js', ['jquery'], '2.0.8', true);
    wp_register_script('sm-2-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js', ['sm-scripts'], '3.9.1', true);
    wp_register_script('sm-3-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/plugins/animation.gsap.min.js', ['sm-2-scripts', 'splide-js'], '2.0.8', true);

    wp_register_script('zetenta-scripts', JS . '/index.min.js', ['sm-3-scripts', 'helper-js'], '1.0', true);
    wp_enqueue_script('zetenta-scripts');

    wp_localize_script('zetenta-scripts', 'ajax_var', [
        'url'   => admin_url('admin-ajax.php'),
        'theme' => ROOT,
        'site'  => home_url('/wp-json/', 'https'),
    ]);
}
add_action('wp_enqueue_scripts', 'zetenta_theme_scripts');

// WooCommerce Archive
function enqueue_custom_script_for_woocommerce_archive() {
    if (is_product_category()) {
        wp_register_script('range-js', LIB . '/rangeSlider/rangeSlider.min.js', ['splide-js'], '2.3.1', true);
        wp_enqueue_script('archive-product-helper', WOOCOMMERCE . '/helper.js', ['range-js'], '1.0', true);
        wp_enqueue_script('archive-product-helper-filter', WOOCOMMERCE . '/helper-filter.js', ['archive-product-helper'], '1.0', true);
        wp_enqueue_script('archive-product-script', WOOCOMMERCE . '/archive-subcategory.js', ['archive-product-helper-filter'], '1.0', true);

        wp_register_style('rangeslider-css', LIB . '/rangeSlider/rangeslider.css', [], '2.3.1', 'all');
        wp_register_style('rangeslider-flat', 'https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.skinFlat.min.css', ['rangeslider-css'], '2.3.1', 'all');
        wp_enqueue_style('rangeslider-flat');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script_for_woocommerce_archive');

// Single product
// Activa/desactiva debug por constante
define('DEBUG_PRODUCT_SCRIPTS', true);

function script_styles_single_product() {
    if (!is_product()) return;

    if (!defined('DEBUG_PRODUCT_SCRIPTS') || DEBUG_PRODUCT_SCRIPTS === false) return;

    // === Paso 1: Photoswipe CSS ===
    $photoswipecss = 'https://unpkg.com/photoswipe@5.2.2/dist/photoswipe.css';
    wp_register_style('photoswipe-css', $photoswipecss, ['leaflet-css-map', 'plyr-css'], '1.0', 'all');
    wp_enqueue_style('photoswipe-css');

  

    // === Paso 3: Script slide-single-product.js ===
    wp_enqueue_script('slide-single-product-js', WOOCOMMERCE . '/single-product.js', ['splide-js', 'plyr-js'], '1.0', true);

    // === Paso 4: Google Maps API ===
    $google_maps = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBcsj601U1J3rfq9gTAPQmkrpDIc6NFiIs&libraries=places';
    wp_register_script('single-map', $google_maps, ['slide-single-product-js', 'leaflet-js'], '1.0', true);

    // === Paso 5: Script final que depende de Google Maps ===
    wp_enqueue_script('single-product-map', WOOCOMMERCE . '/single-map-product.js', ['single-map'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'script_styles_single_product');

// Checkout
function script_styles_checkout() {
    if (is_checkout()) {
        wp_enqueue_style('noty-css');
        wp_enqueue_script('noty-js');
    }
}
add_action('wp_enqueue_scripts', 'script_styles_checkout');

// type="module" para scripts específicos
function add_module_type_to_script($tag, $handle, $src) {
    $module_scripts = ['slide-single-product-js'];
    if (in_array($handle, $module_scripts)) {
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_module_type_to_script', 10, 3);