<?php
if ( ! function_exists( 'mopr_check_pagination' ) ) {
	/**
	 * Checks if there is pagination
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	function mopr_check_pagination() {
		global $wp_query;

		$max_num_pages = $wp_query->max_num_pages;

		if ( $max_num_pages > 1 ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'mopr_check_permalink' ) ) {
	/**
	 * Checks if the blog has permalinks enabled.
	 * If true, we need to display ? in the view/post comments links before the comment=true variable is declared
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	function mopr_check_permalink() {
		$permalink = get_option( 'permalink_structure' );

		if ( $permalink == '' ) {
			echo '&amp;';
		} else {
			echo '?';
		}
	}
}

if ( ! function_exists( 'mopr_comment_id' ) ) {
	/**
	 * Returns the current comment id
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	function mopr_comment_id() {
		global $id;
		echo $id;
	}
}

if ( ! function_exists( 'mopr_create_thumbnail' ) ) {
	/**
	 * Create a thumbnail from the specified image url
	 *
	 * @package MobilePress
	 * @since 1.2
	 */
	function mopr_create_thumbnail( $image_src, $auto_scale = TRUE, $image_width = NULL, $image_height = NULL ) {
		if ( $auto_scale ) {
			if ( isset( $_SESSION['MOPR_MOBILE_BROWSER'] ) && $_SESSION['MOPR_MOBILE_BROWSER'] == 'touch') {
				$image_width = '300';
			} else {
				$image_width = '160';
			}

			$thumbnail = WP_CONTENT_URL .'/plugins/mobilepress/libraries/timthumb.php?src='. $image_src .'&w='. $image_width .'';
		} else {
			$thumbnail = WP_CONTENT_URL .'/plugins/mobilepress/libraries/timthumb.php?src='. $image_src .'&w='. $image_width .'&h='. $image_height .'';
		}

		return $thumbnail;
	}
}

if ( ! function_exists( 'mopr_thumbnails' ) ) {
	/**
	 * Creates thumbnails for all images in a post / page
	 *
	 * @package MobilePress
	 * @since 1.2
	 */
	function mopr_thumbnails( $content ) {
		if ( preg_match_all( '#<img.+?src=\"(.*?)\"(.*?) \/>#s', $content, $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$image_url		= $match[1];
				$image_thumb	= '<img src="'. mopr_create_thumbnail( $image_url ) .'" />';

				$content = preg_replace( '#<img.+?src=\"'.$image_url.'\"(.*?) \/>#s', $image_thumb, $content );
			}
		}

		return $content;
	}
}
?>