<?php
/**
 * Checkout Form - CenSkills Custom Override
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout censkills-checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

	<div class="censkills-checkout-wrapper">

		<!-- LEFT: Shipping + Payment -->
		<div class="censkills-checkout-left">

			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div id="customer_details">
					<div><?php do_action( 'woocommerce_checkout_billing' ); ?></div>
					<div><?php do_action( 'woocommerce_checkout_shipping' ); ?></div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<?php endif; ?>

			<!-- Payment Methods -->
			<div class="censkills-payment-section">
				<h2 class="censkills-section-title"><?php esc_html_e( 'Hình thức thanh toán', 'woocommerce' ); ?></h2>
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>

		</div><!-- .censkills-checkout-left -->

		<!-- RIGHT: Order Summary -->
		<div class="censkills-checkout-right">
			<div class="censkills-order-summary-sticky">
				<h2 class="censkills-section-title"><?php esc_html_e( 'Đơn hàng của bạn', 'woocommerce' ); ?></h2>
				<table class="woocommerce-checkout-review-order-table">
					<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
					<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
						<?php
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
							?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td class="product-name">
								<div class="censkills-cart-item">
									<div class="censkills-cart-item-img">
										<?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 64, 64 ) ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<span class="censkills-cart-qty"><?php echo absint( $cart_item['quantity'] ); ?></span>
									</div>
									<div class="censkills-cart-item-info">
										<span class="censkills-cart-item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></span>
										<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</div>
							</td>
							<td class="product-total">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</td>
						</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
				</table>

				<div class="censkills-order-totals">
					<div class="censkills-order-line">
						<span class="censkills-total-label"><?php esc_html_e( 'Tạm tính', 'woocommerce' ); ?></span>
						<span class="censkills-total-value"><?php wc_cart_totals_subtotal_html(); ?></span>
					</div>
					<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
					<div class="censkills-order-line censkills-discount-line">
						<span class="censkills-total-label"><?php echo esc_html( wc_cart_totals_coupon_label( $coupon, false ) ); ?></span>
						<span class="censkills-total-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
					</div>
					<?php endforeach; ?>
					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
					<div class="censkills-order-line">
						<span class="censkills-total-label"><?php esc_html_e( 'Phí vận chuyển', 'woocommerce' ); ?></span>
						<span class="censkills-total-value"><?php do_action( 'woocommerce_review_order_before_shipping' ); wc_cart_totals_shipping_html(); do_action( 'woocommerce_review_order_after_shipping' ); ?></span>
					</div>
					<?php endif; ?>
					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
					<div class="censkills-order-line">
						<span class="censkills-total-label"><?php echo esc_html( $fee->name ); ?></span>
						<span class="censkills-total-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
					</div>
					<?php endforeach; ?>
					<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->get_tax_price_display_mode() ) : ?>
					<div class="censkills-order-line censkills-tax-line">
						<span class="censkills-total-label"><?php esc_html_e( 'VAT', 'woocommerce' ); ?></span>
						<span class="censkills-total-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
					</div>
					<?php endif; ?>
					<div class="censkills-order-line censkills-grand-total">
						<span class="censkills-total-label"><?php esc_html_e( 'Tổng cộng', 'woocommerce' ); ?></span>
						<span class="censkills-total-value"><?php wc_cart_totals_order_total_html(); ?></span>
					</div>
				</div>

			</div><!-- .censkills-order-summary-sticky -->
		</div><!-- .censkills-checkout-right -->

	</div><!-- .censkills-checkout-wrapper -->

</form>

<!-- Sticky Bottom Bar (mobile) -->
<div class="censkills-checkout-sticky-bar" id="censkills-sticky-bar">
	<div class="censkills-sticky-bar-left">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
		<span id="sticky-payment-label"><?php esc_html_e( 'Thanh toán khi nhận hàng', 'woocommerce' ); ?></span>
	</div>
	<div class="censkills-sticky-bar-right">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
		<span><?php esc_html_e( 'Voucher', 'woocommerce' ); ?></span>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
