<?php get_header(); ?>
<div class="main_body_mobile">
	<?php if(have_posts()) : ?>
		<?php $i=0; ?>
		<?php while(have_posts()) : the_post(); ?>
			<div class="wrapper" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="ui-body ui-body-c">
					<div class="ui-grid-a">
						<div class="ui-block-a" style="width: 80px;">
							<div class="calendar-day">
								<div class="month">
									<?php the_time('M') ?>
								</div>
								<div class="day">
									<?php the_time('j') ?>
								</div>
							</div>
						</div>
						<div class="ui-block-b" style="width: 70%; padding-top: 8px;">
							<div class="post_the_title">
					    	<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
								<p class="postmetadata">Author: <?php the_author(); ?></p>
					    </div>
						</div>
					</div><!-- /grid-a -->
					<div class="entry eid<?php echo $i; ?>" style="<?php if($i!=0) echo 'display: none;';?>">
						<!-- Begin -->  
						<?php 
						the_excerpt();
						?>
						<!-- End -->
						<p><a href="<?php the_permalink(); ?>" data-role="button" rel="nofollow">Read More</a></p>
					</div>
					<a href="#" data-role="button" data-icon="<?php if($i==0) echo 'arrow-u'; else echo 'arrow-d'; ?>" data-iconpos="notext" onclick="$('<?php echo '.eid'.$i; ?>').toggle('slow'); return false;"></a>
				</div>
			</div>
		<?php $i++; ?>
		<?php endwhile; ?>
	  <div class="navigation">
	  	<?php posts_nav_link(' &#124; ','&#171; previous','next &#187;'); ?>
	  </div>               
	<?php else : ?>
	<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php _e('No posts are added.'); ?></h2>
	</div>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>      