<?php
/**
 * The main template file
 *
 * @package censkills-theme
 */

get_header(); ?>

<main id="primary" class="site-main">

	<?php
	if ( have_posts() ) :

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			the_content();

		endwhile;

		the_posts_navigation();

	else :

		echo '<p>No content found.</p>';

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
