<?php
/*
Plugin Name: TinyMCE Advanced
Plugin URI: http://www.laptoptips.ca/projects/tinymce-advanced/
Description: Enables advanced features and plugins in TinyMCE, the visual editor in WordPress.
Version: 3.5.9.1
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Some code and ideas from WordPress (http://wordpress.org/). The options page for this plugin uses jQuery (http://jquery.com/).

Released under the GPL v.2, http://www.gnu.org/licenses/gpl-2.0.html

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/


if ( ! function_exists('tadv_paths') ) {
	// If using domain mapping or plugins that change the path dinamically, edit these to set the proper path and URL.
	function tadv_paths() {
		if ( !defined('TADV_URL') )
			define('TADV_URL', plugin_dir_url(__FILE__));
			
		if ( !defined('TADV_PATH') )
			define('TADV_PATH', plugin_dir_path(__FILE__));
	}
	add_action( 'plugins_loaded', 'tadv_paths', 50 );
}


if ( ! function_exists('tadv_version') ) {
	function tadv_version() {
		$ver = get_option('tadv_version', 0);

		if ( $ver < 3420 ) {
			update_option('tadv_version', 3420);

			$plugins = array_diff( get_option('tadv_plugins', array()), array('media') );
			update_option('tadv_plugins', $plugins);
		}
	}
	add_action( 'admin_init', 'tadv_version' );
}


if ( ! function_exists('tadv_add_scripts') ) {
	function tadv_add_scripts($page) {
		if ( 'settings_page_tinymce-advanced' == $page ) {
			wp_enqueue_script( 'tadv-js', TADV_URL . 'js/tadv.js', array('jquery-ui-sortable'), '3.4.2', true );
			wp_enqueue_style( 'tadv-css', TADV_URL . 'css/tadv-styles.css', array(), '3.5.9' );
		}
	}
}


if ( ! function_exists('tadv_load_defaults') ) {
	function tadv_load_defaults() {
		$tadv_options = get_option('tadv_options');
		if ( ! empty($tadv_options) )
			return;

		@include_once('tadv_defaults.php');

		if ( isset($tadv_toolbars) ) {
			add_option( 'tadv_options', $tadv_options );
			add_option( 'tadv_toolbars', $tadv_toolbars, '', 'no' );
			add_option( 'tadv_plugins', $tadv_plugins, '', 'no' );
			add_option( 'tadv_btns1', $tadv_btns1, '', 'no' );
			add_option( 'tadv_btns2', $tadv_btns2, '', 'no' );
			add_option( 'tadv_btns3', $tadv_btns3, '', 'no' );
			add_option( 'tadv_btns4', $tadv_btns4, '', 'no' );
			add_option( 'tadv_allbtns', $tadv_allbtns, '', 'no' );
		}
	}
	add_action( 'admin_init', 'tadv_load_defaults' );
}

if ( ! function_exists('tdav_get_file') ) {
	function tdav_get_file($path) {
	
		if ( function_exists('realpath') )
			$path = realpath($path);
	
		if ( ! $path || ! @is_file($path) )
			return '';
	
		return @file_get_contents($path);
	}
}

$tadv_allbtns = array();
$tadv_hidden_row = 0;


if ( ! function_exists('tadv_mce_btns') ) {
	function tadv_mce_btns($orig) {
		global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns1 = (array) get_option('tadv_btns1', array());
		$tadv_allbtns = (array) get_option('tadv_allbtns', array());
		$tadv_options = get_option('tadv_options', array());

		if ( in_array( 'wp_adv', $tadv_btns1 ) )
			$tadv_hidden_row = 2;

		if ( is_array($orig) && ! empty($orig) ) {
			$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns1 = array_merge( $tadv_btns1, $orig );
		}

		return $tadv_btns1;
	}
	add_filter( 'mce_buttons', 'tadv_mce_btns', 999 );
}


if ( ! function_exists('tadv_mce_btns2') ) {
	function tadv_mce_btns2($orig) {
		global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns2 = (array) get_option('tadv_btns2', array());

		if ( in_array( 'wp_adv', $tadv_btns2 ) )
			$tadv_hidden_row = 3;

		if ( is_array($orig) && ! empty($orig) ) {
			$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns2 = array_merge( $tadv_btns2, $orig );
		}
		return $tadv_btns2;
	}
	add_filter( 'mce_buttons_2', 'tadv_mce_btns2', 999 );
}


if ( ! function_exists('tadv_mce_btns3') ) {
	function tadv_mce_btns3($orig) {
		global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns3 = (array) get_option('tadv_btns3', array());

		if ( in_array( 'wp_adv', $tadv_btns3 ) )
			$tadv_hidden_row = 4;

		if ( is_array($orig) && ! empty($orig) ) {
			$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns3 = array_merge( $tadv_btns3, $orig );
		}
		return $tadv_btns3;
	}
	add_filter( 'mce_buttons_3', 'tadv_mce_btns3', 999 );
}


if ( ! function_exists('tadv_mce_btns4') ) {
	function tadv_mce_btns4($orig) {
		global $tadv_allbtns;
		$tadv_btns4 = (array) get_option('tadv_btns4', array());

		if ( is_array($orig) && ! empty($orig) ) {
			$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns4 = array_merge( $tadv_btns4, $orig );
		}
		return $tadv_btns4;
	}
	add_filter( 'mce_buttons_4', 'tadv_mce_btns4', 999 );
}


if ( ! function_exists('tadv_mce_options') ) {
	function tadv_mce_options($init) {
		global $tadv_hidden_row;
		$tadv_options = get_option('tadv_options', array());

		if ( $tadv_hidden_row > 0 )
			$init['wordpress_adv_toolbar'] = 'toolbar' . $tadv_hidden_row;
		else
			$init['wordpress_adv_hidden'] = false;

		if ( isset($tadv_options['no_autop']) && $tadv_options['no_autop'] == 1 )
			$init['apply_source_formatting'] = true;

		if ( isset($tadv_options['hideclasses']) && $tadv_options['hideclasses'] == 1 )
			$init['class_filter'] = '[function(){return false;}]';

		return $init;
	}
	add_filter( 'tiny_mce_before_init', 'tadv_mce_options' );
}


if ( ! function_exists('tadv_htmledit') ) {
	function tadv_htmledit($c) {
		$tadv_options = get_option('tadv_options', array());

		if ( isset($tadv_options['no_autop']) && $tadv_options['no_autop'] == 1 ) {
			$c = str_replace( array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $c );
			$c = wpautop($c);
			$c = preg_replace( '/^<p>(https?:\/\/[^<> "]+?)<\/p>$/im', '$1', $c );
			$c = htmlspecialchars( $c, ENT_NOQUOTES, get_option( 'blog_charset' ) );
		}
		return $c;
	}
	add_filter('htmledit_pre', 'tadv_htmledit', 999);
}


if ( ! function_exists('tmce_replace') ) {
	function tmce_replace() {
		$tadv_options = get_option('tadv_options', array());

		if ( empty( $tadv_options['no_autop'] ) ) {
			return;
		}

		?>
		<script type="text/javascript">
		if ( typeof(jQuery) != 'undefined' ) {
			jQuery('body').on('afterPreWpautop', function( event, obj ) {
				var regex = [
					new RegExp('https?://(www\.)?youtube\.com/watch.*', 'i'),
					new RegExp('http://youtu.be/*'),
					new RegExp('http://blip.tv/*'),
					new RegExp('https?://(www\.)?vimeo\.com/.*', 'i'),
					new RegExp('https?://(www\.)?dailymotion\.com/.*', 'i'),
					new RegExp('http://dai.ly/*'),
					new RegExp('https?://(www\.)?flickr\.com/.*', 'i'),
					new RegExp('http://flic.kr/*'),
					new RegExp('https?://(.+\.)?smugmug\.com/.*', 'i'),
					new RegExp('https?://(www\.)?hulu\.com/watch/.*', 'i'),
					new RegExp('https?://(www\.)?viddler\.com/.*', 'i'),
					new RegExp('http://qik.com/*'),
					new RegExp('http://revision3.com/*'),
					new RegExp('http://i*.photobucket.com/albums/*'),
					new RegExp('http://gi*.photobucket.com/groups/*'),
					new RegExp('https?://(www\.)?scribd\.com/.*', 'i'),
					new RegExp('http://wordpress.tv/*'),
					new RegExp('https?://(.+\.)?polldaddy\.com/.*', 'i'),
					new RegExp('https?://(www\.)?funnyordie\.com/videos/.*', 'i'),
					new RegExp('https?://(www\.)?twitter\.com/.+?/status(es)?/.*', 'i'),
					new RegExp('https?://(www\.)?soundcloud\.com/.*', 'i'),
					new RegExp('https?://(www\.)?slideshare\.net/*', 'i'),
					new RegExp('http://instagr(\.am|am\.com)/p/.*', 'i'),
					new RegExp('https?://(www\.)?rdio\.com/.*', 'i'),
					new RegExp('https?://rd\.io/x/.*', 'i'),
					new RegExp('https?://(open|play)\.spotify\.com/.*', 'i')
				];

				obj.data = obj.unfiltered
				.replace(/<p>(https?:\/\/[^<> "]+?)<\/p>/ig, function( all, match ) {
					for( var i in regex ) {
						if ( regex[i].test( match ) ) {
							return '\n' + match + '\n';
						}
					}
					return all;
				})
				.replace(/caption\]\[caption/g, 'caption] [caption')
				.replace(/<object[\s\S]+?<\/object>/g, function(a) {
					return a.replace(/[\r\n]+/g, ' ');
				}).replace( /<pre[^>]*>[\s\S]+?<\/pre>/g, function( match ) {
					match = match.replace( /<br ?\/?>(\r\n|\n)?/g, '\n' );
					return match.replace( /<\/?p( [^>]*)?>(\r\n|\n)?/g, '\n' );
				});
			}).on('afterWpautop', function( event, obj ){
				obj.data = obj.unfiltered;
			});
		}
		</script>
		<?php
	}
	add_action( 'after_wp_tiny_mce', 'tmce_replace' );
}


if ( ! function_exists('tadv_load_plugins') ) {
	function tadv_load_plugins($plug) {
		$tadv_plugins = get_option('tadv_plugins');
		$tadv_options = get_option('tadv_options', array());

		if ( isset($tadv_options['editorstyle']) && $tadv_options['editorstyle'] == '1' )
			add_editor_style(); // import user created editor-style.css

		if ( empty($tadv_plugins) || !is_array($tadv_plugins) )
			return $plug;

		$plugpath = TADV_URL . 'mce/';

		$plug = (array) $plug;
		foreach( $tadv_plugins as $plugin )
			$plug["$plugin"] = $plugpath . $plugin . '/editor_plugin.js';

		return $plug;
	}
	add_filter( 'mce_external_plugins', 'tadv_load_plugins', 999 );
}


if ( ! function_exists('tadv_load_langs') ) {
	function tadv_load_langs($langs) {
		$tadv_plugins = get_option('tadv_plugins');
		if ( empty($tadv_plugins) || !is_array($tadv_plugins) )
			return $langs;

		$langpath = TADV_PATH . 'mce/';
		$dolangs = array( 'advhr', 'advimage', 'advlink', 'searchreplace', 'style', 'table', 'xhtmlxtras' );

		$langs = (array) $langs;
		foreach( $tadv_plugins as $plugin ) {
			if ( !in_array( $plugin, $dolangs ) )
				continue;

			$langs["$plugin"] = $langpath . $plugin . '/langs/langs.php';
		}
		return $langs;
	}
	add_filter( 'mce_external_languages', 'tadv_load_langs' );
}


if ( ! function_exists('tadv_page') ) {
	function tadv_page() {
		if ( !defined('TADV_ADMIN_PAGE') )
			define('TADV_ADMIN_PAGE', true);

		tadv_paths();
		include_once( TADV_PATH . 'tadv_admin.php');
	}
}

if ( ! function_exists('tadv_menu') ) {
	function tadv_menu() {
		if ( function_exists('add_options_page') ) {
			add_options_page( 'TinyMCE Advanced', 'TinyMCE Advanced', 'manage_options', 'tinymce-advanced', 'tadv_page' );
			add_action( 'admin_enqueue_scripts', 'tadv_add_scripts' );
		}
	}
	add_action( 'admin_menu', 'tadv_menu' );
}

