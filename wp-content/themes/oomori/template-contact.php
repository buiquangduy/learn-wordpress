<?php
/**
 * Template Name: Contact
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

	<div id="primary" class="content-area wrap-contact-page">
		<div class="container title-heading-page">
			<h2 class="title">お問い合わせ</h2>
		</div>
		<div class="container">
			<?php
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;
			?>
		</div>
	</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
