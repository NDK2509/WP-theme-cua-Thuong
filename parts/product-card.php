<?php
/**
 * Template part for displaying a product card on the front page and AJAX load more.
 */

global $product;
if ( empty( $product ) ) {
	return;
}

$image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: wc_placeholder_img_src();
?>
<div class="censkills-product">
	<div class="censkills-product-image-wrap">
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="censkills-badge sale-badge">SALE</span>
			<?php else: ?>
				<span class="censkills-badge new-badge">NEW</span>
			<?php endif; ?>
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="censkills-product-img">
		</a>
		<?php if ( $product->is_type( 'variable' ) ) : ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="censkills-atc-overlay button"><?php esc_html_e( 'Chọn sản phẩm', 'censkills-theme' ); ?></a>
		<?php else : ?>
			<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
				class="censkills-atc-overlay button ajax_add_to_cart add_to_cart_button"
				data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
				data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				data-quantity="1"
				aria-label="<?php echo esc_attr( sprintf( __( 'Add "%s" to your cart', 'woocommerce' ), $product->get_name() ) ); ?>"
				rel="nofollow">
				Thêm vào giỏ
			</a>
		<?php endif; ?>
	</div>
	<h2 class="censkills-product-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h2>
	<div class="censkills-product-price">
		<?php echo wp_kses_post( $product->get_price_html() ); ?>
	</div>
</div>
