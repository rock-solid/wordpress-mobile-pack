<?php
/**
 * @package WPSMart Mobile
 */
/*
Plugin Name: WPSmart Mobile
Plugin URI: http://www.wpsmart.com
Description: Present your Wordpress site in a beautiful theme optimized for touch-based smartphones
Version: 1.0.4
Author: WPSmart
Author URI: http://www.wpsmart.com/mobile
License: GPLv2 or later
*/

define("WPSMART_VERSION", '1.0.4');
define("WPSMART_BASE_THEME", dirname(__FILE__) . '/themes/base');

require_once('admin/admin.php');
require_once('includes/defaults.php');

add_action('wp_ajax_wps_upload_file', 'wps_upload_file_callback');
add_action('wp_ajax_wps_update_options', 'wps_update_options_callback');
add_action('wp_ajax_wps_support_submission', 'wps_support_submission_callback');
add_action('wp_ajax_wps_activate_theme', 'wps_activate_theme_callback');
add_action('wp_ajax_wps_get_menu', 'wps_get_menu_callback');

if ( ! class_exists( 'WPSmart' ) ) :

class WPSmart
{
	public $wps_show_mobile;
	public $wps_options;
	public $wps_option_name;
	
	function WPSmart()
	{
		$this->wps_check_mobile();
		
		if( $this->wps_show_mobile == true )
		{
			add_filter( 'stylesheet', array( &$this, 'wps_stylesheet' ) );
			add_filter( 'theme_root', array( &$this, 'wps_theme_root' ) );
			add_filter( 'theme_root_uri', array( &$this, 'wps_theme_root_uri' ) );
			add_filter( 'template', array( &$this, 'wps_current_theme' ) );
		}
	}
	
	function wps_install()
	{
		//...crickets
	}
	
	function wps_admin_init()
	{
		$this->wps_admin_enqueue_files();
	}
	

	function wps_admin_enqueue_files()
	{
		wp_register_style( 'wps-admin.css', $this->wps_plugin_uri() . '/admin/css/wps-admin.css' );
		wp_register_style( 'jquery.miniColors.css', $this->wps_plugin_uri() . '/admin/css/jquery.miniColors.css' );
		wp_enqueue_style( 'wps-admin.css' );
		wp_enqueue_style( 'jquery.miniColors.css' );
		
		wp_register_script( 'jquery.miniColors.js', $this->wps_plugin_uri() . '/admin/js/jquery.miniColors.min.js' );
		wp_register_script( 'wps-admin.js', $this->wps_plugin_uri() . '/admin/js/wps-admin.js' );
		wp_register_script( 'bootstrap.js', $this->wps_plugin_uri() . '/admin/js/bootstrap.min.js' );
		
		wp_enqueue_script( 'jquery.miniColors.js' );
		wp_enqueue_script( 'wps-admin.js' );
		wp_enqueue_script( 'bootstrap.js' );
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );	
	}
					
	function wps_stylesheet()
	{
		if( ! isset( $_GET['wps_preview_theme'] ) )
			return wps_get_option( 'current_theme' );
		else
			return $_GET['wps_preview_theme'];
	}
	
	function wps_theme_root() {
		return $this->wps_plugin_dir() . '/themes';
	}
	
	function wps_theme_root_uri()
	{
		return $this->wps_plugin_uri() . '/themes';
	}
	
	function wps_current_theme()
	{
		if( ! isset( $_GET['wps_preview_theme'] ) )
			return wps_get_option( 'current_theme' );
		else
			return $_GET['wps_preview_theme'];
	}
	
	function wps_plugin_uri()
	{
		return WP_PLUGIN_URL . '/wpsmart-mobile';
	}
	
	function wps_plugin_dir()
	{
		return WP_PLUGIN_DIR . '/wpsmart-mobile';
	}
	
	function wps_plugin_admin_uri()
	{
		return $this->wps_plugin_uri() . '/admin';
	}
			
	function wps_check_mobile()
	{
		global $wps_user_agents;
		
		if( ! is_admin() ) {
			if( wps_is_in_preview_mode() ) {
				add_filter('show_admin_bar', '__return_false'); // don't show admin bar when in preview mode
				$this->wps_show_mobile = true;
			} elseif( isset( $_COOKIE['wpsmart_view_full_site'] ) && $_COOKIE['wpsmart_view_full_site'] == 1 ) {
				$this->wps_show_mobile = false;
				add_action( 'wp_footer', array( &$this, 'wps_view_mobile_site' ) );
			} else {		
				$server_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
										
				foreach( $wps_user_agents as $user_agent ) {				
					if( preg_match( "/$user_agent/", $server_user_agent) ) {
						$this->wps_show_mobile = true;
						break;
					}
					else
						$this->wps_show_mobile = false;
				}
			}
		}
	}
	
	function wps_comment_html( $comment_data )
	{
	?>
		<li class="comment">		
			<div class="comment-head" id="comment-<?php echo $comment_data['comment_ID']; ?>">
				<?php echo comment_author_link( $comment_data['comment_ID'] ); ?><span class="dot">&middot;</span><span class="comment-date">just now</span>
			</div>
			
			<div class="comment-content"><?php comment_text( $comment_data['comment_ID'] ); ?></div>
		</li>
	
	<?php
	}
	
	function wps_view_mobile_site()
	{
    ?>
    	<div style="text-align:center;margin:20px 0;font-size:14px;"><a href="#" onclick='document.cookie="wpsmart_view_full_site=0;expires=<?php echo date("Y-m-d H:i:s", 0) ?>";window.location.href = "<?php echo home_url() ?>";return false;'>View moblie optimized site</a></div>
    <?php
    }
    
    function wps_the_content()
    {
	    return wpautop( get_the_content() );
    }
    
    function wps_get_theme_directories()
    {
    	$theme_dirs = array();
    	
	    if( $handle = opendir( $this->wps_theme_root() ) ) {
	    
		    while( false !== ( $entry = readdir( $handle ) ) ) {
		    	$path_to_dir = $this->wps_theme_root() . '/' . $entry;
		    	
		        if( is_dir($path_to_dir) && file_exists( $path_to_dir ) && $entry != '.' && $entry != '..' && $entry != 'base' ) {
		        	$theme_dirs[$entry] = $path_to_dir;
		        }
		    }
	    }
	    
	    return $theme_dirs;
    }
    
    function wps_get_theme_data( $theme_slug )
    {
	    $path_to_theme = $this->wps_theme_root() . "/$theme_slug";
	    
	    $theme_data = array();
	    $theme_data['slug'] = $theme_slug;
	    $theme_data['name'] = $this->wps_get_theme_name( $theme_slug );
	    $theme_data['screenshot'] = $this->wps_get_theme_screenshot( $theme_slug, $path_to_theme );
	    
	    return $theme_data;
    }
    
    function wps_get_theme_name( $theme_slug )
    {
    	$path_to_theme_style = $this->wps_theme_root() . "/$theme_slug/style.css";
    	
	    if( file_exists( $path_to_theme_style ) ) {
		    $stylesheet_contents = file_get_contents( $path_to_theme_style );
		    
		    $theme_name_label = 'Theme Name:';
		    $pattern = preg_quote($theme_name_label, '/');
		    $pattern = "/^.*$pattern.*\$/m";

		    if(preg_match_all($pattern, $stylesheet_contents, $matches)){
		       $theme_name = str_replace($theme_name_label, '', $matches[0]);
		       
		       return $theme_name[0];
		    }
		    
	    } else {
		    return false;
	    }
    }
    
    function wps_get_theme_screenshot( $theme_slug )
    {
    	$path_to_theme = $this->wps_theme_root() . "/$theme_slug";
    	
	    if( file_exists( $path_to_theme . '/screenshot.png' ) )
	    	return $this->wps_theme_root_uri() . "/$theme_slug/screenshot.png";
    }
    
    function wps_get_http_request( $url, $arg = null )
    {
	    $request = new WP_Http;
	    $result = $request->request( $url, $arg );
	    
	    if( $result && ! isset( $result->errors ) )
		    return $result['body'];
		else
			return false;
    }
    
    function wps_get_overview_data()
    {
	    $url = 'http://stats.wpsmart.com/s.php?url=' . urlencode( home_url() );

	    $return_json = $this->wps_get_http_request( $url, null );
 
	    if( $result = json_decode( $return_json, true ) )
	    	return $result;
	    else
	    	return false;
    }
    
    function wps_get_post_title_from_id( $url )
    {

	    $post_id = url_to_postid( $url );

	    if( ! $post_id )
	    	$post_title = $url;
	    else
	    	$post_title = get_the_title( $post_id );
	    	
	    return "<a href=\"$url\">$post_title</a>";	
    }
}

global $wpsmart;
$wpsmart = new WPSmart();

endif; // if ( ! class_exists( 'WPSmart' ) ) :

function wps_enqueue_header()
{

	wp_register_script( 'wps-scripts', wps_get_base_theme_uri() . '/base-js/scripts.js?t=' . time(), array( 'jquery' ) );
	wp_register_script( 'wps-base', wps_get_base_theme_uri() . '/base-js/base.js?t=' . time(), array( 'jquery' ) );
	
	wp_enqueue_script( 'jquery' );	
	wp_enqueue_script( 'comment-reply' );	
	wp_enqueue_script( 'wps-scripts' );
	wp_enqueue_script( 'wps-base' );
	
	wp_register_style( 'base-style', wps_get_base_theme_uri() . '/base-css/base.css?t=' . time() );
	wp_register_style( 'style', wps_get_theme_uri() . '/style.css?t=' . time() );
	wp_register_style( 'font-awesome', wps_get_base_theme_uri() . '/base-css/font-awesome.css?t=' . time() );
	
	wp_enqueue_style( 'base-style' );
	wp_enqueue_style( 'style' );
	wp_enqueue_style( 'font-awesome' );	
}

function wps_user_agents()
{
	global $wps_user_agents;
	return $wps_user_agents;
}

function wps_preview_url()
{
	return bloginfo( 'wpurl' ) . '?wps_preview=1';
}


function wps_get_theme_uri()
{
	global $wpsmart;
	return $wpsmart->wps_theme_root_uri() . '/' . $wpsmart->wps_current_theme();
}

function wps_get_base_theme_uri()
{
	global $wpsmart;
	return $wpsmart->wps_theme_root_uri() . '/base';
}

function wps_get_base_theme()
{
	global $wpsmart;
	return $wpsmart->wps_theme_root() . '/base';
}

function wps_is_uploads_directory_writable()
{
	$upload = wp_upload_dir();
	$basedir = $upload['basedir'];
	$error = $upload['error'];
	
	return is_writable( $basedir );
}

function wps_upload_base_dir()
{
	$upload = wp_upload_dir();
	$basedir = $upload['basedir'];
	
	return $basedir;
}

function wps_upload_dir($upload)
{
	$upload['subdir']	= '/wpsmart';
	$upload['path']		= $upload['basedir'] . $upload['subdir'];
	$upload['url']		= $upload['baseurl'] . $upload['subdir'];
	return $upload;
}

function wps_upload_file_callback()
{
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	
	add_filter('upload_dir', 'wps_upload_dir');
	$upload = wp_upload_dir();
			
	$file = $_FILES['site_logo'];
	image_resize($file['tmp_name'], 360, 60, false, '', '', 100);
	
	$uploaded_file = wp_handle_upload( $file, array('test_form' => false), '' );
	remove_filter('upload_dir', 'wps_upload_dir');
	
	echo json_encode( $uploaded_file );
	die();
}

function wps_update_options_callback()
{
	global $wpsmart;
	
	unset( $_POST['action'] );
	$post_data = $_POST;
	
	if( isset( $post_data['google_analytics_code'] ) )
		$post_data['google_analytics_code'] = htmlentities( $post_data['google_analytics_code'], ENT_QUOTES );
	
	if( isset( $post_data['menu_links'] ) )
		$post_data['menu_links'] = serialize( array_values( $post_data['menu_links'] ) );
		
	foreach( $post_data as $option_name => $option_value ) {
		if( ! wps_add_option ( $option_name, $option_value ) )
			wps_update_option( $option_name, $option_value );	
	}
	die();
}

function wps_support_submission_callback()
{
	global $wpsmart;
	$post_data = $_POST;
	$site = home_url();
	$email = $post_data['email'];
	$body = $post_data['body'] . "\r\nSite URL:" . $site;
	
	if( isset( $email ) && isset( $post_data['body'] ) ) {
		$headers = 'From: WPSmart User<' . $email . '>' . "\r\n" .
			'Reply-To: <' . $email . '>' . "\r\n";

		if( wp_mail( 'support@wpsmart.com', 'WPSmart support inquiry from ' . $site, $body, $headers ) )
			echo json_encode( array( 'status' => 'success' ) );
		else
			echo json_encode( array( 'status' => 'error' ) );
	} else {
		echo json_encode( array( 'status' => 'required' ) );
	}
	
	die();
}

function wps_activate_theme_callback()
{
	global $wpsmart;
	
	$theme_name = $_POST['theme'];
	
	if( ! wps_add_option ( 'current_theme', $theme_name ) ) {
		if( ! wps_update_option( 'current_theme', $theme_name ) ) {
			echo json_encode( array( 'status' => 'error' ) );
		}
		else
			echo json_encode( array( 'status' => 'success' ) );
	} else {
		echo json_encode( array( 'status' => 'success' ) );
	}
	
	die();
}

function wps_get_menu_callback()
{
	global $wpsmart;
	
	$menu_id = $_POST['menu_id'];
	$i = 0;
	
	foreach( wp_get_nav_menu_items( $menu_id ) as $menu_item ) {
?>
		<li id="" class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><?php echo $menu_item->title ?></span>
					<span class="item-controls">
						<a class="item-edit" id="" title="Edit Menu Item" href="#">Edit Menu Item</a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" style="display: none;">
				<p class="description description-thin">
					<label>Label<br>
						<input type="text" name="menu_links[<?php echo $i ?>][title]" class="widefat edit-menu-item-title" value="<?php echo $menu_item->title ?>">
					</label>
				</p>

				<div class="menu-item-actions description-wide submitbox">
					<a class="item-delete submitdelete deletion" href="#">Remove</a> <span class="meta-sep">
				</div>

				<input type="hidden" name="menu_links[<?php echo $i ?>][url]" value="<?php echo $menu_item->url ?>" />
				<input type="hidden" name="menu_links[<?php echo $i ?>][icon]" value="" />
			</div><!-- .menu-item-settings-->
		</li>
	
<?php
		$i++;
	}
	
	die();
}

function wps_admin_menu()
{
	global $wpsmart;
	
	add_menu_page( 'WPSmart', 'WPSmart', 'manage_options', 'wpsmart.php', 'wps_admin_main', $wpsmart->wps_plugin_admin_uri() . '/images/wpsmart_icon.png', 61 );
}

function wps_get_option( $option_name, $option_key = false )
{
	global $wps_default_options;

	if ( get_option( 'wpsmart_' . $option_name ) == '' )
		$wps_option = $wps_default_options[$option_name];
	else
		$wps_option = get_option( 'wpsmart_' . $option_name );
		
	if( $option_key )
		return $wps_option[$option_key];
	else
		return $wps_option;
}

function wps_add_option( $option_name, $option_value )
{
	global $wps_default_options;
	
	if ( array_key_exists( $option_name , $wps_default_options ) )
		return add_option( 'wpsmart_' . $option_name, $option_value );
}

function wps_update_option( $option_name, $option_value )
{
	global $wps_default_options;
	
	if ( array_key_exists( $option_name , $wps_default_options ) )
		return update_option( 'wpsmart_' . $option_name, $option_value );
}

function wps_delete_option( $option_name )
{
	global $wps_default_options;
	
	if ( array_key_exists( $option_name , $wps_default_options ) )
		return delete_option( 'wpsmart_' . $option_name );
}

function wps_checkbox_text( $option )
{
	global $wpsmart;
	
	if( wps_get_option( $option ) == 1 )
		return "checked=\"checked\"";
	else
		return "";
}

function wps_get_pages()
{
	$wp_pages = array();
	$tmp_pages = get_pages();
	$count = 0;
	
	foreach( $tmp_pages as $tmp_page ) {
		$wp_pages[$count]['page_id'] = $tmp_page->ID;
		$wp_pages[$count]['page_title'] = $tmp_page->post_title;
		$wp_pages[$count]['guid'] = $tmp_page->guid;
		$count++;
	}
	
	return $wp_pages;
}

function wps_get_categories()
{
	$wp_categories = array();
	$tmp_categories = get_categories();
	$count = 0;
		
	foreach( $tmp_categories as $tmp_category ) {
		$wp_categories[$count]['category_id'] = $tmp_category->term_id;
		$wp_categories[$count]['category_title'] = $tmp_category->name;
		$wp_categories[$count]['link'] = get_category_link( $tmp_category->term_id );
		$count++;
	}

	return $wp_categories;
}

function wps_html_unclean( $string )
{
	$string = html_entity_decode( stripslashes( $string ), ENT_QUOTES );
	return $string;
}

function wps_is_in_preview_mode()
{
	if( isset( $_GET['wps_preview'] ) && $_GET['wps_preview'] == 1 )
   		return true;
    
    return false;
}

function wps_get_menu_links()
{
	$menu_links = wps_get_option( 'menu_links' );
	
	if( ! is_array( $menu_links ) )
		return stripslashes_deep( unserialize( $menu_links ) );
	else
		return stripslashes_deep( $menu_links );
}

function wps_get_menus()
{
	$menus = get_terms( 'nav_menu' );
	
	return $menus;
}

function wps_get_menu_items( $menu_id )
{
	return wp_get_nav_menu_items( $menu_id );
}

function wps_get_themes( $exclude_current = false)
{
	global $wpsmart;
	
	$theme_dirs = $wpsmart->wps_get_theme_directories();
	$themes = array();
	
	foreach( $theme_dirs as $theme_dir_name => $theme_dir )
	{
		$themes[$theme_dir_name] = $wpsmart->wps_get_theme_data( $theme_dir_name );
	}
	
	if( $exclude_current )
	{
		$current_theme = $wpsmart->wps_current_theme();
		unset( $themes[$current_theme] );
	}
	
	return $themes;	
}

function wps_get_current_theme()
{
	global $wpsmart;
	
	$theme = array();
	$current_theme = $wpsmart->wps_current_theme();
	$theme = $wpsmart->wps_get_theme_data( $current_theme );
	
	return $theme;
}

function wps_google_analytics_script( $tracking_id )
{
?>
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $tracking_id ?>']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
<?php
}

function wps_google_adsense_script( $client_id )
{
?>
	<div class="advertising">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<ins class="adsbygoogle"
		     style="display:inline-block;width:320px;height:50px"
		     data-ad-client="<?php echo $client_id ?>"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
	
	
<?php
}

register_activation_hook( __FILE__, array( &$wpsmart, 'wps_install' ) );
add_action( 'admin_menu',  'wps_admin_menu' );
add_action( 'admin_init', array( &$wpsmart, 'wps_admin_init' ) );