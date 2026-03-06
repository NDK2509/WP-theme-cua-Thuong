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
	}
endif;
add_action( 'wp_enqueue_scripts', 'censkills_theme_scripts' );
