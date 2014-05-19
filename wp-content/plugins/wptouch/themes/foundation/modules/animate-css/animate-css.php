<?php

add_action( 'foundation_module_init_mobile', 'foundation_animate_init' );

function foundation_animate_init() {
	// Load JS
	wp_register_style( 
		'foundation_animate_css', 
		foundation_get_base_module_url() . '/animate-css/animate.css', 
		'',
		FOUNDATION_VERSION,
		'screen'
	);

	wp_enqueue_style( 'foundation_animate_css' );

}