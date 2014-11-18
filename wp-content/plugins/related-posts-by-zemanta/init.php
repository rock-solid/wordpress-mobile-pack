<?php

if (defined('WP_RP_VERSION') || defined('ZEM_RP_VERSION')) {
	add_action( 'admin_notices', 'wp_rp_multiple_plugins_notice_zem' );
	return;
}

define('ZEM_RP_VERSION', '1.9.2');

define('ZEM_RP_PLUGIN_FILE', plugin_basename(__FILE__));

include_once(dirname(__FILE__) . '/config.php');
include_once(dirname(__FILE__) . '/lib/stemmer.php');

include_once(dirname(__FILE__) . '/admin_notices.php');
include_once(dirname(__FILE__) . '/widget.php');
include_once(dirname(__FILE__) . '/thumbnailer.php');
include_once(dirname(__FILE__) . '/settings.php');
include_once(dirname(__FILE__) . '/recommendations.php');
include_once(dirname(__FILE__) . '/edit_related_posts.php');

register_activation_hook(__FILE__, 'zem_rp_activate_hook');
register_deactivation_hook(__FILE__, 'zem_rp_deactivate_hook');

add_action('wp_head', 'zem_rp_head_resources');

add_action('plugins_loaded', 'wp_rp_init_zemanta');


function wp_rp_init_zemanta() {
	include_once(dirname(__FILE__) . '/zemanta/zemanta.php');
	if (zem_is_classic()) {
		$wprp_zemanta = new WPRPZemanta();
	}
}

function zem_rp_get_template($file) {
	return dirname(__FILE__) . '/views/' . $file . '.php';
}

function zem_rp_admin_style() {
	wp_enqueue_style('wp_rp_admin_style', plugins_url('static/css/dashboard.css', __FILE__));
}
add_action( 'admin_enqueue_scripts', 'zem_rp_admin_style');

function zem_rp_global_notice() {
	global $pagenow, $zem_global_notice_pages;
	if (!current_user_can('delete_users')) {
		return;
	}
	$meta = zem_rp_get_meta();
	$close_url = add_query_arg( array(
		'page' => 'zemanta-related-posts',
		'zem_global_notice' => 0,
	), admin_url( 'admin.php' ) );
	$notice = $meta['global_notice'];
	if ($notice && in_array($pagenow, $zem_global_notice_pages)) {
		include(zem_rp_get_template('global_notice'));

	}
}
add_action('all_admin_notices', 'zem_rp_global_notice' );

global $zem_rp_output;
$zem_rp_output = array();
function zem_rp_add_related_posts_hook($content) {
	global $zem_rp_output, $post;
	$options = zem_rp_get_options();

	if ($content != "" && $post->post_type === 'post' && (($options["on_single_post"] && is_single()) || (is_feed() && $options["on_rss"]))) {
		if (!isset($zem_rp_output[$post->ID])) {
			$zem_rp_output[$post->ID] = zem_rp_get_related_posts();
		}
		$content = $content . $zem_rp_output[$post->ID];
	}

	return $content;
}
add_filter('the_content', 'zem_rp_add_related_posts_hook', 10);

function zem_rp_get_platform_options() {
	$options = zem_rp_get_options();

	$thumb_options = array(
		'custom_size_thumbnail_enabled' => false
	);

	if (!empty($options['custom_size_thumbnail_enabled'])) {
		$thumb_options['custom_size_thumbnail_enabled'] = $options['custom_size_thumbnail_enabled'];
		$thumb_options['custom_thumbnail_width'] = $options['custom_thumbnail_width'];
		$thumb_options['custom_thumbnail_height'] = $options['custom_thumbnail_height'];
	}
	
	return $options['desktop'] + $thumb_options;
}

function zem_rp_ajax_load_articles_callback() {
	global $post;

	$platform_options = zem_rp_get_platform_options();

	$getdata = stripslashes_deep($_GET);
	if (!isset($getdata['post_id'])) {
		die('error');
	}

	$post = get_post($getdata['post_id']);
	if(!$post) {
		die('error');
	}

	$from = (isset($getdata['from']) && is_numeric($getdata['from'])) ? intval($getdata['from']) : 0;
	$count = (isset($getdata['count']) && is_numeric($getdata['count'])) ? intval($getdata['count']) : 50;

	$search = isset($getdata['search']) && $getdata['search'] ? $getdata['search'] : false;

	$image_size = isset($getdata['size']) ? $getdata['size'] : 'thumbnail';
	if(!($image_size == 'thumbnail' || $image_size == 'full')) {
		die('error');
	}

	$limit = $count + $from;

	if ($search) {
		$the_query = new WP_Query(array(
			's' => $search,
			'post_type' => 'post',
			'post_status'=>'publish',
			'post_count' => $limit));
		$related_posts = $the_query->get_posts();
	} else {
		$related_posts = array();
		zem_rp_append_posts($related_posts, 'zem_rp_fetch_related_posts_v2', $limit);
		zem_rp_append_posts($related_posts, 'zem_rp_fetch_related_posts', $limit);
		zem_rp_append_posts($related_posts, 'zem_rp_fetch_random_posts', $limit);
	}

	if(function_exists('qtrans_postsFilter')) {
		$related_posts = qtrans_postsFilter($related_posts);
	}

	$response_list = array();

	foreach (array_slice($related_posts, $from) as $related_post) {
		$excerpt_max_length = $platform_options["excerpt_max_length"];
		$excerpt = $related_post->post_excerpt;
		if (!$excerpt) {
			$excerpt = strip_shortcodes(strip_tags($related_post->post_content));
		}
		if ($excerpt) {
			if (strlen($excerpt) > $excerpt_max_length) {
				$excerpt = zem_rp_text_shorten($excerpt, $excerpt_max_length);
			}
		}
		
		array_push($response_list, array(
				'id' => $related_post->ID,
				'url' => get_permalink($related_post->ID),
				'title' => $related_post->post_title,
				'excerpt' => $excerpt,
				'date' => $related_post->post_date,
				'comments' => $related_post->comment_count,
				'img' => zem_rp_get_post_thumbnail_img($related_post, $image_size)
			));
	}

	header('Content-Type: text/javascript');
	die(json_encode($response_list));
}
add_action('wp_ajax_zem_rp_load_articles', 'zem_rp_ajax_load_articles_callback');
add_action('wp_ajax_nopriv_zem_rp_load_articles', 'zem_rp_ajax_load_articles_callback');

function zem_rp_append_posts(&$related_posts, $fetch_function_name, $limit) {
	$options = zem_rp_get_options();

	$len = sizeof($related_posts);
	$num_missing_posts = $limit - $len;
	if ($num_missing_posts > 0) {
		$exclude_ids = array_map(create_function('$p', 'return $p->ID;'), $related_posts);

		$posts = call_user_func($fetch_function_name, $num_missing_posts, $exclude_ids);
		if ($posts) {
			$related_posts = array_merge($related_posts, $posts);
		}
	}
}

function zem_rp_fetch_posts_and_title() {
	$options = zem_rp_get_options();

	$limit = $options['max_related_posts'];
	$title = __($options["related_posts_title"],'zemanta_related_posts');

	$related_posts = array();

	zem_rp_append_posts($related_posts, 'zem_rp_fetch_related_posts_v2', $limit);
	zem_rp_append_posts($related_posts, 'zem_rp_fetch_related_posts', $limit);
	zem_rp_append_posts($related_posts, 'zem_rp_fetch_random_posts', $limit);

	if(function_exists('qtrans_postsFilter')) {
		$related_posts = qtrans_postsFilter($related_posts);
	}

	return array(
		"posts" => $related_posts,
		"title" => $title
	);
}

function zem_rp_get_next_post(&$related_posts, &$selected_related_posts, &$inserted_urls, &$special_urls, $default_post_type) {
	$post = false;

	while (!($post && $post->ID) && !(empty($related_posts) && empty($selected_related_posts))) {
		$post = array_shift($selected_related_posts);
		$post_type = $default_post_type;

		if ($post && $post->type) {
			$post_type = $post->type;
		}

		if (!$post || !$post->ID) {
			while (!empty($related_posts) && (!($post = array_shift($related_posts)) || isset($special_urls[get_permalink($post->ID)])));
		}
		if ($post && $post->ID) {
			$post_url = property_exists($post, 'post_url') ? $post->post_url : get_permalink($post->ID);
			if (isset($inserted_urls[$post_url])) {
				$post = false;
			} else {
				$post->type = $post_type;
			}
		}
	}

	if (!$post || !$post->ID) {
		return false;
	}

	$inserted_urls[$post_url] = true;

	return $post;
}

function zem_rp_text_shorten($text, $max_chars) {
	$shortened_text = mb_substr($text, 0, $max_chars - strlen(ZEM_RP_EXCERPT_SHORTENED_SYMBOL));
	$shortened_words = explode(" ", $shortened_text);
	$shortened_size = count($shortened_words);
	if ($shortened_size > 1) {
		$shortened_words = array_slice($shortened_words, 0, $shortened_size - 1);
		$shortened_text = implode(" ", $shortened_words);
	}
	return $shortened_text . ZEM_RP_EXCERPT_SHORTENED_SYMBOL; //'...';
}

function zem_rp_generate_related_posts_list_items($related_posts, $selected_related_posts) {
	$options = zem_rp_get_options();
	$platform_options = zem_rp_get_platform_options();
	$output = "";

	$limit = $options['max_related_posts'];

	$inserted_urls = array(); // Used to prevent duplicates
	$special_urls = array();

	foreach ($selected_related_posts as $post) {
		if (property_exists($post, 'post_url') && $post->post_url) {
			$special_urls[$post->post_url] = true;
		}
	}

	$default_post_type = empty($selected_related_posts) ? 'none' : 'empty';

	$image_size = ($platform_options['theme_name'] == 'pinterest.css') ? 'full' : 'thumbnail';
	for ($i = 0; $i < $limit; $i++) {
		$related_post = zem_rp_get_next_post($related_posts, $selected_related_posts, $inserted_urls, $special_urls, $default_post_type);
		if (!$related_post) {
			break;
		}

		if (property_exists($related_post, 'type')) {
			$post_type = $related_post->type;
		} else {
			$post_type = $default_post_type;
		}

		if (in_array($post_type, array('empty', 'none'))) {
			$post_id = 'in-' . $related_post->ID;
		} else {
			$post_id = 'ex-' . $related_post->ID;
		}

		$data_attrs = 'data-position="' . $i . '" data-poid="' . $post_id . '" data-post-type="' . $post_type . '"';

		$output .= '<li ' . $data_attrs . '>';

		$post_url = property_exists($related_post, 'post_url') ? $related_post->post_url : get_permalink($related_post->ID);

		$img = zem_rp_get_post_thumbnail_img($related_post, $image_size);
		if ($img) {
			$output .=  '<a href="' . $post_url . '" class="zem_rp_thumbnail">' . $img . '</a>';
		}

		if ($platform_options["display_publish_date"]){
			$dateformat = get_option('date_format');
			$output .= '<small class="wp_rp_publish_date">' . mysql2date($dateformat, $related_post->post_date) . '</small>';
		}

		$output .= '<a href="' . $post_url . '" class="zem_rp_title">' . wptexturize($related_post->post_title) . '</a>';

		if ($platform_options["display_comment_count"] && property_exists($related_post, 'comment_count')){
			$output .=  '<small class="wp_rp_comments_count"> (' . $related_post->comment_count . ')</small><br />';
		}

		if ($platform_options["display_excerpt"]){
			$excerpt_max_length = $platform_options["excerpt_max_length"];
			$excerpt = '';

			if ($related_post->post_excerpt){
				$excerpt = strip_shortcodes(strip_tags($related_post->post_excerpt));
			}
			if (!$excerpt) {
				$excerpt = strip_shortcodes(strip_tags($related_post->post_content));
			}

			if ($excerpt) {
				if (strlen($excerpt) > $excerpt_max_length) {
					$excerpt = zem_rp_text_shorten($excerpt, $excerpt_max_length);
				}
				$output .= '<small class="wp_rp_excerpt">' . $excerpt . '</small>';
			}
		}
		$output .=  '</li>';
	}

	return $output;
}

function zem_rp_should_exclude() {
	global $wpdb, $post;

	if (!$post || !$post->ID) {
		return true;
	}

	$options = zem_rp_get_options();

	if(!$options['exclude_categories']) { return false; }

	$q = 'SELECT COUNT(tt.term_id) FROM '. $wpdb->term_taxonomy.' tt, ' . $wpdb->term_relationships.' tr WHERE tt.taxonomy = \'category\' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tr.object_id = '. $post->ID . ' AND tt.term_id IN (' . $options['exclude_categories'] . ')';

	$result = $wpdb->get_col($q);

	$count = (int) $result[0];

	return $count > 0;
}

function zem_rp_head_resources() {
	global $post, $wpdb;

	if (zem_rp_should_exclude()) {
		return;
	}

	$meta = zem_rp_get_meta();
	$options = zem_rp_get_options();
	$platform_options = zem_rp_get_platform_options();

	$output = '';

	$tags = $wpdb->get_col("SELECT DISTINCT(label) FROM " . $wpdb->prefix . "zem_rp_tags WHERE post_id=$post->ID ORDER BY weight desc;", 0);
	if (!empty($tags)) {
		$post_tags = '[' . implode(', ', array_map(create_function('$v', 'return "\'" . urlencode(substr($v, strpos($v, \'_\') + 1)) . "\'";'), $tags)) . ']';
	} else {
		$post_tags = '[]';
	}
	$output .= "<script type=\"text/javascript\">\n" .
		"\twindow._zem_rp_post_id = '" . esc_js($post->ID) . "';\n" .
		"\twindow._zem_rp_thumbnails = " . ($platform_options['display_thumbnail'] ? 'true' : 'false') . ";\n" .
		"\twindow._zem_rp_post_title = '" . urlencode($post->post_title) . "';\n" .
		"\twindow._zem_rp_post_tags = {$post_tags};\n" .
		"\twindow._zem_rp_static_base_url = '" . esc_js(ZEM_RP_ZEMANTA_CONTENT_BASE_URL) . "';\n" .
		"\twindow._zem_rp_wp_ajax_url = '" . admin_url('admin-ajax.php') . "';\n" .
		"\twindow._zem_rp_plugin_version = '" . ZEM_RP_VERSION . "';\n" .
		"\twindow._zem_rp_num_rel_posts = '" . $options['max_related_posts'] . "';\n" .
		(current_user_can('edit_posts') ?
			"\twindow._zem_rp_admin_ajax_url = '" . admin_url('admin-ajax.php') . "';\n" .
			"\twindow._zem_rp_ajax_nonce = '" . wp_create_nonce("zem_rp_ajax_nonce") . "';\n" .
			"\twindow._zem_rp_plugin_static_base_url = '" . esc_js(plugins_url('static/' , __FILE__)) . "';\n" .
			"\twindow._zem_rp_erp_search = true;\n"
		: '') .
		"</script>\n";

	$static_url = plugins_url('static/', __FILE__);
	$theme_url = $static_url . ZEM_RP_STATIC_THEMES_PATH;

	if ($platform_options['theme_name'] !== 'plain.css' && $platform_options['theme_name'] !== 'm-plain.css') {
		$output .= '<link rel="stylesheet" href="' . $theme_url . $platform_options['theme_name'] . '?version=' . ZEM_RP_VERSION . '" />' . "\n";
	}
	if ($platform_options['custom_theme_enabled']) {
		$output .= '<style type="text/css">' . "\n" . $platform_options['theme_custom_css'] . "</style>\n";
	}

	if (current_user_can('edit_posts')) {
		wp_enqueue_style('zem_rp_edit_related_posts_css', $theme_url . 'edit_related_posts.css', array(), ZEM_RP_VERSION);
		wp_enqueue_script('zem_rp_edit_related_posts_js', $static_url . 'js/edit_related_posts.js', array('jquery'), ZEM_RP_VERSION);
	}

	if($platform_options['theme_name'] === 'm-stream.css') {
		wp_enqueue_script('zem_rp_infiniterecs', $static_url . ZEM_RP_STATIC_INFINITE_RECS_JS_FILE, array('jquery'), ZEM_RP_VERSION);
	}

	if($platform_options['theme_name'] === 'pinterest.css') {
		wp_enqueue_script('zem_rp_pinterest', $static_url . ZEM_RP_STATIC_PINTEREST_JS_FILE, array('jquery'), ZEM_RP_VERSION);
	}

	echo $output;
}

function zem_rp_get_selected_posts() {
	global $post;

	$selected_related_posts = get_post_meta($post->ID, '_zem_rp_selected_related_posts');
	if (empty($selected_related_posts)) {
		return array();
	}

	$selected_related_posts = $selected_related_posts[0];
	if (empty($selected_related_posts)) {
		return array();
	}

	$options = zem_rp_get_options();
	$limit = $options['max_related_posts'];

	return array_slice((array)$selected_related_posts, 0, $limit);
}

global $zem_rp_is_first_widget;
$zem_rp_is_first_widget = true;
function zem_rp_get_related_posts() {
	if (zem_rp_should_exclude()) {
		return;
	}

	global $post, $zem_rp_is_first_widget;

	$options = zem_rp_get_options();
	$platform_options = zem_rp_get_platform_options();
	$meta = zem_rp_get_meta();

	$posts_and_title = zem_rp_fetch_posts_and_title();
	$related_posts = $posts_and_title['posts'];
	$title = $posts_and_title['title'];

	$selected_related_posts = zem_rp_get_selected_posts();

	$related_posts_content = "";

	if (!$related_posts) {
		return;
	}

	$posts_footer = '';
	if (current_user_can($options['only_admins_can_edit_related_posts'] ? 'manage_options' : 'edit_posts')) {
		$posts_footer .= '<div class="zem_rp_footer" style="text-align: right;"><a class="zem_rp_edit" href="#" id="zem_rp_edit_related_posts">' .__('Edit Related Posts','zemanta_related_posts') .'</a></div>';
	}
	if ($options['display_zemanta_linky']) {
		$posts_footer .= '<div class="zem_rp_footer" style="text-align: right;">' .
				'<a class="zem_rp_backlink" style="color: #999; font-size: 11px; text-decoration: none;" target="_blank" href="http://www.zemanta.com/?related-posts" rel="nofollow">Zemanta</a>' .
			'</div>';
	}

	$css_classes = 'related_post zem_rp';
	$css_classes_wrap = str_replace(array('.css', '-'), array('', '_'), esc_attr('zem_rp_th_' . $platform_options['theme_name']));

	if ($related_posts) {
		$related_posts_lis = zem_rp_generate_related_posts_list_items($related_posts, $selected_related_posts);
		$related_posts_ul = '<ul class="' . $css_classes . '">' . $related_posts_lis . '</ul>';

		$related_posts_content = $title ? '<h3 class="related_post_title">' . $title . '</h3>' : '';
		$related_posts_content .= $related_posts_ul;
	}

	$first_id_attr = '';
	if ($zem_rp_is_first_widget) {
		$zem_rp_is_first_widget = false;
		$first_id_attr = 'id="zem_rp_first"';
	}

	$output = '<div class="zem_rp_wrap ' . $css_classes_wrap . '" ' . $first_id_attr . '>' .
				'<div class="zem_rp_content">' .
					$related_posts_content .
					$posts_footer .
				'</div>' .
			'</div>';

	return "\n" . $output . "\n";
}

function zemanta_related_posts() {
	echo zem_rp_get_related_posts();
}
