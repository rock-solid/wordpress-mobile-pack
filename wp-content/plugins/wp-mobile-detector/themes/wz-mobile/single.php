<?php get_header(); ?>
		
		<div id="content" class="part">
			<?php if(have_posts()) : ?>
				<div id="individual_post">
					<?php while(have_posts()) : the_post(); ?>
						<?php $post_id = get_the_ID(); ?>
						<?php $images = wz_boot_get_all_images(get_the_content(),get_the_ID()); ?>
						<h1><?php the_title(); ?></h1>
						<p class="meta">
							Posted by <?php the_author_posts_link(); ?> <?php echo wz_calculate_time(get_the_date('Y-m-d H:i:s')); ?><br>
							<?php _e("Category:", "wz-mobile"); ?> <?php the_category(', '); ?><br>
							<?php the_tags(__("Tags","wz-mobile") . ': ', ', ', ''); ?>
						</p>
						<p>
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</p>
					<?php endwhile; ?>
					<div id="comments">
						<?php comments_template(); ?>
					</div>
				</div>
			<?php else : ?>
			
			<?php endif; ?>
			
			<?php $args = array( 'post_type' => 'post', 'posts_per_page' => 10, 'paged' => 1, 'post__not_in' =>array($post_id) ); ?>
			<?php $feed = new WP_Query( $args ); ?>
			<?php if($feed->have_posts()): ?>
			<div id="article_feed" style="background-color: #444; height: 100px; overflow: scroll; border-top: 1px solid #333;">
				<div style="width: <?php echo ($feed->post_count*100);?>px">
					<?php while ( $feed->have_posts() ) : $feed->the_post(); ?>
					<?php $first_image = wz_boot_get_first_image(get_the_content(),get_the_ID()); ?>
					<a href="<?php the_permalink(); ?>">
					<div style="float: left; height: 100px; width: 100px;">
						<div style="overflow: hidden; position: relative; height: 100px; border-right: 1px solid #333; border-left: 1px solid #555; background-position: center center; background-repeat: no-repeat;<?php if($first_image) echo " background-image: url('".$first_image."');"; ?>">
							<div style="padding: 5px; position: absolute; display: inline-block; right: 0px; left: 0px; bottom: 0px; font-size: 12px; text-shadow: black 1px 1px 1px; color: #fff; font-weight: bold; line-height: 15px;">
								<?php the_title(); ?>
							</div>
						</div>
					</div>
					</a>
					<?php endwhile; ?>
				</div>
			</div>
			<?php endif; ?>
		</div><!-- END CONTENT -->
		
		<?php get_sidebar('right_single'); ?>
		
<?php get_footer(); ?>