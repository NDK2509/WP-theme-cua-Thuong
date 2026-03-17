<?php
/**
 * Theme Settings Page
 *
 * @package censkills-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings and menu page
 */
function censkills_theme_settings_init() {
	// Register the setting
	register_setting( 'censkills_settings_group', 'censkills_google_ai_key' );
	register_setting( 'censkills_settings_group', 'censkills_google_ai_model' );

	// Add settings section
	add_settings_section(
		'censkills_ai_section',
		'AI Configuration',
		'censkills_ai_section_callback',
		'censkills-settings'
	);

	// Add setting fields
	add_settings_field(
		'censkills_google_ai_key',
		'Google AI API Key',
		'censkills_google_ai_key_render',
		'censkills-settings',
		'censkills_ai_section'
	);

	add_settings_field(
		'censkills_google_ai_model',
		'Google AI Model',
		'censkills_google_ai_model_render',
		'censkills-settings',
		'censkills_ai_section'
	);
}
add_action( 'admin_init', 'censkills_theme_settings_init' );

/**
 * Section callback
 */
function censkills_ai_section_callback() {
	echo '<p>Configure the Google AI (Nano Banana / Gemini) integration for your site.</p>';
}

/**
 * Render the API Key field
 */
function censkills_google_ai_key_render() {
	$value = get_option( 'censkills_google_ai_key' );
	?>
	<input type="text" name="censkills_google_ai_key" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
	<p class="description">Enter your Google AI Studio API Key. Get it from <a href="https://aistudio.google.com/" target="_blank">Google AI Studio</a>.</p>
	<?php
}

/**
 * Render the Model field
 */
function censkills_google_ai_model_render() {
	$value = get_option( 'censkills_google_ai_model', 'gemini-2.5-flash-image' );
	?>
	<input type="text" name="censkills_google_ai_model" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
	<p class="description">Enter the Gemini model name (e.g., <code>gemini-1.5-flash-latest</code>, <code>gemini-3.1-flash-image-preview</code>).</p>
	<?php
}

/**
 * Add Menu Page
 */
function censkills_theme_add_admin_menu() {
	add_menu_page(
		'CenSkills Settings',
		'CenSkills',
		'manage_options',
		'censkills-settings',
		'censkills_theme_settings_page',
		'dashicons-admin-generic'
	);
}
add_action( 'admin_menu', 'censkills_theme_add_admin_menu' );

/**
 * Render Settings Page
 */
function censkills_theme_settings_page() {
	?>
	<div class="wrap">
		<h1>CenSkills Theme Settings</h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'censkills_settings_group' );
			do_settings_sections( 'censkills-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
