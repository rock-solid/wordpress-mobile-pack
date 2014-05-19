/* WPtouch Foundation Google Fonts Code */

var googleBodyEls = ( 'form *' );
var googleHeadingEls = ( 'h1, h2, h3, h4, h5, h6' );

function addGoogleFontClasses() {
	jQuery( googleBodyEls ).addClass( 'body-font' );
	jQuery( googleHeadingEls ).addClass( 'heading-font' );
}

jQuery( document ).ready( function() { addGoogleFontClasses(); });
jQuery( document ).ajaxComplete( function() { addGoogleFontClasses(); });