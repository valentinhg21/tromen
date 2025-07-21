<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
$product_data = product_details($product);
$product_name = $product_data['product_name'];
$product_permalink = $product_data['product_permalink'];
$first_gallery_image = $product_data['first_gallery_image'];
$product_image = $product_data['product_image'];
$category = $product_data['category_name'];
$price = $product_data['product_price'];
$discount_per = $product_data['discount'];
?>


<li <?php wc_product_class( 'splide__slide', $product ); ?>>
	<a href="<?php echo $product_permalink;?>" class="product-card" aria-label="Ver detalles de <?php echo $product_name;?>">
		<div class="product-image">
            <?php if($product_image):?>
                <?php  echo $product_image;?>
                <?php else: ?>
                    <img fetchpriority="high" decoding="async" src="<?php echo IMAGE ?>/productos/default.png" class="attachment-large size-large" width="100%" height="100%">
            <?php endif; ?>
            <?php echo $discount_per; ?>
        </div>
		<div class="product-body p-relative">
            <p><?php echo $product_name; ?></p>
            <p class="product-price-slide"><?php echo $price;?></p>
            <?php echo price_fee_html($product); ?>
            <p class="text-red"><?php echo $category; ?></p>
            <button class="permalink">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
	</a>
</li>
