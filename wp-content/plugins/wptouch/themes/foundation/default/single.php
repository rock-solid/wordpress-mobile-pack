<?php get_header(); ?>

	<div id="content">
		<?php while ( wptouch_have_posts() ) { ?>
		
			<?php wptouch_the_post(); ?>

			<div class="<?php wptouch_post_classes(); ?>">
				<div class="post-head-area">
					<?php // if ( has_post_thumbnail() ) the_post_thumbnail(); ?>  
					<h2 class="post-title heading-font"><?php wptouch_the_title(); ?></h2>
					<span class="post-date"><?php wptouch_the_time(); ?> &bull;</span>
					<span class="post-author"><?php _e( 'By', 'wptouch-pro' ); ?> <?php the_author(); ?></span>
				</div>
				<?php wptouch_the_content(); ?>
			</div>

		<?php } ?>
	</div> <!-- content -->
	
	<?php get_template_part( 'nav-bar' ); ?>
	
	<?php get_template_part( 'related-posts' ); ?>
	
	<?php if ( comments_open() ) { ?>
		<div id="comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>
	
<?php get_footer(); ?>