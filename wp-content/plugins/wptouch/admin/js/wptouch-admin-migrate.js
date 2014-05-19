function doCloudMigrate() {
	var brokenThemeDiv = jQuery( '#repair-cloud-theme' );
	if ( brokenThemeDiv.length ) {
		var ajaxParams = {};

		wptouchAdminAjax( 'repair-active-theme', ajaxParams, function( result ) {
			var thisResult = result;

			setTimeout( function() {
					jQuery( '#repair-cloud-theme' ).hide();
					if ( result == "0" ) {
						jQuery( '#repair-cloud-failure' ).fadeIn( 1000 );
					} else {
						jQuery( '#repair-cloud-success' ).fadeIn( 1000 );
					}
				},
				1000 
			);

		});
	}
}

jQuery( document ).ready( function() { doCloudMigrate(); } );