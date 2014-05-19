<?php

define( 'WPTOUCH_TWITTER_SECS_IN_MINUTE', 60 );
define( 'WPTOUCH_TWITTER_SECS_IN_HOUR', WPTOUCH_TWITTER_SECS_IN_MINUTE*60 );
define( 'WPTOUCH_TWITTER_SECS_IN_DAY', WPTOUCH_TWITTER_SECS_IN_HOUR*24 );

add_action( 'foundation_module_init', 'foundation_twitter_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_twitter_settings' );


function foundation_twitter_init() {
	// Load WordTwit extension
	if ( file_exists( WP_PLUGIN_DIR . '/wordtwit-pro/include/extensions/wptouch-pro.php' ) && function_exists( 'wordtwit_get_accounts' ) ) {
		require_once( WP_PLUGIN_DIR . '/wordtwit-pro/include/extensions/wptouch-pro.php' );
	}

	// Free version : )
	if ( file_exists( WP_PLUGIN_DIR . '/wordtwit/include/extensions/wptouch-pro.php' ) && function_exists( 'wordtwit_get_accounts' ) ) {
		require_once( WP_PLUGIN_DIR . '/wordtwit/include/extensions/wptouch-pro.php' );
	}

}

function foundation_twitter_pretty_text( $tweet_text ) {
	$has_mentions = preg_match_all( '/(^|\s)@(\w+)/', $tweet_text, $matches );
	if ( $has_mentions ) {
		foreach( $matches[0] as $mention ) {
			$mention = trim( $mention );
			$minus_at_sign = ltrim( $mention, '@' );

			$tweet_text = str_replace( $mention, '<a href="https://twitter.com/' . $minus_at_sign . '" rel="nofollow">@' . $minus_at_sign . '</a>', $tweet_text );
		}
	}

	return $tweet_text;
}

function foundation_twitter_pretty_time( $time ) {
	$time_diff = time() - $time;

	if ( $time_diff <= WPTOUCH_TWITTER_SECS_IN_MINUTE ) {
		echo sprintf( _n( '%d second ago', '%d seconds ago', $time, 'wptouch-pro' ), $time_diff );
	} else if ( $time_diff <= WPTOUCH_TWITTER_SECS_IN_HOUR ) {
		$minutes = floor( $time_diff / WPTOUCH_TWITTER_SECS_IN_MINUTE );
		echo sprintf( _n( '%d minute ago', '%d minutes ago', $minutes, 'wptouch-pro' ), $minutes );
	} else if ( $time_diff <= WPTOUCH_TWITTER_SECS_IN_DAY ) {
		$hours = floor( $time_diff / WPTOUCH_TWITTER_SECS_IN_HOUR );
		echo sprintf( _n( '%d hour ago', '%d hours ago', $hours, 'wptouch-pro' ), $hours );
	} else {
		$days = floor( $time_diff / WPTOUCH_TWITTER_SECS_IN_DAY );
		echo sprintf( _n( '%d day ago', '%d days ago', $days, 'wptouch-pro' ), $days );
	}
}

function foundation_twitter_get_tweet_time( $raw_tweet_time ) {
	$old_time_zone = date_default_timezone_get();
	date_default_timezone_set( 'UTC' );
	$utc_time = strtotime( $raw_tweet_time );
	date_default_timezone_set( $old_time_zone );

	return $utc_time;
}

function foundation_twitter_settings( $page_options ) {
	if ( defined( 'WORDTWIT_WPTOUCH_PRO_EXT' ) ) {
		$twitter_accounts = array( 'none' => __( 'Disabled', 'wptouch-pro' ) );

		$accounts = wordtwit_wptouch_get_accounts();
		foreach( $accounts as $name => $account ) {
			$twitter_accounts[ $name ] = $name;
		}

		wptouch_add_page_section(
			FOUNDATION_PAGE_GENERAL,
			'Twitter',
			'foundation-web-mobile-twitter',
			array(
				wptouch_add_setting(
					'list',
					'twitter_account',
					__( 'Twitter account to use for Tweet display', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0',
					$twitter_accounts
				)
			),
			$page_options,
			FOUNDATION_SETTING_DOMAIN
		);
	}
	
	return $page_options;
}