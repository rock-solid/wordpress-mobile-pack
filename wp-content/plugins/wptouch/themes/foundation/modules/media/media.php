<?php

add_action( 'foundation_module_init_mobile', 'foundation_media_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_media_settings' );

function foundation_media_init() {

	$settings = foundation_get_settings();
	if ( $settings->video_handling_type == 'fitvids' ) {

		// Load FitVids
		wp_enqueue_script( 
			'foundation_media_fitvids', 
			foundation_get_base_module_url() . '/media/fitvids.js', 
			array( 'foundation_base' ),
			FOUNDATION_VERSION,
			true
		);
	
	} elseif ( $settings->video_handling_type == 'fluidvids' ) {
	
		// Load Fluid Width Videos
		wp_enqueue_script( 
			'foundation_media_fluidvids', 
			foundation_get_base_module_url() . '/media/fluid-width-videos.js', 
			array( 'foundation_base' ),
			FOUNDATION_VERSION,
			true
		);
	
	}
	
	if ( $settings->video_handling_type != 'none' ) {
		wp_enqueue_script( 
			'foundation_media_handling', 
			foundation_get_base_module_url() . '/media/media.js',
			false,
			FOUNDATION_VERSION,
			true 
		);
	}
	
}

function foundation_media_settings( $page_options ){
	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Video Handling', 'wptouch-pro' ),
		'foundation-media-settings',
		array(
			wptouch_add_setting(
				'list',
				'video_handling_type',
				'',
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				array(
					'none' => __( 'None', 'wptouch-pro' ),
					'css' => __( 'CSS only (HTML5 videos)', 'wptouch-pro' ),
					'fitvids' => __( 'FitVids Method', 'wptouch-pro' ),
					'fluidvids' => __( 'Fluid-Width Method', 'wptouch-pro' )
				)
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);
	
	return $page_options;
}