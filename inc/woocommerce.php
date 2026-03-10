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

