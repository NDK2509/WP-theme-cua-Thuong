	<div class="container pb-lg">

		<!-- Categories Slider -->
		<section class="section">
			<div class="censkills-categories-slider" style="position: relative;">
				<div class="censkills-categories-swiper swiper">
					<div class="swiper-wrapper">
						<?php
						$categories = get_terms( array(
							'taxonomy'   => 'product_cat',
							'hide_empty' => false,
							'number'     => 0,
							'exclude'    => array( get_option( 'default_product_cat' ) ),
						) );

						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
							foreach ( $categories as $category ) :
								$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
								$image_url    = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : wc_placeholder_img_src();
								$term_link    = get_term_link( $category );
						?>
						<a href="<?php echo esc_url( $term_link ); ?>" class="swiper-slide category-slide-card">
							<div class="category-slide-img">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
							</div>	
							<span class="category-slide-name"><?php echo esc_html( $category->name ); ?></span>
						</a>
						<?php
							endforeach;
						else :
							for ( $i = 1; $i <= 6; $i++ ) :
						?>
						<a href="#" class="swiper-slide category-slide-card">
							<div class="category-slide-img">
								<img src="https://placehold.co/300x400/eeeeee/333333?text=<?php echo $i; ?>" alt="Category <?php echo $i; ?>">
							</div>
							<span class="category-slide-name">Bộ Sưu Tập <?php echo $i; ?></span>
						</a>
						<?php
							endfor;
						endif;
						?>
					</div>
				</div>
				<!-- Navigation Arrows -->
				<div class="censkills-cat-prev"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></div>
				<div class="censkills-cat-next"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
			</div>

			<style>
				.censkills-categories-slider {
					position: relative;
					padding: 0 30px;
				}
				.censkills-categories-swiper {
					overflow: hidden;
				}
				.category-slide-card {
					display: flex;
					flex-direction: column;
					align-items: center;
					text-decoration: none;
					color: inherit;
					gap: 10px;
				}
				.category-slide-img {
					width: 100%;
					aspect-ratio: 3/4;
					border-radius: 12px;
					overflow: hidden;
					background: #f0f0f0;
				}
				.category-slide-img img {
					width: 100%;
					height: 100%;
					object-fit: cover;
					transition: transform 0.3s ease;
				}
				.category-slide-card:hover .category-slide-img img {
					transform: scale(1.05);
				}
				.category-slide-name {
					font-size: 13px;
					font-weight: 600;
					text-transform: uppercase;
					letter-spacing: 0.5px;
					text-align: center;
				}
				.censkills-cat-prev,
				.censkills-cat-next {
					position: absolute;
					top: 40%;
					transform: translateY(-50%);
					width: 36px;
					height: 36px;
					border-radius: 50%;
					background: rgba(255,255,255,0.9);
					box-shadow: 0 2px 8px rgba(0,0,0,0.15);
					display: flex;
					align-items: center;
					justify-content: center;
					cursor: pointer;
					z-index: 10;
					transition: background 0.2s;
				}
				.censkills-cat-prev:hover,
				.censkills-cat-next:hover {
					background: #fff;
				}
				.censkills-cat-prev { left: 0; }
				.censkills-cat-next { right: 0; }
			</style>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					if (typeof Swiper !== 'undefined') {
						new Swiper('.censkills-categories-swiper', {
							slidesPerView: 2,
							spaceBetween: 12,
							loop: false,
							navigation: {
								nextEl: '.censkills-cat-next',
								prevEl: '.censkills-cat-prev',
							},
							breakpoints: {
								640: {
									slidesPerView: 2,
									spaceBetween: 15,
								},
								1024: {
									slidesPerView: 4,
									spaceBetween: 20,
								}
							}
						});
					}
				});
			</script>
		</section>

		<!-- Featured Products -->
		<section class="section text-center">
			<h2 class="section-title">SẢN PHẨM MỚI NHẤT</h2>
			
			<div class="product-grid grid grid-cols-2 lg-grid-cols-4 gap-md text-left">
				<?php 
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 8,
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC'
				);
				$loop = new WP_Query( $args );

				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
						global $product;
						$image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: wc_placeholder_img_src();
						get_template_part( 'parts/product-card' ); 
					endwhile; 
					wp_reset_postdata();
				else : 
					// Fallback to static if no products discovered
					for ($i = 1; $i <= 8; $i++) : 
				?>
				<div class="censkills-product">
					<div class="censkills-product-image-wrap">
						<a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
							<?php if ($i % 3 === 0) : ?>
								<span class="censkills-badge sale-badge">SALE</span>
							<?php else: ?>
								<span class="censkills-badge new-badge">NEW</span>
							<?php endif; ?>
							<img src="https://placehold.co/400x500/e2e2e4/333333?text=Product+<?php echo $i; ?>" alt="Product Name" class="censkills-product-img">
						</a>
						<div class="hover-add-to-cart">
							<a href="#" class="button add_to_cart_button">Add to Cart</a>
						</div>
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
				<?php 
					endfor; 
				endif; 
				?>
			</div>

			<div class="mt-lg">
				<button id="censkills-load-more" class="btn btn-outline" data-page="1" style="width: 200px; padding: 12px;">Xem Thêm</button>
			</div>
		</section>

	</div><!-- .container -->

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const loadMoreBtn = document.getElementById('censkills-load-more');
		const productGrid = document.querySelector('.product-grid');
		
		if (loadMoreBtn && productGrid) {
			loadMoreBtn.addEventListener('click', function(e) {
				e.preventDefault();
				
				// Keep track of the page
				let currentPage = parseInt(loadMoreBtn.getAttribute('data-page'));
				let nextPage = currentPage + 1;
				
				// Set loading state
				const originalText = loadMoreBtn.innerText;
				loadMoreBtn.innerText = 'Đang tải...';
				loadMoreBtn.disabled = true;

				// Prepare AJAX data
				const data = new URLSearchParams();
				data.append('action', 'censkills_load_more_products');
				data.append('page', nextPage);

				// Send the request
				fetch(typeof censkills_ajax !== 'undefined' ? censkills_ajax.ajaxurl : '/wp-admin/admin-ajax.php', {
					method: 'POST',
					body: data
				})
				.then(res => res.json())
				.then(response => {
					if (response.success && response.data.html) {
						// Append new products
						productGrid.insertAdjacentHTML('beforeend', response.data.html);
						
						// Re-trigger WooCommerce inject script if present (since elements are new)
						if (typeof injectAtcButtons === 'function') {
							injectAtcButtons();
						}
						
						// Update page attribute
						loadMoreBtn.setAttribute('data-page', nextPage);
						
						// Hide button if no more products
						if (!response.data.has_more) {
							loadMoreBtn.style.display = 'none';
						} else {
							loadMoreBtn.innerText = originalText;
							loadMoreBtn.disabled = false;
						}
					} else {
						// Either no more or empty response
						loadMoreBtn.style.display = 'none';
					}
				})
				.catch(err => {
					console.error(err);
					loadMoreBtn.innerText = 'Lỗi, thử lại';
					loadMoreBtn.disabled = false;
				});
			});
		}
	});
	</script>
