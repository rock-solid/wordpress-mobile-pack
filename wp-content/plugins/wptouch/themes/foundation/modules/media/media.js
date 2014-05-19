/* WPtouch Foundation Media Handling Code */

function handleVids() {
	// Add dynamic automatic video resizing via fitVids (if enabled)
	if ( jQuery.isFunction( jQuery.fn.fitVids ) ) {	
		jQuery( '#content' ).fitVids();
	}
	
	// Add dynamic automatic video resizing via CoyierVids (if enabled)
	if ( typeof window.coyierVids == 'function' ) {
		coyierVids();
	}
	
	// If we have html5 videos, add controls for them if they're not specified, CSS will style them appropriately
	if ( jQuery( 'video' ).length ) {
		jQuery( 'video' ).attr( 'controls', 'controls' );
	}
}

// Fixes all HTML5 videos from trigging when menus are overtop
function listenForMenuOpenHideVideos(){
	jQuery( '.show-hide-toggle' ).on( 'click', function(){
		setTimeout( function(){
			var menuDisplay = jQuery( '#menu, #alt-menu' ).css( 'display' );
			if ( menuDisplay == 'block' ) {
				jQuery( '.css-videos video, .css-videos embed, .css-videos object, .css-videos .mejs-container' ).css( 'visibility', 'hidden' );
			} else {
				jQuery( '.css-videos video, .css-videos embed, .css-videos object, .css-videos .mejs-container' ).css( 'visibility', 'visible' );			
			}
		}, 500 );

	});
}

jQuery( document ).ready( function() { 
	handleVids(); 
	listenForMenuOpenHideVideos();
});