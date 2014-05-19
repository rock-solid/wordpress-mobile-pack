<?php get_header(); ?>

	<div id="content">
		<div class="comment-error">
			<h2 class="heading-font">
				<?php _e( 'Comment Error', 'wptouch-pro' ); ?>
			</h2>
			<p><?php _e( 'Please enter all fields correctly to post a comment.', 'wptouch-pro' ); ?></p>
			<p><center><a class="back-button button" href="#"><i class="icon-arrow-left"></i> <?php _e( 'Go back', 'wptouch-pro' ); ?></a></center></p>
		</div>
	</div> <!-- content -->

<?php get_footer(); ?>