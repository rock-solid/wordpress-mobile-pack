<?php

define('WP_RP_ZEMANTA_PLUGIN_VERSION_OPTION', 'zemanta_plugin_version');
define('WP_RP_ZEMANTA_PLUGIN_FLASH_META', 'zemanta_plugin_flash');

if(!class_exists('WP_Http')) {
	require_once(ABSPATH . WPINC . '/class-http.php');
}

require_once(ABSPATH . 'wp-admin/includes/image.php');

class WPRPZemanta {

	var $version = '1.2.3';
	var $api_url = 'http://api.zemanta.com/services/rest/0.0/';
	var $api_key = '';
	var $options = array();
	var $supported_features = array();
	var $update_notes = array();
	var $flash_data = null;
	var $top_menu_slug = null;

	public function __construct()
	{
		if (defined('ZEMANTA_PLUGIN_VERSION_OPTION')) { // Make sure this doesn't clash with the Editorial Assistant
			return;
		}
		global $wp_version;
		
		// initialize update notes shown once on plugin update
		$this->update_notes['1.0.5'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		$this->update_notes['1.0.7'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		$this->update_notes['1.0.8'] = __('Please double-check your upload paths in Zemanta Settings, we changed some things that might affect your images.', 'zemanta');
		
		add_action('admin_init', array($this, 'init'));
		// add_action('admin_init', array($this, 'register_options')); // Why 

		register_activation_hook(dirname(__FILE__) . '/zemanta.php', array($this, 'activate'));
		
		$this->supported_features['featured_image'] = version_compare($wp_version, '3.1', '>=') >= 0;
	}
	
	/**
	* admin_init
	*
	* Initialize plugin
	*
	*/
	public function init() {

		add_action('wp_ajax_zemanta_set_featured_image', array($this, 'ajax_zemanta_set_featured_image'));
		add_action('edit_form_advanced', array($this, 'assets'), 1);
		add_action('edit_page_form', array($this, 'assets'), 1);
		add_action('save_post', array($this, 'save_post'), 20);
		
		$this->check_plugin_updated();
		$this->create_options();
		$this->check_options();
		
		if(!$this->check_dependencies())
			add_action('admin_notices', array($this, 'warning'));

		$this->register_options();
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
	* add_options
	*
	* Add configuration page to menu
	*/
	public function add_options() 
	{
		$this->top_menu_slug = add_menu_page(
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
		
		$this->options = apply_filters('zemanta_options', $options);
	}

	/**
	* register_options
	*
	* Register options with Settings API
	*/
	public function register_options()
	{
		register_setting('zemanta_options', 'zemanta_options', array($this, 'validate_options'));

		add_settings_section('zemanta_options_plugin', null, array($this, 'callback_options_dummy'), 'zemanta');
		add_settings_field('zemanta_option_api_key', 'Your API key', array($this, 'options_set'), 'zemanta', 'zemanta_options_plugin', $this->options['zemanta_option_api_key']);

		add_settings_section('zemanta_options_image', null, array($this, 'callback_options_dummy'), 'zemanta');
		add_settings_field('zemanta_option_image_upload', 'Enable image uploader', array($this, 'options_set'), 'zemanta', 'zemanta_options_image', $this->options['zemanta_option_image_upload']);
	}

	/**
	* callback_options_dummy
	*
	* Dummy callback for add_settings_sections
	*/
	public function callback_options_dummy()
	{
	}

	/**
	* options_set
	*
	* Output the fields for the options
	*/
	public function options_set($option = null)
	{
		// WordPress < 2.9 has a bug where the settings callback is not passed the arguments value so we check for it here.
		if ($option == null)
		{
			$option = array_shift($this->options);
		}

		$this->render('options-input-' . $option['type'], array(
			'option' => $this->get_option($option['field']),
			'field' => $option['field'],
			'title' => isset($option['title']) ? $option['title'] : null,
			'default_value' => isset($option['default_value']) ? $option['default_value'] : null,
			'description' => isset($option['description']) ? $option['description'] : null
		));
	}

	/**
	* validate_options
	*
	* Handle input Validation
	*/
	public function validate_options($input) {
		return $input;
	}

	/**
	* options
	*
	* Add configuration page
	*/
	public function options() 
	{
		if(!$this->api_key) 
		{
			$this->api_key = $this->fetch_api_key();
			$this->set_option('api_key', $this->api_key);
		}

		$this->render('options', array(
			'api_key' => $this->api_key
		));
	}

	/**
	* sideload_image
	* 
	* New image uploader, this is slightly modified version of media_sideload_image from wp-admin/includes/media.php
	*
	* @param string $file the URL of the image to download
	* @param int $post_id The post ID the media is to be associated with
	* @param string $desc Optional. Description of the image
	* @return string|WP_Error uploaded image URL on success
	*/
	public function sideload_image($file, $post_id, $desc = null) {
		$tmp = download_url($file);
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);

		$file_array = array(
			// sometimes wikipedia images have % in file names
			// so let's fix it to avoid URL encoding conflicts
			'name' => str_replace('%', '_', basename($matches[0])),
			'tmp_name' => $tmp
		);

		// If error storing temporarily, unlink
		if(is_wp_error($tmp)) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		$id = media_handle_sideload($file_array, $post_id, $desc);
		if(is_wp_error($id)) {
			@unlink($file_array['tmp_name']);
			return $id;
		}

		return wp_get_attachment_url($id);
	}

	/**
	* is_uploader_enabled
	*
	*/
	public function is_uploader_enabled() 
	{
		return $this->get_option('image_uploader');
	}

	/**
	* save_post
	*
	* Download images if necessary and update post
	*/
	public function save_post($post_id)
	{
		// do not process revisions, autosaves and auto-drafts
		if(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id) || get_post_status($post_id) == 'auto-draft' || isset($_POST['autosave']))
			return;
		
		// do not process if uploader disabled
		if(!$this->is_uploader_enabled())
			return;
		
		$content = stripslashes($_POST['post_content']);
		$nlcontent = str_replace("\n", '', $content);
		$urls = array();
		$descs = array();

		// this thingy looks for href instead of alt attributes in images
		// seems like we didn't have alts before
		// it's sort of legacy code that must be dropped at some point
		// @deprecated
		if(preg_match_all('/<div[^>]+zemanta-img[^>]+>.+?<\/div>/', $nlcontent, $matches))
		{
			foreach($matches[0] as $str) 
			{
				if(preg_match('/src="([^"]+)"/', $str, $srcurl)) 
				{
					if(preg_match('/href="([^"]+)"/', $str, $desc))
						$descs[] = $desc[1];
					else
						$descs[] = '';

					$urls[] = $srcurl[1];
				}
			}
		}

		// this code looks for all images in the post
		// extracts alt and src attributes for image downloader
		if(preg_match_all('/<img .*?src="[^"]+".*?>/', $nlcontent, $matches)) 
		{
			foreach($matches[0] as $str) 
			{
				if(preg_match('/src="([^"]+)"/', $str, $srcurl))
				{
					if(!in_array($srcurl[1], $urls)) 
					{
						if(preg_match('/alt="([^"]+)"/', $str, $desc))
							$descs[] = strlen($desc[1]) ? $desc[1] : $srcurl[1];
						else
							$descs[] = $srcurl[1];

						$urls[] = $srcurl[1];
					}
				}
			}
		}
		
		// do not do anything if there no images found in the post
		if(empty($urls))
			return;
		
		// download images to blog and replace external URLs with local
		for($i = 0, $c = sizeof($urls); $i < $c; $i++)
		{
			$url = $urls[$i];
			$desc = $descs[$i];
			
			// skip images from img.zemanta.com and FMP
			if(strpos($url, 'http://img.zemanta.com/') !== false || preg_match('#https?://.+\.fmpub\.net/#i', $url))
				continue;
			
			// skip if already hosted on our blog
			if(strpos($url, get_bloginfo('url')) !== false) {
				continue;
			}
			// upload image from URL and replace URL in the post content
			$localurl = $this->sideload_image($url, $post_id, $desc);
			if(!is_wp_error($localurl) && !empty($localurl))
				$content = str_replace($url, $localurl, $content);
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
	public function api($arguments)
	{
		$arguments = array_merge($arguments, array(
			'api_key'=> $this->api_key
			));
		
		if (!isset($arguments['format']))
		{
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
	public function ajax_zemanta_set_featured_image()
	{
		global $post_ID;
		
		if(!isset($this->supported_features['featured_image'])) {
			$this->ajax_error(new WP_Error(4, __('Featured image feature is not supported on current platform.', 'zemanta')));
		}
		
		$args = wp_parse_args($_REQUEST, array('post_id' => 0, 'image_url' => ''));
		extract($args);
		
		$post_id = (int)$post_id;
		
		if(!empty($image_url) && $post_id)
		{
			$http_response = wp_remote_get($image_url, array('timeout' => 10));

			if(!is_wp_error($http_response))
			{
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
		$response = $this->api(array(
			'method' => 'zemanta.auth.create_user',
			'partner_id' => 'wordpress-zem'
			));

		if(!is_wp_error($response))
		{
			if(preg_match('/<status>(.+?)<\/status>/', $response['body'], $matches))
			{
				if($matches[1] == 'ok' && preg_match('/<apikey>(.+?)<\/apikey>/', $response['body'], $matches))
					return $matches[1];
			}
		}

		return '';
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
		$options = get_option('zemanta_options');

		return isset($options[$name]) ? $options[$name] : null;
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
	* get_api_key
	*
	* Get API Key
	*/ 
	public function get_api_key()
	{
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
		$this->set_option('api_key', $api_key);
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
	* check_plugin_updated
	*
	* Checks whether plugin update happened and triggers update notice
	*
	*/
	protected function check_plugin_updated()
	{
		$last_plugin_version = get_option(WP_RP_ZEMANTA_PLUGIN_VERSION_OPTION);

		// setup current version for new plugin installations
		// zemanta_api_key option presents on older 0.8 versions
		if(!$last_plugin_version && !get_option('zemanta_api_key')) {
			update_option(WP_RP_ZEMANTA_PLUGIN_VERSION_OPTION, $this->version, '', true);
		}

		// it'll trigger only if different version of plugin was installed before
		if(!$last_plugin_version || version_compare($last_plugin_version, $this->version, '!='))
		{
			// save new version string to database to avoid event doubling
			update_option(WP_RP_ZEMANTA_PLUGIN_VERSION_OPTION, $this->version);
		}
	}

	/**
	* render
	*
	* Render HTML/JS/CSS to screen
	*
	* @param string $view File to display
	* @param array $arguments Arguments to pass to file
	* @param boolean $return Whether or not to return the output or print it
	*/
	protected function render($view, $arguments = array(), $return = false) 
	{		
		$view_file = untrailingslashit(dirname(__FILE__)) . '/views/' . $view . '.php';

		extract($arguments, EXTR_SKIP);

		if ($return)
			ob_start();

		if(file_exists($view_file))
			include($view_file);
		else
			echo '<pre>View Not Found: ' . $view . '</pre>';

		if ($return)
			return ob_get_clean();
	}

}

//
// End of file zemanta.php
//
