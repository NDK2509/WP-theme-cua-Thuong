<?php
/**
 * Elementor Widget: CenSkills Product Filter
 * Wraps the [censkills_product_filter category="..."] shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CenSkills_Widget_Product_Filter extends \Elementor\Widget_Base {

	public function get_name() {
		return 'censkills_product_filter';
	}

	public function get_title() {
		return 'CenSkills – Product Filter';
	}

	public function get_icon() {
		return 'eicon-filter';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'censkills', 'filter', 'products', 'woocommerce' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Settings',
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

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
				'label'       => 'Product Category (optional)',
				'description' => 'Leave empty to detect from the current category page.',
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $category_options,
				'default'     => '',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$category = sanitize_text_field( $settings['category'] );
		echo do_shortcode( '[censkills_product_filter category="' . esc_attr( $category ) . '"]' );
	}
}
