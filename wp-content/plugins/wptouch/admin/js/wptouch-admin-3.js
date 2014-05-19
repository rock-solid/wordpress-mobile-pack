function wptouchSetupAdminMenu(){
	// Admin menu click handler
	var adminMenuArea = jQuery( '#wptouch-admin-menu' );
	if ( adminMenuArea.length ) {
		jQuery( adminMenuArea ).on( 'click', 'a', function( e ) {
			var targetSlug = jQuery( this ).attr( 'data-page-slug' );

			jQuery( '.wptouch-settings-sub-page:not(#' + targetSlug + ')' ).hide();
			jQuery( '#' + targetSlug ).show();

			jQuery( '#wptouch-admin-menu' ).find( 'a' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );

			jQuery.cookie( 'wptouch-admin-menu', targetSlug );

			wptouchRefreshScrollers();
			e.preventDefault();
		});

		// Check to see if the menu cookie has been set previously
		var previousCookie = jQuery.cookie( 'wptouch-admin-menu' );
		var menuHandled = 0;
		if ( previousCookie ) {
			if ( jQuery( '#wptouch-admin-menu a.' + previousCookie ).length ) {
				menuHandled = 1;
			}
		}
		// If not then click the first element
		if ( !menuHandled ) {
			jQuery( '#wptouch-admin-menu' ).find( 'a:first' ).click();
		}
	}
}

function wptouchTooltipSetup() {
	jQuery( 'i.wptouch-tooltip' ).tooltip( { placement:'right' } );
}

function wptouchHandleLicensePanel() {
	// Check to make sure the panel exists

	// Cache objects we'll be using for this beast
	var activate = jQuery( '#activate-license' );
	var success = jQuery( '#success-license' );
	var progress = jQuery( '#progress-license' );
	var rejected = jQuery( '#rejected-license' );
	var progressBar = jQuery( '#progress-license' ).find( '.bar' );

	if ( jQuery( '#license-settings-area' ).length ) {

		if ( jQuery( '#wptouch-settings-area.licensed' ).length ) {
			jQuery( '#license_key' ).attr( 'type', 'password' );
		}

		// See if we have a license already
		if ( bncHasLicense == 0 ) {
			jQuery( activate ).show();

			jQuery( activate ).on( 'click', 'a', function( e ) {
				jQuery( progress ).fadeIn();
				wptouchProgressBarStart( progressBar );
				jQuery( rejected ).fadeOut();
				e.preventDefault();

				// Need to contact the server here
				var licenseEmail = jQuery( '#license_email' ).val();
				var licenseKey = jQuery( '#license_key' ).val();

				// Need to generate an AJAX request now for activation
				var ajaxParams = {
					email: licenseEmail,
					key: licenseKey
				};

				jQuery( activate ).hide();

				wptouchAdminAjax( 'activate-license-key', ajaxParams, function( result ) {
					if ( result == '1' ) {
						// license success
						wptouchProgressBarSuccess( progressBar );
						setTimeout( function(){
							jQuery( success ).fadeIn();
						}, 1000 );
					} else if ( result == '2' ) {
						// rejected license
						wptouchProgressBarError( progressBar );
						setTimeout( function(){
							jQuery( progress ).fadeOut( 250 );
						}, 5000 );
						setTimeout( function(){
							jQuery( activate ).fadeIn();
							wptouchProgressBarReset( progressBar );
						}, 5250 );
						jQuery( rejected ).fadeIn().delay( 4500 ).fadeOut();
					} else if ( result == '3' ) {
						// too many licenses
						wptouchProgressBarError( progressBar );
						setTimeout( function(){
							jQuery( progress ).fadeOut( 250 );
						}, 2500 );
						setTimeout( function(){
							wptouchProgressBarReset( progressBar );
						}, 5250 );
						jQuery( '#too-many-license' ).fadeIn();
					} else if ( result == '4' ) {
						// server issue license
						wptouchProgressBarError( progressBar );
						setTimeout( function(){
							jQuery( progress ).fadeOut( 250 );
						}, 5000 );
						setTimeout( function(){
							jQuery( activate ).show();
							wptouchProgressBarReset( progressBar );
						}, 5250 );
						jQuery( '#server-issue-license' ).fadeIn().delay( 4500 ).fadeOut();
					}
				});
			});
		} else {
			jQuery( activate ).hide();
			jQuery( success ).show()
		}
	}
}
// Functions for dealing with the animation of the progress bars for licensing

function wptouchProgressBarStart( barElement ){
	jQuery( barElement ).animate({
		width: '50%'
	}, 500, function(){
		// end animation function?
		});
}

function wptouchProgressBarError( barElement ){
	jQuery( barElement ).parent().removeClass( 'progress-striped' );
	jQuery( barElement ).addClass( 'bar-danger' );
}

function wptouchProgressBarSuccess( barElement ){
	jQuery( barElement ).animate({
		width: '100%'
	}, 500, function(){
		jQuery( barElement ).parent().removeClass( 'progress-striped' );
		jQuery( barElement ).addClass( 'bar-success' );
	});
}

function wptouchProgressBarReset( barElement ){
	jQuery( barElement ).css( 'width', '0%' ).removeClass( 'bar-danger bar-success' );
}

//  function wptouchHandleCarousels(){
//  	jQuery( '.carousel' ).each( function( i ) {
//
//  		var items = jQuery( this ).find( '.carousel-inner .item' );
//  		var toAdd = '';
//  		for ( i = 0; i < items.length; i++ ) {
//  			if ( i == 0 ) {
//  				toAdd = toAdd + '<li class="active" data-num="' + i + '">&nbsp;</li>';
//  			} else {
//  				toAdd = toAdd + '<li data-num="' + i + '">&nbsp;</li>';
//  			}
//  		}
//
//  		jQuery( this ).find( 'ul.dots' ).append( toAdd );
//
//  		jQuery( this ).on( 'slid', function() {
//  			var which = 1;
//  			var found = 0;
//  			jQuery( this ).find( '.carousel-inner div.item' ).each( function() {
//  				if ( !jQuery( this ).hasClass( 'active' ) && !found ) {
//  					which = which + 1;
//  				} else found = 1;
//  			});
//
//  			jQuery( this ).find( 'ul.dots li' ).removeClass( 'active' );
//  			jQuery( this ).find( 'ul.dots li:nth-child(' + which + ')' ).addClass( 'active' );
//  		});
//  	});
//  }

function wptouchSetupHomescreenUploaders() {
	if ( jQuery( '.uploader' ).length ) {

		// Remove placeholder background if we have an image
		jQuery( '.image-placeholder img' ).parent().css( 'background', 'none' );

		jQuery( '.uploader' ).each( function() {
			var thisUploader = jQuery( this );
			var baseId = jQuery( this ).find( 'button.upload' ).parent().attr( 'id' );
			var settingName = jQuery( '#' + baseId + '_upload' ).attr( 'data-esn' );
			var deleteButton = jQuery( '#' + baseId ).find( 'button.delete' );
			var uploader = new AjaxUpload( baseId + '_upload', {
		    	action: ajaxurl,
		    	allowedExtensions: [ 'png' ],
				debug: true,
				data: {
					action: 'upload_file',
					file_type: 'homescreen_image',
					setting_name: settingName,
					wp_nonce: WPtouchCustom.admin_nonce
				},
				name: 'myfile',
				onSubmit: function( fileName, extension ) {
					jQuery( '.' + baseId + '_wrap' ).find( '.progress' ).show().addClass( 'active progress-striped' );
					jQuery( '.' + baseId + '_wrap' ).find( '.progress .bar' ).css( 'width', '0%' );
					//thisUploader.find( '.spinner' ).show();
				},
				onComplete: function( fileName, response ) {
					jQuery( '.' + baseId + '_wrap' ).find( '.progress .bar' ).css( 'width', '100%' );
					jQuery( '.' + baseId + '_wrap' ).find( '.progress' ).removeClass( 'progress-info active progress-striped' ).addClass( 'progress-success' );
					// Remove placeholder background if we have an image
					thisUploader.find( '.image-placeholder' ).css( 'background', 'none' );
					setTimeout( function() {
						jQuery( '.' + baseId + '_wrap' ).find( '.progress' ).tooltip( 'show' );
						thisUploader.find( '.image-placeholder' ).html( '<img src="' + response + '" />');
					},
					1250 );
					setTimeout( function() {
						jQuery( '.' + baseId + '_wrap' ).find( '.progress' ).tooltip( 'hide' );
						jQuery( '.' + baseId + '_wrap' ).find( '.progress' ).hide();
						deleteButton.show();
					},
					3500 );
				},
				onCancel: function( id, fileName ) {},
				showMessage: function( message ) {
				}
			});

			jQuery( '#' + baseId + '_upload' ).on( 'click', function( e ) {
				jQuery( '#' + baseId + '_spot' ).trigger( 'click' );
				e.preventDefault();
			});

			deleteButton.on( 'click', function( e ) {
				var deleteButton = jQuery( this );
				var placeHolder = jQuery( this ).parent().find( '.image-placeholder' );
				placeHolder.html( '' );

				var baseId = jQuery( this ).parent().attr( 'id' );
				var settingName = jQuery( '#' + baseId + '_upload' ).attr( 'data-esn' );

				var ajaxParams = {
					setting_name: settingName
				};

				wptouchAdminAjax( 'delete-image-upload', ajaxParams, function( result ) {
					if ( result == 0 ) {
						// Remove placeholder background if we have an image
						placeHolder.css(
							'background', 'url(' + WPtouchCustom.plugin_admin_image_url + '/upload-placeholder.png) no-repeat -8px -22px'
						);

						deleteButton.fadeOut();
					}
				});

				e.preventDefault();
			});
		});
	}
}

function wptouchEvaluateDefaultIconsInMenu() {
	// Set up default icons
	var defaultIcon = jQuery( '#default-area img' ).attr( 'src' );
	if ( defaultIcon ) {
		jQuery( '#menu-area' ).find( 'li img' ).each( function() {
			var iconSrc = jQuery( this ).attr( 'src' );
			if ( iconSrc == defaultIcon ) {
				jQuery( this ).addClass( 'def-icon' )
			} else {
				jQuery( this ).removeClass( 'def-icon' )
			}
		});
	}
}

function wptouchHandleMenuArea(){
	jQuery( '#left-area' ).find( '.nano' ).nanoScroller({
		flash: true,
		flashDelay: 3000,
		preventPageScrolling: true
	});

	jQuery( '#right-area' ).find( '.nano' ).nanoScroller({
		flash: true,
		flashDelay: 3000,
		preventPageScrolling: true
	});

	jQuery( '#pack-list' ).on( 'change', function( e ) {
		jQuery( '#pack-set-menu-area' ).find( '.pack' ).hide();
		jQuery( '#pack-' + jQuery( this ).val() ).fadeIn();
		e.preventDefault();
		wptouchRefreshScrollers();
	}).trigger( 'change' );

	// Change the menu list based on the select box
	jQuery( 'select#menu-list' ).on( 'change', function( e ) {
		var selectedMenu = jQuery( 'select#menu-list'  ).prop( 'value' );
		jQuery( 'div.menu-item-list' ).hide();
		jQuery( 'div[data-menu-name="' + selectedMenu + '"]' ).show();
		e.preventDefault();
		wptouchRefreshScrollers();
	}).trigger( 'change' );

	// Show or hide children in the menu icon list
	jQuery( '#right-area ul.menu-tree > li a.title' ).each( function(){
		jQuery( this ).on( 'click', function( e ) {
			if ( !jQuery( this ).hasClass( 'open' ) ) {
				jQuery( this ).addClass( 'open' ).parent().find( 'ul' ).first().show();
				jQuery( this ).parent().addClass( 'open-tree' );
			} else {
				jQuery( this ).removeClass( 'open' ).parent().find( 'ul' ).first().removeClass( 'open-tree' ).hide();
				jQuery( this ).parent().removeClass( 'open-tree' );
			}
			wptouchRefreshScrollers();
			e.preventDefault();
		});
	});

	// AJAX enable/disable menu item
	jQuery( '.menu-enable' ).find( 'input' ).on( 'change', function( e ) {
		var isEnabled = jQuery( this ).is( ':checked' ) ? '1' : '0';
		var pageId = jQuery( this ).parent().parent().find( '.drop-target' ).attr( 'data-object-id' );

		var ajaxParams = {
			is_checked: isEnabled,
			page_id: pageId
		};

		wptouchAdminAjax( 'enable-menu-item', ajaxParams, function( result ) {
			if ( result == 0 ) {
				// success
			}
		});

		if ( isEnabled == 0 ) {
			jQuery( this ).parent().parent().find( 'ul input:checked' ).click();
			jQuery( this ).parent().parent().find( 'ul input' ).prop( 'disabled', true );
		} else {
			jQuery( this ).parent().parent().find( 'ul input' ).removeProp( 'disabled' ).click();
		}
	})

	// Disable all initially
	var menuEnable = jQuery( '.menu-enable' );

	menuEnable.find( 'input' ).not( ':checked' ).each( function() {
		jQuery( this ).parent().parent().find( 'ul input' ).prop( 'disabled', true );
	});

	// Handle "Check All"

	jQuery( '#menu-set-options' ).on( 'click', 'a.check-all', function( e ) {
		menuEnable.find( 'input' ).each( function() {
			// Check all items that *aren't* checked already
			if ( !jQuery( this ).is( ':checked' ) ) {
				jQuery( this ).click();
			}
		});
		e.preventDefault();

	// Now handle "Check None"
	}).on( 'click', 'a.check-none', function( e ) {
		menuEnable.find( 'input' ).each( function() {
			// Check all items that *are* checked already
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( this ).trigger( 'click' );
			}
		});
		e.preventDefault();

	// AJAX reset all menu items
	}).on( 'click', 'a.reset-all', function( e ) {
		if ( confirm( WPtouchCustom.reset_menus ) ) {
			var ajaxParams = {};

			wptouchAdminAjax( 'reset-page-icons-and-state', ajaxParams, function( result ) {
				if ( result == 0 ) {
					window.location.href = window.location.href;
				}
			})
		}

		e.preventDefault();
	});

	wptouchEvaluateDefaultIconsInMenu();
}

function wptouchHandleIconDragDrop() {
	jQuery( '#pack-set-menu-area' ).find( '.pack ul li img' ).draggable( {
	 	helper: 'clone',
	 	appendTo: 'body',
		revert: true,
		revertDuration: 250,
	});

	jQuery( 'div.drop-target' ).find( 'img' ).droppable( {
		drop: function( event, ui ) {
			// Swap the image out on a successful drop
			var imageSource = ui.draggable.attr('src');
			var pageId = jQuery( this ).parent().attr( 'data-object-id' );

			jQuery( '[data-object-id=' + pageId + ']' ).find( 'img' ).attr( 'src', imageSource ).removeClass( 'def-icon' );

			var ajaxParams = {
				image_file: imageSource,
				page_id: pageId
			};

			wptouchAdminAjax( 'update-page-icon', ajaxParams, function( result ) {
				if ( result == 0 ) {
					// success
				}
			});

		},
		hoverClass: 'on-hover'
	});

	// Default menu Icon droppable area
	jQuery( '#default-area' ).find( 'img' ).droppable( {
		drop: function( event, ui ) {

			// Swap the image out on a successful drop
			var imageSource = ui.draggable.attr( 'src' );

			// Update all icons that are set to the default icon
			jQuery( '#default-area img' ).attr( 'src', imageSource );

			// Update the actual default icon area
			jQuery( 'img.def-icon' ).attr( 'src', imageSource );

			var ajaxParams = {
				image_file: imageSource
			};

			wptouchAdminAjax( 'set-default-icon', ajaxParams, function( result ) {
				if ( result == 0 ) {
					// success
				}
			});
		},
		hoverClass: 'on-hover'
	});

	// Makes sure draggable icon works properly even in the scrolling areas
	jQuery( 'div.drop-target' ).find( 'img' ).draggable( {
	 	helper: 'clone',
	 	appendTo: 'body',
	 	revert: 'invalid'
	});

	// Trashing icon droppable area
	jQuery( '#trash-area' ).find( 'img' ).droppable( {
		drop: function( event, ui ) {
			// Swap the image out on a successful drop
			var pageId = jQuery( ui.draggable ).parent().attr( 'data-object-id' );

			var defaultIcon = jQuery( '#default-area img' ).attr( 'src' );
			jQuery( '[data-object-id=' + pageId + ']' ).find( 'img' ).attr( 'src', defaultIcon ).addClass( 'def-icon' );

			var ajaxParams = {
				page_id: pageId
			};

			wptouchAdminAjax( 'reset-page-icon', ajaxParams, function( result ) {
				if ( result == 0 ) {
					// success
				}
			});
		},
		hoverClass: 'on-hover'
	});
}

function WPtouchRefreshCustomIconArea() {
	customIconEl = jQuery( '#section-uploaded-icons' );

	jQuery( customIconEl ).css( 'opacity', '0.5' );
	setTimeout( function() {
		jQuery( customIconEl ).find( 'ul.custom-uploads-display' ).load( document.location.href + ' #section-uploaded-icons ul.custom-uploads-display li', function() {
			jQuery( customIconEl ).css( 'opacity', '1.0' );
		});
	}, 500 );
}

function wptouchHandleCustomIconUpload() {
	if ( jQuery( '.custom-icon-uploader' ).length ) {
	// Can move this function after
		var thisUploader = jQuery( '#custom_icon_uploader' );
		var thisSpinner = jQuery( this ).find( '.spinner' );

		var uploader = new AjaxUpload( '#custom_icon_upload_button', {
	    	action: ajaxurl,
	    	allowedExtensions: [ 'png' ],
			debug: true,
			data: {
				action: 'upload_file',
				file_type: 'custom_image',
				wp_nonce: WPtouchCustom.admin_nonce
			},
			name: 'myfile',
			onSubmit: function( fileName, extension ) {
				thisSpinner.show();
			},
			onComplete: function( fileName, response ) {
				thisSpinner.hide();

				WPtouchRefreshCustomIconArea();
				wptouchAdminUpdateMenuSetupPages();
			},
			onCancel: function( id, fileName ) {
				thisSpinner.hide();
			},
			showMessage: function( message ) {
				// Nothin'
			}
		});
	}

	// Handle delete custom Icon
	if ( jQuery( 'ul.custom-uploads-display' ).length ) {
		jQuery( '#section-uploaded-icons' ).on( 'click', 'ul.custom-uploads-display li', function( e ) {
			var iconName = jQuery( this ).find( 'p.name' ).attr( 'data-name' );
			var ajaxParams = {
				icon_name: iconName
			};

			wptouchAdminAjax( 'delete-custom-icon', ajaxParams, function( result ) {
				if ( result == 0 ) {
					WPtouchRefreshCustomIconArea();
					wptouchAdminUpdateMenuSetupPages();
				}
			});

			e.preventDefault();
		});
	}

	// Handle delete fade-in/fade-out
	jQuery( 'ul.custom-uploads-display' ).on( 'mouseenter', 'li', function() {
		jQuery( this ).find( 'a' ).fadeIn( 'fast' );
	}).on( 'mouseleave', 'li', function() {
		jQuery( this ).find( 'a' ).fadeOut( 'fast' );
	});
}

function WPtouchUpdateNotificationArea( jsonData ) {
	var result = jQuery.parseJSON( jsonData );
	var notificationDiv = jQuery( '#ajax-notifications' );
	var countDiv = jQuery( '.number' );

	// Update HTML
	notificationDiv.html( result.html );
	wptouchRefreshScrollers();

	// Update notification count
	if ( result.count > 0 ) {
		jQuery( countDiv ).html( result.count ).show();
	} else {
		jQuery( countDiv ).html( '0' );
	}
}

function wptouchSetupNotifications() {

	var notificationDiv = jQuery( '#ajax-notifications' );
	if ( notificationDiv.length ) {
		var ajaxParams = {};

		// AJAX load the notification area on the TouchBoard and the dropdown
		wptouchAdminAjax( 'load-notifications', ajaxParams, function( result ) {
			WPtouchUpdateNotificationArea( result );

			jQuery( '#ajax-notifications .nano' ).nanoScroller({
				preventPageScrolling: true
			});

			// Handle notification dismissal
			notificationDiv.on( 'click', '.dismiss', function() {
				var toDismiss = jQuery( this ).attr( 'data-key' );
				var dismissAjaxParams = {
					notification_key: toDismiss
				}

				jQuery( this ).parent().animate( {
					height: 'toggle',
					opacity: 'toggle'
				}, 280 );

				setTimeout( function() {
					wptouchAdminAjax( 'dismiss-notification', dismissAjaxParams, function( result ) {
						WPtouchUpdateNotificationArea( result );
					});
				}, 300 );
			});

			// Notification scroller is hidden in the drop-down, so we have to fire a refresh when it's first shown
			jQuery( '#notification-drop, a.menu-icons-menus' ).on( 'click', function(){
				wptouchRefreshScrollers();
			});
		});
	}
}

function wptouchHandlePluginCompat() {
	var pluginCompatDiv = jQuery( '#plugin-compat-setting .content' );
	if ( pluginCompatDiv.length ) {
		var ajaxParams = {};

		wptouchAdminAjax( 'load-plugin-compat-list', ajaxParams, function( result ) {
			pluginCompatDiv.html( result );
			setTimeout( function() {
				jQuery( '#plugin-compat-setting.nano' ).nanoScroller({
					preventPageScrolling: true
				});
				jQuery( '#plugin-compat-setting' ).find( 'i.wptouch-tooltip' ).tooltip( { placement:'right' } );
			},
			0 );
		});
	}
}

// Function to add Checkbox element toggles
function wptouchCheckToggle( checkBox, toggleElements ) {
	if ( jQuery( checkBox ).prop( 'checked' ) ) {
		jQuery( toggleElements ).show();
	} else {
		jQuery( toggleElements ).hide();
	}
	jQuery( checkBox ).on( 'change', function() {
		if ( jQuery( checkBox ).prop( 'checked' ) ) {
			jQuery( toggleElements ).animate( {
				height: 'toggle',
				opacity: 'toggle'
			}, 280 );

		} else {
			jQuery( toggleElements ).animate( {
				height: 'toggle',
				opacity: 'toggle'
			}, 280 );
		}
	});
}

// Function that holds radio toggle settings
function wptouchSetupRadios() {

	// Foundation: Advertising Options
	jQuery( '#section-service' ).find( 'input' ).on( 'change', function() {

		var presentationDiv = jQuery( '#section-ad-presentation' );
		var googleDiv = jQuery( '#section-google-adsense' );
		var customDiv = jQuery( '#section-custom-ads' );
		var optionsDev = jQuery( '#section-active-pages' );

		switch( jQuery( '#section-service input:checked' ).val() ) {
			case 'none':
				presentationDiv.hide();
				googleDiv.hide();
				customDiv.hide();
				optionsDev.hide();
				break;
			case 'google':
				presentationDiv.show();
				googleDiv.show();
				optionsDev.show();
				customDiv.hide();
				break;
			case 'custom':
				presentationDiv.show();
				optionsDev.show();
				googleDiv.hide();
				customDiv.show();
				break;
		}
	} ).trigger( 'change' );

	// Core Settings: WPtouch Homepage
	jQuery( '#setting-homepage_landing' ).find( 'input' ).on( 'change', function() {

		var redirectTargetDiv = jQuery( '#setting-homepage_redirect_wp_target' );
		var customTargetDiv = jQuery( '#setting-homepage_redirect_custom_target' );

		switch( jQuery( '#setting-homepage_landing input:checked' ).val() ) {
			case 'none':
				redirectTargetDiv.hide();
				customTargetDiv.hide();
				break;
			case 'select':
				redirectTargetDiv.show();
				customTargetDiv.hide();
				break;
			case 'custom':
				customTargetDiv.show();
				redirectTargetDiv.hide();
				break;
		}
	} ).trigger( 'change' );
}

// A little extra gravy to make sure the Overview News is the correct height
function wptouchRefreshOverviewHeight(){
	if ( jQuery( '#touchboard-news' ).length ) {
		if ( jQuery( '.wptouch-free' ).length ) {
			var leftHeight = ( jQuery( '#touchboard-left' ).height() - 216 );
		} else {
			var leftHeight = ( jQuery( '#touchboard-left' ).height() - 192 );
		}
		jQuery( '#touchboard-news' ).height( leftHeight );
	}
}

function wptouchAdminResize(){
	jQuery( window ).resize( function() {
		wptouchRefreshOverviewHeight();
		wptouchRefreshScrollers();
	}).resize();
}

// Call this if you need to have a nano scroller recalculate its height
function wptouchRefreshScrollers(){
	setTimeout( function(){
		jQuery( '.nano' ).nanoScroller();
	}, 0 );
}


function wptouchSetupAdminToggles() {
	// Enable iOS Web-App Mode
	wptouchCheckToggle( '#webapp_mode_enabled', '#setting-webapp_enable_persistence, #section-notice-message, #section-iphone-startup-screen, #section-retina-iphone-startup-screen, #section-iphone-5-startup-screen, #section-ipad-mini-and-ipad-startup-screens, #setting-webapp_ignore_urls, #setting-webapp_external_message, #section-retina-ipad-startup-screens' );
	// Show a notice message for iPhone, iPod touch & iPad visitors about Web-App Mode
	wptouchCheckToggle( '#webapp_show_notice', '#setting-webapp_notice_message, #setting-webapp_notice_expiry_days' );
	// Include functions.php from Desktop theme method
	wptouchCheckToggle( '#include_functions_from_desktop_theme', '#setting-functions_php_loading_method' );
	// Cache menu settings (advanced)
	wptouchCheckToggle( '#show_share', '#setting-share_location, #setting-share_colour_scheme' );
	wptouchCheckToggle( '#automatically_backup_settings', '#setting-backup' );

}

function wptouchHandleDownloadSettings() {
	var downloadSettingsButton = jQuery( 'button#backup' );
	if ( downloadSettingsButton.length ) {
		downloadSettingsButton.click( function( e ) {
			var ajaxParams = {};

			wptouchAdminAjax( 'prep-settings-download', ajaxParams, function( result ) {
				if ( result ) {
					var newUrl = WPtouchCustom.plugin_url + '&action=wptouch-download-settings&backup_file=' + result + '&nonce=' + WPtouchCustom.admin_nonce + '&redirect=' + WPtouchCustom.plugin_url;

					document.location.href = newUrl;
				}
			});

			e.preventDefault();
		});

		var uploader = new AjaxUpload( '#restore', {
	    	action: ajaxurl,
	    	allowedExtensions: [ 'txt' ],
			debug: true,
			data: {
				action: 'upload_file',
				file_type: 'settings_backup',
				wp_nonce: WPtouchCustom.admin_nonce
			},
			name: 'myfile',
			onComplete: function( fileName, response ) {
				// Reload the page
				document.location.href = document.location.href;
			},
			showMessage: function( message ) {}
		});

		// Intercept enter key, which strangely causes the first button in the DOM to be pressed
		// in our case this results in a backup file download
	    jQuery( 'form input' ).keypress( function ( e ) {
	        if ( ( e.which && e.which == 13 ) || ( e.keyCode && e.keyCode == 13 ) ) {
	            jQuery( '.button-primary' ).click();
	            return false;
	        } else {
	            return true;
	        }
	    });
	}
}

var wptouchPreviewWindow;

// The Preview Pop-Up Window
function wptouchPreviewWindow(){

	var previewEl = jQuery( 'input#preview' );

	if ( wptouchIsWebKit() ) {
		previewEl.on( 'click', function( e ) {
			var width = '320', height = '510';
			topPosition = ( screen.height ) ? ( screen.height - height ) / 2:0;
			leftPosition = ( screen.width ) ? ( screen.width - width ) / 2:0;
			options = 'scrollbars=no, titlebar=no, status=no, menubar=no';
			previewUrl = jQuery( this ).attr( 'data-url' );
			window.open( previewUrl, 'preview', 'width=320, height=510,' + options + ', top=' + topPosition + ',left=' + leftPosition + '' );
			wptouchPreviewWindow = window.open( '', 'preview', '' );
			jQuery.cookie( 'wptouch-preview-window', 'open' );
			e.preventDefault();
		});
	} else {
		previewEl.on( 'click', function( e ) {
			e.preventDefault();
		})
		.addClass( 'disabled' )
		.attr( 'rel', 'popover' )
		.attr( 'data-trigger', 'hover' )
		.attr( 'data-title', WPtouchCustom.text_preview_title )
		.attr( 'data-content', WPtouchCustom.text_preview_content )
		.popover( { placement:'right' } );
	}
}

function wptouchHandlePreviewWindow(){
	if ( wptouchPreviewWindow.closed ) {
		jQuery.cookie( 'wptouch-preview-window', null );
	}
}

function rgb2hex( rgb ){
 rgb = rgb.match( /^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/ );
 return "#" +
  ( '0' + parseInt( rgb[1],10 ).toString( 16 ) ).slice( -2 ) +
  ( '0' + parseInt( rgb[2],10 ).toString( 16 ) ).slice( -2 ) +
  ( '0' + parseInt( rgb[3],10 ).toString( 16 ) ).slice( -2 );
}

function wptouchSetupColorPicker() {
	if ( jQuery( '.colorpicker' ).length ) {
		jQuery( '.colorpicker' ).each( function() {
			var targetName = jQuery( this ).attr( 'data-target' );
			jQuery( this ).farbtastic( '#' + targetName );
		});
	}

	jQuery( '.fdn-colors' ).find( '.dropdown' ).each( function() {
		var thisSection = jQuery( this );
		jQuery( this ).find( 'a.tabbed' ).click( function( e ) {
			thisSection.find( 'ul ul' ).hide();

			var targetSection = jQuery( this ).attr( 'rel' );
			thisSection.find( '.' + targetSection ).show();
			thisSection.find( 'a.tabbed' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			e.preventDefault();
		});

		jQuery( this ).find( 'a.tabbed' ).first().click();

		var targetName = thisSection.find( '.colorpicker' ).attr( 'data-target' );
		var myObject = jQuery.farbtastic( '#color-' + targetName );

		jQuery( '.desktop-colors-ul li' ).each( function() {
			var keepColor = jQuery( this ).attr( 'data-background' );
			jQuery( this ).attr( 'style', '' ).css( 'background-color', keepColor );
		});

		jQuery( '.desktop-colors-ul', this ).on( 'click', 'li', function() {
			var thisColor = jQuery( this ).css( 'background-color' );
			var colorToSet = rgb2hex( thisColor );
			thisSection.find( '.dropdown-toggle' ).find( 'span' ).css( 'background-color', colorToSet );

			myObject.setColor( colorToSet );
		});

		thisSection.find( 'a.reset-color' ).click( function( e ) {
			var colorToSet = jQuery( this ).attr( 'data-original-color' );

			myObject.setColor( colorToSet );
			e.preventDefault();
		});
	});

	setInterval(
		function() {
			jQuery( '.selected-color' ).each( function() {
				var selectedColor = jQuery( this ).attr( 'value' );
				var colorShower = jQuery( this ).parent().parent().parent().parent().find( '.dropdown-toggle' ).find( 'span' );
				colorShower.css( 'background-color', selectedColor );
			});
		},
		750
	);
}

function wptouchLoadTouchBoardArea() {
	var touchboard = jQuery( '#touchboard-left' );
	if ( touchboard.length ) {
		if ( !navigator.onLine ) {
			jQuery( '#touchboard-left h3 span' ).find( 'strong:last').replaceWith( '<strong class="orange">' + WPtouchCustom.cloud_offline + '</strong>' );
		} else {
			var ajaxParams = {};
			wptouchAdminAjax( 'load-touchboard-area', ajaxParams, function( result ) {
				touchboard.html( result );
				setTimeout( function(){ wptouchAdminResize(); }, 200 );
			});
		}
	}
}

function wptouchLoadUpgradeArea() {
	var upgrade = jQuery( '#upgrade-area' );
	if ( upgrade.length ) {
		var ajaxParams = {};
		wptouchAdminAjax( 'load-upgrade-area', ajaxParams, function( result ) {
			upgrade.html( result );
			setTimeout( function(){ wptouchAdminResize(); }, 200 );
		});
	}
}

function wptouchAdminUpdateMenuSetupPages() {
	// Need to update Page Setup area
	jQuery( '#menu-icons-menus > .wptouch-section' ).load(
		document.location.href + ' #menu-icons-menus #pack-set-menu-area, #menu-icons-menus #default-trash-area',
		function() {
				wptouchHandleMenuArea();
				wptouchHandleIconDragDrop();
		}
	);
}

function wptouchHandleDownloadIconSets() {
	var iconSetArea = jQuery( '#manage-icon-sets' );
	if ( iconSetArea.length ) {
		var ajaxParams = {};
		wptouchAdminAjax( 'get-icon-set-info', ajaxParams, function( result ) {
			iconSetArea.html( result );

			jQuery( 'ul.manage-sets' ).on( 'click', 'button', function( e ) {
				var pressedButton = jQuery( this );
				var installURL = jQuery( this ).attr( 'data-install-url' );
				var basePath = jQuery( this ).attr( 'data-base-path' );
				var loadingText = jQuery( this ).attr( 'data-loading-text' );

				var ajaxParams = {
					url: installURL,
					base: basePath
				};

				pressedButton.html( loadingText ).addClass( 'disabled' );

				wptouchAdminAjax( 'download-icon-set', ajaxParams, function( result ) {
					if ( result == '1' ) {
						// Succeeded
						pressedButton.parent().find( '.installed' ).show();
						pressedButton.hide();

						wptouchAdminUpdateMenuSetupPages();
					} else {
						// Failed
						pressedButton.parent().find( '.error' ).show();
						pressedButton.hide();
					}
				});

				e.preventDefault();
			});
		});
	}
}

function wptouchSetupNews() {
	var newsDiv = jQuery( '#ajax-news' );
	if ( newsDiv.length ) {
		var ajaxParams = {};

		// AJAX load the notification area on the TouchBoard and the dropdown
		wptouchAdminAjax( 'load-news', ajaxParams, function( result ) {
			//	WPtouchUpdateNotificationArea( result );
			newsDiv.html( result );

			newsDiv.find( '.nano' ).nanoScroller({
				preventPageScrolling: true
			});
		});
	}
}

function wptouchHandleResetSettings() {
	jQuery( '#reset' ).click( function( e ) {
		if ( !confirm( WPtouchCustom.reset_settings ) ) {
			e.preventDefault();
		}
	});
}

function wptouchLoadChangeLog() {
	var ajaxParams = {};

	wptouchAdminAjax( 'admin-change-log', ajaxParams, function( result ) {
		jQuery( '#change-log' ).html( result );
	});
}

function wptouchLoadThemes() {
	var themesDiv = jQuery( '#wptouch-theme-browser-load-ajax' );
	if ( themesDiv.length ) {
		var ajaxParams = {};

		wptouchAdminAjax( 'load-theme-browser', ajaxParams, function( result ) {

			// No internet connection
			if ( !navigator.onLine ) {
				themesDiv.find( '.load' ).replaceWith( '<div class="not-available">' + WPtouchCustom.cloud_offline_message + '</div>' );
			// looks like we're online
			} else {
				themesDiv.find( '.load' ).parent().replaceWith( result );

				jQuery( '#setup-themes-browser' ).on( 'click', 'a.download, a.upgrade', function( e ) {
					var pressedButton = jQuery( this );
					var installURL = jQuery( this ).attr( 'data-url' );
					var basePath = jQuery( this ).attr( 'data-name' );

					var loadingText = jQuery( this ).attr( 'data-loading-text' );

					var ajaxParams = {
						url: installURL,
						base: basePath
					};

					pressedButton.html( loadingText ).addClass( 'disabled' );

					wptouchAdminAjax( 'download-theme', ajaxParams, function( result ) {
						ourResult = jQuery.parseJSON( result );
						if ( ourResult.status == '1' ) {
							// Succeeded
							location.reload( true );
						} else {
							var str = WPtouchCustom.cloud_download_fail;
							alert( str.replace( '%reason%', ourResult.error ) );
						}
					});

					e.preventDefault();
				});
			}
		});
	}
}

function wptouchLoadAddons() {
	var addonDiv = jQuery( '#wptouch-addon-browser-load-ajax' );
	if ( addonDiv.length ) {
		var ajaxParams = {};

		wptouchAdminAjax( 'load-addon-browser', ajaxParams, function( result ) {

			// No internet connection
			if ( !navigator.onLine ) {
				addonDiv.find( '.load' ).replaceWith( '<div class="not-available">' + WPtouchCustom.cloud_offline_message + '</div>' );
			// looks like we're online
			} else {
				addonDiv.find( '.load' ).parent().replaceWith( result );

				jQuery( '#setup-addons-browser' ).on( 'click', 'a.download, a.upgrade', function( e ) {
					var pressedButton = jQuery( this );
					var installURL = jQuery( this ).attr( 'data-url' );
					var basePath = jQuery( this ).attr( 'data-name' );

					var loadingText = jQuery( this ).attr( 'data-loading-text' );

					var ajaxParams = {
						url: installURL,
						base: basePath
					};

					var oldText = pressedButton.html();
					pressedButton.html( loadingText ).addClass( 'disabled' );

					wptouchAdminAjax( 'download-addon', ajaxParams, function( result ) {
						ourResult = jQuery.parseJSON( result );
						if ( ourResult.status == '1' ) {
							// Succeeded
							location.reload( true );
						} else {
							var str = WPtouchCustom.cloud_download_fail;
							alert( str.replace( '%reason%', ourResult.error ) );

							pressedButton.html( loadingText ).removeClass( 'disabled' ).html( oldText );
						}
					});

					e.preventDefault();
				});
			}

		});
	}
}

function wptouchAdminHandleGeneral() {
	wptouchCheckToggle( '#show_wptouch_in_footer', '#setting-add_referral_code' );
}

function wptouchAdminReady() {
	wptouchSetupAdminMenu();
	wptouchTooltipSetup();
	wptouchHandleLicensePanel();
	wptouchHandleMenuArea();
	wptouchHandleIconDragDrop();
	wptouchAdminHandleGeneral();

	wptouchSetupHomescreenUploaders();
	wptouchHandleCustomIconUpload();
	wptouchSetupNotifications();
	wptouchHandlePluginCompat();
	wptouchSetupAdminToggles();

	wptouchSetupRadios();
	wptouchHandleDownloadSettings();
	wptouchSetupColorPicker();
	wptouchPreviewWindow();
	wptouchHandlePreviewWindow();

	wptouchLoadTouchBoardArea();
	wptouchLoadUpgradeArea();
	wptouchHandleDownloadIconSets();
	wptouchSetupNews();
	wptouchLoadChangeLog();
	wptouchHandleResetSettings();

	wptouchLoadThemes();
	wptouchLoadAddons();
}

jQuery( document ).ready( function() {
	wptouchAdminReady();
});