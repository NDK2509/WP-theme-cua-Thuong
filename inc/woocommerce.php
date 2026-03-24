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
			data-product_title="' . esc_attr( $product->get_name() ) . '"
			data-quantity="1"
			aria-label="' . esc_attr( sprintf( __( 'Add "%s" to your cart', 'woocommerce' ), $product->get_name() ) ) . '"
			rel="nofollow">
			Thêm vào giỏ
		</a>';
	}


	echo '</div>'; // close wrap
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
 * Detect product gender for size suggestions (men, women)
 */
function censkills_get_product_gender( $product_id ) {
	$tags = get_the_terms( $product_id, 'product_tag' );
	$cats = get_the_terms( $product_id, 'product_cat' );
	$all_slugs = array();

	if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
		foreach ( $tags as $tag ) $all_slugs[] = $tag->slug;
	}
	if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
		foreach ( $cats as $cat ) $all_slugs[] = $cat->slug;
	}

	foreach ( $all_slugs as $slug ) {
		if ( in_array( $slug, array( 'women', 'nu', 'female' ) ) ) return 'women';
		if ( in_array( $slug, array( 'men', 'nam', 'male' ) ) ) return 'men';
	}
	return 'men'; // default to men
}

/**
 * Detect product type for size suggestions (shirt, shoes, bottom)
 */
function censkills_get_product_type( $product_id ) {
	$tags = get_the_terms( $product_id, 'product_tag' );
	$cats = get_the_terms( $product_id, 'product_cat' );
	$all_slugs = array();

	if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
		foreach ( $tags as $tag ) $all_slugs[] = $tag->slug;
	}
	if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
		foreach ( $cats as $cat ) $all_slugs[] = $cat->slug;
	}

	foreach ( $all_slugs as $slug ) {
		if ( in_array( $slug, array( 'shirt', 'ao', 't-shirt', 'polo' ) ) ) return 'shirt';
		if ( in_array( $slug, array( 'shoes', 'giay', 'sneakers', 'giay-the-thao' ) ) ) return 'shoes';
		if ( in_array( $slug, array( 'quan', 'pants', 'trousers', 'shorts' ) ) ) return 'bottom';
		if ( in_array( $slug, array( 'bra', 'quan-lot', 'undergarment', 'ao-lot' ) ) ) return 'bra';
	}
	return 'default';
}

// Add Size Guide Button
add_action('woocommerce_single_product_summary', 'censkills_add_size_guide_button', 25);
function censkills_add_size_guide_button() {
	global $product;
	if ( ! $product->is_type( 'variable' ) ) return; 
	
	echo '<div class="size-guide-trigger-wrap">';
	echo '<button type="button" class="btn-size-guide" id="open-size-modal">';
	echo '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20M7 7l10 10M17 7L7 10"/></svg>';
	echo 'Gợi ý chọn size';
	echo '</button>';
	echo '</div>';
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

            // Move 'Clear' button to the end
            const resetBtn = select.parentNode.querySelector('.reset_variations');
            if (resetBtn) {
                select.parentNode.appendChild(resetBtn);
            }
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

add_action('wp_footer', 'censkills_add_to_cart_toast_assets', 99);
function censkills_add_to_cart_toast_assets() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Toast Notification (PHP Trigger) ─────────────────────────────────
        let toastNode = document.querySelector('.censkills-added-toast');
        if (toastNode) {
            let parentNotice = toastNode.closest('.woocommerce-message');
            if (parentNotice) {
                parentNotice.style.display = 'none';
            }
            showCustomToast(toastNode.dataset.name);
        }

        // ── Toast Notification (AJAX Trigger) ─────────────────────────────────
        if (typeof jQuery !== 'undefined') {
            // Handle standard archive AJAX add to cart
            jQuery(document).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                let name = 'Sản phẩm';
                if ($button && $button.data('product_title')) {
                    name = $button.data('product_title');
                } else {
                    let titleEl = document.querySelector('.product_title');
                    if(titleEl) name = titleEl.innerText;
                }
                showCustomToast(name);
            });

            // Handle Single Product Page Add to Cart via AJAX to prevent page reload
            jQuery(document).on('submit', 'form.cart', function(e) {
                e.preventDefault();
                var $form = jQuery(this);
                // Use the main add to cart button to find the value
                var $btn = $form.find('.single_add_to_cart_button');
                var formData = new FormData($form[0]);
                
                // Append the submit button's value so WooCommerce knows which action to take
                let btnName = $btn.attr('name') || 'add-to-cart';
                let btnVal = $btn.val() || $form.find('input[name="add-to-cart"]').val();
                if(btnVal) {
                    formData.append(btnName, btnVal);
                }

                $btn.css('opacity', '0.5');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    $btn.css('opacity', '1');
                    
                    // Show the toast
                    let titleEl = document.querySelector('.product_title');
                    let name = titleEl ? titleEl.innerText : 'Sản phẩm';
                    showCustomToast(name);

                    // Trigger WooCommerce to refresh cart fragments
                    jQuery(document.body).trigger('wc_fragment_refresh');
                })
                .catch(error => {
                    $btn.css('opacity', '1');
                    console.error('Add to cart error:', error);
                });
            });
        }
    });

    function showCustomToast(name) {
        // Remove existing toast if any
        let existing = document.querySelector('.censkills-toast-popup');
        if (existing) existing.remove();

        let toast = document.createElement('div');
        toast.className = 'censkills-toast-popup';
        toast.innerHTML = '<div class="censkills-toast-content">Bạn đã thêm <strong>' + name + '</strong> vào giỏ hàng</div><button class="censkills-toast-close">&times;</button>';
        
        document.body.appendChild(toast);
        
        // Close event
        toast.querySelector('.censkills-toast-close').addEventListener('click', () => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });

        // Auto hide
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            if(document.body.contains(toast)) {
                toast.classList.remove('show');
                setTimeout(() => {
                    if(document.body.contains(toast)) toast.remove();
                }, 300);
            }
        }, 2000);
    }
    </script>
    <style>
    /* Toast CSS */
    .censkills-toast-popup {
        position: fixed;
        top: 96px;
        right: 24px;
        background: var(--color-bg-white, #fff);
        color: var(--color-text, #111);
        padding: 16px 20px;
        border-radius: var(--radius-md, 8px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-left: 4px solid var(--color-primary, #2f5acf);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 16px;
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        max-width: 400px;
    }
    .censkills-toast-popup.show {
        transform: translateY(0);
        opacity: 1;
    }
    .censkills-toast-content {
        font-size: 14px;
        line-height: 1.4;
    }
    .censkills-toast-close {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--color-text-light, #555);
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .censkills-toast-close:hover {
        color: var(--color-text, #111);
    }
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
 * Custom Add to Cart Message for Toast
 */
add_filter( 'wc_add_to_cart_message_html', 'censkills_custom_add_to_cart_toast', 10, 2 );
function censkills_custom_add_to_cart_toast( $message, $products ) {
    $titles = array();
    // $products is sometimes an array of product IDs or product_id => qty
    if ( is_array( $products ) ) {
        foreach ( $products as $product_id => $qty ) {
            $titles[] = get_the_title( $product_id );
        }
    } else {
        $titles[] = get_the_title( $products );
    }
    $product_name = implode( ', ', $titles );
    // Return an invisible trigger div that the JS picks up.
    return '<span class="censkills-added-toast hidden" data-name="' . esc_attr( $product_name ) . '"></span>';
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

add_action('wp_footer', 'censkills_size_suggestion_modal_assets', 100);
function censkills_size_suggestion_modal_assets() {
    if ( ! function_exists('is_product') || ! is_product() ) return;
    $product_id = get_the_ID();
    $type = censkills_get_product_type($product_id);
    $gender = censkills_get_product_gender($product_id);
    ?>
    <div id="censkills-size-modal" class="censkills-modal">
        <div class="censkills-modal-overlay"></div>
        <div class="censkills-modal-content">
            <button class="censkills-modal-close" aria-label="Close">&times;</button>
            
            <div class="size-modal-inner">
                <h3 class="modal-title">Gợi ý kích thước cho bạn</h3>
                <p class="modal-subtitle">Nhập thông tin để chúng tôi tìm size phù hợp nhất</p>

                <?php if ( $type === 'shoes' ) : ?>
                    <!-- Shoes Guide -->
                    <div class="size-calculator shoes-calc">
						<div class="input-row">
							<div class="input-group">
								<label>Chiều dài bàn chân (cm)</label>
								<input type="number" id="foot-length" placeholder="Ví dụ: 25.5">
							</div>
						</div>
                        <button class="suggest-btn" id="calc-size-shoes">Xem gợi ý</button>
                        <div id="size-result" class="size-result-box"></div>
                    </div>
                <?php elseif ( $type === 'bra' ) : ?>
                    <!-- Bra Calculator -->
                    <div class="size-calculator bra-calc">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Vòng chân ngực (cm)</label>
                                <input type="number" id="underbust" placeholder="75">
                            </div>
                            <div class="input-group">
                                <label>Vòng đỉnh ngực (cm)</label>
                                <input type="number" id="overbust" placeholder="88">
                            </div>
                        </div>
                        <button class="suggest-btn" id="calc-size-bra">Xem gợi ý</button>
                        <div id="size-result" class="size-result-box"></div>
                    </div>
                <?php else : ?>
                    <!-- Shirt/Pants Calculator -->
                    <div class="size-calculator apparel-calc" data-gender="<?php echo esc_attr($gender); ?>">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Chiều cao (cm)</label>
                                <input type="number" id="user-height" placeholder="170">
                            </div>
                            <div class="input-group">
                                <label>Cân nặng (kg)</label>
                                <input type="number" id="user-weight" placeholder="65">
                            </div>
                        </div>
                        <button class="suggest-btn" id="calc-size-apparel">Xem gợi ý</button>
                        <div id="size-result" class="size-result-box"></div>
                    </div>
                <?php endif; ?>

                <div class="size-chart-section">
                    <h4 class="section-label">Bảng size tham khảo</h4>
                    <div class="chart-scroll">
                    <?php if ( $type === 'shoes' ) : ?>
                        <table class="size-table">
                            <thead><tr><th>Size (EU)</th><th>38</th><th>39</th><th>40</th><th>41</th><th>42</th><th>43</th></tr></thead>
                            <tbody><tr><td>Chiều dài (cm)</td><td>23.5</td><td>24.5</td><td>25.0</td><td>26.0</td><td>26.5</td><td>27.5</td></tr></tbody>
                        </table>
                    <?php elseif ( $type === 'bra' ) : ?>
                        <table class="size-table">
                            <thead><tr><th>Band</th><th>70</th><th>75</th><th>80</th><th>85</th><th>90</th></tr></thead>
                            <tbody><tr><td>Chân ngực</td><td>68-72</td><td>73-77</td><td>78-82</td><td>83-87</td><td>88-92</td></tr></tbody>
                        </table>
                        <table class="size-table" style="margin-top: 10px;">
                            <thead><tr><th>Cup</th><th>A</th><th>B</th><th>C</th><th>D</th></tr></thead>
                            <tbody><tr><td>Chênh lệch</td><td>10-12</td><td>12-14</td><td>15-17</td><td>18-20</td></tr></tbody>
                        </table>
                    <?php else: ?>
                        <?php if ( $gender === 'women' ) : ?>
                            <table class="size-table">
                                <thead><tr><th>Size (Nữ)</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>XXL</th></tr></thead>
                                <tbody>
                                    <tr><td>Chiều cao</td><td>150-155</td><td>155-160</td><td>160-165</td><td>165-170</td><td>170-175</td></tr>
                                    <tr><td>Cân nặng</td><td>40-47kg</td><td>47-53kg</td><td>53-59kg</td><td>59-65kg</td><td>65-72kg</td></tr>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <table class="size-table">
                                <thead><tr><th>Size (Nam)</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>XXL</th></tr></thead>
                                <tbody>
                                    <tr><td>Chiều cao</td><td>160-165</td><td>165-170</td><td>170-175</td><td>175-180</td><td>180-185</td></tr>
                                    <tr><td>Cân nặng</td><td>55-60kg</td><td>60-68kg</td><td>68-76kg</td><td>76-85kg</td><td>85-95kg</td></tr>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('censkills-size-modal');
        const openBtn = document.getElementById('open-size-modal');
        const closeBtn = modal.querySelector('.censkills-modal-close');
        const overlay = modal.querySelector('.censkills-modal-overlay');

        if(openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
        
        const closeModal = () => {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        };

        if(closeBtn) closeBtn.addEventListener('click', closeModal);
        if(overlay) overlay.addEventListener('click', closeModal);

        // Apparel Calculation logic
        const apparelBtn = document.getElementById('calc-size-apparel');
        if(apparelBtn) {
            apparelBtn.addEventListener('click', function() {
                const h = parseInt(document.getElementById('user-height').value);
                const w = parseInt(document.getElementById('user-weight').value);
                const result = document.getElementById('size-result');
                const calcDiv = document.querySelector('.apparel-calc');
                const gender = calcDiv ? calcDiv.dataset.gender : 'men';
                
                if(!h || !w) {
                    result.innerHTML = '<span class="error">Vui lòng nhập đầy đủ thông tin</span>';
                    result.classList.add('show');
                    return;
                }

                let size = "L";
                if(gender === 'women') {
                    if(w <= 47) size = "S";
                    else if(w <= 53) size = "M";
                    else if(w <= 59) size = "L";
                    else if(w <= 65) size = "XL";
                    else size = "XXL";
                } else {
                    if(w <= 60) size = "S";
                    else if(w <= 68) size = "M";
                    else if(w <= 76) size = "L";
                    else if(w <= 85) size = "XL";
                    else size = "XXL";
                }

                result.innerHTML = 'Size phù hợp với bạn là: <strong>' + size + '</strong>';
                result.classList.add('show');
            });
        }

        // Shoes Calculation logic
        const shoesBtn = document.getElementById('calc-size-shoes');
        if(shoesBtn) {
            shoesBtn.addEventListener('click', function() {
                const len = parseFloat(document.getElementById('foot-length').value);
                const result = document.getElementById('size-result');
                
                if(!len) {
                    result.innerHTML = '<span class="error">Vui lòng nhập chiều dài bàn chân</span>';
                    result.classList.add('show');
                    return;
                }

                let size = "40";
                if(len < 24) size = "38";
                else if(len < 25) size = "39";
                else if(len < 25.5) size = "40";
                else if(len < 26.5) size = "41";
                else if(len < 27) size = "42";
                else size = "43";

                result.innerHTML = 'Size phù hợp với bạn là: <strong>' + size + '</strong>';
                result.classList.add('show');
            });
        }

        // Bra Calculation logic
        const braBtn = document.getElementById('calc-size-bra');
        if(braBtn) {
            braBtn.addEventListener('click', function() {
                const u = parseFloat(document.getElementById('underbust').value);
                const o = parseFloat(document.getElementById('overbust').value);
                const result = document.getElementById('size-result');
                
                if(!u || !o) {
                    result.innerHTML = '<span class="error">Vui lòng nhập đầy đủ thông tin</span>';
                    result.classList.add('show');
                    return;
                }

                const diff = o - u;
                let band = "75";
                if(u <= 72) band = "70";
                else if(u <= 77) band = "75";
                else if(u <= 82) band = "80";
                else if(u <= 87) band = "85";
                else band = "90";

                let cup = "A";
                if(diff < 12) cup = "A";
                else if(diff < 15) cup = "B";
                else if(diff < 17) cup = "C";
                else cup = "D";

                result.innerHTML = 'Size phù hợp với bạn là: <strong>' + band + cup + '</strong>';
                result.classList.add('show');
            });
        }
    });
    </script>
    <?php
}
