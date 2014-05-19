<?php get_header(); ?>

	<div id="contentwrap">
	<?php
		if ( isset( $_GET['postcomment'] ) ) :
			if ( have_posts() ) : while ( have_posts() ): the_post();
				comments_template( '/postcomment.php' );
			endwhile; endif;

		else :
	?>

		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

		<div id="title">
			<h2><?php the_title(); ?></h2>
		</div>

		<div class="post">
			<?php if ( has_post_thumbnail() ) : $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); ?>
			<a href="<?php the_permalink(); ?>" class="thumbnail"><img src="<?php echo mopr_create_thumbnail( $thumbnail_url[0], 0, 50, 50 ); ?>" /></a>
			<?php endif; ?>

			<?php the_content(); ?>
			<?php wp_link_pages( 'before=<p>&after=</p>&next_or_number=number&pagelink=Page %' ); ?>
		</div>

		<?php
			$allow_comments = mopr_get_option( 'comments' );

			if ( $allow_comments == 'all' || $allow_comments == 'pages' ) :
		?>
			<?php comments_template( '/comments.php', true ); ?>
		<?php endif; ?>

		<?php endwhile; ?>
		<?php endif;?>
	<?php endif; ?>
	</div>

<?php get_footer(); ?>