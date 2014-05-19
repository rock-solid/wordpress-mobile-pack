<?php
	if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ( 'Please do not load this page directly. Thanks!' );

	if ( ! empty( $post->post_password ) ) {
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) {
			?>

			<div class="post oneline">
				<p>This post is password protected. Enter the password to view comments.</p>
			</div>

			<?php
			return;
		}
	}
?>

<?php if ( $comments ) : ?>

	<div id="comments">
		<div id="respond">
			<p><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?> | <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>postcomment=true">Post Comment</a></p>
		</div>
	</div>

	<?php foreach ( $comments as $comment ) : ?>

		<div class="post" id="comment-<?php comment_ID(); ?>">
			<p><cite><?php comment_author_link() ?></cite> says:</p>
			<p class="comment">
				<?php comment_text() ?>
			</p>
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p><em>Your comment is awaiting moderation.</em></p>
			<?php endif; ?>
			<p class="singleline commentmeta">Posted on <?php comment_date( 'F jS, Y' ); ?></p>
		</div>

	<?php endforeach; ?>

<?php else : ?>

	<?php if ( 'open' == $post->comment_status ) : ?>

		<div class="post oneline">
			<p>Be the first to <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>postcomment=true">post a comment</a>.</p>
		</div>

	 <?php else : ?>

		<div class="post oneline">
			<p>Sorry, comments are closed on this post.</p>
		</div>

	<?php endif; ?>

<?php endif; ?>
