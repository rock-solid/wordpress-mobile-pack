<?php

add_action( 'init', 'foundation_featured_setup' );
add_action( 'foundation_module_init_mobile', 'foundation_featured_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_featured_settings' );


define( 'FOUNDATION_FEATURED_MIN_NUM', 2 );

global $foundation_featured_args;
global $foundation_featured_posts;
global $foundation_featured_data;


function foundation_featured_init() {
	$settings = foundation_get_settings();
	if ( $settings->featured_enabled ) {

		wp_enqueue_script(
			'foundation_featured',
			foundation_get_base_module_url() . '/featured/swipe.js',
			false,
			FOUNDATION_VERSION,
			true
		);

		wp_enqueue_script(
			'foundation_featured_init',
			foundation_get_base_module_url() . '/featured/wptouch-swipe.js',
			'foundation_featured',
			FOUNDATION_VERSION,
			true
		);

		foundation_determine_images();
	}
}

function foundation_featured_setup() {
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'foundation-featured-image', 900, 9999, false );
	}
}

function foundation_featured_config( $args ) {
	global $foundation_featured_args;

	$foundation_featured_args = $args;
}

function foundation_featured_modify_query( $query ) {
	$settings = foundation_get_settings();
	if ( $settings->featured_filter_posts ) {
		return;
	}

	$should_be_ignored = apply_filters(
		'foundation_featured_should_modify_query',
		$query->is_single || $query->is_page || $query->is_feed || $query->is_search || $query->is_archive || $query->is_category,
		$query
	);

	if ( $should_be_ignored ) {
		return;
	}

	global $foundation_featured_posts;

	if ( count( $foundation_featured_posts ) < FOUNDATION_FEATURED_MIN_NUM ) {
		return $query;
	}

	$post_array = array();

	foreach( $foundation_featured_posts as $post_id ) {
		$post_array[] = '-' . $post_id;
	}

	$query->query_vars[ 'post__not_in']  = $post_array;

	return $query;
}

function foundation_featured_get_args() {
	$settings = foundation_get_settings();
	$max_posts = $settings->featured_max_number_of_posts;

	global $foundation_featured_args;

	$defaults = array(
		'type' => 'post',
		'num' => $max_posts,
		'show_dots' => true,		// might not be needed
		'before' => '',
		'after' => '',
		'max_search' => 20
	);
	// Parse defaults into arguments
	return wp_parse_args( $foundation_featured_args, $defaults );
}

function foundation_determine_images() {
	global $foundation_featured_posts;
	global $foundation_featured_data;
	global $post;

	$settings = foundation_get_settings();

	$foundation_featured_posts = array();
	$foundation_featured_data = array();

	$args = foundation_featured_get_args();

	$new_posts = false;
	switch( $settings->featured_type ) {
		case 'tag':
			$new_posts = new WP_Query( 'tag=' . $settings->featured_tag . '&posts_per_page=' . $args[ 'max_search' ] );
			break;
		case 'category':
			$new_posts = new WP_Query( 'category_name=' . $settings->featured_category . '&posts_per_page=' . $args[ 'max_search' ] );
			break;
		case 'posts':
			$post_ids = explode( ',', str_replace( ' ', '', $settings->featured_post_ids ) );
			if ( is_array( $post_ids ) && count( $post_ids ) ) {
				$new_posts = new WP_Query( array( 'post__in'  => $post_ids, 'posts_per_page' => $args[ 'max_search' ], 'post_type' => 'any', 'orderby' => 'post__in' ) );
			}
			break;
		case 'latest':
		default:
			break;
	}

	if ( !$new_posts ) {
		$new_posts = new WP_Query( 'posts_per_page=' . $args[ 'max_search' ] );
	}

	while ( $new_posts->have_posts() ) {
		$new_posts->the_post();

		$image = get_the_post_thumbnail( $post->ID, 'foundation-featured-image' );

		if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
			$image = $matches[1];

			$our_size = sprintf( "%d", WPTOUCH_FEATURED_SIZE );
			if ( strpos( $image, $our_size ) === false ) {
				// It's not our image, so just use the WP medium size
				$image = get_the_post_thumbnail( $post->ID, 'large' );
				if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
					$image = $matches[1];
				}
			}
		}

		if ( $image ) {
			$results = new stdClass;
			$results->image = $matches[1];
			$results->date = get_the_date();
			$results->title = get_the_title();
			$results->link = get_permalink();
			$results->comments_number = wptouch_get_comment_count();

			$foundation_featured_data[] = $results;

			$foundation_featured_posts[] = $post->ID;
		}

		// Break out if we have enough images
		if ( count( $foundation_featured_data ) == $args[ 'num' ] ) {
			break;
		}
	}

	add_filter( 'parse_query', 'foundation_featured_modify_query' );
}

function featured_should_show_slider() {
	$should_show = is_home() || is_front_page();

	return apply_filters( 'foundation_featured_show', $should_show );
}

function foundation_featured_get_slider_classes() {
	$settings = foundation_get_settings();

	$featured_classes = array( 'swipe' );

	if ( $settings->featured_grayscale ) {
		$featured_classes[] = 'grayscale';
	}

	if ( $settings->featured_autoslide ) {
		$featured_classes[] = 'slide';
	}

	if ( $settings->featured_continuous ) {
		$featured_classes[] = 'continuous';
	}

	switch( $settings->featured_speed ) {
		case 'slow':
			$featured_classes[] = 'slow';
			break;
		case 'fast':
			$featured_classes[] = 'fast';
			break;
	}

	return $featured_classes;
}

function foundation_featured_slider( $manual = false, $manual_html = false ) {
	global $foundation_featured_data;

	$args = foundation_featured_get_args();
	$settings = foundation_get_settings();

	if ( $manual == false && ( count( $foundation_featured_data ) >= FOUNDATION_FEATURED_MIN_NUM ) && $settings->featured_enabled ) {
		echo $args['before'];

		echo "<div id='slider' class='" . implode( ' ', foundation_featured_get_slider_classes() ) . "'>\n";
		echo "<div class='swipe-wrap'>\n";

		foreach( $foundation_featured_data as $image_data ) {
			echo "<div class='one-swipe-image' style='visibility: hidden;'>";
			echo "<a href='" . $image_data->link . "' class='needsclick'>";
			echo "<div class='comments-number'><span>" . $image_data->comments_number . "</span></div>";
			echo "<img src='" . $image_data->image . "' alt='" . $image_data->title . "' / >";
			if ( $settings->featured_title_date ) {
				echo "<p class='featured-date'>". $image_data->date . "</p>";
				echo "<p class='featured-title'><span>". $image_data->title . "</span></p>";
			}
			echo "</a>";
			echo "</div>";
		}

		echo "</div>\n";
		echo "</div>\n";
		echo $args['after'];
	// Private for now, we'll improve manual mode for customer use in 3.2
	} elseif ( $manual == true ) {
		echo $args['before'];

		echo "<div id='slider' class='" . implode( ' ', foundation_featured_get_slider_classes() ) . "'>\n";
		echo "<div class='swipe-wrap'>\n";

		echo $manual_html;

		echo "</div>\n";
		echo "</div>\n";
		echo $args['after'];
	}
}

function foundation_featured_settings( $page_options ) {

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Featured Slider', 'wptouch-pro' ),
		'foundation-featured-settings',
		array(
			wptouch_add_setting(
				'checkbox',
				'featured_enabled',
				__( 'Enable featured slider', 'wptouch-pro' ),
				__( 'Requires at least 2 entries to contain featured images', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_setting(
				'list',
				'featured_max_number_of_posts',
				__( 'Maximum number of posts', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_ADVANCED,
				'2.0',
				array(
					'3' => __( '3 posts', 'wptouch-pro' ),
					'5' => __( '5 posts', 'wptouch-pro' ),
					'10' => __( '10 posts', 'wptouch-pro' )
				)
			),
			wptouch_add_setting(
				'checkbox',
				'featured_autoslide',
				__( 'Automatically slide', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			),
			wptouch_add_setting(
				'checkbox',
				'featured_continuous',
				__( 'Continuously slide', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0.2'
			),
			wptouch_add_setting(
				'checkbox',
				'featured_grayscale',
				__( 'Grayscale images (CSS 3 effect)', 'wptouch-pro' ),
				__( 'Featured slider images will be in grayscale for devices that support CSS filters.', 'wptouch-pro' ),
				WPTOUCH_SETTING_ADVANCED,
				'1.0'
			),
//			wptouch_add_setting(
//				'checkbox',
//				'featured_title_date',
//				__( 'Show title & date', 'wptouch-pro' ),
//				__( 'If disabled, the title and date will not be shown. Note: some themes do not show the date.', 'wptouch-pro' ),
//				WPTOUCH_SETTING_ADVANCED,
//				'2.0'
//			),
			wptouch_add_setting(
				'checkbox',
				'featured_filter_posts',
				__( 'Featured slider posts also show in listings', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0.3'
			),
			wptouch_add_setting(
				'list',
				'featured_speed',
				__( 'Slide transition speed', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_ADVANCED,
				'1.0.2',
				array(
					'slow' => __( 'Slow', 'wptouch-pro' ),
					'normal' => __( 'Normal', 'wptouch-pro' ),
					'fast' => __( 'Fast', 'wptouch-pro' )
				)
			),
			wptouch_add_setting(
				'list',
				'featured_type',
				'',
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				array(
					'latest' => __( 'Show latest posts', 'wptouch-pro' ),
					'tag' => __( 'Show posts from a specific tag', 'wptouch-pro' ),
					'category' => __( 'Show posts from a specific category', 'wptouch-pro' ),
					'posts' => __( 'Show only specific posts or pages', 'wptouch-pro' )
				)
			),
			wptouch_add_setting(
				'text',
				'featured_tag',
				__( 'Only this tag', 'wptouch-pro' ),
				__( 'Enter the tag/category slug name', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0',
				false //foundation_get_tag_list()
			),
			wptouch_add_setting(
				'text',
				'featured_category',
				__( 'Only this category', 'wptouch-pro' ),
				__( 'Enter the tag/category slug name', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'1.0',
				false //foundation_get_category_list()
			),
			wptouch_add_setting(
				'text',
				'featured_post_ids',
				__( 'Comma-separated list of post/page IDs', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			)
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}