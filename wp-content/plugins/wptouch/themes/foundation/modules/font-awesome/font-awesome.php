<?php

add_action( 'foundation_module_init_mobile', 'foundation_font_awesome_init' );

function foundation_font_awesome_get_style_deps() {
	$style_deps = array();

	if ( defined( 'WPTOUCH_MODULE_RESET_INSTALLED' ) ) {
		$style_deps[] = 'foundation_reset';
	}				

	return $style_deps;
}

function foundation_font_awesome_init() {
	wp_enqueue_style( 
		'foundation_font_awesome_css',
		foundation_get_base_module_url() . '/font-awesome/font-awesome.min.css',
		'',
		FOUNDATION_VERSION,
		'screen'
	);
}