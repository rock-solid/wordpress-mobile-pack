<?php
error_reporting(0);

add_filter('show_admin_bar', '__return_false');

function wz_boot_footer_scripts() {
    echo '<script type="text/javascript" src="'.get_template_directory_uri().'/js/jmobile.min.js"></script><script type="text/javascript" src="'.get_template_directory_uri().'/js/wz_mobile.min.js"></script>';
}
add_action('wp_footer', 'wz_boot_footer_scripts');

function wz_boot_is_home(){
	if($_SERVER['REQUEST_URI'] == "/"):
		return true;
	endif;
	
	return false;
}

function wz_boot_get_all_images($html,$post_id=null){
	$images = array();
	
	if (!is_null($post_id) && has_post_thumbnail( $post_id ) ):
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
		$images[] = $image[0];
	endif;
	
	if(!is_null($post_id)):
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_parent' => $post_id,
			'exclude'     => get_post_thumbnail_id()
		) );
	
		if ( $attachments ):
			foreach ( $attachments as $attachment ):
				$thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
				$images[] = $thumbimg[0];
			endforeach;
		endif;
	endif;
	
	return $images;
}

function wz_boot_get_first_image($html,$post_id=null){
	$first_image = false;
	
	if (!is_null($post_id) && has_post_thumbnail( $post_id ) ):
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
		if(strlen($image[0]) > 0):
			return $image[0];
		endif;
	endif;
	
	if(!is_null($post_id)):
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_parent' => $post_id,
			'exclude'     => get_post_thumbnail_id()
		) );
	
		if ( $attachments ):
			foreach ( $attachments as $attachment ):
				$thumbimg = wp_get_attachment_image_src( $attachment->ID, 'full' );
				if(strlen($thumbimg[0]) > 0):
					return $thumbimg[0];
				endif;
			endforeach;
		endif;
	endif;
	
	if (class_exists('DOMDocument')) {
		try{
			//Resize the images on the page
			$dom = new DOMDocument();
			$dom->loadHTML($html);
			
			// grab all the on the page and make sure they are the right size
			$xpath = new DOMXPath($dom);
			$imgs = $xpath->evaluate("//img");
			
			for ($i = 0; $i < $imgs->length; $i++) {
				$img = $imgs->item($i);
				$src = trim($img->getAttribute('src'));
				if(strlen($src) > 0){
					return $src;
				}
			}
		}catch(Exception $e){
		}
	}
	
	return $first_image;
}

function wz_calculate_time($time){
	$day = 86400;
	$hour = 3600;
	$minute = 60;
	$todays_date = strtotime("now");
	$postTime = strtotime($time);
	$since = ($todays_date - $postTime);
	$days = $since/$day;
	if($days > 1){
		$finDays = round($days);
		if($finDays > 1)
			$ago = $finDays . " days ago";
		else
			$ago = $finDays . " day ago";
	}else{
		$hours = round($since/$hour);
		if($hours > 1){
			$ago = $hours." hours ago";
		}else{
			$minutes = round($since/$minute);
			if($minutes > 1)
				$ago = $minutes." minutes ago";
			else
				$ago = "less than 1 minute ago";
		}
	}
	return $ago;
}

function wz_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
?>
	<div class="wz-comments" <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="wz-comments-author">
			<p class="wz-comments-gravatar"><?php  if(function_exists('get_avatar')){ echo get_avatar($comment, '80'); } ?></p>
			<p class="wz-comments-author-link"><?php  comment_author_link() ?></p>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
	   	<p class="wz-comments-awaiting-moderation"><?php _e('This comment is awaiting moderation.'); ?></p>
	  <?php endif; ?>
		<p class="wz-comments-text"><?php comment_text() ?></p>
		<p class="wz-comments-reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
		<div style="clear: both;"></div>
	</div>
<?php
}

function wz_get_current_encoded_url(){
	return urlencode(get_option('home').$_SERVER['REQUEST_URI']);
}
?>