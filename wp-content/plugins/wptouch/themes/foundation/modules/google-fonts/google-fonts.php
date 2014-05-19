<?php

add_action( 'foundation_module_init_mobile', 'foundation_google_fonts_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_admin_panel' );

function foundation_admin_panel( $page_options ) {
	$fonts = foundation_get_google_font_pairings();

	if ( count( $fonts ) ) {
		$font_defaults = array( 'default' => __( 'Browser Fonts', 'wptouch-pro' ) );

		foreach( $fonts as $setting_value => $font_info ) {
			$font_defaults[ $setting_value ] = sprintf( '%s & %s', $font_info[0]->name, $font_info[1]->name );
		}

		wptouch_add_page_section(
			FOUNDATION_PAGE_BRANDING,
			__( 'Typography', 'wptouch-pro' ),
			'foundation-typography',
			array(
				wptouch_add_setting(
					'list',
					'typography_sets',
					__( 'Font style', 'wptouch-pro' ),
					__( 'Choose a Google font pairing designed for this theme, or default browser fonts.', 'wptouch-pro' ),
					WPTOUCH_SETTING_BASIC,
					'1.0',
					$font_defaults
				)
			),
			$page_options,
			FOUNDATION_SETTING_DOMAIN
		);
	}

	return $page_options;
}

function foundation_google_fonts_get_selected_info() {
	$settings = wptouch_get_settings( 'foundation' );
	$fonts = foundation_get_google_font_pairings();

	$selected_font_info = false;
	foreach( $fonts as $setting_name => $font_info ) {
		if ( $settings->typography_sets == $setting_name ) {
			$selected_font_info = $font_info;
			break;
		}
	}

	return $selected_font_info;
}

function foundation_google_fonts_init() {
	$settings = wptouch_get_settings( 'foundation' );

	if ( $settings->typography_sets != 'default' ) {
		wp_enqueue_script(
			'foundation_google_fonts',
			foundation_get_base_module_url() . '/google-fonts/google-fonts.js',
			false,
			md5( FOUNDATION_VERSION ),
			true
		);

		add_filter( 'wptouch_body_classes', 'foundation_add_google_font_classes' );
	}

	$selected_font_info = foundation_google_fonts_get_selected_info();

	if ( $selected_font_info ) {
		$family_string = '';
		$inline_style_data = '';

		if ( is_array( $selected_font_info ) && count( $selected_font_info ) ) {
			$new_families = array();
			foreach( $selected_font_info as $font_info ) {

				$font_string = htmlentities( $font_info->name );
				if ( isset( $font_info->variants ) && is_array( $font_info->variants ) ) {
					$font_string .=  ':' . implode( ',', $font_info->variants );
				}

				$new_families[] = $font_string;

				$inline_style_data .= "." . $font_info->selector . "-font" . " {\n";
				$inline_style_data .= "\tfont-family: '" . $font_info->name . "', " . $font_info->fallback . ";\n";
				$inline_style_data .= "}\n";
			}

			$family_string = implode( '|', $new_families );
		}

		if ( $family_string ) {
			wp_enqueue_style(
				'foundation_google_fonts',
				'http://fonts.googleapis.com/css?family=' . $family_string,
				false,
				FOUNDATION_VERSION,
				false
			);

			if ( $inline_style_data ) {
				wp_add_inline_style( 'foundation_google_fonts', $inline_style_data );
			}
		}
	}
}

global $wptouch_google_fonts;
$wptouch_google_fonts = array();

function foundation_create_google_font( $selector, $name, $fallback = 'sans-serif', $variants = false ) {
	$font = new stdClass;

	$font->selector = $selector;
	$font->name = $name;
	$font->fallback = $fallback;
	$font->variants = $variants;

	return $font;
}

function foundation_register_google_font_pairing( $setting_value, $font1, $font2 ) {
	global $wptouch_google_fonts;

	$wptouch_google_fonts[ $setting_value ] = array( $font1, $font2 );
}

function foundation_get_google_font_pairings() {
	global $wptouch_google_fonts;
	return $wptouch_google_fonts;
}

function foundation_add_google_font_classes( $classes ) {
	$settings = wptouch_get_settings( 'foundation' );

	$classes[] = 'fonts-' . $settings->typography_sets;

	return $classes;
}