<?php

// Only define these functions if WordTwit Pro is installed
if ( defined( 'WORDTWIT_PRO_INSTALLED' ) ) {

	function wptouch_wordtwit_get_enabled_accounts() {
		$enabled_accounts = array();
		
		if ( wordtwit_wptouch_has_accounts() ) {
			$accounts = wordtwit_wptouch_get_accounts();
			$settings = wptouch_get_settings();
			
			foreach( $accounts as $name => $account ) {
				if ( wptouch_wordtwit_current_user_can_use_account( $account ) ) {
					$setting_name = 'wordtwit_account_' . $name;
					
					if ( isset( $settings->$setting_name ) && $settings->$setting_name ) {
						$enabled_accounts[ $name ] = $account;	
					}	
				}	
			}	
		}
		
		return $enabled_accounts;	
	}
	
	function wptouch_wordtwit_current_user_can_use_account( $account ) {
		return ( wordtwit_current_user_can_tweet_from_account( $account ) && ( $account->is_global || $account->is_site_wide ) );
	}
	
	function wptouch_wordtwit_get_recent_tweets() {
		$recent_tweets = array();
		
		$enabled_accounts = wptouch_wordtwit_get_enabled_accounts();
		if ( count( $enabled_accounts ) ) { 
			
			$hash_string = '';
			foreach( $enabled_accounts as $name => $account ) {
				$hash_string = $hash_string . $name;
			}
			
			$hash = md5( $hash_string );
			
			$account_info = get_option( 'wordtwit_pro_tweet_cache' );
			if ( $account_info ) {
				if ( time() < ( $account_info->last_time + 300 ) ) {
					if ( $account_info->hash == $hash ) {
						return $account_info->cached_data;
					}
				}
			}
			
			foreach( $enabled_accounts as $name => $account ) {
				$tweets = wordtwit_wptouch_get_tweets_for_account( $name );
				
				foreach( $tweets as $num => $one_tweet ) {
					$tweet = new stdClass;
					
					$tweet->screen_name = $account->screen_name;
					$tweet->profile_image_url = $account->profile_image_url;
					$tweet->created_at = strtotime( $one_tweet['created_at'] );
					$tweet->text = $one_tweet['text'];
					$tweet->id = $one_tweet['id'];
					$tweet->url = 'https://mobile.twitter.com/#!/' . $account->screen_name . '/status/' . $one_tweet['id'];
	
					$recent_tweets[ $tweet->created_at ] = $tweet;
				}
			}	
			
			if ( count( $recent_tweets ) ) {
				krsort( $recent_tweets );	
			}
			
			if ( count( $recent_tweets ) > 10 ) {
				$recent_tweets = array_slice( $recent_tweets, 0, 10 );	
			}
			
			$account_info = new stdClass;
			$account_info->cached_data = $recent_tweets;
			$account_info->last_time = time();
			$account_info->hash = $hash;
			
			update_option( 'wordtwit_pro_tweet_cache', $account_info );
		}
		
		return $recent_tweets;
	}
	
	global $wordtwit_tweet;
	global $wordtwit_tweet_iterator;
	
	$wordtwit_tweet_iterator = false;
	
	function wptouch_wordtwit_has_recent_tweets() {
		global $wordtwit_tweet;
		global $wordtwit_tweet_iterator;
		
		if ( !$wordtwit_tweet_iterator ) {
			$tweets = wptouch_wordtwit_get_recent_tweets();
			
			$wordtwit_tweet_iterator = new WPtouchArrayIterator( $tweets );	
		}	
		
		return $wordtwit_tweet_iterator->have_items();
	}
	
	function wptouch_wordtwit_the_recent_tweet() {
		global $wordtwit_tweet_iterator;	
		global $wordtwit_tweet;	
		
		if ( $wordtwit_tweet_iterator ) {
			$wordtwit_tweet = $wordtwit_tweet_iterator->the_item();	
		}
	}
	
	function wptouch_wordtwit_recent_tweet_get_profile_image() {
		global $wordtwit_tweet;	
		$image = false;
		
		if ( isset( $wordtwit_tweet->profile_image_url ) ) {
			$image = $wordtwit_tweet->profile_image_url;	
		}	
		
		return apply_filters( 'wptouch_wordtwit_recent_tweet_profile_image', $image );
	}
	
	function wptouch_wordtwit_recent_tweet_the_profile_image() {
		echo wptouch_wordtwit_recent_tweet_get_profile_image();
	}
	
	function wptouch_wordtwit_recent_tweet_get_screen_name() {
		global $wordtwit_tweet;	
		$screen_name = false;
		
		if ( isset( $wordtwit_tweet->screen_name ) ) {
			$screen_name = $wordtwit_tweet->screen_name;	
		}	
		
		return apply_filters( 'wptouch_wordtwit_recent_tweet_screen_name', $screen_name );	
	}
	
	function wptouch_wordtwit_the_recent_tweet_url() {
		global $wordtwit_tweet;	
		
		echo $wordtwit_tweet->url;
	}
	
	
	function wptouch_wordtwit_recent_tweet_the_screen_name() {
		echo wptouch_wordtwit_recent_tweet_get_screen_name();
	}
	
	function wptouch_wordtwit_recent_tweet_get_text() {
		global $wordtwit_tweet;	
		$tweet_text = false;
		
		if ( isset( $wordtwit_tweet->text ) ) {
			$tweet_text = $wordtwit_tweet->text;	
		}	
		
		return apply_filters( 'wptouch_wordtwit_recent_tweet_text', $tweet_text );		
	}
	
	function wptouch_wordtwit_recent_tweet_the_text() {
		echo wptouch_wordtwit_recent_tweet_get_text();	
	}
	
	function wptouch_wordtwit_recent_tweet_the_hours_ago() {
		global $wordtwit_tweet;	
		
		if ( isset( $wordtwit_tweet->created_at ) ) {
			$seconds_since = time() - $wordtwit_tweet->created_at;
			
			$secs_60 = 60;
			$mins_60 = 3600;
			$hours_24 = 24*3600;
			
			if ( $seconds_since < $secs_60 ) {
				echo sprintf( _n( '%d second ago', '%d seconds ago', $seconds_since, 'wptouch-pro' ), $seconds_since );
			} else if ( $seconds_since < $mins_60 ) {
				$mins = floor( $seconds_since / 60 );
				echo sprintf( _n( '%d minute ago', '%d minutes ago', $mins, 'wptouch-pro' ), $mins );
			} else if ( $seconds_since < $hours_24 ) {
				$hours = floor( $seconds_since / 3600 );
				echo sprintf( _n( '%d hour ago', '%d hours ago', $hours, 'wptouch-pro' ), $hours );			
			} else {
				$days = floor( $seconds_since / $hours_24 );
				echo sprintf( _n( '%d day ago', '%d days ago', $days, 'wptouch-pro' ), $days );	
			}
		}
	}

}

