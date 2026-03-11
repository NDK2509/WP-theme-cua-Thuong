<?php
/**
 * The header for our theme
 *
 * @package censkills-theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<!-- Top Announcement Bar -->
	<div class="top-bar">
		<div class="container flex justify-between items-center text-sm font-medium">
			<span>Đổi trả miễn phí trong 60 ngày - Miễn phí vận chuyển đơn hàng trên 200k</span>
			<div class="top-bar-nav flex items-center gap-sm">
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="top-bar-btn">Cửa hàng</a>
				<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="top-bar-btn">Blog</a>
				<a href="<?php echo esc_url( home_url( '/cskh' ) ); ?>" class="top-bar-btn">CSKH</a>
			</div>
		</div>
	</div>

	<!-- Main Navigation Header -->
	<header id="masthead" class="site-header sticky-header shadow-sm">
		<div class="container flex justify-between items-center header-inner">
			
			<!-- Logo -->
			<div class="site-branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo-link">
					<span class="logo-text">CenSkills</span>
				</a>
			</div>
			
			<!-- Primary Menu -->
			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'nav-menu flex gap-md',
						'container'      => false,
						'link_before'    => '',
						'link_after'     => '',
						'fallback_cb'    => function() {
							if ( current_user_can( 'manage_options' ) ) {
								echo '<ul class="nav-menu flex gap-md"><li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="nav-link">Set up the Primary Menu</a></li></ul>';
							}
						},
					)
				);
				?>
			</nav>
			
			<!-- Header Icons -->
			<div class="header-icons flex items-center gap-sm">
				<button class="icon-btn search-toggle" aria-label="Tìm kiếm">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				</button>
				<?php $account_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : '#'; ?>
				<?php 
				$cart_url   = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '#';
				$cart_count = class_exists( 'WooCommerce' ) && ! is_null( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
				?>
				<a href="<?php echo esc_url( $cart_url ); ?>" class="icon-btn cart-link" aria-label="Giỏ hàng">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
					<span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
				</a>
				<a href="<?php echo esc_url( $account_url ); ?>" class="icon-btn account-link" aria-label="Tài khoản">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
				</a>
			</div>
			
		</div>

		<!-- Product Search Form (hidden by default) -->
		<div class="header-search-form container" style="display: none; padding-bottom: var(--spacing-sm); position: relative;">
			<form role="search" method="get" class="search-form flex gap-sm" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product" />
				<input type="search" class="search-field w-full" placeholder="Tìm kiếm sản phẩm..." value="<?php echo get_search_query(); ?>" name="s" required style="padding: 10px; border: 1px solid var(--color-border); border-radius: var(--border-radius-sm); outline: none;" autocomplete="off" />
				<button type="submit" class="search-submit btn btn-primary" style="white-space: nowrap; padding: 10px 20px;">Tìm Kiếm</button>
			</form>
			<!-- Suggestions Dropdown -->
			<div id="search-suggestions" class="search-suggestions-container" style="display: none;"></div>
		</div>

		<script>
			// Make ajaxurl available to our frontend script
			const censkills_ajax = {
				ajaxurl: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>'
			};

			document.addEventListener('DOMContentLoaded', function() {
				const searchToggle = document.querySelector('.search-toggle');
				const searchForm = document.querySelector('.header-search-form');
				const searchField = document.querySelector('.search-field');
				const suggestionsContainer = document.getElementById('search-suggestions');
				let debounceTimer;

				if (searchToggle && searchForm && searchField && suggestionsContainer) {
					// Toggle Search Bar
					searchToggle.addEventListener('click', function(e) {
						e.preventDefault();
						if (searchForm.style.display === 'none' || searchForm.style.display === '') {
							searchForm.style.display = 'block';
							searchField.focus();
						} else {
							searchForm.style.display = 'none';
							suggestionsContainer.style.display = 'none';
						}
					});

					// Close when clicking outside
					document.addEventListener('click', function(event) {
						if (!searchToggle.contains(event.target) && !searchForm.contains(event.target)) {
							searchForm.style.display = 'none';
							suggestionsContainer.style.display = 'none';
						}
					});

					// AJAX Search logic with 1s debounce
					searchField.addEventListener('input', function() {
						const term = this.value.trim();
						
						clearTimeout(debounceTimer);
						
						if (term.length < 2) {
							suggestionsContainer.style.display = 'none';
							suggestionsContainer.innerHTML = '';
							return;
						}

						// Show simple loading state (optional)
						suggestionsContainer.style.display = 'block';
						suggestionsContainer.innerHTML = '<div class="suggestion-loading p-sm text-center text-sm text-text-light">Đang tìm kiếm...</div>';

						// 1-second delay (1000ms)
						debounceTimer = setTimeout(function() {
							const data = new URLSearchParams();
							data.append('action', 'censkills_ajax_search');
							data.append('term', term);

							fetch(typeof censkills_ajax !== 'undefined' ? censkills_ajax.ajaxurl : '/wp-admin/admin-ajax.php', {
								method: 'POST',
								body: data
							})
							.then(res => res.json())
							.then(response => {
								if (response.success && response.data.length > 0) {
									let html = '';
									response.data.forEach(function(product) {
										html += `
											<a href="${product.url}" class="suggestion-item flex items-center gap-sm p-sm">
												<div class="suggestion-img">
													<img src="${product.image}" alt="${product.title}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
												</div>
												<div class="suggestion-info flex-col justify-center">
													<span class="suggestion-title font-medium text-sm text-text">${product.title}</span>
												</div>
											</a>
										`;
									});
									
									// Append "View all results" link
									const searchUrl = '<?php echo esc_url( home_url( '/' ) ); ?>?s=' + encodeURIComponent(term) + '&post_type=product';
									html += `
										<a href="${searchUrl}" class="suggestion-item view-all block text-center p-sm text-sm text-primary font-medium hover-bg-gray">
											Xem tất cả kết quả cho "${term}"
										</a>
									`;
									
									suggestionsContainer.innerHTML = html;
								} else {
									suggestionsContainer.innerHTML = '<div class="p-sm text-center text-sm text-text-light">Không tìm thấy sản phẩm nào.</div>';
								}
							})
							.catch(() => {
								suggestionsContainer.innerHTML = '<div class="p-sm text-center text-sm text-red">Đã có lỗi xảy ra.</div>';
							});
						}, 1000);
					});
				}
			});
		</script>
	</header><!-- #masthead -->
	
	<!-- Main Content Area -->
	<div id="content" class="site-content">
