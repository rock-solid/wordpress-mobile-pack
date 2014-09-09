<?php
/**
 * WP-Members Dialog Functions
 *
 * Handles functions that output front-end dialogs to end users.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014  Chad Butler (email : plugins@butlerblog.com)
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2014
 */


/**
 * include the form building functions
 */
include_once( 'forms.php' );


if ( ! function_exists( 'wpmem_inc_loginfailed' ) ):
/**
 * Login Failed Dialog
 *
 * Returns the login failed error message.
 *
 * @since 1.8
 *
 * @return string $str the generated html for the login failed message
 */
function wpmem_inc_loginfailed() 
{ 
	// defaults
	$defaults = array(
		'div_before'     => '<div align="center" id="wpmem_msg">',
		'div_after'      => '</div>', 
		'heading_before' => '<h2>',
		'heading'        => __( 'Login Failed!', 'wp-members' ),
		'heading_after'  => '</h2>',
		'p_before'       => '<p>',
		'message'        => __( 'You entered an invalid username or password.', 'wp-members' ),
		'p_after'        => '</p>',
		'link'           => '<a href="' . $_SERVER['REQUEST_URI'] . '">' . __( 'Click here to continue.', 'wp-members' ) . '</a>'
	);
	
	/**
	 * Filter the login failed dialog arguments.
	 *
	 * @since 2.9.0
	 *
	 * @param array An array of arguments to merge with defaults.
	 */
	$args = apply_filters( 'wpmem_login_failed_args', '' );
	
	// merge $args with defaults and extract
	extract( wp_parse_args( $args, $defaults ) );
	
	$str = $div_before 
		. $heading_before . $heading . $heading_after 
		. $p_before . $message . $p_after 
		. $p_before . $link . $p_after
		. $div_after;
	
	/**
	 * Filter the login failed dialog.
	 *
	 * @since ?.?
	 *
	 * @param string $str The login failed dialog.
	 */
	$str = apply_filters( 'wpmem_login_failed', $str );

	return $str;
}
endif;


if ( ! function_exists( 'wpmem_inc_regmessage' ) ):
/**
 * Message Dialog
 *
 * Returns various dialogs and error messages.
 *
 * @since 1.8
 *
 * @param  string $toggle error message toggle to look for specific error messages
 * @param  string $msg a message that has no toggle that is passed directly to the function
 * @return string $str The final HTML for the message
 */
function wpmem_inc_regmessage( $toggle, $msg = '' )
{
	// defaults
	$defaults = array(
		'div_before' => '<div class="wpmem_msg" align="center">',
		'div_after'  => '</div>', 
		'p_before'   => '<p>',
		'p_after'    => '</p>',
		'toggles'    => array( 
							'user', 
							'email', 
							'success', 
							'editsuccess', 
							'pwdchangerr', 
							'pwdchangesuccess', 
							'pwdreseterr', 
							'pwdresetsuccess' 
						)
	);
	
	/**
	 * Filter the message arguments.
	 *
	 * @since 2.9.0
	 *
	 * @param array An array of arguments to merge with defaults.
	 */
	$args = apply_filters( 'wpmem_msg_args', '' );

	// get dialogs set in the db
	$dialogs = get_option( 'wpmembers_dialogs' );

	for( $r = 0; $r < count( $defaults['toggles'] ); $r++ ) {
		if( $toggle == $defaults['toggles'][$r] ) {
			$msg = __( stripslashes( $dialogs[$r+1] ), 'wp-members' );
			break;
		}
	}
	$defaults['msg'] = $msg;
	
	/**
	 * Filter the message array
	 *
	 * @since 2.9.2
	 *
	 * @param array  $defaults An array of the defaults.
	 * @param string $toggle   The toggle that we are on, if any.
	 */
	$defaults = apply_filters( 'wpmem_msg_dialog_arr', $defaults, $toggle );
	
	// merge $args with defaults and extract
	extract( wp_parse_args( $args, $defaults ) );
	
	$str = $div_before . $p_before . stripslashes( $msg ) . $p_after . $div_after;

	/**
	 * Filter the message.
	 *
	 * @since ?.?
	 *
	 * @param string $str The message.
	 */
	return apply_filters( 'wpmem_msg_dialog', $str );

}
endif;


if( ! function_exists( 'wpmem_inc_memberlinks' ) ):
/**
 * Member Links Dialog
 *
 * Outputs the links used on the members area.
 *
 * @since 2.0
 *
 * @param  string $page
 * @return string $str
 */
function wpmem_inc_memberlinks( $page = 'members' ) 
{
	global $user_login; 
	
	$link = wpmem_chk_qstr();
	
	/**
	 * Filter the log out link.
	 *
	 * @since 2.8.3
	 *
	 * @param string $link The default logout link.
	 */
	$logout = apply_filters( 'wpmem_logout_link', $link . 'a=logout' );
	
	switch( $page ) {
	
	case 'members':
		$str  = '<ul><li><a href="'  .$link . 'a=edit">' . __( 'Edit My Information', 'wp-members' ) . '</a></li>
				<li><a href="' . $link . 'a=pwdchange">' . __( 'Change Password', 'wp-members' ) . '</a></li>';
		if( WPMEM_USE_EXP == 1 && function_exists( 'wpmem_user_page_detail' ) ) { $str .= wpmem_user_page_detail(); }
		$str.= '</ul>';
		/**
		 * Filter the links displayed on the User Profile page (logged in state).
		 *
		 * @since 2.8.3
		 *
		 * @param string $str The default links.
		 */
		$str = apply_filters( 'wpmem_member_links', $str );
		break;
		
	case 'register':	
		$str = '<p>' . sprintf( __( 'You are logged in as %s', 'wp-members' ), $user_login ) . '</p>
			<ul>
				<li><a href="' . $logout . '">' . __( 'Click to log out.', 'wp-members' ) . '</a></li>
				<li><a href="' . get_option('home') . '">' . __( 'Begin using the site.', 'wp-members' ) . '</a></li>
			</ul>';
		/**
		 * Filter the links displayed on the Register page (logged in state).
		 *
		 * @since 2.8.3
		 *
		 * @param string $str The default links.
		 */
		$str = apply_filters( 'wpmem_register_links', $str );
		break;	
	
	case 'login':

		$str = '<p>
		  	' . sprintf( __( 'You are logged in as %s', 'wp-members' ), $user_login ) . '<br />
		  	<a href="' . $logout . '">' . __( 'Click to log out', 'wp-members' ) . '</a>
			</p>';
		/**
		 * Filter the links displayed on the Log In page (logged in state).
		 *
		 * @since 2.8.3
		 *
		 * @param string $str The default links.
		 */
		$str = apply_filters( 'wpmem_login_links', $str );
		break;	
			
	case 'status':
		$str ='<p>
			' . sprintf( __( 'You are logged in as %s', 'wp-members' ), $user_login ) . '  | 
			<a href="' . $logout . '">' . __( 'click to log out', 'wp-members' ) . '</a>
			</p>';
		break;
	
	}
	
	return $str;
}
endif;


if ( ! function_exists( 'wpmem_page_pwd_reset' ) ):
/**
 * Password reset forms
 *
 * This function creates both password reset and forgotten
 * password forms for page=password shortcode.
 *
 * @since 2.7.6
 *
 * @param  string $wpmem_regchk
 * @param  string $content
 * @return string $content
 */
function wpmem_page_pwd_reset( $wpmem_regchk, $content )
{
	if( is_user_logged_in() ) {
	
		switch( $wpmem_regchk ) { 
				
		case "pwdchangempty":
			$content = wpmem_inc_regmessage( $wpmem_regchk, __( 'Password fields cannot be empty', 'wp-members' ) );
			$content = $content . wpmem_inc_changepassword();
			break;

		case "pwdchangerr":
			$content = wpmem_inc_regmessage( $wpmem_regchk );
			$content = $content . wpmem_inc_changepassword();
			break;

		case "pwdchangesuccess":
			$content = $content . wpmem_inc_regmessage( $wpmem_regchk );
			break;

		default:
			$content = $content . wpmem_inc_changepassword();
			break;				
		}
	
	} else {
	
		switch( $wpmem_regchk ) {

		case "pwdreseterr":
			$content = $content 
				. wpmem_inc_regmessage( $wpmem_regchk )
				. wpmem_inc_resetpassword();
			$wpmem_regchk = ''; // clear regchk
			break;

		case "pwdresetsuccess":
			$content = $content . wpmem_inc_regmessage( $wpmem_regchk );
			$wpmem_regchk = ''; // clear regchk
			break;

		default:
			$content = $content . wpmem_inc_resetpassword();
			break;
		}
		
	}
	
	return $content;

}
endif;


if ( ! function_exists( 'wpmem_page_user_edit' ) ):
/**
 * Creates a user edit page
 *
 * @since 2.7.6
 *
 * @param  string $wpmem_regchk
 * @param  string $content
 * @return string $content
 */
function wpmem_page_user_edit( $wpmem_regchk, $content )
{
	global $wpmem_a, $wpmem_themsg;
	/**
	 * Filter the default User Edit heading for shortcode.
	 *
	 * @since 2.7.6
	 *
	 * @param string The default edit mode heading.
	 */	
	$heading = apply_filters( 'wpmem_user_edit_heading', __( 'Edit Your Information', 'wp-members' ) );
	
	if( $wpmem_a == "update") { $content.= wpmem_inc_regmessage( $wpmem_regchk, $wpmem_themsg ); }
	$content = $content . wpmem_inc_registration( 'edit', $heading );
	
	return $content;
}
endif;

/** End of File **/