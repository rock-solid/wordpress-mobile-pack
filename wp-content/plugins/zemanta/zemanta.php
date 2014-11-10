<?php
/*
Copyright (c) 2007 - 2012, Zemanta Ltd.
The copyrights to the software code in this file are licensed under the (revised) BSD open source license.

Plugin Name: Editorial Assistant by Zemanta
Plugin URI: http://wordpress.org/extend/plugins/zemanta/
Description: Contextual suggestions of related posts, images and tags that makes your blogging fun and efficient.
Version: 1.2.7
Author: Zemanta Ltd.
Author URI: http://www.zemanta.com/
Contributers: Kevin Miller (http://www.p51labs.com), Andrej Mihajlov (http://codeispoetry.ru/)
*/

define('ZEMANTA_PLUGIN_VERSION_OPTION', 'zemanta_plugin_version');
define('ZEMANTA_PLUGIN_FLASH_META', 'zemanta_plugin_flash');

if(!class_exists('WP_Http')) {
	require_once(ABSPATH . WPINC . '/class-http.php');
}

require_once(ABSPATH . 'wp-admin/includes/image.php');

$zemanta = new Zemanta();

/**
* zemanta_get_api_key
*
* Helper function to return api key
*
* @return string
*/
function zemanta_get_api_key() {
	global $zemanta;

	return $zemanta->get_api_key();
}

// Add getty as provider
//wp_oembed_add_provider( 'http://gty.im/*', 'http://embed.gettyimages.com/oembed' );

add_action( 'init', 'zemanta_add_oembed_handlers' );
function zemanta_add_oembed_handlers() {
	wp_oembed_add_provider( 'http://gty.im/*', 'http://embed.gettyimages.com/oembed' );
}

class Zemanta {

	var $version = '1.2.7';
	var $api_url = 'http://api.zemanta.com/services/rest/0.0/';
	var $api_key = '';
	var $options = array();
	var $supported_features = array();
	var $update_notes = array();
	var $flash_data = null;
	var $menu_slug = null;

	public function __construct()
	{
		global $wp_version;
		
		// initialize update notes shown once on plugin update
		$this->update_notes['1.0.5'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		$this->update_notes['1.0.7'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		$this->update_notes['1.0.8'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		
		add_action('admin_init', array($this, 'init'));
		add_action('admin_init', array($this, 'register_options'));
		add_action('admin_menu', array($this, 'add_options'));
		add_action('admin_menu', array($this, 'add_meta_box'));
		
		register_activation_hook(dirname(__FILE__) . '/zemanta.php', array($this, 'activate'));
		
		$this->supported_features['featured_image'] = version_compare($wp_version, '3.1', '>=') >= 0;
		
		// check if we use pro plugin and load pro settings
		$this->check_pro_settings();
	}

	/**
	* admin_init
	*
	* Initialize plugin
	*
	*/
	public function init() 
	{
		add_action('wp_ajax_zemanta_set_featured_image', array($this, 'ajax_zemanta_set_featured_image'));
		add_action('edit_form_advanced', array($this, 'assets'), 1);
		add_action('edit_page_form', array($this, 'assets'), 1);
		add_action('save_post', array($this, 'save_post'), 20);
		
		$this->load_flashdata();
		add_action('shutdown', array($this, 'save_flashdata'));
		
		$this->check_plugin_updated();
		$this->create_options();
		$this->check_options();
		
		if(!$this->check_dependencies())
			add_action('admin_notices', array($this, 'warning'));
		
		add_action('admin_notices', array($this, 'plugin_update_notice'));
	}

	/**
	* activate
	*
	* Run any functions needed for plugin activation
	*/
	public function activate() 
	{
		$this->fix_user_meta();
	}

	/**
	* admin_head
	*
	* Add any assets to the edit page
	*/
	public function assets() 
	{	
		$this->render('assets', array(
			'api_key' => $this->api_key,
			'version' => $this->version,
			'features' => $this->supported_features
		));
	}

	/**
	* warning for no api key
	*
	* Display api key warning
	*/
	public function warning_no_api_key()
	{
		$this->render('message', array(
			'type' => 'error'
			,'message' => __('You have no Zemanta API key and the plugin was unable to retrieve one. You can still use Zemanta, '.
			'but until the new key is successfully obtained you will not be able to customize the widget or remove '.
			'this warning. You may try to deactivate and activate the plugin again to make it retry to obtain the key.', 'zemanta')
			));
	}

	/**
	* warning
	*
	* Display plugin warning
	*/
	public function warning()
	{
		$this->render('message', array(
			'type' => 'updated fade'
			,'message' => __('Zemanta needs either the cURL PHP module or allow_url_fopen enabled to work. Please ask your server administrator to set either of these up.', 'zemanta')
			));
	}

	/**
	* plugin_update_notice
	*
	* Display plugin update notice if available
	*/
	public function plugin_update_notice()
	{
		global $pagenow;

		$message = $this->flashdata('plugin_update_notice');

		if($message) 
		{
			// keep update message on update and plugins page because they do many redirects, 
			// so we never know whether user seen the message or not
			if($pagenow == 'update.php' || ($pagenow == 'plugins.php' && isset($_GET['action'])))
				$this->keep_flashdata('plugin_update_notice');
			
			$this->render('message', array(
				'type' => 'updated fade', 
				'message' => $message)
			);
		}
	}

	/**
	* add_options
	*
	* Add configuration page to menu
	*/
	public function add_options() 
	{
		$this->menu_slug = add_options_page(
			__('Zemanta', 'zemanta'), 
			__('Zemanta', 'zemanta'), 
			'manage_options', 'zemanta', 
			array($this, 'options'), 
			plugins_url('/img/menu_icon.png', __FILE__)
		);
	}

	/**
	* check_options
	*
	* Check to see if we need to create or import options
	*/
	public function check_options()
	{
		$this->api_key = $this->get_api_key();

		if (!$this->api_key) 
		{
			$options = get_option('zemanta_options');

			if (!$options)
			{
				$options = $this->legacy_options($options);
			}

			$this->api_key = $this->get_api_key();
			if (!$this->api_key) 
			{
				$this->api_key = $this->fetch_api_key();
				if ($this->api_key) 
				{
					$this->set_api_key($this->api_key);
				} 
				else 
				{
					add_action('admin_notices', array($this, 'warning_no_api_key'));
				}
			}
		}
	}

	/**
	* create_options
	*
	* Create the Initial Options
	*/
	public function create_options()
	{
		$wp_upload_dir = wp_upload_dir();
		$options = array(
			'zemanta_option_api_key' => array(
				'type' => 'apikey'
				,'title' => __('Your API key (in case you need to contact support)', 'zemanta')
				,'field' => 'api_key'
				,'default_value' => $this->api_key
				)
			,'zemanta_option_image_upload' => array(
				'type' => 'checkbox'
				,'title' => __('Automatically upload inserted images to your blog', 'zemanta')
				,'field' => 'image_uploader'
				//,'description' => __('Using Zemanta image uploader in this way may download copyrighted images to your blog. Make sure you and your blog writers check and understand licenses of each and every image before using them in your blog posts and delete them if they infringe on author\'s rights.')
				)
		);


		// @deprecated enable custom path only for old users
		if($this->is_uploader_custom_path()) {
			$options += array(
				'zemanta_option_image_uploader_custom_path' => array(
					'type' => 'checkbox'
					,'title' => __('Use a custom path to store your images', 'zemanta')
					,'field' => 'image_uploader_custom_path'
					//,'description' => __('Use a custom path to store your images?')
				),
				'zemanta_option_image_upload_dir' => array(
					'type' => 'path'
					//,'title' => ''
					,'field' => 'image_uploader_dir'
					,'description' => ($wp_upload_dir['error'] !== false ? 'wp-content/uploads' : str_replace(ABSPATH, '', $wp_upload_dir['basedir'])) . '/'
					,'default_value' => ''
				)
			);
		}
		
		$this->options = apply_filters('zemanta_options', $options);
	}

	/**
	* register_options
	*
	* Register options with Settings API
	*/
	public function register_options() {
		register_setting('zemanta_options', 'zemanta_options', array($this, 'validate_options'));

		add_settings_section('zemanta_options_plugin', null, array($this, 'callback_options_dummy'), 'zemanta');
		add_settings_field('zemanta_option_api_key', 'Your API key', array($this, 'options_set'), 'zemanta', 'zemanta_options_plugin', $this->options['zemanta_option_api_key']);

		add_settings_section('zemanta_options_image', null, array($this, 'callback_options_dummy'), 'zemanta');
		add_settings_field('zemanta_option_image_upload', 'Enable image uploader', array($this, 'options_set'), 'zemanta', 'zemanta_options_image', $this->options['zemanta_option_image_upload']);
		
		// @deprecated enable for old users only
		if($this->is_uploader_custom_path()) {
			add_settings_field('zemanta_option_image_uploader_custom_path', 'Enable custom path', array($this, 'options_set'), 'zemanta', 'zemanta_options_image', $this->options['zemanta_option_image_uploader_custom_path']);
			add_settings_field('zemanta_option_image_upload_dir', 'Store uploads in this folder', array($this, 'options_set'), 'zemanta', 'zemanta_options_image', $this->options['zemanta_option_image_upload_dir']);
		}
	}

	/**
	* callback_options_dummy
	*
	* Dummy callback for add_settings_sections
	*/
	public function callback_options_dummy() {
	}

	/**
	* options_set
	*
	* Output the fields for the options
	*/
	public function options_set($option = null) {
		// WordPress < 2.9 has a bug where the settings callback is not passed the arguments value so we check for it here.
		if ($option == null) {
			$option = array_shift($this->options);
		}

		$this->render('options-input-' . $option['type'], array(
			'option' => $this->get_option($option['field']),
			'field' => $option['field'],
			'title' => isset($option['title']) ? $option['title'] : null,
			'default_value' => isset($option['default_value']) ? $option['default_value'] : null,
			'description' => isset($option['description']) ? $option['description'] : null,
			// @TODO: temporary solution
			'disabled' => $this->is_pro()
		));
	}

	/**
	* validate_options
	*
	* Handle input Validation
	*/
	public function validate_options($input) {
		// @deprecated only used by old users
		if(isset($input['image_uploader_dir']))
			$input['image_uploader_dir'] = trim($input['image_uploader_dir'], '\\/');

		return $input;
	}

	/**
	* options
	*
	* Add configuration page
	*/
	public function options() {
		// @TODO: what's the difference between regular settings and pro settings?
		// if ($this->is_pro()) 
		// 		{
		// 			return zem_pro_wp_admin();
		// 		}

		if($this->is_uploader_enabled()) {
			$upload_dir = $this->image_upload_dir();
			
			if(is_wp_error($upload_dir)) {
				$this->render('message', array(
					'type' => 'error',
					'message' => sprintf(__('%s Zemanta will not be able to upload images.', 'zemanta'), $upload_dir->get_error_message())
				));
			}
			else if($this->get_option('image_uploader_dir') && !is_writable($upload_dir)) {
				$this->render('message', array(
					'type' => 'error',
					'message' => sprintf(__('Your upload directory (%s) cannot be written to. Zemanta will not be able to upload images there.', 'zemanta'), $upload_dir)
				));
			} 
		}

		if(!$this->api_key) {
			$this->api_key = $this->fetch_api_key();

			$this->set_option('api_key', $this->api_key);
		}

		$this->render('options', array(
			'api_key' => $this->api_key,
			'is_pro' => $this->is_pro()
		));
	}

	/**
	* image_upload_dir
	* 
	* Add configuration page
	*/
	public function image_upload_dir() {
		$wp_upload_dir = wp_upload_dir();
		
		if($wp_upload_dir['error'] !== false)
			return new WP_Error('create_upload_dir', $wp_upload_dir['error']);

		if($this->is_uploader_enabled() && $this->is_uploader_custom_path()) 
		{
			$upload_dir = $this->get_option('image_uploader_dir');
			return untrailingslashit($wp_upload_dir['basedir'] . '/' . str_replace('\\', '/', $upload_dir));
		} 

		return $wp_upload_dir['path'];
	}

	/**
	* image_upload_url
	*
	* Add configuration page
	*/
	public function image_upload_url() {
		$wp_upload_dir = wp_upload_dir();
		
		if($wp_upload_dir['error'] !== false)
			return new WP_Error('create_upload_dir', $wp_upload_dir['error']);

		if($this->is_uploader_enabled() && $this->is_uploader_custom_path()) 
		{
			$dir = $this->get_option('image_uploader_dir');
			return untrailingslashit($wp_upload_dir['baseurl'] . '/' . str_replace('\\', '/', $dir));
		}
		return $wp_upload_dir['url'];
	}

	/**
	* filesystem_method
	*
	* Change WP_Filesystem method to direct for this plugin
	*
	* @param string $method File System Method
	*/
	public function filesystem_method($method) {
		return 'direct';
	}

	/**
	* upload_image
	*
	* Add configuration page
	*/
	public function upload_image($url) {
		global $wp_filesystem;

		$upload_dir = $this->image_upload_dir();
		
		if(is_wp_error($upload_dir))
			return false;

		//decode before sanitizing, wp_unique_filename includes a call to sanitize_file_name
		$safe_filename = $this->properly_sanitize_image_filename(urldecode(basename($url)));
		$file_name = wp_unique_filename($upload_dir, $safe_filename); 
		$file_path = $upload_dir . '/' . $file_name;

		if(!file_exists($file_path)) {
			$http_response = wp_remote_get($url, array('timeout' => 10));

			if(is_wp_error($http_response))
				return false;
				
			$data = wp_remote_retrieve_body($http_response);

			add_filter('filesystem_method', array($this, 'filesystem_method'));

			WP_Filesystem();

			if (!$wp_filesystem->put_contents($file_path, $data, FS_CHMOD_FILE)) {
				return false;
			}

			return $file_name;
		}

		return false;
	}

	/**
	* Get safe image file name - use the same function used to sanitize post url
	*/
	public function properly_sanitize_image_filename($filename) {
		//partly copied from WP 3.8.1/wp-includes/functions.php
		$info = pathinfo($filename);
		$ext = !empty($info['extension']) ? '.' . $info['extension'] : '';
		$name = basename($filename, $ext);

		return sanitize_title($name) . $ext;
	}

	/**
	* is_uploader_enabled
	*
	*/
	public function is_uploader_enabled() {
		return $this->get_option('image_uploader');
	}

	/**
	* is_uploader_custom_path
	*
	*/
	public function is_uploader_custom_path() {
		return $this->get_option('image_uploader_custom_path');
	}

	/**
	* save_post
	*
	* Download images if necessary and update post
	*/
	public function save_post($post_id) {
		// do not process revisions, autosaves and auto-drafts
		if(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id) || get_post_status($post_id) == 'auto-draft' || isset($_POST['autosave']))
			return;

		$image_upload_dir = $this->image_upload_dir();
		$image_upload_url = $this->image_upload_url();
		$site_url = get_site_url();
		
		// do not process if uploader disabled or upload path or url are broken
		if(!$this->is_uploader_enabled() || is_wp_error($image_upload_dir) || is_wp_error($image_upload_url))
			return;
		
		$content = isset($_POST['post_content']) ? stripslashes($_POST['post_content']) : "";
		$nlcontent = str_replace("\n", "", $content);
		$urls = array();
		$descs = array();

		while(true) 
		{
			$matches = $this->match("/<div[^>]+zemanta-img[^>]+>.+?<\/div>/", $nlcontent);
			
			if(!sizeof($matches))
				break;

			$srcurl = $this->match('/src="([^"]+)"/', $matches[0]);
			$desc = $this->match('/href="([^"]+)"/', $matches[0]);
			$urls[] = $srcurl[1];
			$descs[] = $desc[1];
			$nlcontent = substr($nlcontent, strpos($nlcontent, $matches[0]) + strlen($matches[0]));
		}

		$nlcontent = str_replace("\n", "", $content);
		
		while(true) {
			$matches = $this->match('/<img .*?src="[^"]+".*?>/', $nlcontent);
			
			if(!sizeof($matches))
				break;
			
			$srcurl = $this->match('/src="([^"]+)"/', $matches[0]);
			if(!in_array($srcurl[1], $urls)) {
				$desc = $this->match('/alt="([^"]+)"/', $matches[0]);
				$urls[] = $srcurl[1];
				$descs[] = strlen($desc[1]) ? $desc[1] : $srcurl[1];
			}
			$nlcontent = substr($nlcontent, strpos($nlcontent, $matches[0]) + strlen($matches[0]));
		}
		
		if(!sizeof($urls))
			return;
		
		for($i = 0, $c = sizeof($urls); $i < $c; $i++) {
			$url = $urls[$i];
			$desc = $descs[$i];
			
			if (strpos($url, $site_url) !== false || strpos($url, '//img.zemanta.com/') !== false)
				continue;
			
			$file_name = $this->upload_image($url);

			if ($file_name !== false) {
				$localurl = $image_upload_url . '/' . $file_name;
				$localfile = $image_upload_dir . '/' . $file_name;
				$wp_filetype = wp_check_filetype($file_name, null);
				
				$content = str_replace($url, $localurl, $content);
				
				$attach_id = wp_insert_attachment(array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
					'post_content' => '',
					'post_status' => 'inherit',
					'guid' => $localurl
				), $localfile, $post_id);

				$attach_data = wp_generate_attachment_metadata($attach_id, $localfile);
				wp_update_attachment_metadata($attach_id, $attach_data);
			}
		}

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', array($this, 'save_post'), 20);
		
		// put modified content back to _POST so other plugins can reuse it
		$_POST['post_content'] = addslashes($content);

		// update post in database
		wp_update_post(array(
			'ID' => $post_id, 
			'post_content' => $content)
		);

		// re-hook this function
		add_action('save_post', array($this, 'save_post'), 20);
	}

	/**
	* api
	*
	* API Call
	*
	* @param array $arguments Arguments to pass to the API
	*/
	public function api($arguments) {
		$arguments = array_merge($arguments, array(
			'api_key'=> $this->api_key
			));
		
		if (!isset($arguments['format'])) {
			$arguments['format'] = 'xml';
		}
		
		return wp_remote_post($this->api_url, array('method' => 'POST', 'body' => $arguments));
	}


	/**
	* ajax_error
	* 
	* Helper function to throw WP_Errors to ajax as json
	*/
	public function ajax_error($wp_error) {
		if(is_wp_error($wp_error)) {
			die(json_encode(array(
				'error' => array(
					'code' => $wp_error->get_error_code(),
					'message' => $wp_error->get_error_message(),
					'data' => $wp_error->get_error_data()
				)
			)));
		}
	}

	/**
	* ajax_zemanta_set_featured_image
	*
	* Download and set featured image by URL
	* @require WordPress 3.1+
	*/
	public function ajax_zemanta_set_featured_image() {
		global $post_ID;
		
		if(!isset($this->supported_features['featured_image'])) {
			$this->ajax_error(new WP_Error(4, __('Featured image feature is not supported on current platform.', 'zemanta')));
		}

		$args = wp_parse_args($_REQUEST, array('post_id' => 0, 'image_url' => ''));
		extract($args);

		$post_id = (int)$post_id;
		
		if(!empty($image_url) && $post_id) {
			$http_response = wp_remote_get($image_url, array('timeout' => 10));

			if(!is_wp_error($http_response)) {
				$data = wp_remote_retrieve_body($http_response);
				
				// throw error if there no data
				if(empty($data)) {
					$this->ajax_error(new WP_Error(5, __('Featured image has invalid data.', 'zemanta')));
				}

				$upload = wp_upload_bits(basename($image_url), null, $data);

				if(!is_wp_error($upload) && !$upload['error']) 
				{
					$filename = $upload['file'];
					$wp_filetype = wp_check_filetype(basename($filename), null );
					$attachment = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
						'post_content' => '',
						'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment($attachment, $filename, $post_id);
					$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
					wp_update_attachment_metadata($attach_id, $attach_data);
					
					// this is necessary, or _wp_post_thumbnail_html returns broken remove link
					$post_ID = $post_id;
					
					// set_post_thumbnail available only since WordPress 3.1
					if(set_post_thumbnail($post_id, $attach_id)) {
						die(json_encode(array(
								// _wp_post_thumbnail_html is private function but we really need it to behave natively
								'html' => _wp_post_thumbnail_html($attach_id), // call WPSetThumbnailHTML(html) from javascript
								'attach_id' => $attach_id // call WPSetThumbnailID(attach_id) from javascript
							))
						);
					} else {
						$this->ajax_error(new WP_Error(1, __('An unexpected error occurred.', 'zemanta')));
					}
				} else {
					$this->ajax_error(new WP_Error(2, sprintf(__('An upload error occurred: %s', 'zemanta'), $upload->get_error_message())));
				}
			} else {
				$this->ajax_error(new WP_Error(3, sprintf(__('An error occurred while image download: %s', 'zemanta'), $http_response->get_error_message())));
			}
		}

		die(0);
	}

	/**
	* fetch_api_key
	*
	* Get API Key
	*/
	public function fetch_api_key() 
	{
		if($this->is_pro()) 
		{
			return '';
		}

		$response = $this->api(array(
			'method' => 'zemanta.auth.create_user',
			'partner_id' => 'wordpress-ea'
			));

		if(!is_wp_error($response))
		{
			$matches = $this->match('/<status>(.+?)<\/status>/', $response['body']);

			if ($matches[1] == 'ok') 
			{
				$matches = $this->match('/<apikey>(.+?)<\/apikey>/', $response['body']);

				return $matches[1];
			}
		}

		return '';
	}

	/**
	* add_meta_box
	*
	* Adds meta box to posts/pages
	*/
	public function add_meta_box() 
	{
		if (function_exists('add_meta_box')) 
		{
			add_meta_box('zemanta-wordpress', __('Content Recommendations', 'zemanta'), array($this, 'shim'), 'post', 'side', 'high');
			add_meta_box('zemanta-wordpress', __('Content Recommendations', 'zemanta'), array($this, 'shim'), 'page', 'side', 'high');
		}
	}

	/**
	* shim
	*
	* Adds Shim to Edit Page for Zemanta Plugin
	*/
	public function shim() 
	{
		echo '<div id="zemanta-sidebar"></div>';
	}

	/**
	* match
	*
	* Backwards Compatible Regex Matching
	* 
	* @param string $rstr Regular Expression
	* @param string $str String to match against
	* 
	* @return array
	*/
	protected function match($rstr, $str) 
	{
		if (function_exists('preg_match'))
		{
			preg_match($rstr, $str, $matches);
		}
		elseif (function_exists('ereg'))
		{
			ereg($rstr, $str, $matches);
		}
		else
		{
			$matches = array('', '');
		}

		return $matches;
	}

	/**
	* legacy_options
	*
	* Get Options from Legacy Options if available
	*/
	protected function legacy_options($options)
	{
		if (empty($this->options))
		{
			return false;
		}

		foreach ($this->options as $option => $details)
		{
			$old_option = get_option('zemanta_' . $details['field']);

			if ($old_option && !isset($options[$details['field']]))
			{
				$options[$details['field']] = $old_option == 'on' ? 1 : $old_option;
			}
		}

		update_option('zemanta_options', $options);

		return get_option('zemanta_options');
	}

	/**
	* get_option
	*
	* Get Option
	*
	* @param string $name Name of option to retrieve
	*/
	protected function get_option($name)
	{
		// @TODO: find better solution
		if($this->is_pro())
			return $this->get_pro_option($name);
		
		$options = get_option('zemanta_options');

		return isset($options[$name]) ? $options[$name] : null;
	}

	/**
	* get_pro_option
	*
	* Get pro plugin option
	* @param string $name Name of option to retrieve from prosettings.php
	*/
	protected function get_pro_option($name)
	{
		$const_name = 'zemanta_' . $name;

		// just to keep compatibility
		if($name == 'api_key')
			$const_name = strtoupper($const_name);

		if(defined($const_name)) {
			$val = constant($const_name);
			if($const_name == 'zemanta_image_uploader') {
				return is_bool($val) ? $val : $val === 'on';
			}
		}

		return false;
	}

	/**
	* set_option
	*
	* Set option
	*
	* @param string $name Name of option to set
	* @param string $value Value of option
	*/
	protected function set_option($name, $value) 
	{
		// @TODO: find better solution
		if ($this->is_pro())
			return $this->set_pro_option($name, $value);

		$options = get_option('zemanta_options');

		if ($value === null)
		{
			unset($options[$name]);
		}
		else
		{
			$options[$name] = $value;
		}

		return update_option('zemanta_options', $options);
	}

	/**
	* set_pro_option
	*
	* Set pro plugin option
	*
	* @param string $name Name of option to set
	* @param string $value Value of option
	*/
	protected function set_pro_option($name, $value) 
	{
		// @TODO: implement or drop
		return true;
	}

	/**
	* get_api_key
	*
	* Get API Key
	*/ 
	public function get_api_key()
	{
		if($this->is_pro())
			return constant('ZEMANTA_API_KEY');

		return $this->get_option('api_key');
	}

	/**
	* set_api_key
	*
	* Get API Key
	*
	* @param string $api_key API Key to set
	*/ 
	protected function set_api_key($api_key) 
	{
		if ($this->is_pro())
		{
			// @TODO: do we really change API key for PRO users?
			return;// zem_set_pro_api_key($api_key);
		}

		$this->set_option('api_key', $api_key);
	}

	/**
	* is_pro
	*
	* Check if the plugin upgraded to PRO
	*/
	protected function is_pro() 
	{
		return defined('ZEMANTA_API_KEY');
	}

	/**
	*
	* Load Zemanta pro plugin settings
	*
	*/
	protected function check_pro_settings()
	{
		if(file_exists(dirname(__FILE__) . '/APIKEY.php'))
			require_once(dirname(__FILE__) . '/APIKEY.php');

		if(file_exists(dirname(__FILE__) . '/prosettings.php'))
			require_once(dirname(__FILE__) . '/prosettings.php');

		if (!defined("ZEMANTA_API_KEY"))
			return false;
		else
			return array('api_key' => constant('ZEMANTA_API_KEY'));
	}

	/**
	* fix_user_meta
	*
	* If WP > 3.0 remove Zemanta User Meta
	*/
	protected function fix_user_meta()
	{
		if (function_exists('delete_user_meta')) 
		{ 
			global $wpdb;

			if (method_exists($wpdb, 'esc_like')) {
				// If this is WP 4.0 ?
				$prefix = $wpdb->esc_like($wpdb->base_prefix);
			}
			else {
				// Fallback on deprecated function
				$prefix = like_escape($wpdb->base_prefix);
			}

			$r = $wpdb->get_results("SELECT user_id, meta_key FROM $wpdb->usermeta WHERE meta_key LIKE '{$prefix}%metaboxorder%' OR meta_key LIKE '{$prefix}%meta-box-order%'", ARRAY_N);

			if ($r) 
			{
				foreach ($r as $i) 
				{
					delete_user_meta($i[0], $i[1]);
				}
			}
		}
	}

	/**
	* check_dependencies
	*
	* Return true if CURL and DOM XML modules exist and false otherwise
	*
	* @return boolean
	*/
	protected function check_dependencies() 
	{
		return ((function_exists('curl_init') || ini_get('allow_url_fopen')) && (function_exists('preg_match') || function_exists('ereg')));
	}

	/**
	* load_flashdata
	*
	* Load flashdata that used to be available once and then wiped
	*
	*/
	public function load_flashdata() 
	{
		global $user_ID;

		$this->flash_data = get_user_option(ZEMANTA_PLUGIN_FLASH_META, $user_ID);

		if(!is_array($this->flash_data))
			$this->flash_data = array();
	}

	/**
	* save_flashdata
	*
	* Save flashdata to user meta
	*
	*/
	public function save_flashdata() 
	{
		global $user_ID;
		$new_data = array();

		if(is_array($this->flash_data)) {
			foreach($this->flash_data as $k => $v) {
				if(substr($k, 0, 4) == 'new#')
					$new_data['old#' . substr($k, 4)] = $v;
			}

			update_user_option($user_ID, ZEMANTA_PLUGIN_FLASH_META, $new_data, false);
		}
	}

	/**
	* keep_flashdata
	*
	* Keep flashdata key till next time
	*	
	*/
	protected function keep_flashdata($key) {
		$val = $this->flashdata($key);

		if(!is_null($val))
			$this->flash_data['new#' . $key] = $val;
	}

	/**
	* set_flashdata
	*
	* Set flashdata value by key, pass null value to unset flashdata
	*
	*/
	protected function set_flashdata($key, $value) 
	{
		if(is_null($value)) {
			if(isset($this->flash_data['new#' . $key]))
				unset($this->flash_data['new#' . $key]);
			if(isset($this->flash_data['old#' . $key]))
				unset($this->flash_data['old#' . $key]);

			return;
		}
		$this->flash_data['new#' . $key] = $value;
	}

	/**
	* flashdata
	*
	* Get flashdata by key and wipes it immidiately
	*
	*/
	protected function flashdata($key) 
	{
		if(isset($this->flash_data['new#' . $key]))
			return $this->flash_data['new#' . $key];
		else if(isset($this->flash_data['old#' . $key]))
			return $this->flash_data['old#' . $key];

		return null;
	}

	/**
	* check_plugin_updated
	*
	* Checks whether plugin update happened and triggers update notice
	*
	*/
	protected function check_plugin_updated()
	{
		$last_plugin_version = get_option(ZEMANTA_PLUGIN_VERSION_OPTION);

		// setup current version for new plugin installations
		// zemanta_api_key option presents on older 0.8 versions
		if(!$last_plugin_version && !get_option('zemanta_api_key')) {
			update_option(ZEMANTA_PLUGIN_VERSION_OPTION, $this->version, '', true);
		}

		// it'll trigger only if different version of plugin was installed before
		if(!$last_plugin_version || version_compare($last_plugin_version, $this->version, '!='))
		{
			// save new version string to database to avoid event doubling
			update_option(ZEMANTA_PLUGIN_VERSION_OPTION, $this->version);
			
			// setup flashdata so admin_notices hook could pick it up next time it will be displayed
			if(isset($this->update_notes[$this->version]))
				$this->set_flashdata('plugin_update_notice', $this->update_notes[$this->version]);
		}
	}

	/**
	* pre
	*
	* Outputs an object to the screen
	*
	* @param object $output Object to display
	*/
	protected function pre($output)
	{
		echo '<pre>';

		print_r($output);

		echo '</pre>';
	}

	/**
	* render
	*
	* Render HTML/JS/CSS to screen
	*
	* @param string $view File to display
	* @param array $arguments Arguments to pass to file
	* @param boolean $return Whether or not to return the output or print it
	* @param boolean $theme Whether or not to check the theme for an override
	*/
	protected function render($view, $arguments = array(), $return = false, $theme = false) 
	{
		foreach ($arguments as $key => $value) 
		{
			$$key = $value;
		}

		if ($return)
		{
			ob_start();
		}

		$theme = explode('/themes/', get_bloginfo('stylesheet_directory'));

		$theme_root = get_theme_root() . '/' . $theme[1] . '/views/' . $view . '.php';
		$application_root = untrailingslashit(dirname(__FILE__)) . '/views/' . $view . '.php';

		if (file_exists($theme_root))
		{
			$file = $theme_root;
		}
		else if (file_exists($application_root))
		{
			$file = $application_root;
		}

		if (file_exists($file))
		{
			include $file;
		}
		else
		{
			$this->pre('View Not Found: ' . $view);
		}

		if ($return)
		{
			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		}
	}

}

?>
