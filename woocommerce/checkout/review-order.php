<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-image" colspan="1">
						<?php 
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<?php if ( empty( $product_permalink ) ) : ?>
								<?php echo $thumbnail?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span class="product-quantity">' . $cart_item['quantity'] . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endif; ?>
					</td>
					<td class="product-name" colspan="2">
					<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					<td class="product-total" colspan="1">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}
		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><?php  echo show_input_cupon(); ?></td>
		</tr>
		<tr class="cart-subtotal">
			<th colspan="2"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td colspan="2"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th colspan="2"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td colspan="2"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th colspan="1"><?php echo esc_html( $fee->name ); ?></th>
				<td colspan="2"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		<tr class="order-total">
			<th colspan="1"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td colspan="3"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<tr class="order-submit">
			<td colspan="4">
			<button type="submit" class="button alt btn btn-red w-100" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo esc_attr( __('Place order', 'woocommerce') ); ?>">
				<?php echo esc_html( __('Place order', 'woocommerce') ); ?>
			</button>
			</td>
		</tr>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
					document.addEventListener('click', function (event) {
						if (event.target && event.target.id === 'validar-cupon-btn') {
							event.preventDefault();
							const couponCode = document.querySelector('#coupon-code-input');
							// Obtén el valor del input
							if(couponCode.value){
								ajaxVerifyCupon({coupon_code: couponCode.value})
									.then(() => {
										
										// setTimeout(() => {
										//     miniCartAddToCart.classList.remove('adding');
										//     miniCartAddToCart.disabled = false;
										//     miniCartAddToCart.innerHTML = miniCartAddToCart.dataset.text;
										// }, 250);
						
									})
									.catch(() => {
								
										// setTimeout(() => {
										//     miniCartAddToCart.classList.remove('adding');
										//     miniCartAddToCart.disabled = false;
										//     miniCartAddToCart.innerHTML = miniCartAddToCart.dataset.text;
										// }, 250);
									});
							}
						}
					});

			});
		</script>
		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</tfoot>
</table>
