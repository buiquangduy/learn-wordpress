<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-thumbnail">
		<?php quangduy_thumbnail( 'thumbnail' ); ?>
	</div>
	<header class="entry-header">
		<?php quangduy_entry_header(); ?>
		<?php quangduy_entry_meta() ?>
	</header>
	<div class="entry-content">
		<?php quangduy_entry_content(); ?>
		<?php ( is_single() ? quangduy_entry_tag() : '' ); ?>
	</div>
</article>