<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the emails tab.
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
 * builds the emails panel
 *
 * @since 2.7
 *
 * @param array $wpmem_settings
 */
function wpmem_a_build_emails( $wpmem_settings )
{ 
	if( $wpmem_settings[5] == 0 ) {
		$wpmem_email_title_arr = array(
			array( __( "New Registration", 'wp-members' ), 'wpmembers_email_newreg' )
		);
	} else {
        $wpmem_email_title_arr = array(
			array( __( "Registration is Moderated", 'wp-members' ), 'wpmembers_email_newmod' ),
			array( __( "Registration is Moderated, User is Approved", 'wp-members' ), 'wpmembers_email_appmod' )
		);
	}
	array_push( 
		$wpmem_email_title_arr,
        array( __( "Password Reset", 'wp-members' ), 'wpmembers_email_repass' )
	);
	if( $wpmem_settings[4] == 1 ) {
		array_push(
			$wpmem_email_title_arr,
			array( __( "Admin Notification", 'wp-members' ), 'wpmembers_email_notify' )
		);
	}
	array_push(
		$wpmem_email_title_arr,
		array( __( "Email Signature", 'wp-members' ), 'wpmembers_email_footer' )
    ); ?>
	<div class="metabox-holder">

		<div id="post-body">
			<div id="post-body-content">
				<div class="postbox">	
					<h3><span>WP-Members <?php _e( 'Email Messages', 'wp-members' ); ?></span></h3>
					<div class="inside">
						<p>
						<?php _e( 'You can customize the content of the emails sent by the plugin.', 'wp-members' ); ?><br />
						<a href="http://rocketgeek.com/plugins/wp-members/users-guide/customizing-emails/" target="_blank">
						<?php _e( 'A list of shortcodes is available here.', 'wp-members' ); ?></a>
						</p>
						<hr />
						<form name="updateemailform" id="updateemailform" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
						<?php wp_nonce_field( 'wpmem-update-emails' ); ?>
							<table class="form-table"> 
								<tr valign="top"> 
									<th scope="row"><?php _e( 'Set a custom email address', 'wp-members' ); ?></th> 
									<td><input type="text" name="wp_mail_from" size="40" value="<?php echo get_option( 'wpmembers_email_wpfrom' ); ?>" />&nbsp;<span class="description"><?php _e( '(optional)', 'wp-members' ); ?> email@yourdomain.com</span></td> 
								</tr>
								<tr valign="top"> 
									<th scope="row"><?php _e( 'Set a custom email name', 'wp-members' ); ?></th> 
									<td><input type="text" name="wp_mail_from_name" size="40" value="<?php echo stripslashes( get_option( 'wpmembers_email_wpname' ) ); ?>" />&nbsp;<span class="description"><?php _e( '(optional)', 'wp-members' ); ?> John Smith</span></td>
								</tr>
								<tr><td colspan="2"><hr /></td></tr>
							
							<?php for( $row = 0; $row < ( count( $wpmem_email_title_arr ) - 1 ); $row++ ) { 
							
								$arr = get_option( $wpmem_email_title_arr[$row][1] );
							?>
								<tr valign="top"><td colspan="2"><strong><?php echo $wpmem_email_title_arr[$row][0]; ?></strong></td></tr>
								<tr valign="top"> 
									<th scope="row"><?php _e( 'Subject', 'wp-members' ); ?></th> 
									<td><input type="text" name="<?php echo $wpmem_email_title_arr[$row][1] . '_subj'; ?>" size="80" value="<?php echo stripslashes( $arr['subj'] ); ?>"></td> 
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( 'Body', 'wp-members' ); ?></th>
									<td><textarea name="<?php echo $wpmem_email_title_arr[$row][1] . '_body'; ?>" rows="12" cols="50" id="" class="large-text code"><?php echo stripslashes( $arr['body'] ); ?></textarea></td>
								</tr>
								<tr><td colspan="2"><hr /></td></tr>
							<?php } 
							
								$arr = get_option( $wpmem_email_title_arr[$row][1] ); ?>
							
								<tr valign="top">
									<th scope="row"><strong><?php echo $wpmem_email_title_arr[$row][0]; ?></strong> <span class="description"><?php _e( '(optional)', 'wp-members' ); ?></span></th>
									<td><textarea name="<?php echo $wpmem_email_title_arr[$row][1] . '_body'; ?>" rows="10" cols="50" id="" class="large-text code"><?php echo stripslashes( $arr ); ?></textarea></td>
								</tr>
								<tr><td colspan="2"><hr /></td></tr>			
								<tr valign="top"> 
									<th scope="row">&nbsp;</th> 
									<td>
										<input type="hidden" name="wpmem_admin_a" value="update_emails" />
										<input type="submit" name="save" class="button-primary" value="<?php _e( 'Update Emails', 'wp-members' ); ?> &raquo;" />
									</td> 
								</tr>	
							</table> 
						</form>
					</div><!-- .inside -->
				</div><!-- #post-box -->
				<div class="postbox">
					<h3><span><?php _e( 'Need help?', 'wp-members' ); ?></span></h3>
					<div class="inside">
						<strong><i>See the <a href="http://rocketgeek.com/plugins/wp-members/users-guide/plugin-settings/emails/" target="_blank">Users Guide on email options</a>.</i></strong>
					</div>
				</div>
			</div> <!-- #post-body-content -->
		</div><!-- #post-body -->
	</div><!-- .metabox-holder -->
	<?php
}


/**
 * Updates the email message settings
 *
 * @since 2.8
 *
 * @return string The emails updated message
 */
function wpmem_update_emails()
{
	//check nonce
	check_admin_referer( 'wpmem-update-emails' );
	
	$wpmem_settings = get_option( 'wpmembers_settings' );
			
	// update the email address (if applicable)
	( $_POST['wp_mail_from'] ) ? update_option( 'wpmembers_email_wpfrom', $_POST['wp_mail_from'] ) : delete_option( 'wpmembers_email_wpfrom' );
	( $_POST['wp_mail_from_name'] ) ? update_option( 'wpmembers_email_wpname', $_POST['wp_mail_from_name'] ) : delete_option( 'wpmembers_email_wpname' );
			
	// update the various emails being used
	( $wpmem_settings[5] == 0 ) ? $arr = array( 'wpmembers_email_newreg' ) : $arr = array( 'wpmembers_email_newmod', 'wpmembers_email_appmod' );
	array_push( $arr, 'wpmembers_email_repass' );
	( $wpmem_settings[4] == 1 ) ? array_push( $arr, 'wpmembers_email_notify' ) : false;
	array_push(	$arr, 'wpmembers_email_footer' );
			
	for( $row = 0; $row < ( count( $arr ) - 1 ); $row++ ) {
		$arr2 = array( 
			"subj" => $_POST[$arr[$row] . '_subj'],
			"body" => $_POST[$arr[$row] . '_body']
		);
		update_option( $arr[$row], $arr2, false );
		$arr2 = '';
	}
			
	// updated the email footer
	update_option( $arr[$row], $_POST[$arr[$row] . '_body'], false );
			
	return __('WP-Members emails were updated', 'wp-members');

}

/** End of File **/