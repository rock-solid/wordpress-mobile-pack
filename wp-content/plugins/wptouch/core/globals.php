<?php

if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
	require_once( WPTOUCH_DIR . '/pro/professional.php' );
}

define( 'WPTOUCH_PRO_DESKTOP_FCN_CACHE_TIME', 3600 );

add_action( 'wptouch_pro_loaded', 'wptouch_load_add_ons' );
add_filter( 'wptouch_modify_setting__compat__enabled_plugins', 'wptouch_modify_enabled_plugins' );

require_once( WPTOUCH_DIR . '/core/class-array-iterator.php' );

function wptouch_is_mobile_theme_showing() {
	global $wptouch_pro;

	return ( $wptouch_pro->is_mobile_device && $wptouch_pro->showing_mobile_theme );
}

function wptouch_load_add_ons() {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );
	$php_files = wptouch_get_all_recursive_files( WPTOUCH_DIR . '/include/add-ons/', '.php' );

	if ( $php_files && count( $php_files ) ) {
		foreach( $php_files as $php_file ) {
			require_once( WPTOUCH_DIR . '/include/add-ons' . $php_file );
		}
	}
}

function wptouch_locate_template( $param1, $param2, $param3, $param4 = false, $param5 = false ) {
	$template_path = false;
	$current_path = false;
	$require_once = true;

	if ( $param4 ) {
		if ( $param5 ) {
			// 5 parameters
			$template_path = $param4;
			$current_path = $param5;
			$require_once = $param3;
		} else {
			// 4 parameters
			$template_path = $param3;
			$current_path = $param4;
		}
	} else {
		// 3 parameters
		$template_path = $param2;
		$current_path = $param3;
	}

	$template_file = $template_path . '/' . $param1;
	if ( !file_exists( $template_file ) ) {
		$template_file = $current_path . '/' . $param1;
	}

	if ( file_exists( $template_path ) ) {
		global $wptouch_pro;

		require_once( WPTOUCH_DIR . '/core/desktop-functions.php' );

		$current_path = dirname( $template_file );
		if ( $require_once ) {
			wptouch_include_functions_file( $wptouch_pro, $template_file, $template_path, $current_path, 'require_once' );
		} else {
			wptouch_include_functions_file( $wptouch_pro, $template_file, $template_path, $current_path, 'require' );
		}
	} else {
		// add debug statement
	}
}

function wptouch_strip_dashes( $str ) {
	return str_replace( '-', '_', $str );
}

function wptouch_convert_to_class_name( $class_to_convert ) {
	return str_replace( array( ' ', '"', '.', '\'', '#' ), array( '-', '', '-', '', '' ), strtolower( $class_to_convert ) );
}

function wptouch_do_template( $template_name ) {
	global $wptouch_pro;
	$template_path = $wptouch_pro->get_current_theme_directory() . '/' . $wptouch_pro->get_active_device_class() . '/' . $template_name;
	$directories = array( TEMPLATEPATH );
	if ( $wptouch_pro->is_child_theme() ) {
		array_unshift( $directories, STYLESHEETPATH );
	}

	foreach( $directories as $dir ) {
		if ( file_exists( $dir . '/' . $template_name ) ) {
			include( $dir . '/' . $template_name );
			return true;
		}
	}

	return false;
}

function wptouch_modify_enabled_plugins( $enabled_list ) {
	$new_list = array();

	foreach( $enabled_list as $key => $value ) {
		$new_list[ $value ] = 1;
	}

	$settings = wptouch_get_settings( 'compat' );
	if ( isset( $settings->plugin_hooks ) ) {
		foreach( $settings->plugin_hooks as $name => $value ) {
			if ( !array_key_exists( $name, $new_list ) ) {
				$new_list[ $name ] = 0;
			}
		}
	}

	return $new_list;
}

function wptouch_is_device_real_ipad() {
	return ( stripos( $_SERVER[ 'HTTP_USER_AGENT' ], 'ipad' ) !== false );
}

function wptouch_capture_include_file( $file_name ) {
	ob_start();
	require_once( $file_name );
	$contents = ob_get_contents();
	ob_end_clean();

	return $contents;
}

function wptouch_is_multisite_enabled() {
	return is_multisite();
}

function wptouch_is_showing_mobile_theme_on_mobile_device() {
	global $wptouch_pro;

	return $wptouch_pro->is_showing_mobile_theme_on_mobile_device();
}

function wptouch_save_settings( $settings, $domain = 'wptouch_pro' ) {
	global $wptouch_pro;

	$wptouch_pro->save_settings( $settings, $domain );
}

function wptouch_get_settings( $domain = 'wptouch_pro', $clone_it = true ) {
	global $wptouch_pro;

	return $wptouch_pro->get_settings( $domain, $clone_it );
}

function wptouch_get_quick_setting_value( $domain, $name ) {
	global $wptouch_pro;

	// Check to see if we have the object already loaded
	if ( !isset( $wptouch_pro->settings_objects[ $domain ] ) ) {
		wptouch_get_settings( $domain, false );
	}

	return $wptouch_pro->settings_objects[ $domain ]->$name;
}

function wptouch_cron_backup_settings() {
	require_once( WPTOUCH_DIR . '/core/admin-backup-restore.php' );

	wptouch_backup_settings();
}

function wptouch_show_desktop_switch_link() {
	$switch_html = wptouch_capture_include_file( WPTOUCH_DIR . '/include/html/desktop-switch.php' );
	echo apply_filters( 'wptouch_desktop_switch_html', $switch_html );
}

function wptouch_split_string( $str, $chars ) {
	return substr( $str, 0, strrpos( substr( $str, 0, $chars ), ' ') );
}

function wptouch_rss_date( $rss_date ) {
	$date_time = strtotime( $rss_date );

	echo date( 'F jS, Y', $date_time );
}

function wptouch_in_preview_window() {
	return ( isset( $_GET['wptouch_preview_theme'] ) );
}

function wptouch_get_translated_device_type( $tag ) {
	if ( $tag == 'smartphone' ) {
		return __( 'smartphone', 'wptouch-pro' );
	} else if ( $tag == 'tablet' ) {
		return __( 'tablet', 'wptouch-pro' );
	}
}

function wptouch_desktop_switch_link( $echo_result = true ) {
	$link = wptouch_get_desktop_switch_link();

	if ( $echo_result ) {
		echo $link;
	} else {
		return $link;
	}
}

function wptouch_the_desktop_switch_link() {
	echo wptouch_get_desktop_switch_link();
}

function wptouch_get_desktop_switch_link() {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->post[ 'wptouch_switch_location' ] ) ) {
		$redirect_location = $wptouch_pro->post[ 'wptouch_switch_location' ];
	} else {
		$redirect_location = $_SERVER['REQUEST_URI'];
	}

	return apply_filters( 'wptouch_desktop_switch_link', get_bloginfo( 'url' ) . '?wptouch_switch=mobile&amp;redirect=' . urlencode( $redirect_location ) );
}

if ( defined( 'WPTOUCH_IS_FREE' ) ) {
	function wptouch_can_show_license_menu() {
		return false;
	}

	function wptouch_should_show_license_nag() {
		return false;
	}
}

function wptouch_admin_url( $url ) {
	if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
		return network_admin_url( $url );
	} else {
		return admin_url( $url );
	}
}

function wptouch_is_site_licensed() {
	$settings = wptouch_get_settings( 'bncid' );
	return $settings->license_accepted;
}

function wptouch_should_show_activation_nag() {
	return wptouch_should_show_license_nag();
}

function wptouch_is_multisite_primary() {
	global $blog_id;
	return ( $blog_id == 1 );
}

function wptouch_is_multisite_secondary() {
	if ( wptouch_is_multisite_enabled() ) {
		global $blog_id;

		return ( $blog_id > 1 );
	} else {
		return false;
	}
}

function wptouch_bloginfo( $setting_name ) {
	echo wptouch_get_bloginfo( $setting_name );
}

function wptouch_get_bloginfo( $setting_name ) {
	global $wptouch_pro;
	$settings = $wptouch_pro->get_settings();

	$setting = false;

	switch( $setting_name ) {
		case 'foundation_directory':
			$setting = WPTOUCH_DIR . '/themes/foundation';
			break;
		case 'foundation_url':
			$setting = WPTOUCH_URL . '/themes/foundation';
			break;
		case 'template_directory':
		case 'template_url':
			$setting = $wptouch_pro->get_template_directory_uri( false );
			break;
		case 'child_theme_directory_uri':
			$setting = $wptouch_pro->get_stylesheet_directory_uri( false );
			break;
		case 'theme_root_directory':
			$setting = $wptouch_pro->get_current_theme_directory();
			break;
		case 'theme_root_url':
			$setting = $wptouch_pro->get_current_theme_uri();
			break;
		case 'site_title':
			$setting = stripslashes( $settings->site_title );
			break;
		case 'wptouch_directory':
			$setting = WPTOUCH_DIR;
			break;
		case 'wptouch_url':
			$setting = WPTOUCH_URL;
			break;
		case 'version':
			$setting = WPTOUCH_VERSION;
			break;
		case 'theme_count':
			$themes = $wptouch_pro->get_available_themes();
			$setting = count( $themes );
			break;
		case 'icon_set_count':
			$icon_sets = $wptouch_pro->get_available_icon_packs();
			// Remove the custom icon count
			$setting = count( $icon_sets ) - 1;
			break;
		case 'icon_count':
			$icon_sets = $wptouch_pro->get_available_icon_packs();
			$total_icons = 0;
			foreach( $icon_sets as $setname => $set ) {
				if ( $setname == "Custom Icons" ) continue;

				$icons = $wptouch_pro->get_icons_from_packs( $setname );
				$total_icons += count( $icons );
			}
			$setting = $total_icons;
			break;
		case 'support_licenses_remaining':
			$licenses = $wptouch_pro->bnc_api->user_list_licenses( 'wptouch-pro' );
			if ( $licenses ) {
				$setting = $licenses['remaining'];
			} else {
				$setting = 0;
			}
			break;
		case 'support_licenses_total':
			$licenses = $wptouch_pro->bnc_api->get_total_licenses( 'wptouch-pro' );
			if ( $licenses ) {
				$setting = $licenses;
			} else {
				$setting = 0;
			}
			break;
		case 'active_theme_friendly_name':
			$theme_info = $wptouch_pro->get_current_theme_info();
			if ( $theme_info ) {
				$setting = $theme_info->name;
			}
			break;
		case 'rss_url':
			if ( $settings->menu_custom_rss_url ) {
				$setting = $settings->menu_custom_rss_url;
			} else {
				$setting = get_bloginfo( 'rss2_url' );
			}
			break;
		case 'warnings':
			$setting = wptouch_get_plugin_warning_count();
			break;
		case 'url':
			if ( $settings->homepage_landing != 'none' ) {
				if ( $settings->homepage_landing == 'custom' ) {
					$setting = $settings->homepage_redirect_custom_target;
				} else {
					$setting = get_permalink( $settings->homepage_redirect_wp_target );
				}
			} else {
				$setting = home_url();
			}
			break;
		case 'search_url':
			if ( function_exists( 'home_url' ) ) {
				$setting = home_url();
			} else {
				$setting = get_bloginfo( 'home' );
			}
			break;
		default:
			// proxy other values to the original get_bloginfo function
			$setting = get_bloginfo( $setting_name );
			break;
	}

	return $setting;
}

function wptouch_get_locale() {
	global $wptouch_pro;

	return $wptouch_pro->locale;
}

function wptouch_get_desktop_bloginfo( $param ) {
        switch( $param ) {
                case 'stylesheet_directory':
                case 'template_url':
                case 'template_directory':
                        return WP_CONTENT_URL . '/themes/' . get_option( 'template' );
                default:
                        return get_bloginfo( $param );
        }
}

function wptouch_desktop_bloginfo( $param ) {
        echo wptouch_get_desktop_bloginfo( $param );
}

function wptouch_can_cloud_install( $theme = true ) {
	global $wptouch_pro;
	return $wptouch_pro->can_perform_cloud_install( $theme );
}

function wptouchize_it( $str ) {
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		return str_replace( 'WPtouch Pro', 'WPtouch', $str );
	} else {
		return $str;
	}
}

