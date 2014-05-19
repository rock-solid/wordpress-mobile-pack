function doFoundationLogin(){
	
	var loginDiv = jQuery( '.wptouch-login-wrap' );

	if ( loginDiv.length ) {
		jQuery( '#user_login', loginDiv ).attr( 'placeholder', wptouchFdnLogin.username_text );	
		jQuery( '#user_pass', loginDiv ).attr( 'placeholder', wptouchFdnLogin.password_text );
	
		// Bind to login toggles
		jQuery( '.login-toggle, .login-req' ).each( function() {	
			jQuery( this ).on( 'click', function(){
				loginDiv.viewportCenter().webkitSlideToggle();
				return false;
			});
		});
		
		// The close button
		jQuery( '.login-close' ).each( function() {	
			jQuery( this ).on( 'click', function(){
				loginDiv.webkitSlideToggle();
				return false;
			});
		});
	}
}

jQuery( document ).ready( function() { doFoundationLogin(); } );