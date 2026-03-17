<?php
/**
 * WooCommerce Compatibility File
 *
 * @package censkills-theme
 */

if ( ! function_exists( 'censkills_woocommerce_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WooCommerce features.
	 */
	function censkills_woocommerce_setup() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 150,
				'single_image_width'    => 300,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'default_columns' => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
				),
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif;
add_action( 'after_setup_theme', 'censkills_woocommerce_setup' );

/**
 * Custom shortcode to display products or a "No product found!" message if empty.
 */
function censkills_products_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
		),
		$atts,
		'censkills_products'
	);

	if ( empty( $atts['category'] ) ) {
		return '<p class="censkills-no-products">No product found!</p>';
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $atts['category'],
				),
			),
		)
	);

	if ( $query->have_posts() ) {
		// Products exist, use the default WooCommerce shortcode
		return do_shortcode( '[products category="' . esc_attr( $atts['category'] ) . '"]' );
	} else {
		// No products found
		return '<p class="censkills-no-products" style="text-align: center; padding: 2em; font-size: 1.2em;">No product found!</p>';
	}
}
add_shortcode( 'censkills_products', 'censkills_products_shortcode' );

/**
 * Custom WooCommerce Loop Hooks for Modern Product Card Design
 */
add_action( 'init', 'censkills_custom_woocommerce_loop_hooks' );
function censkills_custom_woocommerce_loop_hooks() {
	// Remove standard WooCommerce hooks
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Add our custom hooks
	add_action( 'woocommerce_before_shop_loop_item', 'censkills_woocommerce_loop_link_open', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'censkills_woocommerce_loop_thumbnail', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'censkills_woocommerce_loop_setup', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'censkills_woocommerce_loop_price', 10 );
}

function censkills_woocommerce_loop_link_open() {
	global $product;
	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link censkills-product">';
}

function censkills_woocommerce_loop_thumbnail() {
	global $product;
	echo '<div class="censkills-product-image-wrap">';
	
	// Badge
	if ( $product->is_on_sale() ) {
		echo '<span class="censkills-badge sale-badge">SALE</span>';
	} else {
		// Mock NEW badge
		echo '<span class="censkills-badge new-badge">NEW</span>';
	}

	echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'censkills-product-img' ) );
	echo '</div>'; // close wrap

	// Mockup Color Swatches
	echo '<div class="censkills-product-swatches">';
	echo '<span class="swatch bg-black"></span>';
	echo '<span class="swatch bg-gray-light"></span>';
	echo '<span class="swatch bg-gray-dark"></span>';
	echo '</div>';
}

function censkills_woocommerce_loop_setup() {
	echo '<h2 class="censkills-product-title">' . get_the_title() . '</h2>';
}

function censkills_woocommerce_loop_price() {
	global $product;
	echo '<div class="censkills-product-price">' . $product->get_price_html() . '</div>';
}

/**
 * Product Filter Shortcode
 * Usage: [censkills_product_filter]
 */
add_shortcode( 'censkills_product_filter', 'censkills_product_filter_shortcode' );

function censkills_product_filter_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
		),
		$atts,
		'censkills_product_filter'
	);

	$brands = get_terms( array(
		'taxonomy'   => 'pa_brand',
		'hide_empty' => true,
	) );

	$sizes = get_terms( array(
		'taxonomy'   => 'pa_size',
		'hide_empty' => true,
	) );
	
	ob_start();
	?>
	<div class="censkills-shop-sidebar w-full">
		<h2 class="filter-main-title">Bộ Lọc</h2>
		<form id="censkills-product-filter" class="censkills-filter-form">
			
			<!-- Brands -->
			<div class="filter-section">
				<h3 class="filter-title">Thương hiệu</h3>
				<div class="filter-options">
					<?php if ( ! is_wp_error( $brands ) && ! empty( $brands ) ) : ?>
						<?php foreach ( $brands as $brand ) : ?>
							<label class="filter-checkbox">
								<input type="checkbox" name="filter_brand[]" value="<?php echo esc_attr( $brand->slug ); ?>">
								<span class="checkmark"></span>
								<?php echo esc_html( $brand->name ); ?>
							</label>
						<?php endforeach; ?>
					<?php else: ?>
						<p class="text-sm text-gray-500">Chưa có thương hiệu.</p>
					<?php endif; ?>
				</div>
			</div>

			<!-- Price -->
			<div class="filter-section">
				<h3 class="filter-title">Giá</h3>
				<div class="filter-options">
					<label class="filter-radio">
						<input type="radio" name="filter_price" value="" checked>
						<span class="radiomark"></span>
						Tất cả
					</label>
					<label class="filter-radio">
						<input type="radio" name="filter_price" value="0-500000">
						<span class="radiomark"></span>
						Dưới 500.000đ
					</label>
					<label class="filter-radio">
						<input type="radio" name="filter_price" value="500000-1000000">
						<span class="radiomark"></span>
						500.000đ - 1.000.000đ
					</label>
					<label class="filter-radio">
						<input type="radio" name="filter_price" value="1000000-2000000">
						<span class="radiomark"></span>
						1.000.000đ - 2.000.000đ
					</label>
					<label class="filter-radio">
						<input type="radio" name="filter_price" value="2000000-">
						<span class="radiomark"></span>
						Trên 2.000.000đ
					</label>
				</div>
			</div>

			<!-- Sizes -->
			<div class="filter-section">
				<h3 class="filter-title">Kích thước</h3>
				<div class="filter-size-grid">
					<?php if ( ! is_wp_error( $sizes ) && ! empty( $sizes ) ) : ?>
						<?php foreach ( $sizes as $size ) : ?>
							<label class="filter-size-btn">
								<input type="checkbox" name="filter_size[]" value="<?php echo esc_attr( $size->slug ); ?>" class="hidden-checkbox">
								<span class="size-label"><?php echo esc_html( $size->name ); ?></span>
							</label>
						<?php endforeach; ?>
					<?php else: ?>
						<p class="text-sm text-gray-500">Chưa có kích thước.</p>
					<?php endif; ?>
				</div>
			</div>
			
			<input type="hidden" name="action" value="censkills_filter_products">
			<?php if ( ! empty( $atts['category'] ) ) : ?>
				<input type="hidden" name="product_cat" value="<?php echo esc_attr( $atts['category'] ); ?>">
			<?php elseif ( is_product_category() ) : ?>
				<input type="hidden" name="product_cat" value="<?php echo esc_attr( get_queried_object()->slug ); ?>">
			<?php endif; ?>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * AJAX Product Filter Handler
 */
add_action( 'wp_ajax_censkills_filter_products', 'censkills_filter_products_ajax' );
add_action( 'wp_ajax_nopriv_censkills_filter_products', 'censkills_filter_products_ajax' );

function censkills_filter_products_ajax() {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() ),
	);

	$tax_query = array( 'relation' => 'AND' );

	// Product Category
	if ( ! empty( $_POST['product_cat'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => sanitize_text_field( wp_unslash( $_POST['product_cat'] ) ),
		);
	}

	// Brand
	if ( ! empty( $_POST['filter_brand'] ) && is_array( $_POST['filter_brand'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'pa_brand',
			'field'    => 'slug',
			'terms'    => array_map( 'sanitize_text_field', $_POST['filter_brand'] ),
			'operator' => 'IN',
		);
	}

	// Size
	if ( ! empty( $_POST['filter_size'] ) && is_array( $_POST['filter_size'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'pa_size',
			'field'    => 'slug',
			'terms'    => array_map( 'sanitize_text_field', $_POST['filter_size'] ),
			'operator' => 'IN',
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	// Price — supports "min-max" and "min-" (open-ended)
	if ( ! empty( $_POST['filter_price'] ) ) {
		$price_range = sanitize_text_field( wp_unslash( $_POST['filter_price'] ) );
		$meta_query  = array( 'relation' => 'AND' );

		if ( strpos( $price_range, '-' ) !== false ) {
			$parts = explode( '-', $price_range, 2 );
			$min   = isset( $parts[0] ) && $parts[0] !== '' ? intval( $parts[0] ) : null;
			$max   = isset( $parts[1] ) && $parts[1] !== '' ? intval( $parts[1] ) : null;

			if ( ! is_null( $min ) && ! is_null( $max ) ) {
				$meta_query[] = array(
					'key'     => '_price',
					'value'   => array( $min, $max ),
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC',
				);
			} elseif ( ! is_null( $min ) ) {
				$meta_query[] = array(
					'key'     => '_price',
					'value'   => $min,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				);
			}
		}

		if ( count( $meta_query ) > 1 ) {
			$args['meta_query'] = $meta_query;
		}
	}

	// Run the query
	$query = new WP_Query( $args );

	// Temporarily swap global $wp_query so WC loop hooks work
	global $wp_query;
	$temp_query = $wp_query;
	$wp_query   = $query; // not clone — use the actual query object

	ob_start();
	echo '<ul class="products columns-4">';

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );
		}
	} else {
		echo '<li class="censkills-no-results">Không tìm thấy sản phẩm phù hợp.</li>';
	}

	echo '</ul>';
	$html = ob_get_clean();

	// Restore global query
	$wp_query = $temp_query;
	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
	wp_die();
}


/**
 * Virtual Try-On Shortcode
 * Usage: [censkills_try_on]
 */
add_shortcode( 'censkills_try_on', 'censkills_try_on_shortcode' );

function censkills_try_on_shortcode() {
	if ( ! is_product() ) {
		return '';
	}

	global $product;
	if ( ! $product ) {
		$product = wc_get_product( get_the_ID() );
	}

	$product_image = get_the_post_thumbnail_url( $product->get_id(), 'full' );
	
	ob_start();
	?>
	<div class="censkills-try-on-container" data-product-img="<?php echo esc_url( $product_image ); ?>">
		<div class="try-on-header">
			<h3 class="try-on-title">Virtual Try-On</h3>
			<p class="try-on-desc">Upload your photo to see how it looks!</p>
		</div>

		<div class="try-on-upload-wrapper">
			<label for="try-on-upload" class="try-on-upload-label">
				<span class="upload-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 16V8M12 8L9 11M12 8L15 11M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
				Chụp ảnh hoặc Tải ảnh lên
			</label>
			<input type="file" id="try-on-upload" accept="image/*" class="hidden">
		</div>

		<div id="try-on-preview-area" class="try-on-preview-area hidden">
			<div class="preview-controls">
				<button id="try-on-reset" class="try-on-btn-secondary">Chọn ảnh khác</button>
				<button id="try-on-render" class="try-on-btn-primary">Thử ngay</button>
			</div>
			<div class="canvas-wrapper">
				<canvas id="try-on-canvas"></canvas>
			</div>
		</div>

		<div id="try-on-loading" class="try-on-loading hidden">
			<div class="spinner"></div>
			<p>Đang xử lý hình ảnh...</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * AJAX Handler: Virtual Try-On AI
 */
add_action( 'wp_ajax_censkills_try_on_ai', 'censkills_try_on_ai_handler' );
add_action( 'wp_ajax_nopriv_censkills_try_on_ai', 'censkills_try_on_ai_handler' );

function censkills_try_on_ai_handler() {
	// Prevent any previous output from contaminating the JSON
	while ( ob_get_level() > 0 ) {
		ob_end_clean();
	}
	ob_start();
	
	// Ensure we don't output any warnings/errors to the response
	ini_set( 'display_errors', 0 );
	
	try {
	$api_key = get_option( 'censkills_google_ai_key' );
	if ( empty( $api_key ) ) {
		$api_key = defined( 'GOOGLE_AI_API_KEY' ) ? GOOGLE_AI_API_KEY : '';
	}
	
	if ( empty( $api_key ) ) {
		wp_send_json_error( array( 'message' => 'Google AI API Key is missing. Please set it in CenSkills Settings or define GOOGLE_AI_API_KEY in wp-config.php.' ) );
	}

	$user_image_data = isset( $_POST['user_image'] ) ? $_POST['user_image'] : '';
	$product_image_url = isset( $_POST['product_image'] ) ? esc_url_raw( $_POST['product_image'] ) : '';

	if ( empty( $user_image_data ) || empty( $product_image_url ) ) {
		wp_send_json_error( array( 'message' => 'Missing image data.' ) );
	}

	// Prepare the prompt
	$prompt = "You are a professional fashion AI. Take the user's photo and the product image provided. Generate a new image that realistically shows the person in the user's photo wearing/using the product. Maintain the person's identity, features, and posture. The result must be a clean, high-quality image.";

	// Call Gemini API (using the model set in settings)
	$model_name = get_option( 'censkills_google_ai_model', 'gemini-1.5-flash-latest' );
	$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/" . $model_name . ":generateContent?key=" . $api_key;

	// Extract base64 content
	$user_image_base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $user_image_data);
	
	// Get product image content
	$product_image_response = wp_remote_get( $product_image_url );
	if ( is_wp_error( $product_image_response ) ) {
		wp_send_json_error( array( 'message' => 'Failed to fetch product image: ' . $product_image_response->get_error_message() ) );
	}
	
	$product_image_body = wp_remote_retrieve_body( $product_image_response );
	if ( empty( $product_image_body ) ) {
		wp_send_json_error( array( 'message' => 'Product image content is empty.' ) );
	}
	
	$product_image_base64 = base64_encode( $product_image_body );
	$product_image_mime = wp_remote_retrieve_header( $product_image_response, 'content-type' );

	$payload = array(
		'contents' => array(
			array(
				'parts' => array(
					array( 'text' => $prompt ),
					array(
						'inlineData' => array(
							'mimeType' => 'image/png', 
							'data'     => $user_image_base64
						)
					),
					array(
						'inlineData' => array(
							'mimeType' => $product_image_mime ?: 'image/jpeg',
							'data'     => $product_image_base64
						)
					)
				)
			)
		),
		'generationConfig' => array(
			'responseModalities' => array('IMAGE'),
		)
	);

	$response = wp_remote_post( $endpoint, array(
		'headers' => array( 'Content-Type' => 'application/json' ),
		'body'    => wp_json_encode( $payload ),
		'timeout' => 45
	) );

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( array( 'message' => 'API Request Failed: ' . $response->get_error_message() ) );
	}

	$body = wp_remote_retrieve_body( $response );
	$status_code = wp_remote_retrieve_response_code( $response );
	
	if ( $status_code !== 200 ) {
		error_log( 'Gemini API Error (HTTP ' . $status_code . '): ' . $body );
		$error_info = json_decode( $body, true );
		$err_msg = isset( $error_info['error']['message'] ) ? $error_info['error']['message'] : 'AI API error. Status: ' . $status_code;
		wp_send_json_error( array( 'message' => $err_msg . ' (Body: ' . substr($body, 0, 100) . ')' ) );
	}

	$result = json_decode( $body, true );
	$found_image = false;

	if ( isset( $result['candidates'][0]['content']['parts'] ) && is_array( $result['candidates'][0]['content']['parts'] ) ) {
		foreach ( $result['candidates'][0]['content']['parts'] as $part ) {
			// As per user's Python code: handle both snake_case and camelCase
			$img_obj = null;
			if ( isset( $part['inlineData'] ) ) {
				$img_obj = $part['inlineData'];
			} elseif ( isset( $part['inline_data'] ) ) {
				$img_obj = $part['inline_data'];
			}

			if ( $img_obj && isset( $img_obj['data'] ) ) {
				$mime = isset( $img_obj['mimeType'] ) ? $img_obj['mimeType'] : ( isset( $img_obj['mime_type'] ) ? $img_obj['mime_type'] : 'image/png' );
				$output_image = 'data:' . $mime . ';base64,' . $img_obj['data'];
				wp_send_json_success( array( 'image' => $output_image ) );
				$found_image = true;
				break;
			}
		}
	}

	if ( ! $found_image ) {
		// If it returned text instead of an image, log it
		$text_response = isset( $result['candidates'][0]['content']['parts'][0]['text'] ) ? $result['candidates'][0]['content']['parts'][0]['text'] : 'No text response';
		error_log( 'Gemini API No Image Found. Response Body: ' . substr( $body, 0, 1000 ) );
		wp_send_json_error( array( 'message' => 'Model returned text instead of an image. Response: ' . substr( $text_response, 0, 150 ) . '...' ) );
	}
	} catch ( Exception $e ) {
		while ( ob_get_level() > 0 ) {
			ob_end_clean();
		}
		wp_send_json_error( array( 'message' => 'Exception: ' . $e->getMessage() ) );
	}
	wp_die();
}
