<?php if ( post_password_required() ) : ?>

	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'mobile' ); ?></p>

<?php return; endif; // password protected? ?>

<div id="comments" class="comments-area">
		
	<?php if ( wps_get_option( 'enable_comments' ) ) : ?>
	
		<?php if ( have_comments() ) : ?>
		
			<h2 class="comments-title"><?php echo wps_comments_title( sizeof( $comments ) ); ?></h2>
			
			<ul class="comment-list">
				
				<?php wp_list_comments( array( 'callback' => 'wps_comment' ) ); ?>
				
			</ul>
	
		<?php endif; // have_comments() ?>

	<?php endif; // are comments closed and there are no comments? ?>
	
	<?php if ( ! wps_get_option( 'enable_comments' ) ) : ?>
		
		<p class="comments-closed alert notice">Comments are closed.</p>
		
	<?php else : ?>
	
		<div id="respond" class="post-comment">
		
			<h3>Post a new comment</h3>
			
			<a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display: none;" class="cancel-reply">Cancel reply</a>
			
			<form action="<?php bloginfo('wpurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="" data-ajax="false">
			
				<?php if ( ! $user_ID ) : ?>
					
					<span class="logged_in">Your email will not be published.</span>
					
					<div class="comment-form-row input-wrap">
						<label>Name (required)</label>
						<input type="text" name="author" value="" class="required" />
					</div>
					
					<div class="comment-form-row input-wrap">
						<label>Email (required)</label>
						<input type="text" name="email" value="" class="required" />
					</div>
					
					<div class="comment-form-row input-wrap">
						<label>Website</label>
						<input type="text" name="url" value="" />
					</div>
				
				<?php else: ?>
				
					<span class="logged_in">Logged in as <em><?php echo $user_identity; ?></em></span>
				
				<?php endif; ?>
				
				
				<div class="comment-form-row input-wrap input-wrap-textarea">
					<textarea name="comment" rows="5" class="required"></textarea>
				</div>
				
				<div class="comment-form-row">
					<button type="submit" data-role="none">Post Comment</button>
				</div>
				
				<input type="hidden" name="comment_post_ID" value="<?php the_ID() ?>" id="comment_post_ID">
				<input type="hidden" name="comment_parent" id="comment_parent" value="0">
				<input type="hidden" name="comment_redirect" value="<?php echo get_permalink( $id ); ?>"/>
			</form>
			
			<div class="loading">Submitting comment...</div>
		</div>
	
	<?php endif; ?><!-- if comments enabled -->

</div><!-- #comments -->