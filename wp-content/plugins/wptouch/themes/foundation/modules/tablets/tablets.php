<?php

add_action( 'foundation_module_init_mobile', 'foundation_tablets_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_tablet_settings' );
add_filter( 'wptouch_supported_device_classes', 'foundation_tablet_supported_device_classes' );

function foundation_tablets_init() {
	wp_enqueue_script( 
		'foundation_tablets', 
		foundation_get_base_module_url() . '/tablets/tablets-helper.js',
		false,
		FOUNDATION_VERSION,
		true
	);
}

function foundation_tablet_supported_device_classes( $device_classes ) {

	global $wptouch_tablet_list;

	$wptouch_tablet_list = array(
		'iPad',											// Apple iPads
		array( 'Android', 'Tablet' ),					// Catches ALL Android devices/browsers that explicitly state they're tablets
		array( 'Nexus', '7' ),							// Nexus 7
		'Android',										// Catches ALL Android devices, not just smartphones : )
		'IEMobile/10.0',								// Windows IE 10 touch tablet devices
		'PlayBook',										// BB PlayBook
		'Xoom',											// Motorola Xoom
		'P160U',										// HP TouchPad
		'SCH-I800',										// Galaxy Tab
		'Kindle',										// Kindles
		'Silk'											// Kindles in Silk mode
	);

	foreach( $wptouch_tablet_list as $tablet_user_agent ) {
		$device_classes[ 'default' ][] = $tablet_user_agent;
	}

	return $device_classes;
}

function foundation_tablet_settings( $page_options ){

	wptouch_add_page_section(
		FOUNDATION_PAGE_HOMESCREEN_ICONS,
		__( 'iPad', 'wptouch-pro' ),
		'admin_menu_homescreen_ipad_retina',
		array(
			wptouch_add_setting(
				'image-upload',
				'ipad_icon_retina',
				sprintf( __( '%d by %d pixels (PNG)', 'wptouch-pro' ), 152, 152 ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}