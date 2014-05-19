<?php

// Actions
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_login_settings' );
add_action( 'foundation_module_init_mobile', 'foundation_login_init' );
add_action( 'wptouch_post_footer', 'foundation_add_login_form' );


function foundation_login_init() {
	// Load JS
	if ( wptouch_fdn_show_login() ) {
		wp_enqueue_script( 
			'foundation_login_jquery', 
			foundation_get_base_module_url() . '/login/wptouch-login.js', 
			array( 'jquery' ),
			FOUNDATION_VERSION,
			true
		);
	}

	$fdn_login_strings = array(
		'username_text' => __( 'Account Username', 'wptouch-pro' ),
		'password_text' => __( 'Account Password', 'wptouch-pro' )
	);

	wp_localize_script( 'foundation_login_jquery', 'wptouchFdnLogin', $fdn_login_strings );
}

function foundation_login_settings( $page_options ) {
	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Login Form', 'wptouch-pro' ),
		'login-options',
		array(
			wptouch_add_setting(
				'checkbox',
				'show_login_box',
				__( 'Use fly-in login form', 'wptouch-pro' ),
				__( 'Will add login links and allow mobile visitors to login to your website from mobile devices', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'2.0'
			),
			wptouch_add_setting(
				'checkbox',
				'show_login_links',
				__( 'Show "Sign-up" and "Lost Password?" links', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'2.0'
			)

		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}

function foundation_add_login_form(){
	if ( wptouch_fdn_show_login() ) {
		$content = wptouch_capture_include_file( dirname( __FILE__ ) . '/login-html.php' );	
		echo $content;
	}
}

// Can be used in themes (public)
function wptouch_fdn_show_login(){
	$settings = foundation_get_settings();
	if ( $settings->show_login_box ) {
		return true;
	} else {
		return false;		
	}
}

function wptouch_fdn_show_login_links(){
	$settings = foundation_get_settings();
	if ( $settings->show_login_links ) {
		return true;
	} else {
		return false;		
	}
}