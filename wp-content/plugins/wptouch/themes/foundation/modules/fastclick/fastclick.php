<?php

add_action( 'foundation_module_init_mobile', 'foundation_fastclick_init' );

function foundation_fastclick_init() {
	wp_enqueue_script( 
		'foundation_fastclick_wptouch', 
		foundation_get_base_module_url() . '/fastclick/wptouch.fastclick.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true 
	);

	wp_enqueue_script( 
		'foundation_fastclick', 
		foundation_get_base_module_url() . '/fastclick/fastclick.min.js',
		array( 'foundation_fastclick_wptouch' ),
		FOUNDATION_VERSION,
		true 
	);	
}