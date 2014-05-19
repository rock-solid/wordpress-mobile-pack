<?php
add_theme_support( 'post-thumbnails' );
if( get_option( 'timezone_string' ) != '') date_default_timezone_set( get_option( 'timezone_string' ) );


if ( ! function_exists( 'wps_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function wps_comment( $comment, $args, $depth ) {
     $GLOBALS['comment'] = $comment;

     switch ( $comment->comment_type ) :
     	case 'pingback' :
     	case 'trackback' :
     ?>
     		<li class="post pingback">
	     	<p><?php _e( 'Pingback:', 'wpsmart' ); ?> <?php comment_author_link(); ?></p>
     <?php
	     break;
     default :
     ?>

     <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	     <article id="comment-<?php comment_ID(); ?>" class="comment">
               <div class="comment-meta">
                         <?php
                              echo get_avatar( $comment, $avatar_size = 34 );

                              /* translators: 1: comment author, 2: date and time */
                              printf( __( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
                                   sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                                   sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                        esc_url( get_comment_link( $comment->comment_ID ) ),
                                        get_comment_time( 'c' ),
                                        /* translators: 1: date, 2: time */
                                        sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
                                   )
                              );
                         ?>

                    <?php if ( $comment->comment_approved == '0' ) : ?>
                         <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wpsmart' ); ?></em>
                         <br />
                    <?php endif; ?>
               </div>

               <div class="comment-content"><?php comment_text(); ?></div>

               <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'wpsmart' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
               </div><!-- .reply -->
          </article><!-- #comment-## -->
     <?php break; endswitch;

}
endif; // ends check for wpsmart_comment()


if ( ! function_exists( 'wps_time_since_post' ) ) :
/**
 * Calculate the time since the post was published (minutes, hours, days)
 * 
 * If the post was made more than a week ago then the date is simply displayed
 *
 */
function wps_time_since_post( $long_form = true, $post_timestamp )
{
	$now_timestamp = time();
	$minute_in_seconds = 60;
	$hour_in_seconds = 3600;
	$day_in_seconds = 86400;
	$week_in_seconds = 604800;
	
	$offset = abs( $now_timestamp - $post_timestamp );
	
	if( $offset <= $minute_in_seconds ) {
		$span = 'just now';
	} elseif( $offset < $hour_in_seconds) {
		$span = round( $offset / $minute_in_seconds ) . ' minutes ago';
	} elseif( $offset < $day_in_seconds ) {
		$span = round( $offset / $hour_in_seconds ) .' hours ago';
	} elseif( $offset < $week_in_seconds ) {
		$span = round( $offset / $day_in_seconds ) .' days ago';
	} else {
		if( $long_form )
			$span = date( "F j, Y g:ia T" , $post_timestamp ); 
		else
			$span = date( "F j, Y" , $post_timestamp );
	}
	
	return $span;
}
endif;


if ( ! function_exists( 'wps_posted_on' ) ) :
/**
 * Meta text of author name and text publish date
 *
 */
function wps_posted_on( $long_form = true )
{
	$display = "none";
	
	if( wps_get_option( 'show_post_author' ) == true )
		$display = "block";

	return "<span style=\"display:$display\">Posted " . wps_time_since_post( $long_form, get_post_time() ) . " by " . get_the_author() . "</span>";
}
endif;


if ( ! function_exists( 'wps_get_post_image' ) ) :
/**
 * Retrieve the post image (if one exists), typically the post thumbnail, used on the post listing pages
 * 
 * If no thumbnail is set, then use one of the attached images instead
 * 
 */
function wps_get_post_image( $post_id, $size = 'medium' )
{
	
	$thumbnail_image =  wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
	
	if( ! empty( $thumbnail_image ) ) {
		return $thumbnail_image[0];
	} else {
		$args = array(
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_type'      => 'attachment',
			'post_parent'    => $post_id,
			'post_mime_type' => 'image',
			'numberposts'    => -1,
		);
		
		$attachments = get_posts($args);
				
		if( ! empty( $attachments ) ) {
			$image = wp_get_attachment_image_src( $attachments[0]->ID, $size );
			return $image[0];
		} else {
			return false;
		}
	}
}
endif;


if ( ! function_exists( 'wps_banner' ) ) :
/**
 * Draw the header's banner containing logo as well as search and menu drop-downs
 * 
 */
function wps_banner()
{
?>
	<!-- header -->
	<header id="masthead" class="site-header" data-role="header" data-position="fixed">
		<div id="view-search" class="view-search <?php echo  ! wps_get_option( 'enable_search' ) ? "hidden" : null; ?>"><a href="#"><i class="icon-search"></i></a></div>
		<div id="view-menu" class="view-menu <?php echo  ! wps_get_option( 'enable_menu' ) ? "hidden" : null; ?>"><a href="#"><i class="icon-reorder"></i></a></div>
		
		<h1 class="site-title">
			<a href="<?php bloginfo( 'url' ) ?>" target="_self" rel="home">
				<?php if( wps_get_option( 'site_logo' ) == '' ) : ?>
					
					<?php echo wps_get_option( 'site_title' ); ?>
					
				<?php else : ?>
				
					<img src="<?php echo wps_get_option( 'site_logo' ); ?>"/>
					
				<?php endif; ?>
			</a>
		</h1>	
	</header>
	
	<!-- Advertising code -->
	<?php if( wps_get_option( 'advertising_type' ) == 'google_adsense' ) : wps_google_adsense_script( wps_get_option( 'adsense_client_id' ) );  ?>
	<?php elseif( wps_get_option( 'advertising_type' ) == 'custom_advertising' ) : echo wps_html_unclean( wps_get_option( 'custom_advertising_code' ) ); ?>
	<?php endif; ?>
	
	<!-- menu bar -->
	<div class="menu-bar header-drop-down">
		<ul>
			<?php foreach( wps_get_menu_links() as $links ): ?>
				<li><a href="<?php echo $links['url']; ?>" target="_self"><?php echo $links['title']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	
		
	<!-- search form drop-down -->
	<div class="search-bar header-drop-down">
		<div class="search-head">
			<form action="<?php echo home_url( '/' ); ?>" method="get" id="search-form" autocomplete="off" data-ajax="false">
				<?php if( wps_is_in_preview_mode() ):?>
					<input type="hidden" name="wps_preview" value="1"/>	
				<?php endif; ?>
				
				<div class="search-input-wrap input-wrap">
					<label id="search-input-label">Search by article title</label>
					<input id="s" name="s" type="search" value="" />
				</div>
			</form>
		</div>
	</div>
<?php
}
endif;


if ( ! function_exists( 'wps_footer_links' ) ) :
/**
 * Draw the theme footer links
 * 
 */
function wps_footer_links()
{
?>
	<div id="footer" class="site-footer">
		<p>Site optimized for mobile devices by <a href="http://www.wpsmart.com/mobile">WPSmart Mobile</a></p>
		<p><a href="http://wordpress.org/">Proudly powered by WordPress</a> | <a href="#" id="view_full_site">View Full Site</a></p>
	</div>
<?php
}
endif;


if ( ! function_exists( 'wps_page_head' ) ) :
/**
 * If the current page is a category or search results page, then draw a notification box informing the user
 * 
 */
function wps_page_head()
{
	if ( is_category() ) :
?>

		<div class="page-head">Currently browsing <span><?php single_cat_title() ?></span></div>
		
<?php elseif ( is_search() ) : ?>
	
		<div class="page-head">Search results for <span><?php echo get_search_query() ?></span></div>
	
<?php endif;	
}
endif;


if ( ! function_exists( 'wps_get_category' ) ) :
/**
 * Return the title of the post category
 * 
 */
function wps_get_category()
{
	$categories = get_the_category();
	
	if( sizeof($catgories) > 0 )
		return false;
	else
		return $categories[0]->cat_name;
}
endif;


if ( ! function_exists( 'wps_get_tags' ) ) :
/**
 * Get a comma separated list of post tags
 * 
 */
function wps_get_tags()
{
	$tags = get_the_tags();
	$tag_array = array();
	
	if( ! $tags )
		return false;
	else
	{
		foreach( $tags as $tag ) {
			$tag_array[] = $tag->name;
		}
	
		return implode(", ", $tag_array);
	}
}
endif;


if ( ! function_exists( 'wps_comments_title' ) ) :
/**
 * Pluralize the words 'Comment'
 * 
 */
function wps_comments_title( $comment_count )
{
	$comments_text = $comment_count == 1 ? "Comment" : "Comments";
	
	return $comment_count . " " . $comments_text;
}
endif;
