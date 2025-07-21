<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
// <span class="suggest">*Precio sugerido en Bs. As.</span>
?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
    <?php if($product->get_price_html()): ?>
        <?php echo $product->get_price_html(); ?>
		<span class="suggest" style="color: #1d1d1b !important;">*Precio sugerido en Bs. As.</span>
    <?php endif; ?>
</p>

<?php echo price_fee_html($product); ?>
<?php if($product->is_on_sale()): ?>
	<div class="link-discount-container">

	
		<?php
		$url = get_field('pagina_de_oferta', 'options');
		if ($url): ?>
			<a href="<?php echo esc_url($url); ?>#puntos-de-venta" class="link-discount">
				Ver puntos de venta con descuentos
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>

