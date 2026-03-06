<?php
/**
 * The template for the homepage
 *
 * @package censkills-theme
 */

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner Slider -->
	<section class="hero-swiper-section relative">
		<div class="swiper hero-swiper">
			<div class="swiper-wrapper">
				<?php if ( is_active_sidebar( 'front-page-banners' ) ) : ?>
					<?php dynamic_sidebar( 'front-page-banners' ); ?>
				<?php else : ?>
					<div class="swiper-slide hero-section">
						<div class="hero-image" style="background-image: url('https://placehold.co/1920x800/000000/ffffff?text=CenSkills+Banner');">
							<!-- A real Coolmate hero would use a slider like Swiper.js, here we use a static hero block -->
						</div>
						<div class="hero-content text-center">
							<h2 class="hero-title text-white">THƯƠNG HIỆU THỜI TRANG CHẤT LƯỢNG</h2>
							<p class="hero-subtitle text-white">Trải nghiệm mua sắm tuyệt vời cùng CenSkills. Sản xuất tại Việt Nam.</p>
							<div class="flex justify-center gap-md mt-lg">
								<a href="#" class="btn btn-primary" style="background-color: white; color: black;">Mua Ngay</a>
								<a href="#" class="btn btn-outline" style="border-color: white; color: white;">Khám Phá</a>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<!-- If we need pagination -->
			<div class="swiper-pagination"></div>

			<!-- If we need navigation buttons -->
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
	</section>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			if (typeof Swiper !== 'undefined') {
				const heroSwiper = new Swiper('.hero-swiper', {
					loop: true,
					autoplay: {
						delay: 3000,
						disableOnInteraction: false,
					},
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
					},
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
				});
			}
		});
	</script>

	<div class="container pb-xxl">

		<!-- Categories Grid -->
		<section class="section">
			<div class="grid grid-cols-2 lg-grid-cols-4 gap-sm">
				<a href="#" class="category-card">
					<img src="https://placehold.co/600x800/eeeeee/333333?text=Đồ+Thu+Đông" alt="Đồ Thu Đông">
					<div class="category-content">
						<h3 class="category-title text-white">Đồ Thu Đông</h3>
					</div>
				</a>
				<a href="#" class="category-card">
					<img src="https://placehold.co/600x800/dddddd/333333?text=Đồ+Lót+Nam" alt="Đồ Lót Nam">
					<div class="category-content">
						<h3 class="category-title text-white">Đồ Lót Nam</h3>
					</div>
				</a>
				<a href="#" class="category-card">
					<img src="https://placehold.co/600x800/cccccc/333333?text=Đồ+Thể+Thao" alt="Đồ Thể Thao">
					<div class="category-content">
						<h3 class="category-title text-white">Đồ Thể Thao</h3>
					</div>
				</a>
				<a href="#" class="category-card">
					<img src="https://placehold.co/600x800/bbbbbb/333333?text=Áo+Sơ+Mi" alt="Áo Sơ Mi">
					<div class="category-content">
						<h3 class="category-title text-white">Áo Sơ Mi</h3>
					</div>
				</a>
			</div>
		</section>

		<!-- Featured Products -->
		<section class="section text-center">
			<h2 class="section-title">SẢN PHẨM MỚI NHẤT</h2>
			
			<div class="product-grid grid grid-cols-2 lg-grid-cols-4 gap-md text-left">
				<?php for ($i = 1; $i <= 8; $i++) : ?>
				<div class="censkills-product">
					<a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
						<div class="censkills-product-image-wrap">
							<?php if ($i % 3 === 0) : ?>
								<span class="censkills-badge sale-badge">SALE</span>
							<?php else: ?>
								<span class="censkills-badge new-badge">NEW</span>
							<?php endif; ?>
							<img src="https://placehold.co/400x500/e2e2e4/333333?text=Product+<?php echo $i; ?>" alt="Product Name" class="censkills-product-img">
						</div>
					</a>
					<div class="censkills-product-swatches">
						<span class="swatch bg-black"></span>
						<span class="swatch bg-gray-light"></span>
						<span class="swatch bg-gray-dark"></span>
					</div>
					<h2 class="censkills-product-title"><a href="#">Bó chân Essentials Coolmate - Product <?php echo $i; ?></a></h2>
					<div class="censkills-product-price">
						<?php if ($i % 3 === 0) : ?>
							<del>129.000đ</del> <ins>99.000đ</ins>
						<?php else: ?>
							<span>99.000đ</span>
						<?php endif; ?>
					</div>
				</div>
				<?php endfor; ?>
			</div>

			<div class="mt-lg">
				<a href="#" class="btn btn-outline" style="width: 200px; padding: 12px;">Xem Thêm</a>
			</div>
		</section>

		<!-- Info Banner Sections (like in Coolmate) -->
		<section class="section mb-xl">
			<div class="grid grid-cols-1 lg-grid-cols-2 gap-sm">
				<div class="info-banner relative">
					<img src="https://placehold.co/800x400/1a1a1a/ffffff?text=Excool+Collection" alt="Mát Lạnh" class="w-full h-full object-cover">
					<div class="info-content absolute inset-0 flex flex-col justify-center items-start p-lg">
						<h3 class="text-3xl font-bold text-white mb-sm">EXCOOL - MẶC LÀ MÁT</h3>
						<p class="text-white mb-md">Công nghệ làm mát tức thì đến từ CenSkills.</p>
						<a href="#" class="btn btn-outline border-white text-white hover-bg-white hover-text-black">Khám Phá Cảm Giác</a>
					</div>
				</div>
				<div class="info-banner relative">
					<img src="https://placehold.co/800x400/f3f3f3/333333?text=Bảo+Hành+60+Ngày" alt="Bảo Hành" class="w-full h-full object-cover">
					<div class="info-content absolute inset-0 flex flex-col justify-center items-start p-lg">
						<h3 class="text-3xl font-bold text-text mb-sm">NGẠI GÌ KHÔNG THỬ?</h3>
						<p class="text-text mb-md">Đổi trả miễn phí 60 ngày với bất kì lý do gì.</p>
						<a href="#" class="btn btn-primary">Tìm Hiểu Ngay</a>
					</div>
				</div>
			</div>
		</section>

	</div><!-- .container -->

</main><!-- #main -->

<?php
get_footer();
