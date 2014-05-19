<?php

function wptouch_delete_all_transients() {
	delete_transient( '_wptouch_available_cloud_themes' );
	delete_transient( '_wptouch_available_cloud_addons' );
}

function wptouch_settings_process( $wptouch_pro ) {
	if ( isset( $wptouch_pro->post[ 'wptouch-reset-3' ] ) ) {
		$wptouch_pro->verify_post_nonce();

		// Clear the cookie
		setcookie( 'wptouch-admin-menu', 0, time() - 3600 );

		WPTOUCH_DEBUG( WPTOUCH_INFO, "Settings are being reset" );
		$wptouch_pro->erase_all_settings();

		$wptouch_pro->reset_icon_states();
		$wptouch_pro->reload_settings();

		require_once( WPTOUCH_DIR . '/core/menu.php' );

		// Check for multisite reset
		if ( wptouch_is_multisite_enabled() && wptouch_is_multisite_primary() ) {
			delete_site_option( WPTOUCH_MULTISITE_LICENSED );
		}

		$wptouch_pro->redirect_to_page( admin_url( 'admin.php?page=wptouch-admin-touchboard' ) );

		wptouch_delete_all_transients();

	} else if ( isset( $wptouch_pro->post['wptouch-submit-3' ] ) ) {
		$wptouch_pro->verify_post_nonce();

		if ( isset( $wptouch_pro->post[ 'wptouch_restore_settings'] ) && strlen( $wptouch_pro->post[ 'wptouch_restore_settings' ] ) ) {
			require_once( 'admin-backup-restore.php' );

			wptouch_restore_settings( $wptouch_pro->post[ 'wptouch_restore_settings'] );
			return;
		}

		$new_settings = array();
		$modified_domains = array();

		// Search for all the settings to update
		foreach( $wptouch_pro->post as $key => $content ) {
			if ( preg_match( '#^wptouch__(.*)__(.*)#', $key, $match ) ) {
				$setting_domain = $match[1];
				$setting_name = $match[2];

				// Decode slashes on strings
				if ( is_string( $content ) ) {
					$content = htmlspecialchars_decode( $content );
				}

				$new_settings[ $setting_domain ][ $setting_name ] = apply_filters( 'wptouch_modify_setting__' . $setting_domain . '__' . $setting_name, $content );

				// Flag which domains have been modified
				$modified_domains[ $setting_domain ] = 1;

				if ( isset( $wptouch_pro->post[ 'hid-wptouch__' . $match[1] . '__' . $match[2] ] ) ) {
					// This is a checkbox
					$new_settings[ $setting_domain ][ $setting_name ] = 1;
				}
			}
		}

		// Do a loop and find all the checkboxes that should be disabled
		foreach( $wptouch_pro->post as $key => $content ) {
			if ( preg_match( '#^hid-wptouch__(.*)__(.*)#', $key, $match ) ) {
				$setting_domain = $match[1];
				$setting_name = $match[2];

				$new_settings[ $setting_domain ][ $setting_name ] = ( isset( $new_settings[ $setting_domain ][ $setting_name ] ) ? 1 : 0 );

				$modified_domains[ $setting_domain ] = 1;
			}
		}

		// Update all the domains that have been modified
		foreach( $modified_domains as $domain => $ignored_value ) {
			$settings = $wptouch_pro->get_settings( $domain );

			// Update settings with new values
			foreach( $new_settings[ $domain ] as $key => $value ) {
				if ( isset( $settings->$key ) ) {
					$settings->$key = $value;
				}
			}

			$settings->save();
		}

		// Handle automatic backup
		$settings = wptouch_get_settings();
		if ( $settings->automatically_backup_settings ) {
			require_once( 'admin-backup-restore.php' );

			wptouch_backup_settings();
		}

		wptouch_delete_all_transients();
	}
}