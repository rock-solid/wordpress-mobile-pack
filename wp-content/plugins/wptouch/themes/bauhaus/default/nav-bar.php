<?php  if ( is_single() ) { ?>
	<div class="nav-controls clearfix">
		
		<?php if ( wptouch_fdn_if_previous_post_link() ) { ?>
			<div class="previous">
				<?php _e( 'previous post', 'wptouch-pro' ); ?>
				<?php wptouch_fdn_get_previous_post_link_w_title(); ?>
			</div>
		<?php } ?>
			
		<?php if ( wptouch_fdn_if_next_post_link() ) { ?>
			<div class="next">
			<?php _e( 'next post', 'wptouch-pro' ); ?>
			<?php wptouch_fdn_get_next_post_link_w_title(); ?>
			</div>
		<?php } ?>
	</div>
<?php } ?>