<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the Users > All Users page.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2013  Chad Butler (email : plugins@butlerblog.com)
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2013
 */


/**
 * Actions and filters
 */
add_action( 'admin_footer-users.php', 'wpmem_bulk_user_action' );
add_action( 'load-users.php', 'wpmem_users_page_load' );
add_action( 'admin_notices', 'wpmem_users_admin_notices' );
add_filter( 'views_users', 'wpmem_users_views' );
add_filter( 'manage_users_columns', 'wpmem_add_user_column' );
add_action( 'manage_users_custom_column',  'wpmem_add_user_column_content', 10, 3 );
if( WPMEM_MOD_REG == 1 ) {
	add_filter( 'user_row_actions', 'wpmem_insert_activate_link', 10, 2 );
}


/**
 * Function to add activate/export to the bulk dropdown list
 *
 * @since 2.8.2
 */
function wpmem_bulk_user_action()
{ ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
	<?php if( WPMEM_MOD_REG == 1 ) { ?>
        jQuery('<option>').val('activate').text('<?php _e( 'Activate' )?>').appendTo("select[name='action']");
	<?php } ?>
		jQuery('<option>').val('export').text('<?php _e( 'Export', 'wp-members' )?>').appendTo("select[name='action']");
	<?php if( WPMEM_MOD_REG == 1 ) { ?>
        jQuery('<option>').val('activate').text('<?php _e( 'Activate' )?>').appendTo("select[name='action2']");
	<?php } ?>
		jQuery('<option>').val('export').text('<?php _e( 'Export', 'wp-members' )?>').appendTo("select[name='action2']");
		jQuery('<input id="export_all" name="export_all" class="button action" type="submit" value="<?php _e( 'Export All Users', 'wp-members' ); ?>" />').appendTo(".bottom .bulkactions");
      });
    </script>
    <?php
}


/**
 * Function to add activate link to the user row action
 *
 * @since 2.8.2
 *
 * @param  array $actions
 * @param  $user_object
 * @return array $actions
 */
function wpmem_insert_activate_link( $actions, $user_object ) {
    if( current_user_can( 'edit_users', $user_object->ID ) ) {
	
		$var = get_user_meta( $user_object->ID, 'active', true );
		
		if( $var != 1 ) {
			$url = "users.php?action=activate-single&amp;user=$user_object->ID";
			$url = wp_nonce_url( $url, 'activate-user' );
			$actions['activate'] = '<a href="' . $url . '">Activate</a>';
		}
	}
    return $actions;
}


/**
 * Function to handle bulk actions at page load
 *
 * @since 2.8.2
 *
 * @uses WP_Users_List_Table
 */
function wpmem_users_page_load()
{
	// if exporting all users, do it, then exit
	if( isset( $_REQUEST['export_all'] ) && $_REQUEST['export_all'] == __( 'Export All Users', 'wp-members' ) ) {
		include_once( WPMEM_PATH . 'admin/user-export.php' );
		wpmem_export_all_users();
		exit();
	}
	
	$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
	$action = $wp_list_table->current_action();
	$sendback = '';
	
	if( $action == 'activate' || 'activate-single' ) {
		// find out if we need to set passwords
		$chk_pass = false;
		$wpmem_fields = get_option( 'wpmembers_fields' );
		foreach( $wpmem_fields as $field ) {
			if( $field[2] == 'password' && $field[4] == 'y' ) { 
				$chk_pass = true; 
				break;
			}
		}
	}
	
	switch( $action ) {
		
	case 'activate':
		
		/** validate nonce */
		check_admin_referer( 'bulk-users' );
		
		/** get the users */
		$users = $_REQUEST['users'];
		
		/** update the users */
		$x = 0;
		foreach( $users as $user ) {
			// check to see if the user is already activated, if not, activate
			if( ! get_user_meta( $user, 'active', true ) ) {
				wpmem_a_activate_user( $user, $chk_pass );
				$x++;
			}
		}
		
		/** set the return message */
		$sendback = add_query_arg( array('activated' => $x . ' users activated' ), $sendback );
		
		break;
		
	case 'activate-single':
		
		/** validate nonce */
		check_admin_referer( 'activate-user' );
		
		/** get the users */
		$users = $_REQUEST['user'];

		/** check to see if the user is already activated, if not, activate */
		if( ! get_user_meta( $users, 'active', true ) ) {
			
			wpmem_a_activate_user( $users, $chk_pass );
			
			/** get the user data */
			$user_info = get_userdata( $users );

			/** set the return message */
			$sendback = add_query_arg( array('activated' => "$user_info->user_login activated" ), $sendback );
		
		} else {

			/** get the return message */
			$sendback = add_query_arg( array('activated' => "That user is already active" ), $sendback );
		
		}
		
		break;
		
	case 'show':
		
		add_action( 'pre_user_query', 'wpmem_a_pre_user_query' );
		return;
		break;
		
	case 'export':

		$users  = ( isset( $_REQUEST['users'] ) ) ? $_REQUEST['users'] : false;
		include_once( WPMEM_PATH . 'admin/user-export.php' );
		wpmem_export_selected( $users );
		return;
		break;
		
	default:
		return;
		break;

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
function wpmem_users_admin_notices()
{    
	global $pagenow, $user_action_msg;
	if( $pagenow == 'users.php' && isset( $_REQUEST['activated'] ) ) {
		$message = $_REQUEST['activated'];
		echo "<div class=\"updated\"><p>{$message}</p></div>";
	}

	if( $user_action_msg ) {
		echo "<div class=\"updated\"><p>{$user_action_msg}</p></div>";
	}
}


/**
 * Function to add user views to the top list
 *
 * @since 2.8.2
 *
 * @param  array $views
 * @return array $views
 */
function wpmem_users_views( $views )
{
	$arr = array();	
	if( defined( 'WPMEM_USE_EXP' ) && WPMEM_USE_EXP == 1 ) { $arr[] = 'Pending'; }
	if( defined( 'WPMEM_USE_TRL' ) && WPMEM_USE_TRL == 1 ) { $arr[] = 'Trial'; }
	if( defined( 'WPMEM_USE_EXP' ) && WPMEM_USE_EXP == 1 ) { $arr[] = 'Subscription'; $arr[] = 'Expired'; }
	if( defined( 'WPMEM_MOD_REG' ) && WPMEM_MOD_REG == 1 ) { $arr[] = 'Not Active'; }
	$arr[] = 'Not Exported';
	$show = ( isset( $_GET['show'] ) ) ? $_GET['show'] : false;
	
	for( $row = 0; $row < count( $arr ); $row++ )
	{
		$link = "users.php?action=show&amp;show=";
		$lcas = str_replace( " ", "", strtolower( $arr[$row] ) );
		$link.= $lcas;
		$curr = ( $show == $lcas ) ? ' class="current"' : '';
		
		$echolink = true;
		if( $lcas == "notactive" && WPMEM_MOD_REG != 1 ) { $echolink = false; }
		
		if( $echolink ) { $views[$lcas] = "<a href=\"$link\" $curr>$arr[$row] <span class=\"count\"></span></a>"; }
	}

	/** @todo if $show, then run function search query for the users */

	return $views;
}


/**
 * Function to add custom user columns to the user table
 *
 * @since 2.8.2
 *
 * @param  array $columns
 * @return array $columns
 */
function wpmem_add_user_column( $columns ) 
{
	global $wpmem_user_columns;
	$wpmem_user_columns = get_option( 'wpmembers_utfields' );
	
	if( $wpmem_user_columns ) {
		foreach( $wpmem_user_columns as $key => $val ) {

			if( $key == 'active' ) {
			
				if( WPMEM_MOD_REG == 1 ) {
					$columns[$key] = $val;
				}
			
			} else {
				$columns[$key] = $val;
			}
		}
	}
	
	return $columns;
} 


/**
 * Function to add the user content to the custom column
 *
 * @since 2.8.2
 * 
 * @param $value
 * @param $column_name
 * @param $user_id
 * @return The user value for the custom column
 */
function wpmem_add_user_column_content( $value, $column_name, $user_id ) {

	// is the column a WP-Members column?
	global $wpmem_user_columns;
	$is_wpmem = ( is_array( $wpmem_user_columns ) && array_key_exists( $column_name, $wpmem_user_columns ) ) ? true : false;
	
	if( $is_wpmem ) {
	
		switch( $column_name ) {
		
		case 'active':
			if( WPMEM_MOD_REG == 1 ) {
			/**
			 * If the column is "active", then return the value or empty.
			 * Returning in here keeps us from displaying another value.
			 */
				return ( get_user_meta( $user_id , 'active', 'true' ) != 1 ) ? __( 'No' ) : '';
			} else {
				return;
			}
			break;

		case 'user_url':
		case 'user_registered':
			/**
			 * Unlike other fields, website/url is not a meta field
			 */
			$user_info = get_userdata( $user_id );
			return $user_info->$column_name;
			break;
			
		default:
			return get_user_meta( $user_id, $column_name, true );
			break;
		}
	
	}
	
	return $value;
}


/**
 * Activates a user
 *
 * If registration is moderated, sets the activated flag 
 * in the usermeta. Flag prevents login when WPMEM_MOD_REG
 * is true (1). Function is fired from bulk user edit or
 * user profile update.
 *
 * @since 2.4
 *
 * @uses do_action Calls 'wpmem_user_activated' action
 *
 * @param int  $user_id
 * @param bool $chk_pass
 * @uses $wpdb WordPress Database object
 */
function wpmem_a_activate_user( $user_id, $chk_pass = false )
{
	// define new_pass
	$new_pass = '';
	
	// If passwords are user defined skip this
	if( ! $chk_pass ) {
		// generates a password to send the user
		$new_pass = wp_generate_password();
		$new_hash = wp_hash_password( $new_pass );
		
		// update the user with the new password
		global $wpdb;
		$wpdb->update( $wpdb->users, array( 'user_pass' => $new_hash ), array( 'ID' => $user_id ), array( '%s' ), array( '%d' ) );
	}
	
	// if subscriptions can expire, set the user's expiration date
	if( WPMEM_USE_EXP == 1 ) { wpmem_set_exp( $user_id ); }

	// generate and send user approved email to user
	require_once( WPMEM_PATH . 'wp-members-email.php' );
	wpmem_inc_regemail( $user_id, $new_pass, 2 );
	
	// set the active flag in usermeta
	update_user_meta( $user_id, 'active', 1 );
	
	do_action( 'wpmem_user_activated', $user_id );
	
	return;
}


/**
 * Deactivates a user
 *
 * Reverses the active flag from the activation process
 * preventing login when registration is moderated.
 *
 * @since 2.7.1
 *
 * @param int $user_id
 */
function wpmem_a_deactivate_user( $user_id ) {
	update_user_meta( $user_id, 'active', 0 );
}


/**
 * Adjusts user query based on custom views
 *
 * @since 2.8.3
 *
 * @param $user_search
 */
function wpmem_a_pre_user_query( $user_search ) 
{
	global $wpdb;
	$show = $_GET['show'];	
	switch ( $show ) {
	
		case 'notactive':        
		case 'notexported':
			$key = ( $show == 'notactive' ) ? 'active' : 'exported';
			$replace_query = "WHERE 1=1 AND {$wpdb->users}.ID NOT IN (
			 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
				WHERE {$wpdb->usermeta}.meta_key = \"$key\"
				AND {$wpdb->usermeta}.meta_value = '1' )";
			break;
			
		case 'trial':
		case 'subscription':			
			$replace_query = "WHERE 1=1 AND {$wpdb->users}.ID IN (
			 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
				WHERE {$wpdb->usermeta}.meta_key = 'exp_type'
				AND {$wpdb->usermeta}.meta_value = \"$show\" )";
			break;
			
		case 'pending': 		
			$replace_query = "WHERE 1=1 AND {$wpdb->users}.ID IN (
			 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
				WHERE {$wpdb->usermeta}.meta_key = 'exp_type'
				AND {$wpdb->usermeta}.meta_value = \"$show\" )";
			break;

			
		case 'expired':
			$replace_query = "WHERE 1=1 AND {$wpdb->users}.ID IN (
			 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
				WHERE {$wpdb->usermeta}.meta_key = 'expires'
				AND STR_TO_DATE( {$wpdb->usermeta}.meta_value, '%m/%d/%Y' ) < CURDATE()
				AND {$wpdb->usermeta}.meta_value != '01/01/1970' )";
			break;
	}
	
	$user_search->query_where = str_replace( 'WHERE 1=1', $replace_query,	$user_search->query_where );
}

/** End of File **/