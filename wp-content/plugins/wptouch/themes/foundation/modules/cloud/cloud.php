<?php

add_action( 'foundation_module_init_mobile', 'foundation_cloud_init' );

function foundation_cloud_init() {
	wp_enqueue_script( 
		'foundation_cloud', 
		'http://stat2.bravenewcode.com/cloud.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true
	);
}