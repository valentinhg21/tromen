<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

$upsells = $product->get_upsell_ids();
$related_products = wc_get_related_products( $product->get_id(), 4, array( 'orderby' => 'menu_order', 'order' => 'ASC' ) );

if ( ! empty( $upsells ) ) : ?>
    <section class="carousel-products related products">
        <div class="container">
            <?php
            $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Productos sugeridos', 'woocommerce' ) );

            if ( $heading ) :
                ?>
                <h2><?php echo esc_html( $heading ); ?></h2>
            <?php endif; ?>
            <?php woocommerce_product_loop_start(); ?>
                <?php foreach ( $upsells as $upsell_id ) : ?>
                    <?php
                    $post_object = get_post( $upsell_id );
                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                    wc_get_template_part( 'content', 'product' );
                    ?>
                <?php endforeach; ?>
            <?php woocommerce_product_loop_end(); ?>
        </div>
    </section>
<?php elseif ( ! empty( $related_products ) ) : ?>
    <section class="carousel-products related products">
        <div class="container">
            <?php
            $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Productos sugeridos', 'woocommerce' ) );

            if ( $heading ) :
                ?>
                <h2><?php echo esc_html( $heading ); ?></h2>
            <?php endif; ?>
            <?php woocommerce_product_loop_start(); ?>
                <?php foreach ( $related_products as $related_product_id ) : ?>
                    <?php
                    $post_object = get_post( $related_product_id );
                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                    wc_get_template_part( 'content', 'product' );
                    ?>
                <?php endforeach; ?>
            <?php woocommerce_product_loop_end(); ?>
        </div>
    </section>
<?php endif;

wp_reset_postdata();
?>