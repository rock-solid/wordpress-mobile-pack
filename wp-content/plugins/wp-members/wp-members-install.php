<?php
/**
 * WP-Members Installation Functions
 *
 * Functions to install and upgrade WP-Members
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
 * Installs or upgrades the plugin
 *
 * @since 2.2.2
 */
function wpmem_do_install()
{
	/*
		if you need to force an install, set $chk_force = true
		
		IMPORTANT NOTES: 
		
		(1) This will override any settings you already have
			for any of the plugin settings.  
		
		(2) This will not effect any WP settings or registered 
			users.
	*/
	
	$chk_force = false;

	if( !get_option( 'wpmembers_settings' ) || $chk_force == true ) {

		// this is a clean install (or an upgrade from 2.1 or earlier)
		
		$wpmem_settings = array( WPMEM_VERSION, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
		update_option( 'wpmembers_settings', $wpmem_settings, '', 'yes' ); // using update_option to allow for forced update
		
		// order, label, optionname, type, display, required, native, checked value, checked by default
		$wpmem_fields_options_arr = array(
			array( 1,  'First Name',         'first_name',       'text',     'y', 'y', 'y' ),	
			array( 2,  'Last Name',          'last_name',        'text',     'y', 'y', 'y' ),
			array( 3,  'Address 1',          'addr1',            'text',     'y', 'y', 'n' ),
			array( 4,  'Address 2',          'addr2',            'text',     'y', 'n', 'n' ),	
			array( 5,  'City',               'city',             'text',     'y', 'y', 'n' ),
			array( 6,  'State',              'thestate',         'text',     'y', 'y', 'n' ),
			array( 7,  'Zip',                'zip',              'text',     'y', 'y', 'n' ),
			array( 8,  'Country',            'country',          'text',     'y', 'y', 'n' ),
			array( 9,  'Day Phone',          'phone1',           'text',     'y', 'y', 'n' ),
			array( 10, 'Email',              'user_email',       'text',     'y', 'y', 'y' ),
			array( 11, 'Confirm Email',      'confirm_email',    'text',     'n', 'n', 'n' ),
			array( 12, 'Website',            'user_url',         'text',     'n', 'n', 'y' ),
			array( 13, 'Biographical Info',  'description',      'textarea', 'n', 'n', 'y' ),
			array( 14, 'Password',           'password',         'password', 'n', 'n', 'n' ),
			array( 15, 'Confirm Password',   'confirm_password', 'password', 'n', 'n', 'n' ),
			array( 16, 'TOS',                'tos',              'checkbox', 'n', 'n', 'n', 'agree', 'n' )
		);
		update_option( 'wpmembers_fields', $wpmem_fields_options_arr, '', 'yes' ); // using update_option to allow for forced update
		
		$wpmem_dialogs_arr = array(
			"This content is restricted to site members.  If you are an existing user, please log in.  New users may register below.",
			"Sorry, that username is taken, please try another.",
			"Sorry, that email address already has an account.<br />Please try another.",
			"Congratulations! Your registration was successful.<br /><br />You may now log in using the password that was emailed to you.",
			"Your information was updated!",
			"Passwords did not match.<br /><br />Please try again.",
			"Password successfully changed!",
			"Either the username or email address do not exist in our records.",
			"Password successfully reset!<br /><br />An email containing a new password has been sent to the email address on file for your account."
		);
		
		update_option( 'wpmembers_dialogs', $wpmem_dialogs_arr, '', 'yes' ); // using update_option to allow for forced update

		append_tos( 'new' );

		append_email();
		
		// if it's a new install, use the Twenty Twelve stylesheet
		update_option( 'wpmembers_style', plugin_dir_url ( __FILE__ ) . 'css/generic-no-float.css', '', 'yes' );
		
	} else {
	
		update_captcha();
	
		update_dialogs();
	
		append_email();
	
		$wpmem_settings = get_option( 'wpmembers_settings' );
		
		switch( count ( $wpmem_settings ) ) {
		
		  case 4:
		
			// upgrading from 2.2.x
			// update version, insert new toggles, keep other settings
			$wpmem_newsettings = array(
				WPMEM_VERSION, 			//  0 version
				$wpmem_settings[1],		//  1 block posts
				$wpmem_settings[2],		//  2 block pages
				'0', 					//  3 show excerpts on posts/pages
				'0',					//  4 notify admin
				'0',					//  5 moderate registration
				'0',					//  6 toggle captcha
				'0',					//  7 turn off registration
				'1',					//  8 add use legacy forms (tables)
				'0',					//  9 time based expiration
				'0',					// 10 offer trial period
				$wpmem_settings[3]		// 11 ignore warnings
			);
			update_option( 'wpmembers_settings', $wpmem_newsettings );
			append_tos( '2.2+' );
			break;
			
		  case 12:
		
			// upgrading from 2.5.1 or higher
			$wpmem_newsettings = array(
				WPMEM_VERSION, 			//  0 version
				$wpmem_settings[1],		//  1 block posts
				$wpmem_settings[2],		//  2 block pages
				$wpmem_settings[3],		//  3 show excerpts on posts/pages
				$wpmem_settings[4],		//  4 notify admin
				$wpmem_settings[5],		//  5 moderate registration
				$wpmem_settings[6],		//  6 toggle captcha
				$wpmem_settings[7],		//  7 turn off registration
				$wpmem_settings[8],		//  8 add use legacy forms (tables)
				$wpmem_settings[9],		//  9 time based expiration
				$wpmem_settings[10],	// 10 offer trial period
				$wpmem_settings[11]		// 11 ignore warnings		
			);
			update_option( 'wpmembers_settings', $wpmem_newsettings );
			break;
		
		  default: // count($wpmem_settings) > 4 && count($wpmem_settings) < 12 
		
			// upgrading from 2.3.0, 2.3.1, 2.3.2, 2.4.0, or 2.5.0
			// update version, insert captcha toggle, keep other settings
			$wpmem_newsettings = array(
				WPMEM_VERSION, 			//  0 version
				$wpmem_settings[1],		//  1 block posts
				$wpmem_settings[2],		//  2 block pages
				$wpmem_settings[3],		//  3 show excerpts on posts/pages
				$wpmem_settings[4],		//  4 notify admin
				$wpmem_settings[5],		//  5 moderate registration
				'0',					//  6 toggle captcha
				$wpmem_settings[6],		//  7 turn off registration
				'1',					//  8 add use legacy forms (tables)
				$wpmem_settings[7],		//  9 time based expiration
				$wpmem_settings[8],		// 10 offer trial period
				$wpmem_settings[9]		// 11 ignore warnings
			);
			update_option( 'wpmembers_settings', $wpmem_newsettings );
			append_tos( '2.2+');
			break;
		}
	}
}


/**
 * Adds TOS field to upgrades if appropriate
 *
 * @since 2.4
 */
function append_tos( $upgrade )
{		
	// check if _tos has been put in before; if not, populate dummy data	
	if( !get_option('wpmembers_tos') ) {
		$dummy_tos = "Put your TOS (Terms of Service) text here.  You can use HTML markup.";	
		update_option( 'wpmembers_tos', $dummy_tos );

		if( $upgrade == '2.2+' ) {
			// append a TOS field to the end of the fields array
			$fields = get_option( 'wpmembers_fields' );

			$x = count( $fields );
			$x = $x + 1;

			$fields[] = array( $x, 'TOS', 'tos', 'checkbox', 'y', 'y', 'n', 'agree', 'n' );

			update_option( 'wpmembers_fields', $fields );
		}
	}
}


/**
 * Adds the fields for email messages
 *
 * @since 2.7
 */
function append_email()
{

	//email for a new registration
	$subj = 'Your registration info for [blogname]';		
	$body = 'Thank you for registering for [blogname]

Your registration information is below.
You may wish to retain a copy for your records.

username: [username]
password: [password]

You may login here:
[reglink]

You may change your password here:
[members-area]
';
		
	$arr = array( 
		"subj" => $subj,
		"body" => $body
	);
	
	if( ! get_option( 'wpmembers_email_newreg' ) ) { 
		update_option( 'wpmembers_email_newreg', $arr, false ); 
	}
	
	$arr = $subj = $body = '';
	
	// email for new registration, registration is moderated
	$subj = 'Thank you for registering for [blogname]';
	$body =	'Thank you for registering for [blogname]. 
Your registration has been received and is pending approval.
You will receive login instructions upon approval of your account
';

	$arr = array( 
		"subj" => $subj,
		"body" => $body
	);
	
	if( ! get_option( 'wpmembers_email_newmod' ) ) { 
		update_option( 'wpmembers_email_newmod', $arr, false );
	}
	
	$arr = $subj = $body = '';
	
	// email for registration is moderated, user is approved
	$subj = 'Your registration for [blogname] has been approved';
	$body = 'Your registration for [blogname] has been approved.

Your registration information is below.
You may wish to retain a copy for your records.

username: [username]
password: [password]

You may login and change your password here:
[members-area]

You originally registered at:
[reglink]
';
	
	$arr = array( 
		"subj" => $subj,
		"body" => $body
	);
	
	if( ! get_option( 'wpmembers_email_appmod' ) ) { 
		update_option( 'wpmembers_email_appmod', $arr, false );
	}
	
	$arr = $subj = $body = '';
	
	// email for password reset
	$subj = 'Your password reset for [blogname]';
	$body = 'Your password for [blogname] has been reset

Your new password is included below. You may wish to retain a copy for your records.

password: [password]
';

	$arr = array( 
		"subj" => $subj,
		"body" => $body
	);
	
	if( ! get_option( 'wpmembers_email_repass' ) ) { 
		update_option( 'wpmembers_email_repass', $arr, false );
	}
	
	$arr = $subj = $body = '';

	// email for admin notification
	$subj = 'New user registration for [blogname]';
	$body = 'The following user registered for [blogname]:
	
username: [username]
email: [email]

[fields]
This user registered here:
[reglink]

user IP: [user-ip]
	
activate user: [activate-user]
';
	
		$arr = array( 
		"subj" => $subj,
		"body" => $body
	);
	
	if( ! get_option( 'wpmembers_email_notify' ) ) { 
		update_option( 'wpmembers_email_notify', $arr, false );
	}
	
	$arr = $subj = $body = '';

	// email footer (no subject)
	$body = '----------------------------------
This is an automated message from [blogname]
Please do not reply to this address';

	if( ! get_option( 'wpmembers_email_footer' ) ) { 
		update_option( 'wpmembers_email_footer', $body, false );
	}
	
	return true;
}


/**
 * Checks the dialogs array for string changes.
 *
 * @since 2.9.3
 */
function update_dialogs()
{
	$wpmem_dialogs_arr = get_option( 'wpmembers_dialogs' );
	$do_update = false;
	
	if( $wpmem_dialogs_arr[0] == "This content is restricted to site members.  If you are an existing user, please login.  New users may register below." ) {
		$wpmem_dialogs_arr[0] = "This content is restricted to site members.  If you are an existing user, please log in.  New users may register below.";
		$do_update = true;
	}
	
	if( $wpmem_dialogs_arr[3] == "Congratulations! Your registration was successful.<br /><br />You may now login using the password that was emailed to you." ) {
		$wpmem_dialogs_arr[3] = "Congratulations! Your registration was successful.<br /><br />You may now log in using the password that was emailed to you.";
		$do_update = true;
	}
	
	if( $do_update ) {
		update_option( 'wpmembers_dialogs', $wpmem_dialogs_arr, '', 'yes' );
	}
	
	return;
}


/**
 * Checks the captcha settings and updates accordingly.
 *
 * @since 2.9.5
 */
function update_captcha()
{
	$captcha_settings = get_option( 'wpmembers_captcha' );
	
	// if there captcha settings, update them
	if( $captcha_settings && ! array_key_exists( 'recaptcha', $captcha_settings ) ) {
		
		// check to see if the array keys are numeric
		$is_numeric = false;
		foreach( $captcha_settings as $key => $setting ) {
			$is_numeric = ( is_int( $key ) ) ? true : $is_numeric;
		}
		
		if( $is_numeric ) {
			$new_captcha = array();
			// these are old recaptcha settings
			$new_captcha['recaptcha']['public']  = $captcha_settings[0];
			$new_captcha['recaptcha']['private'] = $captcha_settings[1];
			$new_captcha['recaptcha']['theme']   = $captcha_settings[2];
			update_option( 'wpmembers_captcha', $new_captcha );
		}
		
	}
	
	return;
}


/** End of File **/