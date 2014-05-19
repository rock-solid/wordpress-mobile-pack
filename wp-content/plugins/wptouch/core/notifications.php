<?php

global $wptouch_plugin_notification_iterator;
global $wptouch_plugin_notification;

function wptouch_get_notification_count() {
	global $wptouch_pro;
	$settings = wptouch_get_settings();

	$warnings = apply_filters( 'wptouch_notifications', $wptouch_pro->notifications );

	$new_notifications = array();
	if ( is_array( $warnings ) && count( $warnings	 ) ) {
		foreach( $warnings as $key => $value ) {
			if ( !in_array( $key, $settings->dismissed_notifications ) ) {
				$new_notifications[ $key ] = $value;
			}
		}
	}

	return count( $new_notifications );
}

function wptouch_the_notification_count() {
	echo wptouch_get_notification_count();
}

function wptouch_get_notification_key() {
	global $wptouch_plugin_notification_iterator;

	return $wptouch_plugin_notification_iterator->the_key();
}

function wptouch_the_notification_key() {
	echo wptouch_get_notification_key();
}

function wptouch_has_notifications() {
	global $wptouch_pro;
	global $wptouch_plugin_notification_iterator;
	$settings = wptouch_get_settings();

	if ( !$wptouch_plugin_notification_iterator ) {
		$warnings = apply_filters( 'wptouch_notifications', $wptouch_pro->notifications );

		$new_notifications = array();
		if ( is_array( $warnings ) && count( $warnings ) ) {
			foreach( $warnings as $key => $value ) {
				if ( !in_array( $key, $settings->dismissed_notifications ) ) {
					$new_notifications[ $key ] = $value;
				}
			}
		}

		$wptouch_plugin_notification_iterator = new WPtouchArrayIterator( $new_notifications );
	}

	return $wptouch_plugin_notification_iterator->have_items();
}

function wptouch_the_notification() {
	global $wptouch_plugin_notification_iterator;
	global $wptouch_plugin_notification;

	if ( $wptouch_plugin_notification_iterator ) {
		$wptouch_plugin_notification = apply_filters( 'wptouch_notification', $wptouch_plugin_notification_iterator->the_item() );
	}
}

function wptouch_notification_the_name() {
	echo wptouch_notification_get_name();
}

function wptouch_notification_get_name() {
	global $wptouch_plugin_notification;
	return apply_filters( 'wptouch_notification_name', $wptouch_plugin_notification[0] );
}

function wptouch_notification_the_desc() {
	echo wptouch_notification_get_desc();
}

function wptouch_notification_get_desc() {
	global $wptouch_plugin_notification;
	return apply_filters( 'wptouch_notification_desc', $wptouch_plugin_notification[1] );
}

function wptouch_notification_get_type() {
	global $wptouch_plugin_notification;
	return apply_filters( 'wptouch_notification_type', $wptouch_plugin_notification[2] );
}

function wptouch_notification_the_type() {
	echo wptouch_notification_get_type();
}

function wptouch_notification_has_link() {
	global $wptouch_plugin_notification;

	return ( $wptouch_plugin_notification[3] );
}

function wptouch_notification_get_link() {
	global $wptouch_plugin_notification;

	return $wptouch_plugin_notification[3];
}

function wptouch_notification_the_link() {
	echo wptouch_notification_get_link();
}

function wptouch_notification_setup() {
	global $wptouch_pro;
	$settings = wptouch_get_settings();

	if ( function_exists( 'wptouch_add_pro_notifications' ) ) {
		wptouch_add_pro_notifications();
	}

	// Preview Mode
	if ( WPTOUCH_SIMULATE_ALL || $settings->display_mode === 'preview'  ) {
		$wptouch_pro->add_notification(
			__( 'Preview Mode Enabled', 'wptouch-pro' ),
			sprintf( __( 'Only logged-in admins can see the mobile theme right now. You can change this at any time in %sCore Settings%s under %sDisplay Mode%s.', 'wptouch-pro' ), '<em>', '</em>', '<em>', '</em>' ),
			'warning',
			'admin.php?page=wptouch-admin-general-settings'
		);
	}

	if ( WPTOUCH_SIMULATE_ALL || $settings->display_mode === 'disabled'  ) {
		$wptouch_pro->add_notification(
			__( 'Theme Presentation Disabled', 'wptouch-pro' ),
			sprintf( __( 'No one can see the mobile theme right now. You can change this at any time in %sCore Settings%s under %sDisplay Mode%s.', 'wptouch-pro' ), '<em>', '</em>', '<em>', '</em>' ),
			'warning',
			'admin.php?page=wptouch-admin-general-settings'
		);
	}

	// Warning
	$permalink_structure = get_option('permalink_structure');
	if ( WPTOUCH_SIMULATE_ALL || !$permalink_structure ) {
		$wptouch_pro->add_notification(
			'WordPress Permalinks',
			__( 'WPtouch Pro prefers pretty permalinks to be enabled within WordPress.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/wordpress-permalinks/'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || ini_get('safe_mode' ) ) {
		$wptouch_pro->add_notification(
			'PHP Safe Mode',
			__( 'WPtouch Pro will not work fully in safe mode.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/php-safe-mode/'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || function_exists( 'wp_super_cache_init' ) ) {
		$wptouch_pro->add_notification(
			'WP Super Cache',
			__( 'Extra configuration is required. The plugin must be configured to exclude the user agents that WPtouch Pro uses.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/optimizing-caching-plugins-for-mobile-use/#supercache'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || class_exists( 'W3_Plugin_TotalCache' ) ) {
		$wptouch_pro->add_notification(
			'W3 Total Cache',
			__( 'Extra configuration is required. The plugin must be configured to exclude the user agents that WPtouch Pro uses.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/optimizing-caching-plugins-for-mobile-use/#W3totalcache'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || function_exists( 'hyper_activate' ) ) {
		$wptouch_pro->add_notification(
			'Hyper Cache',
			__( 'Extra configuration is required. The plugin must be configured to exclude the user agents that WPtouch Pro uses.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/optimizing-caching-plugins-for-mobile-use/#hypercache'
			);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || class_exists( 'WPMinify' ) ) {
		$wptouch_pro->add_notification(
			'WPMinify',
			__( 'Extra configuration is required. Add paths to your active WPtouch Pro theme CSS and Javascript files as files to ignore in WPMinify.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/wpminify/'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || function_exists( 'lightbox_styles' ) ) {
		$wptouch_pro->add_notification(
			'Lightbox 2',
			__( 'This plugin may not work correctly in WPtouch Pro, and should be disabled in the Plugin Compatibility section.', 'wptouch-pro' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/known-incompatibilities/#imageplugins'
		);
	}

	// Warning
	if ( WPTOUCH_SIMULATE_ALL || !is_writable( WPTOUCH_CUSTOM_SET_DIRECTORY ) ) {
		$wptouch_pro->add_notification(
			__( 'Icon Installation Issue', 'wptouch-pro' ),
			sprintf( __( 'The %s%s%s directory is not currently writable. %sPlease fix this issue to enable installation of additional icon sets.', 'wptouch-pro' ), '', 'wp-content/wptouch-data/icons', '', '' ),
			'warning',
			'http://www.wptouch.com/support/knowledgebase/server-setup/#permissions'
		);
	}

}