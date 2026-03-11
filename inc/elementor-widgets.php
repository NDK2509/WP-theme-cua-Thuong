<?php
/**
 * Custom Elementor Widgets for CenSkills shortcodes
 *
 * Registers three drag-and-drop widgets in Elementor:
 *   • CenSkills – Products          → [censkills_products]
 *   • CenSkills – Product Filter    → [censkills_product_filter]
 *   • CenSkills – Front Page Grids  → [censkills_front_page_grids]
 *
 * @package censkills-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register widgets after Elementor is loaded.
add_action( 'elementor/widgets/register', 'censkills_register_elementor_widgets' );

function censkills_register_elementor_widgets( $widgets_manager ) {
	require_once get_template_directory() . '/inc/elementor/widget-products.php';
	require_once get_template_directory() . '/inc/elementor/widget-product-filter.php';
	require_once get_template_directory() . '/inc/elementor/widget-front-page-grids.php';

	$widgets_manager->register( new \CenSkills_Widget_Products() );
	$widgets_manager->register( new \CenSkills_Widget_Product_Filter() );
	$widgets_manager->register( new \CenSkills_Widget_Front_Page_Grids() );
}
