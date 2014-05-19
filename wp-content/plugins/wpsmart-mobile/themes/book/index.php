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
	
    	<article id="post-<?php the_ID(); ?>">
    		<div class="entry-wrapper">
    			<a href="<?php the_permalink(); ?>" target="_self" rel="bookmark" style="display:block;">
		    		<div class="entry-header">
		    			<h1 class="entry-title"><?php the_title(); ?></h1>
				    	<div class="entry-meta"><?php echo wps_posted_on( false ); ?></div>
				    </div>
				    <div class="entry-content" style="display:none">
				    	<?php the_excerpt(); ?>
				    </div>
    			</a>
    			<span class="entry-preview"><a href="#">Show article preview</a></span>
			    <div class="clear"></div>
    		</div>
		</article>

    <?php endwhile; endif; ?>

    <?php if ( get_next_posts_link() != '' ): ?>
    
    	<div id="load-more" class="load-more" data-url="<?php echo get_next_posts_page_link(); ?>"><a href="#">Tap to load more articles</a></div>
    	
    <?php else : ?>
    	
    	<div class="load-more showing-all-articles">Showing all articles</div>
    	
    <?php endif; ?>

<?php endif; // end if custom front page is set ?>


<?php if( ! $is_ajax ) : // if not an ajax request ?>

</div><!-- .home-content -->

<script type='text/javascript'>
$wpsmart(document).ready(function() {
 
    $wpsmart('#main').on('click', '.entry-preview a', function(event) {  
    	event.preventDefault();
    	
    	var object = $wpsmart(this),
    		content_object = object.closest('.entry-wrapper').find('.entry-content');
    	
    	content_object.slideToggle('fast', function() {
    		object.text(
    			content_object.is(':visible') ? "Hide article preview" : "Show article preview"
    		);	
    	});    
    	    	
    	return false;
    });
       
});
</script>

<?php get_footer(); endif; ?>