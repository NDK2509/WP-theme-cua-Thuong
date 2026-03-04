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
