<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header van-hoc">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			$args = array(
				'category_name' => 'toan'
			);

			$the_query = new WP_Query( $args );

			// The Loop
			if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
//					the_meta();
					$tp_field = get_post_custom();

					echo '<pre>';
					print_r( $tp_field );
					echo '</pre>';
					echo the_title()."<br>";
					echo the_content()."<br>";
					echo the_date()."<br>";
					echo the_post_thumbnail()."<br>";
				endwhile;
			endif;
			// Reset Post Data
			wp_reset_postdata();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
