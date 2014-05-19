<?php

define( 'BAUHAUS_THEME_VERSION', '1.1' );
define( 'BAUHAUS_SETTING_DOMAIN', 'bauhaus' );
define( 'BAUHAUS_DIR', wptouch_get_bloginfo( 'theme_root_directory' ) );
define( 'BAUHAUS_URL', wptouch_get_bloginfo( 'theme_root_url' ) );

// Bauhaus actions
add_action( 'foundation_init', 'bauhaus_theme_init' );
add_action( 'foundation_modules_loaded', 'bauhaus_register_fonts' );
add_action( 'admin_enqueue_scripts', 'bauhaus_enqueue_admin_scripts' );

// Bauhaus filters
add_filter( 'wptouch_registered_setting_domains', 'bauhaus_setting_domain' );
add_filter( 'wptouch_setting_defaults_bauhaus', 'bauhaus_setting_defaults' );

add_filter( 'wptouch_body_classes', 'bauhaus_body_classes' );
add_filter( 'wptouch_post_classes', 'bauhaus_post_classes' );

// Bauhaus GUI Settings
add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'bauhaus_render_theme_settings' );
add_filter( 'foundation_settings_blog', 'bauhaus_blog_settings' );
// add_filter( 'foundation_settings_pages', 'bauhaus_page_settings' );
add_filter( 'wptouch_post_footer', 'bauhaus_footer_version' );

add_filter( 'wptouch_has_post_thumbnail', 'bauhaus_handle_has_thumbnail' );
add_filter( 'wptouch_the_post_thumbnail', 'bauhaus_handle_the_thumbnail' );
add_filter( 'wptouch_setting_version_compare', 'bauhaus_setting_version_compare', 10, 2 );

function bauhaus_setting_domain( $domain ) {
	$domain[] = BAUHAUS_SETTING_DOMAIN;
	return $domain;
}

function bauhaus_get_settings() {
	return wptouch_get_settings( BAUHAUS_SETTING_DOMAIN );
}

function bauhaus_setting_version_compare( $version, $domain ) {
	if ( $domain == BAUHAUS_SETTING_DOMAIN ) {
		return BAUHAUS_THEME_VERSION;
	}

	return $version;
}

function bauhaus_footer_version(){
	echo '<!--Bauhaus v' . BAUHAUS_THEME_VERSION . '-->';
}

function bauhaus_setting_defaults( $settings ) {

	// Bauhaus menu default
	$settings->primary_menu = 'wp';

	// Theme colors
	$settings->bauhaus_background_color = '#f9f9f8';
	$settings->bauhaus_header_color = '#2d353f';
	$settings->bauhaus_link_color = '#35c4ff';
	$settings->bauhaus_post_page_header_color = '#6dfdb9';

	// Shapes
	$settings->bauhaus_shape_type = 'circles';

	// Blog
	$settings->bauhaus_show_taxonomy = false;
	$settings->bauhaus_show_date = true;
	$settings->bauhaus_show_author = false;
	$settings->bauhaus_show_search = true;
	$settings->bauhaus_show_comment_bubbles = true;
	$settings->bauhaus_use_infinite_scroll = false;

	$settings->bauhaus_use_thumbnails = 'index_single_page';
	$settings->bauhaus_thumbnail_type = 'featured';
	$settings->bauhaus_thumbnail_custom_field = '';

	return $settings;
}

function bauhaus_theme_init() {

	// Foundation modules this theme should load
	foundation_add_theme_support(
		array(
			// Modules w/ settings
			'webapp',
			'advertising',
			'custom-posts',
			'custom-latest-posts',
			'related-posts',
			'google-fonts',
			'load-more',
			'media',
			'login',
			'sharing',
			'social-links',
			'featured',
			// Modules w/o settings
			'menu',
			'spinjs',
			'tappable',
			'fastclick',
			'tappable',
			'font-awesome',
			'concat'
		)
	);

	// If enable in Bauhaus settings, load up infinite scrolling
	bauhaus_if_infinite_scroll_enabled();

	// Example of how to register a theme menu
	wptouch_register_theme_menu(
		array(
			'name' => 'primary_menu',	// this is the name of the setting
			'friendly_name' => __( 'Header Menu', 'wptouch-pro' ),	// the friendly name, shows as a section heading
			'settings_domain' => BAUHAUS_SETTING_DOMAIN,	// the setting domain (should be the same for the whole theme)
			'description' => __( 'Choose a menu', 'wptouch-pro' ),	 	// the description
			'tooltip' => __( 'Main menu selection', 'wptouch-pro' ), // Extra help info about this menu, perhaps?
			'can_be_disabled' => false
		)
	);

	// Example of how to register theme colors
	// (Name, element to add color to, element to add background-color to, settings domain)
	foundation_register_theme_color( 'bauhaus_background_color', __( 'Theme background', 'wptouch-pro' ), '', '.page-wrapper', BAUHAUS_SETTING_DOMAIN );
	foundation_register_theme_color( 'bauhaus_header_color', __( 'Header & Menu', 'wptouch-pro' ), 'a', 'body, header, .wptouch-menu, #search-dropper, .date-circle', BAUHAUS_SETTING_DOMAIN );
	foundation_register_theme_color( 'bauhaus_link_color', __( 'Links', 'wptouch-pro' ), 'a, #slider a p:after', '.dots li.active, #switch .active', BAUHAUS_SETTING_DOMAIN );
	foundation_register_theme_color( 'bauhaus_post_page_header_color', __( 'Post/Page Headers', 'wptouch-pro' ), '', '.bauhaus, .wptouch-login-wrap, form#commentform button#submit', BAUHAUS_SETTING_DOMAIN );
}

// Example of how to register Google font pairings
// (Apply to (Headings or Body), Google font Pretty Name, kerning, weights)
function bauhaus_register_fonts() {
	if ( foundation_is_theme_using_module( 'google-fonts' ) ) {
		foundation_register_google_font_pairing(
			'lato_roboto',
			foundation_create_google_font( 'heading', 'Lato', 'sans-serif', array( '300', '600' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'droidserif_roboto',
			foundation_create_google_font( 'heading', 'Droid Serif', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'baumans_ubuntu',
			foundation_create_google_font( 'heading', 'Baumans', 'sans-serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Ubuntu', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'alegreya_roboto',
			foundation_create_google_font( 'heading', 'Alegreya', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'fjalla_cantarell',
			foundation_create_google_font( 'heading', 'Fjalla One', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Open Sans', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'grandhotel_crimson',
			foundation_create_google_font( 'heading', 'Domine', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'News Cycle', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'muli_montserrat',
			foundation_create_google_font( 'heading', 'Montserrat', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Muli', 'sans-serif', array( '400', '400italic' ) )
		);
	}
}

function bauhaus_body_classes( $classes ) {
	$settings = bauhaus_get_settings();

	$heading_luma = wptouch_hex_to_luma( $settings->bauhaus_header_color );
	$shape_type = $settings->bauhaus_shape_type;

	if ( $heading_luma <= 150 ) {
		$classes[] = 'dark-header';
	} else {
		$classes[] = 'light-header';
	}

	$body_luma = wptouch_hex_to_luma( $settings->bauhaus_background_color );

	if ( $body_luma <= 150 ) {
		$classes[] = 'dark-body';
	} else {
		$classes[] = 'light-body';
	}

	$post_page_head_luma = wptouch_hex_to_luma( $settings->bauhaus_post_page_header_color );

	if ( $post_page_head_luma <= 150 ) {
		$classes[] = 'dark-post-head';
	} else {
		$classes[] = 'light-post-head';
	}

	$classes[] = $shape_type;

	if ( !$settings->bauhaus_show_comment_bubbles ) {
		$classes[] = 'no-com-bubbles';
	}

	return $classes;
}

function bauhaus_post_classes( $classes ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_thumbnails != 'none' ) {
	  $classes[] = 'show-thumbs';
	} else {
	  $classes[] = 'no-thumbs';
	}

	return $classes;
}


function bauhaus_enqueue_admin_scripts() {
	wp_enqueue_script(
		'bauhaus-admin-js',
		BAUHAUS_URL . '/admin/bauhaus-admin.js',
		array( 'jquery', 'wptouch-pro-admin' ),
		BAUHAUS_THEME_VERSION,
		false
	);
}

// Admin Settings

function bauhaus_render_theme_settings( $page_options ) {
	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Theme Shapes', 'wptouch-pro' ),
		'theme-shapes',
		array(
			wptouch_add_setting(
				'radiolist',
				'bauhaus_shape_type',
				__( 'Theme shape style', 'wptouch-pro' ),
				__( 'Bauhaus will use this shape style throughout its appearance', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0',
				array(
					'circles' => __( 'Circles', 'wptouch-pro' ),
					'roundsquares' => __( 'Rounded squares', 'wptouch-pro' )
				)
			)
		),
		$page_options,
		BAUHAUS_SETTING_DOMAIN

	);

	return $page_options;

}

// Hook into Foundation page section for Blog and add settings
function bauhaus_blog_settings( $blog_settings ) {

	$blog_settings[] = wptouch_add_setting(
		'radiolist',
		'bauhaus_use_thumbnails',
		__( 'Post thumbnails', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		array(
			'none' => __( 'No thumbnails', 'wptouch-pro' ),
			'index' => __( 'Blog listing only', 'wptouch-pro' ),
			'index_single' => __( 'Blog listing, single posts', 'wptouch-pro' ),
			'index_single_page' => __( 'Blog listing, single posts & pages', 'wptouch-pro' ),
			'all' => __( 'All (blog, single, pages, search & archive)', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'radiolist',
		'bauhaus_thumbnail_type',
		__( 'Thumbnail Selection', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		array(
			'featured' => __( 'Post featured image', 'wptouch-pro' ),
			'custom_field' => __( 'Post custom field', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'text',
		'bauhaus_thumbnail_custom_field',
		__( 'Thumbnail custom field name', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_taxonomy',
		__( 'Show post categories and tags', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_date',
		__( 'Show post date', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_author',
		__( 'Show post author', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_comment_bubbles',
		__( 'Show comment bubbles on posts', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0.5',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_search',
		__( 'Show search in header', 'wptouch-pro' ),
		__( 'Adds Search capability in the site header.', 'wptouch-pro' ),
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_use_infinite_scroll',
		__( 'Use infinite scrolling for blog', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	return $blog_settings;
}

// Hook into Foundation page section for Blog and add settings
function bauhaus_page_settings( $page_settings ) {

	$page_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_featured_slider_on_front',
		__( 'Show featured slider on front page', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		BAUHAUS_SETTING_DOMAIN
	);

	return $page_settings;
}

function bauhaus_handle_has_thumbnail( $does_have_it ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		if ( $settings->bauhaus_thumbnail_custom_field ) {
			global $post;

			$possible_image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
			return strlen( $possible_image );
 		}
	}

	return $does_have_it;
}

function bauhaus_handle_the_thumbnail( $current_thumbnail ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		global $post;

		$image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
		echo $image;
	}

	return $current_thumbnail;
}

function bauhaus_if_infinite_scroll_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_infinite_scroll ) {
		foundation_add_theme_support( 'infinite-scroll' );
	}
}