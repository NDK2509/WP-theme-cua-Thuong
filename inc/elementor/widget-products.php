<?php
/**
 * Elementor Widget: CenSkills Products
 * Wraps the [censkills_products category="..."] shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CenSkills_Widget_Products extends \Elementor\Widget_Base {

	public function get_name() {
		return 'censkills_products';
	}

	public function get_title() {
		return 'CenSkills – Products';
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'censkills', 'products', 'woocommerce', 'shop' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Settings',
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Populate category dropdown from WooCommerce terms.
		$category_options = [ '' => '— All categories —' ];
		if ( function_exists( 'get_terms' ) ) {
			$terms = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] );
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$category_options[ $term->slug ] = $term->name;
				}
			}
		}

		$this->add_control(
			'category',
			[
				'label'   => 'Product Category',
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $category_options,
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$category = sanitize_text_field( $settings['category'] );
		echo do_shortcode( '[censkills_products category="' . esc_attr( $category ) . '"]' );
	}
}
