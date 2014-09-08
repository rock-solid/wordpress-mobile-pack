<?php
/**
 * WP-Members Functions for WordPress Native Registration
 *
 * Handles functions that add WP-Members custom fields to the 
 * WordPress native (wp-login.php) registration and the 
 * Users > Add New screen.
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
 * * wpmem_do_wp_register_form
 * * wpmem_do_wp_newuser_form
 */


/**
 * Appends WP-Members registration fields to wp-login.php registration form.
 *
 * @since 2.8.7
 */
function wpmem_do_wp_register_form()
{
	$wpmem_fields = get_option( 'wpmembers_fields' );
	foreach ( $wpmem_fields as $field ) {
	//for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
	
		$req = ( $field[5] == 'y' ) ? ' <span class="req">' . __( '(required)' ) . '</span>' : '';
		
		if( $field[4] == 'y' && $field[2] != 'user_email' ) {
		
			if( $field[3] == 'checkbox' ) {
			
				if( $field[2] == 'tos' ) {
					$tos_content = stripslashes( get_option( 'wpmembers_tos' ) );
					if( stristr( $tos_content, '[wp-members page="tos"' ) ) {
						
						$tos_content = " " . $tos_content;
						$ini = strpos( $tos_content, 'url="' );
						$ini += strlen( 'url="' );
						$len = strpos( $tos_content, '"]', $ini ) - $ini;
						$link = substr( $tos_content, $ini, $len );
						$tos_pop = '<a href="' . $link . '" target="_blank">';

					} else { 
						$tos_pop = "<a href=\"#\" onClick=\"window.open('" . WP_PLUGIN_URL . "/wp-members/wp-members-tos.php','mywindow');\">";
					}
					/**
					 * Filter the TOS link text.
					 *
					 * When this filter is used for the WP native registration, the $toggle parameter is not passed.
					 *
					 * @since 2.7.5
					 *
					 * @param string The text and link for the TOS.
					 */
					$tos = apply_filters( 'wpmem_tos_link_txt', sprintf( __( 'Please indicate that you agree to the %s TOS %s', 'wp-members' ), $tos_pop, '</a>' ) );
				
				}
			
				$label = ( $field[2] == 'tos' ) ? $tos : __( $field[2], 'wp-members' );

				$val = ( isset( $_POST[ $field[2] ] ) ) ? $_POST[ $field[2] ] : '';
				$val = ( ! $_POST && $field[8] == 'y' ) ? $field[7] : $val;
			
				$row_before = '<p class="wpmem-checkbox">';
				$label = '<label for="' . $field[2] . '">' . $label . $req;
				$input = wpmem_create_formfield( $field[2], $field[3], $field[7], $val );
				$row_after = '</label></p>';
				
			} else {
			
				$row_before = '<p>';
				$label = '<label for="' . $field[2] . '">' . __( $field[1], 'wp-members' ) . $req . '<br />';
				
				
				// determine the field type and generate accordingly...
				
				switch( $field[3] ) {
				
				case( 'select' ):
					$val = ( isset( $_POST[ $field[2] ] ) ) ? $_POST[ $field[2] ] : '';
					$input = wpmem_create_formfield( $field[2], $field[3], $field[7], $val );
					break;
					
				case( 'textarea' ):
					$input = '<textarea name="' . $field[2] . '" id="' . $field[2] . '" class="textarea">'; 
					$input.= ( isset( $_POST[ $field[2] ] ) ) ? esc_textarea( $_POST[ $field[2] ] ) : ''; 
					$input.= '</textarea>';		
					break;

				default:
					$input = '<input type="' . $field[3] . '" name="' . $field[2] . '" id="' . $field[2] . '" class="input" value="'; 
					$input.= ( $_POST ) ? esc_attr( $_POST[ $field[2] ] ) : ''; 
					$input.= '" size="25" />';
					break;
				}
				
				$row_after = '</label></p>';
			
			}
			
			// if the row is set to display, add the row to the form array
			$rows[$field[2]] = array(
				'type'         => $field[3],
				'row_before'   => $row_before,
				'label'        => $label,
				'field'        => $input,
				'row_after'    => $row_after
			);
		}
	}
	
	/**
	 * Filter the native registration form rows.
	 *
	 * @since 2.9.3.
	 *
	 * @param array $rows The custom rows added to the form.
	 */
	$rows = apply_filters( 'wpmem_native_form_rows', $rows );
	
	foreach( $rows as $row_item ) {
		if( $row_item['type'] == 'checkbox' ) {
			echo $row_item['row_before'] . $row_item['field'] . $row_item['label'] . $row_item['row_after'];
		} else { 
			echo $row_item['row_before'] . $row_item['label'] . $row_item['field'] . $row_item['row_after'];
		}
	}
	
}


/**
 * Appends WP-Members registration fields to wp-login.php registration form.
 *
 * @since 2.9.0
 */
function wpmem_do_wp_newuser_form()
{

	echo '<table class="form-table"><tbody>';
	
	$wpmem_fields = get_option( 'wpmembers_fields' );
	$exclude = wpmem_get_excluded_meta( 'register' );

	foreach( $wpmem_fields as $field ) {

		if( $field[4] == 'y' && $field[6] == 'n' && ! in_array( $field[2], $exclude ) ) {

			$req = ( $field[5] == 'y' ) ? ' <span class="description">' . __( '(required)' ) . '</span>' : '';
		
			echo '<tr>
				<th scope="row">
					<label for="' . $field[2] . '">' . __( $field[1], 'wp-members' ) . $req . '</label>
				</th>
				<td>';
		
			// determine the field type and generate accordingly...
			
			switch( $field[3] ) {
			
			case( 'select' ):
				$val = ( isset( $_POST[ $field[2] ] ) ) ? $_POST[ $field[2] ] : '';
				echo wpmem_create_formfield( $field[2], $field[3], $field[7], $val );
				break;
				
			case( 'textarea' ):
				echo '<textarea name="' . $field[2] . '" id="' . $field[2] . '" class="textarea">'; 
				echo ( isset( $_POST[ $field[2] ] ) ) ? esc_textarea( $_POST[ $field[2] ] ) : ''; 
				echo '</textarea>';		
				break;
				
			case( 'checkbox' ):
				echo wpmem_create_formfield( $field[2], $field[3], $field[7], '' );
				break;

			default:
				echo '<input type="' . $field[3] . '" name="' . $field[2] . '" id="' . $field[2] . '" class="input" value="'; echo ( $_POST ) ? esc_attr( $_POST[ $field[2] ] ) : ''; echo '" size="25" />';
				break;
			}
				
			echo '</td>
				</tr>';

		}
	}
	echo '</tbody></table>';

}
/** End of File **/