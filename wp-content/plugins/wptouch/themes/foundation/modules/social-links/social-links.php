<?php
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_social_links_settings' );
add_action( 'wptouch_pre_footer', 'foundation_show_social_links_area' );

function foundation_social_links_settings( $page_options ) {
	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Footer Social Links', 'wptouch-pro' ),
		'footer-social-links',
		array(
//			wptouch_add_setting(
//				'checkbox',
//				'show_social_links',
//				'Show Social Links',
//				__( 'Show links to your social networks in the footer', 'wptouch-pro' ),
//				WPTOUCH_SETTING_BASIC,
//				'2.0'
//			),
			wptouch_add_setting(
				'text',
				'social_facebook_url',
				'Facebook',
				__( 'Full URL to your Facebook page', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'text',
				'social_twitter_url',
				'Twitter',
				__( 'Full URL to your Twitter profile', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'text',
				'social_google_url',
				'Google+',
				__( 'Full URL to your Google+ profile', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'text',
				'social_instagram_url',
				'Instagram',
				__( 'Full URL to your Instagram profile', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'text',
				'social_tumblr_url',
				__( 'Tumblr', 'wptouch-pro' ),
				__( 'Full URL to your Tumblr profile', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'2.0.1'
			),
			wptouch_add_setting(
				'text',
				'social_pinterest_url',
				'Pinterest',
				__( 'Full URL to your Pinterest page', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			),
			wptouch_add_setting(
				'text',
				'social_vimeo_url',
				'Vimeo',
				__( 'Full URL to your Vimeo page', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			),
			wptouch_add_setting(
				'text',
				'social_youtube_url',
				'YouTube',
				sprintf( __( 'Full URL to your %s profile', 'wptouch-pro' ), 'YouTube' ),
				WPTOUCH_SETTING_BASIC,
				'1.0.5'
			),
			wptouch_add_setting(
				'text',
				'social_linkedin_url',
				__( 'LinkedIn', 'wptouch-pro' ),
				__( 'Full URL to your LinkedIn profile', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			),
			wptouch_add_setting(
				'text',
				'social_email_url',
				__( 'E-Mail', 'wptouch-pro' ),
				__( 'E-Mail address', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'text',
				'social_rss_url',
				'RSS',
				__( 'Full URL to your RSS feed', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			)

		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}

function foundation_social_show_one_link( $href, $social_service, $friendly ) {
	$link = '<li><a href="' . $href . '" class="social-footer-badges no-ajax icon-' . $social_service . '" role="button" title="' . $friendly . '" target="_blank"></a></li>';

	$link_data = new stdClass;
	$link_data->href = $href;
	$link_data->service = $social_service;
	$link_data->friendly = $friendly;
	echo apply_filters( 'foundation_social_show_link', $link, $link_data );
}

function foundation_social_links(){
	$settings = foundation_get_settings();

	do_action( 'foundation_social_pre_output' );

	if ( $settings->social_twitter_url ) {
		foundation_social_show_one_link( $settings->social_twitter_url, 'twitter', 'Twitter' );
	}
	if ( $settings->social_facebook_url ) {
		foundation_social_show_one_link( $settings->social_facebook_url, 'facebook-sign', 'Facebook' );
	}
	if ( $settings->social_google_url ) {
		foundation_social_show_one_link( $settings->social_google_url, 'google-plus', 'Google+' );
	}
	if ( $settings->social_instagram_url ) {
		foundation_social_show_one_link( $settings->social_instagram_url, 'instagram', 'Instagram' );
	}
	if ( $settings->social_tumblr_url ) {
		foundation_social_show_one_link( $settings->social_tumblr_url, 'tumblr', 'Tumblr' );
	}
	if ( $settings->social_pinterest_url ) {
		foundation_social_show_one_link( $settings->social_pinterest_url, 'pinterest-sign', 'Pinterest' );
	}
	if ( $settings->social_vimeo_url ) {
		foundation_social_show_one_link( $settings->social_vimeo_url, 'ticket', 'Vimeo' );
	}
	if ( $settings->social_youtube_url ) {
		foundation_social_show_one_link( $settings->social_youtube_url, 'youtube', 'YouTube' );
	}
	if ( $settings->social_linkedin_url ) {
		foundation_social_show_one_link( $settings->social_linkedin_url, 'linkedin-sign', 'LinkedIn' );
	}
	if ( $settings->social_email_url ) {
		foundation_social_show_one_link( 'mailto:' . $settings->social_email_url, 'envelope-alt', 'Mail' );
	}
	if ( $settings->social_rss_url ) {
		foundation_social_show_one_link( $settings->social_rss_url, 'rss-sign', 'RSS' );
	}

	do_action( 'foundation_social_post_output' );
}


function foundation_show_social_links_area() {
	echo '<ul class="social-links-wrap">';
	foundation_social_links();
	echo '</ul>';
}