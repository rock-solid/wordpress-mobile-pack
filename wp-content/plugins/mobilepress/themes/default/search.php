<?php get_header(); ?>

	<div id="contentwrap">
		<div id="title">
			<h2>Results for: "<em><?php the_search_query(); ?></em>"</h2>
		</div>

		<?php $access_key = 1; ?>
		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

		<div class="post">
			<?php if ( mopr_get_option( 'show_thumbnails' ) && has_post_thumbnail() ) : $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); ?>
			<a href="<?php the_permalink(); ?>" class="thumbnail"><img src="<?php echo mopr_create_thumbnail( $thumbnail_url[0], 0, 50, 50 ); ?>" /></a>
			<?php endif; ?>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" accesskey="<?php echo $access_key; $access_key++; ?>"><?php the_title(); ?></a></h2>
			<p class="subtitle"><?php the_time( get_option( 'date_format' ) ) ?>.
				<?php if ( $post->post_type != 'page'  ) : ?>
					<a href="<?php the_permalink(); ?>#comments"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a>
				<?php endif; ?>
			</p>
		</div>

		<?php endwhile; ?>
		<?php endif; ?>

		<?php if ( mopr_check_pagination() ) : ?>
		<div id="pagination">
			<div class="next"><?php next_posts_link( 'Next Page &raquo;', 0 ) ?></div>
			<div class="prev"><?php previous_posts_link( '&laquo; Previous Page' ); ?></div>
			<div class="clearfix"></div>
		</div>
		<?php endif; ?>

		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>