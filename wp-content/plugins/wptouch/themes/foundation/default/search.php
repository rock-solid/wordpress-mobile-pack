<?php get_header(); ?>


<div id="content" class="search">

	<h3 class="search-heading page-heading"><?php echo sprintf( __( 'You searched for "%s"', 'wptouch-pro' ), $_GET['s'] ); ?>:</h3>
	
	<?php
		$post_types = wptouch_fdn_get_search_post_types();		
		foreach( $post_types as $post_type ) { 
		global $search_post_type; 
		$search_post_type = $post_type; 
	?>
	
		<h3 class="search-heading heading-font">
			<?php echo sprintf( __( "%s results", 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ); ?>
		</h3>
	
		<div id="<?php echo strtolower( wptouch_fdn_get_search_post_type() ); ?>-results">		
			<ul>
				<?php $query = new WP_Query( $query_string . '&post_type=' . $post_type . '&max_num_pages=10&posts_per_page='. foundation_number_of_posts_to_show() .'' ); if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>	
		
				<li class="<?php wptouch_post_classes(); ?>">
					<p class="date"><?php wptouch_the_time(); ?></p>
					<a href="<?php wptouch_the_permalink(); ?>"><?php wptouch_the_title(); ?></a>
					<?php wptouch_the_excerpt(); ?>
				</li>
		
				<?php } // Query ?>
				
				</ul>
	
			<?php } else { ?>
				
				<?php if ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) { ?>
					<li><?php _e( 'No search results found', 'wptouch-pro' ); ?></li>
				<?php } ?>
				
			<?php } ?>

		</div>

		<?php if ( get_next_posts_link() ) { ?>
			<a class="load-more-<?php echo strtolower ( wptouch_fdn_get_search_post_type() ); ?>-link no-ajax" href="javascript:return false;" rel="<?php echo get_next_posts_page_link(); ?>">
				<?php echo strtolower( sprintf( __( "Load more %s results", 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ) ); ?>&hellip;
			</a>
		<?php } ?>

	<?php } ?>

</div> <!-- content -->

<?php get_footer(); ?>