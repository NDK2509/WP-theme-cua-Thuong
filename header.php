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
		<div class="container text-center text-sm font-medium">
			Đổi trả miễn phí trong 60 ngày - Miễn phí vận chuyển đơn hàng trên 200k
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
	</header><!-- #masthead -->
	
	<!-- Main Content Area -->
	<div id="content" class="site-content">
