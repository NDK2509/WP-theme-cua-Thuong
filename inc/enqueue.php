<?php
/**
 * Enqueue scripts and styles
 *
 * @package censkills-theme
 */

if ( ! function_exists( 'censkills_theme_scripts' ) ) :
	function censkills_theme_scripts() {
		$theme_version = filemtime( get_template_directory() . '/style.css' );
		wp_enqueue_style( 'censkills-theme-style', get_stylesheet_uri(), array(), $theme_version );
	}
endif;
add_action( 'wp_enqueue_scripts', 'censkills_theme_scripts' );
