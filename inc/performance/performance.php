<?php
// Asegúrate de no acceder directamente al archivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

/**
 * Optimizaciones para el backend de WordPress y WooCommerce
 */
class Backend_Optimization {

    public function __construct() {
        // Deshabilitar funciones innecesarias
        add_action( 'init', array( $this, 'disable_wp_embeds' ) );
        add_action( 'init', array( $this, 'disable_wp_emojicons' ) );
        add_action( 'admin_init', array( $this, 'disable_comments_and_trackbacks' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'disable_dashicons_for_non_admins' ) );
        add_action( 'init', array( $this, 'limit_post_revisions' ) );
        add_action( 'init', array( $this, 'increase_php_memory' ) );
        add_action( 'init', array( $this, 'disable_heartbeat' ) );

        // Limpieza programada
        add_action( 'wp_scheduled_delete', array( $this, 'clean_up_revisions' ) );
        add_action( 'wp_scheduled_delete', array( $this, 'clean_up_transients' ) );
        add_action( 'wp_scheduled_delete', array( $this, 'optimize_database' ) );

        // Deshabilitar jQuery en el backend si no es necesario
        add_action( 'admin_enqueue_scripts', array( $this, 'disable_jquery_in_admin' ) );

        // Deshabilitar feeds RSS
        add_action( 'do_feed', array( $this, 'disable_feeds' ), 1 );
        add_action( 'do_feed_rdf', array( $this, 'disable_feeds' ), 1 );
        add_action( 'do_feed_rss', array( $this, 'disable_feeds' ), 1 );
        add_action( 'do_feed_rss2', array( $this, 'disable_feeds' ), 1 );
        add_action( 'do_feed_atom', array( $this, 'disable_feeds' ), 1 );

        // Optimización adicional específica para WooCommerce
        add_action( 'admin_init', array( $this, 'optimize_woocommerce_admin' ) );
    }

    /**
     * Deshabilitar Embeds de WordPress
     */
    public function disable_wp_embeds() {
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        add_filter( 'embed_oembed_discover', '__return_false' );
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    }

    /**
     * Deshabilitar Emojis
     */
    public function disable_wp_emojicons() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );    
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    }

    /**
     * Limitar el número de revisiones de post
     */
    public function limit_post_revisions() {
        define( 'WP_POST_REVISIONS', 3 );
    }

    /**
     * Aumentar la memoria límite de PHP
     */
    public function increase_php_memory() {
        define( 'WP_MEMORY_LIMIT', '256M' );
        define( 'WP_MAX_MEMORY_LIMIT', '512M' );
    }

    /**
     * Deshabilitar Heartbeat API
     */
    public function disable_heartbeat() {
        wp_deregister_script( 'heartbeat' );
    }

    /**
     * Deshabilitar comentarios y trackbacks
     */
    public function disable_comments_and_trackbacks() {
        // Cerrar comentarios en todos los tipos de post
        add_filter( 'comments_open', '__return_false', 20, 2 );
        add_filter( 'pings_open', '__return_false', 20, 2 );

        // Quitar el menú de comentarios del admin
        remove_menu_page( 'edit-comments.php' );

        // Cerrar comentarios en futuras consultas de búsqueda
        add_action( 'pre_get_posts', function( $query ) {
            if ( $query->is_admin ) {
                $query->set( 'comments', false );
            }
        } );
    }

    /**
     * Deshabilitar Dashicons para usuarios no administradores
     */
    public function disable_dashicons_for_non_admins() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_deregister_style( 'dashicons' );
        }
    }

    /**
     * Limpiar revisiones antiguas automáticamente
     */
    public function clean_up_revisions() {
        global $wpdb;
        $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)" );
    }

    /**
     * Limpiar transients antiguos
     */
    public function clean_up_transients() {
        global $wpdb;
        $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_value < NOW()" );
    }

    /**
     * Optimizar la base de datos
     */
    public function optimize_database() {
        global $wpdb;
        $tables = array(
            $wpdb->posts,
            $wpdb->postmeta,
            $wpdb->comments,
            $wpdb->commentmeta,
            $wpdb->options,
            $wpdb->usermeta,
            $wpdb->terms,
            $wpdb->termmeta,
            $wpdb->term_relationships,
            $wpdb->term_taxonomy,
            $wpdb->woocommerce_order_items,
            $wpdb->woocommerce_order_itemmeta,
            $wpdb->woocommerce_sessions,
            // Añade más tablas de WooCommerce si es necesario
        );

        foreach ( $tables as $table ) {
            $wpdb->query( "OPTIMIZE TABLE $table" );
        }
    }

    /**
     * Deshabilitar jQuery en el backend si no es necesario
     */
    public function disable_jquery_in_admin() {
        // Si realmente no necesitas jQuery en el admin, puedes descomentar la siguiente línea
        // wp_deregister_script( 'jquery' );
        // Sin embargo, WooCommerce y otros plugins pueden depender de jQuery, así que procede con precaución
    }

    /**
     * Deshabilitar feeds RSS
     */
    public function disable_feeds() {
        wp_die( __( 'No feed disponible, por favor visita nuestra página principal.' ) );
    }

    /**
     * Optimización específica para WooCommerce en el backend
     */
    public function optimize_woocommerce_admin() {
        // Deshabilitar scripts y estilos innecesarios en el backend de WooCommerce
        if ( is_admin() && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) {
            // Deshabilitar estilos de Dashicons si ya están deshabilitados globalmente
            // Deshabilitar scripts que no sean esenciales para agregar o editar productos
            // Nota: WooCommerce depende de muchos scripts, así que evita deshabilitar elementos esenciales
        }

        // Optimizar consultas específicas de WooCommerce si es necesario
        // Puedes agregar filtros o acciones adicionales aquí
    }
}

// Inicializar la clase de optimización
new Backend_Optimization();

/**
 * Otras optimizaciones recomendadas
 */

/**
 * Programar tareas de limpieza y optimización
 */
if ( ! wp_next_scheduled( 'wp_scheduled_delete' ) ) {
    wp_schedule_event( time(), 'daily', 'wp_scheduled_delete' );
}

/**
 * Desactivar XML-RPC si no lo usas
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Optimizar la administración de WooCommerce
 */
add_filter( 'woocommerce_admin_meta_boxes_product_data', '__return_empty_array' );

/**
 * Deshabilitar la revisión automática de WooCommerce
 */
remove_action( 'woocommerce_new_product', 'wp_save_post_revision' );
remove_action( 'woocommerce_update_product', 'wp_save_post_revision' );

/**
 * Reducir la cantidad de versiones guardadas por WooCommerce
 */
add_filter( 'woocommerce_product_data_revision_limit', function() {
    return 3; // Limita a 3 revisiones por producto
} );

/**
 * Deshabilitar scripts y estilos innecesarios en el backend
 */
function dequeue_unnecessary_admin_scripts() {
    global $pagenow;

    // Solo en páginas de administración específicas
    if ( is_admin() ) {
        // Ejemplo: Deshabilitar el editor de bloques Gutenberg en el backend si no lo usas
        // remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

        // Deshabilitar estilos y scripts específicos que no necesitas
        // wp_dequeue_style( 'some-style-handle' );
        // wp_dequeue_script( 'some-script-handle' );
    }
}
add_action( 'admin_enqueue_scripts', 'dequeue_unnecessary_admin_scripts', 100 );

/**
 * Optimizar la carga de imágenes y productos en WooCommerce
 */
function optimize_woocommerce_product_queries( $q ) {
    if ( ! is_admin() && is_post_type_archive( 'product' ) && $q->is_main_query() ) {
        // Limitar los productos por página
        $q->set( 'posts_per_page', 20 );
    }
}
add_action( 'pre_get_posts', 'optimize_woocommerce_product_queries' );

/**
 * Reducir la frecuencia de autosave en el backend
 */
function reduce_autosave_interval() {
    return 300; // 5 minutos
}
add_filter( 'autosave_interval', 'reduce_autosave_interval' );

/**
 * Limitar las revisiones de WooCommerce productos
 */
function limit_product_revisions( $revisions, $post ) {
    if ( 'product' === get_post_type( $post ) ) {
        return 3; // Limita a 3 revisiones por producto
    }
    return $revisions;
}
add_filter( 'wp_revisions_to_keep', 'limit_product_revisions', 10, 2 );

/**
 * Limitar la cantidad de elementos en el dashboard
 */
function remove_dashboard_widgets() {
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );

/**
 * Optimizar el editor de productos de WooCommerce
 */
function optimize_wc_product_editor() {
    // Deshabilitar el editor de bloques si prefieres el editor clásico
    // add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

    // Otras optimizaciones específicas del editor pueden agregarse aquí
}
add_action( 'init', 'optimize_wc_product_editor' );
