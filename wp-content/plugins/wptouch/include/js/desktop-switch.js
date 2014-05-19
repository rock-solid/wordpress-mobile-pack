function wptouchGetXMLHttpRequestObject() {
    if ( window.XMLHttpRequest ) {
        return new XMLHttpRequest();
    } else if ( window.ActiveXObject ) { // Older IE.
		try {
        	return new ActiveXObject( "MSXML2.XMLHTTP.3.0" );
        }
		catch( ex ) {
			return null;
		}
    }
}

var wptouchAjaxRequest = wptouchGetXMLHttpRequestObject();

function wptouchAsyncHandler() {
    if ( wptouchAjaxRequest.readyState == 4 ) {
        if ( wptouchAjaxRequest.status == 200 ) {
			var domElement = document.getElementById( 'wptouch_desktop_switch' );
			if ( domElement != null ) {
				domElement.innerHTML = wptouchAjaxRequest.responseText;
			}        	
        }
    }
}

if ( wptouchAjaxRequest != null ) {
	wptouchAjaxRequest.open( 'POST', wptouchAjaxUrl, true );
	wptouchAjaxRequest.onreadystatechange = wptouchAsyncHandler;
	wptouchAjaxRequest.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
	wptouchAjaxRequest.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
	wptouchAjaxRequest.send( 'action=wptouch_client_ajax&wptouch_action=desktop_switch&wptouch_nonce=' + encodeURIComponent( wptouchAjaxNonce ) + '&wptouch_switch_location=' + encodeURIComponent( wptouchAjaxSwitchLocation ) );	
}

