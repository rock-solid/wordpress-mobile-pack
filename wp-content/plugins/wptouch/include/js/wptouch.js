/* WPtouch Basic Client-side Ajax Routines */
function WPtouchAjax( actionName, actionParams, callback ) {	
	var ajaxData = {
		action: "wptouch_client_ajax",
		wptouch_action: actionName,
		wptouch_nonce: WPtouch.security_nonce
	};
	
	for ( name in actionParams ) { ajaxData[name] = actionParams[name]; }

	jQuery.post( WPtouch.ajaxurl, ajaxData, function( result ) {
		callback( result );	
	});	
}