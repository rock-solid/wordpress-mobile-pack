<?php

/* This is the main settings object for WPtouch Pro 3.x /*
/* It defines the default settings for the majority of features within WPtouch Pro */
/* To augment these settings, please use one of the appropriate WPtouch hooks */

class WPtouchSettings extends stdClass {
	function save() {
		if ( isset( $this->domain ) ) {
			global $wptouch_pro;
			$wptouch_pro->save_settings( $this, $this->domain );
		} else {
			die( 'Setting domain not set' );
		}
	}
};

// These settings should never be adjusted, but rather should be augmented at a later time */
class WPtouchDefaultSettings30 extends WPtouchSettings {
	function WPtouchDefaultSettings30() {
		// Basic or advanced mode
		$this->settings_mode = WPTOUCH_SETTING_BASIC;
		$this->display_mode = 'normal';

		// Setup - General
		$this->site_title = get_bloginfo( 'name' );
		if ( defined( 'WPTOUCH_IS_FREE' ) ) {
			$this->show_wptouch_in_footer = false;
		} else {
			$this->show_wptouch_in_footer = true;	
		}
		
		$this->add_referral_code = false;

		// Setup - Desktop / Mobile Switching
		$this->desktop_is_first_view = false;
		$this->show_switch_link = true;
		$this->switch_link_method = 'automatic';
		$this->mobile_switch_link_target = 'current_page';

		// Setup - Regionalization
		$this->force_locale = 'auto';
		$this->translate_admin = true;

		// Setup - Statistics
		$this->custom_stats_code = '';

		// Setup - Home Page Redirect
		$this->homepage_landing = 'none';
		$this->homepage_redirect_wp_target = 0;
		$this->homepage_redirect_custom_target = '';

		// Setup - Backup and Import
		$this->automatically_backup_settings = true;

		// Setup - Tools and Debug
		$this->use_jquery_2 = false;
		$this->show_footer_load_times = false;
		// Depreciated in 3.1
		$this->preview_mode = 'off';

		// Setup - Compatibility
		$this->include_functions_from_desktop_theme = false;
		$this->functions_php_loading_method = 'translate';

		$this->remove_shortcodes = '';
		$this->ignore_urls = '';
		$this->custom_user_agents = '';

		// Default Theme
		$this->current_theme_friendly_name = 'Bauhaus';
		$this->current_theme_location = '/plugins/' . WPTOUCH_ROOT_NAME . '/themes';
		$this->current_theme_name = 'bauhaus';

		// Warnings
		$this->dismissed_notifications = array();

		// Menu
		$this->custom_menu_name = 'wp';
		$this->appended_menu_name = 'none';
		$this->prepended_menu_name = 'none';

		$this->enable_parent_items = true;
		$this->enable_menu_icons = true;

		$this->default_menu_icon = WPTOUCH_DEFAULT_MENU_ICON;
		$this->disabled_menu_items = array();
		$this->temp_disabled_menu_items = array();

		// Debug Log
		$this->debug_log = false;
		$this->debug_log_level = WPTOUCH_ALL;
		$this->debug_log_salt = substr( md5( mt_rand() ), 0, 10 );

		// Settings that are not yet hooked up and might go away
		$this->menu_icons = array();			// ?
		$this->menu_sort_order = 'wordpress';
		$this->menu_disable_parent_as_child = false;
		$this->disable_menu = false;
		$this->make_links_clickable = false;
		$this->custom_css_file = '';
		$this->wptouch_enable_custom_post_types = false;
		$this->always_refresh_css_js_files = false;
		$this->classic_excluded_categories = false;
		$this->convert_menu_links_to_internal = false;

		// Settings that probably need to go away
		$this->post_thumbnails_enabled = true;

		// Add-Ons
		$this->active_addons = array();
		$this->show_wpml_lang_switcher = true;
	}
};

class WPtouchDefaultSettingsBNCID30 extends WPtouchSettings {
	function WPtouchDefaultSettingsBNCID30() {
		// License Information
		$this->bncid = '';
		$this->wptouch_license_key = '';

		$this->license_accepted = false;
		$this->license_accepted_time = 0;
		$this->next_update_check_time = 0;
		$this->failures = 0;

		$this->referral_user_id = false;
	}
};

class WPtouchDefaultSettingsCompat extends WPtouchSettings {
	function WPtouchDefaultSettingsCompat() {
		$this->plugin_hooks = '';
		$this->enabled_plugins = array();
	}
};
