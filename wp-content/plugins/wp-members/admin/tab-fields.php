<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage the fields tab.
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
 * Builds the fields panel
 *
 * @since 2.2.2
 *
 * @param  string $wpmem_fields deprecated in 2.8.0
 * @global string $add_field_err_msg The fields error message
 */
function wpmem_a_build_fields() 
{ 
	global $add_field_err_msg;
	$add_toggle = ( isset( $_GET['edit'] ) ) ? $_GET['edit'] : false;
	$wpmem_fields = get_option( 'wpmembers_fields' ); 
	?>
	<div class="metabox-holder">
		
		<div id="post-body">
			<div id="post-body-content">
			<?php if( $add_toggle && ( isset( $_POST['wpmem_admin_a'] ) != 'edit_field' ) ) { 
				wpmem_a_field_edit( 'edit', $wpmem_fields, $add_toggle );
			 } else {
				if( ! $add_field_err_msg ) { wpmem_a_field_table( $wpmem_fields ); }
				wpmem_a_field_edit( 'add' ); 
			} ?>

				<div class="postbox">
					<h3><span><?php _e( 'Need help?', 'wp-members' ); ?></span></h3>
					<div class="inside">
						<strong><i>See the <a href="http://rocketgeek.com/plugins/wp-members/users-guide/plugin-settings/fields/" target="_blank">Users Guide on the field manager</a>.</i></strong>
					</div>
				</div>
			</div><!-- #post-body-content -->
		</div><!-- #post-body -->
 
	</div><!-- .metabox-holder -->
	<?php
}


/**
 * reorders the fields on DnD
 *
 * @since 2.5.1
 */
function wpmem_a_field_reorder()
{
	// start fresh
	$new_order = $wpmem_old_fields = $wpmem_new_fields = $key = $row = '';

	$new_order = $_REQUEST['orderstring'];
	$new_order = explode( "&", $new_order );	
	
	// loop through $new_order to create new field array
	$wpmem_old_fields = get_option( 'wpmembers_fields' );
	for( $row = 0; $row < count( $new_order ); $row++ )  {
		if( $row > 0 ) {
			$key = $new_order[$row];
			$key = substr( $key, 15 ); //echo $key.", ";
			
			for( $x = 0; $x < count( $wpmem_old_fields ); $x++ )  {
				
				if( $wpmem_old_fields[$x][0] == $key ) {
					$wpmem_new_fields[$row - 1] = $wpmem_old_fields[$x];
				}
			}
		}
	}
	
	update_option( 'wpmembers_fields', $wpmem_new_fields ); 

	die(); // this is required to return a proper result

}


/**
 * Updates fields
 *
 * @since 2.8
 *
 * @param  string $action The field update action (update_fields|add|edit)
 * @global string $add_field_err_msg The add field error message
 * @return string $did_update The fields update message
 *
 * @todo   apply some additional form validation to the add/update process
 */
function wpmem_update_fields( $action )
{
	// get the current fields
	$wpmem_fields    = get_option( 'wpmembers_fields' );
	$wpmem_ut_fields = get_option( 'wpmembers_utfields' );

	if( $action == 'update_fields' ) {
	
		// check nonce
		check_admin_referer( 'wpmem-update-fields' );

		// @todo - need some additional form validation here
		
		// update user table fields
		$arr = ( isset( $_POST['ut_fields'] ) ) ? $_POST['ut_fields'] : '';
		update_option( 'wpmembers_utfields', $arr );
	
		// rebuild the array, don't touch user_email - it's always mandatory
		$nrow = 0;
		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {

			// check to see if the field is checked for deletion, and if not, add it to the new array.
			$delete_field = "del_" . $wpmem_fields[$row][2];
			$delete_field = ( isset( $_POST[$delete_field] ) ) ? $_POST[$delete_field] : false; 
			if( $delete_field != "delete" ) {

				for( $i = 0; $i < 4; $i++ ) {
					$wpmem_newfields[$nrow][$i] = $wpmem_fields[$row][$i];
				}
				
				$wpmem_newfields[$nrow][0] = $nrow + 1;

				$display_field = $wpmem_fields[$row][2] . "_display"; 
				$require_field = $wpmem_fields[$row][2] . "_required";
				$checked_field = $wpmem_fields[$row][2] . "_checked";

				if( $wpmem_fields[$row][2] != 'user_email' ){
					$wpmem_newfields[$nrow][4] = ( isset( $_POST[$display_field] ) ) ? $_POST[$display_field] : '';
					$wpmem_newfields[$nrow][5] = ( isset( $_POST[$require_field] ) ) ? $_POST[$require_field] : '';
				} else {
					$wpmem_newfields[$nrow][4] = 'y';
					$wpmem_newfields[$nrow][5] = 'y';		
				}

				if( $wpmem_newfields[$nrow][4] != 'y' && $wpmem_newfields[$nrow][5] == 'y' ) { $chkreq = "err"; }
				$wpmem_newfields[$nrow][6] = $wpmem_fields[$row][6];
				$wpmem_newfields[$nrow][7] = ( isset( $wpmem_fields[$row][7] ) ) ? $wpmem_fields[$row][7] : '';
				if( $wpmem_fields[$row][3] == 'checkbox' ) { 
					if( isset( $_POST[$checked_field] ) && $_POST[$checked_field] == 'y' ) { //for debugging: echo "checked: " . $_POST[$checked_field];
						$wpmem_newfields[$nrow][8] = 'y';
					} else {
						$wpmem_newfields[$nrow][8] = 'n';
					}
				}
			
				$nrow = $nrow + 1;
			}
			
		}
		
		update_option( 'wpmembers_fields', $wpmem_newfields );
		$did_update = __( 'WP-Members fields were updated', 'wp-members' );
		
	} elseif( $action == 'add_field' || 'edit_field' ) {
	
		// check nonce
		check_admin_referer( 'wpmem-add-fields' );
	
		global $add_field_err_msg;
	
		// error check that field label and option name are included and unique
		$add_field_err_msg = ( ! $_POST['add_name'] )   ? __( 'Field Label is required for adding a new field. Nothing was updated.', 'wp-members' ) : false;
		$add_field_err_msg = ( ! $_POST['add_option'] ) ? __( 'Option Name is required for adding a new field. Nothing was updated.', 'wp-members' ) : false;
		
		// check for duplicate field names
		$chk_fields = array();
		foreach ( $wpmem_fields as $field ) {
			$chk_fields[] = $field[2];
		}
		$add_field_err_msg = ( in_array( $_POST['add_option'], $chk_fields ) ) ? __( 'A field with that option name already exists', 'wp-members' ) : false;
	
		// error check option name for spaces and replace with underscores
		$us_option = $_POST['add_option'];
		$us_option = preg_replace( "/ /", '_', $us_option );
		
		$arr = array();
		
		$arr[0] = ( $action == 'add_field' ) ? ( count( $wpmem_fields ) ) + 2 : false;
		$arr[1] = stripslashes( $_POST['add_name'] );
		$arr[2] = $us_option;
		$arr[3] = $_POST['add_type'];
		$arr[4] = ( isset( $_POST['add_display'] ) )  ? $_POST['add_display']  : 'n';
		$arr[5] = ( isset( $_POST['add_required'] ) ) ? $_POST['add_required'] : 'n';
		$arr[6] = ( $us_option == 'user_nicename' || $us_option == 'display_name' || $us_option == 'nickname' ) ? 'y' : 'n';
		
		if( $_POST['add_type'] == 'checkbox' ) { 
			$add_field_err_msg = ( ! $_POST['add_checked_value'] ) ? __( 'Checked value is required for checkboxes. Nothing was updated.', 'wp-members' ) : false;
			$arr[7] = ( isset( $_POST['add_checked_value'] ) )   ? $_POST['add_checked_value']   : false;
			$arr[8] = ( isset( $_POST['add_checked_default'] ) ) ? $_POST['add_checked_default'] : 'n';
		}
		
		if( $_POST['add_type'] == 'select' ) {
			// get the values
			$str = stripslashes( $_POST['add_dropdown_value'] );
			// remove linebreaks
			$str = trim( str_replace( array("\r", "\r\n", "\n"), '', $str ) );
			// create array
			if( ! function_exists( 'str_getcsv' ) ) {
				$arr[7] = explode( ',', $str );
			} else {
				$arr[7] = str_getcsv( $str, ',', '"' );
			}
		}

		if( $action == 'add_field' ) {
			if( ! $add_field_err_msg ) {
				array_push( $wpmem_fields, $arr );
				update_option( 'wpmembers_fields', $wpmem_fields );
				$did_update = $_POST['add_name'] . ' ' . __( 'field was added', 'wp-members' );
			} else {
				$did_update = $add_field_err_msg;
			}
		} else {
		
			for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
				if( $wpmem_fields[$row][2] == $_GET['edit'] ) {
					$arr[0] = $wpmem_fields[$row][0];
					$x = ( $arr[3] == 'checkbox' ) ? 8 : ( ( $arr[3] == 'select' ) ? 7 : 6 );
					for( $r = 0; $r < $x+1; $r++ ) {
						$wpmem_fields[$row][$r] = $arr[$r];
					}
				}
			}

			update_option( 'wpmembers_fields', $wpmem_fields );
			
			$did_update = $_POST['add_name'] . ' ' . __( 'field was updated', 'wp-members' );
			
		} 
	//} elseif( $action == 'edit_field' ) {
	
	}
	
	if( WPMEM_DEBUG == true && isset( $arr ) ) { echo "<pre>"; print_r($arr); echo "</pre>"; }

	return $did_update;
}


/**
 * Function to write the field edit link
 *
 * @since 2.8
 *
 * @param string $field_id The option name of the field to be edited
 */
function wpmem_fields_edit_link( $field_id ) {
	return '<a href="' . get_admin_url() . 'options-general.php?page=wpmem-settings&amp;tab=fields&amp;edit=' . $field_id . '">' . __( 'Edit' ) . '</a>';
}


/**
 * Function to dispay the add/edit field form
 *
 * @since 2.8
 *
 * @param string      $mode The mode for the function (edit|add)
 * @param array|null  $wpmem_fields the array of fields
 * @param string|null $field the field being edited
 */
function wpmem_a_field_edit( $mode, $wpmem_fields = null, $field = null )
{
	if( $mode == 'edit' ) {
		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
			if( $wpmem_fields[$row][2] == $field ) {
				$field_arr = $wpmem_fields[$row];
			}
		}	
	}
	
	$form_action = ( $mode == 'edit' ) ? 'editfieldform' : 'addfieldform';
	
?>
	<div class="postbox">
		<h3 class="title"><?php ( $mode == 'edit' ) ? _e( 'Edit Field', 'wp-members' ) : _e( 'Add a Field', 'wp-members' ); ?></h3>
		<div class="inside">
			<form name="<?php echo $form_action; ?>" id="<?php echo $form_action; ?>" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>">
				<?php wp_nonce_field( 'wpmem-add-fields' ); ?>
				<ul>
					<li>
						<label><?php _e( 'Field Label', 'wp-members' ); ?></label>
						<input type="text" name="add_name" value="<?php echo ( $mode == 'edit' ) ? $field_arr[1] : false; ?>" />
						<?php _e( 'The name of the field as it will be displayed to the user.', 'wp-members' ); ?>
					</li>
					<li>
						<label><?php _e( 'Option Name', 'wp-members' ); ?></label>
						<?php if( $mode == 'edit' ) { 
							echo $field_arr[2]; ?>
							<input type="hidden" name="add_option" value="<?php echo $field_arr[2]; ?>" /> 
						<?php } else { ?>	
							<input type="text" name="add_option" value="" />
							<?php _e( 'The database meta value for the field. It must be unique and contain no spaces (underscores are ok).', 'wp-members' ); ?>
						<?php } ?>
					</li>
					<li>
						<label><?php _e( 'Field Type', 'wp-members' ); ?></label>
						<?php if( $mode == 'edit' ) {
							echo $field_arr[3]; ?>
							<input type="hidden" name="add_type" value="<?php echo $field_arr[3]; ?>" /> 							
						<?php } else { ?>						
							<select name="add_type" id="wpmem_field_type_select">
								<option value="text"><?php     _e( 'text',     'wp-members' ); ?></option>
								<option value="textarea"><?php _e( 'textarea', 'wp-members' ); ?></option>
								<option value="checkbox"><?php _e( 'checkbox', 'wp-members' ); ?></option>
								<option value="select"><?php   _e( 'dropdown', 'wp-members' ); ?></option>
								<option value="password"><?php _e( 'password', 'wp-members' ); ?></option>
							</select>
						<?php } ?>
					</li>
					<li>
						<label><?php _e( 'Display?', 'wp-members' ); ?></label>
						<input type="checkbox" name="add_display" value="y" <?php echo ( $mode == 'edit' ) ? wpmem_selected( 'y', $field_arr[4] ) : false; ?> />
					</li>
					<li>
						<label><?php _e( 'Required?', 'wp-members' ); ?></label>
						<input type="checkbox" name="add_required" value="y" <?php echo ( $mode == 'edit' ) ? wpmem_selected( 'y', $field_arr[5] ) : false; ?> />
					</li>
				<?php if( $mode == 'add' || ( $mode == 'edit' && $field_arr[3] == 'checkbox' ) ) { ?>
				<?php echo ( $mode == 'add' ) ? '<div id="wpmem_checkbox_info">' : ''; ?>
					<li>
						<strong><?php _e( 'Additional information for checkbox fields', 'wp-members' ); ?></strong>
					</li>
					<li>
						<label><?php _e( 'Checked by default?', 'wp-members' ); ?></label>
						<input type="checkbox" name="add_checked_default" value="y" <?php echo ( $mode == 'edit' && $field_arr[3] == 'checkbox' ) ? wpmem_selected( 'y', $field_arr[8] ) : false; ?> />
					</li>
					<li>
						<label><?php _e( 'Stored value if checked:', 'wp-members' ); ?></label>
						<input type="text" name="add_checked_value" value="<?php echo ( $mode == 'edit' && $field_arr[3] == 'checkbox' ) ? $field_arr[7] : false; ?>" class="small-text" />
					</li>
				<?php echo ( $mode == 'add' ) ? '</div>' : ''; ?>
				<?php } ?>
				<?php if( $mode == 'add' || ( $mode == 'edit' && $field_arr[3] == 'select' ) ) { ?>
				<?php echo ( $mode == 'add' ) ? '<div id="wpmem_dropdown_info">' : ''; ?>
					<li>
						<strong><?php _e( 'Additional information for dropdown fields', 'wp-members' ); ?></strong>
					</li>
					<li>
						<label><?php _e( 'For dropdown, array of values:', 'wp-members' ); ?></label>
						<textarea name="add_dropdown_value" rows="5" cols="40"><?php
/**  Accomodate editing the current dropdown values or create dropdown value example */
if( $mode == 'edit' ) {
for( $row = 0; $row < count( $field_arr[7] ); $row++ ) {
/** If the row contains commas (i.e. 1,000-10,000), wrap in double quotes */
if( strstr( $field_arr[7][$row], ',' ) ) {
echo '"' . $field_arr[7][$row]; echo ( $row == count( $field_arr[7] )- 1  ) ? '"' : "\",\n";
} else {
echo $field_arr[7][$row]; echo ( $row == count( $field_arr[7] )- 1  ) ? "" : ",\n";
} }
						} else { 
							if (version_compare(PHP_VERSION, '5.3.0') >= 0) { ?>
<---- Select One ---->|, 
Choice One|choice_one,
"1,000|one_thousand",
"1,000-10,000|1,000-10,000",
Last Row|last_row<?php } else { ?>
<---- Select One ---->|,
Choice One|choice_one,
Choice 2|choice_two,
Last Row|last_row<?php } } ?></textarea>
					</li>
					<li>
						<label>&nbsp;</label>
						<span class="description"><?php _e( 'Options should be Option Name|option_value,', 'wp-members' ); ?>
					</li>
					<li>
						<label>&nbsp;</label>
						<a href="http://rocketgeek.com/plugins/wp-members/users-guide/registration/choosing-fields/" target="_blank"><?php _e( 'Visit plugin site for more information', 'wp-members' ); ?></a></span>
					</li>
				<?php echo ( $mode == 'add' ) ? '</div>' : ''; ?>
				<?php } ?>
				
				</ul><br />
				<?php if( $mode == 'edit' ) { ?><input type="hidden" name="field_arr" value="<?php echo $field_arr[2]; ?>" /><?php } ?>
				<input type="hidden" name="wpmem_admin_a" value="<?php echo ( $mode == 'edit' ) ? 'edit_field' : 'add_field'; ?>" />
				<input type="submit" name="save"  class="button-primary" value="<?php echo ( $mode == 'edit' ) ? __( 'Edit Field', 'wp-members' ) : __( 'Add Field', 'wp-members' ); ?> &raquo;" /> 
			</form>
		</div>
	</div>

<?php

}



/**
 * Function to display the table of fields in the field manager tab
 * 
 * @since 2.8
 *
 * @param array $wpmem_fields The array of fields
 */
function wpmem_a_field_table( $wpmem_fields )
{
	?>
	<div class="postbox">
		<h3 class="title"><?php _e( 'Manage Fields', 'wp-members' ); ?></h3>
		<div class="inside">
			<p><?php _e( 'Determine which fields will display and which are required.  This includes all fields, both native WP fields and WP-Members custom fields.', 'wp-members' ); ?>
				<br /><strong><?php _e( '(Note: Email is always mandatory and cannot be changed.)', 'wp-members' ); ?></strong></p>
			<form name="updatefieldform" id="updatefieldform" method="post" action="<?php echo $_SERVER['REQUEST_URI']?>">
			<?php wp_nonce_field( 'wpmem-update-fields' ); ?>
				<table class="widefat" id="wpmem-fields">
					<thead><tr class="head">
						<th scope="col"><?php _e( 'Add/Delete',  'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Field Label', 'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Option Name', 'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Field Type',  'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Display?',    'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Required?',   'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Checked?',    'wp-members' ); ?></th>
						<th scope="col"><?php _e( 'Edit'                      ); ?></th>
						<th scope="col"><?php _e( 'Users Screen','wp-members' ); ?></th>
					</tr></thead>
				<?php
				// get the user table fields array
				$wpmem_ut_fields = get_option( 'wpmembers_utfields' );
				// order, label, optionname, input type, display, required, native
				$class = '';
				for( $row = 0; $row < count($wpmem_fields); $row++ ) {
					$class = ( $class == 'alternate' ) ? '' : 'alternate'; ?>
					<tr class="<?php echo $class; ?>" valign="top" id="<?php echo $wpmem_fields[$row][0];?>">
						<td width="10%"><?php 
						$can_delete = ( $wpmem_fields[$row][2] == 'user_nicename' || $wpmem_fields[$row][2] == 'display_name' || $wpmem_fields[$row][2] == 'nickname' ) ? 'y' : 'n';
							if( ( $can_delete == 'y' ) || $wpmem_fields[$row][6] != 'y' ) {  ?><input type="checkbox" name="<?php echo "del_".$wpmem_fields[$row][2]; ?>" value="delete" /> <?php _e( 'Delete', 'wp-members' ); } ?></td>
						<td width="15%"><?php 
							_e( $wpmem_fields[$row][1], 'wp-members' );
							if( $wpmem_fields[$row][5] == 'y' ){ ?><font color="red">*</font><?php }
							?>
						</td>
						<td width="15%"><?php echo $wpmem_fields[$row][2]; ?></td>
						<td width="10%"><?php echo $wpmem_fields[$row][3]; ?></td>
					  <?php if( $wpmem_fields[$row][2]!='user_email' ) { ?>
						<td width="10%"><?php echo wpmem_create_formfield( $wpmem_fields[$row][2] . "_display", 'checkbox', 'y', $wpmem_fields[$row][4] ); ?></td>
						<td width="10%"><?php echo wpmem_create_formfield( $wpmem_fields[$row][2] . "_required",'checkbox', 'y', $wpmem_fields[$row][5] ); ?></td>
					  <?php } else { ?>
						<td colspan="2" width="20%"><small><i><?php _e( '(Email cannot be removed)', 'wp-members' ); ?></i></small></td>
					  <?php } ?>
						<td align="center" width="10%"><?php if( $wpmem_fields[$row][3] == 'checkbox' ) { 
							echo wpmem_create_formfield( $wpmem_fields[$row][2]."_checked", 'checkbox', 'y', $wpmem_fields[$row][8] ); } ?>
						</td>
						<td width="10%"><?php echo ( $wpmem_fields[$row][6] == 'y' ) ? 'native' : wpmem_fields_edit_link( $wpmem_fields[$row][2] ); ?></td>

						<td align="center" width="10%">
						<?php
						$wpmem_ut_fields_skip = array( 'user_email', 'confirm_email', 'password', 'confirm_password' );
						if ( !in_array( $wpmem_fields[$row][2], $wpmem_ut_fields_skip ) ) { ?>
							<input type="checkbox" name="ut_fields[<?php echo $wpmem_fields[$row][2]; ?>]" 
							value="<?php echo $wpmem_fields[$row][1]; ?>" 
							<?php echo ( ( $wpmem_ut_fields ) && ( in_array( $wpmem_fields[$row][1], $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						<?php } ?>
						</td>
					</tr><?php
				} ?>
					<tr class="nodrag nodrop">
						<td>&nbsp;</td>
						<td><i><?php _e( 'Registration Date', 'wp-members' ); ?></i></td>
						<td><i>user_registered</i></td>
						<td colspan="4">&nbsp;</td>
						<td><?php _e( 'native', 'wp-members' ); ?></td>
						<td align="center">
							<input type="checkbox" name="ut_fields[user_registered]" 
								value="Registration Date" 
								<?php echo ( ( $wpmem_ut_fields ) && ( in_array( 'Registration Date', $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						</td>
					</tr>
				<?php if( WPMEM_MOD_REG == 1 ) { ?>
					<tr class="nodrag nodrop">
						<td>&nbsp;</td>
						<td><i><?php _e( 'Active', 'wp-members' ); ?></i></td>
						<td><i>active</i></td>
						<td colspan="5">&nbsp;</td>
						<td align="center">
							<input type="checkbox" name="ut_fields[active]" 
								value="Active" 
								<?php echo ( ( $wpmem_ut_fields ) && ( in_array( 'Active', $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						</td>
					</tr>
				<?php } ?>
					<tr class="nodrag nodrop">
						<td>&nbsp;</td>
						<td><i><?php _e( 'Registration IP', 'wp-members' ); ?></i></td>
						<td><i>wpmem_reg_ip</i></td>
						<td colspan="5">&nbsp;</td>
						<td align="center">
							<input type="checkbox" name="ut_fields[wpmem_reg_ip]" 
								value="Registration IP" 
								<?php echo ( ( $wpmem_ut_fields ) && ( in_array( 'Registration IP', $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						</td>
					</tr>
				<?php if( WPMEM_USE_EXP == 1 ) { ?>
					<tr class="nodrag nodrop">
						<td>&nbsp;</td>
						<td><i>Subscription Type</i></td>
						<td><i>exp_type</i></td>
						<td colspan="5">&nbsp;</td>
						<td align="center">
							<input type="checkbox" name="ut_fields[exp_type]" 
								value="Subscription Type" 
								<?php echo ( ( $wpmem_ut_fields ) && ( in_array( 'Subscription Type', $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						</td>
					</tr>
					<tr class="nodrag nodrop">
						<td>&nbsp;</td>
						<td><i>Expires</i></td>
						<td><i>expires</i></td>
						<td colspan="5">&nbsp;</td>
						<td align="center">
							<input type="checkbox" name="ut_fields[expires]" 
								value="Expires" 
								<?php echo ( ( $wpmem_ut_fields ) && ( in_array( 'Expires', $wpmem_ut_fields ) ) ) ? 'checked' : false; ?> />
						</td>
					</tr>
				<?php } ?>
				</table><br />
				<input type="hidden" name="wpmem_admin_a" value="update_fields" />
				<input type="submit" name="save"  class="button-primary" value="<?php _e( 'Update Fields', 'wp-members' ); ?> &raquo;" /> 
			</form>
		</div><!-- .inside -->
	</div>	
	<?php
}

/** End of File **/