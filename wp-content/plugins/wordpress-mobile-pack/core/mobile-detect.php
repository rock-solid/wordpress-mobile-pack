<?php

class WPMobileDetect {


	/* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/
	
	function wmp_detect_device() { 
		
		// set load app variable
		$load_app = false;
		
		require_once (WMP_LIBS_DIR.'mobileesp/mdetect.php');
		$uagent_obj = new uagent_info();
		
		$is_tablet = $uagent_obj->DetectTierTablet();
		$is_mobile = $uagent_obj->DetectTierIphone();
		
		if($is_mobile && !$is_tablet) {
			
			// set load app cookie	
			setcookie("load_app", 1, time()+3600*7,'/');
			
			// set load app variable to true
			$load_app = true;	
		}
		
		return $load_app;
	}
	

	function wmp_make_writable($dir) {
		
		if(!file_exists($dir)){
			if (!mkdir($dir, 0777, true)) {//0777
				die('Failed to create folders...');
			}
		
		} else
			if(!chmod($dir,0777))
				die('Failed to create folders...');
	}


	function wmp_is_writable($dir) {
		
		return is_writable($dir);
	}


	
	 
}


