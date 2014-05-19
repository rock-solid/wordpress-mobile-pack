<?php

define( 'BNC_API_VERSION', '3.6' );
define( 'BNC_API_URL', 'http://api.bravenewcode.com/v/' . BNC_API_VERSION );
define( 'BNC_API_TIMEOUT', 10 );

class BNCAPI {
	var $bncid;
	var $license_key;
	var $time_variance;
	var $credentials_invalid;
	var $server_down;
	var $response_code;
	var $attempts;
	var $might_have_license;

	function BNCAPI( $bncid, $license_key ) {
		$this->bncid = $bncid;
		$this->license_key = $license_key;
		$this->credentials_invalid = false;
		$this->server_down = false;
		$this->response_code = 0;
		$this->attempts = 0;

		// Cache the server time
		$this->time_variance = get_transient( 'wptouch_pro_server_time' );
		if ( false === $this->time_variance ) {
			$this->time_variance = $this->get_server_time();
			set_transient( 'wptouch_pro_server_time', $this->time_variance, 3600 );
		}

		if ( $this->bncid && $this->license_key ) {
			$this->might_have_license = true;
		} else {
			$this->might_have_license = false;
		}
	}

	function might_have_license() {
		return $this->might_have_license;
	}

	function get_server_time() {
		$result = $this->do_api_request( 'server', 'get_time' );
		if ( $result && isset( $result[ 'time' ] ) ) {
			return $result[ 'time' ] - time();
		}
	}

	function do_api_request( $method, $command, $params = array(), $do_auth = false ) {
		$url = BNC_API_URL . "/{$method}/{$command}/";

		// Always use the PHP serialization method for data
		$params[ 'format' ] = 'php';

		/*
		if ( !$this->bncid || !$this->license_key ) {
			return false;
		}
		*/

		if ( $do_auth && $this->might_have_license ) {
			// Add the timestamp into the request, offseting it by the difference between this server's time and the BNC server's time
			$params[ 'timestamp' ] = time() + $this->time_variance;

			// Sort the parameters
			ksort( $params );

			// Generate a string to use for authorization
			$auth_string = '';
			foreach( $params as $key => $value ) {
				$auth_string = $auth_string . $key . $value;
			}

			WPTOUCH_DEBUG( WPTOUCH_INFO, 'Auth string [' . $auth_string . '], Key [' . $this->license_key . ']' );

			// Create the authorization hash using the server nonce and the license key
			$params[ 'auth' ] = md5( $auth_string . $this->license_key );
		}

        $body_params = array();
        foreach( $params as $key => $value ) {
        	$body_params[] = $key . '=' . urlencode( $value );
        }
        $body = implode( '&', $body_params );

        $options = array( 'method' => 'POST', 'timeout' => BNC_API_TIMEOUT, 'body' => $body );
        $options['headers'] = array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
            'Content-Length' => strlen( $body ),
            'User-Agent' => 'WordPress/' . get_bloginfo("version") . '/WPtouch-Pro/' . WPTOUCH_VERSION,
            'Referer' => get_bloginfo("url")
        );

        $this->attempts++;
        $raw_response = wp_remote_request( $url, $options );
        if ( !is_wp_error( $raw_response ) ) {

        	if ( $raw_response['response']['code'] == 200 ) {
        		$result = unserialize( $raw_response['body'] );

        		$this->response_code = $result['code'];
        		return $result;
        	} else {
        		WPTOUCH_DEBUG( WPTOUCH_WARNING, "Unable to connect to server. Response code is " . $raw_response[ 'response' ][ 'code' ] );
        		return false;
        	}
        } else {
       		$this->server_down = true;
       		return false;
        }
	}

	function get_response_code() {
		return $this->response_code;
	}

	function get_proper_server_name() {
		$server_name = $_SERVER['HTTP_HOST'];
		if ( strpos( $server_name, ':' ) !== false ) {
			$server_params = explode( ':', $server_name );

			return $server_params[0];
		} else {
			return $server_name;
		}
	}

	function verify_site_license( $product_name ) {
		// only check for a real license if they have a  license key
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'product_name' => $product_name
		);

		$result = $this->do_api_request( 'user', 'verify_license', $params, true );

		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}

	function check_api() {
		$params = array(
			'site' => $this->get_proper_server_name(),
			'product_name' => 'wptouch-pro-3',
			'product_version' => WPTOUCH_VERSION,
			'bncid_temp' => $this->bncid
		);

		$result = $this->do_api_request( 'check', 'api', $params, false );

		if ( $result and $result['status'] == 'ok' ) {
			if ( isset( $result[ 'result' ] ) ) {
				return $result[ 'result' ];
			}
		}

		return false;
	}

	function get_all_available_themes() {
		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'current_version' => WPTOUCH_VERSION
		);

		$result = false;
		if ( $this->might_have_license ) {
			$result = $this->do_api_request( 'themes', 'get_available', $params, true );
		} else {
			$result = $this->do_api_request( 'themes', 'get_available', $params, false );
		}

		if ( $result and $result['status'] == 'ok' ) {
			return $result[ 'result' ][ 'themes' ];
		}

		return false;
	}

	function get_all_available_addons() {

		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'current_version' => WPTOUCH_VERSION
		);

		$result = false;
		if ( $this->might_have_license ) {
			$result = $this->do_api_request( 'addons', 'get_available', $params, true );
		} else {
			$result = $this->do_api_request( 'addons', 'get_available', $params, false );
		}

		if ( $result and $result['status'] == 'ok' ) {
			return $result[ 'result' ][ 'addons' ];
		}

		return false;
	}

	function get_total_licenses( $product_name ) {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $product_name
		);

		$result = $this->do_api_request( 'user', 'get_license_count', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return $result['result']['count'];
		}

		return false;
	}

	function get_product_version( $product_name ) {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'product_name' => $product_name
		);

		$result = $this->do_api_request( 'products', 'get_version', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return $result['result']['product'];
		}

		return false;
	}

	function user_list_licenses( $product_name ) {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $product_name
		);

		$result = $this->do_api_request( 'user', 'list_licenses', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return $result['result'];
		}

		return false;
	}

	function user_add_license( $product_name ) {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $product_name,
			'site' => $this->get_proper_server_name()
		);

		$result = $this->do_api_request( 'user', 'add_license', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}
}
