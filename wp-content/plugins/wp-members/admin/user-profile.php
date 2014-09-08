<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the user profile screen.
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

 
/** Actions */
add_action( 'show_user_profile', 'wpmem_admin_fields' );
add_action( 'edit_user_profile', 'wpmem_admin_fields' );
add_action( 'profile_update',    'wpmem_admin_update' );


/**
 * add WP-Members fields to the WP user profile screen
 *
 * @since 2.1
 *
 * @global array $current_screen The WordPress screen object
 * @global int   $user_ID The user ID
 */
function wpmem_admin_fields()
{
	global $current_screen, $user_ID;
	$user_id = ( $current_screen->id == 'profile' ) ? $user_ID : $_REQUEST['user_id']; ?>

	<h3><?php 
	/**
	 * Filter the heading for additional profile fields.
	 *
	 * @since 2.8.2
	 *
	 * @param string The default additional fields heading.
	 */
	echo apply_filters( 'wpmem_admin_profile_heading', __( 'WP-Members Additional Fields', 'wp-members' ) ); ?></h3>   
 	<table class="form-table">
		<?php
		// get fields
		$wpmem_fields = get_option( 'wpmembers_fields' );
		// get excluded meta
		$exclude = wpmem_get_excluded_meta( 'admin-profile' );
		
		do_action( 'wpmem_admin_before_profile', $user_id, $wpmem_fields );
		
		foreach( $wpmem_fields as $meta ) {
		
			$valtochk = ''; 

			/** determine which fields to show in the additional fields area */	
			$show = ( $meta[6] == 'n' && ! in_array( $meta[2], $exclude ) ) ? true : false;
			$show = ( $meta[1] == 'TOS' && $meta[4] != 'y' ) ? null : $show;
			
			if( $show ) {
				// is the field required
				$req = ( $meta[5] == 'y' ) ? ' <span class="description">' . __( '(required)' ) . '</span>' : '';
	
				$show_field = '
					<tr>
						<th><label>' . __( $meta[1], 'wp-members' ) . $req . '</label></th>
						<td>';
				$val = htmlspecialchars( get_user_meta( $user_id, $meta[2], 'true' ) );
				if( $meta[3] == 'checkbox' || $meta[3] == 'select' ) {
					$valtochk = $val; 
					$val = $meta[7];
				}
				$show_field.=  wpmem_create_formfield( $meta[2], $meta[3], $val, $valtochk ) . '
						</td>
					</tr>';
				
				/**
				 * Filter the profile field.
				 * 
				 * @since 2.8.2
				 *
				 * @param string $show_field The HTML string for the additional profile field.
				 */
				echo apply_filters( 'wpmem_admin_profile_field', $show_field );
			}
		}

		// see if reg is moderated, and if the user has been activated
		if( WPMEM_MOD_REG == 1 ) { 
			$user_active_flag = get_user_meta( $user_id, 'active', 'true' );
			switch( $user_active_flag ) {
			
				case '':
					$label  = __( 'Activate this user?', 'wp-members' );
					$action = 1;
					break;
				
				case 0: 
					$label  = __( 'Reactivate this user?', 'wp-members' );
					$action = 1;
					break;
				
				case 1:
					$label  = __( 'Deactivate this user?', 'wp-members' );
					$action = 0;
					break;
				
			}?>

			<tr>
				<th><label><?php echo $label; ?></label></th>
				<td><input id="activate_user" type="checkbox" class="input" name="activate_user" value="<?php echo $action; ?>" /></td>
			</tr>

		<?php }  

		// if using subscription model, show expiration
		// if registration is moderated, this doesn't show if user is not active yet.
		if( WPMEM_USE_EXP == 1 ) {
			if( ( WPMEM_MOD_REG == 1 &&  get_user_meta( $user_id, 'active', 'true' ) == 1 ) || ( WPMEM_MOD_REG != 1 ) ) { 
				wpmem_a_extenduser( $user_id );
			} 
		} ?>
		<tr>
			<th><label><?php _e( 'IP @ registration', 'wp-members' ); ?></label></th>
			<td><?php echo get_user_meta( $user_id, 'wpmem_reg_ip', 'true' ); ?></td>
		</tr>
		
		<?php do_action( 'wpmem_admin_after_profile', $user_id, $wpmem_fields ); ?>
		
	</table><?php
}


/**
 * updates WP-Members fields from the WP user profile screen
 *
 * @since 2.1
 */
function wpmem_admin_update()
{
	$user_id = $_REQUEST['user_id'];
	$wpmem_fields = get_option( 'wpmembers_fields' );

	/**
	 * Action before the user profile is updated.
	 *
	 * @since 2.9.2
	 *
	 * @param int   $user_id      The user ID.
	 * @param array $wpmem_fields Array of the custom fields.
	 */
	do_action( 'wpmem_admin_pre_user_update', $user_id, $wpmem_fields );
	
	$fields = array();
	$chk_pass = false;
	foreach( $wpmem_fields as $meta ) {
		if( $meta[6] == "n" && $meta[3] != 'password' && $meta[3] != 'checkbox' ) {
			( isset( $_POST[$meta[2]] ) ) ? $fields[$meta[2]] = $_POST[$meta[2]] : false;
		} elseif( $meta[2] == 'password' && $meta[4] == 'y' ) {
			$chk_pass = true;
		} elseif( $meta[3] == 'checkbox' ) {
			$fields[$meta[2]] = ( isset( $_POST[$meta[2]] ) ) ? $_POST[$meta[2]] : '';
		}
	}
	
	/**
	 * Filter the submitted field values for backend profile update.
	 *
	 * @since 2.8.2
	 *
	 * @param array $fields An array of the posted form values.
	 * @param int   $user_id The ID of the user being updated.
	 */
	$fields = apply_filters( 'wpmem_admin_profile_update', $fields, $user_id ); 
	
	// get any excluded meta fields
	$exclude = wpmem_get_excluded_meta( 'admin-profile' );
	foreach( $fields as $key => $val ) {
		if( ! in_array( $key, $exclude ) ) {
			update_user_meta( $user_id, $key, $val );
		}
	}

	if( WPMEM_MOD_REG == 1 ) {

		$wpmem_activate_user = ( isset( $_POST['activate_user'] ) == '' ) ? -1 : $_POST['activate_user'];
		
		if( $wpmem_activate_user == 1 ) {
			wpmem_a_activate_user( $user_id, $chk_pass );
		} elseif( $wpmem_activate_user == 0 ) {
			wpmem_a_deactivate_user( $user_id );
		}
	}

	( WPMEM_USE_EXP == 1 ) ? wpmem_a_extend_user( $user_id ) : '';
	
	/**
	 * Action after the user profile is updated.
	 *
	 * @since 2.9.2
	 *
	 * @param int $user_id The user ID.
	 */
	do_action( 'wpmem_admin_after_user_update', $user_id );
	
	return;
}

/** End of File **/