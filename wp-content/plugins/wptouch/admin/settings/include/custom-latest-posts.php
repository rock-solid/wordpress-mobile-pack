<?php 
	$settings = foundation_get_settings();
	
	ob_start();
	wp_dropdown_pages(); 
	$contents = ob_get_contents();
	ob_end_clean();
	
	$contents = str_replace( 'page_id', wptouch_admin_get_manual_encoded_setting_name( 'foundation', 'latest_posts_page' ), $contents );
	$value_string = 'value="' . $settings->latest_posts_page . '"';
	$contents = str_replace( $value_string, $value_string . ' selected', $contents );
	
	$is_custom = ( $settings->latest_posts_page == 'none' ? ' selected' : '' );
	$contents = str_replace( '</select>', '<option class="level-0" value="none"' . $is_custom . '>' . __( "None (Use WordPress Settings)", "wptouch-pro" ) . '</option></select>', $contents );
	
	echo $contents;	
?>