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
		// remove slider to allow grid layout
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
			'columns'  => '4',
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
		return do_shortcode( '[products category="' . esc_attr( $atts['category'] ) . '" columns="' . esc_attr( $atts['columns'] ) . '"]' );
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
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Add our custom hooks
	add_action( 'woocommerce_before_shop_loop_item_title', 'censkills_woocommerce_loop_thumbnail', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'censkills_woocommerce_loop_setup', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'censkills_woocommerce_loop_price', 10 );

	// Remove duplicate brand from WooCommerce Brands plugin (we have custom display in meta.php)
	if ( isset( $GLOBALS['WC_Brands'] ) ) {
		remove_action( 'woocommerce_product_meta_end', array( $GLOBALS['WC_Brands'], 'show_brand' ) );
	}
}

function censkills_woocommerce_loop_thumbnail() {
	global $product;
	if ( ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) return;

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	
	echo '<div class="censkills-product-image-wrap">';
	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	
	// Badge
	if ( $product->is_on_sale() ) {
		echo '<span class="censkills-badge sale-badge">SALE</span>';
	} else {
		// Mock NEW badge
		echo '<span class="censkills-badge new-badge">NEW</span>';
	}

	echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'censkills-product-img' ) );
	echo '</a>'; // close link wrapping image

	// Add to Cart overlay button
	if ( $product->is_type( 'variable' ) ) {
		// Variable products: link to product page for variation selection
		echo '<a href="' . esc_url( $link ) . '" class="censkills-atc-overlay button">' . esc_html__( 'Chọn sản phẩm', 'censkills-theme' ) . '</a>';
	} else {
		echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '"
			class="censkills-atc-overlay button ajax_add_to_cart add_to_cart_button"
			data-product_id="' . esc_attr( $product->get_id() ) . '"
			data-product_sku="' . esc_attr( $product->get_sku() ) . '"
			data-quantity="1"
			aria-label="' . esc_attr( sprintf( __( 'Add "%s" to your cart', 'woocommerce' ), $product->get_name() ) ) . '"
			rel="nofollow">
			Thêm vào giỏ
		</a>';
	}


	echo '</div>'; // close wrap

	// Mockup Color Swatches
	echo '<div class="censkills-product-swatches">';
	echo '<span class="swatch bg-black"></span>';
	echo '<span class="swatch bg-gray-light"></span>';
	echo '<span class="swatch bg-gray-dark"></span>';
	echo '</div>';
}

function censkills_woocommerce_loop_setup() {
	echo '<h2 class="censkills-product-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
}

function censkills_woocommerce_loop_price() {
	global $product;
	if ( ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) return;

	echo '<div class="censkills-product-price">' . $product->get_price_html() . '</div>';
}

/**
 * Custom Single Product Additions (Coolmate UI)
 */
/**
 * Premium Single Product Hooks (Tennis Racket / High-End UI)
 */

// Add Ratings above title (Clickable)
add_action('woocommerce_single_product_summary', 'censkills_premium_product_rating', 3);
function censkills_premium_product_rating() {
    global $product;
    $rating_count = $product->get_rating_count();
    $average      = $product->get_average_rating();
    if ( $rating_count > 0 ) {
        echo '<div class="premium-rating clickable" onclick="document.querySelector(\'#reviews\').scrollIntoView({behavior:\'smooth\'})">
            <span class="stars">' . wc_get_rating_html($average, $rating_count) . '</span>
            <span class="count">(' . $rating_count . ' reviews)</span>
        </div>';
    }
}

// Add Selling Points below price
add_action('woocommerce_single_product_summary', 'censkills_premium_selling_points', 15);
function censkills_premium_selling_points() {
    echo '<ul class="premium-selling-points">
        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> High-Performance Control</li>
        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Ultra-Lightweight Carbon Fiber</li>
    </ul>';
}

// Discount Badge logic for summary
add_action('woocommerce_single_product_summary', 'censkills_premium_discount_badge', 12);
function censkills_premium_discount_badge() {
    global $product;
    if ( $product->is_on_sale() ) {
        if ($product->is_type('variable')) {
            $percentages = array();
            $prices = $product->get_variation_prices();
            foreach ($prices['regular_price'] as $key => $regular_price) {
                $sale_price = $prices['sale_price'][$key];
                if ($sale_price < $regular_price) {
                    $percentages[] = round(100 - ($sale_price / $regular_price * 100));
                }
            }
            $percentage = max($percentages);
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $percentage = round(100 - ($sale_price / $regular_price * 100));
        }
        echo '<span class="premium-discount-badge">-' . $percentage . '% OFF</span>';
    }
}

// Add Quick Actions (Buy Now) next to Add to Cart
add_action('woocommerce_after_add_to_cart_button', 'censkills_premium_extra_ctas');
function censkills_premium_extra_ctas() {
    echo '<button type="button" class="button buy-now-button">Buy Now</button>';
}


add_action('wp_footer', 'censkills_variation_swatches', 99);

function censkills_variation_swatches() {
    if ( ! function_exists('is_product') || ! is_product() ) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Variation Swatches ──────────────────────────────────────────────
        const selects = document.querySelectorAll('.variations select');
        selects.forEach(select => {
            const wrapper = document.createElement('div');
            wrapper.className = 'custom-swatches';

            Array.from(select.options).forEach(option => {
                if(option.value === '') return;
                const btn = document.createElement('div');
                btn.className = 'swatch-btn';
                btn.textContent = option.text;
                btn.dataset.value = option.value;
                if (select.value === option.value) btn.classList.add('selected');

                btn.addEventListener('click', function() {
                    wrapper.querySelectorAll('.swatch-btn').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    select.value = option.value;
                    if(typeof jQuery !== 'undefined') jQuery(select).trigger('change');
                });
                wrapper.appendChild(btn);
            });

            select.parentNode.appendChild(wrapper);
        });

        if(typeof jQuery !== 'undefined') {
            jQuery('.variations_form').on('reset_data', function() {
                document.querySelectorAll('.swatch-btn.selected').forEach(b => b.classList.remove('selected'));
            });
        }

        // ── Quantity +/- Buttons ────────────────────────────────────────────
        document.querySelectorAll('.summary .quantity').forEach(qtyWrap => {
            const input = qtyWrap.querySelector('input.qty');
            if (!input) return;

            // Create minus btn
            const minus = document.createElement('span');
            minus.className = 'qty-minus';
            minus.innerHTML = '&minus;';
            minus.addEventListener('click', () => {
                const min = parseInt(input.min) || 1;
                const val = parseInt(input.value) || min;
                if (val > min) { input.value = val - 1; input.dispatchEvent(new Event('change')); }
            });

            // Create plus btn
            const plus = document.createElement('span');
            plus.className = 'qty-plus';
            plus.innerHTML = '&plus;';
            plus.addEventListener('click', () => {
                const max = parseInt(input.max) || Infinity;
                const val = parseInt(input.value) || 1;
                if (val < max) { input.value = val + 1; input.dispatchEvent(new Event('change')); }
            });

            qtyWrap.prepend(minus);
            qtyWrap.appendChild(plus);
        });
    });
    </script>
    <style>
    .variations select { display: none !important; }
    </style>
    <?php
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
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
	) );


	ob_start();
	?>
	<div class="censkills-shop-sidebar w-full">
		<h2 class="filter-main-title">Bộ Lọc</h2>
		<form id="censkills-product-filter" class="censkills-filter-form">
			
			<!-- Brands -->
			<details class="filter-section">
				<summary class="filter-title">Thương hiệu</summary>
				<div class="filter-options brand-options">
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
			</details>

			<!-- Price -->
			<details class="filter-section">
				<summary class="filter-title">Giá</summary>
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
			</details>


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
			'taxonomy' => 'product_brand',
			'field'    => 'slug',
			'terms'    => array_map( 'sanitize_text_field', $_POST['filter_brand'] ),
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
