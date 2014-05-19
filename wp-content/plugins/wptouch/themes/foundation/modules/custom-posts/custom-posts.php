<?php

add_filter( 'wptouch_setting_defaults_foundation', 'wptouch_custom_posts_default_settings' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'wptouch_custom_posts_render_theme_settings' );
add_filter( 'foundation_search_post_types', 'wptouch_custom_posts_add_to_search' );
add_filter( 'pre_get_posts', 'wptouch_custom_posts_pre_get_posts' );

function wptouch_custom_posts_add_to_search( $post_types ) {
	$settings = foundation_get_settings();
	
	$custom_post_types = wptouch_custom_posts_get_list();
	foreach( $custom_post_types as $custom_post_type ) {
		$setting_name = wptouch_custom_posts_get_name_for_post_type( $custom_post_type );
		if ( isset( $settings->$setting_name ) && $settings->$setting_name ) {
			$post_types[] = $custom_post_type;
		}
	}

	return $post_types;
}

function wptouch_custom_posts_get_list( $remove_defaults = true ) {
	$default_post_types = array( 'post', 'page', 'attachment', 'revision', 'nav_menu_item', 'tweet' );
	
	// Get the internal list
	$post_types = get_post_types();	
	
	if ( $remove_defaults ) {
		return apply_filters( 'wptouch_custom_posts_list', array_diff( $post_types, $default_post_types ) );
	} else {
		return apply_filters( 'wptouch_custom_posts_list', $post_types );
	}
}

function wptouch_custom_posts_get_name_for_post_type( $post_type ) {
	return 'show_custom_post_type_' . $post_type;
}

function wptouch_custom_posts_default_settings( $defaults ) {
	$defaults->enable_custom_post_types = false;

	$defaults->show_custom_post_taxonomy = false;
	
	$post_types = wptouch_custom_posts_get_list( true );
	if ( $post_types && count( $post_types )  ) {
		foreach( $post_types as $post_type ) {
			$setting_name = wptouch_custom_posts_get_name_for_post_type( $post_type );
			$defaults->$setting_name  = false;
		}
	}	
	
	return $defaults;
}

function wptouch_custom_posts_render_theme_settings( $page_options ) {
	$custom_post_settings = array();

	$post_types = wptouch_custom_posts_get_list( true );
	if ( count( $post_types ) ) {
		foreach( $post_types as $post_type ) {
			$setting = wptouch_add_setting(
				'checkbox',
				wptouch_custom_posts_get_name_for_post_type( $post_type ),
				sprintf( __( 'Enable %s', 'wptouch-pro' ), $post_type ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				FOUNDATION_SETTING_DOMAIN
			);

			$custom_post_settings[] = $setting;
		}

		wptouch_add_page_section(
			FOUNDATION_PAGE_GENERAL,
			__( 'Custom Post Support', 'wptouch-pro' ),
			'foundation-web-custom-post-type-support',
			array(
				wptouch_add_setting(
					'checkbox',
					'enable_custom_post_types',
					__( 'Enable custom post-type support', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),
				wptouch_add_setting(
					'checkbox',
					'show_custom_post_taxonomy',
					__( 'Show custom post taxonomy', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),				
			),			
			$page_options,
			FOUNDATION_SETTING_DOMAIN
		);	

		wptouch_add_page_section(
			FOUNDATION_PAGE_GENERAL,
			__( 'Custom Post Types', 'wptouch-pro' ),
			'foundation-web-custom-post-types',
			$custom_post_settings,				
			$page_options,
			FOUNDATION_SETTING_DOMAIN
		);					
	}

	return $page_options;
}

function wptouch_custom_posts_pre_get_posts( $query ) {
	// Only modify the custom post type information when a mobile theme is showing
	$settings = foundation_get_settings();
	if ( !$settings->enable_custom_post_types ) {
		return $query;
	}
	
	if ( is_attachment() ) {
		return $query;
	}
	
	// Right now only support custom post types on the home page and single post pages
	if ( ( is_single() && !is_page() ) || is_home() ) {	
		// Only employ this logic for when the mobile theme is showing
		if ( wptouch_is_mobile_theme_showing() ) {
			$settings = foundation_get_settings();
			
			$post_types = wptouch_custom_posts_get_list( true );
			if ( $post_types && count( $post_types )  ) {			
				$post_type_array = array();
				
				foreach( $post_types as $post_type ) {
					$setting_name = wptouch_custom_posts_get_name_for_post_type( $post_type );
					if ( isset( $settings->$setting_name ) && $settings->$setting_name ) {
						$post_type_array[] = $post_type;
					}
				}
			}			
			
			if ( count( $post_type_array ) ) {	
				// Determine the original post type in the query
				$original_post_type = false;			
				if ( isset( $query->queried_object ) ) {
					$original_post_type = $query->queried_object->post_type;
				} else if ( isset( $query->query_vars['post_type'] ) ) {
					$original_post_type = $query->query_vars['post_type'];
				}
					
				if ( $original_post_type ) {
					$page_for_posts = get_option( 'page_for_posts' );
					if ( isset( $query->queried_object_id ) && ( $query->queried_object_id == $page_for_posts ) ) {
						// we're on the posts page
						$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( array( 'post' ), $post_type_array ) );
					} else {
						if ( !is_array( $original_post_type ) ) {
							$original_post_type = array( $original_post_type );
						}				
						
						$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( $original_post_type, $post_type_array ) );
					}	
					
					$query->set( 'post_type', $custom_post_types );		
				} else {
					// We're on the home page or possibly another page for a normal site
					$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( array( 'post' ), $post_type_array ) );
					$query->set( 'post_type', $custom_post_types );	
				}
			}
		}
	}
	
	return $query;
}

function wptouch_custom_post_should_show( $current ) {
	global $post;
	$settings = wptouch_get_settings();
	
	$available_posts = wptouch_custom_posts_get_list();
	if ( array_key_exists( $post->post_type, $available_posts ) ) {
		// Show custom post types
		if ( $settings->show_custom_post_taxonomy && is_single() && !is_page() ) {
			$current = true;
		}
		
		if ( $settings->show_custom_post_taxonomy && !is_single() && !is_page() ) {
			$current = true;
		}
	}

	return $current;
}

function wptouch_custom_post_type_get_taxonomies( $post_type ) {
	$post_info = get_object_taxonomies( $post_type );
	
	return $post_info;
}

function wptouch_custom_posts_get_taxonomy( $tax_info = false ) {
	global $post;

	$taxonomies = wptouch_custom_post_type_get_taxonomies( $post->post_type );
	if ( $taxonomies && count( $taxonomies ) ) {
		foreach( $taxonomies as $taxonomy ) {
			$product_terms = wp_get_object_terms( $post->ID, $taxonomy );
			if ( $product_terms ) {
				$tax_object = get_taxonomy( $taxonomy );
				$tax_info[ $tax_object->labels->name ] = array();		
				
				foreach( $product_terms as $term ) {	
					$term_info = new stdClass;
					$term_info->name = $term->name;
					$term_info->link = get_term_link( $term->slug, $taxonomy );
					
					$tax_info[ $tax_object->labels->name ][] = $term_info;
				}
			}
		}
	}

	return $tax_info;
}
