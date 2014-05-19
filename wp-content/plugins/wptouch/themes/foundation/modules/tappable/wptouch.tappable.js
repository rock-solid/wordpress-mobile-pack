jQuery( window ).load( function() { 
	bindTappableEls( '.tappable, .show-hide-toggle, .slide-toggle' ); 
});

jQuery( document ).ajaxComplete( function() {
	bindTappableEls( '.tappable, .show-hide-toggle, .slide-toggle' );
});

function bindTappableEls( elements ){
	jQuery( elements ).each( function(){
		jQuery( this ).tappable({ touchDelay: 90 });
	});
}