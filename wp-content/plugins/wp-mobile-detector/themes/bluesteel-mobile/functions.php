<?php
if ( ! isset( $content_width ) )
	$content_width = 480;
	
if (function_exists('register_sidebar'))
  register_sidebar();

add_action( 'after_setup_theme', 'websitez_setup' );

if (!function_exists('websitez_setup')){
	function websitez_setup() {
		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
	}
}

function websitez_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
?>
	<div class="websitez-comments" <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="websitez-comments-author">
			<p class="websitez-comments-gravatar"><?php  if(function_exists('get_avatar')){ echo get_avatar($comment, '80'); } ?></p>
			<p class="websitez-comments-author-link"><?php  comment_author_link() ?></p>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
	   	<p class="websitez-comments-awaiting-moderation"><?php _e('This comment is awaiting moderation.'); ?></p>
	  <?php endif; ?>
		<p class="websitez-comments-text"><?php comment_text() ?></p>
		<p class="websitez-comments-reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
		<div style="clear: both;"></div>
	</div>
<?php
}
?>