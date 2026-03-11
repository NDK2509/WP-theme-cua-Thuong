<?php
/**
 * Template Name: Blog Page
 *
 * Displays all posts in a modern card grid with category filter pills and Load More.
 *
 * @package censkills-theme
 */

get_header();

// Collect all post categories for filter pills.
$all_cats = get_categories( array(
	'hide_empty' => true,
	'orderby'    => 'count',
	'order'      => 'DESC',
) );

// Fetch the 3 most recent posts for the featured hero section.
$featured_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
) );

?>

<div class="blog-page">

	<!-- ── Blog Hero Banner ──────────────────────────────────────────── -->
	<div class="blog-hero">
		<div class="container blog-hero-inner">
			<h1 class="blog-hero-title">CenSkills Blog</h1>
			<p class="blog-hero-subtitle">Kiến thức thể thao · Phong cách sống · Dinh dưỡng</p>
		</div>
	</div>

	<!-- ── Featured Posts (top 3) ────────────────────────────────────── -->
	<?php if ( $featured_query->have_posts() ) : ?>
	<section class="blog-featured-section">
		<div class="container">
			<h2 class="blog-section-title">Bài viết nổi bật</h2>
			<div class="blog-featured-grid">
				<?php
				$i = 0;
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					$thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					$cats  = get_the_category();
					$cat   = ! empty( $cats ) ? $cats[0] : null;
					$is_big = ( $i === 0 );
				?>
				<article class="blog-featured-card <?php echo $is_big ? 'is-big' : ''; ?>">
					<a href="<?php the_permalink(); ?>" class="blog-card-img-wrap" tabindex="-1" aria-hidden="true">
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
						<h3 class="blog-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="blog-card-meta">
							<span class="blog-card-date">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
								<?php echo get_the_date( 'd/m/Y' ); ?>
							</span>
						</div>
						<?php if ( $is_big ) : ?>
							<p class="blog-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" class="blog-card-readmore">Đọc ngay →</a>
					</div>
				</article>
				<?php
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── Category Filter Pills + Post Grid ─────────────────────────── -->
	<section class="blog-grid-section">
		<div class="container">
			<h2 class="blog-section-title">Tất cả bài viết</h2>

			<!-- Filter pills -->
			<div class="blog-filter-pills" id="blog-filter-pills">
				<button class="blog-pill is-active" data-cat="">Tất cả</button>
				<?php foreach ( $all_cats as $cat ) : ?>
					<button class="blog-pill" data-cat="<?php echo esc_attr( $cat->term_id ); ?>">
						<?php echo esc_html( $cat->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- Posts grid (initially loaded server-side) -->
			<div class="blog-posts-grid" id="blog-posts-grid">
				<?php
				$paged = 1;
				$posts_per_page = 9;
				$grid_query = new WP_Query( array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
				) );

				$total_posts = $grid_query->found_posts;

				while ( $grid_query->have_posts() ) :
					$grid_query->the_post();
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
				?>
			</div>

			<!-- Load More -->
			<?php if ( $total_posts > $posts_per_page ) : ?>
			<div class="blog-load-more-wrap" id="blog-load-more-wrap">
				<div class="blog-load-more-info">
					Hiển thị <span id="blog-shown"><?php echo min( $posts_per_page, $total_posts ); ?></span>
					/ <?php echo esc_html( $total_posts ); ?> bài viết
				</div>
				<button class="blog-load-more-btn" id="blog-load-more-btn"
					data-page="1"
					data-per-page="<?php echo esc_attr( $posts_per_page ); ?>"
					data-total="<?php echo esc_attr( $total_posts ); ?>"
					data-cat="">
					Xem thêm
				</button>
			</div>
			<?php endif; ?>

		</div>
	</section>

</div><!-- .blog-page -->

<script>
(function () {
	const grid       = document.getElementById('blog-posts-grid');
	const loadMoreBtn = document.getElementById('blog-load-more-btn');
	const shownEl    = document.getElementById('blog-shown');
	const wrapEl     = document.getElementById('blog-load-more-wrap');
	const pills      = document.querySelectorAll('.blog-pill');
	const ajaxUrl    = '<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>';

	let currentCat  = '';
	let currentPage = 1;
	let isLoading   = false;

	// ── Category filter
	pills.forEach(function (pill) {
		pill.addEventListener('click', function () {
			pills.forEach(p => p.classList.remove('is-active'));
			pill.classList.add('is-active');
			currentCat  = pill.dataset.cat;
			currentPage = 1;

			// Reset grid and fetch first page
			grid.innerHTML = '<div class="blog-loading">Đang tải...</div>';
			if (loadMoreBtn) loadMoreBtn.dataset.cat = currentCat;
			fetchPosts(true);
		});
	});

	// ── Load More
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function () {
			if (isLoading) return;
			currentPage++;
			fetchPosts(false);
		});
	}

	function fetchPosts(reset) {
		if (isLoading) return;
		isLoading = true;
		if (loadMoreBtn) loadMoreBtn.textContent = 'Đang tải...';

		const data = new URLSearchParams();
		data.append('action', 'censkills_blog_load_more');
		data.append('page',   currentPage);
		data.append('per_page', loadMoreBtn ? loadMoreBtn.dataset.perPage : 9);
		data.append('cat',    currentCat);

		fetch(ajaxUrl, { method: 'POST', body: data })
			.then(r => r.json())
			.then(function (res) {
				if (res.success) {
					if (reset) {
						grid.innerHTML = res.data.html;
					} else {
						grid.insertAdjacentHTML('beforeend', res.data.html);
					}
					const shown  = res.data.shown;
					const total  = res.data.total;
					if (shownEl) shownEl.textContent = shown;
					if (wrapEl) {
						wrapEl.style.display = shown < total ? '' : 'none';
					}
				}
			})
			.finally(function () {
				isLoading = false;
				if (loadMoreBtn) loadMoreBtn.textContent = 'Xem thêm';
			});
	}
})();
</script>

<?php get_footer(); ?>
