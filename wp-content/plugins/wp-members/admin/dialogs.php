<?php
/**
 * WP-Members Admin Functions
 *
 * Handles functions that output admin dialogs to adminstrative users.
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

 
/** Actions */
if( ! is_multisite() ) {
	add_action('wp_dashboard_setup', 'butlerblog_dashboard_widget');
}


/**
 * Outputs the various admin warning messages
 *
 * @since 2.8
 * 
 * @param string $did_update Contains the update message
 * @param array  $wpmem_settings Array containing the plugin settings
 */
function wpmem_a_do_warnings( $did_update, $wpmem_settings )
{
	//$wpmem_settings = get_option( 'wpmembers_settings' ); 
	$wpmem_dialogs = get_option( 'wpmembers_dialogs' );
	
	if( $did_update != false ) {

/* 		if( $chkreq == "err" ) { ?>
			<div class="error"><p><strong><?php _e('Settings were saved, but you have required fields that are not set to display!', 'wp-members'); ?></strong><br /><br />
				<?php _e('Note: This will not cause an error for the end user, as only displayed fields are validated.  However, you should still check that your displayed and required fields match up.  Mismatched fields are highlighted below.', 'wp-members'); ?></p></div>
		<?php } elseif( $add_field_err_msg ) { ?>
        	<div class="error"><p><strong><?php echo $add_field_err_msg; ?></p></div>
        <?php } else { ?> */ ?>
			<div id="message" class="updated fade"><p><strong><?php echo $did_update; ?></strong></p></div>
		<?php //}

	}


	/**
	 * Warning messages
 	 */

	// settings allow anyone to register
	if( get_option( 'users_can_register' ) != 0 && $wpmem_settings[11] == 0 ) { 
		wpmem_a_warning_msg(1);
	}

	// settings allow anyone to comment
	if( get_option( 'comment_registration' ) !=1 && $wpmem_settings[11] == 0 ) { 
		wpmem_a_warning_msg(2);
	} 
	
	// rss set to full text feeds
	if( get_option( 'rss_use_excerpt' ) !=1 && $wpmem_settings[11] == 0 ) { 
		wpmem_a_warning_msg(3);
	} 

	// holding registrations but haven't changed default successful registration message
	if( $wpmem_settings[11] == 0 && $wpmem_settings[5] == 1 && $wpmem_dialogs[3] == 'Congratulations! Your registration was successful.<br /><br />You may now login using the password that was emailed to you.' ) { 
		wpmem_a_warning_msg(4);
	}  

	// turned off registration but also have set to moderate and/or email new registrations
	if( $wpmem_settings[11] == 0 && $wpmem_settings[7] == 1 ) { 
		if( $wpmem_settings[5] == 1 || $wpmem_settings[4] ==1 ) { 
			wpmem_a_warning_msg(5);
		}  
	}
	
	// haven't entered recaptcha api keys
	if( $wpmem_settings[11] == 0 && $wpmem_settings[6] == 1 ) {
		$wpmem_captcha = get_option('wpmembers_captcha');
		if( !$wpmem_captcha['recaptcha']['public'] || !$wpmem_captcha['recaptcha']['private'] ) {
			wpmem_a_warning_msg(6);
		}
	}
	
}


/**
 * Assembles the various admin warning messages
 *
 * @since 2.4.0
 * 
 * @param int $msg The number for which message should be displayed
 */
function wpmem_a_warning_msg( $msg )
{
	$strong_msg = $remain_msg = $span_msg = '';
	
	switch( $msg ) {

	case 1: 

		$strong_msg = __( 'Your WP settings allow anyone to register - this is not the recommended setting.', 'wp-members' );
		$remain_msg = sprintf( __( 'You can %s change this here %s making sure the box next to "Anyone can register" is unchecked.', 'wp-members'), '<a href="options-general.php">', '</a>' );
		$span_msg   = __( 'This setting allows a link on the /wp-login.php page to register using the WP native registration process thus circumventing any registration you are using with WP-Members. In some cases, this may suit the users wants/needs, but most users should uncheck this option. If you do not change this setting, you can choose to ignore these warning messages under WP-Members Settings.', 'wp-members' );

		break;
	
	case 2:

		$strong_msg = __( 'Your WP settings allow anyone to comment - this is not the recommended setting.', 'wp-members' );
		$remain_msg = sprintf( __( 'You can %s change this here %s by checking the box next to "Users must be registered and logged in to comment."', 'wp-members' ), '<a href="options-discussion.php">', '</a>' );
		$span_msg   = __( 'This setting allows any users to comment, whether or not they are registered. Depending on how you are using WP-Members will determine whether you should change this setting or not. If you do not change this setting, you can choose to ignore these warning messages under WP-Members Settings.', 'wp-members' );

		break; 

	case 3: 

		$strong_msg = __( 'Your WP settings allow full text rss feeds - this is not the recommended setting.', 'wp-members' );
		$remain_msg = sprintf( __( 'You can %s change this here %s by changing "For each article in a feed, show" to "Summary."', 'wp-members' ), '<a href="options-reading.php">' , '</a>' );
		$span_msg   = __( 'Leaving this set to full text allows anyone to read your protected content in an RSS reader. Changing this to Summary prevents this as your feeds will only show summary text.', 'wp-members' );

		break;
	
	case 4: 
	
		$strong_msg = __( 'You have set WP-Members to hold registrations for approval', 'wp-members' );
		$remain_msg = __( 'but you have not changed the default message for "Registration Completed" under "WP-Members Dialogs and Error Messages."  You should change this message to let users know they are pending approval.', 'wp-members' );
	
		break;

	case 5: 

		$strong_msg = __( 'You have set WP-Members to turn off the registration process', 'wp-members' );
		$remain_msg = __( 'but you also set to moderate and/or email admin new registrations.  You will need to set up a registration page for users to register.', 'wp-members' );	

		break;
		
	case 6:
	
		$strong_msg = __( 'You have turned on reCAPTCHA', 'wp-members');
		$remain_msg = __( 'but you have not entered API keys.  You will need both a public and private key.  The CAPTCHA will not display unless a valid API key is included.', 'wp-members' );
		
		break;

	}
	
	if ( $span_msg ) { $span_msg = ' [<span title="' . $span_msg . '">why is this?</span>]'; }
	echo '<div class="error"><p><strong>' . $strong_msg . '</strong> ' . $remain_msg . $span_msg . '</p></div>';

}


/**
 * Assemble the side meta box
 *
 * @since 2.8
 */
function wpmem_a_meta_box()
{
	?><div class="postbox">
		<h3><span>WP-Members Information</span></h3>
		<div class="inside">

			<p><strong><?php _e('Version:', 'wp-members'); echo "&nbsp;".WPMEM_VERSION; ?></strong><br />
				<a href="http://rocketgeek.com/plugins/wp-members/quick-start-guide/"><?php _e( 'Quick Start Guide', 'wp-members' ); ?></a><br />
				<a href="http://rocketgeek.com/plugins/wp-members/users-guide/"><?php _e( 'Online User Guide', 'wp-members' ); ?></a><br />
				<a href="http://rocketgeek.com/plugins/wp-members/users-guide/faqs/"><?php _e( 'FAQs', 'wp-members' ); ?></a>
			<?php if( ! defined( 'WPMEM_REMOVE_ATTR' ) ) { ?>
				<br /><br /><a href="http://rocketgeek.com/about/site-membership-subscription/">Find out how to get access</a> to WP-Members private members forum, premium code snippets, tutorials, and add-on modules!
			<?php } ?>
			</p>
		
			<p><i>
			<?php _e( 'Thank you for using WP-Members', 'wp-members' ); ?>&trade;!<br /><br />
			<?php _e( 'A plugin developed by', 'wp-members' ); ?>&nbsp;<a href="http://butlerblog.com">Chad Butler</a><br />
			<?php _e( 'Follow', 'wp-members' ); ?> ButlerBlog: <a href="http://feeds.butlerblog.com/butlerblog" target="_blank">RSS</a> | <a href="http://www.twitter.com/butlerblog" target="_blank">Twitter</a><br />
			Copyright &copy; 2006-<?php echo date("Y"); ?><br /><br />
			Premium support and installation service <a href="http://rocketgeek.com/about/site-membership-subscription/">available at rocketgeek.com</a>.
			</i></p>
		</div>
	</div><?php
}


/**
 * Assemble the rocketgeek.com rss feed box
 *
 * @since 2.8.0
 */
function wpmem_a_rss_box()
{
	?><div class="postbox">
		<h3><span><?php _e( 'Latest from RocketGeek', 'wp-members' ); ?></span></h3>
		<div class="inside"><?php
		wp_widget_rss_output(array(
			'url' => 'http://rocketgeek.com/feed/',  //put your feed URL here
			'title' => __( 'Latest from RocketGeek', 'wp-members' ),
			'items' => 4, //how many posts to show
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 0
		));?>
		</div>
	</div><?php
}


/**
 * Add the dashboard widget
 *
 * @since 2.8.0
 */
function butlerblog_dashboard_widget() {
	wp_add_dashboard_widget( 'dashboard_custom_feed', __( 'Latest from ButlerBlog', 'wp-members' ), 'butlerblog_feed_output' );
}
 

/**
 * Output the rss feed for the dashboard widget
 *
 * @since 2.8.0
 */
function butlerblog_feed_output() {
    echo '<div class="rss-widget">';
    wp_widget_rss_output(array(
        'url' => 'http://feeds.feedburner.com/butlerblog',
        'title' => __( 'Latest from ButlerBlog', 'wp-members' ),
        'items' => 5,
        'show_summary' => 0,
        'show_author' => 0,
        'show_date' => 1
    ));
    echo "</div>";
}

/** End of File **/