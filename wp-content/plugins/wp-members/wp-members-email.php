<?php
/**
 * WP-Members Email Functions
 *
 * Generates emails sent by the plugin.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014 Chad Butler
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2014
 *
 * Functions Included:
 * * wpmem_inc_regemail
 * * wpmem_notify_admin
 * * wpmem_mail_from
 * * wpmem_mail_from_name
 */


if ( ! function_exists( 'wpmem_inc_regemail' ) ):
/**
 * Builds emails for the user
 *
 * @since 1.8
 *
 * @uses wp_mail
 *
 * @param int $user_id
 * @param string $password
 * @param string $toggle
 */
function wpmem_inc_regemail( $user_id, $password, $toggle, $fields = null )
{
	/** get the user ID */
	$user = new WP_User( $user_id );
	
	/** userdata for default shortcodes */
	$user_login  = stripslashes( $user->user_login );
	$user_email  = stripslashes( $user->user_email );
	$blogname    = wp_specialchars_decode( get_option ( 'blogname' ), ENT_QUOTES );
	$exp_type    = ( WPMEM_USE_EXP == 1 ) ? get_user_meta( $user_id, 'exp_type', 'true' ) : '';
	$exp_date    = ( WPMEM_USE_EXP == 1 ) ? get_user_meta( $user_id, 'expires', 'true' )  : '';
	$wpmem_msurl = get_option( 'wpmembers_msurl', null );
	$reg_link    = esc_url( get_user_meta( $user_id, 'wpmem_reg_url', true ) );

	/** Setup default shortcodes */
	$shortcd = array( '[blogname]', '[username]', '[password]', '[reglink]', '[members-area]', '[exp-type]', '[exp-data]' );
	$replace = array( $blogname, $user_login, $password, $reg_link, $wpmem_msurl, $exp_type, $exp_date );
	
	/** handle backward compatibility for customizations that may call the email function directly */
	if( ! $fields ) {
		$fields = get_option( 'wpmembers_fields' );
	}
	
	/** create the custom field shortcodes */
	foreach( $fields as $field ) {
		$shortcd[] = '[' . $field[2] . ']'; 
		$replace[] = get_user_meta( $user_id, $field[2], true );
	}

	/**
	 * Determine which email is being sent
	 */
	switch ($toggle) {
	
	case 0: 
		//this is a new registration
		$arr = get_option( 'wpmembers_email_newreg' );
		/**
		 * Filters the new registration email.
		 *
		 * @since 2.7.4
		 *
		 * @param string $arr['body'] The body content of the new registration email.
		 */
		$arr['body'] = apply_filters( 'wpmem_email_newreg', $arr['body'] );
		break;
		
	case 1:
		//registration is moderated
		$arr = get_option( 'wpmembers_email_newmod' );
		/**
		 * Filters the new moderated registration email.
		 *
		 * @since 2.7.4
		 *
		 * @param string $arr['body'] The body content of the moderated registration email.
		 */
		$arr['body'] = apply_filters( 'wpmem_email_newmod', $arr['body'] );
		break;

	case 2:
		//registration is moderated, user is approved
		$arr = get_option( 'wpmembers_email_appmod' );
		/**
		 * Filters the reset password email.
		 *
		 * @since 2.7.4
		 *
		 * @param string $arr['body'] The body content of the reset password email.
		 */
		$arr['body'] = apply_filters( 'wpmem_email_appmod', $arr['body'] );
		break;

	case 3:
		//this is a password reset
		$arr = get_option( 'wpmembers_email_repass' );
		/**
		 * Filters the approved registration email.
		 *
		 * @since 2.7.4
		 *
		 * @param string $arr['body'] The body content of the approved registration email.
		 */
		$arr['body'] = apply_filters( 'wpmem_email_repass', $arr['body'] );
		break;
		
	}
	
	/* Get the subject and body, filter shortcodes */
	$subj = str_replace( $shortcd, $replace, $arr['subj'] );
	$body = str_replace( $shortcd, $replace, $arr['body'] );
	
	/* Get the email footer and append to the $body */
	$foot = get_option ( 'wpmembers_email_footer' );
	$foot = str_replace( $shortcd, $replace, $foot );
	$body.= "\r\n" . $foot;
	
	/* Apply filters (if set) for the sending email address */
	add_filter( 'wp_mail_from', 'wpmem_mail_from' );
	add_filter( 'wp_mail_from_name', 'wpmem_mail_from_name' );
	
	/**
	 * Filters the email headers.
	 *
	 * @since 2.7.4
	 *
	 * @param mixed The email headers (default = null).
	 */
	$headers = apply_filters( 'wpmem_email_headers', '' );

	/* Send the message */
	wp_mail( $user_email, stripslashes( $subj ), stripslashes( $body ), $headers );

}
endif;


if( ! function_exists( 'wpmem_notify_admin' ) ):
/**
 * Builds the email for admin notification of new user registration
 *
 * @since 2.3
 *
 * @uses wp_mail
 *
 * @param int $user_id
 * @param array $wpmem_fields
 */
function wpmem_notify_admin( $user_id, $wpmem_fields )
{
	$wp_user_fields = array( 'user_login', 'user_nicename', 'user_url', 'user_registered', 'display_name', 'first_name', 'last_name', 'nickname', 'description' );
	$user     = get_userdata( $user_id );
	$blogname = wp_specialchars_decode( get_option ( 'blogname' ), ENT_QUOTES );
	
	$user_ip  = get_user_meta( $user_id, 'wpmem_reg_ip', true );
	$reg_link = esc_url( get_user_meta( $user_id, 'wpmem_reg_url', true ) );
	$act_link = get_bloginfo ( 'wpurl' ) . "/wp-admin/user-edit.php?user_id=".$user_id;

	$exp_type = ( WPMEM_USE_EXP == 1 ) ? get_user_meta( $user_id, 'exp_type', 'true' ) : '';
	$exp_date = ( WPMEM_USE_EXP == 1 ) ? get_user_meta( $user_id, 'expires',  'true' ) : '';	
	
	$field_str = '';
	foreach ( $wpmem_fields as $meta ) {
		if( $meta[4] == 'y' ) {
			$name = $meta[1];
			if( ! in_array( $meta[2], wpmem_get_excluded_meta( 'email' ) ) ) {
				if( ( $meta[2] != 'user_email' ) && ( $meta[2] != 'password' ) ) {
					if( $meta[2] == 'user_url' ) {
						$val = esc_url( $user->user_url );
					} elseif( in_array( $meta[2], $wp_user_fields ) ) {
						$val = esc_html( $user->$meta[2] );
					} else {
						$val = esc_html( get_user_meta( $user_id, $meta[2], 'true' ) );
					}
				
					$field_str.= "$name: $val \r\n";
				}
			}
		}
	}
	
	/** Setup default shortcodes */
	$shortcd = array( '[blogname]', '[username]', '[email]', '[reglink]', '[exp-type]', '[exp-data]', '[user-ip]', '[activate-user]', '[fields]' );
	$replace = array( $blogname, $user->user_login, $user->user_email, $reg_link, $exp_type, $exp_date, $user_ip, $act_link, $field_str );
	
	/** create the custom field shortcodes */
	foreach( $wpmem_fields as $field ) {
		$shortcd[] = '[' . $field[2] . ']'; 
		$replace[] = get_user_meta( $user_id, $field[2], true );
	}
	
	$arr  = get_option( 'wpmembers_email_notify' );
	
	$subj = str_replace( $shortcd, $replace, $arr['subj'] );
	$body = str_replace( $shortcd, $replace, $arr['body'] );
	
	$foot = get_option ( 'wpmembers_email_footer' );
	$foot = str_replace( $shortcd, $replace, $foot );
	
	$body.= "\r\n" . $foot;
	
	/**
	 * Filters the admin notification email.
	 *
	 * @since 2.8.2
	 *
	 * @param string $body The admin notification email body.
	 */
	$body = apply_filters( 'wpmem_email_notify', $body );
	
	/* Apply filters (if set) for the sending email address */
	add_filter( 'wp_mail_from', 'wpmem_mail_from' );
	add_filter( 'wp_mail_from_name', 'wpmem_mail_from_name' );

	/**
	 * Filters the address the admin notification is sent to.
	 *
	 * @since 2.7.5
	 *
	 * @param string The email address of the admin to send to.
	 */
	$admin_email = apply_filters( 'wpmem_notify_addr', get_option( 'admin_email' ) );
	
	/**
	 * Filters the email headers.
	 *
	 * @since 2.7.4
	 *
	 * @param mixed The email headers (default = null).
	 */
	$headers = apply_filters( 'wpmem_email_headers', '' );
	
	/* Send the message */
	wp_mail( $admin_email, stripslashes( $subj ), stripslashes( $body ), $headers );

}
endif;


/**
 * Filters the wp_mail from address (if set)
 *
 * @since 2.7
 *
 * @param  string $email
 * @return string $email
 */
function wpmem_mail_from( $email )
{
	if( get_option( 'wpmembers_email_wpfrom' ) ) {
		$email = get_option( 'wpmembers_email_wpfrom' );
	}
    return $email;
}


/**
 * Filters the wp_mail from name (if set)
 *
 * @since 2.7
 *
 * @param  string $name
 * @return string $name
 */
function wpmem_mail_from_name( $name )
{
	if( get_option( 'wpmembers_email_wpname' ) ) {
		$name = stripslashes( get_option( 'wpmembers_email_wpname' ) );
	}
    return $name;
}

/** End of File **/