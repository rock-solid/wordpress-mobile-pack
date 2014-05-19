<?php

add_action( 'add_meta_boxes', 'wptouch_page_template_init' );
add_action( 'save_post', 'wptouch_page_template_save' );

function wptouch_get_page_template( $post_id ) {
	return get_post_meta( $post_id, '_mobile_page_template', true );
}

function wptouch_page_template_init() {
	$screens = array( 'page' );

	foreach( $screens as $screen ) {
		add_meta_box(
			'mobile-page-template',
			__( 'Mobile Page Template', 'wptouch-pro' ),
			'wptouch_admin_render_page_template',
			$screen,
			'side',
			'high'
		);
	}
}

function wptouch_page_templates_find_all_in_dir( $dir ) {
	$templates = array();

	require_once( WPTOUCH_DIR . '/core/file-operations.php' );

	$files = wptouch_get_all_recursive_files( $dir, '.php' );
	foreach( $files as $file ) {
		$content = wptouch_load_file( $dir . '/' . $file );
		if ( preg_match( '#Mobile Template: (.*)#', $content, $matches ) ) {
			$template = new stdClass;
			$template->name = $matches[1];

			$template_parts = explode( DIRECTORY_SEPARATOR, $file );
			$template->location = $template_parts[ count( $template_parts ) - 1 ];

			$templates[ $file ] = $template;
		}
	}

	return $templates;
}

function wptouch_page_templates_get_all() {
	global $wptouch_pro;

	$theme_info = $wptouch_pro->get_current_theme_info();
	$theme_location = WP_CONTENT_DIR . $theme_info->location;

	$templates = wptouch_page_templates_find_all_in_dir( $theme_location );

	if ( isset( $theme_info->parent_theme ) && strlen( $theme_info->parent_theme ) ) {
		$parent_info = $wptouch_pro->get_parent_theme_info();
		$parent_location = WP_CONTENT_DIR . $parent_info->location;

		$templates = array_merge( wptouch_page_templates_find_all_in_dir( $parent_location ), $templates );
	}

	return $templates;
}

function wptouch_admin_render_page_template( $post ) {
	include( WPTOUCH_DIR . '/include/html/page-template.php' );
}

function wptouch_page_template_save( $post_id ) {
 	if ( ! isset( $_POST['mobile_template_box_nonce'] ) ) {
    	return $post_id;
    }

	$nonce = $_POST['mobile_template_box_nonce'];

	if ( !wp_verify_nonce( $nonce, 'mobile_template_box' ) ) {
		return $post_id;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return $post_id;
    }
	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	$page_template = sanitize_text_field( $_POST['wptouch_mobile_page_template'] );

 	// Update the meta field in the database.
 	update_post_meta( $post_id, '_mobile_page_template', $page_template );
}