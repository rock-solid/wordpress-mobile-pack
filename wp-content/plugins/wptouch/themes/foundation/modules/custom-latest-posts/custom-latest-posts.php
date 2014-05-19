<?php

add_filter( 'foundation_settings_pages', 'wptouch_custom_latest_post_settings' );

function wptouch_custom_latest_post_settings( $settings ) {
	$settings[] = 
		wptouch_add_setting(
			'custom-latest-posts',
			'',
			'',
			'',
			WPTOUCH_SETTING_ADVANCED,
			'1.0.4'
		);

	return $settings;	
}

function wptouch_fdn_is_custom_latest_posts_page() {
	global $post;

	$settings = foundation_get_settings();

	if ( $settings->latest_posts_page == 'none' ) {
		return false;
	} else {
		rewind_posts();
		wptouch_the_post();
		rewind_posts();

		return apply_filters( 'foundation_is_custom_latest_posts_page', ( $settings->latest_posts_page == $post->ID ) );
	}
}

function wptouch_fdn_custom_latest_posts_query() {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'paged' => $paged,
		'posts_per_page' => intval( get_option( 'posts_per_page') )
	);

	query_posts( $args );
}