/* WPtouch Foundation Meny Code */

// WPtouch-specific meny code will go here
function doMenyMenus() {
	var meny = Meny.create({
	    // The element that will be animated in from off screen
	    menuElement: document.querySelector( '#menu' ),
		
	    // The contents that gets pushed down while Meny is active
	    contentsElement: document.querySelector( '.page-wrapper' ),
	
		threshold: 0,

		overlap: 0,

	    // The alignment of the menu (top/right/bottom/left)
	    position: 'top',
	
	    // The height of the menu (when using top/bottom position)
	    height: 320,
	
	    // The width of the menu (when using left/right position)
	    width: 260
	});
	
	jQuery( 'a.meny-toggle' ).unbind().on( 'click', function(){	
		
		if ( meny.isOpen() ) {
			meny.close();
		} else {
			meny.open();
		}
//		alert( 'clicked' );
	});

var contentDiv = document.querySelector( '.page-wrapper' );	

	meny.addEventListener( 'open', function() {
		contentDiv.addEventListener( 'touchmove', classicTouchMove, false );
		// do something on open
//		jQuery( 'html' ).addClass( 'meny-open' );
	});

	meny.addEventListener( 'close', function() {
		contentDiv.removeEventListener( 'touchmove', classicTouchMove, false );
	  // do something on close
	//	jQuery( 'html' ).removeClass( 'meny-open' );
	});	
	
	function classicTouchMove( e ){
		e.preventDefault();
	}
}
	
jQuery( document ).ready( function() { doMenyMenus(); } );