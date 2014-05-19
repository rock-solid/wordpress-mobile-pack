
function wptouchFdnSetupMenu( menuContainer ) {

	$menuContainer = jQuery( menuContainer );

	$menuContainer.find( 'li.menu-item ul' ).each( function() {
		if ( !jQuery( this ).children().length > 0 ) {
			jQuery( this ).remove();
		}
	});

	$menuContainer.find( 'li.menu-item' ).has( 'ul' ).addClass( 'has_children' ).prepend( '<span></span>' );

	jQuery( 'ul li.has_children span', menuContainer ).on( 'click', function( e ) {
		jQuery( this ).toggleClass( 'toggle' ).parent().toggleClass( 'open-tree' );
		jQuery( this ).parent().find( 'ul' ).first().webkitSlideToggle();
		e.preventDefault();
		e.stopPropagation();
	});

	//If parent links are turned off
	var noParentLinks = jQuery( 'ul.no-parent-links' );
	if ( jQuery( noParentLinks ).length ) {

		$menuContainer.each( function(){
			jQuery( noParentLinks, this ).off().on( 'click', 'li.has_children > a', function( e ){
				jQuery( this ).parent().find( 'span' ).trigger( 'click' );
				e.preventDefault();
				e.stopPropagation();
			});
		});
	}
}

// Setup show/hide menus
function wptouchFdnSetupAllMenus() {
	jQuery( '.show-hide-menu' ).each( function() {
		var menuId = jQuery( this ).prop( 'id' );
		if ( menuId ) {
			wptouchFdnSetupMenu( '#' + menuId );
		}
	});
}

function wptouchDoFdnMenuReady() {
	wptouchFdnSetupAllMenus();
}

jQuery( document ).ready( function() { wptouchDoFdnMenuReady(); } );