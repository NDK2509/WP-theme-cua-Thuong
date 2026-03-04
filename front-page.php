<?php
/**
 * The template for the homepage
 *
 * @package censkills-theme
 */

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="hero-section">
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
	</section>

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
				<div class="product-card">
					<a href="#" class="product-image-container relative">
						<img src="https://placehold.co/400x550/f0f0f0/555555?text=Sản+Phẩm" alt="Product Name" class="product-image">
						<!-- Discount Badge Example -->
						<?php if ($i % 3 === 0) : ?>
							<span class="product-badge absolute top-2 left-2">-20%</span>
						<?php endif; ?>
					</a>
					<div class="product-info mt-sm">
						<!-- Colors -->
						<div class="product-colors flex gap-xs mb-xs">
							<span style="background-color: #000; width: 16px; height: 16px; display: inline-block; border-radius: 50%;"></span>
							<span style="background-color: #f0f0f0; width: 16px; height: 16px; display: inline-block; border-radius: 50%; border: 1px solid #ccc;"></span>
							<span style="background-color: #2f5acf; width: 16px; height: 16px; display: inline-block; border-radius: 50%;"></span>
						</div>
						<h3 class="product-title font-medium text-sm"><a href="#">Áo Thun Giữ Nhiệt Nam Cổ Tròn CenSkills</a></h3>
						<div class="product-price mt-xs">
							<span class="price-current font-bold text-lg">299.000đ</span>
							<?php if ($i % 3 === 0) : ?>
								<span class="price-old text-sm text-light line-through ml-xs" style="text-decoration: line-through; color: #888;">399.000đ</span>
							<?php endif; ?>
						</div>
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
