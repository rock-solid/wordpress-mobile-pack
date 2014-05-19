<?php
if ( wps_get_option('front_page') != '' && ! is_category() && ! is_search() ) :  // if custom front page is set and not a search or category page
	
	query_posts('page_id=' . wps_get_option('front_page'));
	get_template_part( 'front_page' );  
	wp_reset_query(); 

else : // else show normal homepage

	global $is_ajax;
	$is_ajax = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] );
	
	
	if( ! $is_ajax ) : get_header(); // if not an ajax request
?>
	
	<div class="home-content">
	
		<?php wps_page_head(); ?>
	
	<?php endif; // end if not an ajax request ?>
	
	    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    	
    	<?php $post_image_src = wps_get_post_image( $post->ID, 'large' ); ?>
    	
    	<article id="post-<?php the_ID(); ?>">
    		<div class="entry-wrapper">
    			<a href="<?php the_permalink(); ?>" target="_self" rel="bookmark" style="display:block;">
    				<?php if( $post_image_src != '' ): ?><div class="entry-image" style="background-image:url(<?php echo $post_image_src ?>);<?php echo ! wps_get_option( 'show_thumbnails' ) ? 'display:none' : null ?>"></div><?php endif; ?>
		    		<div class="entry-header">
		    			<h1 class="entry-title"><?php the_title(); ?></h1>
				    	<div class="entry-meta"><?php echo wps_posted_on( false ); ?></div>
				    </div>
				    <div class="entry-content"><?php the_excerpt(); ?></div>
    			</a>
    			
			    <div class="clear"></div>
    		</div>
		</article>

    <?php endwhile; endif; // while have_posts ?>
    
    
    <?php if(get_next_posts_link() != ''): ?>

    	<div id="load-more" class="load-more" data-url="<?php echo get_next_posts_page_link(); ?>"><a href="#">Tap to load more articles</a></div>
    	
    <?php else: ?>
    	
    	<div class="load-more showing-all-articles">Showing all articles</div>
    	
    <?php endif; ?>
	    
<?php endif; // end if custom front page is set ?>


<?php if( ! $is_ajax ) : // if not an ajax request ?>

</div><!-- .home-content -->

<?php get_footer(); endif; ?>