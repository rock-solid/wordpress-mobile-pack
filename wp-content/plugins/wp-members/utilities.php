<?php
/**
 * WP-Members Utility Functions
 *
 * Handles primary functions that are carried out in most
 * situations. Includes commonly used utility functions.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014  Chad Butler
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler 
 * @copyright 2006-2014
 *
 * Functions included:
 * * wpmem_create_formfield
 * * wpmem_selected
 * * wpmem_chk_qstr
 * * wpmem_generatePassword
 * * wpmem_texturize
 * * wpmem_enqueue
 * * wpmem_do_excerpt
 * * wpmem_test_shortcode
 */


if ( ! function_exists( 'wpmem_create_formfield' ) ):
/**
 * Creates form fields
 *
 * Creates various form fields and returns them as a string.
 *
 * @since 1.8
 *
 * @param  string $name the name of the field
 * @param  string $type the field type
 * @param  string $value the default value for the field
 * @param  string $valtochk optional for comparing the default value of the field
 * @param  string $class optional for setting a specific CSS class for the field 
 * @return string $str the field returned as a string
 */
function wpmem_create_formfield( $name, $type, $value, $valtochk=null, $class='textbox' )
{
	switch( $type ) {

	case "checkbox":
		if( $class = 'textbox' ) { $class = "checkbox"; }
		$str = "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\"" . wpmem_selected( $value, $valtochk, $type ) . " />";
		break;

	case "text":
		$value = stripslashes( esc_attr( $value ) );
		$str = "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\" class=\"$class\" />";
		break;

	case "textarea":
		$value = stripslashes( esc_textarea( $value ) );
		if( $class == 'textbox' ) { $class = "textarea"; }
		$str = "<textarea cols=\"20\" rows=\"5\" name=\"$name\" id=\"$name\" class=\"$class\">$value</textarea>";
		break;

	case "password":
		$str = "<input name=\"$name\" type=\"$type\" id=\"$name\" class=\"$class\" />";
		break;

	case "hidden":
		$str = "<input name=\"$name\" type=\"$type\" value=\"$value\" />";
		break;

	case "option":
		$str = "<option value=\"$value\" " . wpmem_selected( $value, $valtochk, 'select' ) . " >$name</option>";
		break;

	case "select":
		if( $class == 'textbox' ) { $class = "dropdown"; }
		$str = "<select name=\"$name\" id=\"$name\" class=\"$class\">\n";
		foreach( $value as $option ) {
			$pieces = explode( '|', $option );
			$str = $str . "<option value=\"$pieces[1]\"" . wpmem_selected( $pieces[1], $valtochk, 'select' ) . ">" . __( $pieces[0], 'wp-members' ) . "</option>\n";
		}
		$str = $str . "</select>";
		break;

	}
	
	return $str;
}
endif;


if ( ! function_exists( 'wpmem_selected' ) ):
/**
 * Determines if a form field is selected (i.e. lists & checkboxes)
 *
 * @since 0.1
 *
 * @param  string $value
 * @param  string $valtochk
 * @param  string $type
 * @return string $issame
 */
function wpmem_selected( $value, $valtochk, $type=null )
{
	$issame = ( $type == 'select' ) ? ' selected' : ' checked';
	if( $value == $valtochk ){ return $issame; }
}
endif;


if ( ! function_exists( 'wpmem_chk_qstr' ) ):
/**
 * Checks querystrings
 *
 * @since 2.0
 *
 * @uses   get_permalink
 * @param  string $url
 * @return string $return_url
 */
function wpmem_chk_qstr( $url = null )
{
	$permalink = get_option( 'permalink_structure' );
	if( ! $permalink ) {
		if( ! $url ) { $url = get_option( 'home' ) . "/?" . $_SERVER['QUERY_STRING']; }
		$return_url = $url . "&amp;";
	} else {
		if( !$url ) { $url = get_permalink(); }
		$return_url = $url . "?";
	}
	return $return_url;
}
endif;


if ( ! function_exists( 'wpmem_generatePassword' ) ):
/**
 * Generates a random password 
 *
 * @since 2.0
 *
 * @return string the random password
 */
function wpmem_generatePassword()
{	
	return substr( md5( uniqid( microtime() ) ), 0, 7);
}
endif;


if ( ! function_exists( 'wpmem_texturize' ) ):
/**
 * Overrides the wptexturize filter
 *
 * Currently only used for the login form to remove the <br> tag that WP puts in after the "Remember Me"
 *
 * @since 2.6.4
 *
 * @param  string $content
 * @return string $new_content
 */
function wpmem_texturize( $content ) 
{
	$new_content = '';
	$pattern_full = '{(\[wpmem_txt\].*?\[/wpmem_txt\])}is';
	$pattern_contents = '{\[wpmem_txt\](.*?)\[/wpmem_txt\]}is';
	$pieces = preg_split( $pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE );

	foreach( $pieces as $piece ) {
		if( preg_match( $pattern_contents, $piece, $matches ) ) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize( wpautop( $piece ) );
		}
	}

	return $new_content;
}
endif;


if ( ! function_exists( 'wpmem_enqueue_style' ) ):
/**
 * Loads the stylesheet for tableless forms
 *
 * @since 2.6
 *
 * @uses wp_register_style
 * @uses wp_enqueue_style
 */
function wpmem_enqueue_style() {		
	$css_path = ( WPMEM_CSSURL != null ) ? WPMEM_CSSURL : WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ) . "css/wp-members.css";
	wp_register_style( 'wp-members', $css_path );
	wp_enqueue_style ( 'wp-members' );
}
endif;


if ( ! function_exists( 'wpmem_do_excerpt' ) ):
/**
 * Creates an excerpt on the fly if there is no 'more' tag
 *
 * @since 2.6
 *
 * @param  string $content
 * @return string $content
 */
function wpmem_do_excerpt( $content )
{	
	$arr = get_option( 'wpmembers_autoex' );
	
	/** is there already a 'more' link in the content? */
	$has_more_link = ( stristr( $content, 'class="more-link"' ) ) ? true : false;
	
	/** if auto_ex is on */
	if( $arr['auto_ex'] == true ) {
		
		/** build an excerpt if one does not exist */
		if( ! $has_more_link ) {
		
			$words = explode( ' ', $content, ( $arr['auto_ex_len'] + 1 ) );
			if( count( $words ) > $arr['auto_ex_len'] ) { array_pop( $words ); }
			$content = implode( ' ', $words );
			
			/** check for common html tags */
			$common_tags = array( 'i', 'b', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5' );
			foreach ( $common_tags as $tag ) {
				if( stristr( $content, '<' . $tag . '>' ) ) {
					$after = stristr( $content, '</' . $tag . '>' );
					$content = ( ! stristr( $after, '</' . $tag . '>' ) ) ? $content . '</' . $tag . '>' : $content;
				}
			}
		} 		
	}

	global $post, $more;
	/** if there is no 'more' link and auto_ex is on **/
	if( ! $has_more_link && ( $arr['auto_ex'] == true ) ) {
		// the default $more_link_text
		$more_link_text = __( '(more&hellip;)' );
		// the default $more_link
		$more_link = ' <a href="'. get_permalink( $post->ID ) . '" class="more-link">' . $more_link_text . '</a>';
		// apply the_content_more_link filter if one exists (will match up all 'more' link text)
		$more_link = apply_filters( 'the_content_more_link' , $more_link, $more_link_text );
		// add the more link to the excerpt
		$content = $content . $more_link;
	}
	
	/**
	 * Filter the auto excerpt.
	 *
	 * @since 2.8.1
	 * 
	 * @param string $content The excerpt.
	 */
	$content = apply_filters( 'wpmem_auto_excerpt', $content );
	
	/** return the excerpt */
	return $content;
}
endif;


if ( ! function_exists( 'wpmem_test_shortcode' ) ):
/**
 * Tests $content for the presence of the [wp-members] shortcode
 *
 * @since 2.6
 *
 * @global string $post
 * @uses   get_shortcode_regex
 * @return bool
 *
 * @example http://codex.wordpress.org/Function_Reference/get_shortcode_regex
 */
function wpmem_test_shortcode( $content, $tag )
{
	global $shortcode_tags; 
	if( array_key_exists( $tag, $shortcode_tags ) ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;

		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] )
				return true;
		}
	}
	return false;
}
endif;


/**
 * Sets an array of user meta fields to be excluded from update/insert.
 *
 * @since 2.9.3
 *
 * @param string $tag A tag so we know where the function is being used.
 */
function wpmem_get_excluded_meta( $tag )
{
	/**
	 * Filter the fields to be excluded when user is created/updated.
	 *
	 * @since 2.9.3
	 *
	 * @param array       An array of the field meta names to exclude.
	 * @param string $tag A tag so we know where the function is being used.
	 */
	return apply_filters( 'wpmem_exclude_fields', array( 'password', 'confirm_password', 'confirm_email', 'password_confirm', 'email_confirm' ), $tag );
}

/** End of File **/