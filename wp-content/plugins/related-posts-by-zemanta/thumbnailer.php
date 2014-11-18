<?php

/**
 * Init
 */

function zem_rp_add_image_sizes() {
	$platform_options = zem_rp_get_platform_options();
	add_image_size(ZEM_RP_THUMBNAILS_NAME, ZEM_RP_THUMBNAILS_WIDTH, ZEM_RP_THUMBNAILS_HEIGHT, true);
	if ($platform_options['theme_name'] == 'pinterest.css') {
		add_image_size(ZEM_RP_THUMBNAILS_PROP_NAME, ZEM_RP_THUMBNAILS_WIDTH, 0, false);
	}
	if ($platform_options['custom_size_thumbnail_enabled']) {
		add_image_size(ZEM_RP_THUMBNAILS_NAME, $platform_options['custom_thumbnail_width'], $platform_options['custom_thumbnail_height'], true);
	}
}
add_action('init', 'zem_rp_add_image_sizes');


/**
 * Settings - replace default thumbnail
 */

function zem_rp_upload_default_thumbnail_file() {
	if (empty($_FILES['zem_rp_default_thumbnail'])) {
		return new WP_Error('upload_error');
	}
	$file = $_FILES['zem_rp_default_thumbnail'];
	if(isset($file['error']) && $file['error'] === UPLOAD_ERR_NO_FILE) {
		return false;
	}

	if ($image_id = media_handle_upload('zem_rp_default_thumbnail', 0)) {
		$image_data = zem_rp_get_image_data($image_id);
		$platform_options = zem_rp_get_platform_options();

		$img_width = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_thumbnail_width'] : ZEM_RP_THUMBNAILS_WIDTH;
		$img_height = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_thumbnail_height'] : ZEM_RP_THUMBNAILS_HEIGHT;

		if ($image = zem_rp_get_image_with_exact_size($image_data, array($img_width, $img_height))) {
			$upload_dir = wp_upload_dir();
			return $upload_dir['url'] . '/' . $image['file'];
		}
	}

	return new WP_Error('upload_error');
}


/**
 * Cron - Thumbnail extraction
 */

function zem_rp_upload_attachment($url, $post_id) {
	/* Parts copied from wp-admin/includes/media.php:media_sideload_image */

	include_once(ABSPATH . 'wp-admin/includes/file.php');
	include_once(ABSPATH . 'wp-admin/includes/media.php');
	include_once(ABSPATH . 'wp-admin/includes/image.php');

	$tmp = download_url($url);
	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $url, $matches);

	if(! $matches) return;

	$file_array['name'] = sanitize_file_name(urldecode(basename($matches[0])));

	$file_array['tmp_name'] = $tmp;
	if (is_wp_error($tmp)) {
		@unlink($file_array['tmp_name']);
		return false;
	}

	$post_data = array(
		'guid' => $url,
		'post_title' => 'rp_' . $file_array['name'],
	);

	$attachment_id = media_handle_sideload($file_array, $post_id, null, $post_data);
	if (is_wp_error($attachment_id)) {
		@unlink($file_array['tmp_name']);
		return false;
	}

	$attach_data = wp_get_attachment_metadata($attachment_id);
	$platform_options = zem_rp_get_platform_options();
	$min_width = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_size_thumbnail_width'] : ZEM_RP_THUMBNAILS_WIDTH;
	$min_height = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_size_thumbnail_height'] : ZEM_RP_THUMBNAILS_HEIGHT;

	if (!$attach_data || $attach_data['width'] < $min_width || $attach_data['height'] < $min_height) {
		wp_delete_attachment($attachment_id);
		return false;
	}

	return $attachment_id;
}

function zem_rp_get_image_from_img_tag($post_id, $url, $img_tag) {
	if (($attachment_id = zem_rp_attachment_url_to_postid($url)) || ($attachment_id = zem_rp_img_html_to_post_id($img_tag))) {
		if (zem_rp_update_attachment_id($attachment_id)) {
			return $attachment_id;
		}
	}

	return zem_rp_upload_attachment($url, $post_id);
}

function zem_rp_actually_extract_images_from_post_html($post) {
	$content = $post->post_content;

	if (!preg_match_all('#' . zem_rp_get_tag_regex('img') . '#i', $content, $matches) || empty($matches)) {
		return false;
	}

	$html_tags = $matches[0];
	$attachment_id = false;

	if(count($html_tags) == 0) {
		return false;
	}
	array_splice($html_tags, 10);

	foreach ($html_tags as $html_tag) {
		if (preg_match('#src=([\'"])(.+?)\1#is', $html_tag, $matches) && !empty($matches)) {
			$url = $matches[2];

			$attachment_id = zem_rp_get_image_from_img_tag($post->ID, $url, $html_tag);
			if ($attachment_id) {
				break;
			}
		}
	}

	return $attachment_id;
}

function zem_rp_update_attachment_id($attachment_id) {
	include_once(ABSPATH . 'wp-admin/includes/image.php');

	$img_path = get_attached_file($attachment_id);
	if (!$img_path) { return false; }

	$attach_data = wp_generate_attachment_metadata($attachment_id, $img_path);
	wp_update_attachment_metadata($attachment_id, $attach_data);

	return $attachment_id;
}

function zem_rp_cron_do_extract_images_from_post($post_id, $attachment_id) {
	// Prevent multiple thumbnail extractions for a single post
	if (get_post_meta($post_id, '_zem_rp_image', true) !== '') { return; }

	$post_id = (int) $post_id;
	$attachment_id = (int) $attachment_id;
	$post = get_post($post_id);

	if ($attachment_id) {
		$new_attachment_id = zem_rp_update_attachment_id($attachment_id);
	} else {
		$new_attachment_id = zem_rp_actually_extract_images_from_post_html($post);
	}

	if ($new_attachment_id) {
		update_post_meta($post_id, '_zem_rp_image', $new_attachment_id);
	} else {
		update_post_meta($post_id, '_zem_rp_image', 'empty');
	}
}
add_action('zem_rp_cron_extract_images_from_post', 'zem_rp_cron_do_extract_images_from_post', 10, 2);

function zem_rp_extract_images_from_post($post, $attachment_id=null) {
	//WP quirk: posts can have an image, but still no attachment
	//if(empty($post->post_content) && !$attachment_id) { return; }
	if(empty($post->post_content) ) { return; }

	delete_post_meta($post->ID, '_zem_rp_image');
	wp_schedule_single_event(time(), 'zem_rp_cron_extract_images_from_post', array($post->ID, $attachment_id));
}


/**
 * Update images on post save
 */

function zem_rp_post_save_update_image($post_id) {
	$post = get_post($post_id);

	if(empty($post->post_content) || $post->post_status !== 'publish' || $post->post_type === 'page' || $post->post_type === 'attachment' || $post->post_type === 'nav_menu_item') {
		return;
	}

	delete_post_meta($post->ID, '_zem_rp_image');

	zem_rp_get_post_thumbnail_img($post);
}
add_action('save_post', 'zem_rp_post_save_update_image');


/**
 * Get thumbnails when post is displayed
 */

function zem_rp_get_img_tag($src, $alt, $size=null) {
	if (!$size || !is_array($size)) {
		$size = array(ZEM_RP_THUMBNAILS_WIDTH, ZEM_RP_THUMBNAILS_HEIGHT);
	}
	$size_attr = ($size[0] ? ('width="' . $size[0] . '" ') : '');
	if ($size[1]) {
		$size_attr .= 'height="' . $size[1] . '" ';
	}
	return '<img src="'. esc_attr($src) . '" alt="' . esc_attr($alt) . '" '.$size_attr.' />';
}

function zem_rp_get_default_thumbnail_url($seed = false, $size = 'thumbnail') {
	$options = zem_rp_get_options();
	$upload_dir = wp_upload_dir();

	if ($options['default_thumbnail_path']) {
		return $options['default_thumbnail_path'];
	} else {
		if ($seed) {
			$next_seed = rand();
			srand($seed);
		}
		$file = rand(0, ZEM_RP_THUMBNAILS_DEFAULTS_COUNT - 1) . '.jpg';
		if ($seed) {
			srand($next_seed);
		}
		return plugins_url('/static/thumbs/' . $file, __FILE__);
	}
}

function zem_rp_get_image_with_exact_size($image_data, $size) {
	# Partially copied from wp-include/media.php image_get_intermediate_size and image_downsize
	if (!$image_data) { return false; }

	$platform_options = zem_rp_get_platform_options();
	$img_url = wp_get_attachment_url($image_data['id']);
	$img_url_basename = wp_basename($img_url);

	// Calculate exact dimensions for proportional images
	if (!$size[0]) { $size[0] = (int) ($image_data['data']['width'] / $image_data['data']['height'] * $size[1]); }
	if (!$size[1]) { $size[1] = (int) ($image_data['data']['height'] / $image_data['data']['width'] * $size[0]); }

	if (!$image_data['data']['sizes']) {
		$w = $image_data['data']['width'];
		$h = $image_data['data']['height'];

		$thumb_width = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_thumbnail_width'] : ZEM_RP_THUMBNAILS_WIDTH;
		$thumb_height = $platform_options['custom_size_thumbnail_enabled'] ? $platform_options['custom_thumbnail_height'] : ZEM_RP_THUMBNAILS_HEIGHT;

		if ($w == $thumb_width && $h == $thumb_height) {
			$file = explode("/", $image_data['data']['file']);
			$file = $file[count($file) - 1];
			$img_url = str_replace($img_url_basename, wp_basename($file), $img_url);
			return array(
				'url' => $img_url,
				'file' => $file,
				'width' => $w,
				'height' => $h
			);
		}
	}

	foreach ($image_data['data']['sizes'] as $_size => $data) {
		// width and height can be both string and integers. WordPress..
		if (($size[0] == $data['width']) && ($size[1] == $data['height'])) {
			$file = $data['file'];
			$img_url = str_replace($img_url_basename, wp_basename($file), $img_url);
			return array(
				'url' => $img_url,
				'file' => $data['file'],
				'width' => $data['width'],
				'height' => $data['height']
			);
		}
	}

	return false;
}

function zem_rp_get_image_data($image_id) {
	if (!$image_id || is_wp_error($image_id)) { return false; }

	$imagedata = wp_get_attachment_metadata($image_id);
	if (!$imagedata || !is_array($imagedata) || !isset($imagedata['sizes']) || !is_array($imagedata['sizes'])) {
		return false;
	}

	return array(
		'id' => $image_id,
		'data' => $imagedata
	);
}

function zem_rp_get_attached_img_url($related_post, $size) {
	$extracted_image = get_post_meta($related_post->ID, '_zem_rp_image', true);
	if ($extracted_image === 'empty') { return false; }

	$image_data = zem_rp_get_image_data((int)$extracted_image);
	if (!$image_data && $extracted_image) {
		// image_id in the db is incorrect
		delete_post_meta($related_post->ID, '_zem_rp_image');
	}

	if (!$image_data && has_post_thumbnail($related_post->ID)) {
		$image_data = zem_rp_get_image_data(get_post_thumbnail_id($related_post->ID));
	}

	if (!$image_data && function_exists('get_post_format_meta') && function_exists('img_html_to_post_id')) {
		// WP 3.6 Image post format. Check wp-includes/media.php:get_the_post_format_image for the reference.
		$meta = get_post_format_meta($related_post->ID);
		if (!empty($meta['image'])) {
			if (is_numeric($meta['image'])) {
				$image_id = absint($meta['image']);
			} else {
				$image_id = img_html_to_post_id($meta['image']);
			}
			$image_data = zem_rp_get_image_data($image_id);
		}
	}

	if (!$image_data) {
		zem_rp_extract_images_from_post($related_post);
		return false;
	}

	if ($img_src = zem_rp_get_image_with_exact_size($image_data, $size)) {
		return $img_src['url'];
	}

	zem_rp_extract_images_from_post($related_post, $image_data['id']);
	return false;
}

function zem_rp_get_thumbnail_size_array($size) {
	$platform_options = zem_rp_get_platform_options();
	if (!$size || $size === 'thumbnail') {
		if ($platform_options['custom_size_thumbnail_enabled']) {
			return array($platform_options['custom_thumbnail_width'], $platform_options['custom_thumbnail_height']);
		}
		return array(ZEM_RP_THUMBNAILS_WIDTH, ZEM_RP_THUMBNAILS_HEIGHT);
	}
	if ($size == 'full') {
		return array(ZEM_RP_THUMBNAILS_WIDTH, 0);
	}
	if (is_array($size)) {
		return $size;
	}
	return false;
}

function zem_rp_get_post_thumbnail_img($related_post, $size = null, $force = false) {
	$options = zem_rp_get_options();
	$platform_options = zem_rp_get_platform_options();

	if (!($platform_options["display_thumbnail"] || $force)) {
		return false;
	}

	$post_title = wptexturize($related_post->post_title);

	if (property_exists($related_post, 'thumbnail')) {
		return zem_rp_get_img_tag($related_post->thumbnail, $post_title, $size);
	}

	$size = zem_rp_get_thumbnail_size_array($size);
	if (!$size) { return false; }

	if ($options['thumbnail_use_custom']) {
		$thumbnail_src = get_post_meta($related_post->ID, $options["thumbnail_custom_field"], true);

		if ($thumbnail_src) {
			return zem_rp_get_img_tag($thumbnail_src, $post_title, $size);
		}
	}

	$attached_img_url = zem_rp_get_attached_img_url($related_post, $size);
	if ($attached_img_url) {
		return zem_rp_get_img_tag($attached_img_url, $post_title, $size);
	}

	return zem_rp_get_img_tag(zem_rp_get_default_thumbnail_url($related_post->ID, $size), $post_title, $size);
}

function zem_rp_process_latest_post_thumbnails() {
	$latest_posts = get_posts(array('numberposts' => ZEM_RP_THUMBNAILS_NUM_PREGENERATED_POSTS));
	foreach ($latest_posts as $post) {
		zem_rp_get_post_thumbnail_img($post);
	}
}



/**
 * Helpers
 * Mostly! copied from WordPress 3.6 wp-includes/media.php and functions.php
 */

function zem_rp_get_tag_regex( $tag ) {
	if ( empty( $tag ) )
		return;
	return sprintf( '<%1$s[^<]*(?:>[\s\S]*<\/%1$s>|\s*\/?>)', tag_escape( $tag ) ); // Added the last ?
}

function zem_rp_img_html_to_post_id( $html, &$matched_html = null ) {
	$attachment_id = 0;

	// Look for an <img /> tag
	if ( ! preg_match( '#' . zem_rp_get_tag_regex( 'img' ) .  '#i', $html, $matches ) || empty( $matches ) )
		return $attachment_id;

	$matched_html = $matches[0];

	// Look for attributes.
	if ( ! preg_match_all( '#class=([\'"])(.+?)\1#is', $matched_html, $matches ) || empty( $matches ) )
		return $attachment_id;

	$img_class = $matches[2][0];

	if ( ! $attachment_id && ! empty( $img_class ) && false !== strpos( $img_class, 'wp-image-' ) )
		if ( preg_match( '#wp-image-([0-9]+)#i', $img_class, $matches ) )
			$attachment_id = absint( $matches[1] );

	return $attachment_id;
}

function zem_rp_attachment_url_to_postid( $url ) {
	global $wpdb;
	if ( preg_match( '#\.[a-zA-Z0-9]+$#', $url ) ) {
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' " .
			"AND guid = %s", $url ) );

		if ( ! empty( $id ) )
			return (int) $id;
	}

	return 0;
}
