<?php
/**
 * Single Product Meta
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta censkills-product-meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
		<span class="sku_wrapper meta-item">
			<span class="meta-label"><?php esc_html_e( 'SKU:', 'censkills-theme' ); ?></span>
			<span class="sku meta-value"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'censkills-theme' ); ?></span>
		</span>
	<?php endif; ?>

	<?php
	// Category
	$cat_count = sizeof( get_the_terms( $product->get_id(), 'product_cat' ) );
	echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in meta-item"><span class="meta-label">' . _n( 'Category:', 'Categories:', $cat_count, 'censkills-theme' ) . '</span> <span class="meta-value">', '</span></span>' );

	// Brand (Custom for CenSkills)
	$brands = get_the_terms( $product->get_id(), 'product_brand' );
	if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
		$brand_links = array();
		foreach ( $brands as $brand ) {
			$brand_links[] = '<a href="' . esc_url( get_term_link( $brand ) ) . '">' . esc_html( $brand->name ) . '</a>';
		}
		?>
		<span class="brand_wrapper meta-item">
			<span class="meta-label"><?php esc_html_e( 'Brand:', 'censkills-theme' ); ?></span>
			<span class="brand meta-value"><?php echo implode( ', ', $brand_links ); ?></span>
		</span>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
