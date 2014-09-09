<?php
/**
 * WP-Members Export Functions
 *
 * Mananges exporting users to a CSV file.
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
  * Exports selected users.
  *
  * @since 2.8.7
  *
  * @param array $user_arr The array of users.
  */
function wpmem_export_selected( $user_arr )
{
	/**
	 * Output needs to be buffered, start the buffer
	 */
	ob_start();

	// $user_arr = get_option( 'wpmembers_export' );
	
	header( "Content-Description: File Transfer" );
	header( "Content-type: application/octet-stream" );

	// generate a filename based on date of export
	$today = date( "m-d-y" ); 
	$filename = "wp-members-user-export-" . $today . ".csv";
	header( "Content-Disposition: attachment; filename=\"$filename\"" );
	header( "Content-Type: text/csv; charset=" . get_option( 'blog_charset' ), true );

	echo "\xEF\xBB\xBF"; // UTF-8 BOM

	// get the fields
	$wpmem_fields = get_option( 'wpmembers_fields' );

	// do the header row
	$hrow = "User ID,Username,";

	for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
		$hrow.= $wpmem_fields[$row][1] . ",";
	}

	if( WPMEM_MOD_REG == 1 ) {
		$hrow.= __( 'Activated?', 'wp-members' ) . ",";
	}

	if( WPMEM_USE_EXP == 1 ) {
		$hrow.= __( 'Subscription', 'wp-members' ) . "," . __( 'Expires', 'wp-members' ) . ",";
	}

	$hrow.= __( 'Registered', 'wp-members' ) . ",";
	$hrow.= __( 'IP', 'wp-members' );
	$data = $hrow . "\r\n";

	// we used the fields array once, rewind so we can use it again
	reset( $wpmem_fields );

	// build the data, delimit by commas, use \n switch for new line
	foreach( $user_arr as $user ) {

		$user_info = get_userdata( $user );

		$data.= '"' . $user_info->ID . '","' . $user_info->user_login . '",';
		
		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
			
			$wp_user_fields = array( 'user_email', 'user_nicename', 'user_url', 'display_name' );
			if( in_array( $wpmem_fields[$row][2], $wp_user_fields ) ) {
				$data.= '"' . $user_info->$wpmem_fields[$row][2] . '",';
			} else {
				$data.= '"' . get_user_meta( $user, $wpmem_fields[$row][2], true ) . '",';
			}
			
		}
		
		if( WPMEM_MOD_REG == 1 ) {
		
			if( get_user_meta( $user, 'active', 1 ) ) {
				$data.= '"' . __( 'Yes' ) . '",';
			} else {
				$data.= '"' . __( 'No' ) . '",';
			}
			
		}

		if( WPMEM_USE_EXP ==1 ) {
		
			$data.= '"' . get_user_meta( $user, "exp_type", true ) . '",';
			$data.= '"' . get_user_meta( $user, "expires", true  ) . '",';
		
		}
		
		$data.= '"' . $user_info->user_registered . '",';
		$data.= '"' . get_user_meta( $user, "wpmem_reg_ip", true ). '"';
		$data.= "\r\n";
		
		// update the user record as being exported
		update_user_meta( $user, 'exported', 1 );
	}

	echo $data; 

	// update_option( 'wpmembers_export', '' );

	/**
	 * Clear the buffer 
	 */
	ob_flush();

	
	exit();
}


/**
 * Exports all users
 *
 * @since 2.8.7
 */
function wpmem_export_all_users()
{
	/**
	 * Output needs to be buffered, start the buffer
	 */
	ob_start();

	/**
	 * Get all of the users
	 */
	$user_arr = get_users();


	/**
	 * Generate headers and a filename based on date of export
	 */
	$today = date( "m-d-y" ); 
	$filename = "user-export-" . $today . ".csv";
	header( "Content-Description: File Transfer" );
	header( "Content-type: application/octet-stream" );
	header( "Content-Disposition: attachment; filename=" . $filename );
	header( "Content-Type: text/csv; charset=" . get_option( 'blog_charset' ), true );

	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	
	/**
	 * get the fields
	 */
	$wpmem_fields = get_option( 'wpmembers_fields' );

	/**
	 * do the header row
	 */
	$hrow = "User ID,Username,";
	for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
		$hrow.= $wpmem_fields[$row][1] . ",";
	}

	if( WPMEM_MOD_REG == 1 ) {
		$hrow.= __( 'Activated?', 'wp-members' ) . ",";
	}
	if( WPMEM_USE_EXP == 1 ) {
		$hrow.= __( 'Subscription', 'wp-members' ) . "," . __( 'Expires', 'wp-members' ) . ",";
	}

	$hrow.= __( 'Registered', 'wp-members' ) . ",";
	$hrow.= __( 'IP', 'wp-members' );
	$data = $hrow . "\r\n";

	/**
	 * we used the fields array once,
	 * rewind so we can use it again
	 */
	reset( $wpmem_fields );

	/**
	 * Loop through the array of users,
	 * build the data, delimit by commas, wrap fields with double quotes, 
	 * use \n switch for new line
	 */
	foreach( $user_arr as $user ) {

		$data.= '"' . $user->ID . '","' . $user->user_login . '",';
		
		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
			
			$wp_user_fields = array( 'user_email', 'user_nicename', 'user_url', 'display_name' );
			if( in_array( $wpmem_fields[$row][2], $wp_user_fields ) ) {
				$data.= '"' . $user->$wpmem_fields[$row][2] . '",';
			} else {
				$data.= '"' . get_user_meta( $user->ID, $wpmem_fields[$row][2], true ) . '",';
			}
			
		}
		
		if( WPMEM_MOD_REG == 1 ) {
		
			if( get_user_meta( $user->ID, 'active', 1 ) ) {
				$data.= '"' . __( 'Yes' ) . '",';
			} else {
				$data.= '"' . __( 'No' ) . '",';
			}
			
		}

		if( WPMEM_USE_EXP ==1 ) {
			$data.= '"' . get_user_meta( $user->ID, "exp_type", true ) . '",';
			$data.= '"' . get_user_meta( $user->ID, "expires", true ) . '",';
		}
		
		$data.= '"' . $user->user_registered . '",';
		$data.= '"' . get_user_meta( $user->ID, "wpmem_reg_ip", true ) . '"';
		$data.= "\r\n";

	}

	/**
	 * We are done, output the CSV
	 */
	echo $data; 
	
	/**
	 * Clear the buffer 
	 */
	ob_flush();
	
	exit();
	
}

/** End of File **/