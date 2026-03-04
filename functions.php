<?php
/**
 * CenSkills Theme functions and definitions
 *
 * @package censkills-theme
 */

// Include theme setup.
require get_template_directory() . '/inc/setup.php';

// Include theme activation setup.
require get_template_directory() . '/inc/activation.php';

// Include scripts and styles enqueues.
require get_template_directory() . '/inc/enqueue.php';

// Register widget areas.
require get_template_directory() . '/inc/widgets.php';

// Include WooCommerce compatibility if plugin is active.
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
