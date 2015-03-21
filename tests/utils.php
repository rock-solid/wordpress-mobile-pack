<?php

class WPMPTestsUtils {

	private static $UserAgent = "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3";
	
	public function make_request($request_url, $mobile_agent = true){
		
		// check if curl is enabled
		if (extension_loaded('curl')) {
			
			$response = array(
				'content' => '',
				'redirect' => ''
			);
			
			$send_curl = curl_init();
				
			// set curl options
			curl_setopt($send_curl, CURLOPT_URL, $request_url);
			
			if ($mobile_agent)
				curl_setopt($send_curl, CURLOPT_USERAGENT, self::$UserAgent);
				
			curl_setopt($send_curl, CURLOPT_HEADER, true);
			curl_setopt($send_curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($send_curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($send_curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($send_curl, CURLOPT_SSL_VERIFYHOST, false);
			$response['content'] = curl_exec($send_curl);
			
			// get request status
			$status = curl_getinfo($send_curl, CURLINFO_HTTP_CODE);
			curl_close($send_curl);
				
			 // line endings is the wonkiest piece of this whole thing
			$out = str_replace("\r", "", $response['content']);
			
			// only look at the headers
			$headers_end = strpos($out, "\n\n");
			if( $headers_end !== false ) { 
				$out = substr($out, 0, $headers_end);
			}   
			
				
			$headers = explode("\n", $out);
			
			foreach ($headers as $header) {
				if( substr($header, 0, 10) == "Location: " ) { 
					$target = substr($header, 10);
					$response['redirect'] = $target;
				}   
			}
			
			return $response;
		}
	}
	
	
	/**
	 *
	 * Return 301 or 302 redirect location for an url
	 *
	 */
	public function get_furl($url) {
		
		$furl = false;
	   
		// First check response headers
		$headers = get_headers($url);
	   
		// Test for 301 or 302
		if (preg_match('/^HTTP\/\d\.\d\s+(301|302)/',$headers[0])) {
			
			foreach($headers as $value) {
				if (substr(strtolower($value), 0, 9) == "location:"){
					$furl = trim(substr($value, 9, strlen($value)));
				}
			}
		}
		
		// Set final URL
		$furl = ($furl) ? $furl : $url;
		
		return $furl;
	}
}

