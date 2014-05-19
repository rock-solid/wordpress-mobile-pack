<?php

define( 'WPTOUCH_PRO_BNCAPI_PRODUCT_NAME', 'wptouch-pro-3' );
define( 'WPTOUCH_BNCID_CACHE_TIME', 3600 );

function wptouch_has_license() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	if ( false === ( $has_license = get_transient( '_wptouch_bncid_has_license' ) ) ) {
		$has_license = $wptouch_pro->bnc_api->verify_site_license( WPTOUCH_PRO_BNCAPI_PRODUCT_NAME );

		set_transient( '_wptouch_bncid_has_license', $has_license, WPTOUCH_API_GENERAL_CACHE_TIME );
	}

	return $has_license;
}

function wptouch_is_upgrade_available() {
	global $wptouch_pro;

	$upgrade_avail = $wptouch_pro->check_for_update();

	return $upgrade_avail;
}

function wptouch_get_available_cloud_themes() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	return $wptouch_pro->bnc_api->get_all_available_themes();;
}

function wptouch_get_available_cloud_addons() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	return $wptouch_pro->bnc_api->get_all_available_addons();
}

function wptouch_check_api() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	$bnc_settings = wptouch_get_settings( 'bncid' );

	WPTOUCH_DEBUG( WPTOUCH_INFO, 'Checking BNC API to make sure it is working properly' );

	$now = time();
	if ( $now > $bnc_settings->next_update_check_time ) {
		WPTOUCH_DEBUG( WPTOUCH_INFO, '...performing update' );
		$result = $wptouch_pro->bnc_api->check_api();
		if ( isset( $result['has_valid_license'] ) ) {
			if ( !$result['has_valid_license'] ) {
				WPTOUCH_DEBUG( WPTOUCH_INFO, '...DOES NOT appear to have a valid license' );
				if ( $bnc_settings->license_accepted ) {
					$bnc_settings->failures = $bnc_settings->failures + 1;

					WPTOUCH_DEBUG( WPTOUCH_INFO, '......this is failure #' . $bnc_settings->failures );

					if ( $bnc_settings->failures >= WPTOUCH_API_CHECK_FAILURES ) {
						$bnc_settings->failures = 0;

						$bnc_settings->license_accepted = false;
						$bnc_settings->license_accepted_time = 0;
						$bnc_settings->referral_user_id = false;

						$bnc_settings->save();
					}
				}
			} else {
				WPTOUCH_DEBUG( WPTOUCH_INFO, '...user DOES HAVE a valid license' );
				$bnc_settings->failures = 0;
				$bnc_settings->license_accepted = true;
				$bnc_settings->license_accepted_time = $now;

				if ( isset( $result[ 'user_id'] ) ) {
					$bnc_settings->referral_user_id = $result[ 'user_id' ];
				}
			}
		} else {
			WPTOUCH_DEBUG( WPTOUCH_INFO, '...no info? ' . print_r( $result, true ) );
		}

		WPTOUCH_DEBUG( WPTOUCH_INFO, '...saving updated BNCID settings' . print_r( $bnc_settings, true ) );

		$bnc_settings->next_update_check_time = $now + WPTOUCH_API_CHECK_INTERVAL;
		$bnc_settings->save();
	}
}

