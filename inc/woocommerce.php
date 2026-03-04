<?php
/**
 * WooCommerce Compatibility File
 *
 * @package censkills-theme
 */

if ( ! function_exists( 'censkills_woocommerce_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WooCommerce features.
	 */
	function censkills_woocommerce_setup() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 150,
				'single_image_width'    => 300,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'default_columns' => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
				),
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif;
add_action( 'after_setup_theme', 'censkills_woocommerce_setup' );

/**
 * Custom shortcode to display products or a "No product found!" message if empty.
 */
function censkills_products_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
		),
		$atts,
		'censkills_products'
	);

	if ( empty( $atts['category'] ) ) {
		return '<p class="censkills-no-products">No product found!</p>';
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $atts['category'],
				),
			),
		)
	);

	if ( $query->have_posts() ) {
		// Products exist, use the default WooCommerce shortcode
		return do_shortcode( '[products category="' . esc_attr( $atts['category'] ) . '"]' );
	} else {
		// No products found
		return '<p class="censkills-no-products" style="text-align: center; padding: 2em; font-size: 1.2em;">No product found!</p>';
	}
}
add_shortcode( 'censkills_products', 'censkills_products_shortcode' );

/**
 * Custom WooCommerce Loop Hooks for Modern Product Card Design
 */
add_action( 'init', 'censkills_custom_woocommerce_loop_hooks' );
function censkills_custom_woocommerce_loop_hooks() {
	// Remove standard WooCommerce hooks
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Add our custom hooks
	add_action( 'woocommerce_before_shop_loop_item', 'censkills_woocommerce_loop_link_open', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'censkills_woocommerce_loop_thumbnail', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'censkills_woocommerce_loop_setup', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'censkills_woocommerce_loop_price', 10 );
}

function censkills_woocommerce_loop_link_open() {
	global $product;
	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link censkills-product">';
}

function censkills_woocommerce_loop_thumbnail() {
	global $product;
	echo '<div class="censkills-product-image-wrap">';
	
	// Badge
	if ( $product->is_on_sale() ) {
		echo '<span class="censkills-badge sale-badge">SALE</span>';
	} else {
		// Mock NEW badge
		echo '<span class="censkills-badge new-badge">NEW</span>';
	}

	echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'censkills-product-img' ) );
	echo '</div>'; // close wrap

	// Mockup Color Swatches
	echo '<div class="censkills-product-swatches">';
	echo '<span class="swatch bg-black"></span>';
	echo '<span class="swatch bg-gray-light"></span>';
	echo '<span class="swatch bg-gray-dark"></span>';
	echo '</div>';
}

function censkills_woocommerce_loop_setup() {
	echo '<h2 class="censkills-product-title">' . get_the_title() . '</h2>';
}

function censkills_woocommerce_loop_price() {
	global $product;
	echo '<div class="censkills-product-price">' . $product->get_price_html() . '</div>';
}
