<?php if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) { ?>
		die ( 'Please do not load this page directly. Thanks!' );
<?php } ?>

<?php if ( post_password_required() ) { return; } ?>

<?php if ( have_comments() ) { ?>

	<h3 id="responses" class="heading-font">
		<?php comments_number( __( 'no responses', 'wptouch-pro' ), __( '1 response', 'wptouch-pro' ), __( '% responses', 'wptouch-pro' ) ); ?>
	</h3>

	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&avatar_size=80&max_depth=3&callback=wptouch_fdn_display_comment' ); ?>

		<?php if ( wptouch_fdn_comments_pagination() ) { ?>
			<?php if ( get_option( 'default_comments_page' ) == 'newest' ) { ?>
				<?php if ( get_previous_comments_link() ) { ?>
					<li class="load-more-comments-wrap">
						<?php previous_comments_link( __( 'Load More Comments&hellip;', 'wptouch-pro' ) ); ?>
					</li>
				<?php } ?>
			<?php } else { ?>
				<?php if ( get_next_comments_link() ) { ?>
					<li class="load-more-comments-wrap">
						<?php next_comments_link( __( 'Load More Comments&hellip;', 'wptouch-pro' ) ); ?>
					</li>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</ol>

<?php } else { ?>

	<?php if ( comments_open() ) { ?>
		<!-- If comments are open, but there are no comments -->
 	<?php } else { ?>
		<p class="nocomments"><?php _e( 'Comments are closed', 'wptouch-pro' ); ?></p>
 	<?php }?>

<?php } ?>

<!--  End of dealing with the comments, now the comment form -->

<?php if ( comments_open() ) { ?>
	<div id="respond">
		<div class="cancel-comment-reply">
			<?php cancel_comment_reply_link( __( 'Cancel', 'wptouch-pro' ) ); ?>
		</div>	
	
		<h3><?php comment_form_title( __( 'Leave a Reply', 'wptouch-pro' ), __( 'Leave a Reply to %s', 'wptouch-pro' ) ); ?></h3>
	
	<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) { ?>
		<p><?php echo sprintf( __( 'You must be %slogged in%s to post a comment.', 'wptouch-pro' ), '<a class="login-req" href="' . wp_login_url( get_permalink() ) . '">', '</a>' ); ?></p>
	<?php } else { ?>
		<form action="<?php wptouch_bloginfo( 'wpurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
	
			<?php comment_id_fields(); ?>
				
			<?php if ( is_user_logged_in() ) { ?>
				<p><?php _e( 'Logged in as', 'wptouch-pro' ); ?> <?php echo $user_identity; ?>. <a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>" title="Log out"><?php _e( 'Log out', 'wptouch-pro' ); ?> &raquo;</a></p>
			
			<?php } else { ?>

				<p><input type="text" name="author" id="author" value="<?php echo esc_attr( $comment_author ); ?>" size="22" <?php if ( $req ) echo "aria-required='true'"; ?> />&nbsp;<label for="author"><?php _e( 'Name', 'wptouch-pro' ); ?><?php if ( $req ) echo "*"; ?></label></p>
			
				<p><input type="email" autocapitalize="off" name="email" id="email" value="<?php echo esc_attr( $comment_author_email ); ?>" size="22" <?php if ( $req ) echo "aria-required='true'"; ?> tabindex="11" />&nbsp;<label for="email"><?php _e( 'E-Mail', 'wptouch-pro' ); ?><?php if ( $req ) echo "*"; ?></label></p>
			
				<p><input type="url" autocapitalize="off" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" />&nbsp;<label for="url"><?php _e( 'Website', 'wptouch-pro' ); ?></label></p>
			
			<?php } ?>
			
			<p><textarea name="comment" id="comment"></textarea></p>
		
			<p><button name="submit" type="submit" id="submit"><?php _e( 'Publish', 'wptouch-pro' ); ?></button></p>
			
			<?php do_action( 'comment_form', $post->ID ); ?>
	
		</form>
	<?php } ?>
	
	</div><!-- #respond // end dealing with the comment form -->
	
<?php }