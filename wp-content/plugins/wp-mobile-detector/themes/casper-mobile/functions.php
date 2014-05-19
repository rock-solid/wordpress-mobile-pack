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
	<div class="wrapper">
		<div class="ui-body ui-body-c commentmetadata">
    
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tbody>
	     <tr>
	     	<td align="left" class="comment_body_author" style="text-align:center;"><?php  if(function_exists('get_avatar')){ echo get_avatar($comment, '80'); } ?></td>
	     </tr>
	     <tr>
	     	<td align="left" class="comment_body_author" style="text-align:center;"><?php  comment_author_link() ?></td>
	     </tr>
	     <tr>     
				 <td align="left" class="comment_body_meta">
				   <span style="color:#666666;"><?php comment_date('F j, Y') ?></span> <?php edit_comment_link('Edit Comment','',''); ?>
				   <?php if ($comment->comment_approved == '0') : ?>
				   <em>
				   <?php _e('This comment is awaiting moderation.'); ?>
				   </em>
				   <?php endif; ?>
				 </td>
	     </tr>
	     <tr>
	          <td width="75%" align="left" class="comment_body_text"><?php comment_text() ?></td>
	     </tr>
	     <tr>
	     	<td width="100%" align="left" class="comment_body_meta">
         	<p><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
	     	</td>
	     </tr>
     </tbody>
   </table>
                   
	</div>
	</div>
<?php
}
?>