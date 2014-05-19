<?php get_header(); ?>
		
		<div id="content" class="part">
			<?php if(have_posts()) : ?>
				<div id="individual_post">
					<?php while(have_posts()) : the_post(); ?>
						<?php $post_id = get_the_ID(); ?>
						<?php $images = wz_boot_get_all_images(get_the_content(),get_the_ID()); ?>
						<h1><?php the_title(); ?></h1>
						<p><?php the_content(); ?></p>
					<?php endwhile; ?>
					<div id="comments">
						<?php comments_template(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div><!-- END CONTENT -->
		
		<?php get_sidebar('right_single'); ?>
		
<?php get_footer(); ?>