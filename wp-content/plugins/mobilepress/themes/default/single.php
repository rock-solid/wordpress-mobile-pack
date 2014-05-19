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

		<div class="postmeta">
			<p>By <a href="#"><?php the_author_meta( 'display_name' ); ?></a> on <?php the_time( get_option( 'date_format' ) ) ?> &middot; Posted in <?php the_category( ', ' ) ?></p>
		</div>

		<?php if ( mopr_get_option( 'show_tags' ) && has_tag() ) : ?>
		<div id="posttags">
			<p><?php the_tags( 'Tags: ', ', ' ); ?></p>
		</div>
		<?php endif; ?>

		<?php if ( mopr_check_pagination() ) : ?>
		<div id="pagination">
			<div class="next"><?php next_post_link(); ?></div>
			<div class="prev"><?php previous_post_link(); ?></div>
			<div class="clearfix"></div>
		</div>
		<?php endif; ?>

		<?php
			$allow_comments = mopr_get_option( 'comments' );

			if ( $allow_comments == 'all' || $allow_comments == 'posts' ) :
		?>
			<?php comments_template( '/comments.php', true ); ?>
		<?php endif; ?>

		<?php endwhile; ?>
		<?php endif;?>
	<?php endif; ?>
	</div>

<?php get_footer(); ?>