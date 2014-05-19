			<div id="title">
				<h2>Post Comment</h2>
			</div>

			<?php
				if ( comments_open() ) :
					if ( get_option( 'comment_registration' ) && ! $user_ID ) :
			?>
					<div class="post">
						<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
					</div>

				<?php else : ?>
					<div class="post">

						<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

							<?php if ( $user_ID ): ?>

							<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option( 'siteurl' ); ?>/wp-login.php?action=logout" title="Log out of this account">Log out</a></p>

							<?php else : ?>

							<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="14" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
							<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

							<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="14" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
							<label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>

							<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="14" tabindex="3" />
							<label for="url"><small>Website</small></label></p>

							<?php endif; ?>

						<p><textarea name="comment" id="comment" cols="90%" rows="8" tabindex="4"></textarea></p>
						<p>
							<input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
							<input type="hidden" name="comment_post_ID" value="<?php mopr_comment_id(); ?>" />
						</p>
						<?php do_action( 'comment_form', $post->ID ); ?>

						</form>

					</div>

					<?php endif; ?>

			<?php else : ?>

				<div class="post">
					<p>Sorry, comments are closed on this post.</p>
				</div>

			<?php endif; ?>

			<div class="postmeta">
				<p><a href="<?php the_permalink() ?>">&laquo; Back To Post</a></p>
			</div>
