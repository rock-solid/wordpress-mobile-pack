<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the post/page editor screens.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014  Chad Butler
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2014
 */


/**
 * Actions
 */
add_action( 'admin_footer-edit.php', 'wpmem_bulk_posts_action' );
add_action( 'load-edit.php', 'wpmem_posts_page_load' );
add_action( 'admin_notices', 'wpmem_posts_admin_notices');


/**
 * Function to add block/unblock to the bulk dropdown list
 *
 * @since 2.9.2
 */
function wpmem_bulk_posts_action()
{ ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('block').text('<?php _e( 'Block', 'wp-members' )?>').appendTo("select[name='action']");
		jQuery('<option>').val('unblock').text('<?php _e( 'Unblock', 'wp-members' )?>').appendTo("select[name='action']");
        jQuery('<option>').val('block').text('<?php _e( 'Block', 'wp-members' )?>').appendTo("select[name='action2']");
		jQuery('<option>').val('unblock').text('<?php _e( 'Unblock', 'wp-members' )?>').appendTo("select[name='action2']");
      });
    </script>
    <?php
}


/**
 * Function to handle bulk actions at page load
 *
 * @since 2.9.2
 *
 * @uses WP_Users_List_Table
 */
function wpmem_posts_page_load()
{
	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	$action = $wp_list_table->current_action();
	$sendback = '';

	switch( $action ) { 
	
		case ( 'block' ):
		case ( 'unblock' ):
			/** validate nonce **/
			check_admin_referer( 'bulk-posts' );
			/** get the posts **/
			$posts = ( isset( $_REQUEST['post'] ) ) ? $_REQUEST['post'] : '';
			/** update posts **/
			$x = '';
			if( $posts ) {	
				foreach( $posts as $post_id ) {
					$x++;
					$post = get_post( $post_id );
					// update accordingly
					if( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 0 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 0 ) ) {
						if( $action == 'block' ) {
							update_post_meta( $post_id, 'block', true);
						} else {
							delete_post_meta( $post_id, 'block' );
						}
					}
					
					if( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 1 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 1 ) ) {
					
						if( $action == 'unblock' ) {
							update_post_meta( $post_id, 'unblock', true );	
						} else {
							delete_post_meta( $post_id, 'unblock' );
						}
					}
				}
				/** set the return message */
				$sendback = add_query_arg( array( 'block' => $action, 'b' => $x ), $sendback );
			} else {
				/** set the return message */
				$sendback = add_query_arg( array( 'block' => 'none' ), $sendback );
			}
			break;
		
		default:
			return;

	}

	/** if we did not return already, we need to wp_redirect */
	wp_redirect( $sendback );
	exit();
}


/**
 * Function to echo admin update message
 *
 * @since 2.8.2
 */
function wpmem_posts_admin_notices()
{    
	global $post_type, $pagenow, $user_action_msg;
	if( $pagenow == 'edit.php' && $post_type == 'post' &&
		isset( $_REQUEST['block'] ) ) {
		$message = sprintf( __( '%s posts %sed.', 'wp-members' ), $_REQUEST['b'], $_REQUEST['block'] );
		echo "<div class=\"updated\"><p>{$message}</p></div>";
	}
}


/**
 * Adds the blocking meta boxes for post and page editor screens.
 *
 * @since 2.8
 */
function wpmem_block_meta_add() 
{
	/**
	 * Filter the post meta box title
	 *
	 * @since 2.9.0
	 */
	$post_title = apply_filters( 'wpmem_admin_post_meta_title', __( 'Post Restriction', 'wp-members' ) );
	
	/**
	 * Filter the page meta box title
	 *
	 * @since 2.9.0
	 */
	$page_title = apply_filters( 'wpmem_admin_page_meta_title', __( 'Page Restriction', 'wp-members' ) );

    add_meta_box( 'wpmem-block-meta-id', $post_title, 'wpmem_block_meta', 'post', 'side', 'high' );
	add_meta_box( 'wpmem-block-meta-id', $page_title, 'wpmem_block_meta', 'page', 'side', 'high' );	
}


/**
 * Builds the meta boxes for post and page editor screens.
 *
 * @since 2.8
 *
 * @uses do_action Calls 'wpmem_admin_after_block_meta' Allows actions at the end of the block meta box on pages and posts
 *
 * @global $post The WordPress post object
 */
function wpmem_block_meta()  
{  
    global $post;
	
    wp_nonce_field( 'wpmem_block_meta_nonce', 'wpmem_block_meta_nonce' );
	
	if( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 1 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 1 ) ) {
	
		$notice = '<p>' 
			. ucfirst( $post->post_type ) 
			. 's are blocked by default.&nbsp;&nbsp;<a href="' 
			. get_admin_url() 
			. '/options-general.php?page=wpmem-settings">Edit</a></p>';
		$block = 'wpmem_unblock';
		$meta = 'unblock';
		$text = 'Unblock';
	
	} elseif( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 0 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 0 ) ) {
	
		$notice = '<p>' 
			. ucfirst( $post->post_type ) 
			. 's are not blocked by default.&nbsp;&nbsp;<a href="' 
			. get_admin_url() 
			. '/options-general.php?page=wpmem-settings">Edit</a></p>';
		$block = 'wpmem_block';
		$meta = 'block';
		$text = 'Block';		
	
	}

	echo $notice;
	
	?>
    <p>
		<input type="checkbox" id="<?php echo $block; ?>" name="<?php echo $block; ?>" value="true" <?php checked( get_post_meta( $post->ID, $meta, true ), 'true' ); ?> />
		<label for="<?php echo $block; ?>"><?php echo $text; ?> this <?php echo $post->post_type; ?></label>
    </p>
    <?php
	do_action( 'wpmem_admin_after_block_meta', $post, $block );
}


/**
 * Saves the meta boxes data for post and page editor screens.
 *
 * @since 2.8
 *
 * @uses do_action Calls 'wpmem_admin_block_meta_save' allows actions to be hooked to the meta save process
 *
 * @param int $post_id The post ID
 */
function wpmem_block_meta_save( $post_id )  
{  
    // quit if we are doing autosave
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
	
    // quit if the nonce isn't there, or is wrong
    if( ! isset( $_POST['wpmem_block_meta_nonce'] ) || ! wp_verify_nonce( $_POST['wpmem_block_meta_nonce'], 'wpmem_block_meta_nonce' ) ) return; 
    
	// quit if the current user cannot edit posts
    if( ! current_user_can( 'edit_posts' ) ) return;  
    
	// get values
    $block   = isset( $_POST['wpmem_block'] )   ? $_POST['wpmem_block']   : false; 
	$unblock = isset( $_POST['wpmem_unblock'] ) ? $_POST['wpmem_unblock'] : false;	
	
	// need the post object
	global $post; 
	
	// update accordingly
	if( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 0 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 0 ) ) {
		if( $block ) {
			update_post_meta( $post_id, 'block', $block );
		} else {
			delete_post_meta( $post_id, 'block' );
		}
	}
	
	if( ( $post->post_type == 'post' && WPMEM_BLOCK_POSTS == 1 ) || ( $post->post_type == 'page' && WPMEM_BLOCK_PAGES == 1 ) ) {
	
		if( $unblock ) {
			update_post_meta( $post_id, 'unblock', $unblock );	
		} else {
			delete_post_meta( $post_id, 'unblock' );
		}
	}
	
	do_action( 'wpmem_admin_block_meta_save', $post, $block, $unblock );
}


/**
 * Adds WP-Members blocking status to Posts Table columns
 *
 * @since 2.8.3
 *
 * @uses wp_enqueue_style Loads the WP-Members admin stylesheet
 *
 * @param arr $columns The array of table columns
 */
function wpmem_post_columns( $columns ) {
	wp_enqueue_style ( 'wpmem-admin-css', WPMEM_DIR . '/css/admin.css', '', WPMEM_VERSION );
	$columns['wpmem_block'] = ( WPMEM_BLOCK_POSTS == 1 ) ? __( 'Unblocked?', 'wp-members' ) : __( 'Blocked?', 'wp-members' );
    return $columns;
}


/**
 * Adds blocking status to the Post Table column
 *
 * @since 2.8.3
 *
 * @param $column_name
 * @param $post_ID
 */
function wpmem_post_columns_content( $column_name, $post_ID ) {
	if( $column_name == 'wpmem_block' ) {  
		$block = ( WPMEM_BLOCK_POSTS == 1 ) ? 'unblock' : 'block';
		echo ( get_post_custom_values( $block, $post_ID ) ) ? __( 'Yes' ) : '';
    } 
}


/**
 * Adds WP-Members blocking status to Page Table columns
 *
 * @since 2.8.3
 *
 * @uses wp_enqueue_style Loads the WP-Members admin stylesheet
 *
 * @param arr $columns The array of table columns
 */
function wpmem_page_columns( $columns ) {
	wp_enqueue_style ( 'wpmem-admin-css', WPMEM_DIR . '/css/admin.css', '', WPMEM_VERSION );
	$columns['wpmem_block'] = ( WPMEM_BLOCK_PAGES == 1 ) ? __( 'Unblocked?', 'wp-members' ) : __( 'Blocked?', 'wp-members' );  
    return $columns;
}


/**

 * Adds blocking status to the Page Table column
 *
 * @since 2.8.3
 *
 * @param $column_name
 * @param $post_ID
 */
function wpmem_page_columns_content( $column_name, $post_ID ) {
	if( $column_name == 'wpmem_block' ) {  
		$block = ( WPMEM_BLOCK_PAGES == 1 ) ? 'unblock' : 'block';
		echo ( get_post_custom_values( $block, $post_ID ) ) ? __( 'Yes' ) : '';
    } 
}

/** End of File **/