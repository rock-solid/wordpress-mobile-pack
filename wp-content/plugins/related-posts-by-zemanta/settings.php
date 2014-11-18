<?php

/**
* Add settings link to installed plugins list
**/
function zem_rp_add_link_to_settings($links) {
	return array_merge( array(
		'<a href="' . admin_url('admin.php?page=zemanta-related-posts') . '">' . __('Settings', 'zemanta_related_posts') . '</a>',
	), $links);
}
add_filter('plugin_action_links_' . ZEM_RP_PLUGIN_FILE, 'zem_rp_add_link_to_settings', 10, 2);

/**
* Place menu icons at admin head
**/
add_action('admin_head', 'zem_rp_admin_head');
function zem_rp_admin_head() {
	$menu_icon = plugins_url('static/img/menu_icon.png', __FILE__);
	$menu_icon_retina = plugins_url('static/img/menu_icon_2x.png', __FILE__);
?>
<style type="text/css">
#toplevel_page_zemanta-related-posts .wp-menu-image {
	background: url('<?php echo $menu_icon; ?>') 7px 6px no-repeat;
}
@media only screen and (-webkit-min-device-pixel-ratio: 1.5) {
	#toplevel_page_zemanta-related-posts .wp-menu-image {
		background-image: url('<?php echo $menu_icon_retina; ?>');
		background-size: 16px 17px;
	}
}
</style>
<?php
}


/**
* Settings
**/

add_action('admin_menu', 'zem_rp_settings_admin_menu');

function zem_rp_settings_admin_menu() {
	if (!current_user_can('delete_users')) {
		return;
	}

	$title = __('Related Posts by Zemanta', 'zemanta_related_posts');

	$page = add_options_page(__('Related Posts by Zemanta', 'zemanta_related_posts'), $title, 
							'manage_options', 'zemanta-related-posts', 'zem_rp_settings_page');
	add_action('admin_print_scripts-' . $page, 'zem_rp_settings_scripts');
}

function zem_rp_settings_scripts() {
	wp_enqueue_script('zem_rp_themes_script', plugins_url('static/js/themes.js', __FILE__), array('jquery'), ZEM_RP_VERSION);
	wp_enqueue_script("zem_rp_dashboard_script", plugins_url('static/js/dashboard.js', __FILE__), array('jquery'), ZEM_RP_VERSION);
	wp_enqueue_script("zem_rp_extras_script", plugins_url('static/js/extras.js', __FILE__), array('jquery'), ZEM_RP_VERSION);
}
function zem_rp_settings_styles() {
	wp_enqueue_style("zem_rp_dashaboard_style", plugins_url("static/css/dashboard.css", __FILE__), array(), ZEM_RP_VERSION);
}

function zem_rp_get_blog_email() {
	$meta = zem_rp_get_meta();
	if($meta['email']) return $meta["email"];
	return false;
}

function zem_rp_get_api_key() {
	$meta = zem_rp_get_meta();
	if($meta['zemanta_api_key']) return $meta['zemanta_api_key'];

	$zemanta_options = get_option('zemanta_options');
	if ($zemanta_options && !empty($zemanta_options['api_key'])) {
		$meta['zemanta_api_key'] = $zemanta_options['api_key'];
		zem_rp_update_meta($meta);
		return $meta['zemanta_api_key'];
	}
	return false;
}

function zem_rp_subscribe($email_or_unsubscribe, $subscription_types) {
    $meta = zem_rp_get_meta();
	$options = zem_rp_get_options();
	if (! $subscription_types) {
		if ($email_or_unsubscribe) { return false; }
		$subscription_types = "activityreport,newsletter";
	}

	if (! $meta['subscribed'] && $meta['email'] && !$email_or_unsubscribe) {
		// Not processed yet
		$meta['email'] = false;
		$options['subscription_types'] = false;
		zem_rp_update_meta($meta);
		zem_rp_update_options($options);
		return true;
	}
	
	if($meta['zemanta_api_key']) {
		$post = array(
			'api_key' => $meta['zemanta_api_key'],
			'platform' => 'wordpress-zem',
			'url' => get_site_url(),
			'subscriptions' => $subscription_types
		);

		if ($email_or_unsubscribe) {
			$post['email'] = $email_or_unsubscribe;
		}
		$response = wp_remote_post(ZEM_RP_ZEMANTA_SUBSCRIPTION_URL . 'subscribe/', array(
			'body' => $post,
			'timeout' => 30
		));
		if (wp_remote_retrieve_response_code($response) == 200) {
			$body = wp_remote_retrieve_body($response);
			if ($body) {
				$response_json = json_decode($body);
				
				if ($response_json->status !== 'ok') {
					$waiting = $response_json->reason == 'user-missing';
					if ($email_or_unsubscribe && $waiting) {
						$meta['email'] = $email_or_unsubscribe;
						$meta['subscribed'] = false;
						$options['subscription_types'] = $subscription_types;
						zem_rp_update_meta($meta);
						zem_rp_update_options($options);
						return true;
// We will try again when 
					}
					return false;
				}
				$meta['email'] = $email_or_unsubscribe;
				$meta['subscribed'] = (int) !!$email_or_unsubscribe;
				$options['subscription_types'] = $subscription_types;
				zem_rp_update_meta($meta);
				zem_rp_update_options($options);
				return true; // don't subscribe to bf if zem succeeds
			}
		}
	}
	return false;
}

function zem_rp_ajax_subscribe_callback () {
	check_ajax_referer('zem_rp_ajax_nonce');
	$email = (!empty($_POST['email']) && $_POST['email'] !== '0') ? $_POST['email'] : false;
	$types = empty($_POST['subscription']) ? array() : explode(",", $_POST['subscription']);
	$valid_types = array();
	foreach($types as $tp) {
		if ($tp && in_array($tp, array('activityreport', 'newsletter'))) {
			$valid_types[] = $tp;
		}
	}
	$valid_types = $valid_types ? implode(',', $valid_types) : false;
	if (zem_rp_subscribe($email, $valid_types)) {
		print "1";
	}
	else {
		print "0";
	}
	die();
}

add_action('wp_ajax_zem_subscribe', 'zem_rp_ajax_subscribe_callback');
  

function zem_rp_ajax_dismiss_notification_callback() {
	check_ajax_referer('zem_rp_ajax_nonce');

	if(isset($_REQUEST['id'])) {
		zem_rp_dismiss_notification((int)$_REQUEST['id']);
	}
	if(isset($_REQUEST['noredirect'])) {
		die('ok');
	}
	wp_redirect(admin_url('admin.php?page=wordpress-related-posts'));
}

add_action('wp_ajax_rp_dismiss_notification', 'zem_rp_ajax_dismiss_notification_callback');


function zem_rp_register() {
	$meta = zem_rp_get_meta();
	if ($meta['registered']) {
		return;
	}
	$api_key = zem_rp_get_api_key();
	if(! $api_key) {
		$wprp_zemanta = new WPRPZemanta();
		$wprp_zemanta->init(); // we have to do this manually because the admin_init hook was already triggered
		$wprp_zemanta->register_options();
		
		$api_key = $wprp_zemanta->api_key;
		$meta['zemanta_api_key'] = $api_key;
	}
	if (!$api_key) { return false; }

	$url = urlencode(get_bloginfo('wpurl'));
	$post = array(
		'api_key' => $api_key,
		'platform' => 'wordpress-zem',
		'post_rid' => '',
		'post_url' => $url,
		'current_url' => $url,
		'format' => 'json',
		'method' => 'zemanta.post_published_ping'
	);
	$response = wp_remote_post(ZEM_RP_ZEMANTA_API_URL, array(
		'body' => $post,
		'timeout' => 30
	));
	if (wp_remote_retrieve_response_code($response) == 200) {
		$body = wp_remote_retrieve_body($response);
		if ($body) {
			$response_json = json_decode($body);
			$meta['registered'] = $response_json->status === 'ok';
		}
	}

	zem_rp_update_meta($meta);
	return $meta['registered'];
}

function zem_rp_settings_page() {
	if (!current_user_can('delete_users')) {
		die('Sorry, you don\'t have permissions to access this page.');
	}
	zem_rp_register();

	$options = zem_rp_get_options();
	$meta = zem_rp_get_meta();

	if ($meta['email'] && !$meta['subscribed']) {
		zem_rp_subscribe($meta['email']);
	}
	
	if (isset( $_GET['zem_global_notice'] ) && $_GET['zem_global_notice'] === '0') {
		$meta['global_notice'] = null;
		zem_rp_update_meta($meta);
	}
	
	$postdata = stripslashes_deep($_POST);

	if(sizeof($_POST)) {
		if (!isset($_POST['_zem_rp_nonce']) || !wp_verify_nonce($_POST['_zem_rp_nonce'], 'zem_rp_settings') ) {
			die('Sorry, your nonce did not verify.');
		}

		$old_options = $options;
		$new_options = array(
			'on_single_post' => isset($postdata['zem_rp_on_single_post']),
			'max_related_posts' => (isset($postdata['zem_rp_max_related_posts']) && is_numeric(trim($postdata['zem_rp_max_related_posts']))) ? intval(trim($postdata['zem_rp_max_related_posts'])) : 5,
			'on_rss' => isset($postdata['zem_rp_on_rss']),
			'related_posts_title' => isset($postdata['zem_rp_related_posts_title']) ? trim($postdata['zem_rp_related_posts_title']) : '',
			'max_related_post_age_in_days' => (isset($postdata['zem_rp_max_related_post_age_in_days']) && is_numeric(trim($postdata['zem_rp_max_related_post_age_in_days']))) ? intval(trim($postdata['zem_rp_max_related_post_age_in_days'])) : 0,
			'custom_size_thumbnail_enabled' => isset($postdata['zem_rp_custom_size_thumbnail_enabled']) && $postdata['zem_rp_custom_size_thumbnail_enabled'] === 'yes',
			'custom_thumbnail_width' => isset($postdata['zem_rp_custom_thumbnail_width']) ? intval(trim($postdata['zem_rp_custom_thumbnail_width'])) : ZEM_RP_CUSTOM_THUMBNAILS_WIDTH ,
			'custom_thumbnail_height' => isset($postdata['zem_rp_custom_thumbnail_height']) ? intval(trim($postdata['zem_rp_custom_thumbnail_height'])) : ZEM_RP_CUSTOM_THUMBNAILS_HEIGHT,

			'thumbnail_use_custom' => isset($postdata['zem_rp_thumbnail_use_custom']),
			'thumbnail_custom_field' => isset($postdata['zem_rp_thumbnail_custom_field']) ? trim($postdata['zem_rp_thumbnail_custom_field']) : '',
			'display_zemanta_linky' => isset($postdata['zem_rp_display_zemanta_linky']),
			'only_admins_can_edit_related_posts' => !empty($postdata['zem_rp_only_admins_can_edit_related_posts']),
			'desktop' => array(
				'display_thumbnail' => isset($postdata['zem_rp_desktop_display_thumbnail']),
				'display_comment_count' => isset($postdata['zem_rp_desktop_display_comment_count']),
				'display_publish_date' => isset($postdata['zem_rp_desktop_display_publish_date']),
				'display_excerpt' => isset($postdata['zem_rp_desktop_display_excerpt']),
				'excerpt_max_length' => (isset($postdata['zem_rp_desktop_excerpt_max_length']) && is_numeric(trim($postdata['zem_rp_desktop_excerpt_max_length']))) ? intval(trim($postdata['zem_rp_desktop_excerpt_max_length'])) : 200,
				'custom_theme_enabled' => isset($postdata['zem_rp_desktop_custom_theme_enabled'])
			)
		);

		if(!isset($postdata['zem_rp_exclude_categories'])) {
			$new_options['exclude_categories'] = '';
		} else if(is_array($postdata['zem_rp_exclude_categories'])) {
			$new_options['exclude_categories'] = join(',', $postdata['zem_rp_exclude_categories']);
		} else {
			$new_options['exclude_categories'] = trim($postdata['zem_rp_exclude_categories']);
		}

		foreach (array('desktop') as $platform) {
			if(isset($postdata['zem_rp_' . $platform . '_theme_name'])) {		// If this isn't set, maybe the AJAX didn't load...
				$new_options[$platform]['theme_name'] = trim($postdata['zem_rp_' . $platform . '_theme_name']);

				if(isset($postdata['zem_rp_' . $platform . '_theme_custom_css'])) {
					$new_options[$platform]['theme_custom_css'] = $postdata['zem_rp_' . $platform . '_theme_custom_css'];
				} else {
					$new_options[$platform]['theme_custom_css'] = '';
				}
			} else {
				$new_options[$platform]['theme_name'] = $old_options[$platform]['theme_name'];
				$new_options[$platform]['theme_custom_css'] = $old_options[$platform]['theme_custom_css'];
			}
		}

		if (isset($postdata['zem_classic_state'])) {
			$meta['classic_user'] = true;
		} else {
			$meta['classic_user'] = false;
		}
		zem_rp_update_meta($meta);

		$default_thumbnail_path = zem_rp_upload_default_thumbnail_file();

		if($default_thumbnail_path === false) { // no file uploaded
			if(isset($postdata['zem_rp_default_thumbnail_remove'])) {
				$new_options['default_thumbnail_path'] = false;
			} else {
				$new_options['default_thumbnail_path'] = $old_options['default_thumbnail_path'];
			}
		} else if(is_wp_error($default_thumbnail_path)) { // error while upload
			$new_options['default_thumbnail_path'] = $old_options['default_thumbnail_path'];
			zem_rp_add_admin_notice('error', $default_thumbnail_path->get_error_message());
		} else { // file successfully uploaded
			$new_options['default_thumbnail_path'] = $default_thumbnail_path;
		}

		if (((array) $old_options) != $new_options) {
			if(!zem_rp_update_options($new_options)) {
				zem_rp_add_admin_notice('error', __('Failed to save settings.', 'zemanta_related_posts'));
			} else {
				zem_rp_add_admin_notice('updated', __('Settings saved.', 'zemanta_related_posts'));
			}
		} else {
			// I should duplicate success message here
			zem_rp_add_admin_notice('updated', __('Settings saved.', 'zemanta_related_posts'));
		}
	}

	$settings_file = __FILE__;
	$blog_url = get_site_url();
	
	include zem_rp_get_template('settings');
}

