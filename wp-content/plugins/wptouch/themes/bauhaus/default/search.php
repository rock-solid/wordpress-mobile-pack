<?php get_header(); ?>

<div id="content" class="search">

	<div class="post-page-head-area bauhaus">
		<h2 class="post-title heading-font"><?php echo sprintf( __( 'You searched for "%s"', 'wptouch-pro' ), $_GET['s'] ); ?>:</h2>
		<span class="select-wrap">
			<select class="search-select heading-font">	
				<?php
					$post_types = wptouch_fdn_get_search_post_types();		
					foreach( $post_types as $post_type ) { global $search_post_type; $search_post_type = $post_type;
				?>
					<option data-section="<?php echo strtolower( wptouch_fdn_get_search_post_type() ); ?>">
						<?php echo sprintf( __( 'Show %s Results', 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ); ?>
					</option>		
				<?php } ?>
			</select>
			<i class="icon-caret-down"></i>
		</span>
	</div>
		
	<?php
		$post_types = wptouch_fdn_get_search_post_types();		
		foreach( $post_types as $post_type ) { global $search_post_type; $search_post_type = $post_type;
	?>

	<div id="<?php echo strtolower( wptouch_fdn_get_search_post_type() ); ?>-results">		
			<?php $query = new WP_Query( $query_string . '&post_type=' . $post_type . '&max_num_pages=10&posts_per_page='. foundation_number_of_posts_to_show() .'' ); if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>	
	
			<?php get_template_part( 'post-loop' ); ?>
			
			<?php } // $query ?>
			
			<?php } else { ?>
	
				<?php if ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) { ?>
					<span class="no-results">
						<?php _e( 'No results found', 'wptouch-pro' ); ?>.
					</span>
				<?php } ?>
			
			<?php } ?>

	<?php if ( get_next_posts_link() ) { ?>
		<a class="load-more-<?php echo strtolower ( wptouch_fdn_get_search_post_type() ); ?>-link no-ajax" href="javascript:return false;" rel="<?php echo get_next_posts_page_link(); ?>">
			<?php echo strtolower( sprintf( __( "Load more %s results", 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ) ); ?>&hellip;
		</a>
	<?php } ?>
	</div>

	<?php } ?>

</div> <!-- content -->

<?php get_footer(); ?>