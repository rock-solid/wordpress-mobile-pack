<?php
include_once( WPSMART_BASE_THEME . '/base-functions.php' );

if ( ! function_exists( 'wps_excerpt_more' ) ) :
/**
 * Custom 'Read More' text
 *
 */
function wps_excerpt_more( $more )
{
    global $post;
	return '...<span style="color:#2477B3">Read more</span>';
}
endif;
add_filter( 'excerpt_more', 'wps_excerpt_more' );


if ( ! function_exists( 'wps_excerpt_length' ) ) :
/**
 * Set the excerpt length to 80 words
 *
 */
function wps_excerpt_length( $length )
{
	return 80;
}
endif;
add_filter( 'excerpt_length', 'wps_excerpt_length', 999 );


// ... additional custom functions go here