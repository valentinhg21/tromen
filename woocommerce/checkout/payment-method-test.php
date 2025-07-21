<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<!--
<li class="payment-mp-custom wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio input-payment" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
	<i class="fa-regular fa-circle"></i>
	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<span>
		<?php if ($gateway->get_title() === 'Transferencia bancaria directa'): ?>
			<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
				<path d="M260-280v-320h40v320h-40Zm200 0v-320h40v320h-40ZM141.54-160v-40h676.92v40H141.54ZM660-280v-320h40v320h-40ZM141.54-680v-33.85L480-875.38l338.46 161.53V-680H141.54Zm105.69-40h465.54-465.54Zm0 0h465.54L480-830 247.23-720Z"/>
			</svg>

		<?php elseif ($gateway->get_title() === 'Contra reembolso'): ?>
			<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
				<path d="M459.46-140v-81.38Q408-228 371.04-257q-36.96-29-54.96-80.23l37.07-16.15q17.31 44.92 47.58 69.15Q431-260 482.85-260q45.61 0 83.34-22.73 37.73-22.73 37.73-73.27 0-42.69-27.77-68.58-27.77-25.88-103.92-50.34-77.54-24.7-109.15-56.43-31.62-31.73-31.62-82.65 0-55.77 42.77-89.08 42.77-33.3 85.23-36V-820h40v80.92q41.54 4.16 71.35 23.04 29.81 18.89 49.42 53.73l-35.54 18.16Q570.38-670 545.31-686q-25.08-16-63.85-16-46.31 0-78.15 24.5-31.85 24.5-31.85 63.5 0 36.85 26.16 59.69 26.15 22.85 104.76 47.69 72.85 23.08 107.2 59.27 34.34 36.2 34.34 90.89 0 60.23-42.38 95.69-42.39 35.46-102.08 39.85V-140h-40Z"/>
			</svg>
		

		<?php elseif ($gateway->get_title() === 'Mercadopago'): ?>
			<?php insert_custom_image(IMAGE . '/mercadopago-logo.png'); ?>
			<p class="mp-title">Hasta 12 cuotas</p>

		<?php endif; ?>
		<?php if($gateway->get_title() !== 'Mercadopago'): ?>

			<?php echo esc_html($gateway->get_title()); ?> 
			<?php echo $gateway->get_icon(); ?>
		<?php endif; ?>
		</span>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?><?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<div class="content">
				<?php $gateway->payment_fields(); ?>
			</div>
		
		</div>
	<?php endif; ?>

</li>
!--> ##