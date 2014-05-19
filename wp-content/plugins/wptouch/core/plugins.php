<?php

function wptouch_plugins_generate_hook_list( $wptouch_pro, $settings ) {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );
	$php_files = wptouch_get_all_recursive_files( WP_PLUGIN_DIR, '.php' );

	$plugin_whitelist = apply_filters( 'wptouch_plugin_whitelist', array( 'akismet', 'wptouch', 'wptouch-pro', 'wptouch-pro-image-optimizer', 'wptouch-pro-3' ) );

	$new_plugin_list = array();

	foreach( $php_files as $plugin_file ) {
		$path_info = explode( '/', $plugin_file );

		if ( count( $path_info ) > 2 ) {
			$plugin_slug = $path_info[1];

			if ( in_array( $plugin_slug, $plugin_whitelist ) ) {
				continue;
			}

			$plugin_file_path = WP_PLUGIN_DIR . $plugin_file;

			$contents = $wptouch_pro->load_file( $plugin_file_path );

			if ( !isset( $new_plugin_list[ $plugin_slug ] ) ) {
				$new_plugin_list[ $plugin_slug ] = new stdClass;
			}

			// Default actions
			if ( preg_match_all( "#add_action\([ ]*[\'\"]+(.*)[\'\"]+,[ ]*[\'\"]+(.*)[\'\"]+[ ]*(\s*[,]\s*+(.*))*\)\s*;#iU", $contents, $matches ) ) {
				for( $i = 0; $i < count( $matches[0] ); $i++ ) {
					if ( strpos( $matches[2][$i], ' ' ) === false ) {
						$info = new stdClass;
						$info->hook = $matches[1][$i];
						$info->hook_function = $matches[2][$i];

						if ( isset( $matches[4][$i] ) && $matches[4][$i] > 0 ) {
						    $info->priority = $matches[4][$i];
						} else {
						    $info->priority = false;
						}

						$new_plugin_list[ $plugin_slug ]->actions[] = $info;
					}
				}
			}

			// Default filters
			if ( preg_match_all( "#add_filter\([ ]*[\'\"]+(.*)[\'\"]+,[ ]*[\'\"]+(.*)[\'\"]+[ ]*(\s*[,]\s*+(.*))*\)\s*;#iU", $contents, $matches ) ) {
				for( $i = 0; $i < count( $matches[0] ); $i++ ) {
					if ( strpos( $matches[2][$i], ' ' ) === false ) {
						$info = new stdClass;
						$info->hook = $matches[1][$i];
						$info->hook_function = $matches[2][$i];

						if ( isset( $matches[4][$i] ) && $matches[4][$i] > 0 ) {
						    $info->priority = $matches[4][$i];
						} else {
						    $info->priority = false;
						}

						$new_plugin_list[ $plugin_slug ]->filters[] = $info;
					}
				}
			}
		}
	}

	// Create list of active plugins
	$active_plugins = get_option( 'active_plugins' );
	if ( !$active_plugins ) {
		$active_plugins = array();
	}

	// Check for network activated plugins
	if ( wptouch_is_multisite_enabled() ) {
		$active_site_plugins = get_site_option( 'active_sitewide_plugins' );
		if ( is_array( $active_site_plugins ) && count ( $active_site_plugins ) ) {
			foreach( $active_site_plugins as $key => $value ) {
				if ( !in_array( $key, $active_plugins ) ) {
					$active_plugins[] = $key;
				}
			}
		}
	}

	$active_plugin_names = array();
	if ( is_array( $active_plugins ) && count( $active_plugins ) ) {
		foreach( $active_plugins as $plugin ) {
			$name = substr( $plugin , 0, strpos( $plugin, DIRECTORY_SEPARATOR ) );

			$active_plugin_names[] = $name;
		}
	}

	$final_hook_list = array();
	if ( count( $new_plugin_list ) ) {
		// Filter based on this list
		$usable_plugins = array();
		foreach( $new_plugin_list as $name => $info ) {
			if ( in_array( $name, $active_plugin_names ) ) {
				$final_hook_list[ $name ] = $info;
			}
		}
	}

	$wptouch_pro->plugin_hooks = apply_filters( 'wptouch_plugin_exclusion_list', $final_hook_list );

	@ksort( $wptouch_pro->plugin_hooks );
	$settings->plugin_hooks = $wptouch_pro->plugin_hooks;

	$settings->save();
}

function wptouch_plugins_disable( $wptouch_pro, $settings ) {
	foreach( $settings->plugin_hooks as $name => $hook_info ) {
		if ( $name == 'ignore' ) {
			continue;
		}

		if ( isset( $settings->enabled_plugins[ $name ] ) && !$settings->enabled_plugins[ $name ]  ) {
			if ( isset( $hook_info->filters ) && count( $hook_info->filters ) ) {
				foreach( $hook_info->filters as $hooks ) {
					WPTOUCH_DEBUG( WPTOUCH_VERBOSE, "Disable filter [" . $hooks->hook . "] with function [" . $hooks->hook_function . "]" );
					if ( $hooks->priority ) {
						remove_filter( $hooks->hook, $hooks->hook_function, $hooks->priority );
					} else {
						remove_filter( $hooks->hook, $hooks->hook_function );
					}
				}
			}

			if ( isset( $hook_info->actions ) && count( $hook_info->actions ) ) {
				foreach( $hook_info->actions as $hooks ) {
					WPTOUCH_DEBUG( WPTOUCH_VERBOSE, "Disable action [" . $hooks->hook . "] with function [" . $hooks->hook_function . "]" );
					if ( $hooks->priority ) {
						remove_action( $hooks->hook, $hooks->hook_function, $hooks->priority );
					} else {
						remove_action( $hooks->hook, $hooks->hook_function );
					}
				}
			}
		}
	}
}

function wptouch_plugins_get_friendly_name( $wptouch_pro, $name ) {
	$plugin_file = WP_PLUGIN_DIR . '/' . $name . '/' . $name . '.php';
	if ( file_exists( $plugin_file ) ) {
		$contents = $wptouch_pro->load_file( $plugin_file );
		if ( $contents ) {
			if ( preg_match( "#Plugin Name: (.*)\n#", $contents, $matches ) ) {
				return $matches[1];
			}
		}
	}

	$all_files = $wptouch_pro->get_files_in_directory( WP_PLUGIN_DIR . '/' . $name, '.php' );
	if ( $all_files ) {
		foreach( $all_files as $some_file ) {
			if ( file_exists( $some_file ) ) {
				$contents = $wptouch_pro->load_file( $some_file );
				if ( $contents ) {
					if ( preg_match( "#Plugin Name: (.*)\n#", $contents, $matches ) ) {
						return $matches[1];
					}
				}
			}
		}
	}

	return str_replace( '_' , ' ', $name );
}