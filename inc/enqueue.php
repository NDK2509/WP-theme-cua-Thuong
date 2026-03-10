<?php
/**
 * Enqueue scripts and styles
 *
 * @package censkills-theme
 */

if ( ! function_exists( 'censkills_theme_scripts' ) ) :
	function censkills_theme_scripts() {
		$theme_version = filemtime( get_template_directory() . '/style.css' );
		
		// Swiper styles and scripts
		wp_enqueue_style( 'swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.5' );
		wp_enqueue_script( 'swiper-script', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.5', true );

		wp_enqueue_style( 'censkills-theme-style', get_stylesheet_uri(), array(), $theme_version );

		wp_enqueue_script( 'censkills-shop-filter', get_template_directory_uri() . '/assets/js/shop-filter.js', array( 'jquery' ), $theme_version, true );
		wp_localize_script( 'censkills-shop-filter', 'censkills_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );

		// Checkout page script
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			wp_enqueue_script( 'censkills-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array(), $theme_version, true );
		}

		// Product page + cart quantity stepper
		if ( function_exists( 'is_product' ) && is_product() || function_exists( 'is_cart' ) && is_cart() ) {
			wp_enqueue_script( 'censkills-product', get_template_directory_uri() . '/assets/js/product.js', array(), $theme_version, true );
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'censkills_theme_scripts' );
