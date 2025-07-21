<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">

	<?php if ( $show_shipping ) : ?>

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

	<?php endif; ?>

	<h2 class="woocommerce-column__title"><?php esc_html_e( 'Detalles de facturación', 'woocommerce' ); ?></h2>

	<address>
		<p>
			Nombre y Apellido:
			<?php 
			// Nombre y Apellido
			echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); 
			?>
		</p>
		<p>
			Nombre de la empresa:
			<?php 
			// Empresa
			if ( $order->get_billing_company() ) {
				echo esc_html( $order->get_billing_company() );
				echo '<br>';
			}
			?>
		</p>
		<?php if($order->get_meta('_billing_razon_social')): ?>
			<p>
				CUIT:
				<?php echo esc_html( $order->get_meta('_billing_razon_social')) ?>
			</p>
		<?php endif; ?>
		<?php if($order->get_shipping_address_1()): ?>
			<p>
				Dirección:
				<?php echo esc_html( $order->get_shipping_address_1()) ?>
				<?php echo esc_html( $order->get_meta('_shipping_numero')) ?>
			</p>
		<?php endif; ?>
		<?php if($order->get_shipping_address_2()): ?>
			<p>
				Departamento:
				<?php echo esc_html( $order->get_shipping_address_2()) ?>
			</p>
		<?php endif; ?>
		<?php if($order->get_meta('_shipping_barrio')): ?>
			<p>
				Barrio:
				<?php echo esc_html( $order->get_meta('_shipping_barrio')) ?>
			</p>
		<?php endif; ?>

	

	

		<?php if ( $order->get_billing_phone() ) : ?>
			<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
		<?php endif; ?>

		<?php if ( $order->get_billing_email() ) : ?>
			<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
		<?php endif; ?>
		<?php if ( $order->get_customer_note() ) : ?>
			<p>
				<?php esc_html_e( 'Note:', 'woocommerce' ); ?>
				<?php echo wp_kses( nl2br( wptexturize( $order->get_customer_note() ) ), array() ); ?>
			</p>
		<?php endif; ?>
		<?php
			/**
			 * Action hook fired after an address in the order customer details.
			 *
			 * @since 8.7.0
			 * @param string $address_type Type of address (billing or shipping).
			 * @param WC_Order $order Order object.
			 */
			do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order );
		?>
	</address>

	<?php if ( $show_shipping ) : ?>

		</div><!-- /.col-1 -->

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2 d-none">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
			<address>
				<?php  #wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

				<?php if ( $order->get_shipping_phone() ) : ?>
					<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
				<?php endif; ?>
				<?php if($order->get_shipping_address_1()): ?>
					<p>
						Dirección:
						<?php echo esc_html( $order->get_shipping_address_1()) ?>
						<?php echo esc_html( $order->get_meta('_shipping_numero')) ?>
					</p>
				<?php endif; ?>
				<?php if($order->get_shipping_address_2()): ?>
					<p>
						Departamento:
						<?php echo esc_html( $order->get_shipping_address_2()) ?>

					</p>
				<?php endif; ?>
				<?php if($order->get_meta('_shipping_barrio')): ?>
					<p>
						Barrio:
						<?php echo esc_html($order->get_meta('_shipping_barrio')) ?>

					</p>
				<?php endif; ?>
				<?php if($order->get_shipping_postcode()): ?>
					<p>
						CP:
						<?php echo esc_html( $order->get_shipping_postcode()) ?>

					</p>
				<?php endif; ?>
				<?php
					/**
					 * Action hook fired after an address in the order customer details.
					 *
					 * @since 8.7.0
					 * @param string $address_type Type of address (billing or shipping).
					 * @param WC_Order $order Order object.
					 */
					do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order );
				?>
			</address>
		</div><!-- /.col-2 -->

	</section><!-- /.col2-set -->

	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
