<?php

if ( ! class_exists( 'WPMobileDetect' ) ) {
    
    /**
     * WPMobileDetect
     * 
     * Main class for detecting the user's device and browser.
     * 
     */  
    class WPMobileDetect {
    
    
    	/* ----------------------------------*/
        /* Methods							 */
        /* ----------------------------------*/
    	
		/**
		 *
		 * Check the browser's user agent and return true if the device is a supported smartphone or tablet
		 *
		 */
    	function wmp_detect_device() { 
    		
    		// set load app variable
    		$load_app = false;
    		
			$is_wireless_device = 0;
			$is_tablet = 0;
			$is_supported_device = 0;
			$is_supported_browser = 0;
			
			$is_premium = 0;
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '')
				$is_premium = 1;
				
			require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.12/Mobile_Detect.php');
			$detect = new WPMP_Mobile_Detect();
			
    		if ($detect->isMobile() || $detect->isTablet())
				$is_wireless_device = 1;
				
			if ($detect->isTablet())
				$is_tablet = 1;
				
			if ($detect->is('iOS') || $detect->is('AndroidOS') || $detect->is('WindowsPhoneOS') ||  $detect->is('WindowsMobileOS')) {
				$is_supported_device = 1;
				
			} else {
				
				// Assume we have FirefoxOS, but this part should be replaced with a proper detection
				if ($detect->isMobile() && $detect->is('Firefox') && stripos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') === false)
					$is_supported_device = 1;    
			}
			
			if ($detect->is('WebKit') || $detect->is('Firefox') || ($detect->is('IE') && intval($detect->version('IE')) >= 10))
				$is_supported_browser = 1;
				
			// Assume the device is not Windows 8 / IE
			$is_IE_tablet = false;
			
			// Check user agent only for not detected devices (should exclude Windows phones)
			if ($is_wireless_device == 0){
					
				if (isset($_SERVER['HTTP_USER_AGENT'])){
					
					// Check if user agent is IE v>10 ex: Trident/6.0 or Trident/7.0 (for IE11) with Touch
					preg_match("@Trident/([0-9]{1,}[\.0-9]{0,}); Touch@", $_SERVER['HTTP_USER_AGENT'], $matches);
					
					// if IE version is equal or more than 10
					if(isset($matches[1]) && $matches[1] >= 6)
						$is_IE_tablet = true;
				}
			}
				
			if ($is_wireless_device && $is_supported_device && $is_supported_browser) {
					
				if ($is_tablet == 0 || $is_premium == 1){
					$load_app = true;
				}
				
			} elseif ($is_IE_tablet && $is_premium == 1) {
					
				$is_wireless_device = 1;
				$is_tablet = 1;
				$is_supported_device = 1;
				$is_supported_browser = 1;
				$load_app = true;
			}
    		
            // set load app cookie
			if ($load_app)
				setcookie("wmp_load_app", 1, time()+3600*7*24,'/');
    		
    		return $load_app;
    	}
		
		
		/**
		 *
		 * Check the browser's user agent and return true if the device is a supported tablet
		 *
		 */
		function wmp_is_tablet() {
			
			require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.12/Mobile_Detect.php');
			$detect = new WPMP_Mobile_Detect();
			
			$is_tablet = false;
			if ($detect->isTablet())
				$is_tablet = true;
				
			$is_IE_tablet = false;
			
			// Check user agent only for not detected devices (should exclude Windows phones)
			if (!$detect->is('WindowsPhoneOS')  && !$detect->is('WindowsMobileOS')){
					
				// Check if user agent is IE v>10 ex: Trident/6.0 or Trident/7.0 (for IE11) with Touch
				preg_match("@Trident/([0-9]{1,}[\.0-9]{0,}); Touch@", $_SERVER['HTTP_USER_AGENT'], $matches);
				
				// if IE version is equal or more than 10
				if (isset($matches[1]) && $matches[1] >= 6)
					$is_IE_tablet = true;
			}
			
			return ($is_tablet || $is_IE_tablet);
		}
    }
}