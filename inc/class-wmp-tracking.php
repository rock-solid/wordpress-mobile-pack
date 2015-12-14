<?php
/**
 *
 * This method is a simplified version of the tracking class used by Yoast's SEO plugin (https://yoast.com/wordpress/plugins/seo/).
 *
 * It tracks anonymous data about the Wordpress install. No credentials or identity information are sent.
 */

if ( ! defined( 'WMP_VERSION' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( ! class_exists( 'WMobilePack_Tracking' ) ) {
    /**
     *
     * NOTE: this functionality is opt-in. Disabling the tracking in the settings or saying no when asked will cause
     * this file to not even be loaded.
     *
     */
    class WMobilePack_Tracking {

        /**
         * Main tracking function.
         */
        public function tracking() {

            $transient_key = WMobilePack_Options::$transient_prefix.'tracking_cache';
            $data          = get_transient( $transient_key );

            // bail if transient is set and valid
            if ( $data !== false ) {
                return;
            }

            // Make sure to only send tracking data once a week
            set_transient( $transient_key, 1, WEEK_IN_SECONDS );

            // Start of Metrics
            global $blog_id, $wpdb;

            $hash = get_option('WPMP_Tracking_Hash', false );

            if ( ! $hash || empty( $hash ) ) {
                // create and store hash
                $hash = md5( site_url() );
                update_option('WPMP_Tracking_Hash', $hash );
            }

            $pts        = array();
            $post_types = get_post_types( array( 'public' => true ) );
            if ( is_array( $post_types ) && $post_types !== array() ) {
                foreach ( $post_types as $post_type ) {
                    $count             = wp_count_posts( $post_type );
                    $pts[ $post_type ] = $count->publish;
                }
            }
            unset( $post_types );

            $comments_count = wp_count_comments();

            $theme_data     = wp_get_theme();
            $theme          = array(
                'name'       => $theme_data->display( 'Name', false, false ),
                'theme_uri'  => $theme_data->display( 'ThemeURI', false, false ),
                'version'    => $theme_data->display( 'Version', false, false ),
                'author'     => $theme_data->display( 'Author', false, false ),
                'author_uri' => $theme_data->display( 'AuthorURI', false, false ),
            );
            $theme_template = $theme_data->get_template();
            if ( $theme_template !== '' && $theme_data->parent() ) {
                $theme['template'] = array(
                    'version'    => $theme_data->parent()->display( 'Version', false, false ),
                    'name'       => $theme_data->parent()->display( 'Name', false, false ),
                    'theme_uri'  => $theme_data->parent()->display( 'ThemeURI', false, false ),
                    'author'     => $theme_data->parent()->display( 'Author', false, false ),
                    'author_uri' => $theme_data->parent()->display( 'AuthorURI', false, false ),
                );
            } else {
                $theme['template'] = '';
            }
            unset( $theme_template );


            $plugins       = array();
            $active_plugin = get_option( 'active_plugins' );
            foreach ( $active_plugin as $plugin_path ) {
                if ( ! function_exists( 'get_plugin_data' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                }

                $plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );

                $slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
                $plugins[ $slug ] = array(
                    'slug'       => $slug,
                    'version'    => $plugin_info['Version'],
                    'name'       => $plugin_info['Name'],
                    'plugin_uri' => $plugin_info['PluginURI'],
                    'author'     => $plugin_info['AuthorName'],
                    'author_uri' => $plugin_info['AuthorURI'],
                );
            }
            unset( $active_plugins, $plugin_path );


            $data = array(
                'site'      => array(
                    'hash'      => $hash,
                    'version'   => get_bloginfo( 'version' ),
                    'multisite' => is_multisite(),
                    'users'     => $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ({$wpdb->users}.ID = {$wpdb->usermeta}.user_id) WHERE 1 = 1 AND ( {$wpdb->usermeta}.meta_key = %s )", 'wp_' . $blog_id . '_capabilities' ) ),
                    'lang'      => get_locale(),
                ),
                'pts'       => $pts,
                'comments'  => array(
                    'total'    => $comments_count->total_comments,
                    'approved' => $comments_count->approved,
                    'spam'     => $comments_count->spam,
                    'pings'    => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
                ),

                'theme'     => $theme,
                'plugins'   => array_values($plugins),

                'other_options'   => array(

                    'webserver_apache'            => isset($_SERVER) && isset( $_SERVER['SERVER_SOFTWARE'] ) && stristr( $_SERVER['SERVER_SOFTWARE'], 'apache' ) !== false ? 1 : 0,
                    'webserver_apache_version'    => function_exists( 'apache_get_version' ) ? apache_get_version() : 0,
                    'webserver_nginx'             => isset($_SERVER) && isset( $_SERVER['SERVER_SOFTWARE'] ) && stristr( $_SERVER['SERVER_SOFTWARE'], 'nginx' ) !== false ? 1 : 0,

                    'webserver_server_software'   => isset($_SERVER) && isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '',
                    'webserver_gateway_interface' => isset($_SERVER) && isset($_SERVER['GATEWAY_INTERFACE']) ? $_SERVER['GATEWAY_INTERFACE'] : '',
                    'webserver_server_protocol'   => isset($_SERVER) && isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '',

                    'php_version'                 => phpversion(),

                    'php_max_execution_time'      => ini_get( 'max_execution_time' ),
                    'php_memory_limit'            => ini_get( 'memory_limit' ),

                    'php_ctype_enabled'           => extension_loaded( 'ctype' ) ? 1 : 0,
                    'php_curl_enabled'            => extension_loaded( 'curl' ) ? 1 : 0
                )
            );

            $args = array(
                'body'      => array(
                    'items' => json_encode($data),
                ),
                'blocking'  => false,
                'sslverify' => false,
            );

            wp_remote_post(WMP_APPTICLES_TRACKING_SSL, $args);

        }
    } /* End of class */
} /* End of class-exists wrapper */

