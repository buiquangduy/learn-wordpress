<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php quangduy_thumbnail( 'large' ); ?>
		<?php quangduy_entry_header(); ?>
		<?php
		/*
		 * Đếm số lượng attachment có trong post
		 */
		$attachments = get_children( array( 'post_parent'=>$post->ID ) );
		$attachment_number = count($attachments);
		printf( __('This image post contains %1$s photos', 'quangduy'), $attachment_number );
		?>
	</header>
	<div class="entry-content">
		<?php quangduy_entry_content(); ?>
		<?php
		if ( is_single() ) :
			quangduy_entry_tag();
		endif;
		?>
	</div>
</article>