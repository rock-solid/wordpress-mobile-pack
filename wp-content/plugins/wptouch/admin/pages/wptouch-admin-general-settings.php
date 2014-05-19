<?php

define( 'WPTOUCH_ADMIN_SETUP_GENERAL', __( 'General', 'wptouch-pro' ) );
define( 'WPTOUCH_ADMIN_SETUP_COMPAT', __( 'Compatibility', 'wptouch-pro' ) );

add_filter( 'wptouch_admin_page_render_wptouch-admin-general-settings', 'wptouch_render_general_page' );

function wptouch_admin_get_languages() {
	$languages = array(
		'auto' => __( 'Auto-detect', 'wptouch-pro' ),
		'en_US' => 'English',
		'fr_FR' => 'Français',
		'it_IT' => 'Italiano',
		'es_ES' => 'Español',
		'sv_SE' => 'Svenska',
		'de_DE' => 'Deutsch',
		'el' => 'ελληνικά',
		'da_DK' => 'Dansk',
		'pt' => 'Português',
		'nl_NL' => 'Nederlands',
		'hu' => 'Magyar',
		'id_ID' => 'Bahasa Indonesia',
		'he_IL' => 'עִבְרִית',
		'vi' => 'Tiếng Việt',
		'tr' => 'Türkçe',
		'ru_RU' => 'русский',
		'th' => 'ภาษาไทย',
		'ja_JP' => '日本語',
		'zh_CN' => '简体字',
		'zh_HK' => '繁體字',
		'ko_KR' => '한국어,조선말',
		'hi_IN' => 'मानक हिन्दी',
		'ar' => 'العربية/عربي'	
	);	
	
	return apply_filters( 'wptouch_admin_languages', $languages );
}

function wptouch_render_general_page( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_SETUP_GENERAL, 'setup-general-general', $page_options );
	wptouch_add_sub_page( WPTOUCH_ADMIN_SETUP_COMPAT, 'setup-general-compat', $page_options );

	$these_settings = array(
		wptouch_add_setting( 
			'text', 
			'site_title', 
			wptouchize_it( __( 'WPtouch Pro site title', 'wptouch-pro' ) ), 
			__( 'If the title of your site is long, you can shorten it for display within WPtouch Pro themes.', 'wptouch-pro' ), 
			WPTOUCH_SETTING_BASIC, 
			'3.0' 
		),
		wptouch_add_setting( 
			'checkbox', 
			'show_wptouch_in_footer', 
			wptouchize_it( sprintf( __( 'Display %sPowered by WPtouch Pro%s in footer', 'wptouch-pro' ), '&quot;', '&quot;' ) ), 
			'', 
			WPTOUCH_SETTING_BASIC, 
			3.0 
		)
	);

	if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
		$these_settings[] = wptouch_add_setting( 
			'checkbox', 
			'add_referral_code', 
			__( 'Use my WPtouch Pro referral code to earn commission', 'wptouch-pro' ), 
			__( 'Licensed users of WPtouch Pro can earn a commission for each sale they generate from their mobile website', 'wptouch-pro') , 
			WPTOUCH_SETTING_BASIC, 
			3.2
		);
	}

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Site Title & Byline', 'wptouch-pro' ),
		'setup-title-byline',
		$these_settings,
		$page_options
	);

	// Build admin panel page here
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Regionalization', 'wptouch-pro' ),
		'setup-regionalization',
		array(
			wptouch_add_setting( 
				'list', 
				'force_locale', 
				__( 'Language', 'wptouch-pro' ), 
				wptouchize_it( __( 'The WPtouch Pro admin panel &amp; supported themes will be shown in this locale.', 'wptouch-pro' ) ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0', 
				wptouch_admin_get_languages()
			),
			wptouch_add_setting( 
				'checkbox', 
				'translate_admin', 
				__( 'Translate administration panel text', 'wptouch-pro' ), 
				'', 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0.2'
			)
		),
		$page_options
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Display Mode', 'wptouch-pro' ),
		'setup-display-mode',		
		array(
			wptouch_add_setting(
				'radiolist',
				'display_mode',
				__( 'Theme Display', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'3.1',
				array(
					'normal' => __( 'Normal (active for all mobile visitors)', 'wptouch-pro' ),
					'preview' => __( 'Preview (active only for logged-in site administrators)', 'wptouch-pro' ),
					'disabled' => __( 'Disabled (mobile theme will never show)', 'wptouch-pro' )
				)
			)
		),
		$page_options
	);
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Landing Page', 'wptouch-pro' ),
		'setup-landing-page',
		array(
			wptouch_add_setting( 
				'radiolist', 
				'homepage_landing', 
				wptouchize_it( __( 'WPtouch Pro landing page', 'wptouch-pro' ) ), 
				'',
				WPTOUCH_SETTING_BASIC,
				'3.0',
				array(
					'none' => __( 'Default (same as WordPress)', 'wptouch-pro' ),
					'select' => __( 'Select from WordPress pages', 'wptouch-pro' ),							
					'custom' => _x( 'Custom', 'Refers to a custom landing page', 'wptouch-pro' )
				)
			),
			wptouch_add_setting( 'redirect', 'homepage_redirect_wp_target', '', '', WPTOUCH_SETTING_BASIC, '3.0' ),
			wptouch_add_setting( 
				'text', 
				'homepage_redirect_custom_target', 
				__( 'Custom Slug or URL', 'wptouch-pro' ), 
				__( 'Enter a Slug (i.e. "/home") or a full URL path', 'wptouch-pro' ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0' 
			),
		),
		$page_options
	);	
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Desktop / Mobile Switching', 'wptouch-pro' ),
		'setup-general',
		array(
			wptouch_add_setting( 
				'checkbox', 
				'desktop_is_first_view', 
				__( '1st time visitors see desktop theme', 'wptouch-pro' ), 
				__( 'Your regular theme will be shown to 1st time mobile visitors.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting( 
				'checkbox', 
				'show_switch_link', 
				__( 'Show switch link in mobile view', 'wptouch-pro' ), 
				__( 'Will show toggle buttons in the theme\'s footer allowing users to switch to your desktop theme. Is not shown in Web-App Mode.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting(
				'radiolist',
				'mobile_switch_link_target',
				__( 'Choose the target for the mobile switch link', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_ADVANCED,
				'3.0.1',
				array(
					'current_page' => __( 'Current page', 'wptouch-pro' ),
					'home_page' => __( 'Home page', 'wptouch-pro ')
				)
			),
			wptouch_add_setting( 
				'radiolist', 
				'switch_link_method', 
				__( 'Desktop theme switch buttons', 'wptouch-pro' ), 
				__( 'Allows visitors to switch from your desktop theme to your mobile theme. You can also customize the placement of Switch buttons by placing the wptouch_desktop_switch_link() template tag somewhere in your desktop theme.', 'wptouch-pro' ),
				WPTOUCH_SETTING_ADVANCED,
				'3.0',
				array(
					'automatic' => __( 'Automatically inserted inline', 'wptouch-pro' ),
					'ajax' => __( 'Automatically inserted with AJAX (better for caching)', 'wptouch-pro' ),
					'template_tag' => __( 'Template tag', 'wptouch-pro' )
				)
			)			
		),
		$page_options
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Custom Code', 'wptouch-pro' ),
		'setup-custom-code',
		array(
			wptouch_add_setting( 
				'textarea', 
				'custom_stats_code', 
				__( 'HTML, JavaScript, statistics or custom code', 'wptouch-pro' ), 
				__( 'Enter any custom code here to be output in the theme footer.', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC, 
				'3.0'
			)
		),
		$page_options
	);	

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Custom Stylesheet', 'wptouch-pro' ),
		'setup-custom-styles',
		array(
			wptouch_add_setting( 
				'text', 
				'custom_css_file', 
				__( 'URL to a custom CSS file to load', 'wptouch-pro' ), 
				__( 'Useful if you have specific compatibility CSS you need to add.', 'wptouch-pro' ),
				WPTOUCH_SETTING_ADVANCED, 
				'3.0'
			)
		),
		$page_options
	);				
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Shortcodes', 'wptouch-pro' ),
		'shortcodes-compatibility',
		array(
			wptouch_add_setting( 
				'text', 
				'remove_shortcodes', 
				wptouchize_it( __( 'Remove these shortcodes when WPtouch Pro is active', 'wptouch-pro' ) ), 
				__( 'Enter a comma separated list of shortcodes to remove.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0' 
			)
		),
		$page_options
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Ignored URLs', 'wptouch-pro' ),
		'ignored-compatibility',
		array(
			wptouch_add_setting( 
				'textarea', 
				'ignore_urls', 
				wptouchize_it( __( 'Do not load WPtouch Pro on these URLs/Pages', 'wptouch-pro' ) ), 
				__( 'Each permalink URL fragment should be on its own line and relative, e.g. "/about" or "/products/store"', 'wptouch-pro' ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0' 
			)
		),
		$page_options
	);

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Desktop Theme', 'wptouch-pro' ),
		'desktop-theme-compatibility',
		array(
			wptouch_add_setting( 
				'checkbox', 
				'include_functions_from_desktop_theme', 
				__( 'Try to include desktop theme functions.php file', 'wptouch-pro' ), 
				wptouchize_it( __( 'This may be required for desktop themes with unique features that are not showing when WPtouch Pro is active.', 'wptouch-pro' ) ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting( 
				'radiolist',
				'functions_php_loading_method',
				__( 'Method to load file', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_ADVANCED,
				'3.0',
				array(
					'direct' => __( 'Include file directly', 'wptouch-pro' ),
					'translate' => __( 'Translate and create new files', 'wptouch-pro' )
				)
			)			
		),
		$page_options
	);
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Custom User-Agents', 'wptouch-pro' ),
		'custom-user-agents',
		array(
			wptouch_add_setting( 
				'textarea', 
				'custom_user_agents', 
				__( 'User-agents (line separated)', 'wptouch-pro' ), 
				wptouchize_it( __( 'Adding additional user-agents will force WPtouch Pro to be active for matching browsers.', 'wptouch-pro' ) ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0' 
			),
			wptouch_add_setting( 
				'user-agent-list',
				''
			)
		),
		$page_options
	);	

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Backup &amp; Import', 'wptouch-pro' ),
		'setup-backup',
		array(
			wptouch_add_setting( 
				'checkbox', 
				'automatically_backup_settings', 
				sprintf( __( 'Automatically backup settings to the %s folder', 'wptouch-pro' ), 
				'<em>/wptouch-data/backups</em>' ), 
				wptouchize_it( __( 'WPtouch Pro backups your settings each time they are saved.', 'wptouch-pro' ) ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0' 
			),
			wptouch_add_setting( 'custom', 'backup' )
		),
		$page_options
	);			

	$page_options = apply_filters( 'wptouch_settings_compat', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Tools &amp; Debug', 'wptouch-pro' ),
		'setup-tools-debug',
		array(
			wptouch_add_setting( 
				'checkbox', 
				'use_jquery_2', 
				__( 'Use jQuery 2.0 in themes (faster for mobile devices) instead of WordPress\' version', 'wptouch-pro' ), 
				__( 'jQuery 2.0 is significantly smaller and faster than previous jQuery versions - may cause problems with other plugins, use carefully.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting( 
				'checkbox', 
				'show_footer_load_times', 
				__( 'Show load times and query counts in the footer', 'wptouch-pro' ), 
				__( 'Helps you find slow pages/posts on your site.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting( 
				'checkbox', 
				'debug_log', 
				__( 'Enable debug log', 'wptouch-pro' ), 
				__( 'Creates a debug file to help diagnose installation issues.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0' 
			),
			wptouch_add_setting( 'debuginfo', 'debug-info', '', '', WPTOUCH_SETTING_ADVANCED, '3.0' )
		),	
		$page_options
	);	

	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'WordPress Plugins', 'wptouch-pro' ),
		'setup-general-plugin-compat',
		array(
			wptouch_add_setting( 
				'custom', 
				'plugin-compat'
			)
		),	
		$page_options
	);				
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_GENERAL,
		__( 'Admin Mode', 'wptouch-pro' ),
		'admin-mode',
		array(
			wptouch_add_setting( 
				'radiolist', 
				'settings_mode',
				__( 'Admin panel settings shown', 'wptouch-pro' ), 
				'',
				WPTOUCH_SETTING_BASIC, 
				'3.0', 
				array( 
					'0' => __( 'Default', 'wptouch-pro' ),
					'1' => __( 'Advanced', 'wptouch-pro' )
				)
			)
		),
		$page_options
	);		

	return $page_options;
}
