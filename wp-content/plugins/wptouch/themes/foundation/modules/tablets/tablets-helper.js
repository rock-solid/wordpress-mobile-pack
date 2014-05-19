
function doFoundationRejectedBrowsers() {
	if ( ( /iPad/i.test( navigator.userAgent ) ) && ( /Twitter for iPhone/i.test( navigator.userAgent ) ) ) {
		var thisURL = document.URL;
		window.location = thisURL + '?wptouch_switch=desktop&redirect=' + thisURL;
	}
}

jQuery( document ).ready( function() { doFoundationRejectedBrowsers(); } );