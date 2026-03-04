<?php
/**
 * The template for displaying the footer
 *
 * @package censkills-theme
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container footer-inner">
			<div class="grid grid-cols-1 lg-grid-cols-4 gap-lg">
				
				<!-- Column 1: Brand / Description -->
				<div class="footer-col">
					<h3 class="footer-title">CENSKILLS</h3>
					<p class="footer-text mt-sm">Chúng tôi thiết kế những sản phẩm đơn giản, chất lượng cao dành cho mọi người, đồng hành cùng bạn trên mọi hành trình.</p>
					
					<div class="social-links flex gap-sm mt-md">
						<a href="#" class="social-icon" aria-label="Facebook">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
						</a>
						<a href="#" class="social-icon" aria-label="Instagram">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
						</a>
						<a href="#" class="social-icon" aria-label="YouTube">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
						</a>
					</div>
				</div>

				<!-- Column 2: Links -->
				<div class="footer-col">
					<h3 class="footer-title">VỀ CHÚNG TÔI</h3>
					<ul class="footer-links mt-sm">
						<li><a href="#">Câu chuyện thương hiệu</a></li>
						<li><a href="#">Nhà máy</a></li>
						<li><a href="#">CoolClub</a></li>
						<li><a href="#">Tuyển dụng</a></li>
						<li><a href="#">Blog</a></li>
					</ul>
				</div>

				<!-- Column 3: Policy -->
				<div class="footer-col">
					<h3 class="footer-title">CHÍNH SÁCH</h3>
					<ul class="footer-links mt-sm">
						<li><a href="#">Chính sách đổi trả 60 ngày</a></li>
						<li><a href="#">Chính sách khuyến mãi</a></li>
						<li><a href="#">Chính sách bảo mật</a></li>
						<li><a href="#">Chính sách giao hàng</a></li>
					</ul>
				</div>

				<!-- Column 4: Contact -->
				<div class="footer-col">
					<h3 class="footer-title">CHĂM SÓC KHÁCH HÀNG</h3>
					<p class="footer-text mt-sm">Trải nghiệm mua sắm 100% hài lòng</p>
					<ul class="footer-contact mt-sm">
						<li>
							<span class="block font-medium">Hotline mua hàng:</span>
							<a href="tel:1900xxxx" class="text-xl font-bold text-white">1900.XXXX</a>
						</li>
						<li class="mt-xs">
							<span class="block font-medium">Email:</span>
							<a href="mailto:support@censkills.com">support@censkills.com</a>
						</li>
					</ul>
				</div>

			</div>
		</div><!-- .container -->

		<div class="footer-bottom mt-xl">
			<div class="container flex justify-between items-center text-sm">
				<p>&copy; <?php echo date('Y'); ?> CenSkills. All rights reserved. Tự hào thiết kế tại Việt Nam.</p>
				<div class="payment-methods flex gap-xs">
					<!-- Example payment method icons -->
					<div style="background: white; padding: 2px 8px; border-radius: 4px; color: black; font-weight: bold; font-size: 10px;">VISA</div>
					<div style="background: white; padding: 2px 8px; border-radius: 4px; color: black; font-weight: bold; font-size: 10px;">MoMo</div>
					<div style="background: white; padding: 2px 8px; border-radius: 4px; color: black; font-weight: bold; font-size: 10px;">VNPAY</div>
				</div>
			</div>
		</div>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
