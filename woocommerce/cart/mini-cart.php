<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
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

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>
	<?php 
	$count = count(WC()->cart->get_cart()); 
	$class = $count > 3 ? 'scroll-y' : '';
	?>
	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?><?php echo esc_attr($class);?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="content">
						<div class="thumbnail">
							<?php if ( empty( $product_permalink ) ) : ?>
								<?php echo $thumbnail?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endif; ?>
						</div>
						<div class="information">
							<h3><?php echo wp_kses_post( $product_name ); ?></h3>
							<?php 
							echo wc_get_formatted_cart_item_data( 
								$cart_item, 
								$cart_item_key 
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?>
							<p class="price">
								<?php echo $product_price; ?>
							</p>
							<div class="actions">
								<?php 
								echo show_quantity_mini_cart( 
									$cart_item, 
									$cart_item_key, 
									$_product 
								); 
								?>
								<div class="woocommerce-mini-cart-item-remove">
									<?php
									echo apply_filters(
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<button 
												type="button" 
												class="btn-remove-item" 
												aria-label="%s" 
												data-product="%s" 
												data-cart_item_key="%s" 
												data-product_sku="%s">
												Eliminar
											</button>',
											esc_attr( 
												sprintf( 
													__( 'Remove %s from cart', 'woocommerce' ), 
													wp_strip_all_tags( $product_name ) 
												) 
											), // Descripción accesible del botón
											esc_attr( $product_id ), // ID del producto principal
											esc_attr( $cart_item_key ), // Clave del ítem en el carrito
											esc_attr( $_product->get_sku() ) // SKU del producto
										),
										$cart_item_key
									);
									?>
								</div>
							</div>
						</div>
						<?php
						apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">&times;</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								/* translators: %s is the product name */
								esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() ),
								/* translators: %s is the product name */
								esc_attr( sprintf( __( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) )
							),
							$cart_item_key
						);
						?>
					</div>
				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<p class="woocommerce-mini-cart__total total">
		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons d-none"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
	<div class="button__container d-flex justify-center align-items-center w-100">
            <a href="<?php echo wc_get_checkout_url();?>" title="Ir al checkout" class="btn btn-red w-100">
                <i class="bi bi-arrow-right"></i>
                Finalizar compra
            </a>
    </div>
<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'TU CARRITO ESTÁ VACÍO', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
