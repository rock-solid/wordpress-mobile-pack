<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the dialogs tab.
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
 * builds the dialogs panel
 *
 * @since 2.2.2
 */
function wpmem_a_build_dialogs()
{ 
	$wpmem_dialogs  = get_option( 'wpmembers_dialogs' );

	$wpmem_dialog_title_arr = array(
    	__( "Restricted post (or page), displays above the login/registration form", 'wp-members' ),
        __( "Username is taken", 'wp-members' ),
        __( "Email is registered", 'wp-members' ),
        __( "Registration completed", 'wp-members' ),
        __( "User update", 'wp-members' ),
        __( "Passwords did not match", 'wp-members' ),
        __( "Password changes", 'wp-members' ),
        __( "Username or email do not exist when trying to reset forgotten password", 'wp-members' ),
        __( "Password reset", 'wp-members' ) 
    ); ?>
	<div class="metabox-holder has-right-sidebar">
	
		<div class="inner-sidebar">
			<?php wpmem_a_meta_box(); ?>
			<div class="postbox">
				<h3><span><?php _e( 'Need help?', 'wp-members' ); ?></span></h3>
				<div class="inside">
					<strong><i>See the <a href="http://rocketgeek.com/plugins/wp-members/users-guide/plugin-settings/dialogs/" target="_blank">Users Guide on dialogs</a>.</i></strong>
				</div>
			</div>
		</div> <!-- .inner-sidebar -->

		<div id="post-body">
			<div id="post-body-content">
				<div class="postbox">
					<h3><span>WP-Members <?php _e( 'Dialogs and Error Messages', 'wp-members' ); ?></span></h3>
					<div class="inside">
						<p><?php printf( __( 'You can customize the text for dialogs and error messages. Simple HTML is allowed %s etc.', 'wp-members' ), '- &lt;p&gt;, &lt;b&gt;, &lt;i&gt;,' ); ?></p>
						<form name="updatedialogform" id="updatedialogform" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>"> 
						<?php wp_nonce_field( 'wpmem-update-dialogs' ); ?>
							<table class="form-table">        
							<?php for( $row = 0; $row < count( $wpmem_dialog_title_arr ); $row++ ) { ?>
								<tr valign="top"> 
									<th scope="row"><?php echo $wpmem_dialog_title_arr[$row]; ?></th> 
									<td><textarea name="<?php echo "dialogs_".$row; ?>" rows="3" cols="50" id="" class="large-text code"><?php echo stripslashes( $wpmem_dialogs[$row] ); ?></textarea></td> 
								</tr>
							<?php } ?>
							
							<?php $wpmem_tos = stripslashes( get_option( 'wpmembers_tos' ) ); ?>
								<tr valign="top"> 
									<th scope="row"><?php _e( 'Terms of Service (TOS)', 'wp-members' ); ?></th> 
									<td><textarea name="dialogs_tos" rows="3" cols="50" id="" class="large-text code"><?php echo $wpmem_tos; ?></textarea></td> 
								</tr>		
								<tr valign="top"> 
									<th scope="row">&nbsp;</th> 
									<td>
										<input type="hidden" name="wpmem_admin_a" value="update_dialogs" />
										<input type="submit" name="save" class="button-primary" value="<?php _e( 'Update Dialogs', 'wp-members' ); ?> &raquo;" />
									</td> 
								</tr>	
							</table> 
						</form>
					</div><!-- .inside -->
				</div><!-- #post-box -->
			</div><!-- #post-body-content -->
		</div><!-- #post-body -->
	</div> <!-- .metabox-holder -->
	<?php
}


/**
 * Updates the dialog settings
 *
 * @since 2.8
 *
 * @return string The dialogs updated message
 */
function wpmem_update_dialogs()
{
	//check nonce
	check_admin_referer( 'wpmem-update-dialogs' );
	
	$wpmem_dialogs = get_option( 'wpmembers_dialogs' );

	for( $row = 0; $row < count( $wpmem_dialogs); $row++ ) {
		$dialog = "dialogs_" . $row;
		$wpmem_newdialogs[$row] = $_POST[$dialog];
	}

	update_option( 'wpmembers_dialogs', $wpmem_newdialogs );
	$wpmem_dialogs = $wpmem_newdialogs;
		
	// Terms of Service
	update_option( 'wpmembers_tos', $_POST['dialogs_tos'] );		
		
	return __( 'WP-Members dialogs were updated', 'wp-members' );	
}

/** End of File **/