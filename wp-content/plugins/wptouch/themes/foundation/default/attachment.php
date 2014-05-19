<?php get_header(); ?>

<div id="content">

	<div class="<?php wptouch_post_classes(); ?> box">
	
		<?php wptouch_the_post(); ?>
		
		<?php
			$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
			foreach ( $attachments as $k => $attachment ) {
				if ( $attachment->ID == $post->ID ){ 
					break; 
				} 
			}
	
			$k++;
	
			if ( count( $attachments ) > 1 ) {
				if ( isset( $attachments[ $k ] ) ) {
					// get the URL of the next image attachment
					$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
				} else {
					// or get the URL of the first image attachment
					$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
				}
			} else {
				// or, if there's only 1 image, get the URL of the image
				$next_attachment_url = wp_get_attachment_url();
		}?>
	
		<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
			<?php echo wp_get_attachment_image( $post->ID, array( 800, 800 ) ); ?>
		</a>
		
		<?php if ( !empty( $post->post_excerpt ) ) { ?>
			<div class="entry-caption">
				<?php wptouch_the_excerpt(); ?>
			</div>
		<?php } ?>
		
		<div class="gallery-nav" role="menu">
			<span class="left" role="menuitem"><?php previous_image_link( false, __( '&laquo; previous in gallery' , 'wptouch-pro' ) ); ?></span>
			&nbsp;
			<span class="right" role="menuitem"><?php next_image_link( false, __( 'next in gallery &raquo;' , 'wptouch-pro' ) ); ?></span>
		</div>
		
	</div><!-- .post -->

</div><!-- #content -->

<?php get_footer(); ?>