var $wpsmart = jQuery.noConflict();

$wpsmart(document).ready( function() {
	
	var wps_preview_frame = $wpsmart('#wps-admin-preview-frame');
	
	// preview link
	$wpsmart('.wps-preview-link').click(function(event) {
		event.preventDefault();
		wps_show_preview_modal();
	});
	
	// preview theme link
	$wpsmart('.wps-preview-theme-link a').click(function(event) {
		event.preventDefault();

		var base_url = wps_preview_frame.attr('src').replace(/\?(.*)/, ""),
			preview_theme = $wpsmart(this).data('theme');
			
		wps_preview_frame.attr('src', base_url + '?wps_preview=1&wps_preview_theme=' + preview_theme);
		
		wps_show_preview_modal();
	});
	
	// switch preview devices
	$wpsmart('#wps-device-thumb span').click(function(event) {
		event.preventDefault();
		
		var selected_device = $wpsmart(this).data('device'),
			device_object = $wpsmart('#wps-device');
		
		if( device_object.hasClass(selected_device) )
			return false;
		else
			device_object.removeClass().addClass(selected_device);
	});
	
	
	// navigation bar links
	$wpsmart('#wps-admin-nav li a').click(function(event) {
		event.preventDefault();
		var page = $wpsmart(this).data('page');
		wps_show_page(page);
	});
	
	// show settings page to enter license key
	$wpsmart('#wps-show-license-page').click(function(event) {
		event.preventDefault();
		wps_show_page('settings');
	});
	
	$wpsmart('#blog_pages').change(function() {
		if($wpsmart(this).val() == 'custom_link')
			$wpsmart('.wp-url-setting').toggle();
		else
			$wpsmart('.wp-url-setting').hide();
	});
	
	$wpsmart('#wps-remove-logo').click(function(event) {
		event.preventDefault();
		
		$wpsmart('#wps_admin_logo_preview').html('');
		wps_preview_frame.contents().find('h1.site-title a').text($wpsmart('#site_title').val());
		$wpsmart(this).hide();
	});
	
	$wpsmart('#site_logo').change(function() {
		$wpsmart('#wps_admin_update_form').ajaxSubmit({
			url : ajaxurl,
			dataType: 'json',
			data : {action : 'wps_upload_file'},
			success: function(response) {
				$wpsmart('#wps_admin_logo_preview').html('<img src="' + response.url + '" id="wps-admin-logo"/>');
			}
		});
	});
	
	$wpsmart('#analytics_type').change(function() {
		$wpsmart('.wps-admin-analytics-group').addClass('hidden');
		
		if($wpsmart(this).val() != 'none')
		{
			var type_id = $wpsmart(this).val();
			$wpsmart('#' + type_id).removeClass('hidden');
		}
	});
	
	$wpsmart('#advertising_type').change(function() {
		$wpsmart('.wps-admin-advertising-group').addClass('hidden');
		
		if($wpsmart(this).val() != 'none')
		{
			var type_id = $wpsmart(this).val();
			$wpsmart('#ads_' + type_id).removeClass('hidden');
		}
	});
	
	$wpsmart('.wps-save-form').click(function(event) {
		event.preventDefault();
		$wpsmart('#wps_admin_update_form').submit();
	});
	
	$wpsmart('#wps_admin_update_form').submit(function(event) {
		event.preventDefault();
		wps_show_form_loader();
		
		var form_data = $wpsmart(this).serializeArray();
		
		form_data.push({name : 'action', value: 'wps_update_options'});
		
		if($wpsmart('#wps_admin_logo_preview img').length > 0)
			form_data.push({name: 'site_logo', value: $wpsmart('#wps_admin_logo_preview img').prop('src')});
		else
			form_data.push({name: 'site_logo', value: ''});
		
		
		$wpsmart.post(ajaxurl, form_data, function(response) {
			wps_hide_form_loader();
			wps_success_form();
			wps_preview_frame.attr('src', wps_preview_frame.attr('src') );
		},'json');
	});
	
	$wpsmart('#wps_admin_support_form').live('submit', function(event) {
		event.preventDefault();
		
		var form_data = $wpsmart(this).serializeArray(),
			form = $wpsmart(this);
		
		$wpsmart.post(ajaxurl, form_data, function(response) {
			if(response.status == 'success') {
				form.replaceWith('<div class="success">Thanks, we\'ll be in touch shortly!</div>');
				setTimeout(function() {wps_close_support_popover()}, 2500);
			}
		},'json');
	});
	
	
	// add a custom link to the menu	
	$wpsmart('#add-customlink').click(function(event) {
		event.preventDefault();
		
		var	count = $wpsmart('ul.wps-admin-menu-links li').length,
			title = $wpsmart('#custom-menu-item-name').val(),
			url = $wpsmart('#custom-menu-item-url').val();
							
		if( title != '' && url != '') { 
			
			wps_add_link_to_menu(title, url, '', count);
						
			$wpsmart('#custom-menu-item-name').val('');
			$wpsmart('#custom-menu-item-url').val('http://');
			
		}
	});
	
	
	// add pages to the menu
	$wpsmart('#add-pages').click(function(event) {
		event.preventDefault();
		
		var	count = $wpsmart('ul.wps-admin-menu-links li').length,
			selected_pages = $wpsmart('#site-pages input[type=checkbox]:checked');
			
		selected_pages.each(function(key, page) {
			var title = $wpsmart(page).data('title'),
				url = $wpsmart(page).data('guid');
				
			wps_add_link_to_menu(title, url, '', count);
			$wpsmart(page).attr('checked', false);
			
			count++;
		});
	});
	
	
	// add categories to the menu
	$wpsmart('#add-categories').click(function(event) {
		event.preventDefault();
		
		var	count = $wpsmart('ul.wps-admin-menu-links li').length,
			selected_pages = $wpsmart('#site-categories input[type=checkbox]:checked');
			
		selected_pages.each(function(key, page) {
			var title = $wpsmart(page).data('title'),
				url = $wpsmart(page).data('guid');
				
			wps_add_link_to_menu(title, url, '', count);
			$wpsmart(page).attr('checked', false);
			
			count++;
		});
	});
	
	
	// toggle the menu button
	$wpsmart('#enable-menu').change(function() {
		$wpsmart('#wps-menu-disabled-message').toggle();
		$wpsmart('#wps-edit-menu').toggle();
	});
	
	// change the selected menu
	$wpsmart('#select-menu').click(function(event) {
		event.preventDefault();
		
		var menu_id = $wpsmart('#site-menu').val(),
			menu_html = $wpsmart.trim($wpsmart('ul.wps-admin-menu-links').html());
		
		if( menu_html == '' )
			var confirm_new_menu = true;
		else
			var confirm_new_menu = confirm('This will delete your current menu. Continue?');
		
		if( confirm_new_menu ) {
			if( menu_id != 'new' ) {
				$wpsmart('#enable-menu').attr('checked', 'checked');
				$wpsmart.post(ajaxurl, {action : 'wps_get_menu', menu_id : menu_id}, function(response) {
					$wpsmart('ul.wps-admin-menu-links').html(response);
				});
			} else {
				$wpsmart('ul.wps-admin-menu-links').html('');
			}
		}
	});

	
	// drop-down edit of menu items
	$wpsmart('.menu-item-bar a.item-edit').live('click', function(event) {
		event.preventDefault();
		
		var parent_li = $wpsmart(this).closest('li');
		
		if(parent_li.hasClass('menu-item-edit-inactive')) {
			parent_li.removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
			parent_li.find('.menu-item-settings').show();
		}
		else {
			parent_li.removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
			parent_li.find('.menu-item-settings').hide();
		}
	});
	
	
	// delete a menu item
	$wpsmart('.wps-admin-menu-links .item-delete').live('click', function(event) {
		event.preventDefault();
		$wpsmart(this).closest('li').remove();
	});
	
	$wpsmart( "ul.wps-admin-menu-links" ).sortable({ handle: '.menu-item-handle' });
	
	$wpsmart('a#support_popover')
		.popover({'animation' : false, 'title' : 'Questions/Comments?', 'html' : true, 'content' : wps_support_popover_html() })
		.click(function(event) {
			event.preventDefault();
			wps_show_support_popover();
		});
	 
	 $wpsmart('#overlay').live('click', function() {
	 	wps_close_support_popover();
	 	wps_close_preview_modal();
	 });
	 
	 $wpsmart('.wps-close').live('click', function(event) {
		 event.preventDefault();
		 wps_close_support_popover();
		 wps_close_preview_modal();
	 });
	 
	 $wpsmart('.wps-activate-theme-link a').click(function(event) {
	 	event.preventDefault();
	 	
		var theme_slug = $wpsmart(this).data('theme'),
			data = {theme: theme_slug, action: 'wps_activate_theme'};
		
		$wpsmart.post(ajaxurl, data, function(response) {
			wps_success_form();
			location.reload();
		}); 
	 });
	  
	 $wpsmart('#site_background_color').miniColors({
		'letterCase' : 'uppercase'/*,
		change: function(hex, rgb) {
			wps_preview_frame.contents().find('body').css('background-color',hex);
		}*/
	});
	
	$wpsmart('#header_background_color').miniColors({
		'letterCase' : 'uppercase'/*,
		change: function(hex, rgb) {
			wps_preview_frame.contents().find('header.site-header').css('background-color',hex);
		}*/
	});
	
	$wpsmart('#header_trim_color').miniColors({
		'letterCase' : 'uppercase'/*,
		change: function(hex, rgb) {
			wps_preview_frame.contents().find('header.site-header').css('border-top-color', hex);
		}*/
	});
	
	$wpsmart('#header_text_color').miniColors({
		'letterCase' : 'uppercase'/*,
		change: function(hex, rgb) {
			wps_preview_frame.contents().find('header.site-header').css('color',hex);
		}*/
	});
	
	
	//verify license key
	$wpsmart('#wps-verify-license').click(function(event) {
		event.preventDefault();
		
		var entered_key = $wpsmart('input#license_key').val();
		
		wps_show_license_key_loader()
	
		$wpsmart.post(ajaxurl, {key:entered_key, action:'wps_check_license_key'}, function(response) {
			$wpsmart('#wps-license-key-status').html(response.message);	
			
			if(response.status == 'active' && $wpsmart('#wps-admin-license-message').length > 0)
				$wpsmart('#wps-admin-license-message').remove();
			
			wps_hide_license_key_loader();
		},'json');
	});
	
	//license key input
	$wpsmart('#license_key').keyup(function(event) {
		if($wpsmart(this).val() == '')
			$wpsmart('#wps-license-key-status').html('<span class="invalid">Invalid license key</span>');
	});
});

function wps_show_form_loader() {
	$wpsmart('form button[type=submit]').hide();
	$wpsmart('.wps-loader').show();
}

function wps_hide_form_loader() {
	$wpsmart('.wps-loader').hide();
	$wpsmart('form button[type=submit]').show();
}

function wps_show_license_key_loader() {
	$wpsmart('#wps-license-key-status').hide();
	$wpsmart('#wps-license-key-loader').show();
}

function wps_hide_license_key_loader() {
	$wpsmart('#wps-license-key-loader').hide();
	$wpsmart('#wps-license-key-status').show();
}

function wps_success_form() {
	$wpsmart('#wps_admin_saved').css({
		position : 'fixed', 
		left : ($wpsmart('.wps-admin-content').outerWidth(false) - $wpsmart('#wps_admin_saved').outerWidth(false)) / 2 + $wpsmart('.wps-admin-content').offset().left,
		top: (document.body.clientHeight - $wpsmart('#wps_admin_saved').outerHeight(false)) / 2
	}).fadeIn(function(){		
		setTimeout(function() { $wpsmart('#wps_admin_saved').fadeOut()}, 1500);
	});

}

function wps_support_popover_html() {
	var support_html = '';
	
	support_html += '<form action="#" name="wps_admin_support_form" id="wps_admin_support_form" method="post"><input type="hidden" name="action" value="wps_support_submission"/>';
	support_html += '<span class="help-text">Let us know if you have any questions and we\'ll get back to you as soon as possible!</span>';
	support_html += '<div class="popover-input-row"><input type="text" name="email" placeholder="Your email address" /></div>';
	support_html += '<div class="popover-input-row"><textarea name="body" rows="5"></textarea></div>';
	support_html += '<div class="popover-submit-row"><span class="wps-close"><a href="#">Cancel</a></span><button type="submit" class="wps-save">Talk to us</button></div>';
	support_html += '</form>';
	
	return support_html;
}

function wps_close_support_popover() {
	$wpsmart('a#support_popover').popover('hide'); 
	$wpsmart('#overlay').remove();
}

function wps_show_support_popover() {
	$wpsmart('body').append('<div id="overlay"></div>');
	$wpsmart(this).popover('show');
}

function wps_add_link_to_menu(title, url, icon, count) {
	var input_fields_html = '<input type="hidden" name="menu_links[' + count + '][title]" value="' + title + '"/>';
			input_fields_html += '<input type="hidden" name="menu_links[' + count + '][url]" value="' + url + '"/>';
			input_fields_html += '<input type="hidden" name="menu_links[' + count + '][icon]" value=""/>';
			
	var menu_item_html = '<li class="menu-item menu-item-edit-inactive">';
	menu_item_html += '<dl class="menu-item-bar"><dt class="menu-item-handle"><span class="item-title">' + title + '</span><span class="item-controls"><a class="item-edit" title="Edit Menu Item" href="">Edit Menu Item</a></span></dt></dl>'
	menu_item_html += '<div class="menu-item-settings" style="display: none;"><p class="description description-thin"><label>Label<br><input type="text" class="widefat edit-menu-item-title" value="' + title + '"></label></p>';
	menu_item_html += '<div class="menu-item-actions description-wide submitbox"><a class="item-delete submitdelete deletion" href="#">Remove</a> <span class="meta-sep"></div>';
	menu_item_html += '<input type="hidden" name="menu_links[' + count + '][title]" value="' + title + '" /><input type="hidden" name="menu_links[' + count + '][url]" value="' + url + '" /><input type="hidden" name="menu_links[' + count + '][icon]" value="" /></div>';
	menu_item_html += '</li>';
	

	$wpsmart('ul.wps-admin-menu-links').append(menu_item_html);
}

function wps_show_page( page ) {

	$wpsmart('#wps-admin-nav li').removeClass('active');
	$wpsmart('#wps-admin-nav').find('a[data-page="' + page + '"]').closest('li').addClass('active');
	$wpsmart('.wps-admin-option-group').hide();
	$wpsmart('#wps-admin-' + page).show();
}


function wps_close_preview_modal() {
	$wpsmart('#wps-admin-preview').css('visibility', 'hidden');
	$wpsmart('#overlay').remove();
	
	var wps_preview_frame = $wpsmart('#wps-admin-preview-frame');
	
	// reset preview to current theme
	var base_url = wps_preview_frame.attr('src').replace(/\?(.*)/, "");
	wps_preview_frame.attr('src', base_url + '?wps_preview=1');
}

function wps_show_preview_modal() {
	var wps_preview_frame = $wpsmart('#wps-admin-preview-frame');

	// apply appearance changes
	wps_preview_frame.contents().find('header.site-header').css('color', $wpsmart('#header_text_color').val());
	wps_preview_frame.contents().find('header.site-header').css('border-top-color', $wpsmart('#header_trim_color').val());
	wps_preview_frame.contents().find('header.site-header').css('background-color', $wpsmart('#header_background_color').val());
	wps_preview_frame.contents().find('body').css('background-color', $wpsmart('#site_background_color').val());
	wps_preview_frame.contents().find('body').css('font-family',$wpsmart('#site_font').val());
	wps_preview_frame.contents().find('.home-content article .entry-image').toggle($wpsmart('#show_thumbnails').is(':checked'));
	wps_preview_frame.contents().find('#view-menu').toggle($wpsmart('#enable-menu').is(':checked'));
	wps_preview_frame.contents().find('#view-search').toggle($wpsmart('#enable_search').is(':checked'));
	wps_preview_frame.contents().find('.entry-meta span').toggle($wpsmart('#show_post_author').is(':checked'));
	
	if( ! $wpsmart('#wps-admin-logo').attr('src') )
		wps_preview_frame.contents().find('h1.site-title a').text($wpsmart('#site_title').val());
	else
		wps_preview_frame.contents().find('h1.site-title a').html('<img src="' + $wpsmart('#wps-admin-logo').attr('src') + '"/>');

	$wpsmart('body').append('<div id="overlay"></div>');
	$wpsmart('#wps-admin-preview').css('visibility', 'visible');
}
