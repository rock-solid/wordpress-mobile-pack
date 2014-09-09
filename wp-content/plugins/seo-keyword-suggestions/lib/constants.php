<?php
/**
 * Constants used by this plugin
 * 
 * @package HitTail
 * @author Derrick Reimer <service@hittail.com>
 * @version 1.0.3
 * @since 1.0.0
 */

// The current version of this plugin
if( !defined( 'WP_HITTAIL_VERSION' ) ) define( 'WP_HITTAIL_VERSION', '1.0.3' );

// The directory the plugin resides in
if( !defined( 'WP_HITTAIL_DIRNAME' ) ) define( 'WP_HITTAIL_DIRNAME', dirname( dirname( __FILE__ ) ) );

// The URL path of this plugin
if( !defined( 'WP_HITTAIL_URLPATH' ) ) define( 'WP_HITTAIL_URLPATH', WP_PLUGIN_URL . "/" . plugin_basename( WP_HITTAIL_DIRNAME ) );