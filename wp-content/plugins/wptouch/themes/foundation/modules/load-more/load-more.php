<?php

add_action( 'foundation_module_init_mobile', 'foundation_load_more_init' );

function foundation_load_more_init() {
	// Load JS
	wp_enqueue_script( 
		'foundation_load_more', 
		foundation_get_base_module_url() . '/load-more/load-more.js', 
		array( 'foundation_spinjs_jquery' ),
		FOUNDATION_VERSION,
		true
	);
	wp_enqueue_script( 
		'foundation_load_more', 
		foundation_get_base_module_url() . '/load-more/load-more.js', 
		array( 'foundation_spinjs_jquery' ),
		FOUNDATION_VERSION,
		true
	);
}