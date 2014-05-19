<?php get_header(); ?>
		
		<div id="content" class="part">
			<?php if(have_posts()) : ?>
				<div id="posts_container">
					<?php if (is_category()): ?>
						<h1 id="title">
							<span><?php _e("Category:", "wz-mobile"); ?></span> <?php single_cat_title(); ?>
						</h1>
					<?php elseif (is_tag()): ?>
						<h1 id="title">
							<span><?php _e("Tagged:", "wz-mobile"); ?></span> <?php single_tag_title(); ?>
						</h1>
					<?php elseif (is_author()): ?>
						<h1 id="title">
							<span><?php _e("By:", "wz-mobile"); ?></span> <?php get_the_author_meta('display_name'); ?>
						</h1>
					<?php elseif (is_day()): ?>
						<h1 id="title">
							<span><?php _e("Daily Archives:", "wz-mobile"); ?></span> <?php the_time('l, F j, Y'); ?>
						</h1>
					<?php elseif (is_month()): ?>
						<h1 id="title">
							<span><?php _e("Monthly Archives:", "wz-mobile"); ?></span> <?php the_time('F Y'); ?>
						</h1>
					<?php elseif (is_year()): ?>
						<h1 id="title">
							<span><?php _e("Yearly Archives:", "wz-mobile"); ?></span> <?php the_time('Y'); ?>
						</h1>
					<?php endif; ?>
				<?php while(have_posts()) : the_post(); ?>
					<?php $first_image = wz_boot_get_first_image(get_the_content(),get_the_ID()); ?>
					<a href="<?php the_permalink(); ?>">
						<div class="the_post<?php if(!$first_image) echo " no_image"; ?>" <?php if($first_image) echo 'style="background-image: url(\''.WEBSITEZ_PLUGIN_WEB_DIR.'/timthumb.php?src='.urlencode($first_image).'&w=400\'); background-position: center bottom; background-repeat: no-repeat; min-height: 200px;"'; ?>>
							<div class="content<?php if($first_image) echo " image"; ?>">
								<h2><?php the_title(); ?></h2>
								<div class="comments"><?php comments_number( '0', '1', '%' ); ?> <i class="icon-comment icon-white"></i></div>
								<div class="meta"><?php the_author(); ?> | <?php echo wz_calculate_time(get_the_date('Y-m-d H:i:s')); ?></div>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
				</div>
				<div id="pagination">
					<div id="previous">
						<?php previous_posts_link('<i class="icon-arrow-left icon-white"></i> Newer Entries', 0) ?>
					</div>
					<div id="next">
						<?php next_posts_link('Older Entries <i class="icon-arrow-right icon-white"></i>', 0); ?>
					</div>
					<div style="clear: both;"></div>
				</div>
			<?php endif; ?>
		</div><!-- END CONTENT -->
		
		<?php get_sidebar('left_home'); ?>
		
		<?php get_sidebar('right_home'); ?>
		
<?php get_footer(); ?>