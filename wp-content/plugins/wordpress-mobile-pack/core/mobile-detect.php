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
    		$is_mobile = $uagentObj->DetectTierIphone();
    		$is_webkit = $uagentObj->DetectWebkit();
    		$is_windows_mobile = $uagentObj->DetectWindowsPhone8();
    		
    		if($is_mobile && !$is_tablet && ($is_webkit || $is_windows_mobile)) {
    			
    			// set load app cookie	
    			setcookie("wmp_load_app", 1, time()+3600*7*24,'/');
    			
    			// set load app variable to true
    			$load_app = true;	
    		}
    		
    		return $load_app;
    	}
    }
}