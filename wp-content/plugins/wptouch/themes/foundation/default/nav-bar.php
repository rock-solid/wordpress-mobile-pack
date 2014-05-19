<?php  if ( is_single() ) { ?>
	<div class="nav-bar clearfix">		
		<div class="nav-controls">
			<?php if ( wptouch_fdn_if_previous_post_link() ) { ?>
				<a class="prev-post" href="<?php wptouch_fdn_get_previous_post_link(); ?>">
					<?php _e( 'previous post', 'wptouch-pro' ); ?>
				</a>
			<?php } ?>
				
			<?php if ( wptouch_fdn_if_next_post_link() ) { ?>
				<a class="next-post" href="<?php wptouch_fdn_get_next_post_link(); ?>">
					<?php _e( 'next post', 'wptouch-pro' ); ?>
				</a>
			<?php } ?>
		</div>
	</div><!-- nav-bar -->
<?php } ?>