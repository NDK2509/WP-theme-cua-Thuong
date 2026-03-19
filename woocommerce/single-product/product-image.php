<?php
/**
 * Single Product Image — CenSkills Custom (Coolmate-style)
 * Vertical thumbnail strip on left, large main image on right.
 *
 * @package censkills-theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attachment_ids    = $product->get_gallery_image_ids();
$main_image_id     = $product->get_image_id();

// Build ordered list: main image first, then gallery
$all_ids = array_merge( array( $main_image_id ), (array) $attachment_ids );
?>

<div class="censkills-product-gallery" id="censkills-product-gallery">

	<!-- Vertical Thumbnail Strip -->
	<div class="censkills-gallery-thumbs">
		<?php foreach ( $all_ids as $i => $id ) :
			$thumb_src = wp_get_attachment_image_src( $id, 'thumbnail' );
			if ( ! $thumb_src ) continue;
		?>
			<div class="censkills-gallery-thumb <?php echo $i === 0 ? 'is-active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>">
				<img src="<?php echo esc_url( $thumb_src[0] ); ?>" alt="" loading="lazy">
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Main Image Area (Premium Card) -->
	<div class="censkills-gallery-main" id="censkills-main-image-container">
		<?php foreach ( $all_ids as $i => $id ) :
			$full_src = wp_get_attachment_image_src( $id, 'woocommerce_single' );
			if ( ! $full_src ) continue;
		?>
			<div class="censkills-gallery-slide <?php echo $i === 0 ? 'is-active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>">
				<div class="zoom-wrapper">
					<img src="<?php echo esc_url( $full_src[0] ); ?>" 
						 alt="<?php echo esc_attr( get_post_field( 'post_excerpt', $id ) ); ?>" 
						 loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
						 class="main-img">
				</div>
			</div>
		<?php endforeach; ?>

		<!-- Prev / Next arrows -->
		<?php if ( count( $all_ids ) > 1 ) : ?>
		<button class="censkills-gallery-arrow prev" aria-label="Ảnh trước">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
		</button>
		<button class="censkills-gallery-arrow next" aria-label="Ảnh sau">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
		</button>
		<?php endif; ?>
	</div>

</div>

<style>
.zoom-wrapper {
	overflow: hidden;
	cursor: zoom-in;
	width: 100%;
	height: 100%;
}
.main-img {
	transition: transform 0.4s ease-out;
	width: 100%;
	height: 100%;
	object-fit: contain;
	background-color: var(--color-bg-light-gray); /* Contrast card */
}

.zoom-wrapper:hover .main-img {
	transform: scale(1.5);
}
</style>

<script>
(function() {
	const gallery  = document.getElementById('censkills-product-gallery');
	if (!gallery) return;
	const thumbs   = gallery.querySelectorAll('.censkills-gallery-thumb');
	const slides   = gallery.querySelectorAll('.censkills-gallery-slide');
	const prevBtn  = gallery.querySelector('.censkills-gallery-arrow.prev');
	const nextBtn  = gallery.querySelector('.censkills-gallery-arrow.next');
	let current    = 0;

	function goTo(index) {
		thumbs[current].classList.remove('is-active');
		slides[current].classList.remove('is-active');
		current = (index + slides.length) % slides.length;
		thumbs[current].classList.add('is-active');
		slides[current].classList.add('is-active');

		// scroll thumb into view
		thumbs[current].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
	}

	thumbs.forEach((thumb, i) => {
		thumb.addEventListener('click', () => goTo(i));
	});

	if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
	if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));

	// Optional: Track mouse for better zoom
	const wrappers = gallery.querySelectorAll('.zoom-wrapper');
	wrappers.forEach(wrap => {
		wrap.addEventListener('mousemove', e => {
			const img = wrap.querySelector('img');
			const rect = wrap.getBoundingClientRect();
			const x = (e.clientX - rect.left) / rect.width * 100;
			const y = (e.clientY - rect.top) / rect.height * 100;
			img.style.transformOrigin = `${x}% ${y}%`;
		});
	});
})();
</script>
