<?php
/**
 * Theme activation functions
 *
 * @package censkills-theme
 */

/**
 * Helper: Get a page ID by title using WP_Query (replaces deprecated get_page_by_title).
 *
 * @param string $title The page title to search for.
 * @return int|null Page ID or null if not found.
 */
function censkills_get_page_id_by_title( $title ) {
	$query = new WP_Query(
		array(
			'post_type'              => 'page',
			'title'                  => $title,
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( $query->have_posts() ) {
		return $query->posts[0]->ID;
	}

	return null;
}

/**
 * Initialize default pages and categories on theme activation.
 */
function censkills_theme_activation_setup() {
	$created_page_ids = array();

	// Initialize WooCommerce product categories and their pages
	if ( class_exists( 'WooCommerce' ) ) {
		$product_categories = array(
			'Bơi Lội',
			'Bóng Đá',
			'Cầu Lông',
			'Gym',
			'Tennis',
		);

		foreach ( $product_categories as $cat_name ) {
			$slug = sanitize_title( $cat_name );

			if ( ! term_exists( $cat_name, 'product_cat' ) ) {
				wp_insert_term(
					$cat_name,
					'product_cat',
					array(
						'slug' => $slug,
					)
				);
			}

			// Create a page for this category if it doesn't exist
			$existing_id = censkills_get_page_id_by_title( $cat_name );
			if ( ! $existing_id ) {
				$page_data = array(
					'post_title'   => $cat_name,
					'post_content' => '[censkills_products category="' . $slug . '"]',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'page',
				);
				$existing_id = wp_insert_post( $page_data );
			}

			$created_page_ids[ $cat_name ] = $existing_id;
		}
	}

	// Initialize other default pages
	$default_pages = array(
		'Trang chủ' => '',
		'Giới thiệu' => 'Giới thiệu về chúng tôi...',
		'Liên hệ'    => 'Thông tin liên hệ...',
		'Blog'    => '',
	);

	foreach ( $default_pages as $page_title => $page_content ) {
		$existing_id = censkills_get_page_id_by_title( $page_title );
		if ( ! $existing_id ) {
			$page_data = array(
				'post_title'   => $page_title,
				'post_content' => $page_content,
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'page',
			);
			$existing_id = wp_insert_post( $page_data );
		}
		$created_page_ids[ $page_title ] = $existing_id;
	}

	// Set static front page and blog page
	if ( ! empty( $created_page_ids['Trang chủ'] ) && ! empty( $created_page_ids['Blog'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $created_page_ids['Trang chủ'] );
		update_option( 'page_for_posts', $created_page_ids['Tin tức'] );
	}

	// Create default Primary Menu if it doesn't exist
	$menu_name = 'Primary Menu';
	$menu_id   = null;
	$existing_menu = wp_get_nav_menu_object( $menu_name );

	if ( ! $existing_menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $existing_menu->term_id;
	}

	if ( $menu_id && ! is_wp_error( $menu_id ) ) {
		$existing_items = wp_get_nav_menu_items( $menu_id );

		// Only add items if menu is empty (fresh)
		if ( empty( $existing_items ) ) {
			$sport_categories = array( 'Bơi Lội', 'Bóng Đá', 'Cầu Lông', 'Gym', 'Tennis' );
			$menu_order = 1;

			foreach ( $sport_categories as $cat_name ) {
				if ( ! empty( $created_page_ids[ $cat_name ] ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $cat_name,
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $created_page_ids[ $cat_name ],
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
							'menu-item-position'  => $menu_order++,
						)
					);
				}
			}
		}

		// Assign the menu to the primary location
		$locations = get_theme_mod( 'nav_menu_locations' );
		if ( empty( $locations['primary'] ) ) {
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}
add_action( 'after_switch_theme', 'censkills_theme_activation_setup' );
