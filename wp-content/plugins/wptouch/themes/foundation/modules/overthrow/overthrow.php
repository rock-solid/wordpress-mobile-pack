<?php

/* Be sure to add the following to your theme when using this module:

Put a class of 'overthrow' on any elements in which you'd like to apply overflow: auto or scroll CSS.
e.g.:

<div id="foo" class="overthrow">Content goes here!</div>

Overthrow CSS:
Enable overflow: auto on elements with the overthrow class when html element has overthrow class as well: 
.overthrow-enabled .overthrow {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}

More information and full documentation can be found here:
https://github.com/filamentgroup/Overthrow
*/

add_action( 'foundation_module_init_mobile', 'foundation_overthrow_init' );

function foundation_overthrow_init() {
	wp_enqueue_script( 
		'foundation_overthrow', 
		foundation_get_base_module_url() . '/overthrow/overthrow.min.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true
	);
}