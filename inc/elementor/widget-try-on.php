<?php
/**
 * Elementor Widget: CenSkills Virtual Try-On
 * Wraps the [censkills_try_on] shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CenSkills_Widget_Try_On extends \Elementor\Widget_Base {

	public function get_name() {
		return 'censkills_try_on';
	}

	public function get_title() {
		return 'CenSkills – Virtual Try-On';
	}

	public function get_icon() {
		return 'eicon-camera';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'censkills', 'try on', 'virtual', 'woocommerce' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Settings',
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'info_text',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => 'This widget displays the Virtual Try-On interface. It is intended for use on Single Product pages.',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		echo do_shortcode( '[censkills_try_on]' );
	}
}
