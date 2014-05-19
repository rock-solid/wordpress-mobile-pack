<div class="wps-admin-option-group" id="wps-admin-settings" style="display:block">
		
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Title</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<input type="text" name="site_title" id="site_title" value="<?php echo wps_get_option('site_title'); ?>"/>
			</div>
			<div class="wps-admin-section-hint">The site title to be displayed if no logo is uploaded</div>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Site Logo</span>
		</div>
		<div class="wps-admin-section-input-group">
		
			<?php if( wps_is_uploads_directory_writable() ): ?>
			
			<div class="wps-admin-section-input">
				<input type="file" name="site_logo" id="site_logo"/>
				<div class="wps-admin-logo-preview" id="wps_admin_logo_preview">
					<?php if( wps_get_option('site_logo') != '' ): ?>
						<img src="<?php echo wps_get_option('site_logo'); ?>" id="wps-admin-logo" />
					<?php endif; ?>
				</div>
			</div>
			<div class="wps-admin-section-hint">
				<span>Optimal logo size is 400px by 50px for retina displays. <?php if( wps_get_option('site_logo') != '' ): ?><a href="#" class="wps-remove-logo" id="wps-remove-logo">Remove current logo</a><?php endif; ?></span>
				<span>Your upload directory is <strong><?php echo wps_upload_base_dir() ?></strong></span>	
			</div>
		
			<?php else: ?>
				
			<span class="error">The uploads directory <strong><?php echo wps_upload_base_dir() ?></strong> is NOT writeable, please fix this error and refresh the page.</span>
				
			<?php endif; ?>
		
		</div>
		
		<div class="clear"></div>
	</div>
	
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>General</span>
		</div>
		<div class="wps-admin-section-input-group">
			<p class="wps-admin-input-checkbox">
				<input type="hidden" name="enable_search" value="0"/>
				<input type="checkbox" name="enable_search" id="enable_search" value="1" <?php echo wps_checkbox_text( 'enable_search' ) ?>/><label for="enable_search">Enable search from site header</label>
			</p>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-input-checkbox">
				<input type="hidden" name="enable_comments" value="0"/>
				<input type="checkbox" name="enable_comments" id="enable_comments" value="1" <?php echo wps_checkbox_text( 'enable_comments' ) ?>/><label for="enable_comments">Enable commenting in posts</label>
			</div>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-input">
				<label>Custom front page</label>
				<select name="front_page" id="front_page">
					<option value="">None</option>
					<?php foreach( wps_get_pages() as $wps_page ) : ?>
						<option value="<?php echo $wps_page['page_id']; ?>" <?php echo wps_get_option( 'front_page' ) == $wps_page['page_id'] ? 'selected="selected"' : null ?>><?php echo $wps_page['page_title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Post settings</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-input-checkbox">
				<input type="hidden" name="show_post_author" value="0"/>
				<input type="checkbox" name="show_post_author" id="show_post_author" value="1" <?php echo wps_checkbox_text( 'show_post_author' ) ?>/><label for="show_post_author">Show post author and date</label>
			</div>
			<div class="wps-admin-input-checkbox">
				<input type="hidden" name="show_post_tags" value="0"/>
				<input type="checkbox" name="show_post_tags" id="show_post_tags" value="1" <?php echo wps_checkbox_text( 'show_post_tags' ) ?>/><label for="show_post_tags">Show tags when viewing a post</label>
			</div>
			<div class="wps-admin-input-checkbox">
				<input type="hidden" name="show_post_categories" value="0"/>
				<input type="checkbox" name="show_post_categories" id="show_post_categories" value="1" <?php echo wps_checkbox_text( 'show_post_categories' ) ?>/><label for="show_post_categories">Show categories when viewing a post</label>
			</div>
			<div class="wps-admin-input-checkbox">
				<input type="hidden" name="show_thumbnails" value="0"/>
				<input type="checkbox" name="show_thumbnails" id="show_thumbnails" value="1" <?php echo wps_checkbox_text( 'show_thumbnails' ) ?>/><label for="show_thumbnails">Show image thumbnails in post listings (not applicable on some themes)</label>
			</div>
            <div class="wps-admin-input-checkbox">
                <input type="hidden" name="show_featured_image_in_post" value="0"/>
                <input type="checkbox" name="show_featured_image_in_post" id="show_featured_image_in_post" value="1" <?php echo wps_checkbox_text( 'show_featured_image_in_post' ) ?>/><label for="show_featured_image_in_post">Show featured images on post pages</label>
            </div>
		</div>
	</div>
		
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Analytics</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<select name="analytics_type" id="analytics_type">
					<option value="none" <?php echo wps_get_option( 'analytics_type' ) == 'none' ? 'selected="selected"' : null ?>>No analytics</option>
					<option value="google_analytics" <?php echo wps_get_option( 'analytics_type' ) == 'google_analytics' ? 'selected="selected"' : null ?>>Google Analytics</option>
					<option value="custom_analytics" <?php echo wps_get_option( 'analytics_type' ) == 'custom_analytics' ? 'selected="selected"' : null ?>>Custom</option>
				</select>
			</div>
		</div>
		<div class="wps-admin-section-input-group wps-admin-analytics-group <?php echo wps_get_option( 'analytics_type' ) != 'google_analytics' ? "hidden" : null ?>" id="google_analytics">
			<div class="wps-admin-section-input">
				<input type="text" id="google_analytics_code" name="google_analytics_code" value="<?php echo wps_get_option( 'google_analytics_code' ) ?>"/>
			</div>
			<div class="wps-admin-section-hint">Paste your Google Analytics tracking ID</div>
		</div>
		<div class="wps-admin-section-input-group wps-admin-analytics-group <?php echo wps_get_option( 'analytics_type' ) != 'custom_analytics' ? "hidden" : null ?>" id="custom_analytics">
			<div class="wps-admin-section-input">
				<textarea id="custom_analytics_code" name="custom_analytics_code" rows="5"><?php echo wps_html_unclean( wps_get_option( 'custom_analytics_code' ) ) ?></textarea>
			</div>
			<div class="wps-admin-section-hint">Paste your analytics javascript code</div>
		</div>
	</div>
	
	<div class="wps-admin-section">
		<div class="wps-admin-section-title">
			<span>Advertising</span>
		</div>
		<div class="wps-admin-section-input-group">
			<div class="wps-admin-section-input">
				<select name="advertising_type" id="advertising_type">
					<option value="none" <?php echo wps_get_option( 'advertising_type' ) == 'none' ? 'selected="selected"' : null ?>>No advertising</option>
					<option value="google_adsense" <?php echo wps_get_option( 'advertising_type' ) == 'google_adsense' ? 'selected="selected"' : null ?>>Google AdSense</option>
					<option value="custom_advertising" <?php echo wps_get_option( 'advertising_type' ) == 'custom_advertising' ? 'selected="selected"' : null ?>>Custom</option>
				</select>
			</div>
		</div>
		<div class="wps-admin-section-input-group wps-admin-advertising-group <?php echo wps_get_option( 'advertising_type' ) != 'google_adsense' ? "hidden" : null ?>" id="ads_google_adsense">
			<div class="wps-admin-section-input">
				<label>Google AdSense Client ID</label>
				<input type="text" class="text" id="adsense_client_id" name="adsense_client_id" value="<?php echo wps_get_option( 'adsense_client_id' ) ?>"/>
			</div>
		</div>
		<div class="wps-admin-section-input-group wps-admin-advertising-group <?php echo wps_get_option( 'advertising_type' ) != 'custom_advertising' ? "hidden" : null ?>" id="ads_custom_advertising">	
			<div class="wps-admin-section-input">
				<label>Custom advertising code</label>
				<textarea id="custom_advertising_code" name="custom_advertising_code" rows="5"><?php echo wps_html_unclean( wps_get_option( 'custom_advertising_code' ) ) ?></textarea>
			</div>
		</div>
	</div>	
</div>