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

// Include Elementor custom widgets if Elementor is active.
if ( did_action( 'elementor/loaded' ) || defined( 'ELEMENTOR_VERSION' ) ) {
	require get_template_directory() . '/inc/elementor-widgets.php';
}

/**
 * AJAX Product Search Handler
 */
function censkills_ajax_search() {
	$search_term = isset( $_REQUEST['term'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['term'] ) ) : '';
	
	if ( empty( $search_term ) ) {
		wp_send_json_success( array() );
	}

	// Filter posts_where to force case and accent insensitivity by using utf8mb4_general_ci collation for the search term if possible
	add_filter('posts_where', function($where, $wp_query) {
		global $wpdb;
		if ($search_term = $wp_query->get('search_prod_title')) {
			$term = $wpdb->esc_like($search_term);
			$where .= " AND {$wpdb->posts}.post_title LIKE '%{$term}%' COLLATE utf8mb4_general_ci";
		}
		return $where;
	}, 10, 2);

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		's'              => $search_term,
		// 'search_prod_title' => $search_term, // We inject Custom query above to force collation. The 's' arg also performs general search.
	);

	$query = new WP_Query( $args );
	$results = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			global $product;
			
			if ( ! $product ) {
				$product = wc_get_product( get_the_ID() );
			}

			$results[] = array(
				'title' => get_the_title(),
				'url'   => get_permalink(),
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ?: wc_placeholder_img_src(),
			);
		}
	}
	wp_reset_postdata();

	wp_send_json_success( $results );
}
add_action( 'wp_ajax_censkills_ajax_search', 'censkills_ajax_search' );
add_action( 'wp_ajax_nopriv_censkills_ajax_search', 'censkills_ajax_search' );

/**
 * Shortcode to display the Front Page Categories & Products Grids
 */
function censkills_front_page_grids_shortcode() {
	ob_start();
	get_template_part( 'parts/front-page', 'content' );
	return ob_get_clean();
}
add_shortcode( 'censkills_front_page_grids', 'censkills_front_page_grids_shortcode' );

/**
 * AJAX: Blog Load More
 * Returns rendered post cards for the Blog page template.
 */
function censkills_blog_load_more() {
	$page     = isset( $_POST['page'] )     ? absint( $_POST['page'] )     : 1;
	$per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 9;
	$cat_id   = isset( $_POST['cat'] )      ? absint( $_POST['cat'] )      : 0;

	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $page + 1, // page arg is 0-indexed from JS
	);

	if ( $cat_id ) {
		$args['cat'] = $cat_id;
	}

	$query  = new WP_Query( $args );
	$total  = $query->found_posts;
	$shown  = min( ( $page ) * $per_page, $total );

	ob_start();
	while ( $query->have_posts() ) :
		$query->the_post();
		$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
		$cats  = get_the_category();
		$cat   = ! empty( $cats ) ? $cats[0] : null;
		?>
		<article class="blog-grid-card">
			<a href="<?php the_permalink(); ?>" class="blog-card-img-wrap" tabindex="-1">
				<?php if ( $thumb ) : ?>
					<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="blog-card-img" loading="lazy">
				<?php else : ?>
					<div class="blog-card-img-placeholder"></div>
				<?php endif; ?>
				<?php if ( $cat ) : ?>
					<span class="blog-card-cat-badge"><?php echo esc_html( $cat->name ); ?></span>
				<?php endif; ?>
			</a>
			<div class="blog-card-body">
				<?php if ( $cat ) : ?>
					<span class="blog-card-cat-label"><?php echo esc_html( $cat->name ); ?></span>
				<?php endif; ?>
				<h3 class="blog-card-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<p class="blog-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
				<div class="blog-card-meta">
					<span class="blog-card-date"><?php echo get_the_date( 'd/m/Y' ); ?></span>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array(
		'html'  => $html,
		'total' => $total,
		'shown' => $shown,
	) );
}
add_action( 'wp_ajax_censkills_blog_load_more',        'censkills_blog_load_more' );
add_action( 'wp_ajax_nopriv_censkills_blog_load_more', 'censkills_blog_load_more' );
