<?php

class WPMobileDetect {


	/* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/
	
	function wmp_detect_device() { 
		
		// set load app variable
		$load_app = false;
		
		require_once (WMP_PLUGIN_PATH.'libs/mobileesp/mdetect.php');
		$uagent_obj = new uagent_info();
		
		$is_tablet = $uagent_obj->DetectTierTablet();
		$is_mobile = $uagent_obj->DetectTierIphone();
		
		if($is_mobile && !$is_tablet) {
			
			// set load app cookie	
			setcookie("wmp_load_app", 1, time()+3600*7*24,'/');
			
			// set load app variable to true
			$load_app = true;	
		}
		
		return $load_app;
	}
}