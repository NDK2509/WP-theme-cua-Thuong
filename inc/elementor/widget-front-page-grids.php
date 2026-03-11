<?php
/**
 * Elementor Widget: CenSkills Front Page Grids
 * Wraps the [censkills_front_page_grids] shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CenSkills_Widget_Front_Page_Grids extends \Elementor\Widget_Base {

	public function get_name() {
		return 'censkills_front_page_grids';
	}

	public function get_title() {
		return 'CenSkills – Front Page Grids';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'censkills', 'front page', 'grid', 'homepage', 'categories' ];
	}

	protected function register_controls() {
		// No configurable options — this shortcode has no parameters.
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Info',
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'info_notice',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => '<p style="color:#888;font-size:12px;">Renders the front page category &amp; product grids. No additional settings required.</p>',
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		echo do_shortcode( '[censkills_front_page_grids]' );
	}
}
