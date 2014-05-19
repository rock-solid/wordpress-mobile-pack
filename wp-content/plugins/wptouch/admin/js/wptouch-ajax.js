// Let's see if we're in a *happy* browser : )
function wptouchIsWebKit() {
	// Only recent versions of WebKit browsers have this
	return ( '-webkit-filter' in document.body.style );
}

function wptouchSetupAjax() {
	jQuery.ajaxSetup ({
	    cache: false
	});
}

var wptouchTotalAjaxEnabled = 0;

function wptouchAdminAjaxEnableSpinner( enable ) {
	if ( enable ) {
		jQuery( '#admin-spinner' ).show();
		if ( wptouchIsWebKit() ) {
			console.log( 'Saving item…' );
		}
		wptouchTotalAjaxEnabled = wptouchTotalAjaxEnabled + 1;
	} else {
		wptouchTotalAjaxEnabled = wptouchTotalAjaxEnabled - 1;
	}

	if ( wptouchTotalAjaxEnabled == 0 ) {
		jQuery( '#admin-spinner' ).fadeOut( 500 );
		if ( wptouchIsWebKit() ) {
			console.log( 'Save complete.' );
		}
	}
}

function wptouchAdminAjax( actionName, actionParams, callback ) {	
	var ajaxData = {
		action: 'wptouch_ajax',
		wptouch_action: actionName,
		wptouch_ajax_nonce: WPtouchAjax.admin_ajax_nonce
	};
	
	for ( name in actionParams ) { ajaxData[name] = actionParams[name]; }

	wptouchAdminAjaxEnableSpinner( true );

	jQuery.post( ajaxurl, ajaxData, function( result ) {
		wptouchAdminAjaxEnableSpinner( false );

		callback( result );	
	});	
}

jQuery( document ).ready( function() { wptouchSetupAjax(); } );