
function doFoundationFeaturedLoaded() {
	foundationCreateDots();

	var slideNumber = 0;
	var isContinuous = false;

	var slideOption = '0';
	if ( jQuery( '#slider' ).hasClass( 'slide' ) ) {
		slideOption = '4000';
		if ( jQuery( '#slider' ).hasClass( 'slow') ) {
			slideOption = '6000';
		} else if ( jQuery( '#slider').hasClass( 'fast') ) {
			slideOption = '2500';
		}
	}

	if ( jQuery( '#slider' ).hasClass( 'continuous' ) ) {
		isContinuous = true;
	}

	var bullets = jQuery( '.dots' ).find( 'li' );

	var sliderOptions = {
		startSlide: slideNumber,
		continuous: isContinuous,
		callback: function( pos ) {
			var i = bullets.length;
			while (i--) {
				bullets[i].className = ' ';
			}
			bullets[pos].className = 'active';
		}
	}

	// only include this parameter if it's non-zero
	if ( slideOption > 0  && !jQuery( 'body' ).hasClass( 'rtl' ) ) {
		sliderOptions.auto = slideOption;
	}

	jQuery( '.one-swipe-image' ).css( 'visibility', 'visible' );

	var featuredSlider = new Swipe( document.getElementById( 'slider' ), sliderOptions );
}

function foundationCreateDots() {

	var sliderEl = jQuery( '#slider' );
	var images = sliderEl.find( 'a' );
	var slideNumber = 0;

	// Create dots
	var dots = '<ul class="dots">';

	for ( i = 0; i < images.length; i++ ) {
		dots = dots + '<li data-pos="'+i+'">&nbsp;</li>';
	}

	dots = dots + '</ul>';

	sliderEl.before( dots );

	jQuery( '.dots' ).find( 'li[data-pos="'+slideNumber+'"]' ).addClass( 'active' );
}

jQuery( document ).ready( function() {
	doFoundationFeaturedLoaded();
});