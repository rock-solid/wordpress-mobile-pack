function doBauhausThumbnailEvaluate( selectedOption ) {
	var custField = jQuery( '#setting-bauhaus_thumbnail_custom_field' );
	
	if ( selectedOption == 'featured' ) {
		custField.hide();
	} else {
		custField.slideToggle();
	}
}

function doBauhausThumbnailRadio() {
	var thumbSelect = jQuery( '#setting-bauhaus_thumbnail_type input' );

	thumbSelect.change( function(){
		var selectedOption = jQuery( 'input[name=wptouch__bauhaus__bauhaus_thumbnail_type]:checked' ).val();
		doBauhausThumbnailEvaluate( selectedOption );
	});

	doBauhausThumbnailEvaluate( jQuery( 'input[name=wptouch__bauhaus__bauhaus_thumbnail_type]:checked' ).val() );
}

function doBauhausAdminReady() {

	doBauhausThumbnailRadio();
}

jQuery( document ).ready( function() { doBauhausAdminReady(); } );