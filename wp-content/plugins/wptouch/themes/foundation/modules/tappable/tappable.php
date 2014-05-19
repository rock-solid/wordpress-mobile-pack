<?php

/* 
Make sure to add a class of 'tappable' in your theme or add-on
to everything you want to use this function with!
*/

add_action( 'foundation_module_init_mobile', 'foundation_tappable_init' );

function foundation_tappable_init() {
	wp_enqueue_script( 
		'foundation_tappable', 
		foundation_get_base_module_url() . '/tappable/tappable.min.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true
	);
	
	wp_enqueue_script( 
		'foundation_tappable_wptouch', 
		foundation_get_base_module_url() . '/tappable/wptouch.tappable.js',
		array( 'foundation_tappable' ),
		FOUNDATION_VERSION,
		true 
	);
}