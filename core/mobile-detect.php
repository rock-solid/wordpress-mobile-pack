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
    	
    	function wmp_detect_device() { 
    		
    		// set load app variable
    		$load_app = false;
    		
    		require_once (WMP_PLUGIN_PATH.'libs/mobileesp/mdetect.php');
    		$uagentObj = new UAgentInfo();
    		
    		$is_tablet = $uagentObj->DetectTierTablet(); 
    		$is_mobile = $uagentObj->DetectMobileQuick();
    		$is_webkit = $uagentObj->DetectWebkit();
    		$is_windows_mobile = $uagentObj->DetectWindowsPhone8();
			$is_windows_tablet = $uagentObj->DetectWindowsTablet();

    		$is_firefox_os = $uagentObj->DetectFirefoxOsPhone();
			$is_firefox = $uagentObj->DetectFirefoxPhone();
    		
			$is_premium = false;
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '')
				$is_premium = true; 
			
		
			$load_app = false;
			// set load app variable to true
			if(!$is_premium && $is_mobile && !$is_tablet && ($is_webkit || $is_windows_mobile || $is_firefox_os || $is_firefox))
				$load_app = true;
			elseif($is_premium && (($is_mobile || $is_tablet) &&  ($is_webkit || $is_windows_mobile || $is_firefox_os || $is_firefox)|| $is_windows_tablet))
				$load_app = true; 
			
            // set load app cookie
            if ($load_app)
                setcookie("wmp_load_app", 1, time()+3600*7*24,'/');
    		
    		return $load_app;
    	}
		
		function wmp_is_tablet() {
			
			require_once(WMP_PLUGIN_PATH.'libs/mobileesp/mdetect.php');
    		$uagentObj = new UAgentInfo();
			
			return ($uagentObj->DetectTierTablet() || ($uagentObj->DetectWindowsTablet() && !$uagentObj->DetectWindowsPhone8()));
		}
		
    }
}