/* WPtouch Pro Foundation Base JS */
/* Description: This file holds all the default jQuery & Ajax functions for the Foundation parent theme on mobile & tablets, used by all child themes */

// Try to get out of frames!
function wptouchFdnEscFrames() {
	if ( window.top != window.self ) { 
		window.top.location = self.location.href
	}	
}

function wptouchFdnIfFixed() {
	if ( wptouchFdnHasFixedPos() ) {
		jQuery( 'body' ).addClass( 'has-fixed' );		
	}
}

function wptouchFdnBindBackButtons( buttonElement ) {
	if ( typeof( buttonElement ) === 'undefined' ) buttonElement = '.back-button';
	jQuery( buttonElement ).on( 'click', function( e ) {
		history.back();
		e.preventDefault();
	});
}

function wptouchFdnBindFwdButtons( buttonElement ) {
	if ( typeof( buttonElement ) === 'undefined' ) buttonElement = '.fwd-button';
	jQuery( buttonElement ).on( 'click', function( e ) {
		history.forward();
		e.preventDefault();
	});
}

// Try to make smaller elements nicer in posts
function wptouchFdnCenterImages( elements, imgWidth ) {
	jQuery( elements ).each( function() {
		if ( !jQuery( this ).hasClass( 'aligncenter' ) && jQuery( this ).width() > imgWidth ) {
			jQuery( this ).not('.post-thumbnail').addClass( 'aligncenter' );
		}
	});
}

function wptouchFdnSetupSlideToggles() {
	jQuery( '.slide-toggle' ).each( function() {	
		var targetId = jQuery( this ).attr( 'data-effect-target' );
		wptouchFdnSlideToggle( this, '#' + targetId, 500 );
	});
}

/* Detect screen sizes and other device details and add or remove corresponding 'smartphone' or 'tablet' classes
	Using document.body.clientWidth because it's faster (and it's what jQuery uses under the hood, anyways) */
function wptouchFdnUpdateDevice() {
	var bodyEl = jQuery( 'body' );
	// Update the body class if the device width is equal to or bigger than 768px 
	if ( document.body.clientWidth < 768 && !bodyEl.hasClass( 'smartphone' ) ) {
		bodyEl.addClass( 'smartphone' ).removeClass( 'tablet' );
		// Create a cookie for the device class so we can reference it via PHP afterwards if needed
		wptouchCreateCookie( 'wptouch-device-type', 'smartphone', 365 );
	} else if ( document.body.clientWidth >= 768 && !bodyEl.hasClass( 'tablet' ) ) {
		bodyEl.addClass( 'tablet' ).removeClass( 'smartphone' );
		// Create a cookie for the device class so we can reference it via PHP afterwards if needed
		wptouchCreateCookie( 'wptouch-device-type', 'tablet', 365 );
	}
}

function wptouchFdnUpdateOrientation() {
	var bodyEl = jQuery( 'body' );
// Update scroll position slightly to fix fixed elements
//	var scrollPosition = bodyEl.scrollTop() + 1;
//	window.scrollTo( 0, scrollPosition, 100 );

	// If it's a smartphone & the clientWidth is less than 480, assume it's portrait, else landscape (works for iPhone 5, as well )
	if ( bodyEl.hasClass( 'smartphone' ) ) { 
		if ( document.body.clientWidth < 480 ) {
			bodyEl.addClass( 'portrait' ).removeClass( 'landscape' );
			wptouchCreateCookie( 'wptouch-device-orientation', 'portrait', 365 );
	} else {
			bodyEl.addClass( 'landscape' ).removeClass( 'portrait' );
			wptouchCreateCookie( 'wptouch-device-orientation', 'landscape', 365 );
		}
	} 

	// If it's a tablet & the clientWidth is less than 1024, assume it's portrait, else landscape
	if ( bodyEl.hasClass( 'tablet' ) ) {
		if ( document.body.clientWidth < 1024 ) {
			bodyEl.addClass( 'portrait' ).removeClass( 'landscape' );
			wptouchCreateCookie( 'wptouch-device-orientation', 'portrait', 365 );
		} else {
			bodyEl.addClass( 'landscape' ).removeClass( 'portrait' );
			wptouchCreateCookie( 'wptouch-device-orientation', 'landscape', 365 );
		}
	}
}

function wptouchFdnDoDeviceAndOrientationListener() {
	jQuery( window ).resize( function() {
		wptouchFdnUpdateDevice();
		wptouchFdnUpdateOrientation();
	}).resize();
}

// Back to top links in themes
function wptouchFdnSetupBackToTopLinks() {
	jQuery( '.back-to-top' ).each( function() {
		jQuery( this ).on( 'click', function( e ){
		    jQuery( 'body' ).animate( { scrollTop: jQuery( 'html' ).offset().top }, 550 );		
			e.preventDefault();
		});
	});
}

function wptouchFdnSetupShowHideToggles() {
	jQuery( '.show-hide-toggle' ).each( function() {
		var targetId = jQuery( this ).attr( 'data-effect-target' );
		var closeId = jQuery( this ).attr( 'data-effect-close' );
		var linkId = jQuery( this ).prop( 'id' );

		jQuery( this ).on( 'click', function( e ) {
			if ( typeof( closeId ) !== 'undefined' ) {
				var originalId = jQuery( '#' + closeId ).attr( 'data-source-click' );
				if ( typeof( originalId ) !== 'undefined' ) {
					jQuery( '#' + originalId ).removeClass( 'toggle-open' );
					jQuery( '#' + closeId ).data( 'data-source-click', '' );
				}
				
				jQuery( '#' + closeId ).hide();
			}

			jQuery( this ).toggleClass( 'toggle-open' );
			jQuery( '#' + targetId ).attr( 'data-source-click', linkId ).webkitSlideToggle();	
		
			e.preventDefault();
		});
	});
}

function wptouchFdnCheckHideAddressBar() {
	if ( jQuery( 'body' ).hasClass( 'hide-address-bar' ) ) {
		window.scrollTo( 0,1 );
	}
}

function wptouchFdnPreviewReload() {
	jQuery( '#preview-bar' ).find( '.refresher' ).on( 'click', function() {
		setTimeout( window.location.reload.bind( window.location ), 0 );
	});
}

function wptouchFdnSwitchToggle() {
	jQuery( '#switch' ).on( 'click', '.off', function() { 
		jQuery( '.on' ).removeClass( 'active' );
		jQuery( this ).addClass( 'active' );
	});
}

function wptouchFdnHandleShortcode() {
	if ( !navigator.standalone ) {
		jQuery( '.wptouch-shortcode-mobile-only' ).show();
	}
}

function wptouchFdnSetupjQuery() {

	// jQuery function opacityToggle()
	jQuery.fn.opacityToggle = function( speed, easing, callback ) { 
		return this.animate( { opacity: 'toggle' }, speed, easing, callback ); 
   	}
  	
  	// jQuery function webkitSlideToggle()
	jQuery.fn.webkitSlideToggle = function() {     
		if ( !this.hasClass( 'slide-in' ) ) {
			this.removeClass( 'slide-out' ).addClass( 'slide-in' ).show().off( 'animationend webkitAnimationEnd' );
		} else {
			this.removeClass( 'slide-in' ).addClass( 'slide-out' );
			this.on( 'animationend webkitAnimationEnd', function(){ jQuery( this ).hide(); } );
		}
  	}

	// jQuery function viewportCenter()
	jQuery.fn.viewportCenter = function() {
	    this.css( 'position', 'absolute' );
	    this.css( 'top', ( ( jQuery( window ).height() - this.outerHeight() ) / 3 ) + jQuery( window ).scrollTop() + 'px' );
	    this.css( 'left', ( ( jQuery( window ).width() - this.outerWidth() ) / 2 ) + jQuery( window ).scrollLeft() + 'px' );
	    return this;
	}

	// Set the form element tabindex automagically
	jQuery( function() {
		var tabindex = 1;
		jQuery( 'input, select, textarea' ).each( function() {
			if ( this.type != "hidden" ) {
				var inputToTab = jQuery( this );
				inputToTab.attr( 'tabindex', tabindex );
				tabindex++;
			}
		});
	});
}

function wptouchFdnSetupWPML() {
	var wpmlLanguageSwitch = jQuery( '#wpml-language-chooser select' );
	if ( wpmlLanguageSwitch.length ) {
		wpmlLanguageSwitch.change( function() { 
			var switchLink = wpmlLanguageSwitch.val();
			document.location.href = switchLink;
		});
	}
}

function wptouchFdnBaseReady() {
	wptouchFdnEscFrames();
	wptouchFdnIfFixed();
	wptouchFdnBindBackButtons();
	wptouchFdnBindFwdButtons();
	wptouchFdnCenterImages( '.post img, .wp-caption', 105 );
	wptouchFdnSetupSlideToggles();
	wptouchFdnDoDeviceAndOrientationListener();
	wptouchFdnSetupBackToTopLinks();
	wptouchFdnSetupShowHideToggles();
	wptouchFdnCheckHideAddressBar();
	wptouchFdnPreviewReload();
	wptouchFdnSwitchToggle();
	wptouchFdnHandleShortcode();
	wptouchFdnSetupjQuery();
	wptouchFdnSetupWPML();
}

jQuery( document ).ready( function() { wptouchFdnBaseReady(); } );