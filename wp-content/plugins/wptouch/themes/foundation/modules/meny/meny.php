<?php

add_action( 'foundation_module_init_mobile', 'foundation_meny_init' );

function foundation_meny_init() {
	wp_enqueue_script( 
		'foundation_meny', 
		foundation_get_base_module_url() . '/meny/meny.min.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true 
	);	

	wp_enqueue_script( 
		'foundation_meny_wptouch', 
		foundation_get_base_module_url() . '/meny/wptouch.meny.js',
		array( 'foundation_meny' ),
		FOUNDATION_VERSION,
		true 
	);
}