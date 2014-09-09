<?php
/**
 * WP-Members User Functions
 *
 * Handles primary functions that are carried out in most
 * situations. Includes commonly used utility functions.
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


if ( ! function_exists( 'wpmem_user_profile' ) ):
/**
 * add WP-Members fields to the WP user profile screen
 *
 * @since 2.6.5
 *
 * @global int $user_id
 */
function wpmem_user_profile()
{
	global $user_id; 
	/**
	 * Filter the heading for the user profile additional fields.
	 *
	 * @since 2.9.1
	 *
	 * @param string The default heading.
	 */?>
    <h3><?php echo apply_filters( 'wpmem_user_profile_heading', __( 'Additional Information', 'wp-members' ) ); ?></h3>  
 	<table class="form-table">
		<?php
		// get fields
		$wpmem_fields = get_option( 'wpmembers_fields' );
		// get excluded meta
		$exclude = wpmem_get_excluded_meta( 'user-profile' );
		
		foreach( $wpmem_fields as $meta ) {
		
			$val = get_user_meta( $user_id, $meta[2], 'true' );
			$valtochk = '';
			
			$chk_tos = true;
			if( $meta[2] == 'tos' && $val == 'agree' ) { 
				$chk_tos = false; 
				echo wpmem_create_formfield( $meta[2], 'hidden', $val );
			}
			
			// do we exclude the row?
			$chk_pass = ( in_array( $meta[2], $exclude ) ) ? false : true;
		
			if( $meta[4] == "y" && $meta[6] == "n" && $chk_tos && $chk_pass ) { 
				// if there are any required fields
				$req = ( $meta[5] == 'y' ) ? ' <span class="description">' . __( '(required)' ) . '</span>' : '';
				$show_field = ' 
					<tr>
						<th><label>' . __( $meta[1], 'wp-members' ) . $req . '</label></th>
						<td>';
					
					$val = get_user_meta( $user_id, $meta[2], 'true' );
					if( $meta[3] == 'checkbox' || $meta[3] == 'select' ) {
						$valtochk = $val; 
						$val = $meta[7];
					}
				$show_field.= wpmem_create_formfield( $meta[2], $meta[3], $val, $valtochk ) . '
						</td>
					</tr>';

				/**
				 * Filter the field for user profile additional fields.
				 *
				 * @since 2.9.1
				 *
				 * @parma string $show_field The HTML string of the additional field.
				 */
				echo apply_filters( 'wpmem_user_profile_field', $show_field );
			} 
		} ?>
	</table><?php
}
endif;


/**
 * updates WP-Members fields from the WP user profile screen
 *
 * @since 2.6.5
 *
 * @global int $user_id
 */
function wpmem_profile_update()
{
	global $user_id;
	// get the fields
	$wpmem_fields = get_option( 'wpmembers_fields' );
	// get any excluded meta fields
	$exclude = wpmem_get_excluded_meta( 'user-profile' );
	foreach( $wpmem_fields as $meta ) {
		// if this is not an excluded meta field
		if( ! in_array( $meta[2], $exclude ) ) {
			// if the field is user editable, 
			if( $meta[4] == "y" && $meta[6] == "n" && $meta[3] != 'password' ) {
			
				// check for required fields
				$chk = '';
				if( $meta[5] == "n" || ( ! $meta[5] ) ) { $chk = 'ok'; }
				if( $meta[5] == "y" && $_POST[$meta[2]] != '' ) { $chk = 'ok'; }
				
				// check for field value
				$field_val = ( isset( $_POST[$meta[2]] ) ) ? $_POST[$meta[2]] : '';
				
				if( $chk == 'ok' ) { 
					update_user_meta( $user_id, $meta[2], $field_val ); 
				} 
			}
		}
	} 
}

/** End of File **/